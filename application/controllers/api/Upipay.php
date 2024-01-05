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
            'Setting_model',
			'Coin_plan_model'
        ]);
    }

   public function index_post()
    {
		
		 $txnAmount = $this->input->post('txnAmount');
		 $customerName = $this->input->post('customerName');
		 $customerMobile = $this->input->post('customerMobile');
		 $customerEmail = $this->input->post('customerEmail');
		 $user_id = $this->input->post('user_id');
		 $plan_id = $this->input->post('plan_id');
		 $plan = $this->Coin_plan_model->View($plan_id);
		 
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
		$post_data->redirect_url = base_url()."/Home/redirect"; //
		//$post_data->redirect_url = "https://yourdomain.com/redirect_page.php"; // automatically ?client_txn_id=xxxxxx&txn_id=xxxxx will be added on redirect_url
		$post_data->udf1 = $user_id;
		$post_data->udf2 = $plan_id;
		$post_data->udf3 = $plan->coin;
		 

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
	
	
	public function callback_post()
    {
	
		$filename = '/var/www/html/letscard/file.txt'; // Replace 'example.txt' with the name of your file or its path
		
		if(isset($_POST['id']) && $_POST['client_txn_id']){
			
			
			$id = $_POST['id']; // upi gateway transaction id
			$customer_vpa = $_POST['customer_vpa']; // upi id from which payment is made
			$Amount = $_POST['amount']; // 1
			$client_txn_id = $_POST['client_txn_id']; // client_txn_id set while creating order 
			$customer_name = $_POST['customer_name']; // 
			$customer_email = $_POST['customer_email']; // 
			$customer_mobile = $_POST['customer_mobile']; // 
			$p_info = $_POST['p_info']; // p_info set while creating order 
			$upi_txn_id = $_POST['upi_txn_id']; // UTR or Merchant App Transaction ID
			$status = $_POST['status']; // failure
			$remark = $_POST['remark']; // Remark of Transaction
			$udf1 = $user_id = $_POST['udf1']; // user defined data added while creating order
			$udf2 = $plan_id = $_POST['udf2']; // user defined data added while creating order
			$udf3 = $coin = $_POST['udf3']; // user defined data added while creating order
			$redirect_url = $_POST['redirect_url']; // redirect_url added while creating order
			$txnAt = $_POST['txnAt']; // 2023-05-11 date of transaction
			$createdAt = $_POST['createdAt']; // 2023-05-11T12%3A15%3A23.000Z
			if($_POST['status']){
				echo "Transaction Successful";
				
				 $data = [
					'user_id' => $user_id,
					'plan_id' => $plan_id,
					'coin' => $coin,
					'price' => $Amount,
					'payment' => '0',
					'upipay_id' => $id,
					'added_date' => date('Y-m-d H:i:s'),
					'updated_date' => date('Y-m-d H:i:s')
				];
				
				//fwrite($file, $_POST['status']);
				//fwrite($file, $data);
				// Close the file handle
				//fclose($file);
		
				//$file = fopen($filename, 'a'); // 'w' mode opens the file for writing, truncates 
				$response = json_encode($data);
				// Write data to the file
				//fwrite($file, $response);
				// Close the file handle
				
				// Open the file for writing (creates a new file if it doesn't exist)
				
				$last_insert_id = $this->Coin_plan_model->AddCoin($data);
				
				//fwrite($file, $last_insert_id);
			}

			if($_POST['status'] == 'failure'){
				echo "Transaction Failed";
			}
			
		
		}
		
	
		
		/* if(isset($_POST['id']) && $_POST['client_txn_id']){
			$filename = '/var/www/html/letscard/file.txt'; // Replace 'example.txt' with the name of your file or its path

			// Open the file for writing (creates a new file if it doesn't exist)
			$file = fopen($filename, 'w'); // 'w' mode opens the file for writing, truncates the file to zero length, or creates a new file if it doesn't exist

			if ($file) {
				$data = json_encode($_POST);
				
				// Write data to the file
				fwrite($file, $data);

				// Close the file handle
				fclose($file);

				echo "Data has been written to the file.";
			} else {
				echo "Unable to open the file for writing.";
			}
		} */
	
	}
	
	
	

   
}