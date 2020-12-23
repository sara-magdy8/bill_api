<?php

namespace App\Controllers\Api\V1;

use CodeIgniter\RESTful\ResourceController;
use App\Controllers\GenerateBill;


class Bills extends ResourceController {

    protected $format = 'json';
    protected $request;
    protected $uri;
    protected $data;

    function __construct() {
        $this->request = service('request');
        $this->uri = $this->request->uri;
    }

    function index() {

        $this->data = $this->request->getJSON(TRUE);
        if (!$this->data) {
            return $this->failValidationError();
        }


        try {
            $generate_bill = new GenerateBill();
        } catch (\CodeIgniter\Exceptions\ConfigException $e) {
            // There is an error while defining products and currencies of the APP.
            return $this->failServerError("APP configuration error");
        }

        $bill_data = $generate_bill->index($this->data['products'], $this->data['currency']);
        if (!$bill_data) {
            return $this->failValidationError();
        }

        return $this->respond($bill_data);
    }

}
