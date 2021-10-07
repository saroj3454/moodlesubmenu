<?php
require_once('../../config.php');
global $CFG,$USER,$DB,$PAGE;
$PAGE->set_context(context_system::instance());


$fname                =  base64_decode($_REQUEST['firstname']);
 $lname                =  base64_decode($_REQUEST['lastname']);
 $user_name            =  base64_decode($_REQUEST['email']);
 $email                =  base64_decode($_REQUEST['email']);
 $course_id            = base64_decode($_REQUEST['course']);
 $from_mail            = base64_decode($_REQUEST['admin_email']); 

 $courseid =  explode(',', $course_id);  

//  print_r($courseid);
// die();
//Auto generate password
 $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%&*_";
$pwd = substr( str_shuffle( $chars ), 0, 12 );
// $password=md5($pwd); 
// $pwd = 'P@ssw0rd123';
$password=md5($pwd); 

$allcourse_name = array();
//Org table
foreach ($courseid as $coursevalue)
{



    $courseinfo=$DB->get_record_sql("SELECT id,fullname FROM {course} where id ='$coursevalue'");


    array_push($allcourse_name,$courseinfo->fullname);
}





function enrolCourse($courseid, $userid, $roleid,$endtime,$startime) {
    global $DB, $CFG;
    $query = 'SELECT * FROM {enrol} WHERE enrol = "manual" AND courseid = '.$courseid;
    $enrollmentID = $DB->get_record_sql($query);
    if(!empty($enrollmentID->id)) {
        if (!$DB->record_exists('user_enrolments', array('enrolid'=>$enrollmentID->id, 'userid'=>$userid))) {
            $userenrol = new stdClass();
            $userenrol->status = 0;
            $userenrol->userid = $userid;
            $userenrol->enrolid = $enrollmentID->id;
            $userenrol->timestart  = $startime;
            $userenrol->timeend = $endtime;
            $userenrol->modifierid  = 2;
            $userenrol->timecreated  = time();
            $userenrol->timemodified  = time();
            //print_r($userenrol);die;
            $enrol_manual = enrol_get_plugin('manual');
            $enrol_manual->enrol_user($enrollmentID, $userid, $roleid, $userenrol->timestart, $userenrol->timeend);
           // add_to_log($courseid, 'course', 'enrol', '../enrol/users.php?id='.$courseid, $courseid, $userid); //there should be userid somewhere!
            //redirect('http://lln.axisinstitute.edu.au/my');
        } else {
            $oldenroll = $DB->get_record('user_enrolments', array('enrolid'=>$enrollmentID->id, 'userid'=>$userid));
            $oldenroll->timestart = $startime;
            $oldenroll->timeend = $endtime;
            if($oldenroll){
                $insertRecords=$DB->update_record('user_enrolments', $oldenroll);
            }
        }
    }
}


// get admin emial
$adminEmail = $DB->get_record("user", array("id"=>2));
$email_id=$adminEmail->email;
$userinfos=$DB->get_record_sql("SELECT id FROM {user} where username ='$user_name' or email='$user_name'");

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

    // echo "<pre>";
    // print_r($userinsert);
    // die();
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
                // enrolCourse($coursevalue, $userinfo->id, 5);
            $start=time();
            $end=strtotime("+1 year");


            enrolCourse($coursevalue, $userinfo->id, 5,$end,$start);

         }
           


    if(!empty($allcourse_name)){
        $allcourse_name = implode("", $allcourse_name);
        $allcourse_name = $allcourse_name;
    }

    $messagehtml =   "<!DOCTYPE html>
<html>
<head>
    <title>Mail</title>
    <link rel='stylesheet' type='text/css' href='https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css'>
</head>
<body><p>Hi <b>".$fname."</b>,</p>
    <p><b>$fname  $lname </b> has registered LMS Portal.</p>
    <p> Thank you for registration. To access your course, go to http://preparetest.com/login/index.php Your user name is <b>$user_name</b> , and your password is <b>$pwd</b> </p>
    
    <p><b>Login Username:</b> $user_name</p>
    <p><b>Login Password:</b> $pwd</p>
    <p><b>Login URL :</b> ".$CFG->wwwroot."/login/index.php</p>
	<p><b>Your Course:</b>  ".$allcourse_name."</p>
   
<table style='background-color: rgb(237 235 235);' cellpadding='15'>
 <tr><td>
 <p style='font-weight: 600;'>Click below to access your Learning Account!</p>
 <a  href='http://preparetest.com/login/index.php' style='padding: 10px 50px;
 display: inline-block;
 background-color: rgb(177, 24, 48);
 border-radius: 4px;
 border: 2px solid white;
 color: #fff;
 text-decoration: none;
 font-weight: bold;'> MY LEARNING ACCOUNT</a>
</td> 
</tr>
</table>

    <p>Thanks, </p>

    <p>preparetest.com </p></body></html>";

         $fromUser = "notification@preparetest.com";
        $subject = 'Welcome to Preparetest Learning! Registration confirmation';
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

email_to_user($emailuser,$fromUser, $subject, $message = '', $messagehtml);
        
	}
}

else{
    foreach ($courseid as $coursevalue)
    {
         // enrolCourse($coursevalue, $userinfos->id, 5);

          $start=time();
            $end=strtotime("+1 year");
            enrolCourse($coursevalue, $userinfos->id, 5,$end,$start);

    }
  		 
    if(!empty($allcourse_name)){

        $allcourse_name = implode("", $allcourse_name);
        $allcourse_name = $allcourse_name;
    }
    //mail function
     $messagehtml =   "<!DOCTYPE html>
<html>
<head>
    <title>Mail</title>
    <link rel='stylesheet' type='text/css' href='https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css'>
</head>
<body><p>Hi <b>".$fname."</b>,</p>
   <p>$fname  $lname  has registered LMS Portal. </p>
    <p> Thank you for enrolling for our course. To access your course, go to $CFG->wwwroot. </p>
    <p><b>Your Course:</b> ".$allcourse_name."</p>
    <p>Thanks, </p>

    <p>preparetest.com </p></body></html>";

         $fromUser = "notification@preparetest.com";
        $subject = 'Preparetest Learning! Registration confirmation';
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

email_to_user($emailuser,$fromUser, $subject, $message = '', $messagehtml);





}

 //echo $CFG->wwwroot."local/mdl_course_enroll/session.php?token_email='".$_REQUEST['email']."'";
//die; 
 redirect($CFG->wwwroot."/local/mdl_course_enroll/session.php?token_email='".$_REQUEST['email']."'"); 
?>