<?php

/**

* Plugin Name:       Custom Lds

* Description:       This plugin using in allcourses page

* Version:           1.0.0

* Author:            LDS Engineers

*/

/**

* Display expertise field

* @since 1.0.0

*/



add_action( 'wp_print_styles', 'wps_deregister_styles', 100 );
function wps_deregister_styles() {
global $current_user;
 if ( is_page_template('homepage.php') ) {
$current_user = wp_get_current_user();
// if (user_can( $current_user, 'administrator' )) {

// }else{
	 	 
// }

wp_dequeue_style( 'lds-style');
wp_dequeue_style( 'wp-block-library');
wp_dequeue_script( 'lds-navigation' );


}

 
}


function my_deregister_scripts(){
global $current_user;
 if ( is_page_template('homepage.php') ) {
// $current_user = wp_get_current_user();
// if (user_can( $current_user, 'administrator' )) {

// }else{
 	 
// }
wp_deregister_script( 'wp-embed' );

}

 
}
add_action( 'wp_footer', 'my_deregister_scripts' );


add_action( 'wp_enqueue_scripts','add_css');
function add_css(){
	 if ( is_page_template('homepage.php') ) {
	 	wp_enqueue_style( 'lds-plugin', plugins_url('/css/style.css', __FILE__), false, '1.0.0', 'all');
	 }
}

add_action('wp_footer', 'addscript');
function addscript(){
	if ( is_page_template('homepage.php') ) {
		wp_enqueue_script('lds-plugin-cutom', plugins_url('/js/custom.js', __FILE__), array('jquery'), '1.2.3', true);
	}
}



function onlineall_courseshortcode(){
	global $wpdb,$current_user;
$args = array('numberposts' => -1, 'post_type' => 'product', 'post_status' => 'publish',);
$products = get_posts($args);	
?>

<style type="text/css">
    a.s_datao {
    border: solid 1px blue;
    background: blue;
    color: white;
    font-size: 19px;
    padding: 10px;
    float: right;
}
</style>

<section class="pt-5">

    <div class="container">

        <div class="row pb-4">

            <div class="col-md-12">

                <div class="upcoming-header ">

                    <div class="headings"> 

                        <h2 class="text-center"><span class="bold_heading">All </span>Courses 
                            <?php if(user_can( $current_user, 'administrator' )) { ?> <a class="s_datao" href="<?php echo site_url()?>/wp-admin/post-new.php?post_type=product"><i class="fa fa-plus" aria-hidden="true"></i> Add Product</a><?php } ?></h2>

                    </div>

                </div>

            </div>

        </div>

        <div class="row pt-4">
            
<?php foreach ($products as $sproduct){ 
$product = wc_get_product($sproduct->ID);
$dataproduct = new WC_product($sproduct->ID);
$image_id  = $dataproduct->get_image_id();
$image_url = wp_get_attachment_image_url( $image_id, 'full' ); 
$current_user = wp_get_current_user();

$datav=$wpdb->get_row("select * from " . $wpdb->prefix . "wootomoodle where productid='".$sproduct->ID."'");
if(!empty($datav)){
?>

            <div class="col-md-4 ">
                <div class="t">
                	<?php if (user_can( $current_user, 'administrator' )) { ?>

                	 <a href="<?php echo get_site_url(); ?>/wp-admin/post.php?post=<?php echo $sproduct->ID; ?>&action=edit" class="dpostion">
<i class="fas fa-pencil"></i>
                     </a>
                 <?php } ?>
                <div class="nfeature_box text-center mt-4">
                    <div class="dimage">
                       <a href="<?php echo get_site_url(); ?>/index.php/checkout/?add-to-cart=<?php echo $sproduct->ID; ?>"> <img src="<?php echo  $image_url; ?>"></a>
                    </div>
                    
                    <div class="row">
	                    <div class="col-md-8 pdr-0">
	                    	<a href="<?php echo get_site_url(); ?>/index.php/checkout/?add-to-cart=<?php echo $sproduct->ID; ?>">
		                    <div class="nfeature_box-header_top">
				                    <div class="dbox pl-2">
				                        <h4><?php echo $product->name; ?></h4>
				                    </div>
		                     </div>
		                     </a>
	                	</div>
	                			
		                <div class="col-md-4 pdo">
		                     <div class="nfeature_box-header_top">
		                            <!-- <div class="l_price">Price</div> -->
		                           <div class="p_price"> <span><i class="fas fa-rupee-sign"></i></span> <?=$product->sale_price; if (strpos($product->sale_price, "." ) !== false ) {}else{ echo ".00";} ?></div>
		                     </div>
		                </div>
                </div> 
					<div class="row fg"><a href="<?php echo get_site_url(); ?>/index.php/checkout/?add-to-cart=<?php echo $sproduct->ID; ?>" class="d_enroll">Enroll</a></div>
 			</div>
                </div>
            </div>  


<?php } } ?>

        </div>

    </div>
</section>

<?php }
add_shortcode( 'onlineall_course', 'onlineall_courseshortcode');

add_filter( 'woocommerce_add_to_cart_validation', 'remove_cart_item_before_add_to_cart', 20, 3 );
function remove_cart_item_before_add_to_cart( $passed, $product_id, $quantity ) {
    if( ! WC()->cart->is_empty() )
        WC()->cart->empty_cart();
    return $passed;
}


add_filter('woocommerce_checkout_fields', 'addBootstrapToCheckoutFields' );
function addBootstrapToCheckoutFields($fields) {
    foreach ($fields as &$fieldset) {
        foreach ($fieldset as &$field) {
            // if you want to add the form-group class around the label and the input
            $field['class'][] = 'newform'; 

            // add form-control to the actual input
            $field['input_class'][] = 'nform-control';
        }
    }
    return $fields;
}

add_filter( 'default_checkout_billing_country', 'change_default_checkout_country', 10, 1 );

function change_default_checkout_country( $country ) {
    // If the user already exists, don't override country
    if ( WC()->customer->get_is_paying_customer() ) {
        return $country;
    }

    return 'IN'; // Override default to Germany (an example)
}
add_filter( 'wc_add_to_cart_message_html', '__return_null' );

add_action( 'woocommerce_order_status_cancelled', 'change_status_to_refund', 
21, 1 );
function change_status_to_refund( $order_id ) {
	wp_redirect(home_url());
}

add_action('woocommerce_cancelled_order','lenura_redirect_to_home');
function lenura_redirect_to_home() {
    wp_redirect(home_url()); // REDIRECT PATH
 }


 add_action( 'woocommerce_thankyou', 'bbloomer_redirectcustom');
  
function bbloomer_redirectcustom( $order_id ){
   echo  $order = wc_get_order( $order_id );

    $url = 'https://yoursite.com/custom-url';
    if ( ! $order->has_status( 'failed' ) ) {
        wp_redirect(home_url());
        exit;
    }
}


add_action( 'woocommerce_review_order_after_submit', 'checkout_reset_button', 10 );
function checkout_reset_button(){
    echo '<br><br>
    <a class="button alt" style="text-align:center;" href="https://preparetest.com/blocks/searchdashboard/index.php">'.__("Cancel order", "woocommerce").'</a>';
}

add_action( 'template_redirect', 'checkout_reset_cart' );
function checkout_reset_cart() {
    if( ! is_admin() && isset($_GET['cancel']) ) {
        WC()->cart->empty_cart();
        wp_redirect( get_permalink( wc_get_page_id( 'shop' ) ) );
        exit();
    }
}


add_filter( 'woocommerce_checkout_redirect_empty_cart', '__return_false' );
add_filter( 'woocommerce_checkout_update_order_review_expired', '__return_false' );

add_action('template_redirect', 'skip_cart_page_redirecting_to_checkout');
function skip_cart_page_redirecting_to_checkout() {
global $wpdb,$current_user,$post;

?>
<style type="text/css">
    .woocommerce-MyAccount-navigation{
        display: none;
    }
   hr.border-top.pt-2.pb-2 {
    background: none;
}
</style>
<?php

if($post->post_title=="Shop"){
	wp_redirect(site_url());
}
    // If is cart page and cart is not empty
    if( is_cart() && ! WC()->cart->is_empty() ){
    	echo "ddddd";
    	die();
        // wp_redirect( wc_get_checkout_url() );
    }
}


add_shortcode('datadefault','datadefault_shortcode');
function datadefault_shortcode(){




}

function onboarding_update_fields( $fields = array()) {
   // check if it's set to prevent notices being thrown
  if(!empty($_GET['base'])){


     $data= unserialize(gzuncompress(stripslashes(base64_decode(strtr($_GET['base'], '-_,', '+/=')))));



       // if all you want to change is the value, then assign ONLY the value
       $fields['billing']['billing_first_name']['default'] = $data['firstname'];
       $fields['billing']['billing_last_name']['default'] = $data['lastname'];
       $fields['billing']['billing_email']['default'] = $data['email'];
     
   // you must return the fields array 
   return $fields;

}
}

add_filter( 'woocommerce_checkout_fields', 'onboarding_update_fields' );




add_action( 'woocommerce_before_order_notes', 'bbloomer_add_custom_checkout_field' );
  
function bbloomer_add_custom_checkout_field( $checkout ) { 
  
 if(!empty($_GET['base'])){


     $data= unserialize(gzuncompress(stripslashes(base64_decode(strtr($_GET['base'], '-_,', '+/=')))));
    

?>
<input type="hidden" name="redirect_url" value="<?php echo $data['redirect_url']; ?>">
<input type="hidden" name="userid" value="<?php echo $data['userid']; ?>">
<input type="hidden" name="base" value="<?php echo $data['action']; ?>">
<input type="hidden" name="baseid" value="<?php  echo $data['actionid']; ?>">
<?php

}

}



add_action( 'woocommerce_checkout_update_order_meta', 'bbloomer_save_new_checkout_field' );
  
function bbloomer_save_new_checkout_field( $order_id ) { 
    if ( $_POST['redirect_url'] ) update_post_meta( $order_id, 'redirect_url', esc_attr( $_POST['redirect_url'] ) );
    if ( $_POST['userid'] ) update_post_meta( $order_id, 'userid', esc_attr( $_POST['userid'] ) );
    if ( $_POST['base'] ) update_post_meta( $order_id, 'base', esc_attr( $_POST['base'] ) );
    if ( $_POST['baseid'] ) update_post_meta( $order_id, 'baseid', esc_attr( $_POST['baseid'] ) );
}





