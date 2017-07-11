<?php defined( 'ABSPATH' ) or die( 'No script kiddies please!' );
/**
 * Plugin Name: Redirect to random shop
 * Description: This plugin rewrites shop urls with a chosen random name. Instrunctions: Just add shops in the settings and 'rewrite' class in your 'a' tag
 * Version: 1.0.1
 * Author: Alejandro Hernandez
 * Author URI: https://github.com/alexhdz047
 * License: GPL2
 */

 // function to create the DB / Options / Defaults
function rs_options_install() {

     global $wpdb;

     $table_name = $wpdb->prefix . "name_shops";
     $charset_collate = $wpdb->get_charset_collate();
     $sql = "CREATE TABLE $table_name (
             `id` int NOT NULL AUTO_INCREMENT,
             `name` varchar(50) NOT NULL UNIQUE,
             PRIMARY KEY (`id`)
           ) $charset_collate; ";

     require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
     dbDelta($sql);
 }

 // run the install scripts upon plugin activation
 register_activation_hook(__FILE__, 'rs_options_install');

 add_action('admin_menu', 'my_plugin_menu');

 function my_plugin_menu() {
   //this is the main item for the menu
 	add_menu_page('Shops', //page title
 	'Redirect to random shop', //menu title
 	'manage_options', //capabilities
 	'random_shop_list', //menu slug
 	'random_shop_list' //function
 	);

 	//this is a submenu
 	add_submenu_page('random_shop_list', //parent slug
 	'Add New Shop', //page title
 	'Add New', //menu title
 	'manage_options', //capability
 	'random_shop_add', //menu slug
 	'random_shop_add'); //function

 	//this submenu is HIDDEN, however, we need to add it anyways
 	add_submenu_page(null, //parent slug
 	'Update Shop', //page title
 	'Update', //menu title
 	'manage_options', //capability
 	'random_shop_update', //menu slug
 	'random_shop_update'); //function
 }

/* function my_plugin_settings_page() {
?>
<div class="wrap">
<h2>Staff Details</h2>
<form method="post" action="options.php">
 <table class="form-table">
     <tr valign="top">
     <th scope="row">Shop names</th>
       <td>

       </td>
     </tr>
 </table>

 <?php submit_button(); ?>

</form>
</div>
<?php
 }*/
 add_action( 'wp_enqueue_scripts', 'enqueue_functions' );

 function enqueue_functions() {
   wp_enqueue_script( 'functions', plugin_dir_url( __FILE__ ) . 'assets/js/functions.js',array( 'jquery' ), null, true );
   // Localize the script with new data
   global $wpdb;
   $final_array = array();
   $table_name = $wpdb->prefix . "name_shops";
   $rows = $wpdb->get_results("SELECT name from $table_name");
   foreach ($rows as $row) {
     array_push($final_array, $row->name);
   }
   $array = array(
   	'shops' => $rows
   );
   wp_localize_script( 'functions', 'object', $final_array );
 }

define('ROOTDIR', plugin_dir_path(__FILE__));
require_once(ROOTDIR . 'shops-list.php');
require_once(ROOTDIR . 'shops-add.php');
require_once(ROOTDIR . 'shops-update.php');
