<?php
require_once('../../config.php');
global $CFG,$USER,$DB,$PAGE;
$PAGE->set_context(context_system::instance());


// Non-NULL Initialization Vector for decryption 
	$decryption_iv = 'cVb67YtfAz328oOikl96vBn'; 
//decryption_iv = '1085492563145826'; 
// Store the decryption key 
   $decryption_key = "@WAR&KEY#$%$::";  
	$options = 0; 
	$ciphering = "AES-128-CTR"; 
   //decrypt the data 

 echo $fname  =openssl_decrypt (str_replace(" ","+",$_REQUEST['firstname']), $ciphering, $decryption_key, $options, $decryption_iv); 	
  echo $lname  =openssl_decrypt (str_replace(" ","+",$_REQUEST['lastname']), $ciphering, $decryption_key, $options, $decryption_iv); 	
  echo $user_name  =openssl_decrypt (str_replace(" ","+",$_REQUEST['user']), $ciphering, $decryption_key, $options, $decryption_iv);  
  echo $email  =openssl_decrypt (str_replace(" ","+",$_REQUEST['email']), $ciphering, $decryption_key, $options, $decryption_iv); 	
  echo $course_id  =openssl_decrypt ($_REQUEST['course'], $ciphering, $decryption_key, $options, $decryption_iv); 	
  echo $from_mail  = openssl_decrypt(str_replace(" ","+",$_REQUEST['admin_email']), $ciphering, $decryption_key, $options, $decryption_iv); 

$courseid =  explode(',', $course_id);  
/*   echo "<pre>";
print_r($_REQUEST);
die;  */  

/*  echo "<pre>";
print_r($_REQUEST);
die;  
 $fname                =  base64_decode($_REQUEST['firstname']);
 $lname                =  base64_decode($_REQUEST['lastname']);
 $user_name            =  base64_decode($_REQUEST['user']);
 $email                =  base64_decode($_REQUEST['email']);
 $course_id            = base64_decode($_REQUEST['course']);
 $from_mail            = base64_decode($_REQUEST['admin_email']); 

$courseid =  explode(',', $course_id);  */

//Auto generate password
 $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%&*_";
$pwd = substr( str_shuffle( $chars ), 0, 12 );
$password=md5($pwd); 
//$pwd = 'P@ssw0rd';
//$password=md5($pwd); 

$allcourse_name = array();
//Org table
foreach ($courseid as $coursevalue)
{
    $courseinfo=$DB->get_record_sql("SELECT id,fullname FROM {course} where id ='$coursevalue'");
    array_push($allcourse_name, "<li>".$courseinfo->fullname."</li>");
}

function enrolCourse($courseid, $userid, $roleid) {
 
        global $DB, $CFG;
        $query = 'SELECT id FROM {enrol} WHERE enrol = "manual" AND courseid = '.$courseid;
        $enrollmentID = $DB->get_record_sql($query);
       if(!empty($enrollmentID->id)) {
        if (!$DB->record_exists('user_enrolments', array('enrolid'=>$enrollmentID->id, 'userid'=>$userid))) {
            $userenrol = new stdClass();
            $userenrol->status = 0;
            $userenrol->userid = $userid;
            $userenrol->enrolid = $enrollmentID->id;
            $userenrol->timestart  = time();
            $userenrol->timeend = strtotime("+1 year");
            $userenrol->modifierid  = 2;
            $userenrol->timecreated  = time();
            $userenrol->timemodified  = time();

            $enrol_manual = enrol_get_plugin('manual');
            $enrol_manual->enrol_user($enrollmentID, $userid, $roleid, $userenrol->timestart, $userenrol->timeend);
            add_to_log($courseid, 'course', 'enrol', '../enrol/users.php?id='.$courseid, $courseid, $userid); //there should be userid somewhere!  
        }
    }

}
// get admin emial
$adminEmail = $DB->get_record("user", array("id"=>2));
$email_id=$adminEmail->email;
$userinfos=$DB->get_record_sql("SELECT id FROM {user} where username ='$user_name'");

if(empty($userinfos)){
    //echo "new user";die;
    $userinsert  = new stdClass();
    $userinsert->username = $user_name;
    $userinsert->password=$password;
    $userinsert->firstname= $fname;
    $userinsert->lastname= $lname;
    $userinsert->email= $email;
    $userinsert->timecreated= time();
    $userinsert->timemodified= time();
    $userinsert->middlename= " ";
    $userinsert->confirmed= 1;
    $userinsert->mnethostid= 1;
    $insertRecords=$DB->insert_record('user', $userinsert);
    $userinfo=$DB->get_record_sql("SELECT id FROM {user} where username ='$user_name' and email='$email' ");
    if(!empty($userinfo)){
        $userpreinfo=$DB->get_record_sql("SELECT id FROM {user_preferences} where userid = $userinfo->id and value=1 ");
        if(empty($userpreinfo)){
            $forceinsert  = new stdClass();
            $forceinsert->userid = $userinfo->id;
            $forceinsert->name="auth_forcepasswordchange";
            $forceinsert->value=1;
            $insertRecords=$DB->insert_record('user_preferences', $forceinsert);
        }
	
		
        foreach ($courseid as $coursevalue)
        {
                enrolCourse($coursevalue, $userinfo->id, 5);

         }
           


    if(!empty($allcourse_name)){
        $allcourse_name = implode("", $allcourse_name);
        $allcourse_name = "<ol>".$allcourse_name."</ol>";
    }

    //Mail Function
    $messagehtml =   "<!DOCTYPE html>
<html>
<head>
    <title>Mail</title>
    <link rel='stylesheet' type='text/css' href='https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css'>
</head>
<body><p>Hi ".$fname.",</p>
    <p>$fname  $lname  has registered LMS Portal.</p>
    <p> Thank you for registration. To access your course, go to $CFG->wwwroot. Your user name is $user_name , and your password is $pwd. </p>
    <p><b>Login Email Id:</b> $email</p>
    <p><b>Login Username:</b> $user_name</p>
    <p><b>Login Password:</b> $pwd</p>
    <p><b>Organization Name :</b> $company_orgname</p>
    <p><b>Login URL :</b> $CFG->wwwroot </p>
	<p><b>Your Course(s)</b></p>
    ".$allcourse_name."
    <p>Thanks, </p>

    <p>bartest.com </p></body></html>";

         $fromUser = $email_id;
        //$fromUser ='suneet@ldsengineers.com';
        $subject = 'Registration confirmation';

        $emailuser = new stdClass();
        $emailuser->email = $email;
        $emailuser->firstname = $fname;
        $emailuser->lastname= $lname;
        $emailuser->maildisplay = true;
        $emailuser->mailformat = 1; // 0 (zero) text-only emails, 1 (one) for HTML/Text emails.
        $emailuser->id = 1;
        $emailuser->firstnamephonetic = false;
        $emailuser->lastnamephonetic = false;
        $emailuser->middlename = false;
        $emailuser->username = false;
        $emailuser->alternatename = false;


$headers =  "MIME-Version: 1.0" . "\r\n";

$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

//$headers .= 'From: <info@bartest.com>' . "\r\n";
//$email;
//$to = "saroj@lds-international.in";
$to = $email;
$subject = "Registration confirmation";

mail($to,$subject,$messagehtml,$headers);
        // print_r($emailuser);
         // $mail = email_to_user($emailuser,$fromUser, $subject, $message = '', $messagehtml);
         // if($mail){
         //     echo 'mail send';
         // }else{
         //     echo 'failed';
         // }
	}
}

else{
    foreach ($courseid as $coursevalue)
    {
         enrolCourse($coursevalue, $userinfos->id, 5);

    }
  		 
    if(!empty($allcourse_name)){

        $allcourse_name = implode("", $allcourse_name);
        $allcourse_name = "<ol>".$allcourse_name."</ol>";
    }
    //mail function
    $messagehtml =   "<p>Hi ".$fname.",</p>
    <p>$fname  $lname  has registered LMS Portal. </p>
    <p> Thank you for enrolling for our course(s) . To access your course, go to $CFG->wwwroot. </p>
        <p><b>Login URL :</b> $CFG->wwwroot </p>
		<p><b>Your Course(s)</b></p>
    ".$allcourse_name."

    <p>Thanks, </p>

    <p>bartest.com </p>";

            $fromUser = $email_id;          
            $subject = 'Registration confirmation';

            $emailuser = new stdClass();
            $emailuser->email = $email;
            $emailuser->firstname = $fname;
            $emailuser->lastname= $lname;
            $emailuser->maildisplay = true;
            $emailuser->mailformat = 1; // 0 (zero) text-only emails, 1 (one) for HTML/Text emails.
            $emailuser->id = 1;
            $emailuser->firstnamephonetic = false;
            $emailuser->lastnamephonetic = false;
            $emailuser->middlename = false;
            $emailuser->username = false;
            $emailuser->alternatename = false;

$headers =  "MIME-Version: 1.0" . "\r\n";

$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

//$headers .= 'From: <info@bartest.com>' . "\r\n";
$headers .= 'From: <'.$from_mail.'>' . "\r\n";
//$email;
$to = $email;
$subject = "Registration confirmation";

mail($to,$subject,$messagehtml,$headers);
            // print_r($emailuser);
            // $mail = email_to_user($emailuser,$fromUser, $subject, $message = '', $messagehtml);
             //if($mail){
             //    echo 'mail send';
            // }else{
            //    echo 'failed';
           //  }
}
//echo "exitng"; die;
echo $token_email = openssl_encrypt($email, 'AES-128-CTR','1085492563145826',0, '@WAR&KEY#$%$::');
echo $token_user = openssl_encrypt($user_name, 'AES-128-CTR','1085492563145826',0, '@WAR&KEY#$%$::');

/* echo $CFG->wwwroot."local/mdl_course_enroll/session.php?token_email='$email'";
die; */
redirect($CFG->wwwroot."/local/mdl_course_enroll/session.php?token_email='$token_email'&token_user='$token_user'"); 
?>