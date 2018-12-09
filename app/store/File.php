<?php
namespace app\store;

use app\Container;

/**
 * Description of File
 *
 * @author Анатолий
 */
class File extends baseStore {
    private $objs = [],
            $config, 
            $fileName,
            $stored;
    
    public function __construct() {
        $this->config = Container::getConfig();        
    }
    
    public function store() {
        $this->stored = $this->getStoreAble();
        var_dump($this->stored);
        
        file_put_contents($this->fileName, $this->generateString());
        return $this;
    }
    
    protected  function generateString() {
        $str = '';
        foreach ($this->stored as $key => $val) {
            $str .= 'id : ' . $key . ' ' ;
            foreach($val as $prop =>$val) {
                 $str .= " $prop :  $val   " ;
            }
            $str .= PHP_EOL;  
        }
        return $str;
    }

    protected function prepareString($str) {
        return $str;
    }
    

    public function setObjects($objs)
    {               
        $this->objs = $objs;
        return $this;
    }

    public function setFileName($fileName) {
        $this->fileName = $fileName ; 
        return $this;
    }
    
    
    protected function getObjects() {
        return $this->objs; 
    }
    
}
