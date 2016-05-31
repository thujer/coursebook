<?php

    namespace Runtime;

    //! Dynamic generated CSS styles

    header("Content-Type: text/css; charset=utf-8;");

    define('DIRECT_LOAD', true);

    require_once "config/app_config.php";
    require_once "class/request.php";
    require_once "class/css.php";

    if(CONFIG_DEBUG) {
        error_reporting(E_ALL ^ E_NOTICE);
        ini_set('display_errors',   true);
        ini_set('html_errors',      true);
    }

    $file = request::get_var('id');
    if(file_exists($file))
    {
        ob_start();
        include $file;
        $css = ob_get_contents();
        ob_end_clean();
    }
    else
        $css = "/* <not found: ".$file."> */".LN;

    print $css;

    unset($css);

