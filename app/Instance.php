<?php
namespace app;
use app\Container,
    app\loader\Cities,
    app\loader\Places;
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
            
            $p = new Places(); 
            $p->setCities($c->data)
            ->process();
        } catch (\Exception $ex) {
            $this->logMessage($ex->getMessage());
        }
    }
    
    private function logMessage($mess) {
    echo PHP_EOL , $mess , PHP_EOL;      
    }
}
