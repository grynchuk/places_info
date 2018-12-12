<?php

namespace app\response;

/**
 * Description of Cities
 *
 * @author Анатолий
 */
class Places extends BaseResponse {

    protected $data = [];
            
    
    protected function buildData() {
        $this->isMulti = TRUE;        
        if ( ! $this->rowData['results']) {
            throw new \Exception('Not found');
        }        
        foreach ($this->rowData['results'] as $result) {
            $this->data[] = [
                'place_id'=> $result['place_id'],
                'name' => $result['name'],
                'vicinity'=> $result['vicinity'],
                'rating'=> $result['rating']
            ];
        }

    }
    
    protected function getExpFields() {
      return ['place_id', 'name'];  
    } 
    
    public function getNextPageToken(){
       
        return isset($this->rowData['next_page_token'])
             ? $this->rowData['next_page_token']
             : NULL;
    }
  
}
