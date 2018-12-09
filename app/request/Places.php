<?php

namespace app\request;

/**
 * Description of Cities
 *
 * @author Анатолий
 */
class Places extends BaseRequest{
    
    private $lat, $lng, $pagetoken;
    
    protected function getParams() {
        
        if ( ! $this->pagetoken) {
            $params = [
                'location' => $this->lat . ',' . $this->lng, 
                'radius' => 40000,     
                'keyword' => 'Сінево',
                'language' => 'ru'
            ];
        } else {
            $params = ['pagetoken'=> $this->pagetoken];
        } 
        
        return $params;
    }
    
    protected function getMidUrl() {
        return'/maps/api/place/nearbysearch/json';
    }

    function setLng($lng) {       
        $this->lng = $lng;
        return $this;
    }
    
    function setPagetoken($token) {       
        $this->pagetoken = $token;
        return $this;
    }
    
    function setLat($lat) {       
        $this->lat = $lat;
        return $this;
    }
    
    function getId() {
        return $this->city;
    }
    
    
}


