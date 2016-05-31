<?php

    define('RUNTIME_TEST', 'true');

    require_once 'config/app_config.php';
    require_once CONFIG_CORE_DIR . DS . 'core.php';
    require_once CONFIG_CORE_DIR . DS . 'autoload.php';
    require_once CONFIG_CORE_DIR . DS . 'request.php';
    require_once CONFIG_CORE_DIR . DS . 'css.php';

    /**
     * Show debug content
     * @param $array mixed type variable to show it
     * @param string $title Title of screened variable
     * @param bool|false $return_output true = return output, false = print output
     * @return string
     */
    function dbg($array, $title = "DEBUG REPORT", $return_output = false)
    {
        if(CONFIG_DEBUG) {
            $html = '';

            if(!headers_sent())
                $html .= '<meta charset="UTF-8" />';

            $html .= "<hr size=\"1\">"
                ."<b>".$title."...</b><br>"
                ."<pre>"
                .print_r($array, true)
                ."</pre>"
                ."<hr size=\"1\">"
            ;

            if($return_output)
                return($html);
            else
                print($html);
        }
    }


    $core = new Runtime\core();
    $core->process();
    $core->done();
