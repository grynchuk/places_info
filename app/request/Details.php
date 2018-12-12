<?php

namespace app\request;

/**
 * Description of Details
 *
 * @author Анатолий
 */
class Details extends BaseRequest{
    
    private $placeId;
    
    protected function getParams() {
        return [
            'placeid' => $this->placeId
        ];
    }
    
    protected function getMidUrl() {        
        return'/maps/api/place/details/json';
    }
    
    function setPlaceId($placeId) {
       
        $this->placeId = $placeId;
        return $this;
    }
    
    function getId() {
        return $this->placeId;
    }
    
    
}

