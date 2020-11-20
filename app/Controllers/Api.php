<?php namespace app\Controllers;

use CodeIgniter\Controller;
use App\Models\Bill;

class Api extends Controller
{

    protected $data;
    protected $version = '1.0.0';
    protected $request;
    
    /**
     * @desc Check that everything is ok to access the bill genertare API throw POST request
     * @return boolean - success / fail
     */
    public function check_data() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->data = json_decode(file_get_contents("php://input"));
        }
        if (!$this->data) {
            $this->dsp(array('status' => 'error', 'response' => "No direct access allowed"));
            return FALSE;
        } else if (!isset($this->data->auth)) {
            $this->dsp(array('status' => 'error', 'response' => "Missing authentication"));
            return FALSE;
        } else {
            $chk = $this->chkAuth($this->data->auth);
            if (!$chk) {
                $this->dsp(array('status' => 'error', 'response' => "Not authorized"));
                return FALSE;
            }
        }
        return TRUE;
    }
    
    /**
     * @desc Show API Details
     */
    function index() {
        if($this->check_data()) {
            $this->dsp(array('status' => 'success', 'response' => 'Bills REST API V' . $this->version));
        }
    }

    /**
     * @desc API function to generate a bill
     * @return boolean - success / fail
     */
    function get_bill() {
        if($this->check_data()) {
            try
            {
                $bill = new Bill();
            } catch (\CodeIgniter\Exceptions\ConfigException $e) {
                // There is an error while defining products and currencies of the APP.
                $this->dsp(array('status' => 'error', 'response' => "APP configuration error"));
            }
            $bill_data = $bill->prepare_bill($this->data->products, $this->data->currency);
            if($bill_data[0]){
                $this->dsp(array('status' => 'success', 'response' => $bill_data[1]));
            } else { 
                // If error occurs while generate the bill details, Then respond that error.
                $this->dsp(array('status' => 'error', 'response' => $bill_data[1]));
            }
        } 
    }


    /**
     * @desc API respond format
     * @param array $results - data that API will be respond with it.
     */
    protected function dsp($results) {
        log_message('info', 'API respond with ' . json_encode($results).' to IP = ' . $this->request->getIPAddress().PHP_EOL);
        $this->response
                ->setStatusCode(200)
                ->setContentType('application/json', 'utf-8')
                ->setJSON($results)
                ->send();
        exit;
    }
    
    /**
     * @desc Check on authentication code (with  the one saved in the APP) to be able to access the API 
     * @param string $auth_code - authentication code passed to the API (by the api user)
     * @return boolean - success / fail
     */
    private function chkAuth($auth_code){
        if($auth_code == API_AUTH_CODE){
            return TRUE;
        }
        log_message('info', 'authentication failed code passed : ' . $auth_code .' from IP = ' . $this->request->getIPAddress().PHP_EOL);
        return FALSE;
    }
}
