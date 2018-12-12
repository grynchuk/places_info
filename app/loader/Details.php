<?php
namespace app\loader;
use app\Container,     
    GuzzleHttp\Psr7\Request,
    app\response\Details as resp,
    app\request\Details as req,
    app\store\Json,
    app\store\File,
    app\store\Csv;

/**
 * Description of Details
 *
 * @author grynchuk
 */
class Details extends BaseLoader {
    
    private $config, $fileName, $placeIds = [], $decoratedData =[];
    
    public $data;
    
    
    
    public function __construct() {        
        $this->config = Container::getConfig();        
        $this->fileName =  $this->config->data_folder . DIRECTORY_SEPARATOR .  'details.json';
        $this->textFileName =  $this->config->data_folder . DIRECTORY_SEPARATOR .  'details.txt';
        $this->csvFileName =  $this->config->data_folder . DIRECTORY_SEPARATOR .  'details.csv';
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
            
            $file =  new Csv();
            $file->setFileName($this->csvFileName)
                ->setStringFields(['id', 'address'])
                ->decorateWithData($this->decoratedData)
                ->setObjects($success)
                ->store();
        }  
                        
    }
        
    public function setPlaceData($data) {
        $this->placeIds = array_keys($data);
        $this->decoratedData = $this->getDecorated($data);
        return $this;
    }
    
    private function getDecorated($data){
        $res = [];
        foreach($data as  $key => $val)
        {
            $str = '';
            foreach($val['name'] as $lang => $name) {
                $str.= " {$lang} : {$name} ; ";
            }
            
            $res[$key]['name_on_lang'] = $str; 
        }
        
        return $res;
    }
    
    protected function getRequests()
    {        
        foreach ($this->placeIds as $id) {        
            $reqObj = new req();
            $reqObj->setPlaceId($id);             
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
