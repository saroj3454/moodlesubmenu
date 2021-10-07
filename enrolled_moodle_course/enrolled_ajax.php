<?php 
if ($_POST['action'] == 'insert_enrolled_data') {
    require_once("../../../wp-config.php");
    global $wpdb;

    $cdate = date('Y-m-d h:i:s');
    $product = $_POST['md_productID'];
    $course1 = $_POST['mdl_courseID'];

    if (strpos($course1, 'b')) {
        $course = (int) $course1;
        $bundle = 1;
    } else {
        $course = $_POST['mdl_courseID'];
        $bundle = 0;
    }

    $table_name = "{$wpdb->prefix}wootomoodle";
    $myrows = $wpdb->get_results($wpdb->prepare("SELECT id,courseid,productid,datecreated FROM ".$table_name." WHERE productid = %s", $product));
    if (!count($myrows)) {
        $wpdb->insert($table_name, array(
            'courseid' => $course,
            'productid' => $product,
            'bundle' => $bundle,
            'datecreated' => $cdate
        ));
        $status = 0;
    } else {
        $status = 1;
    }
    echo $status;
}

if ($_POST['action'] == 'delete_course') {
    require_once("../../../wp-config.php");
    global $wpdb;
    $enroll_id = $_POST['enrolled_course_ID']; 
	$table_name = "{$wpdb->prefix}wootomoodle";
	$wpdb->delete($table_name, array('id' => $enroll_id, ), array('%s'));	
    if ($queryDelpage) {
        echo $status = 1;
    } else {
        echo $status = 0;
    }
}
// connection function script
if ($_POST['action'] == 'add_moodle_connection_detail') {
	 require_once("../../../wp-config.php");
    global $wpdb;
	$cdate = date('Y-m-d h:i:s');
	$hostname   =  $_POST['hostname'];
	$dbname      =  $_POST['dbname'];
	$userNme    =  $_POST['userNme'];		
	$dbpass = $_POST['pass'];
	// Store the cipher method 
	$ciphering = "AES-128-CTR"; 
	// Use OpenSSl Encryption method 
	$iv_length = openssl_cipher_iv_length($ciphering); 
	$options = 0; 
	// Non-NULL Initialization Vector for encryption 
	$encryption_iv = '1234567891011121'; 
	// Store the encryption key 
	$encryption_key = "ASDSA#$%$::"; 
	// function to encrypt the data 
	$pass = openssl_encrypt($dbpass, $ciphering, $encryption_key, $options, $encryption_iv); 
	$mdlprefix           =  $_POST['mdlprefix'];
	$moodleurl   =  $_POST['moodleurl'];
    $table_name = $wpdb->prefix . "moodledetail";
	$mdldata= moodle_deta();

	if(empty($hostname)){
		$hostname   = $mdldata->hostname ;
	}
	if(empty($dbname)){
		$dbname   = $mdldata->dbname ;
	}
	if(empty($userNme)){
		$userNme   = $mdldata->username ;
	}
	if(empty($pass)){
		$pass   = $mdldata->password ;
	}
	if(empty($moodleurl)){
		$moodleurl   = $mdldata->moodleurl ;
	}
	if(empty($mdlprefix )){
		$mdlprefix    = $mdldata->mdlprefix;
	}
	

    if(empty($mdldata)){
		$wpdb->insert($table_name, array(
		'hostname'           => $hostname,
		'dbname'           => $dbname,
		'username'           => $userNme,
		'password'           => $pass,
		'mdlprefix'           => $mdlprefix,
		'moodleurl'           => $moodleurl,
		'datecreated'           => $cdate
		));
         $status = 0;		
	}else{
	
		$updated=$wpdb->update($table_name, 
		array( 			
		'hostname'           => $hostname,
		'dbname'           => $dbname,
		'username'           => $userNme,
		'password'           => $pass,
		'mdlprefix'           => $mdlprefix,
		'moodleurl'           => $moodleurl,
		'datecreated'           => $cdate
		), 
		array('id' => 1)
		); 
		$status  = 1;	
	}	
	echo $status;
}
