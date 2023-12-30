<?php

use Restserver\Libraries\REST_Controller;

include(APPPATH . '/libraries/REST_Controller.php');
include(APPPATH . '/libraries/Format.php');

class Upipay extends REST_Controller
{
    private $data;
    public function __construct()
    {
        parent::__construct();
        // $header = $this->input->request_headers('token');
        // if (!isset($header['Token'])) {
        //     $data['message'] = 'Invalid Request';
        //     $data['code'] = HTTP_UNAUTHORIZED;
        //     $this->response($data, HTTP_OK);
        //     exit();
        // }
        // if ($header['Token'] != getToken()) {
        //     $data['message'] = 'Invalid Authorization';
        //     $data['code'] = HTTP_METHOD_NOT_ALLOWED;
        //     $this->response($data, HTTP_OK);
        //     exit();
        // }
        // if (!isset($header['Content-Type']) && $header['Content-Type']!= 'application/json') {
        //     $data['message'] = 'Invalid Data';
        //     $data['code'] = HTTP_UNAUTHORIZED;
        //     $this->response($data, HTTP_OK);
        //     exit();
        // }
        // $this->data = json_decode(file_get_contents('php://input'));
		/* $this->data = json_decode(file_get_contents('php://input'));

        $this->load->model([
            'Users_model',
            'Coin_plan_model'
        ]); */
		$this->load->model([
            'Setting_model'
        ]);
    }

    public function index_post()
    {
		
		 $txnAmount = $this->input->post('txnAmount');
		 $customerName = $this->input->post('customerName');
		 $customerMobile = $this->input->post('customerMobile');
		 $customerEmail = $this->input->post('customerEmail');
		 
		 $upi_payment_api_key = $this->Setting_model->Setting('upi_payment_api_key');
		 $key = $upi_payment_api_key->upi_payment_api_key;	// Your Api Token https://merchant.upigateway.com/user/api_credentials
		$post_data = new stdClass();
		$post_data->key = $key;
		$post_data->client_txn_id = (string) rand(100000, 999999); // you can use this field to store order id;
		$post_data->amount = $txnAmount;
		$post_data->p_info = "test_product";
		$post_data->customer_name = $customerName;
		$post_data->customer_email = $customerEmail;
		$post_data->customer_mobile = $customerMobile;
		$post_data->redirect_url = base_url()."/Upipay/cancel"; //
		//$post_data->redirect_url = "https://yourdomain.com/redirect_page.php"; // automatically ?client_txn_id=xxxxxx&txn_id=xxxxx will be added on redirect_url
		$post_data->udf1 = "extradata";
		$post_data->udf2 = "extradata";
		$post_data->udf3 = "extradata";

		$curl = curl_init();
		curl_setopt_array($curl, array(
			CURLOPT_URL => 'https://api.ekqr.in/api/create_order',
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => '',
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 30,
			CURLOPT_FOLLOWLOCATION => true,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => 'POST',
			CURLOPT_POSTFIELDS => json_encode($post_data),
			CURLOPT_HTTPHEADER => array(
				'Content-Type: application/json'
			),
		));
		$response = curl_exec($curl);
		curl_close($curl);
		$result = json_decode($response, true);
		$data['message'] = $result['msg'];
		$data['order_id'] = $result['data']['order_id'];
		$data['payment_url'] = $result['data']['payment_url'];
		$data['upi_id_hash'] = $result['data']['upi_id_hash'];
		$data['code'] = HTTP_OK;
		$this->response($data, HTTP_OK);
		 
    }

   
}