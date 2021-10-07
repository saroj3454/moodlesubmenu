<?php 
error_reporting(E_ALL);
ini_set('display_errors', 'On');

$email = "userdemo367@gmail.com";
$subject =  "Email Test";
$message = "this is a mail testing email function on server";


 $sendMail = mail($email, $subject, $message);
if($sendMail)
{
echo "Email Sent Successfully";
}
else{
echo "Mail Failed";
}