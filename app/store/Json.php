<?php

namespace app\store;

use app\Container;

/**
 * Description of json
 *
 * @author Анатолий
 */
class Json extends BaseStore {
    
    private $objs = [],
            $config, 
            $fileName,
            $stored;
    
    public function __construct() {
        $this->config = Container::getConfig();        
    }
    
    public function store() {
        $this->stored = $this->getStoreAble();
        file_put_contents($this->fileName, json_encode($this->stored));
        return $this;
    }
    
    public function setObjects($objs)
    {               
        $this->objs = $objs;
        return $this;
    }

    public function setFileName($fileName) {
        $this->fileName = $fileName; 
        return $this;
    }
    
    public function getStored() {
        return $this->stored;
    }
    
    protected function getObjects() {
        return $this->objs; 
    }
    
    public function getFormFile() {        
        
        if( ! file_exists($this->fileName)) return NULL;
            
        $data = json_decode(file_get_contents($this->fileName), TRUE);      
        
        return $data;
    }
    
    
    
}
