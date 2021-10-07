<?php 
require('../../config.php');
global $CFG,$USER,$DB;
$rr2 = substr(base64_decode($rr1),11);
$decryption_iv = '1085492563145826'; 
$decryption_key = "@WAR&KEY#$%$::";  
$options = 0; 
$ciphering = "AES-128-CTR"; 
 $user_name  =openssl_decrypt (str_replace(" ","+",$_REQUEST['token_user']), $ciphering, $decryption_key, $options, $decryption_iv);  
 $token_email  =openssl_decrypt (str_replace(" ","+",$_REQUEST['token_email']), $ciphering, $decryption_key, $options, $decryption_iv); 
 //$token_email= base64_decode("ASDSA#$%$::".$_REQUEST['token_email']); 
 $email= trim($token_email,"'"); 
 $userinfo="SELECT * FROM {user} WHERE username='".$user_name."'";
$userinfos=$DB->get_record_sql($userinfo);

if(!empty($userinfos)){
		$userdata = $DB->get_record("user", array("id"=>$userinfos->id));
	if(!empty($userdata)){
		complete_user_login($userdata);
	    \core\session\manager::apply_concurrent_login_limit($userdata->id, session_id());
		redirect(new moodle_url($CFG->wwwroot.'/my/index.php'));
	} else {
		redirect(new moodle_url('/'));
	}
	
}else{
	redirect(new moodle_url('/'));
}



?>