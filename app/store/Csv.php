<?php
namespace app\store;

use app\Container;

/**
 * Description of File
 *
 * @author Анатолий
 */
class Csv extends baseStore {
    private $separator = ',',
            $isHeader = true,
            $objs = [],
            $config, 
            $fileName,
            $stored,
            $stringFields=[];
    
    public function __construct() {
        $this->config = Container::getConfig();        
    }
    
    public function store() {
        $this->stored = $this->getStoreAble();
        
        $fp = fopen($this->fileName, 'w');

        
        foreach ($this->stored as $key => $fields) {          
                                
                if (is_array(array_values($fields)[0])) {
                    
                   foreach ($fields as $field) {
                       
                       $field['id'] = $key;  
                       $this->setHeaderIfNeeded($fp, $field);   
                       $this->prepareString($fp, $field);
                       $this->putData($fp, $field);
                   } 
                } else {
                    $fields['id'] = $key;
                    $this->setHeaderIfNeeded($fp, $fields);
                    $this->prepareString($fp, $fields);
                    $this->putData($fp, $fields);    
                }
                
        }
            
        fclose($fp);
        
        return $this;
    }
    
    private function putData($fp, $data) {
         if (isset($this->decoData[$data['id']])){
             $data += $this->decoData[$data['id']];
         }
        
         fputcsv($fp, $data,$this->separator);
    }
    
    function setHeaderIfNeeded($fp, $field){
        if (! $this->isHeader) return;
        $this->isHeader = false; 
        fputcsv($fp, array_keys($field), $this->separator);
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
        $this->objs = array_merge($this->objs,$objs);
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
