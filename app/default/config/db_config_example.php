<?php

namespace Runtime\App\Config;

/**
 * Class db_config
 */
class db_config
{
    /**
     * Return database login credentials
     */
    protected static function get_config()
    {
        $config = new \stdClass();
        $config->db_host = "localhost";
        $config->db_name = "";
        $config->db_user = "";
        $config->db_pass = "";
        return $config;
    }

}

