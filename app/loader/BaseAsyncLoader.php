<?php

namespace app\loader;

use GuzzleHttp\Client,
    GuzzleHttp\Pool
    ;

/**
 * Description of BaseAsyncLoader
 *
 * @author Анатолий
 */
 abstract class BaseAsyncLoader {
       
    protected $success, $errors;


    protected function process() {
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
    
    abstract protected function getRequests();
    
    abstract protected function getResponse($response);
}
