<?php

namespace app\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Config\Services;

class BasicAuthFilter implements FilterInterface {

    
    public function before(RequestInterface $request, $arguments = NULL) {
        $response = Services::response();
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method, Authorization");
        header("Access-Control-Allow-Methods: POST ");
        $method = $request->getMethod();
        if ($method == "GET" || $method == "OPTIONS" || $method == "PUT" || $method == "DELETE") {
            die('Access denied');
        }
        
        if (empty($_SERVER['PHP_AUTH_USER']) || $_SERVER['PHP_AUTH_USER'] != API_AUTH_USER || $_SERVER['PHP_AUTH_PW'] !=  API_AUTH_PASS) {
            $response->setStatusCode(401);
            $response->setBody("{\"error\": \"unauthorized\"}");
            return $response;
        }
        
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = NULL) {
        
    }

}
