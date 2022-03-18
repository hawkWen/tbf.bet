<?php

namespace App\Helpers;

class BotSCB
{

    private $deviceId;
    private $ApiRefresh;
    public $accnum;
    private $ch;
    private $api_auth;
    public $accdisp;
    private $api = "fasteasy.scbeasy.com:8443";
    protected $bank_account;

    public function __construct($bank_account) {

        $this->bank_account = $bank_account;

    }

    /**
     *@param string $deviceId device Id ที่เก้บมาจากการลงทะเบียน proxy
     *@param string $ApiRefresh Api Refresh ที่เก้บมาจากการลงทะเบียน proxy
     *@return void no return data
     */
    public function setLogin($deviceId, $ApiRefresh)
    {
        $this->deviceId = $deviceId;
        $this->ApiRefresh = $ApiRefresh;
    }
    /**
     *@param string $accnum เลขบัญชีไม่ต้องมี - เป็นตัวเลขอย่างเดียว 10 ตัว
     *@return void no return data
     */
    public function setAccountNumber($accnum)
    {
        if (!is_string($accnum)) {
            die("Account number must be string.");
        }
        if (strlen($accnum) !== 10) {
            die("Account number must be 10 digits.");
        }
        $this->accnum = $accnum;
        $this->accdisp = substr($accnum, 0, 3) . '-' . substr($accnum, 3, 1) . '-' . substr($accnum, 4, 5) . '-' . substr($accnum, 9, 1);
    }

    /**
     *@return bool ถ้า login สำเร็จจะได้ค่า true ถ้าไม่จะได้ false
     */
    public function login()
    {
        $this->bank_account->update([
            'active' => 1,
        ]);
        $this->ch = curl_init();
        curl_setopt($this->ch, CURLOPT_URL, 'https://fasteasy.scbeasy.com:8443/v1/login/refresh');
        curl_setopt($this->ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($this->ch, CURLOPT_POST, 1);
        curl_setopt($this->ch, CURLOPT_POSTFIELDS, '{"deviceId":"'  . $this->deviceId . '"}');
        $headers = array();
        $headers[] = 'Api-Refresh: ' . $this->ApiRefresh;
        curl_setopt($this->ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt(
            $this->ch,
            CURLOPT_HEADERFUNCTION,
            function ($curl, $header) use (&$headers) {
                $len = strlen($header);
                $header = explode(':', $header, 2);
                if (count($header) < 2) // ignore invalid headers
                    return $len;

                $headers[strtolower(trim($header[0]))][] = trim($header[1]);

                return $len;
            }
        );
        $result = curl_exec($this->ch);
        if (curl_errno($this->ch)) {
            return false;
        }
        if(isset($headers['api-auth'])) {
            $this->api_auth = $headers['api-auth'][0];
            $json = json_decode($result, true);
            if (isset($json['status'])) {
                if ($json['status']['description'] === 'Success') {
                    return true;
                }
            }
        }

        $this->bank_account->update([
            'active' => 0,
        ]);
        return false;
    }
    /**
     *@param string|null $startDate เวลาเริ่มต้น date('Y-m-d')
     *@param string|null $endDate เวลาหลัง date('Y-m-d')
     *@param int|null $pageSize จำนวนที่จะดึง ค่าเริ่มต้น 50
     *@param int|null $pageNumber หน้าที่จะดึง ค่าเริ่มต้น 1 
     *@return array ส่ง transactions ของบัญชี ถ้าไม่มีหรือมีปัญหาจะส่ง array ว่างกลับไป
     */
    public function getTransaction(string $startDate = null, string $endDate = null, int $pageSize = null, int $pageNumber = null)
    {
        if ($startDate === null) {
            $startDate = date('Y-m-d');
        }
        if ($endDate === null) {
            $endDate = date('Y-m-d', strtotime("+1 day"));
        }
        if ($pageNumber === null) {
            $pageNumber = 1;
        }
        if ($pageSize === null) {
            $pageSize = 50;
        }
        curl_setopt($this->ch, CURLOPT_URL, 'https://' . $this->api . '/v2/deposits/casa/transactions');
        curl_setopt($this->ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($this->ch, CURLOPT_POSTFIELDS, "{\"accountNo\":\"" . $this->accnum . "\",\"endDate\":\"$endDate\",\"pageNumber\":\"$pageNumber\",\"pageSize\":$pageSize,\"productType\":\"2\",\"startDate\":\"$startDate\"}");
        curl_setopt($this->ch, CURLOPT_POST, 1);

        $headers = array();
        $headers[] = 'Api-Auth: ' . $this->api_auth;
        $headers[] = 'Accept-Language: th';
        $headers[] = 'Content-Type: application/json; charset=UTF-8';
        curl_setopt($this->ch, CURLOPT_HTTPHEADER, $headers);

        $result = curl_exec($this->ch);
        if (curl_errno($this->ch)) {
            return array();
        }
        // echo $result;
        $json = json_decode($result, true);
        if (isset($json['status'])) {
            if ($json['status']['description'] === 'สำเร็จ') {
                $data = [];
                foreach($json['data']['txnList']  as $v) {
                    // echo '<pre>';
                    // print_r($v);
                    if($v['txnCode']['code'] == 'X1') {
                        preg_match_all ("/SCB x(.*) /U", $v['txnRemark'], $scbbank);
                        preg_match_all ("/ ((.*)) \/X([0-9]+)([0-9]+)([0-9]+)([0-9]+)([0-9]+)([0-9]+)/U", $v['txnRemark'], $otherbank);
                        $bankno = "";
                        if($scbbank[0]){
                            $bankno =  str_replace(" x","_",implode($scbbank[0]));
                            $code = 'SCB';
                            $bank_account = substr(trim($bankno), 4);
                        } else {
                            $bankno = str_replace("(","",str_replace(") ","_",str_replace("/X","",implode($otherbank[0]))));
                            $code = substr(trim($bankno), 0, 3);
                            $bank_account = $code === 'KBA' ? substr(trim($bankno), 6) : substr(trim($bankno), 4);
                            $code = $code === 'KBA' ? substr(trim($bankno), 0, 5) : $code;
                        }

                    } else {
                        preg_match_all ("/SCB x(.*) /U", $v['txnRemark'], $scbbank);
                        preg_match_all ("/ ((.*)) \/X([0-9]+)([0-9]+)([0-9]+)([0-9]+)([0-9]+)([0-9]+)/U", $v['txnRemark'], $otherbank);

                        if($scbbank[0]){
                            $bankno =  $v['txnRemark'];
                            $code = 'SCB';
                            $bank_account = '';
                        } else {
                            $bankno = $v['txnRemark'];
                            $code = substr(trim($bankno), 0, 3);
                            $bank_account = '';
                            $code = $code === 'KBA' ? substr(trim($bankno), 0, 5) : $code;
                        }

                    }
                    
                    $Date = date("Y-m-d", strtotime($v['txnDateTime']));
                    $Time = date("H:i:s", strtotime($v['txnDateTime']));
                    $data[] = [
                            "date" => $Date,
                            "time" => $Time,
                            "code_type" => $v['txnCode']['code'],
                            "deposits" => $v['txnAmount'],
                            "description" => trim($bankno),
                            'code' => trim($code),
                            'bank_account' => trim($bank_account)
                    ];
                }
                return $data;
            } else {
                return $data[] = [];
            }
        }
        return array();
    }
    /**
     *@return array ส่ง สรุป ของบัญชี ถ้าไม่มีหรือมีปัญหาจะส่ง array ว่างกลับไป
     */
    public function getSummary()
    {
        curl_setopt($this->ch, CURLOPT_URL, 'https://' . $this->api . '/v2/deposits/summary');
        curl_setopt($this->ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($this->ch, CURLOPT_POST, 1);
        curl_setopt($this->ch, CURLOPT_POSTFIELDS, "{\"depositList\":[{\"accountNo\":\"$this->accnum\"}],\"numberRecentTxn\":2,\"tilesVersion\":\"26\"}");

        $headers = array();
        $headers[] = 'Api-Auth: ' . $this->api_auth;
        $headers[] = 'Accept-Language: th';
        $headers[] = 'Content-Type: application/json; charset=UTF-8';
        curl_setopt($this->ch, CURLOPT_HTTPHEADER, $headers);

        $result = curl_exec($this->ch);
        if (curl_errno($this->ch)) {
            return array();
        }
        return json_decode($result, true);
    }
    /**
     *@return array ส่ง ประวัติการโอน ถ้าไม่มีหรือมีปัญหาจะส่ง array ว่างกลับไป
     */
    public function getHistory()
    {
        curl_setopt($this->ch, CURLOPT_URL, 'https://' . $this->api . '/v1/transfer/history?pagingOffset=0');
        curl_setopt($this->ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($this->ch, CURLOPT_CUSTOMREQUEST, 'GET');

        $headers = array();
        $headers[] = 'Api-Auth: ' . $this->api_auth;
        $headers[] = 'Accept-Language: th';
        $headers[] = 'Content-Type: application/json; charset=UTF-8';
        curl_setopt($this->ch, CURLOPT_HTTPHEADER, $headers);

        $result = curl_exec($this->ch);
        if (curl_errno($this->ch)) {
            return array();
        }
        return json_decode($result, true);
    }
    /**
     *@return array ส่ง ธนาคารที่โอนได้กลับไป ถ้าไม่มีหรือมีปัญหาจะส่ง array ว่างกลับไป
     */
    public function getEligiblebanks()
    {
        curl_setopt($this->ch, CURLOPT_URL, 'https://' . $this->api . '/v1/transfer/eligiblebanks');
        curl_setopt($this->ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($this->ch, CURLOPT_CUSTOMREQUEST, 'GET');

        $headers = array();
        $headers[] = 'Api-Auth: ' . $this->api_auth;
        $headers[] = 'Accept-Language: th';
        $headers[] = 'Content-Type: application/json; charset=UTF-8';
        curl_setopt($this->ch, CURLOPT_HTTPHEADER, $headers);

        $result = curl_exec($this->ch);
        if (curl_errno($this->ch)) {
            return array();
        }
        return json_decode($result, true);
    }
    /**
     *@return array ส่ง ข้อมูลบัยชีที่จะโอนไป ถ้าไม่มีหรือมีปัญหาจะส่ง array ว่างกลับไป
     */
    public function getVerify($accountTo, $accountToBankCode, $amount)
    {
        $transferType = "ORFT";

        if ($accountToBankCode === "014") {
            $transferType = "3RD";
        }
        curl_setopt($this->ch, CURLOPT_URL, 'https://' . $this->api . '/v2/transfer/verification');

        curl_setopt($this->ch, CURLOPT_POSTFIELDS, "{\"accountFrom\":\"$this->accnum\",\"accountFromType\":\"2\",\"accountTo\":\"$accountTo\",\"accountToBankCode\":\"$accountToBankCode\",\"amount\":\"$amount\",\"annotation\":null,\"transferType\":\"$transferType\"}");
        curl_setopt($this->ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($this->ch, CURLOPT_POST, 1);
        curl_setopt($this->ch, CURLOPT_ENCODING, 'gzip, deflate');

        $headers = array();
        $headers[] = 'Api-Auth: ' . $this->api_auth;
        $headers[] = 'Accept-Language: th';
        $headers[] = 'Content-Type: application/json; charset=UTF-8';
        curl_setopt($this->ch, CURLOPT_HTTPHEADER, $headers);
        $result = curl_exec($this->ch);
        if (curl_errno($this->ch)) {
            return array();
        }
        return json_decode($result, true);
    }
    /**
     *@return arrary ส่ง status => true ถ้าสำเร็จ msg ถ้าผิดพลาดจะบอกว่าเกิดจากอะไร
     */
    public function Transfer($accountTo, $accountToBankCode, $amount)
    {
        $verify = $this->getVerify($accountTo, $accountToBankCode, $amount);
        // if(isset($verify['status'])) {
            if ($verify['status']['description'] != 'สำเร็จ') {
                return ['status' => false, 'msg' => $verify['status']['description']];
            }
        // }
        curl_setopt($this->ch, CURLOPT_URL, 'https://' . $this->api . '/v3/transfer/confirmation');
        $d = $verify['data'];
        curl_setopt($this->ch, CURLOPT_POSTFIELDS, "{\"accountFrom\":\"$accountTo\",\"accountFromName\":\"" . $d['accountFromName'] . "\",\"accountFromType\":\"2\",\"accountTo\":\"" . $d['accountTo'] . "\",\"accountToBankCode\":\"" . $d['accountToBankCode'] . "\",\"accountToName\":\"" . $d['accountToName'] . "\",\"amount\":\"" . $amount . "\",\"botFee\":0.0,\"channelFee\":0.0,\"fee\":0.0,\"feeType\":\"\",\"pccTraceNo\":\"" . $d['pccTraceNo'] . "\",\"scbFee\":0.0,\"sequence\":\"" . $d['sequence'] . "\",\"terminalNo\":\"" . $d['terminalNo'] . "\",\"transactionToken\":\"" . $d['transactionToken'] . "\",\"transferType\":\"" . $d['transferType'] . "\"}");
        curl_setopt($this->ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($this->ch, CURLOPT_POST, 1);
        curl_setopt($this->ch, CURLOPT_ENCODING, 'gzip, deflate');

        $headers = array();
        $headers[] = 'Api-Auth: ' . $this->api_auth;
        $headers[] = 'Accept-Language: th';
        $headers[] = 'Content-Type: application/json; charset=UTF-8';
        curl_setopt($this->ch, CURLOPT_HTTPHEADER, $headers);
        $result = curl_exec($this->ch);
        $response = ['status' => true];
        if (curl_errno($this->ch)) {
            $response = ['status' => false, 'msg' => 'ผิดพลาด curl'];
        }
        $data = json_decode($result, true);
        if(empty($data)) {
            $response = ['status' => false, 'msg' => 'API SCB ERROR'];
        }
        if ($data['status']['description'] != "สำเร็จ") {
            $response = ['status' => false, 'msg' => $data['status']['description']];
        }
        return $response;
    }
}