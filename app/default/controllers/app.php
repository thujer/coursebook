<?php

    namespace Runtime\App\Controller;

    use \Runtime\controller;

    if(!defined('RUNTIME_TEST'))
        die('Unauthorized access !');
    
    /**
     * Class Runtime
     * Application IO processing
     * @author Tomas Hujer
     * @version 1.04.140920
     */
    class app extends controller {

        /**
         * Application start
         */
        function __construct() {
        }


        /**
         * Application processing
         * @param $uri_params
         * @return string
         */
        function process(&$uri_params) {

            $s_controller_raw = $uri_params['controller'];

            if(empty($s_controller_raw))
                $s_controller_raw = 'homepage';

            $s_controller = "\\Runtime\\App\\Controller\\" . $s_controller_raw;

            $s_file_controller = CONFIG_APP_CONTROLLERS . DS . $s_controller_raw . '.php';
            if (file_exists($s_file_controller)) {
                include($s_file_controller);
            }

            if (class_exists($s_controller)) {

                $s_action_raw = $uri_params['action'];

                if(empty($s_action_raw))
                    $s_action_raw = 'default';

                $s_action = $s_action_raw . 'Action';
                $a_item = isset($uri_params['a_item']) ? $uri_params['a_item'] : array();

                $o_controller = new $s_controller($s_controller_raw, $s_action_raw, $a_item);

                if (method_exists($o_controller, $s_action)) {
                    return $o_controller->$s_action();
                } else {
                    return "Action " . str_replace('Action', '', $s_action) . " not exists !" . BR;
                }
            }

            return false;
        }

    }
