<?php
namespace app\response;

/**
 * Description of BaseResponse
 *
 * @author Анатолий
 */
abstract class BaseResponse {
    
    protected $rowData , $isMulti;
    
        
    public function parse(\GuzzleHttp\Psr7\Response $resp) {               
        
        $this->rowData = json_decode(
                $resp->getBody()
                ->getContents(),
                TRUE
        );
               
        if (! $this->rowData) {
            throw new Exception('Empty Response');
        }
        
        if ($this->rowData['status'] !== "OK") {
            throw new \Exception('Error : ' .$this->rowData['status']);
        }
        
        $this->buildData();        
    }
    
    public function toArray() {
        return $this->isMulti 
               ? $this->toArrayMulti()
               : $this->toArraySingle();
    }
    
    protected function toArrayMulti() {
        $ress = [];        
        foreach($this->data as $val) {            
            $res = [];
            foreach($this->getExpFields() as $fields) {
                $res[$fields] = $val[$fields];
            }
            $ress[] = $res;
        }
        return $ress;
    }
    
    protected function toArraySingle() {
        $res = [];        
        foreach($this->getExpFields() as $fields) {
            $res[$fields] = $this->$fields;
        }
        return $res;
    }
    
    protected abstract function buildData();
    
    protected abstract function getExpFields();    
    
}
