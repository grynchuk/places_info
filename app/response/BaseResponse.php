<?php
namespace app\response;

/**
 * Description of BaseResponse
 *
 * @author Анатолий
 */
abstract class BaseResponse {
    
    protected $rowData;
    
    public function parse(\GuzzleHttp\Psr7\Response $resp) {               
        
        $this->rowData = json_decode(
                $resp->getBody()
                ->getContents(),
                TRUE
        );
               
        if (! $this->rowData) {
            throw new Exception('Empty Response');
        }
        $this->buildData();        
    }
    
    public function toArray() {
        $res = [];
        foreach($this->getExpFields() as $fields) {
            $res[$fields] = $this->$fields;
        }
        return $res;
    }
    
    protected abstract function buildData();
    
    protected abstract function getExpFields();    
    
}
