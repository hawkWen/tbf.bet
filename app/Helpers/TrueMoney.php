<?php

namespace App\Helpers;

class TrueMoney
{

	private $tmnone_endpoint = 'https://api.tmn.one/api.php';
	private $wallet_endpoint = 'https://tmn-mobile-gateway.truemoney.com/tmn-mobile-gateway/';
	private $tmnone_keyid = 0;
	private $wallet_msisdn, $wallet_login_token, $wallet_tmn_id, $wallet_device_id, $wallet_access_token = '';

	public function __construct()
	{
	}

	public function setData($tmnone_keyid, $wallet_msisdn, $wallet_login_token, $wallet_tmn_id) {
		$this->tmnone_keyid = $tmnone_keyid;
		$this->wallet_msisdn = $wallet_msisdn;
		$this->wallet_login_token = $wallet_login_token;
		$this->wallet_tmn_id = $wallet_tmn_id;
		$this->wallet_device_id = substr(md5($wallet_msisdn . $wallet_tmn_id), 0, 16);
	  }

	public function setDataWithAccessToken($tmnone_keyid, $wallet_access_token, $wallet_login_token, $wallet_device_id) {
		$this->tmnone_keyid = $tmnone_keyid;
		$this->wallet_access_token = $wallet_access_token;
		$this->wallet_login_token = $wallet_login_token;
		$this->wallet_device_id = $wallet_device_id;
	   }

	public function getCachedAccessToken()
	{
		$request_body = json_encode(array('scope'=>'text_storage_obj', 'cmd'=>'get'));
		$encrypted_access_token = $this->tmnone_connect($request_body)['data'];
		if(!empty($encrypted_access_token))
		{
			$aes_key = hex2bin(substr(hash('sha512', $this->wallet_tmn_id) ,0 ,64));
			$aes_iv = hex2bin(substr($encrypted_access_token, 0, 32));
			$access_token = openssl_decrypt(base64_decode(substr($encrypted_access_token, 32)), 'AES-256-CBC', $aes_key,  OPENSSL_RAW_DATA, $aes_iv);
			if(!empty($access_token))
			{
				 $this->wallet_access_token = $access_token;
			}
		}
	}

	public function loginWithPin6($wallet_pin)
	{
		$this->getCachedAccessToken();
		if(!empty($this->wallet_access_token))
		{
			return $this->wallet_access_token;
		}
		$wallet_pin = hash('sha256', $this->wallet_tmn_id . $wallet_pin);
		$signature = $this->calculate_sign256($this->wallet_login_token . '|' . $wallet_pin);
		$postdata = array();
		$postdata['pin'] = $wallet_pin;
		$postdata['app_version'] = '5.25.1';
		$postdata = json_encode($postdata);
		$wallet_response_body = $this->wallet_connect('mobile-auth-service/v1/pin/login', array('Content-Type: application/json', 'Authorization: ' . $this->wallet_login_token , 'signature: ' . $signature , 'X-Device: ' . $this->wallet_device_id, 'X-Geo-Location: city=; country=; country_code=', 'X-Geo-Position: lat=; lng='), $postdata);
		if(!empty($wallet_response_body['data']['access_token']))
		{
			$this->wallet_access_token = $wallet_response_body['data']['access_token'];
			$aes_key = hex2bin(substr(hash('sha512', $this->wallet_tmn_id) ,0 ,64));
			$aes_iv = openssl_random_pseudo_bytes(16);
			$encrypted_access_token = bin2hex($aes_iv) . base64_encode(openssl_encrypt($this->wallet_access_token, 'AES-256-CBC', $aes_key,  OPENSSL_RAW_DATA, $aes_iv));
			$request_body = json_encode(array('scope'=>'text_storage_obj', 'cmd'=>'set', 'data'=>$encrypted_access_token));
			$this->tmnone_connect($request_body);
		}
		return $this->wallet_access_token;
	}

	public function getBalance()
	{
		$uri = 'user-profile-composite/v1/users/';
		$signature = $this->calculate_sign256('/tmn-mobile-gateway/' . $uri);
		$wallet_response_body = $this->wallet_connect($uri, array('Content-Type: application/json', 'Authorization: ' . $this->wallet_access_token), '');
		return isset($wallet_response_body['data']['current_balance']) ? $wallet_response_body['data']['current_balance'] : '';
	}

	public function fetchTransactionHistory($start_date, $end_date, $limit=10, $page=1)
	{
		$uri = 'history-composite/v1/users/transactions/history/?start_date=' . $start_date . '&end_date=' . $end_date . '&limit=' . $limit . '&page=' . $page . '&type=&action=';
		$signature = $this->calculate_sign256('/tmn-mobile-gateway/' . $uri);
		$wallet_response_body = $this->wallet_connect($uri, array('Content-Type: application/json', 'Authorization: ' . $this->wallet_access_token , 'signature: ' . $signature , 'X-Device: ' . $this->wallet_device_id, 'X-Geo-Location: city=; country=; country_code=', 'X-Geo-Position: lat=; lng='), '');
		return isset($wallet_response_body['data']['activities']) ? $wallet_response_body['data']['activities'] : array();
	}

	public function fetchTransactionInfo($report_id)
	{
		$cache_filename = sys_get_temp_dir() . '/tmn-' . $report_id;
		$aes_key = hex2bin(substr(hash('sha512', $this->wallet_tmn_id) ,0 ,64));
		if(file_exists($cache_filename))
		{
			$wallet_response_body = file_get_contents($cache_filename);
			$aes_iv = hex2bin(substr($wallet_response_body, 0, 32));
			$wallet_response_body = openssl_decrypt(substr($wallet_response_body, 32), 'AES-256-CBC', $aes_key,  OPENSSL_RAW_DATA, $aes_iv);
			$wallet_response_body = json_decode($wallet_response_body, true);
			$wallet_response_body['cached'] = true;
			return $wallet_response_body;
		}
		$uri = 'history-composite/v1/users/transactions/history/detail/' . $report_id . '?version=1';
		$signature = $this->calculate_sign256('/tmn-mobile-gateway/' . $uri);
		$wallet_response_body = $this->wallet_connect($uri, array('Content-Type: application/json', 'Authorization: ' . $this->wallet_access_token , 'signature: ' . $signature , 'X-Device: ' . $this->wallet_device_id, 'X-Geo-Location: city=; country=; country_code=', 'X-Geo-Position: lat=; lng='), '');
		if(!empty($wallet_response_body['data']))
		{
			$aes_iv = openssl_random_pseudo_bytes(16);
			$encrypted_wallet_response_body = bin2hex($aes_iv) . openssl_encrypt(json_encode($wallet_response_body['data']), 'AES-256-CBC', $aes_key,  OPENSSL_RAW_DATA, $aes_iv);
			file_put_contents($cache_filename, $encrypted_wallet_response_body);
		}
		return isset($wallet_response_body['data']) ? $wallet_response_body['data'] : array();
	}

	public function getRecipientName($payee_wallet_id)
	{
		try
		{
			$amount = '1.00';
			$uri = 'transfer-composite/v2/p2p-transfer/draft-transactions';
			$signature = $this->calculate_sign256('/tmn-mobile-gateway/' . $uri . '|' .  $this->wallet_access_token . '|' . $amount . '|' . $payee_wallet_id);
			$wallet_response_body = $this->wallet_connect($uri, array('Content-Type: application/json', 'Authorization: ' . $this->wallet_access_token , 'signature: ' . $signature , 'X-Device: ' . $this->wallet_device_id, 'X-Geo-Location: city=; country=; country_code=', 'X-Geo-Position: lat=; lng='),
				'{"receiverId":"' . $payee_wallet_id . '","amount":"' . $amount . '"}');
			if($wallet_response_body['code'] != 'TRC-200')
			{
				throw new Exception($wallet_response_body['code'] . ' - ' . $wallet_response_body['message']);
			}
			return $wallet_response_body['data']['recipient_name'];
		}
		catch (Exception $e)
		{
			return array('error'=>$e->getMessage());
		}
	}

	public function transferP2P($payee_wallet_id,$amount,$personal_msg='')
	{
		try
		{
			$amount = number_format($amount, 2, '.', '');
			$uri = 'transfer-composite/v2/p2p-transfer/draft-transactions';
			$signature = $this->calculate_sign256('/tmn-mobile-gateway/' . $uri . '|' .  $this->wallet_access_token . '|' . $amount . '|' . $payee_wallet_id);
			$wallet_response_body = $this->wallet_connect($uri, array('Content-Type: application/json', 'Authorization: ' . $this->wallet_access_token , 'signature: ' . $signature , 'X-Device: ' . $this->wallet_device_id, 'X-Geo-Location: city=; country=; country_code=', 'X-Geo-Position: lat=; lng='),
				'{"receiverId":"' . $payee_wallet_id . '","amount":"' . $amount . '"}');
			if($wallet_response_body['code'] != 'TRC-200')
			{
				throw new Exception($wallet_response_body['code'] . ' - ' . $wallet_response_body['message']);
			}
			$draft_transaction_id = $wallet_response_body['data']['draft_transaction_id'];
			$reference_key = $wallet_response_body['data']['reference_key'];

			$uri = 'transfer-composite/v1/p2p-transfer/draft-transactions/' . $draft_transaction_id;
			$signature = $this->calculate_sign256($reference_key);
			$wallet_response_body = $this->wallet_connect($uri, array('Content-Type: application/json', 'Authorization: ' . $this->wallet_access_token , 'signature: ' . $signature , 'X-Device: ' . $this->wallet_device_id, 'X-Geo-Location: city=; country=; country_code=', 'X-Geo-Position: lat=; lng='),
				'{"personal_message":"' . $personal_msg . '","signature":"' . $signature . '"}', 'PUT');
			if($wallet_response_body['code'] != 'TRC-200')
			{
				throw new Exception($wallet_response_body['code'] . ' - ' . $wallet_response_body['message']);
			}

			$uri = 'transfer-composite/v1/p2p-transfer/transactions/' . $draft_transaction_id . '/';
			$signature = $this->calculate_sign256($reference_key);
			$wallet_response_body = $this->wallet_connect($uri, array('Content-Type: application/json', 'Authorization: ' . $this->wallet_access_token , 'signature: ' . $signature , 'X-Device: ' . $this->wallet_device_id, 'X-Geo-Location: city=; country=; country_code=', 'X-Geo-Position: lat=; lng='),
				'{"reference_key":"' . $reference_key . '","signature":"' . $signature . '"}');
			if($wallet_response_body['code'] != 'TRC-200')
			{
				throw new Exception($wallet_response_body['code'] . ' - ' . $wallet_response_body['message']);
			}
		}
		catch (Exception $e)
		{
			return array('error'=>$e->getMessage());
		}
		return isset($wallet_response_body['data']) ? $wallet_response_body['data'] : array();
	}

	private function tmnone_connect($request_body)
	{
		$aes_key = hex2bin(substr(hash('sha512', $this->wallet_login_token) ,0 ,64));
		$aes_iv = openssl_random_pseudo_bytes(16);
		$request_body = bin2hex($aes_iv) . base64_encode(openssl_encrypt($request_body, 'AES-256-CBC', $aes_key,  OPENSSL_RAW_DATA, $aes_iv));
		$request_body = json_encode(array('encrypted'=>$request_body));
		$curl = curl_init($this->tmnone_endpoint);
		curl_setopt($curl, CURLOPT_TIMEOUT, 60);
		curl_setopt($curl, CURLOPT_HEADER, false);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
		curl_setopt($curl, CURLOPT_HTTPHEADER, array('X-KeyID: ' . $this->tmnone_keyid, 'Content-Type: application/json'));
		curl_setopt($curl, CURLOPT_USERAGENT, 'okhttp/4.4.0/202109071944/' . $this->tmnone_keyid);
		curl_setopt($curl, CURLOPT_POST, true);
		curl_setopt($curl, CURLOPT_POSTFIELDS, $request_body);
		$response_body = curl_exec($curl);
		curl_close($curl);
		$response_body = json_decode($response_body,true);
		if(isset($response_body['encrypted']))
		{
			$response_body = openssl_decrypt(base64_decode($response_body['encrypted']), 'AES-256-CBC', $aes_key,  OPENSSL_RAW_DATA, $aes_iv);
			$response_body = json_decode($response_body,true);
		}
		return $response_body;
	}

	private function wallet_connect($uri, $headers, $request_body='', $custom_method=null)
	{
		$curl = curl_init($this->wallet_endpoint . $uri);
		curl_setopt($curl, CURLOPT_TIMEOUT, 60);
		curl_setopt($curl, CURLOPT_HEADER, false);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
		curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($curl, CURLOPT_USERAGENT, 'okhttp/4.4.0');
		curl_setopt($curl, CURLOPT_SSL_CIPHER_LIST, 'TLS_RSA_WITH_AES_128_GCM_SHA256,ECDHE-RSA-AES128-GCM-SHA256,ECDHE-ECDSA-AES128-SHA');
		if(!empty($request_body))
		{
			curl_setopt($curl, CURLOPT_POST, true);
			curl_setopt($curl, CURLOPT_POSTFIELDS, $request_body);
		}
		if(!empty($custom_method))
		{
			curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $custom_method);
		}
		$response_body = curl_exec($curl);
		curl_close($curl);
		$response_body = json_decode($response_body,true);
		if(isset($response_body['code']) && $response_body['code'] == 'MAS-401')
		{
			$request_body = json_encode(array('scope'=>'text_storage_obj', 'cmd'=>'set', 'data'=>''));
			$this->tmnone_connect($request_body);
		}
		return $response_body;
	}

	public function calculate_sign256($data)
	{
		$request_body = json_encode(array('cmd'=>'calculate_sign256', 'data'=>array('login_token'=>$this->wallet_login_token, 'device_id'=>$this->wallet_device_id, 'data'=>$data)));
		return isset($this->tmnone_connect($request_body)['signature']) ? $this->tmnone_connect($request_body)['signature'] : '';
	}

}

?>