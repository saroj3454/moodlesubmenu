<?php

/**
  Plugin Name: Enrolled Moodle Course
  Description: Enrolled Moodle course in WordPress
  Version: 1.0.0
  Author: Suneet Sharma
  Author URI: https://ldsengineers.com

 * */
define('WP_DEBUG', true);

add_action('admin_menu', 'enrolled_moodle_course');
function enrolled_moodle_course(){
    add_menu_page('Enroll Course', 'Enroll Course', 'manage_options', 'enrolled', 'showCourse' );
    add_submenu_page('enrolled', 'Submenu Page Title', 'Enroll Course', 'manage_options', 'enrolled' );
    add_submenu_page('enrolled', 'Moodle Connection', 'Moodle Connection', 'manage_options', 'connection','moodleConnection' );
    add_submenu_page('enrolled', 'Required Pages', 'Required Pages', 'manage_options', 'requiredpages','impNote' );
}
function moodle_deta() {
	  global $wpdb;
$mdllist="SELECT id,hostname,dbname,username,password,moodleurl,mdlprefix FROM " .$wpdb->prefix. "moodledetail WHERE id=1";
$mdldata = $wpdb->get_row($mdllist);
return $mdldata;
}


function impNote(){  
$mdllists= moodle_deta();
$mdllists->id;
?>
<div class="impnote">
<h1 class="ntitle">Enrollment Custom Pages</h1>
<p class="ntext">After installation, Enrolled Course Plugin, You need to  creates the following new pages.</p>
<p class="ntext2">You need to create two custom pages (i) thanku and (ii) Moodle loging.</p>
<ul> 
<li> <b>Go to dashboard->pages-> add new</b></li>
<li>Thanku – Create thanku page and select Thank You Template.</li>
<li>Moodle login – Create Moodle login page and select moodle login  Template.</li>

</ul>

</div>
<style>
 .impnote {
    background: #fff;
    padding: 15px;
    margin-top: 20px;
    margin-left: 15px;
    margin-right: 18px;
    box-shadow: 0 0 5px 0 #ddd;
    border: 1px solid #eee;
    border-top: 5px solid #c1970c;
	font-size: 16px;
}
.ntitle{
	color: #c1970c;
	text-transform: capitalize;
}
.ntext{
	font-size: 18px;
	color: #777;
}
.ntext2{
	font-size: 16px;

}
.impnote ul {
    padding: 0;
    margin: 0;
    margin-left: 30px;
    line-height: 30px;
    list-style: circle;
}
</style>

<?php 
}


function moodleConnection() {  
     global $wpdb;
 $plugingpath = plugins_url() . "/enrolled_moodle_course/enrolled_ajax.php";
$mdllists= moodle_deta();
  
?>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <style>
    #msg2{
    color:#dc3545
    }
    #msg1{
    color:#dc3545
    }
    .page_height{
                margin-top: 20px;
    }
    .dbfield{
            height: 60px;
        box-shadow: 0 0 5px 0 #ddd;
        border: 1px solid #ddd;
        color: #333;
        font-size: 16px;
    }


    .addhostdtl, .addhostdtl:hover, .addhostdtl:active {
        background-color: #f9bb00 !important;
        height: 60px;
        width: 165px;
        border: 2px solid #f9bb00;
        font-size: 18px;
        text-transform: uppercase;
        color: #333 !important;
        box-shadow: 0 0 !important;
        outline: 0 !important;
    }
    </style>	
<div class="moodle_database">
    <h2>Moodle site Detail</h2>
    <form id="add_faq_qus_ans" class="user_form_markbtn">
        <div class="form-group page_box">
            <div class="row page_height">
                <div class="col-md-2">
                    <label class="control-label" for="url">Host Name</label>
                </div>	  
                <div class="col-md-6">
                    <input type="text" class="form-control dbfield" id="hostname" placeholder="Add Host Name" name="hostname" value="<?php if(!empty($mdllists->hostname)){ echo $mdllists->hostname; } ?>"> 
                    <div id="msg1" class="spn_star"></div>
                </div> 
                <div class="col-md-4"></div>
            </div>

            <div class="row page_height">
                <div class="col-md-2">
                    <label class="control-label" for="url">Data base</label>
                </div>	  
                <div class="col-md-6">
                    <input type="text" class="form-control dbfield" id="dbname" placeholder="Add database Name" name="dbname" value="<?php if(!empty($mdllists->dbname)){ echo $mdllists->dbname; } ?>"> 
                    <div id="msg2" class="spn_star"></div>
                </div> 
                <div class="col-md-4"></div>
            </div>

            <div class="row page_height">
                <div class="col-md-2">
                    <label class="control-label" for="url">User Name</label>
                </div>	  
                <div class="col-md-6">
                    <input type="text" class="form-control dbfield" id="uname" placeholder="Add User name" name="uname" value="<?php if(!empty($mdllists->username)){ echo $mdllists->username; } ?>"> 
                    <div id="msg2" class="spn_star"></div>
                </div> 
                <div class="col-md-4"></div>
            </div>

            <div class="row page_height">
                <div class="col-md-2">
                    <label class="control-label" for="url">Password</label>
                </div>	  
                <div class="col-md-6">
                    <input type="password" class="form-control dbfield" id="dbpass" placeholder="Add Password" name="dbpass" value="<?php if(!empty($mdllists->password)){ echo $password=openssl_decrypt ($mdllists->password, "AES-128-CTR", "ASDSA#$%$::", 0 , 1234567891011121);} ?>"> 
                    <div id="msg2" class="spn_star"></div>
                </div> 
                <div class="col-md-4">  </div>
            </div>
			
			<div class="row page_height">
                <div class="col-md-2">
                    <label class="control-label" for="url">Moodle Prefix</label>
                </div>	  
                <div class="col-md-6">
                    <input type="text" class="form-control dbfield" id="mdlprf" placeholder="mdl_" name="mdlprf" value="<?php if(!empty($mdllists->mdlprefix)){ echo $mdllists->mdlprefix; }?>"> 
                    <div id="msg2" class="spn_star"></div>
                </div> 
                <div class="col-md-4">  </div>
            </div>
			
			

            <div class="row page_height">
                <div class="col-md-2">
                    <label class="control-label" for="url">Moodle Site Url</label>
                </div>	  
                <div class="col-md-6">
                    <input type="text" class="form-control dbfield" id="siteurl" placeholder="Add URL" name="siteurl" value="<?php if(!empty($mdllists->moodleurl)){ echo $mdllists->moodleurl; } ?>"> 
                    <div id="msg2" class="spn_star"></div>
                </div> 
                <div class="col-md-4"> </div>
            </div>


            <div class="row page_height">
                <div class="col-md-2">
                </div>	  
                <div class="col-md-6">
                    <button class="btn  addhostdtl" type="button">Save</button>
                </div> 
                <div class="col-md-4"></div>
            </div>						
        </div>

    </form>
    <br>
    <br>
<div class="col-md-12">
    <table id="dtBasicExample" class="table table-striped table-bordered" cellspacing="0" width="100%">
        <thead>
            <tr>
                <th class="th-sm">S.No
                </th>
                <th class="th-sm">Host Name
                </th>
                <th class="th-sm">Data Base Name
                </th>
                <th class="th-sm">User Name 
                </th>
				<th class="th-sm">Password
                </th>
				<th class="th-sm">moodle Prefix
				</th>
				<th class="th-sm">Moodle Site Url
				</th>
				
            </tr>
        </thead>
        <tbody>
                <tr> 
                    <td><?php echo 1; ?></td>
                    <td> <?php echo $mdllists->hostname; ?></td>
                    <td> <?php echo $mdllists->dbname; ?></td>
                    <td> <?php echo $mdllists->username; ?></td>
                    <td> <?php echo "************"; ?></td>
                    <td> <?php echo $mdllists->mdlprefix; ?></td>
                    <td> <?php echo $mdllists->moodleurl; ?></td>
                    
            </tr>
        </tbody>
    </table>
</div>

<script>
    jQuery(document).ready(function () {
        jQuery(".addhostdtl").click(function () { 
			var ajxurl            =   '<?php echo $plugingpath ?>';
			var hostname    =   document.getElementById("hostname").value;
			var dbname       =   document.getElementById("dbname").value;
			var userNme     =   document.getElementById("uname").value;
			var pass            =    document.getElementById("dbpass").value;
			var mdlprf            =    document.getElementById("mdlprf").value;
			var moodleurl    =    document.getElementById("siteurl").value;
		jQuery.ajax({
			type    :    "POST",
			url      :  ajxurl,
			data   :{
				action : 'add_moodle_connection_detail',
				hostname :  hostname,
				dbname    :  dbname,
				userNme  :  userNme,
				pass         :  pass,
				mdlprefix        :  mdlprf,
				moodleurl :  moodleurl
				    
			},
			
			     success: function (data) {
                   //alert(data); 
				window.location.reload();
            }
			
		}); 
			
		});


        });

 
</script>


</div>

	
<?php  }


function getmoodlecoursename($courseid, $con) {
	global $wpdb;  
	$mdllists= moodle_deta();
	$mdlprefix = $mdllists->mdlprefix;
	
    $result = mysqli_query($con, "select fullname from ".$mdlprefix."course WHERE id=$courseid");
    while ($row1 = $result->fetch_assoc()) {
        return $row1['fullname'];
    }
}

function showCourse() {
	$mdllists= moodle_deta();
	  global $wpdb;
      $plugingpath = plugins_url() . "/enrolled_moodle_course/enrolled_ajax.php";
	
    $args = array('numberposts' => -1, 'post_type' => 'product', 'post_status' => 'publish',);
    $products = get_posts($args);
	  
	 // get moodle connection data. 

	$mdlserver =$mdllists->hostname;
	$mdldb =$mdllists->dbname;
	$mdluser =$mdllists->username;
	$mdlpass =$mdllists->password;
	$decryption_iv = '1234567891011121'; 
	$ciphering = "AES-128-CTR"; 
   $decryption_key = "ASDSA#$%$::";  
   $options = 0; 
   //decrypt the data 
   $password=openssl_decrypt ($mdlpass, $ciphering, $decryption_key, $options, $decryption_iv); 	
	$mdlurl =$mdllists->moodleurl;
	$mdlprefix = $mdllists->mdlprefix;
    $server = $mdlserver;
    $username = $mdluser;
    $database = $mdldb;

    $con = mysqli_connect($server, $username, $password, $database);
    if (!$con) {
        echo "Failed to connect to MySQL: " . mysqli_connect_error();
    }

    $result = mysqli_query($con, "SELECT ".$mdlprefix."course.id, ".$mdlprefix."course.fullname from ".$mdlprefix."course");
    if (count($products) > 0) {
        ?>

    <link href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.10.15/css/dataTables.bootstrap.min.css" rel="stylesheet">
   <style>
        #adminmenuwrap {
            height: 100% !important;
        }
        
        .previous {
            border-radius: 4px 0px 0px 4px;
            cursor: pointer;
            font-style: italic;
            text-decoration: none;
            color: gray;
            margin-left: 10px;
            padding: 5px 20px;
            border: 1px solid #ccc;
        }
        
        .next {
            border-radius: 0px 4px 4px 0px;
            cursor: pointer;
            font-style: italic;
            text-decoration: none;
            color: gray;
            margin-left: 10px;
            padding: 5px 20px;
            border: 1px solid #ccc;
        }
        
        #example_paginate span {
            cursor: pointer;
            color: #fff !important;
            padding: 6px 15px !important;
            margin-left: 10px !important;
        }
#error{
color: #e00;
    font-size: 16px;
}
    </style>
 <h3>Courses Mapping</h3>
 <div class="impnote"> 
 </div>
<div class="grid">
    <div class="row">
        <div class="col-md-6">
            <form id="enrollment_form" class="user_form_markbtn">
                <div class="form-group">
                    <label for="email">Woocommerce Product Name:</label>
                    <select name="product" class="form-control">
                        <?php
                        foreach ($products as $product){
                        ?>
                        <option value="<?php echo $product->ID; ?>">
                            <?php echo $product->post_title; ?>
                        </option>
                        <?php
                        }
                        ?>
                    </select>
                </div>
                <?php
                }
                if ($result->num_rows > 0) {
                    // output data of each row
                    ?>
                    <div class="form-group">
                        <label for="pwd">Moodle Course Name:</label>
                        <select name="course" class="form-control">
                            <?php
                            while ($row = $result->fetch_assoc()) {
                                // echo "id: " . $row["id"]. "<br>";
                                ?>
                                <option value="<?php echo $row['id']; ?>">
                                    <?php echo $row['fullname']; ?>
                                </option>

                                <?php
                            }
                            ?>
                        </select>

                    </div>
                    <?php
                } else {
                    echo "0 results";
                }
                ?>            <input type="hidden" id="plugingpath" value="<?php echo $plugingpath; ?>">
                <p id="error"></p>
                <button class="btn btn-success course_enrollment_btn" type="button" onclick="fun_enroll()">Submit</button>

            </form>
        </div>
    </div>
</div>
<div class="divider"></div>
<h3>Courses Mapping List</h3>
<div class="row">
    <div class="col-md-8">
        <table id="example" class="table table-striped table-bordered" cellspacing="0" width="100%">
            <thead>
                <tr>
                    <th>S No.</th>
                    <th>WooCommerce Product Name</th>
                    <th>Moodle Course Name</th>
                    <th>Added Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tfoot>
                <tr>
                    <th>S No.</th>
                    <th>WooCommerce Product Name</th>
                    <th>Moodle Course Name</th>
                    <th>Added Date</th>
                    <th>Actions</th>
                </tr>
            </tfoot>
            <tbody>

                <?php
                global $wpdb;

                $myrows = $wpdb->get_results("SELECT id,courseid,productid,bundle FROM " . $wpdb->prefix . "wootomoodle ORDER BY id DESC");
                $i = 1;
                foreach ($myrows as $myrowsdata) {
                    $wooproduct = wc_get_product($myrowsdata->productid);
                    ?>
                    <tr>
                        <td>
    <?php echo $i . '.'; ?>
                        </td>
                        <td>
    <?php echo $wooproduct->get_title(); ?>
                        </td>
                        <td>
    <?php echo getmoodlecoursename($myrowsdata->courseid, $con); ?>
                        </td>
                        <td>
    <?php echo date('d/m/Y h:i:s', strtotime($myrowsdata->datecreated)); ?>
                        </td>
                        <td><a class="btn btn-danger" onclick="wootomoodledelete(<?php echo $myrowsdata->id; ?>)">Delete</a></td>
                    </tr>
                    <?php
                    $i++;
                }
                ?>

            </tbody>
        </table>
    </div>
</div>
    <script src="//code.jquery.com/jquery-1.12.4.js"></script>
    <script src="https://cdn.datatables.net/1.10.15/js/jquery.dataTables.min.js"> </script>
    <script src="https://cdn.datatables.net/1.10.15/js/jquery.dataTables.min.js"></script>
<script>
    function fun_enroll() {
        var plugingpath = $('#plugingpath').val();
        var md_productID = $('select[name=product]').val();
        var mdl_courseID = $('select[name=course]').val();
        jQuery.ajax({
            type: "POST",
            url: plugingpath,
            data: {
                action: 'insert_enrolled_data',
                md_productID: md_productID,
                mdl_courseID: mdl_courseID
            },
            success: function (data) {
                 //  alert(data); 
                // console.log(data);
                if (data == 1) {
                    $('#error').html('Data already exist');
                } else {
                    window.location.reload();
                } 
            }
        });
    }

    $(document).ready(function () {
        $('#example').DataTable();
    });


    function wootomoodledelete(id)
    {
         //alert(id);
        if (confirm("Are you sure you want to delete this Record?")) {
            jQuery.ajax({
                type: "POST",
                url: '<?php echo $plugingpath; ?>',
                data: {
                    action: 'delete_course',
                    enrolled_course_ID: id
                },
                success: function (data) {
                //alert(data); 
                window.location.reload();
                }
            });
        }
    }
</script>

<?php
}

 
//function create table 
function CreateTable_wootomoodle()
{
global $wpdb;
$faqtable = $wpdb->prefix . 'wootomoodle';

// create table 1
if($wpdb->get_var("show tables like '$faqtable'") != $faqtable)
{

$sql = "CREATE TABLE IF NOT EXISTS " . $faqtable . " (
  `id` int(9) NOT NULL AUTO_INCREMENT, 
  `courseid` int(20) NULL,
  `productid` int(20) NULL,
  `bundle` int(20) NULL,
  `datecreated` bigint NULL,
  PRIMARY KEY id (id) 
  );";

require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
dbDelta($sql);
}

}

//activation
register_activation_hook( __FILE__, 'CreateTable_wootomoodle');


//function create table 
function CreateTable_moodledetail()
{
global $wpdb;
$mldtb = $wpdb->prefix . 'moodledetail';

// create table 1
if($wpdb->get_var("show tables like '$mldtb'") != $mldtb)
{

$sql = "CREATE TABLE IF NOT EXISTS " . $mldtb . " (
  `id` int(9) NOT NULL AUTO_INCREMENT, 
  `hostname` text NULL,
  `dbname` varchar(20) NULL,
  `username` varchar(20) NULL,
  `password` varchar(220) NULL,
  `moodleurl` varchar(220) NULL,
  `mdlprefix` varchar(20) NULL,
  `datecreated` bigint NULL,
  PRIMARY KEY id (id) 
  );";

require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
dbDelta($sql);
}

}

//activation
register_activation_hook( __FILE__, 'CreateTable_moodledetail');



// create thanku page
class PageTemplater {

    /**
     * A reference to an instance of this class.
     */
    private static $instance;

    /**
     * The array of templates that this plugin tracks.
     */
    protected $templates;

    /**
     * Returns an instance of this class. 
     */
    public static function get_instance() {

        if (null == self::$instance) {
            self::$instance = new PageTemplater();
        }

        return self::$instance;
    }

    /**
     * Initializes the plugin by setting filters and administration functions.
     */
    private function __construct() {

        $this->templates = array();


        // Add a filter to the attributes metabox to inject template into the cache.
        if (version_compare(floatval(get_bloginfo('version')), '4.7', '<')) {

            // 4.6 and older
            add_filter(
                    'page_attributes_dropdown_pages_args', array($this, 'register_project_templates')
            );
        } else {

            // Add a filter to the wp 4.7 version attributes metabox
            add_filter(
                    'theme_page_templates', array($this, 'add_new_template')
            );
        }

        // Add a filter to the save post to inject out template into the page cache
        add_filter(
                'wp_insert_post_data', array($this, 'register_project_templates')
        );


        // Add a filter to the template include to determine if the page has our 
        // template assigned and return it's path
        add_filter(
                'template_include', array($this, 'view_project_template')
        );


        // Add your templates to this array.
        $this->templates = array(
            'thankyou.php' => 'Thank You',
            'moodlelogin.php' => 'moodle login',
        );
    }

    /**
     * Adds our template to the page dropdown for v4.7+
     *
     */
    public function add_new_template($posts_templates) {
        $posts_templates = array_merge($posts_templates, $this->templates);
        return $posts_templates;
    }

    /**
     * Adds our template to the pages cache in order to trick WordPress
     * into thinking the template file exists where it doens't really exist.
     */
    public function register_project_templates($atts) {

        // Create the key used for the themes cache
        $cache_key = 'page_templates-' . md5(get_theme_root() . '/' . get_stylesheet());

        // Retrieve the cache list. 
        // If it doesn't exist, or it's empty prepare an array
        $templates = wp_get_theme()->get_page_templates();
        if (empty($templates)) {
            $templates = array();
        }

        // New cache, therefore remove the old one
        wp_cache_delete($cache_key, 'themes');

        // Now add our template to the list of templates by merging our templates
        // with the existing templates array from the cache.
        $templates = array_merge($templates, $this->templates);

        // Add the modified cache to allow WordPress to pick it up for listing
        // available templates
        wp_cache_add($cache_key, $templates, 'themes', 1800);

        return $atts;
    }

    /**
     * Checks if the template is assigned to the page
     */
    public function view_project_template($template) {

        // Get global post
        global $post;

        // Return template if post is empty
        if (!$post) {
            return $template;
        }

        // Return default template if we don't have a custom one defined
        if (!isset($this->templates[get_post_meta(
                                $post->ID, '_wp_page_template', true
                )])) {
            return $template;
        }

        $file = plugin_dir_path(__FILE__) . get_post_meta(
                        $post->ID, '_wp_page_template', true
        );

        // Just to be safe, we check if the file exist first
        if (file_exists($file)) {
            return $file;
        } else {
            echo $file;
        }

        // Return template
        return $template;
    }

}

add_action('plugins_loaded', array('PageTemplater', 'get_instance'));



//  function for call thanku page 
add_action('template_redirect', 'wc_custom_redirect_after_purchase');
function wc_custom_redirect_after_purchase() {
    global $wp;

    if (is_checkout() && !empty($wp->query_vars['order-received'])) {
        $order_id = absint($wp->query_vars['order-received']);
        $order_key = wc_clean($_GET['key']);
        /**
         * Replace {PAGE_ID} with the ID of your page
         */
		 
		 // get page id
        $pages = get_pages(array(
            'meta_key' => '_wp_page_template',
            'meta_value' => 'thankyou.php'
        ));
        foreach ($pages as $page) {
             $page->ID;
			//echo "<br>";
        }
         $redirect = get_permalink($page->ID);
         $redirect;
       // die;
        $redirect .= get_option('permalink_structure') === '' ? '&' : '?';
        $redirect .= 'order=' . $order_id . '&key=' . $order_key;

        wp_redirect($redirect);
        exit;
    } 
}


