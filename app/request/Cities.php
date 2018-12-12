<?php

namespace app\request;

/**
 * Description of Cities
 *
 * @author Анатолий
 */
class Cities extends BaseRequest{
    
    private $city;
    
    protected function getParams() {
        return [
            'address' => $this->city . ' , Украина',
            'language' => 'ru'
        ];
    }
    
    protected function getMidUrl() {
        return'/maps/api/geocode/json';
    }
    
    function setCity($city) {
       
        $this->city = $city;
        return $this;
    }
    
    function getId() {
        return $this->city;
    }
    
    
}
