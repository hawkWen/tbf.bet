<?php

class scb{

    private $cookie = __DIR__."/scb/temp/scb.txt";

    protected $bank_account;

    private $headers = array(
        'Content-Type: application/x-www-form-urlencoded',
        'Sec-Fetch-User: ?1',
        'Upgrade-Insecure-Requests: 1',
        'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/79.0.3945.130 Safari/537.36'
    );

    public function __construct($username,$password,$bank_account){
        if (!file_exists(__DIR__.'/scb/temp'.$bank_account)) {
            mkdir(__DIR__.'/scb/temp'.$bank_account, 0777, true);
        }
        $this->username = $username;
        $this->password = $password;

        $this->bank_account = $bank_account;
        $this->delete_files(__DIR__."/scb/temp{$bank_account}/");
        $this->Login($this->username,$this->password);
    }

    private function curl($url,$method,$header = array(),$data = "",$res = false,$save = false, $file_name , $save_file = false){

        $curl = curl_init();

        if($save_file){
            $fp = fopen(__DIR__."/scb/temp{$this->bank_account}/".$file_name.".txt", "w+");
            curl_setopt_array($curl,array(
                CURLOPT_FILE => $fp
            ));
        }

        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_CUSTOMREQUEST => $method,
            CURLOPT_COOKIEFILE => $this->cookie
        ));

        if($data){
            curl_setopt_array($curl,array(
                CURLOPT_POSTFIELDS => $data,
            ));
        }

        if($header){
            curl_setopt_array($curl,array(
                CURLOPT_HTTPHEADER => $header
            ));
        }

        if($save){
            curl_setopt_array($curl,array(
                CURLOPT_COOKIEJAR => $this->cookie,
            ));
        }

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            echo "cURL Error #:" . $err;
        } else {
            if($res) return $response;
        }
    }

    private function delete_files($target) {
        if(is_dir($target)){
            $files = glob( $target . '*', GLOB_MARK ); //GLOB_MARK adds a slash to directories returned
            // print_r($files);
            foreach( $files as $file ){
                $this->delete_files( $file );
            }
        } elseif(is_file($target)) {
            unlink( $target );
        }
    }

    private function DOMXPath($html,$qry){
        $doc = new DOMDocument();
        @$doc->loadHTML($html);
        $xpath = new DOMXPath($doc);
        $nodeList = $xpath->query($qry);

        return $nodeList;
    }

    private function innerHTML($node) {
        return implode(array_map([$node->ownerDocument,"saveHTML"],iterator_to_array($node->childNodes)));
    }

    private function Login($username,$password){

        $_data = array(
            "LANG"=>"E",
            "LOGIN"=>$username,
            "PASSWD"=>$password,
            "lgin.x"=>0,
            "lgin.y"=>0

        );

        $data = http_build_query($_data);
        $this->curl('https://www.scbeasy.com/online/easynet/page/lgn/login.aspx','POST',$this->headers,$data,false,true,'temp_file_1',true);

        $temp1 = file_get_contents(__DIR__."/scb/temp{$this->bank_account}/temp_file_1.txt");
        $SESSIONEASY1 = $this->DOMXPath($temp1,"//input[@name='SESSIONEASY']/@value");

        if($SESSIONEASY1[0]){
            $first_data = array(
                "SESSIONEASY" => $SESSIONEASY1[0]->nodeValue
            );

            $firstdata = http_build_query($first_data);
            $this->curl('https://www.scbeasy.com/online/easynet/page/firstpage.aspx','POST',$this->headers,$firstdata,false,true,'temp_file_2',true);

            $temp2 = file_get_contents(__DIR__."/scb/temp{$this->bank_account}/temp_file_2.txt");
            $SESSIONEASY2 = $this->DOMXPath($temp1,"//input[@name='SESSIONEASY']/@value");

            $file = fopen(__DIR__."/scb/temp{$this->bank_account}/sessioneasy.txt","w+");
            fwrite($file,$SESSIONEASY1[0]->nodeValue);
            fclose($file);
        } else {
            return ["status" => false, "message" => "Cannot Login To API"];
        }
    }

    private function getAccBnk(){
        $myfile = fopen(__DIR__."/scb/temp{$this->bank_account}/sessioneasy.txt", "r") or die("Unable to open file!");
        $sessioneasy = fread($myfile,filesize(__DIR__."/scb/temp{$this->bank_account}/sessioneasy.txt"));
        fclose($myfile);

        $SESSIONEASY = array(
            "SESSIONEASY" => $sessioneasy
        );

        $data = http_build_query($SESSIONEASY);
        $this->curl('https://www.scbeasy.com/online/easynet/page/acc/acc_mpg.aspx','POST',$this->headers,$data,false,false,'acc_mpg',true);

        $temp_file_2 = file_get_contents(__DIR__."/scb/temp{$this->bank_account}/temp_file_2.txt");

        $__VIEWSTATE = $this->DOMXPath($temp_file_2,"//input[@id='__VIEWSTATE']/@value");
        $__VIEWSTATEGENERATOR = $this->DOMXPath($temp_file_2,"//input[@id='__VIEWSTATEGENERATOR']/@value");

        $acc_mpg = array(
            "SESSIONEASY" => $sessioneasy,
            "__EVENTARGUMENT" => "",
            "__EVENTTARGET" => 'ctl00$DataProcess$SaCaGridView$ctl02$SaCaView_LinkButton',
            "__VIEWSTATE=" => $__VIEWSTATE[0]->nodeValue,
            "__VIEWSTATEGENERATOR" => $__VIEWSTATEGENERATOR[0]->nodeValue
        );

        $acc_mpg_data = http_build_query($acc_mpg);

        $this->curl('https://www.scbeasy.com/online/easynet/page/acc/acc_mpg.aspx','POST',$this->headers,$acc_mpg_data,true,false,'acc_mpg',true);
        $this->curl('https://www.scbeasy.com/online/easynet/page/acc/acc_bnk_bln.aspx','POST',$this->headers,$data,true,false,'acc_bnk_bln',true);
        $this->curl('https://www.scbeasy.com/online/easynet/page/acc/acc_bnk_tst.aspx','POST',$this->headers,$data,true,false,'acc_bnk_tst',true);
    }

    public function Transaction(){
        $this->getAccBnk();
        @$bnk_tst = file_get_contents(__DIR__."/scb/temp{$this->bank_account}/acc_bnk_tst.txt");
        $arr = [
            'status' => true,
            'transactions' => []
        ];
        if($bnk_tst){
            $table = $this->DOMXPath($bnk_tst,"//table[@id='DataProcess_GridView']/tr");
            for ($i=1; $i < sizeof($table)-1; $i++) {
                $td = $this->DOMXPath($this->innerHTML($table[$i]),"//td[@class='bd_th_blk11_rtlt10_tpbt5']");

                preg_match_all ("/SCB x(.*) /U", $td[6]->nodeValue, $scbbank);
                preg_match_all ("/ ((.*)) \/X([0-9]+)([0-9]+)([0-9]+)([0-9]+)([0-9]+)([0-9]+)/U", $td[6]->nodeValue, $otherbank);

                $bankno = "";
                if($scbbank[0]){
                    $bankno =  str_replace(" x","_",implode($scbbank[0]));
                } else {
                    $bankno = str_replace("(","",str_replace(") ","_",str_replace("/X","",implode($otherbank[0]))));
                }

                $withdraw = str_replace("+","",$td[4]->nodeValue);
                //
                $deposit = str_replace("+","",$td[5]->nodeValue);

                if($td[2]->nodeValue === 'X1') {

                    $data = [
                        "date" => $td[0]->nodeValue,
                        "time" => $td[1]->nodeValue,
                        "transaction" => $td[2]->nodeValue,
                        "channel" => $td[3]->nodeValue,
                        "withdrawal" => $withdraw,
                        "deposits" => $deposit,
                        "description" => trim($bankno),
                    ];

                    $arr['status'] = true;

                    array_push($arr['transactions'],$data);

                }
            }

            return $arr;
        } else {
            $arr = [
                'status' => false,
                'transactions' => []
            ];
        }
    }
}
?>
