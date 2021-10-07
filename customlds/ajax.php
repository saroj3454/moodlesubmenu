<?php if ($_POST['action'] == 'multideleteproduct') {
  require_once("../../../wp-config.php");
    global $wpdb;

if(!empty($_REQUEST['datapostid'])){
foreach(explode(',',$_REQUEST['datapostid']) as $value){
wp_delete_post($value);
}


}


  }

if($_POST['action'] == 'deleteproduct') {
  require_once("../../../wp-config.php");
    global $wpdb;

   
    wp_delete_post($_POST['postid']);
  }



if ($_POST['action'] == 'createproduct') {
	require_once("../../../wp-config.php");
    global $wpdb;
      $post = array(

        'post_author' => '1',

        'post_content' => '',

        'post_status' => "publish",

        'post_title' => $_POST['title'],

        'post_parent' => '',

        'post_type' => "product",

      );



//Create post

      echo $post_id = wp_insert_post( $post, $wp_error );

      update_post_meta($post_id, '_price', $_POST['price']);

}


if ($_POST['action'] == 'updateproduct') {
	require_once("../../../wp-config.php");
    global $wpdb;
      $post = array(

        'post_author' => '1',

        'post_content' => '',

        'post_status' => "publish",

        'post_title' => $_POST['title'],

        'post_parent' => '',

        'post_type' => "product",

      );



//update post




$my_post = array('ID' =>  $_POST['postid'],

       'post_title'    => $_POST['title'],

       'post_status'   => 'publish'

     );



      wp_update_post($my_post);

      update_post_meta( $_POST['postid'], '_price', $_POST['price']);

echo $_POST['postid'];












}