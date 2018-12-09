<?php
namespace app;
/**
 * Description of config
 *
 * @author Анатолий
 */
class Container {
    
    private static $config = [];    
    
    public static function setConfig($config){
        self::$config = $config;
    }
    
    public static function getConfig(){
        return (object) self::$config; 
    }
    
    
}
