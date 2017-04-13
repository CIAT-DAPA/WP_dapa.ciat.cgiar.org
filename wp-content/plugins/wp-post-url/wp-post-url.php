<?php
/*
Plugin Name: WP Post URL
Plugin URI: http://nitinmaurya.com/
Description: This plugin is useful to change the url of main website, pages and posts as well as it also replace all OLD URL with NEW URL in whole database.
Version: 1.0
Author: Nitin Maurya
Author URI: http://nitinmaurya.com/
License: A "Slug" license name e.g. GPL2
*/
register_activation_hook(__FILE__,'nm_wp_post_url_install');
function nm_wp_post_url_install(){
	global $wp_version;
	if(version_compare($wp_version, "3.2.1", "<")) {
		deactivate_plugins(basename(__FILE__));
		wp_die("This plugin requires WordPress version 3.2.1 or higher.");
	}
}
add_action('admin_menu','nm_wp_post_url_menu');
    function nm_wp_post_url_menu(){
        add_menu_page('WP Post Url', 'WP Post Url','administrator', 'wp-post-url.php', 'nm_wp_post_url_action', plugins_url('link.png', __FILE__));
   }
function nm_wp_post_url_action(){
	require_once('form.php');
	switch($_REQUEST[act]) {
			case "save":
				nm_wp_change_post_url();
				break;
			default:
				
	}
}   
 

function nm_wp_change_post_url(){
		if(!empty($_REQUEST['old_url']) && !empty($_REQUEST['new_url'])){
				nm_change_url($_REQUEST['old_url'],$_REQUEST['new_url']);
				echo '<div class="updated below-h2" id="message" style="position:relative; clear:both;"><p>All URL has been updated.</p></div>';
			
		}
}


function nm_change_url($old_url,$new_url){
$old_url=trim($old_url);
$new_url=trim($new_url);
if(!empty($old_url) && !empty($new_url)){
	global $wpdb;
	$sql='SHOW TABLES FROM '.DB_NAME;
	$list_of_tables=$wpdb->get_results( $sql );
	foreach($list_of_tables as $nm_key=>$nm_val){
		$sql2 = "SHOW COLUMNS FROM ".$nm_val->Tables_in_wordpress;
		$list_of_columns=$wpdb->get_results( $sql2 );
		foreach($list_of_columns as $nm_key1=>$nm_val1){
			$sql3="UPDATE `".$nm_val->Tables_in_wordpress."` SET `".$nm_val1->Field."` = replace(".$nm_val1->Field.", '".$old_url."', '".$new_url."')";
			$wpdb->get_results( $sql3 );
		}
	}
}
	
}
?>