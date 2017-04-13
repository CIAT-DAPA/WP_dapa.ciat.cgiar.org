<?php
/*
Plugin Name: Media from FTP
Plugin URI: https://wordpress.org/plugins/media-from-ftp/
Version: 9.61
Description: Register to media library from files that have been uploaded by FTP.
Author: Katsushi Kawamori
Author URI: http://riverforest-wp.info/
Text Domain: media-from-ftp
Domain Path: /languages
*/

/*  Copyright (c) 2013- Katsushi Kawamori (email : dodesyoswift312@gmail.com)
    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; version 2 of the License.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
*/

	load_plugin_textdomain('media-from-ftp');
//	load_plugin_textdomain('media-from-ftp', false, basename( dirname( __FILE__ ) ) . '/languages' );

	define("MEDIAFROMFTP_PLUGIN_BASE_FILE", plugin_basename(__FILE__));
	define("MEDIAFROMFTP_PLUGIN_BASE_DIR", dirname(__FILE__));
	define("MEDIAFROMFTP_PLUGIN_URL", plugins_url($path='',$scheme=null).'/media-from-ftp');

	include_once MEDIAFROMFTP_PLUGIN_BASE_DIR.'/inc/MediaFromFtp.php';
	$mediafromftp = new MediaFromFtp();
	define("MEDIAFROMFTP_PLUGIN_SITE_URL", $mediafromftp->siteurl());
	list($upload_dir, $upload_url, $upload_path) = $mediafromftp->upload_dir_url_path();
	define("MEDIAFROMFTP_PLUGIN_UPLOAD_DIR", $upload_dir);
	define("MEDIAFROMFTP_PLUGIN_UPLOAD_URL", $upload_url);
	define("MEDIAFROMFTP_PLUGIN_UPLOAD_PATH", $upload_path);
	unset($mediafromftp, $upload_dir, $upload_url, $upload_path);

	define("MEDIAFROMFTP_PLUGIN_TMP_URL", MEDIAFROMFTP_PLUGIN_UPLOAD_URL.'/media-from-ftp-tmp');
	define("MEDIAFROMFTP_PLUGIN_TMP_DIR", MEDIAFROMFTP_PLUGIN_UPLOAD_DIR.'/media-from-ftp-tmp');

	// Make tmp dir
	if ( !is_dir( MEDIAFROMFTP_PLUGIN_TMP_DIR ) ) {
		wp_mkdir_p( MEDIAFROMFTP_PLUGIN_TMP_DIR );
	}

	require_once( MEDIAFROMFTP_PLUGIN_BASE_DIR.'/req/MediaFromFtpRegist.php' );
	$mediafromftpregist = new MediaFromFtpRegist();
	register_activation_hook( __FILE__, array($mediafromftpregist, 'log_settings') );
	add_action( 'plugins_loaded', array($mediafromftpregist, 'log_settings') );
	add_action( 'admin_init', array($mediafromftpregist, 'register_settings'));
	unset($mediafromftpregist);

	require_once( MEDIAFROMFTP_PLUGIN_BASE_DIR.'/req/MediaFromFtpAdmin.php' );
	$mediafromftpadmin = new MediaFromFtpAdmin();
	add_filter( 'plugin_action_links', array($mediafromftpadmin, 'settings_link'), 10, 2 );
	add_action( 'admin_menu', array($mediafromftpadmin, 'add_pages') );
	add_action( 'admin_enqueue_scripts', array($mediafromftpadmin, 'load_custom_wp_admin_style') );
	add_action( 'admin_footer', array($mediafromftpadmin, 'load_custom_wp_admin_style2') );
	add_action( 'screen_settings', array($mediafromftpadmin, 'search_register_show_screen_options'), 10, 2 );
	add_filter( 'set-screen-option', array($mediafromftpadmin, 'search_register_set_screen_options'), 11, 3 );
	add_filter( 'contextual_help', array($mediafromftpadmin, 'search_register_help_tab'), 12, 3);
	unset($mediafromftpadmin);

	require_once( MEDIAFROMFTP_PLUGIN_BASE_DIR.'/req/MediaFromFtpCron.php' );
	$mediafromftpcron = new MediaFromFtpCron();
	add_action( 'MediaFromFtpCronHook', array($mediafromftpcron, 'CronDo') );
	register_activation_hook( __FILE__, array($mediafromftpcron, 'CronAllStart') );
	register_deactivation_hook( __FILE__, array($mediafromftpcron, 'CronAllStop') );
	unset($mediafromftpcron);

	require_once( MEDIAFROMFTP_PLUGIN_BASE_DIR.'/req/MediaFromFtpAjax.php' );
	$mediafromftpajax = new MediaFromFtpAjax();
	$action1 = 'mediafromftp-update-ajax-action';
	$action2 = 'mediafromftp-import-ajax-action';
	add_action( 'wp_ajax_'.$action1, array($mediafromftpajax, 'mediafromftp_update_callback') );
	add_action( 'wp_ajax_mediafromftp_message', array($mediafromftpajax, 'mediafromftp_message_callback') );
	add_action( 'wp_ajax_'.$action2, array($mediafromftpajax, 'mediafromftp_medialibraryimport_update_callback') );
	add_action( 'wp_ajax_mediafromftp_medialibraryimport_message', array($mediafromftpajax, 'mediafromftp_medialibraryimport_message_callback') );
	unset($mediafromftpajax, $action1, $action2);

?>