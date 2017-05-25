<?php

namespace Seregahere\WPPostsManipulate\Base;

class Singleton
{
    protected static $instances = array();

    /**
     * Method to access a singleton.
     *
     * @return static
     */
    public static function getInstance()
    {
        global $wpdb;
        if (is_object($wpdb) && method_exists($wpdb, 'get_results')) {
            $className = get_called_class();
            if (!isset(static::$instances[$className])) {
                static::$instances[$className] = new $className();
            }

            return static::$instances[$className];
        } else {
            throw new \Exception('Wordpress Environment is not loaded! Add require_once("[path_to_wp_installation]/wp-load.php") to your script!');
        }
    }

    protected function __construct()
    {
    }

    protected function __clone()
    {
    }
}

