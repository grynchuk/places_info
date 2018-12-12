<?php

namespace app\store;

use app\request\BaseRequest,
    app\response\BaseResponse;    
/**
 * Description of baseStore
 *
 * @author Анатолий
 */
abstract class baseStore {
   
    protected function getStoreAble() {        
        $res = [];
        foreach($this->getObjects() as $obj)
        {            
            $req = $obj['req'];
            $resp = $obj['data'];                        
            $this->setItem($req, $resp, $res);
        }    
        return $res;
    }
    
    private function setItem(BaseRequest $req, BaseResponse $resp, &$data) {
        $data[$req->getId()] = $resp->toArray();
    }
    
    public function decorateWithData($data) {
        $this->decoData = $data;
        return $this;
    }
    
    abstract public function store();
    abstract protected function getObjects();
}
