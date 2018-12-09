<?php
namespace app\loader;
use app\Container,     
    GuzzleHttp\Psr7\Request,
    app\response\Cities as resp,
    app\request\Cities as req,
    app\store\Json,
    app\store\File,
    app\store\Csv;

/**
 * Description of Cities
 *
 * @author Grynchuk
 */
class Cities extends BaseLoader {
    
    private $config, $fileName;
    
    public $data;
    
    public function __construct() {        
        $this->config = Container::getConfig();        
        $this->fileName =  $this->config->data_folder . DIRECTORY_SEPARATOR .  'cities.json';
        $this->textFileName =  $this->config->data_folder . DIRECTORY_SEPARATOR .  'cities.txt';
        $this->csvFileName =  $this->config->data_folder . DIRECTORY_SEPARATOR .  'cities.csv';
    }    
    
    public function process() {
        
        $store = new Json();
        $store->setFileName($this->fileName);
        $this->data = $store->getFormFile();
        
        if ( ! $this->data) {
        
            list($success, $errors) = parent::processAsync();

            if ($errors OR ! $success) {
                throw new \Exception('Error on process Cities : ' . json_encode($errors));    
            }       
            $this->data = $store->setObjects($success)
                ->store()
                ->getStored();
            
//            $file =  new File();
//            $file->setFileName($this->textFileName)
//                ->setObjects($success)
//                ->store();    
            $file =  new Csv();
            $file->setFileName($this->csvFileName)
                ->setStringFields(['id', 'address'])
                ->setObjects($success)
                ->store();    
                    
        
        }  
                        
    }
    
    
    
    protected function getRequests()
    {        
        foreach ($this->config->cities as $city) {        
            $reqObj = new req();
            $reqObj->setCity($city);             
            $req[] = new Request('GET',  $reqObj->getUrl());
            $reqObjs[] = $reqObj;
        }    
        return [$req, $reqObjs];
    }
    
    protected function getResponse($response) {
        $resp = new resp();
        $resp->parse($response);    
        return $resp;
    }

}
