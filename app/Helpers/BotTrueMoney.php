<?php

namespace App\Helpers;

class BotTrueMoney {
	public $config = array();
	public $config_path = null;

	public $curl_options = null;

	public $data = null;

	public $response = null;
	public $http_code = null;

	public $mobile_api_gateway = "https://tmn-mobile-gateway.truemoney.com/tmn-mobile-gateway/";
	public $mobile_api_endpoint = "/tmn-mobile-gateway/";
	public $remote_key_url = "https://meawhun.com/api/tmwrkey.php";
	
	public $remote_key_id = "byDark-Master";
	public $remote_key_value = "";

	public function prepare_identity () {
		$device_brands = array("samsung");
		$device_models = array(
			"SM-N950N", "SM-G930K", "SM-G955N", "SM-G965N",
			"SM-G930L", "SM-G925F", "SM-N950F", "SM-N9005",
			"SM-G9508", "SM-N935F", "SM-N950W", "SM-G9350",
			"SM-G955F", "SM-N950U", "SM-G955U", "SM-G950U1"
		);
		if (!isset($this->config["device_id"])) {
			$this->updateConfig("device_id", substr(md5(microtime().uniqid()), 16));
		}
		if (!isset($this->config["mobile_tracking"])) {
			$this->updateConfig("mobile_tracking", base64_encode(openssl_random_pseudo_bytes(40)));
		}
		if (!isset($this->config["device_brand"]) || !isset($this->config["device_model"])) {
			$this->updateConfig("device_brand", $device_brands[array_rand($device_brands)]);
			$this->updateConfig("device_model", $device_models[array_rand($device_models)]);
		}
		return true;
	}

	public function __construct ($config = null) {
		if (is_string($config)) {
			$this->setConfigPath($config);
		} elseif (is_array($config)) {
			$this->updateConfig($config);
			$this->prepare_identity();
		}
	}

	public function setConfigPath ($path = null, $merge = false, $reset = true) {
		$this->config_path = is_null($path) ? null : strval($path);
		if (!is_null($this->config_path)) {
			if ($reset) $this->config = array();
			if ($merge) $merge_config = $this->config;
			if (!file_exists($this->config_path)) file_put_contents($this->config_path, json_encode($this->config));
			$this->config = json_decode(file_get_contents($this->config_path), true);
			if ($merge) $this->config = array_replace($this->config, $merge_config);
		}
		$this->updateConfig();
		$this->prepare_identity();
		return true;
	}
	
	public function setConfig ($config = null) {
		if (is_null($config)) $config = array();
		$this->config = $config;
		$this->updateConfig();
		$this->prepare_identity();
	}

	public function updateConfig ($name = null, $value = null) {
		if (is_array($name)) {
			$this->config = array_replace($this->config, $name);
			foreach ($this->config as $name => $value) {
				if (is_null($value)) unset($this->config[$name]);
			}
		} elseif (is_string($name)) {
			if (!is_null($value)) {
				$this->config[$name] = $value;
			} else {
				unset($this->config[$name]);
			}
		}
		if (isset($this->config["no_file"]) && $this->config["no_file"]) $this->config_path = null;
		if (!is_null($this->config_path)) file_put_contents($this->config_path, json_encode($this->config));
		if (isset($this->config["username"]) && isset($this->config["password"]) && !isset($this->config["type"])) {
			$this->updateConfig("type", "mobile");
		}
		if ((!isset($this->config["no_file"]) || !$this->config["no_file"]) && is_null($this->config_path) && isset($this->config["username"])) {
			$this->setConfigPath(base_path().'/public/truemoney/'.$this->config["username"].".txt", true, false);
		}
		return $this->config;
	}

	public function request ($method, $endpoint, $headers = array(), $data = null) {
        $this->data = null;
        $handle = curl_init();
        if (!is_null($data)) {
            curl_setopt($handle, CURLOPT_POSTFIELDS, is_array($data) ? json_encode($data) : $data);
            if (is_array($data)) $headers = array_merge(array("Content-Type" => "application/json"), $headers);
        }
        curl_setopt_array($handle, array(
            CURLOPT_URL => rtrim($this->mobile_api_gateway, "/").$endpoint,
            CURLOPT_CUSTOMREQUEST => $method,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_USERAGENT => "okhttp/3.12.0",
            CURLOPT_HTTPHEADER => $this->buildHeaders($headers)
        ));
        if (is_array($this->curl_options)) curl_setopt_array($handle, $this->curl_options);
        $this->response = curl_exec($handle);
        $this->http_code = curl_getinfo($handle, CURLINFO_HTTP_CODE);
        if ($result = json_decode($this->response, true)) {
            if (isset($result["data"])) $this->data = $result["data"];
            return $result;
        }
        return $this->response;
    }


	public function request_remote_key ($url = null) {
        if (is_null($url)) $url = $this->remote_key_url;
        $handle = curl_init();
        curl_setopt_array($handle, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true
        ));
        if (is_array($this->curl_options)) curl_setopt_array($handle, $this->curl_options);
        $response = curl_exec($handle);
        $http_code = curl_getinfo($handle, CURLINFO_HTTP_CODE);
        if ($http_code == 200 && $result = json_decode($response, true)) {
            if (isset($result["key"]) && is_string($result["key"]) && isset($result["device"]) && is_string($result["device"])) {
				$this->remote_key_id = $result["device"];
				$this->remote_key_value = $result["key"];
                return $this->remote_key_value;
            }
        }
        return "";
    }

	public function buildHeaders ($array) {
		$headers = array();
		foreach ($array as $key => $value) {
			$headers[] = $key.": ".$value;
		}
		return $headers;
	}

	public function getTimestamp() {
		return strval(floor(microtime(true)*1000));
	}
	
	public function getUUIDv4 () {
		$data = openssl_random_pseudo_bytes(16);
		$data[6] = chr(ord($data[6]) & 0x0f | 0x40);
		$data[8] = chr(ord($data[8]) & 0x3f | 0x80);
		return vsprintf("%s%s-%s-%s-%s-%s%s%s", str_split(bin2hex($data), 4));
	}

	public function RequestLoginOTP () {
		if (!isset($this->config["username"]) || !isset($this->config["password"]) || !isset($this->config["type"])) return false;
		$timestamp = $this->getTimestamp();
		$result = $this->request("GET", "/mobile-auth-service/v1/password/login/otp/", array(
			"username" => strval($this->config["username"]),
			"password" => hash("sha256", hash("sha256", substr($timestamp, 4)).hash("sha256", strval($this->config["username"]).strval($this->config["password"]))),
			"type" => strval($this->config["type"]),
			"timestamp" => $timestamp,
			"device_id" => strval($this->config["device_id"]),
            "signature" => hash_hmac("sha1", implode("|", array(strval($this->config["username"]), hash("sha256", hash("sha256", substr($timestamp, 4)).hash("sha256", strval($this->config["username"]).strval($this->config["password"]))), strval($this->config["device_id"]), $timestamp)), "9LXAVCxcITaABNK48pAVgc4muuTNJ4enIKS5YzKyGZ"),
		));
		return $result;
	}

	public function SubmitLoginOTP ($otp_code, $mobile_number = null, $otp_reference = null) {
		if (!isset($this->config["username"]) || !isset($this->config["password"]) || !isset($this->config["type"])) return false;
		if (is_null($mobile_number) && isset($this->data["mobile_number"])) $mobile_number = $this->data["mobile_number"];
		if (is_null($otp_reference) && isset($this->data["otp_reference"])) $otp_reference = $this->data["otp_reference"];
		if (is_null($mobile_number) || is_null($otp_reference)) return false;
		$timestamp = $this->getTimestamp();

		$result = $this->request("POST", "/mobile-auth-service/v1/password/login/otp/", array(
			"X-Device" => strval($this->config["device_id"])
		), array(
			"brand" => strval($this->config["device_brand"]),
			"device_id" => strval($this->config["device_id"]),
			"device_name" => strval($this->config["device_model"]),
			"device_os" => "android",
			"mobile_number" => strval($mobile_number),
			"mobile_tracking" => strval($this->config["mobile_tracking"]),
			"model_identifier" => strval($this->config["device_model"]),
			"model_number" => strval($this->config["device_model"]),
			"otp_code" => strval($otp_code),
			"otp_reference" => strval($otp_reference),
			"password" => hash("sha256", hash("sha256", substr($timestamp, 4)).hash("sha256", strval($this->config["username"]).strval($this->config["password"]))),
			"timestamp" => $timestamp,
			"type" => strval($this->config["type"]),
			"username" => strval($this->config["username"])
		));
		if (isset($result["data"]["tmn_id"])) $this->updateConfig("tmn_id", $result["data"]["tmn_id"]);
		if (isset($result["data"]["access_token"])) $this->updateConfig("access_token", $result["data"]["access_token"]);
		if (isset($result["data"]["login_token"])) $this->updateConfig("login_token", $result["data"]["login_token"]);
		if (isset($result["data"]["reference_token"])) $this->updateConfig("reference_token", $result["data"]["reference_token"]);
		return $result;
	}

	public function Login () {
		$a = hash_hmac("sha256", implode("|", array(strval($this->config["login_token"]), hash("sha256", strval($this->config["tmn_id"]).strval($this->config["pin"])))), $this->remote_key_value);
		if (!isset($this->config["pin"]) || !isset($this->config["tmn_id"]) || !isset($this->config["login_token"])) return false;
		$this->request_remote_key();
		$result = $this->request("POST", "/mobile-auth-service/v1/pin/login/", array(
			"Authorization" => strval($this->config["login_token"]),
			"signature" => hash_hmac("sha256", implode("|", array(strval($this->config["login_token"]), hash("sha256", strval($this->config["tmn_id"]).strval($this->config["pin"])))), $this->remote_key_value),
			"X-Device" => $this->remote_key_id,
		), array(
			"pin" => hash("sha256", strval($this->config["tmn_id"]).strval($this->config["pin"]))
		));
		if (isset($result["data"]["access_token"])) $this->updateConfig("access_token", $result["data"]["access_token"]);
		return $result;

	}

	public function Logout () {
		if (!isset($this->config["access_token"])) return false;
		return $this->request("POST", "/api/v1/signout/".strval($this->config["access_token"]));
	}

	public function GetProfile () {
		if (!isset($this->config["access_token"])) return false;
		return $this->request("GET", "/user-profile-composite/v1/users/", array(
			"Authorization" => strval($this->config["access_token"])
		));
	}

	public function GetBalance () {
		if (!isset($this->config["access_token"])) return false;
		return $this->request("GET", "/user-profile-composite/v1/users/balance/", array(
			"Authorization" => strval($this->config["access_token"])
		));
	}

	public function GetTransaction ($limit = 50, $page = 1, $start_date = null, $end_date = null) {
        if (!isset($this->config["access_token"])) return false;
        if (is_null($start_date) && is_null($end_date)) $start_date = date("Y-m-d", strtotime("-30 days") - date("Z") + 25200);
        if (is_null($end_date)) $end_date = date("Y-m-d", strtotime("+1 day") - date("Z") + 25200);
        if (is_null($start_date) || is_null($end_date)) return false;
        $query = http_build_query(array(
            "start_date" => strval($start_date),
            "end_date" => strval($end_date),
            "limit" => intval($limit),
            "page" => intval($page)
        ));
		$this->request_remote_key();
        return $this->request("GET", "/history-composite/v1/users/transactions/history/?".$query, array(
            "Authorization" => strval($this->config["access_token"]),
            "Signature" => hash_hmac("sha256", rtrim($this->mobile_api_endpoint, "/")."/history-composite/v1/users/transactions/history/?".$query, $this->remote_key_value),
            "X-Device" => $this->remote_key_id
		));
	}
	
	public function GetTransactionReport ($report_id) {
        if (!isset($this->config["access_token"])) return false;
		$this->request_remote_key();
        return $this->request("GET", "/history-composite/v1/users/transactions/history/detail/".$report_id, array(
            "Authorization" => strval($this->config["access_token"]),
            "Signature" => hash_hmac("sha256", rtrim($this->mobile_api_endpoint, "/")."/history-composite/v1/users/transactions/history/detail/".$report_id, $this->remote_key_value),
            "X-Device" => $this->remote_key_id
        ));
    }

	public function TopupCashcard ($cashcard) {
		if (!isset($this->config["access_token"])) return false;
		return $this->request("POST", "/api/v1/topup/mobile/".$this->getUUIDv4()."/".strval($this->config["access_token"])."/cashcard/".$cashcard);
	}

	public function DraftTransferP2P ($mobile_number, $amount) {
		if (!isset($this->config["access_token"])) return false;
		$this->request_remote_key();
		return $this->request("POST", "/transfer-composite/v1/p2p-transfer/draft-transactions/", array(
			"Authorization" => strval($this->config["access_token"]),
			"X-Device" => $this->remote_key_id
		), array(
			"amount" => number_format(str_replace(",", "", strval($amount)), 2, ".", ""),
			"mobileNumber" => str_replace(array("-", " "), "", strval($mobile_number)),
			"signature" => hash_hmac("sha256", implode("|", array(number_format(str_replace(",", "", strval($amount)), 2, ".", ""), str_replace(array("-", " "), "", strval($mobile_number)))), $this->remote_key_value)
		));
	}

	public function ConfirmTransferP2P ($personal_message = "", $wait_processing = true, $draft_transaction_id = null, $reference_key = null) {
		if (!isset($this->config["access_token"])) return false;
		if (is_null($draft_transaction_id) && isset($this->data["draft_transaction_id"])) $draft_transaction_id = $this->data["draft_transaction_id"];
		if (is_null($reference_key) && isset($this->data["reference_key"])) $reference_key = $this->data["reference_key"];
		if (is_null($draft_transaction_id) || is_null($reference_key)) return false;
		$result = $this->request("PUT", "/transfer-composite/v1/p2p-transfer/draft-transactions/".strval($draft_transaction_id), array(
			"Authorization" => strval($this->config["access_token"])
		), array(
			"personal_message" => strval($personal_message)
		));
		if (isset($result["data"]["transaction_id"])) {
			$transaction_id = $result["data"]["transaction_id"];
			$result = $this->request("POST", "/transfer-composite/v1/p2p-transfer/transactions/".$transaction_id."/", array(
				"Authorization" => strval($this->config["access_token"])
			), array(
				"reference_key" => strval($reference_key)
			));
			if ($wait_processing) {
				for ($i = 0; $i < 10; $i++) {
					if (isset($result["data"]["transfer_status"])) {
						if ($result["data"]["transfer_status"] === "PROCESSING") {
							if ($i > 0) sleep(1);
							$result = $this->request("GET", "/transfer-composite/v1/p2p-transfer/transactions/".$transaction_id."/status/", array(
								"Authorization" => strval($this->config["access_token"])
							));
						} else {
							break;
						}
					} else {
						break;
					}
				}
			}
			if (isset($result["data"]["transfer_status"])) {
				$this->data["transaction_id"] = $transaction_id;
			}
		}
		return $result;
	}

	public function GetDetailTransferP2P ($transaction_id = null) {
		if (!isset($this->config["access_token"])) return false;
		if (is_null($transaction_id) && isset($this->data["transaction_id"])) $transaction_id = $this->data["transaction_id"];
		if (is_null($transaction_id)) return false;
		return $this->request("GET", "/transfer-composite/v1/p2p-transfer/transactions/".strval($transaction_id)."/detail/", array(
			"Authorization" => strval($this->config["access_token"]),
			"X-Device" => $this->remote_key_id
		));
	}

	public function DraftBuyCashcard ($amount, $mobile_number) {
		if (!isset($this->config["access_token"])) return false;
		return $this->request("POST", "/api/v1/buy/e-pin/draft/verifyAndCreate/".strval($this->config["access_token"]), array(), array(
			"amount" => str_replace(",", "", strval($amount)),
			"recipientMobileNumber" => str_replace(array("-", " "), "", strval($mobile_number))
		));
	}

	public function ConfirmBuyCashcard ($otp_code, $wait_processing = true, $draft_transaction_id = null, $mobile_number = null, $otp_reference = null) {
		if (!isset($this->config["access_token"])) return false;
		if (is_null($draft_transaction_id) && isset($this->data["draftTransactionID"])) $draft_transaction_id = $this->data["draftTransactionID"];
		if (is_null($mobile_number) && isset($this->data["mobileNumber"])) $mobile_number = $this->data["mobileNumber"];
		if (is_null($otp_reference) && isset($this->data["otpRefCode"])) $otp_reference = $this->data["otpRefCode"];
		if (is_null($draft_transaction_id) || is_null($mobile_number) || is_null($otp_reference)) return false;
		$result = $this->request("PUT", "/api/v1/buy/e-pin/confirm/".strval($draft_transaction_id)."/".strval($this->config["access_token"]), array(), array(
			"mobileNumber" => str_replace(array("-", " "), "", strval($mobile_number)),
			"otpRefCode" => strval($otp_reference),
			"otpString" => strval($otp_code)
		));
		if (isset($result["data"]["status"]) && $result["data"]["status"] === "VERIFIED") {
			$transaction_id = strval($draft_transaction_id);
			if ($wait_processing) {
				for ($i = 0; $i < 10; $i++) {
					if (isset($result["data"]["status"])) {
						if ($result["data"]["status"] === "VERIFIED" || $result["data"]["status"] === "PROCESSING") {
							if ($i > 0) sleep(1);
							$result = $this->request("GET", "/api/v1/buy/e-pin/".$transaction_id."/status/".strval($this->config["access_token"]));
						} else {
							break;
						}
					} else {
						break;
					}
				}
			}
			if (isset($result["data"]["status"])) {
				$this->data["transaction_id"] = $transaction_id;
			}
		}
		return $result;
	}
	
	public function GetDetailBuyCashcard ($transaction_id = null) {
		if (!isset($this->config["access_token"])) return false;
		if (is_null($transaction_id) && isset($this->data["transaction_id"])) $transaction_id = $this->data["transaction_id"];
		if (is_null($transaction_id)) return false;
		return $this->request("GET", "/api/v1/buy/e-pin/".strval($transaction_id)."/details/".strval($this->config["access_token"]));
	}
}
