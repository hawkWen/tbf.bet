<?php

namespace App\Helpers;

class BotSCBPin
{
    private $deviceId;
    private $api_auth;
    public $account_num;
    private $ch;

    protected $bank_account;

    public function __construct($bank_account) {

        $this->bank_account = $bank_account;

    }

    public function preloadauth($deviceid)
    {
        $curl = curl_init();
        curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://fasteasy.scbeasy.com:8443/v3/login/preloadandresumecheck',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_HEADER => 1,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS =>'{"deviceId":"'.$deviceid.'","jailbreak":"0","tilesVersion":"41","userMode":"INDIVIDUAL"}',
        CURLOPT_HTTPHEADER => array(
            'Accept-Language: th',
            'scb-channel: APP',
            'user-agent: Android/10;FastEasy/3.51.0/5423',
            'Content-Type: application/json;charset=UTF-8',
            'Content-Length: 111',
            'Host: fasteasy.scbeasy.com:8443',
            'Connection: Keep-Alive'
        ),
        ));
        $response = curl_exec($curl);
        $headers = array();
        $header_text = substr($response, 0, strpos($response, "\r\n\r\n"));
        foreach (explode("\r\n", $header_text) as $i => $line){
            if ($i === 0) {
                $headers['http_code'] = $line;
            } else {
                list ($key, $value) = explode(': ', $line);
                $headers[$key] = $value;
            }
            
        }

        return $headers;
    }

    public function preauth($apiauth)
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://fasteasy.scbeasy.com:8443/isprint/soap/preAuth',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS =>'{"loginModuleId":"PseudoFE"}',
        CURLOPT_HTTPHEADER => array(
            'Accept-Language: th',
            'scb-channel: APP',
            'Api-Auth: '.str_replace("\r\n","",$apiauth),
            'user-agent: Android/10;FastEasy/3.46.0/4926',
            'Content-Type: application/json;charset=UTF-8',
            'Content-Length: 28',
            'Host: fasteasy.scbeasy.com:8443',
            'Connection: Keep-Alive',
        ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        return $response;
    }

    public function encryptscb($Sid,$ServerRandom,$pubKey,$pin,$hashType)
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://service.fast-x.app/pin/encrypt',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS =>'Sid='.$Sid.'&ServerRandom='.$ServerRandom.'&pubKey='.$pubKey.'&pin='.$pin.'&hashType='.$hashType,
        CURLOPT_HTTPHEADER => array(
            'Content-Type: application/x-www-form-urlencoded'
        ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        return $response;
    }

    public function scblogin($authid,$device,$pseudoPin,$Sid)
    {
        // 
        $this->bank_account->update([
            'active' => 1,
        ]);

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://fasteasy.scbeasy.com:8443/v3/login',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_HEADER => 1,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS =>'{"deviceId":"'.$device.'","pseudoPin":"'.$pseudoPin.'","pseudoSid":"'.$Sid.'"}',
            CURLOPT_HTTPHEADER => array(
                'Accept-Language: th',
                'scb-channel: APP',
                'Api-Auth: '.str_replace("\r\n","",$authid),
                'user-agent: Android/10;FastEasy/3.46.0/4926',
                'Content-Type: application/json;charset=UTF-8',
                'Content-Length: 817',
                'Host: fasteasy.scbeasy.com:8443',
                'Connection: Keep-Alive',
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        $headers = array();
        $header_text = substr($response, 0, strpos($response, "\r\n\r\n"));
        foreach (explode("\r\n", $header_text) as $i => $line){
            if ($i === 0) {
                $headers['http_code'] = $line;
            } else {
                list ($key, $value) = explode(': ', $line);
                $headers[$key] = $value;
            }
            
        }
        
        $this->bank_account->update([
            'active' => 0,
        ]);
        return $headers;
    }

    public function setBaseParam()
    {
        $deviceId = Helper::decryptString($bank_account->app_id, 1, 'base64');
        $api_auth = Helper::decryptString($bank_account->token, 1, 'base64');
        $pin = $this->bank_account->pin;

        $diiHours = Carbon::parse($bank_account->token_refresh)->diffInHours(Carbon::now());
        if ($diiHours >= 3) {
            $preload = $api->preloadauth($deviceId);
            $e2ee = $api->preauth($preload['Api-Auth']);
            $e2eejson = json_decode($e2ee,true);
            $hashType = $e2eejson['e2ee']['pseudoOaepHashAlgo'];
            $Sid = $e2eejson['e2ee']['pseudoSid'];
            $ServerRandom = $e2eejson['e2ee']['pseudoRandom'];
            $pubKey = $e2eejson['e2ee']['pseudoPubKey'];
            
            $encryptscb = $api->encryptscb($Sid,$ServerRandom,$pubKey,$pin,$hashType);

            if(isset($encryptscb['Api-Auth'])) {
                \Session::flash('alert-warning', 'บัญชีธนาคารขัดข้อง กรุณาติดต่อเจ้าหน้าที่ ค่ะ');
                return \redirect()->back();
            }

            $scblogin = $api->scblogin($preload['Api-Auth'],$deviceId,$encryptscb,$Sid);
            $api_auth = $scblogin['Api-Auth'];

            $this->bank_account->update([
                'token' => Helper::encryptString($scblogin['Api-Auth'], 1, 'base64'),
                'otp_updated_at' => Carbon::now()
            ]);

            $api_auth = Helper::decryptString($bank_account->token, 1, 'base64');
        }

        $this->api_auth = $api_auth;
        $this->account_num = $this->bank_account->account;
    }

    public function getTransaction()
    {
        $startDate = date('Y-m-d');
        $endDate = date('Y-m-d', strtotime("+1 day"));
        $curl = curl_init();
        curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://fasteasy.scbeasy.com:8443/v2/deposits/casa/transactions',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS =>'{"accountNo":"'.$this->account_num.'","endDate":"'.$endDate.'","pageNumber":"1","pageSize":20,"productType":"2","startDate":"'.$startDate.'"}',
        CURLOPT_HTTPHEADER => array(
            'Accept-Language: th',
            'scb-channel: APP',
            'Api-Auth: '.$this->api_auth,
            'user-agent: Android/10;FastEasy/3.46.0/4926',
            'Content-Type: application/json;charset=UTF-8',
            'Content-Length: 123',
            'Host: fasteasy.scbeasy.com:8443',
            'Connection: Keep-Alive',
            'Accept-Encoding: gzip',
        ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);

        $json = json_decode($response, true);
    
        if (isset($json['status'])) {
            if ($json['status']['description'] === 'สำเร็จ') {
                foreach($json['data']['txnList']  as $v) {
                    // if($v['txnCode']['code'] == 'X1') {
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

        return [];
    }
    
    public function checktransfer($accountFrom,$accountTo,$accountToBankCode,$amount,$transferType,$apiauth)
    {
        $curl = curl_init();
        curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://fasteasy.scbeasy.com/v2/transfer/verification',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS =>'{"accountFrom":"'.$accountFrom.'","accountFromType":"2","accountTo":"'.$accountTo.'","accountToBankCode":"'.$accountToBankCode.'","amount":"'.$amount.'","annotation":null,"transferType":"'.$transferType.'"}',
        CURLOPT_HTTPHEADER => array(
            'Accept-Language: th',
            'scb-channel: APP',
            'Api-Auth: '.$apiauth,
            'user-agent: Android/10;FastEasy/3.46.0/4926',
            'Content-Type: application/json;charset=UTF-8',
            'Content-Length: 156',
            'Host: fasteasy.scbeasy.com:8443',
            'Connection: Keep-Alive',
        ),
        ));
        $response = curl_exec($curl);
        curl_close($curl);
        return $response;
    }

    public function confirmtransfer($accountFrom,$accountFromName,$accountTo,$accountToBankCode,$accountToName,$amount,$pccTraceNo,$sequence,$terminalNo,$transactionToken,$transferType,$apiauth)
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://fasteasy.scbeasy.com/v3/transfer/confirmation',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS =>'{"accountFrom":"'.$accountFrom.'","accountFromName":"'.$accountFromName.'","accountFromType":"2","accountTo":"'.$accountTo.'","accountToBankCode":"'.$accountToBankCode.'","accountToName":"'.$accountToName.'","amount":"'.$amount.'","botFee":0.0,"channelFee":0.0,"fee":0.0,"feeType":"","pccTraceNo":"'.$pccTraceNo.'","scbFee":0.0,"sequence":"'.$sequence.'","terminalNo":"'.$terminalNo.'","transactionToken":"'.$transactionToken.'","transferType":"'.$transferType.'"}',
        CURLOPT_HTTPHEADER => array(
            'Accept-Language: th',
            'scb-channel: APP',
            'Api-Auth: '.$apiauth,
            'user-agent: Android/10;FastEasy/3.46.0/4926',
            'Content-Type: application/json;charset=UTF-8',
            'Content-Length: 468',
            'Host: fasteasy.scbeasy.com:8443',
            'Connection: Keep-Alive',
        ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        echo $response;
    }
    
    public function getSummary()
    {
        $this->ch = curl_init();
        curl_setopt($this->ch, CURLOPT_URL, 'https://fasteasy.scbeasy.com:8443/v2/deposits/summary');
        curl_setopt($this->ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($this->ch, CURLOPT_POST, 1);
        curl_setopt($this->ch, CURLOPT_POSTFIELDS, "{\"depositList\":[{\"accountNo\":\"$this->account_num\"}],\"numberRecentTxn\":2,\"tilesVersion\":\"26\"}");

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

    public function getVerify($accountTo, $accountToBankCode, $amount)
    {
        $this->ch = curl_init();
        $transferType = "ORFT";

        if ($accountToBankCode === "014") {
            $transferType = "3RD";
        }
        curl_setopt($this->ch, CURLOPT_URL, 'https://fasteasy.scbeasy.com:8443/v2/transfer/verification');

        curl_setopt($this->ch, CURLOPT_POSTFIELDS, "{\"accountFrom\":\"$this->account_num\",\"accountFromType\":\"2\",\"accountTo\":\"$accountTo\",\"accountToBankCode\":\"$accountToBankCode\",\"amount\":\"$amount\",\"annotation\":null,\"transferType\":\"$transferType\"}");
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
        $this->ch = curl_init();
        $verify = $this->getVerify($accountTo, $accountToBankCode, $amount);
        if ($verify['status']['description'] != 'สำเร็จ') {
            return ['status' => false, 'msg' => $verify['status']['description']];
        }
        curl_setopt($this->ch, CURLOPT_URL, 'https://fasteasy.scbeasy.com:8443/v3/transfer/confirmation');
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
        if (curl_errno($this->ch)) {
            return ['status' => false, 'msg' => 'ผิดพลาด curl'];
        }
        $data = json_decode($result, true);
        if ($data['status']['description'] != "สำเร็จ") {
            return ['status' => false, 'msg' => $data['status']['description']];
        }
        return ['status' => true];
    }
}