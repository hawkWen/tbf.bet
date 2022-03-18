<?php 

namespace App\Helpers;

use DOMXPath;
use DOMDocument;

// echo  $api->Adduser($ck, $username  ,'aa123456','บอท สร้าง เองนะ');
// echo $api->Deposit($ck,'Auaz93441','50');
//echo $api->Withdraw($ck,'Ahfu84475','20');
// echo $api->GetBalance($ck,'Auaz93441');
class GClubApi {
    
    private $username;
    
    private $password;
    
    public function Curl($method, $url, $header, $data, $cookie){
		$ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.3) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/77.0.3865.120 Safari/537.36');
        //curl_setopt($ch, CURLOPT_USERAGENT, 'okhttp/3.8.0');
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        if($data){
			curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
		}
		if($cookie){
			curl_setopt($ch, CURLOPT_COOKIESESSION, true);
			curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie);
			curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie);
		}
        return curl_exec($ch);
	}
	public function __construct($user,$pass){
		$this->username = $user;
		$this->password = $pass;
	}
	public function GetAgent(){
		preg_match_all('/^([^\d]+)(\d+)/', $this->username, $match);
		return $match[1][0];
	}
	public function Login($cookie){
	    $header = array(
            "accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.9",
            "cache-control: no-cache",
            "postman-token: 90f6530c-1525-f1d6-6335-d389dfc78982",
            "upgrade-insecure-requests: 1",
            "user-agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/81.0.4044.138 Safari/537.36"
		);
		$url = "http://act.gclub168.com/manage/jxs/login.jsp?languages=Tg&user=".$this->username."&pass=".$this->password;
		$res = $this->Curl('GET',$url,$header,FALSE,$cookie);
		if ($res == ''){
			return  json_encode(['status'=>true,'msg'=>'login success']);
		}else{
			return json_encode(['status'=>false]);
		}
		
	}
	public function main($ck){
		$url = "http://act.gclub168.com/manage/jxs/member/members_add.jsp";
	    $header = array(
		    "accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.9",
            "cache-control: no-cache",
            "upgrade-insecure-requests: 1",
            "Cookie: royal-id-20480-%5BH1%5DACXWSZ_lb_80=".$ck['royal'].";JSESSIONID=".$ck['jessid'],
            "user-agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/81.0.4044.138 Safari/537.36"
		);
		$res = $this->Curl('GET',$url,$header,false,false);
		
	}
	public function Adduser($ck,$username,$password,$name){
		$url = "http://act.gclub168.com/manage/jxs/member/check_mem.jsp?username=A".$this->GetAgent().$username;
	    $header = array(
            "accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.9",
            "cache-control: no-cache",
            "upgrade-insecure-requests: 1",
            "Cookie: royal-id-20480-%5BH1%5DACXWSZ_lb_80=".$ck['royal'].";JSESSIONID=".$ck['jessid'],
            "user-agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/81.0.4044.138 Safari/537.36"
		);
		$res = $this->Curl('POST',$url,$header,false,false);
		if(strpos($res,'บัญชีที่ท่านป้อนใช้งานได้')){
			
			$res = $this->Curl('GET','http://act.gclub168.com/manage/jxs/member/members_add.jsp',$header,false,false);
			$ag_list = $this->DOMXPath($res,"//select[@name='ag_list']/option/@value");
			$ag_list = $ag_list[1]->nodeValue;
			$d = explode('|',$ag_list);
			$aid = $d[3];
			$code = $d[1];
			$d_add = [
                "OK2"=>"ยืนยันการพิมพ์",
                "TingYong_XinYong"=>"-1",
                "TingYong_set"=>"-1",
                "active"=>"1",
                "ag_list"=>$ag_list,
                "aid"=>$aid,
                "alias"=>$name,
                "code"=>$code,
                "count1"=>"3",
                "maxcredit"=>"0",
                "password"=>$password,
                "repassword"=>$password,
                "server_set"=>"-1",
                "type"=>"A",
                "username"=>"A".$this->GetAgent().$username,
                "username5"=>$username,
                "verify"=>"",
            ];
			$data = http_build_query($d_add);
			$res = $this->Curl('POST','http://act.gclub168.com/manage/jxs/member/members_edit.jsp?ok=1',$header,$data,false);
			if(strpos($res,'เพิ่มสมาชิกใหม่สำเร็จ')){
				
				return json_encode(['status'=>true,'username'=>"A".$this->GetAgent().$username,'password'=>$password]);
			}else{
				return json_encode(['status'=>false]);
			}
			
			
		}else{
			return json_encode(['status'=>false,'msg'=>'This username already exists.']);
		}
		
	}
	public function Deposit($ck,$username,$amount){
		$url = "http://act.gclub168.com/manage/jxs/member/members.jsp?sets=1";
	    $header = array(
            "accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.9",
            "cache-control: no-cache",
            "upgrade-insecure-requests: 1",
            "Cookie: royal-id-20480-%5BH1%5DACXWSZ_lb_80=".$ck['royal'].";JSESSIONID=".$ck['jessid'],
            "user-agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/81.0.4044.138 Safari/537.36"
		);
		$res = $this->Curl('GET',$url,$header,false,false);
		// $table = $this->DOMXPath($res,"//table[@id='game_table']/tr/td/text()");
		// print_r($table);
        $id = explode('&Club_Ename='.$username,$res);
		$id = explode('&id=',$id[2]);
		$id = $id[1];
        $res = $this->Curl('GET','http://act.gclub168.com/manage/jxs/member/members_add.jsp',$header,false,false);
		$ag_list = $this->DOMXPath($res,"//select[@name='ag_list']/option/@value");
		$ag_list = $ag_list[1]->nodeValue;
		$d = explode('|',$ag_list);
		$aid = $d[3];
        $res = $this->Curl('GET','http://act.gclub168.com/manage/jxs/member/members_update.jsp?agent_id='.$aid.'&id='.$id.'&c_fid='.$aid.'&c_id='.$id.'&types=1&number='.$amount.'&Remark=',$header,false,false);
        if(strpos($res,'เติมเงินสำเร็จ')){
			return json_encode(['status'=>true]);
		}else{
			return json_encode(['status'=>false]);
		}

		
	}
	public function Withdraw($ck,$username,$amount){
		$url = "http://act.gclub168.com/manage/jxs/member/members.jsp?sets=1";
	    $header = array(
            "accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.9",
            "cache-control: no-cache",
            "upgrade-insecure-requests: 1",
            "Cookie: royal-id-20480-%5BH1%5DACXWSZ_lb_80=".$ck['royal'].";JSESSIONID=".$ck['jessid'],
            "user-agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/81.0.4044.138 Safari/537.36"
		);
		$res = $this->Curl('GET',$url,$header,false,false);
		//$table = $this->DOMXPath($res,"//table[@id='game_table']/tr/td/text()");
		//print_r($table);
		$id = explode('&Club_Ename='.$username,$res);
		$id = explode('&id=',$id[2]);
		$id = $id[1];
		$res = $this->Curl('GET','http://act.gclub168.com/manage/jxs/member/members_add.jsp',$header,false,false);
		$ag_list = $this->DOMXPath($res,"//select[@name='ag_list']/option/@value");
		$ag_list = $ag_list[1]->nodeValue;
		$d = explode('|',$ag_list);
		$aid = $d[3];
		$res = $this->Curl('GET','http://act.gclub168.com/manage/jxs/member/members_update.jsp?agent_id='.$aid.'&id='.$id.'&c_fid='.$aid.'&c_id='.$id.'&types=2&number='.$amount.'&Remark=',$header,false,false);
		if(strpos($res,'ถอนออกสำเร็จ')){
			return ['status'=>true];
		}else{
			return ['status'=>false];
		}
		
	}
	public function genarate_GenerateUsername(){
    
        return rand(10000,100000);
	}
	public function GetBalance($ck,$username){
		$url = "http://act.gclub168.com/manage/jxs/member/members.jsp?sets=1";
	    $header = array(
            "accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.9",
            "cache-control: no-cache",
            "upgrade-insecure-requests: 1",
            "Cookie: royal-id-20480-%5BH1%5DACXWSZ_lb_80=".$ck['royal'].";JSESSIONID=".$ck['jessid'],
            "user-agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/81.0.4044.138 Safari/537.36"
		);
		$res = $this->Curl('GET',$url,$header,false,false);
        // $table = $this->DOMXPath($res,"//table[@id='game_table']/tr/td/text()");
		$id = @explode('&Club_Ename='.$username,$res);
		$id = @explode('&id=',$id[2]);
        $id = @$id[1];
		if($id == null){
			return ['status'=>false,'Balance'=>null,'msg' => 'Success','codeId' => 1];
		}
		$res = $this->Curl('GET','http://act.gclub168.com/manage/jxs/member/members_add.jsp',$header,false,false);
		$ag_list = $this->DOMXPath($res,"//select[@name='ag_list']/option/@value");
		$ag_list = $ag_list[1]->nodeValue;
		$d = explode('|',$ag_list);
		$aid = $d[3];
        $res = $this->Curl('GET','http://act.gclub168.com/manage/jxs/member/members_update.jsp?agent_id='.$aid.'&id='.$id,$header,false,false);
		$money = explode('<font color=ff0000>',$res);
		$money = explode('</font>',$money[1]);
		$money = $money[0];
		if($money == null){
			return ['status'=>false,'Balance'=>null,'msg' => 'Success','codeId' => 0];
		}
		return ['status'=>true,'Balance'=>$money,'msg' => 'Success','codeId' => 0];
	}
	private function DOMXPath($html,$qry){
        $doc = new DOMDocument();
        @$doc->loadHTML($html);
        $xpath = new DOMXPath($doc);
        $nodeList = $xpath->query($qry);
    
        return $nodeList;
    }
	public function genarate_cookie($name){
		$cookie_name = $name.'_'.$this->username;
		file_put_contents('gclub/'.$cookie_name, "");
        $this->cookie = realpath($cookie_name);
		return $this->cookie;
	}
	
	public function getCookie(){
		return $this->cookie;
	}
	public function genarate_Password(){
		$alphabet = "abcdefghijklmnopqrstuwxyzABCDEFGHIJKLMNOPQRSTUWXYZ0123456789";
		$pass = array();
		$alphaLength = strlen($alphabet) - 1;
		for ($i = 0; $i < 8; $i++) {
			$n = rand(0, $alphaLength);
			$pass[] = $alphabet[$n];
		}
		return implode($pass);
	}
	
	public function get_created_cookie($cookie){
		if ($file = fopen($cookie, "r")) {
			while(!feof($file)) {
				$line = fgets($file);
				if(strpos($line, 'royal-id-20480-%5BH1%5DACXWSZ_lb_80')!==false){
					$royal = preg_replace("/\s+/", "", explode("royal-id-20480-%5BH1%5DACXWSZ_lb_80", $line)[1]);
				}
				if(strpos($line, 'JSESSIONID')!==false){
					$jessid = preg_replace("/\s+/", "", explode("JSESSIONID", $line)[1]);
				}

			}
			fclose($file);
		}
		if(empty($royal)||empty($jessid)){
			return false;
		}else{
			return array(
				"royal" => $royal,
				"jessid" => $jessid,
				
			);
		}
	}
	public function get_created_cookie_login($cookie){
		if ($file = fopen($cookie, "r")) {
			while(!feof($file)) {
				$line = fgets($file);
				if(strpos($line, '.ASPXAUTH')!==false){
					$aspx = preg_replace("/\s+/", "", explode(".ASPXAUTH", $line)[1]);
				}
			}
			fclose($file);
		}
		if(empty($aspx)){
			return false;
		}else{
			return array(
				"aspx"=>$aspx
			);
		}
	}
	public function del_cookie($cookie){
		return unlink($cookie);
	}

}
?>