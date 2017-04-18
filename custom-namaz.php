<?php
/*
	Plugin Name: CustomNamaz
	Description: Plugin for customization prayer time.
	Author: Mambago
	Version: 1.1
	Date: 19.03.2017
*/

require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
if (!defined("CUSTOM_NAMAZ_PLUGIN_DIR")) define("CUSTOM_NAMAZ_PLUGIN_DIR",	plugin_dir_path( __FILE__ ));


//--------------------------------------------------------------------------------------------------------------//
// FUNCTIONS FOR REPLACING TABLE NAMES
//--------------------------------------------------------------------------------------------------------------//
if(!function_exists("custom_namaz_table"))
{
	function custom_namaz_table()
	{
		global $wpdb;
		return $wpdb->prefix . "customnamaz";
	}
}

if(!function_exists("custom_namaz_text_table"))
{
	function custom_namaz_text_table()
	{
		global $wpdb;
		return $wpdb->prefix . "text_customnamaz";
	}
}

//-------------------------------------------------------------------------------------------------------------//
if (!function_exists("custom_namaz_plugin_install"))
{
	function custom_namaz_plugin_install() {

		add_option('namaz-language', 1);
		if(file_exists(CUSTOM_NAMAZ_PLUGIN_DIR. "/lib/install-script.php"))
		{
			include CUSTOM_NAMAZ_PLUGIN_DIR . "/lib/install-script.php";
		}
	}
}

//-------------------------------------------------------------------------------------------------------------//
if (!function_exists("custom_namaz_plugin_uninstall"))
{
	function custom_namaz_plugin_uninstall() {

		delete_option('namaz-language');
		include CUSTOM_NAMAZ_PLUGIN_DIR . "/lib/uninstall-script.php";
	}
}

//--------------------------------------------------------------------------------------------------------------//
//FUNCTION FOR SHOW PLUGIN WIDGET ON FRONT
//--------------------------------------------------------------------------------------------------------------//
if (!function_exists("custom_namaz_widget"))
{
	function custom_namaz_widget() {
		include CUSTOM_NAMAZ_PLUGIN_DIR . "/public/namaz-widget.php";
	}
}

//--------------------------------------------------------------------------------------------------------------//
//CODE FOR CALLING JAVASCRIPT FUNCTIONS
//--------------------------------------------------------------------------------------------------------------//
if (!function_exists("namaz_back_scripts_calls")) {
	function namaz_back_scripts_calls () {
		wp_enqueue_script("jquery");
		wp_enqueue_script("namaz-script.js", plugins_url("custom-namaz/admin/js/namaz-script.js",dirname(__FILE__)));
	}
}

if (!function_exists("namaz_front_scripts_calls")) {
	function namaz_front_scripts_calls () {
		wp_register_script( "namaz_script", plugins_url("custom-namaz/public/js/namaz-front-script.js",dirname(__FILE__)), array('jquery') );
		wp_localize_script( 'namaz_script', 'namazAjax', array( 'ajaxurl' => admin_url( 'admin-ajax.php' )));

		wp_enqueue_script("jquery");
		wp_enqueue_script("namaz_script");
	}
}

//--------------------------------------------------------------------------------------------------------------//
//CODE FOR CALLING STYLE SHEETS
//--------------------------------------------------------------------------------------------------------------//
if (!function_exists("namaz_back_css_calls")) 
{
	function namaz_back_css_calls () {
		wp_enqueue_style("namaz-style.css", plugins_url("custom-namaz/admin/css/namaz-style.css",dirname(__FILE__)));
	}
}

if (!function_exists("namaz_front_css_calls")) 
{
	function namaz_front_css_calls () {
		wp_enqueue_style("namaz-front-style.css", plugins_url("custom-namaz/public/css/namaz-front-style.css",dirname(__FILE__)));
	}
}
 
//--------------------------------------------------------------------------------------------------------------//
//CODE FOR ADDING PLUGIN PAGE TO ADMIN MENU
//--------------------------------------------------------------------------------------------------------------//
if (!function_exists("custom_namaz_setup_menu")) 
{
	function custom_namaz_setup_menu(){
	        add_menu_page( 'Расписание Намазов', 'Намазы', 'manage_options', 'custom-namaz', 'custom_namaz_settings', plugins_url( 'admin/images/pray_grey.png', __FILE__ ));
	}
}

if (!function_exists("custom_namaz_settings")) 
{
	function custom_namaz_settings(){
		include CUSTOM_NAMAZ_PLUGIN_DIR . "/admin/index.php";
	}
}

if (!function_exists("custom_namaz_settings")) 
{
	function custom_namaz_settings(){
		include CUSTOM_NAMAZ_PLUGIN_DIR . "/admin/index.php";
	}
}


function namaz_selected(){
	
	global $wpdb;
	if (!empty($_REQUEST['date']) && !empty($_REQUEST['city']))  
	{
		$queryResult = $wpdb->get_results("SELECT * FROM ". custom_namaz_table() ." WHERE city='".$_REQUEST['city']."' AND date='".$_REQUEST['date']."'", ARRAY_A);

		$queryResult2 = $wpdb->get_results("SELECT * FROM ". custom_namaz_text_table() ." WHERE date='".$_REQUEST['date']."'", ARRAY_A);
	}


	$result = array(
		'date' 		 => $_REQUEST['date'],
		'city' 		 => $_REQUEST['city'],
		'namaz' 	 => $queryResult[0],
		'text' 		 => $queryResult2[0]
		);

	wp_send_json($result);
}


// Add ajax action
add_action('wp_ajax_namaz_selected', 'namaz_selected');
add_action('wp_ajax_nopriv_namaz_selected', 'namaz_selected');

add_shortcode("custom_namaz_widget", "custom_namaz_widget");

register_activation_hook(__FILE__, 'custom_namaz_plugin_install');
register_uninstall_hook(__FILE__, 'custom_namaz_plugin_uninstall');

add_action('admin_menu', 'custom_namaz_setup_menu');

add_action("admin_init", "namaz_back_scripts_calls");
add_action("admin_init", "namaz_back_css_calls");

add_action("init", "namaz_front_scripts_calls");
add_action("init", "namaz_front_css_calls");
?>