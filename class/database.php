<?php

    namespace Runtime;

    if(!defined('RUNTIME_TEST'))
        die('Unauthorized access !');

    require_once CONFIG_APP_CONFIG.DS."db_config.php";


    /**
     * Class database
     * @version 1.10.130215
     * @author: Tomas Hujer
     * @licence Copyright (C) 2010 - 2014 Tomas Hujer. All rights reserved.
     *
     * History
     *
     * 1.09.120515  - přidána podpora uložených procedur
     *
     * 1.10.130215  - doplněna metoda call_stored_proc podporující multiresult
     *
     */
    class database extends App\Config\db_config
    {
        public  $connection     = null;
        public  $connected      = false;
        public  $query_string   = null;
        protected static $instance = null;

        function __construct()
        {
        }

        /**
         * Return or create singleton instance
         */
        public static function get_instance() {

            if(self::$instance instanceof self)
                return self::$instance;
            else {
                self::$instance = new self();
                return self::$instance;
            }
        }


        /**
         * Generate connection string
         * @return string
         */
        protected function get_connection_string() {

            $c = $this->get_config();
            $str = "mysql://{$c->db_user}:{$c->db_pass}@{$c->db_host}/{$c->db_name};charset=utf8";
            return $str;
        }


        /**
         * Call stored procedure with params and retrieve multiresult
         * @param $name array stored procedure name
         * @param $a_params array stored procedure parameters
         * @return array of result objects
         */
        public function call_stored_proc($name, $a_params) {

            if(!defined('LN'))
                define('LN', "\n");

            $c = $this->get_config();

            try {
                $o_db = new \mysqli($c->db_host, $c->db_user, $c->db_pass, $c->db_name);

                $o_db->query('SET CHARACTER SET utf8');
                $o_db->set_charset('utf8');
                $o_db->query("SET NAMES `utf8`");
            } catch(\Exception $e) {
                echo "Can't connect to database ".$c->db_name.' !';
                return false;
            }

            $s_params = '';

            $i = 0;
            foreach($a_params as $param) {
                if($i++)
                    $s_params .= ', ';
                $s_params .= "'$param'";
            }

            $query = " CALL $name($s_params);".LN;

            $a_output = array();
            $a_result = array();

            if(mysqli_multi_query($o_db, $query)) {
                do {
                    $result = mysqli_store_result($o_db);

                    /*if(!empty($result)) {*/
                        if(is_object($result) || is_array($result))
                        {
                            while ($row = $result->fetch_object())
                                $a_result[] = $row;
                        }
                        else
                            $a_result = $result;

                        $a_output[] = array(
                            'num_rows' => $result->num_rows,
                            'current_field' => $result->current_field,
                            'field_count' => $result->field_count,
                            'result' => $a_result,
                            'error' => $o_db->error,
                            'query' => $query
                        );

                        unset($a_result);

                        if(is_object($result))
                                $result->free_result();
                        
                    //}
                }
                while(mysqli_more_results($o_db) && mysqli_next_result($o_db)); 
            } else {
                $a_output[] = array(
                    'error' => $o_db->error,
                    'query' => $query
                );
            }

            $o_db->close();

            return $a_output;

        }


        /**
         * Example of JS Stored procedure wrapper
         * @param o_conn
         * @param s_proc_name
         * @param o_param
         * @param cb
         */
        /*
        protected function mysql_proc(o_conn, s_proc_name, o_param, cb) {

            var sql = "CALL ws_core_parameters ('" + s_proc_name + "', '" + o_mysql.db + "');";
            o_conn.query(sql, function (err, rows) {
                if (err) {
                    console.log(sql);
                    throw err;
                }

                var a_params = rows[0][0].param_list.toString().trim().split(',\r\n');
                var s_proc_call = 'CALL '+s_proc_name+' (';

                var b_first = true;
                a_params.forEach(function(s_param_raw) {
                    var a_param = s_param_raw.split(' ');
                    var s_param_io = a_param[0];
                    var s_param_name = a_param[1].substring(1);
                    var s_param_type = a_param[2].replace(')', '').replace('(', ' ').split(' ')[0];

                    var a_value = [];

                    if(s_param_io == 'IN') {

                        if((s_param_name in o_param) === true) {

                            var s_value = o_param[s_param_name];
                            //console.log(s_value, s_param_type, s_param_name);
                            if(s_value === undefined) {
                                s_value = 'NULL';
                                s_param_type = '';
                            }

                            switch(s_param_type) {
                                case 'int': a_value.push(s_value); break;
                                case 'datetime':
                                case 'varchar':
                                case 'text':
                                    a_value.push("'"+s_value+"'");
                                    break;
                                default: a_value.push(s_value);
                            }

                        } else {
                            a_value.push('NULL');
                        }

                        a_value.forEach(function(s_value) {
                            if(!b_first) {
                                s_proc_call += ',';
                            }
                            b_first = false;
                            s_proc_call += s_value;
                        });
                    }


                });

                s_proc_call += ');';

                o_conn.query(s_proc_call, function (err, rows, sql) {
                    cb(err, rows, sql);
                });
            });
        }
        */

    }
