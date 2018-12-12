<?php
namespace app\loader;
use app\Container,     
    GuzzleHttp\Psr7\Request,    
    app\response\Places as resp,
    app\request\Places as req,
    app\store\Json,
    app\store\File,
    app\store\Csv;

/**
 * Description of places
 *
 * @author Grynchuk
 */
class Places extends BaseLoader {
    
    
    private $config,
            $fileName,
            $pagetoken = FALSE,            
            $langPos = 0,
            $langSeq = [
                req::LANG_EN,
                req::LANG_UA,
                req::LANG_RU
            ];
    
    public $data = [],
           $uniqueData = [] ;
    
    public function __construct() {        
        $this->config = Container::getConfig();                
    }    
    
    private function setFileNames(){
        $this->fileName =  $this->config->data_folder . DIRECTORY_SEPARATOR .  'places_'.$this->langSeq[$this->langPos].'.json';
        $this->textFileName =  $this->config->data_folder . DIRECTORY_SEPARATOR .  'places_'.$this->langSeq[$this->langPos].'.txt';
        $this->csvFileName =  $this->config->data_folder . DIRECTORY_SEPARATOR .  'places_'.$this->langSeq[$this->langPos].'.csv';
        return $this; 
    }
    
    public function process()
    {
        $this->processEn()
             ->processRu()
             ->processUa()
             ->setUnique();
    }
    
    private function setUnique() {
        $this->uniqueData = [];
        
        foreach ($this->langSeq as $lang) {
           $this->mergeData($this->getUniquePlaceIds($this->data[$lang]), $lang);
        }
        
        return $this;
    }
    
    private function mergeData($data, $lang){
        foreach($data As $key => $val){
            if (isset($this->uniqueData[$key])){
                $this->uniqueData[$key]['name'][$lang] = $val['name'];
            } else {
                $this->uniqueData[$key] = $val;
                $this->uniqueData[$key]['name']= [$lang => $val['name']];
            }
        }
    }
    
    
    private function processEn(){
        $this->langPos = 0;
        return $this->setFileNames()->processOne();
    }
    
    private function processUa(){
        $this->langPos = 1;
        return $this->setFileNames()->processOne();
    }
    
    public function processRu(){
        $this->langPos = 2;
        return $this->setFileNames()->processOne();
    }
    
    private function processOne() {
        
        $store = new Json();
        $store->setFileName($this->fileName);
        $this->data[
            $this->langSeq[$this->langPos]
        ] = $store->getFormFile();
        
        
        
        $file =  new Csv();
        $file->setFileName($this->csvFileName)   ;
        
        if ($this->data[
            $this->langSeq[$this->langPos]
        ]) {        
           return $this;    
        }    
        
        foreach ($this->cities as $city => $data) {                        

            $this->pagetoken = FALSE;
            $success = $this->processCity($city, $data['lat'], $data['lng']);

            echo $city, PHP_EOL, count($success), PHP_EOL;
           
            $store->setObjects($success);
            $file->setObjects($success);                 
        }
        
        $this->data = $store->store()
                ->getStored();            
        $file->store();
        
        return $this;
    }
    
    private function getUniquePlaceIds($all) {
        $res = [];
        foreach (array_values($all) as $loadKey => $data) {
            foreach($data as $item) {
                //$item['loadKey'] = $loadKey; 
                $res[$item['place_id']]=$item;
            }
        }
        return $res;
    }

    public function setCities($data)
    {
        $this->cities = $data;
        return $this;
    }
    
    protected function processCity($city,$lat, $lng) {
        
        $this->lat = $lat;
        $this->lng = $lng;
        $this->city = $city;
        $total = [];        
        try {
            while ($this->pagetoken OR $this->pagetoken === FALSE ) {

                list($success, $errors) = parent::processSync();
                
                sleep(2);            
                if ($errors OR ! $success) {
                   throw new \Exception('Error on process Cities : ' . json_encode($errors));    
                }

                $total[] = $success;
            }
        } catch(\Exception $e) {
            
          if ($e->getCode() != 1) {
                
                throw $e;
            }
        }

        return $total;
    }
    

    
    protected function getRequests()
    {           
        $reqObj = new req();
        $reqObj->setLang($this->langSeq[$this->langPos])
            ->setCity($this->city)                
            ->setLat($this->lat)
            ->setLng($this->lng)
            ->setPagetoken($this->pagetoken);
        
        $req = new Request('GET', $reqObj->getUrl());
        
        return [$req, $reqObj];
    }
    
    protected function getResponse($response) {
        $resp = new resp();
        $resp->parse($response);
        $this->pagetoken = $resp->getNextPageToken();        
        echo PHP_EOL,$this->pagetoken, PHP_EOL ;
        return $resp;
    }

}
