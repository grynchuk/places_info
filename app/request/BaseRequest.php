<?php
namespace app\request;

use app\Container;

/**
 * Description of baseRequest
 *
 * @author Анатолий
 */
abstract class BaseRequest {
    
    const LANG_EN = 'en',
          LANG_UA = 'ua',
          LANG_RU = 'ru';  


    public function getUrl()
    {
        $config = Container::getConfig();
        $midUrl = $this->getMidUrl();
        $params = $this->getParams();        
        $params['key'] = $config->api_key;        
        return $config->google_url . $midUrl . '?' . http_build_query($params); 
    }
    
    abstract protected function getParams();
    
    abstract protected function getMidUrl();
}
