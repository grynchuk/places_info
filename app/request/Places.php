<?php

namespace app\request;

/**
 * Description of Cities
 *
 * @author Анатолий
 */
class Places extends BaseRequest{
    
    private $lat, $lng, $pagetoken, $city;
    
    protected function getParams($lang = 'en') {
        
        if ( ! $this->pagetoken) {
            $params = $this->getParamLang($lang);
        } else {
            $params = ['pagetoken'=> $this->pagetoken];
        } 
        
        return $params;
    }
    
   
    
    private function getParamLang() {
        $params = [
          self::LANG_EN => [
                'location' => $this->lat . ',' . $this->lng, 
                'radius' => 40000,     
                'keyword' => 'Sinevo'
               ],
          self::LANG_UA => [
                'location' => $this->lat . ',' . $this->lng, 
                'radius' => 40000,
                'language' => 'ua', 
                'keyword' => 'Сінево'
          ],
          self::LANG_RU => [
                'location' => $this->lat . ',' . $this->lng, 
                'radius' => 40000,
                'language' => 'ru', 
                'keyword' => 'Синэво'
          ]  
        ];
        var_dump($this->lang);
        return $params[$this->lang]; 
    }
    
    
    protected function getMidUrl() {
        return'/maps/api/place/nearbysearch/json';
    }

    function setLang($lang) {
        $this->lang = $lang;
        return $this;
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
    
    function setCity($city) {       
        $this->city = $city;
        return $this;
    }
    
    function getId() {
        return $this->city . '_' . $this->pagetoken . '_' . time()  ;
    }
    
    
}


