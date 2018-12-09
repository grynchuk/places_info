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
 * Description of Cities
 *
 * @author Grynchuk
 */
class Places extends BaseLoader {
    
    private $config, $fileName, $pagetoken, $prevtoken, $cities;
    
    public $data = [];
    
    public function __construct() {        
        $this->config = Container::getConfig();        
        $this->fileName =  $this->config->data_folder . DIRECTORY_SEPARATOR .  'places.json';
        $this->textFileName =  $this->config->data_folder . DIRECTORY_SEPARATOR .  'places.txt';
        $this->csvFileName =  $this->config->data_folder . DIRECTORY_SEPARATOR .  'places.csv';
    }    
    
    public function process() {
        
        $store = new Json();
        $store->setFileName($this->fileName);
        $this->data = $store->getFormFile();
        
        if ($this->data) {        
           return;    
        }    
        
        foreach ($this->cities as $city => $data) {
            var_dump($data);
            $this->processCity($data['lat'], $data['lng']);
        }

    }
    
    public function setCities($data)
    {
        $this->cities = $data;
        return $this;
    }
    
    protected function processCity($lat, $lng) {
        
        $this->lat = $lat;
        $this->lng = $lng;
        $this->data = [];
        while ($this->pagetoken OR ! $this->prevtoken) {
            
            list($success, $errors) = parent::processSync();
            echo PHP_EOL, "ok" , PHP_EOL, PHP_EOL;
            sleep(2);
            if ($errors OR ! $success) {
               throw new \Exception('Error on process Cities : ' . json_encode($errors));    
            }
            $this->data = array_merge($this->data, 
                array_values($success['data']->toArray())
            );
        }
        
        var_dump($this->data);
        
       die();
    }
    
    protected function getRequests()
    {   
        $reqObj = new req();
        $reqObj->setLat($this->lat)
            ->setLng($this->lng)
            ->setPagetoken($this->pagetoken);
        
        $req = new Request('GET', $reqObj->getUrl());
        
        return [$req, $reqObj];
    }
    
    protected function getResponse($response) {
        $resp = new resp();
        $resp->parse($response);
        $this->prevtoken = $this->pagetoken;
        $this->pagetoken = $resp->getNextPageToken();         
        return $resp;
    }

}
