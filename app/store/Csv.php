<?php
namespace app\store;

use app\Container;

/**
 * Description of File
 *
 * @author Анатолий
 */
class Csv extends baseStore {
    private $objs = [],
            $config, 
            $fileName,
            $stored,
            $stringFields;
    
    public function __construct() {
        $this->config = Container::getConfig();        
    }
    
    public function store() {
        $this->stored = $this->getStoreAble();
        
        $fp = fopen($this->fileName, 'w');

        foreach ($this->stored as $key => $fields) {
            $fields['id'] = $key;
            $this->prepareString($fields);
            fputcsv($fp, $fields, ',');
        }

        fclose($fp);
        
        return $this;
    }
    
    public function setStringFields($f) {
        $this->stringFields = $f;
        return $this;
    }

    protected function prepareString(&$fields) {
        
        foreach ($this->stringFields as $str) {
            $fields[$str] = iconv('UTF-8','windows-1251//IGNORE', $fields[$str]); 
        }
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
