<?php

namespace app\response;

/**
 * Description of Cities
 *
 * @author Анатолий
 */
class Cities extends BaseResponse {

    public $lat,
           $lng, 
           $address; 
    
    protected function buildData() {
        if ( ! $this->rowData['results']) {
            throw new \Exception('Not found');
        }
        
        $this->setAddres()
            ->setLat()
            ->setLng();        
    
    }
    
    protected function getExpFields() {
      return ['lat', 'lng', 'address'];  
    } 
  
    
    private function setAddres() {
        if ( ! isset($this->rowData['results'][0]['formatted_address']))
        {
            throw new Exception( 'cant get Address');
        }
        
        $this->address = $this->rowData['results'][0]['formatted_address'];
        return $this;
    }
    
    private function setLat() {
        if ( ! isset($this->rowData['results'][0]['geometry']['location']['lat']))
        {
            throw new Exception( 'cant get Lat');
        }
        $this->lat = $this->rowData['results'][0]['geometry']['location']['lat'];
        
        return $this;
    }
    
    private function setLng() {
        if ( ! isset($this->rowData['results'][0]['geometry']['location']['lng']))
        {
            throw new Exception( 'cant get Lat');
        }
        
        $this->lng = $this->rowData['results'][0]['geometry']['location']['lng'];
        
        return $this;        
    }
    
}
