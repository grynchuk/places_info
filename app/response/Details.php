<?php

namespace app\response;

/**
 * Description of Cities
 *
 * @author Анатолий
 */
class Details extends BaseResponse {

    public  $reviews = 0,
            $photos = 0,
            $rating,
            $place_id,
            $formatted_address,
            $name,
            $url;
    
    protected function buildData() {
        if ( ! $this->rowData['result']) {
            throw new \Exception('Not found');
        }
        
        foreach ($this->getExpFields() as $field)
        {
          if( ! isset($this->rowData['result'][$field])) continue;
          
          if (in_array($field, ['reviews','photos']) ) {
              $this->$field = count($this->rowData['result'][$field]);
          }else{
              $this->$field = $this->rowData['result'][$field];
          }
        }       
    
    }
    
    protected function getExpFields() {
      return ['reviews','rating', 'photos', 'formatted_address', 'name','url'];  
    }
    
    
  
    
}
