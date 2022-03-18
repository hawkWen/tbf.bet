<?php
error_reporting(1);
set_time_limit(0);
//$scb_user=$scb_user;
//$scb_pass=$scb_pass;
//$amount=$amount;
//$ac_number=$ac_number;
//$ac_bank=$ac_bank;
//$otp=$otp;
$mobile_otp = $mobile_otp;

$sessionfile = $_SERVER["DOCUMENT_ROOT"].'/datafile/scbsession.3ed2ws1qa';

function curl_scb($url,$post_data){
	sleep(1);
	$ch = curl_init($url);
	global $sessionfile;
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);

	curl_setopt($ch, CURLOPT_COOKIEJAR, $sessionfile);
	curl_setopt($ch, CURLOPT_COOKIEFILE, $sessionfile);
	if(is_array($post_data)){
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
	}
	curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:62.0) Gecko/20100101 Firefox/62.0');
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	@curl_setopt ($ch, CURLOPT_FOLLOWLOCATION, 1);
	curl_setopt($ch, CURLOPT_HEADER, 1);
	//curl_setopt($ch, CURLOPT_TIMEOUT, 20);
	$curl_data= curl_exec($ch);
	return $curl_data;
	$curl_error= curl_errno($ch);
	curl_close($ch);
	if($curl_error>0){
		return "error";
	}else{
		return $curl_data;
	}
}
function cutstring_scb($content,$text1,$text2){
	$fcontents2 = stristr($content, $text1);
	$rest2 = substr($fcontents2,strlen($text1));
	$extra2 = stristr($fcontents2, $text2);
	$titlelen2 = strlen($rest2) - strlen($extra2);
	$gettitle2 = trim(substr($rest2, 0, $titlelen2));
	return $gettitle2;
}
$data_display_curlerror=array("status"=>3,"detail"=>"SCB ตอบสนองช้าเกินไป โปรดลองใหม่ในอีก 1 นาที ");
function get_otp_withdraw($scb_user,$scb_pass,$amount,$ac_number,$ac_bank,$mobile_otp){
	global $data_display_curlerror;
	global $sessionfile;
	$last_login=curl_scb("https://m.scbeasy.com/online/easynet/mobile/welcome.aspx",null);
	$last_login=iconv("windows-874","UTF-8",$last_login);
	$runlogin=true;
	$login_secess=false;
	if(strpos($last_login,"ยินดี")){
		$runlogin=false;
		$login_secess=true;
	}
	if($runlogin){
		$firelogin = curl_scb("https://m.scbeasy.com/online/easynet/mobile/Default.aspx",null);
		if($firelogin=="error"){
			unlink($sessionfile);
			return $data_display_curlerror;

		}
        $login_url=cutstring_scb($firelogin,'Location: ','Date:');
		$login=curl_scb($login_url,null);
		if($login=="error"){
			unlink($sessionfile);
			return $data_display_curlerror;
		}
		$VIEWSTATE=cutstring_scb($login,'VIEWSTATE" value="','" /');
		$__VIEWSTATEGENERATOR=cutstring_scb($login,'VIEWSTATEGENERATOR" value="','" /');
		$logindata["tbUsername"]=$scb_user;
		$logindata["tbPassword"]=$scb_pass;
		$logindata["Login_Button"]="Login";
		$logindata["__VIEWSTATE"]=$VIEWSTATE;
		$logindata["__VIEWSTATEGENERATOR"]=$__VIEWSTATEGENERATOR;

		$login=curl_scb($login_url,$logindata);
		if($login=="error"){
			unlink($sessionfile);
			return $data_display_curlerror;
        }

		if(strpos($login,"welcome.aspx")){
			$login_secess=true;
		}else{
			return array("status"=>2,"detail"=>"SCB singin Error ");
		}
	}

	if($login_secess){
		if($ac_bank=="001"){//ธนาคารใน scb
			$formathbank=curl_scb("https://m.scbeasy.com/online/easynet/mobile/transfers/another-account-noProfile.aspx",null);
			if($formathbank=="error"){
				unlink($sessionfile);
				return $data_display_curlerror;
			}

			$VIEWSTATE=cutstring_scb($formathbank,'VIEWSTATE" value="','" /');
			$__VIEWSTATEGENERATOR=cutstring_scb($formathbank,'VIEWSTATEGENERATOR" value="','" /');
			$formathbank=iconv("windows-874","UTF-8",$formathbank);
			$ddlFromAcc=cutstring_scb($formathbank,'selected" value="','">');
			$send_to_data['__VIEWSTATE']=$VIEWSTATE;
			$send_to_data['__VIEWSTATEGENERATOR']=$__VIEWSTATEGENERATOR;
			$send_to_data['ctl00$DataProcess$btNext']="ถัดไป";
			$send_to_data['ctl00$DataProcess$ddlFromAcc']=$ddlFromAcc;
			$send_to_data['ctl00$DataProcess$ddlMobile']=$mobile_otp;
			$send_to_data['ctl00$DataProcess$tbAmount']=$amount;
			$send_to_data['ctl00$DataProcess$tbToAcc']=$ac_number;

			$formathbank=curl_scb("https://m.scbeasy.com/online/easynet/mobile/transfers/another-account-noProfile.aspx",$send_to_data);
			if($formathbank=="error"){
				unlink($sessionfile);
				return $data_display_curlerror;
			}
			$dataotp=curl_scb("https://m.scbeasy.com/online/easynet/mobile/transfers/another-account-confirm-noProfile.aspx",null);
			$__VIEWSTATE=cutstring_scb($dataotp,'VIEWSTATE" value="','" /');
			$__VIEWSTATEGENERATOR=cutstring_scb($dataotp,'VIEWSTATEGENERATOR" value="','" /');
			$dataotp=iconv("windows-874","UTF-8",$dataotp);
			if(strpos($dataotp,"OTP")){
				$acc_name=trim(cutstring_scb($dataotp,'ชื่อบัญชี:','</p>'));
				return array("status"=>1,"detail"=>"sended","acc_name"=>$acc_name,"__VIEWSTATE"=>$__VIEWSTATE,"__VIEWSTATEGENERATOR"=>$__VIEWSTATEGENERATOR);
			}else{
				return array("status"=>0,"detail"=>"Failed Withdraw ธนาคารปฏิเสธคำขอไม่ทราบสาเหตุ ลองตรวจสอบเลขบัญชี ถ้าถูกต้องแล้วโปรดลองขอถอนอีกครั้ง");
			}
		}else{//ธนาคารอื่น
			$formathbank=curl_scb("https://m.scbeasy.com/online/easynet/mobile/transfers/another-bank-noProfile.aspx",null);
			if($formathbank=="error"){
				unlink($sessionfile);
				return $data_display_curlerror;
			}

			$VIEWSTATE=cutstring_scb($formathbank,'VIEWSTATE" value="','" /');
			$__VIEWSTATEGENERATOR=cutstring_scb($formathbank,'VIEWSTATEGENERATOR" value="','" /');
			$formathbank=iconv("windows-874","UTF-8",$formathbank);
			$ddlFromAcc=cutstring_scb($formathbank,'selected" value="','">');
			$send_to_data['__VIEWSTATE']=$VIEWSTATE;
			$send_to_data['__VIEWSTATEGENERATOR']=$__VIEWSTATEGENERATOR;
			$send_to_data['ctl00$DataProcess$btNext']="ถัดไป";
			$send_to_data['ctl00$DataProcess$ddlBank']=$ac_bank;
			$send_to_data['ctl00$DataProcess$ddlFromAcc']=$ddlFromAcc;
			$send_to_data['ctl00$DataProcess$ddlMobile']=$mobile_otp;
			$send_to_data['ctl00$DataProcess$tbAmt']=$amount;
			$send_to_data['ctl00$DataProcess$tbToAcc']=$ac_number;
			$send_to_data['__EVENTARGUMENT']="";
			$send_to_data['__EVENTTARGET']="";
			$send_to_data['__LASTFOCUS']="";
			$formathbank=curl_scb("https://m.scbeasy.com/online/easynet/mobile/transfers/another-bank-noProfile.aspx",$send_to_data);
			if($formathbank=="error"){
				unlink($sessionfile);
				return $data_display_curlerror;
			}
			$dataotp=curl_scb("https://m.scbeasy.com/online/easynet/mobile/transfers/another-bank-noProfile-confirm.aspx",null);
			$__VIEWSTATE=cutstring_scb($dataotp,'VIEWSTATE" value="','" /');
			$__VIEWSTATEGENERATOR=cutstring_scb($dataotp,'VIEWSTATEGENERATOR" value="','" /');
            $dataotp=iconv("windows-874","UTF-8",$dataotp);
			if(strpos($dataotp,"OTP")){
				$acc_name=trim(cutstring_scb($dataotp,'ชื่อบัญชี:','</p>'));
				return array("status"=>1,"detail"=>"sended","acc_name"=>$acc_name,"__VIEWSTATE"=>$__VIEWSTATE,"__VIEWSTATEGENERATOR"=>$__VIEWSTATEGENERATOR);
			}else{
				return array("status"=>0,"detail"=>"Failed Withdraw ธนาคารปฏิเสธคำขอไม่ทราบสาเหตุ ลองตรวจสอบเลขบัญชี ถ้าถูกต้องแล้วโปรดลองขอถอนอีกครั้ง");
			}

		}
	}
}

function confirm_otp($otp,$__VIEWSTATE,$__VIEWSTATEGENERATOR,$ac_bank){
	$send_otp['__EVENTTARGET']='ctl00$DataProcess$btConfirm';
	$send_otp['__VIEWSTATE']=$__VIEWSTATE;
	$send_otp['__VIEWSTATEGENERATOR']=$__VIEWSTATEGENERATOR;
	$send_otp['ctl00$DataProcess$tbOTP']=$otp;
	if($ac_bank=="001"){//ธนาคารใน scb
		$return_otp_status=curl_scb("https://m.scbeasy.com/online/easynet/mobile/transfers/another-account-confirm-noProfile.aspx",$send_otp);
		$chotp_com=curl_scb("https://m.scbeasy.com/online/easynet/mobile/transfers/another-account-success-noProfile.aspx",null);
		$chotp_com=iconv("windows-874","UTF-8",$chotp_com);
		$ch2confirm=trim(cutstring_scb($chotp_com,'(฿):','</p>'));
		//if(strpos($return_otp_status,"success.aspx")){
		if(strpos($chotp_com,"สำเร็จ")&&$ch2confirm!=""){
			return array("status"=>1,"detail"=>"Withdraw success");
		}else{
			return array("status"=>0,"detail"=>"ส่งคำสั่งถอนแล้ว แต่ธนาคารไม่ตอบกลับสถานะ ให้แอดมินตรวจสอบยอดนี้ ..");
		}
	}else{//ธนาคารอื่น
		$return_otp_status=curl_scb("https://m.scbeasy.com/online/easynet/mobile/transfers/another-bank-noProfile-confirm.aspx",$send_otp);
		$chotp_com=curl_scb("https://m.scbeasy.com/online/easynet/mobile/transfers/another-bank-noProfile-success.aspx",null);
		$chotp_com=iconv("windows-874","UTF-8",$chotp_com);
		//if(strpos($return_otp_status,"success.aspx")){
		if(strpos($chotp_com,"สำเร็จ")){
			return array("status"=>1,"detail"=>"Withdraw success");
		}else{
			return array("status"=>0,"detail"=>"ส่งคำสั่งถอนแล้ว แต่ธนาคารไม่ตอบกลับสถานะ ให้แอดมินตรวจสอบยอดนี้ ..");
		}
	}
}

?>
