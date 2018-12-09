<?php
namespace app;
use app\Container,
    app\loader\Cities;
/**
 * Description of Instance
 *
 * @author Анатолий
 */

class Instance { 
    
    public function __construct($config) {
        var_dump($config);
        Container::setConfig($config);
    }
    
    public function run() {
        try {
            $c = new Cities();
            $c->process();
            var_dump($c->data); 
        } catch (Exception $ex) {
            $this->logMessage($ex->getMessage());
        }
    }
    
    private function logMessage($mess) {
    echo PHP_EOL , $mess , PHP_EOL;      
    }
}
