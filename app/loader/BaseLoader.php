<?php

namespace app\loader;

use GuzzleHttp\Client,
    GuzzleHttp\Pool,
    GuzzleHttp\Psr7\Request;    
    ;

/**
 * Description of BaseAsyncLoader
 *
 * @author Анатолий
 */
 abstract class BaseLoader {
       
    protected $success, $errors;


    protected function processAsync() {
    $client = new Client();
    
    list($reqs, $reqObjs) = $this->getRequests();
    
    $success = [];
    $errors = [];
    
    $pool = new Pool($client, $reqs, [
        'concurrency' => 5,
        'fulfilled' => function ($response, $index) use (&$success, $reqObjs) {        
            $success[] = [
                'data' => $this->getResponse($response),
                'req' => $reqObjs[$index]
            ];   
            // this is delivered each successful response
        },
        'rejected' => function ($reason, $index) use (&$errors, $reqObjs) {
              $errors[] = [
                  'data' => $reason->getMessage(),
                  'req'  => $reqObjs[$index]
              ];
        }
    ]);

    // Initiate the transfers and create a promise
    $promise = $pool->promise();
    // Force the pool of requests to complete.
    $promise->wait();
     
    return [$success, $errors];
    }
    
    public function processSync() {
      $client = new Client();
      
      list($req, $reqObj)= $this->getRequests();
      
      $response = $client->send($req);
            
      $success = [
          'data' => $this->getResponse($response),
          'req'=> $reqObj
      ];
      
      $error = [];
      
      return [$success, $error];
    }
    
    abstract protected function getRequests();
    
    abstract protected function getResponse($response);
}
