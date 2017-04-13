<?php
/**
 * Media from FTP
 * 
 * @package    Media from FTP
 * @subpackage MediafromFtpAjax
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

class MediaFromFtpAjax {

	/* ==================================================
	 * Update Files Callback
	 * 
	 * @since	9.30
	 */
	function mediafromftp_update_callback(){

		$action1 = 'mediafromftp-update-ajax-action';
	    if ( isset( $_REQUEST['nonce'] ) && wp_verify_nonce( $_REQUEST['nonce'], $action1 ) ) {
	        if ( current_user_can( 'upload_files' ) ) {
				$maxcount = intval($_POST["maxcount"]);
				$new_url_attach = $_POST["new_url"];
				$new_url_datetime = $_POST["new_datetime"];

				require_once( MEDIAFROMFTP_PLUGIN_BASE_DIR.'/req/MediaFromFtpAdmin.php' );
				$mediafromftpadmin = new MediaFromFtpAdmin();
				$mediafromftp_settings = get_option($mediafromftpadmin->wp_options_name());
				unset($mediafromftpadmin);

				if (!empty($new_url_attach)) {

					include_once MEDIAFROMFTP_PLUGIN_BASE_DIR.'/inc/MediaFromFtp.php';
					$mediafromftp = new MediaFromFtp();

					$dateset = $mediafromftp_settings['dateset'];
					$yearmonth_folders = get_option('uploads_use_yearmonth_folders');
					$exif_text_tag = NULL;
					if ( $mediafromftp_settings['caption']['apply'] ) {
						$exif_text_tag = $mediafromftp_settings['caption']['exif_text'];
					}

					$exts = explode('.', wp_basename($new_url_attach));
					$ext = end($exts);

					// Delete Cash
					$mediafromftp->delete_cash($ext, $new_url_attach);

					// Regist
					list($attach_id, $new_attach_title, $new_url_attach, $metadata) = $mediafromftp->regist($ext, $new_url_attach, $new_url_datetime, $dateset, $yearmonth_folders, $mediafromftp_settings['character_code'], $mediafromftp_settings['cron']['user']);

					if ( $attach_id == -1 || $attach_id == -2 ) { // error
						$error_title = $mediafromftp->mb_utf8($new_attach_title, $mediafromftp_settings['character_code']);
						$error_url = $mediafromftp->mb_utf8($new_url_attach, $mediafromftp_settings['character_code']);
						if ( $attach_id == -1 ) {
							echo '<div class="notice notice-error is-dismissible"><ul><li>'.'<div>'.__('File name:').$error_title.'</div>'.'<div>'.__('Directory name:', 'media-from-ftp').$error_url.'</div>'.sprintf(__('<div>You need to make this directory writable before you can register this file. See <a href="%1$s" target="_blank">the Codex</a> for more information.</div><div>Or, filename or directoryname must be changed of illegal. Please change Character Encodings for Server of <a href="%2$s">Settings</a>.</div>', 'media-from-ftp'), 'http://codex.wordpress.org/Changing_File_Permissions', admin_url('admin.php?page=mediafromftp-settings')).'</li></div>';
						} else if ( $attach_id == -2 ) {
							echo '<div class="notice notice-error is-dismissible"><ul><li><div>'.__('Title').': '.$error_title.'</div>'.'<div>URL: '.$error_url.'</div><div>'.__('This file could not be registered in the database.', 'media-from-ftp').'</div></li></ul></div>';
						}
					} else {
						// Outputdata
						list($imagethumburls, $mimetype, $length, $stamptime, $file_size, $exif_text) = $mediafromftp->output_metadata($ext, $attach_id, $metadata, $mediafromftp_settings['character_code'], $exif_text_tag);

						$image_attr_thumbnail = wp_get_attachment_image_src($attach_id, 'thumbnail', true);

						$output_html = $mediafromftp->output_html_and_log($ext, $attach_id, $new_attach_title, $new_url_attach, $imagethumburls, $mimetype, $length, $stamptime, $file_size, $exif_text, $image_attr_thumbnail, $mediafromftp_settings);

						header('Content-type: text/html; charset=UTF-8');
						echo $output_html;

					}
					unset($mediafromftp);

				}
			}
		} else {
			status_header( '403' );
			echo 'Forbidden';
		}

		wp_die();

	}

	/* ==================================================
	 * Update Messages Callback
	 * 
	 * @since	9.30
	 */
	function mediafromftp_message_callback(){

		$error_count = intval($_POST["error_count"]);
		$error_update = $_POST["error_update"];
		$success_count = intval($_POST["success_count"]);

		$output_html = NULL;
		if ( $error_count > 0 ) {
			$error_message = sprintf(__('Errored to the registration of %1$d files.', 'media-from-ftp'), $error_count);
			$output_html .= '<div class="notice notice-error is-dismissible"><ul><li><div>'.$error_message.'</div>'.$error_update.'</li></ul></div>';
		}
		$success_message = sprintf(__('Succeeded to the registration of %1$d files.', 'media-from-ftp'), $success_count);
		$output_html .= '<div class="notice notice-success is-dismissible"><ul><li><div>'.$success_message.'</li></ul></div>';

		header('Content-type: text/html; charset=UTF-8');
		echo $output_html;

		wp_die();

	}

	/* ==================================================
	 * Import Files Callback
	 * 
	 * @since	9.40
	 */
	function mediafromftp_medialibraryimport_update_callback(){

		$action2 = 'mediafromftp-import-ajax-action';
	    if ( isset( $_REQUEST['nonce'] ) && wp_verify_nonce( $_REQUEST['nonce'], $action2 ) ) {
	        if ( current_user_can( 'upload_files' ) ) {
				$file = $_POST["file"];
				$filepath = str_replace(MEDIAFROMFTP_PLUGIN_UPLOAD_DIR.'/' , '', $file);
				if ( is_file($file) ) {
					if ( !empty($_POST["db_array"]) ) {
						$db_array = $_POST["db_array"];
						global $wpdb;
						$table_name = $wpdb->prefix.'posts';
						$wpdb->insert( $table_name, $db_array );
						update_attached_file( $db_array['ID'], $filepath ) ;
						if ( !empty($_POST["db_wp_attachment_metadata"]) ) {
							$metadata = maybe_unserialize(stripslashes($_POST["db_wp_attachment_metadata"]));
							update_post_meta( $db_array['ID'], '_wp_attachment_metadata', $metadata );
						}
						if ( !empty($_POST["db_thumbnail_id"]) ) {
							update_post_meta( $db_array['ID'], '_thumbnail_id', $_POST["db_thumbnail_id"] );
						}
						if ( !empty($_POST["db_cover_hash"]) ) {
							update_post_meta( $db_array['ID'], '_cover_hash', $_POST["db_cover_hash"] );
						}
						if ( !empty($_POST["db_wp_attachment_image_alt"]) ) {
							update_post_meta( $db_array['ID'], '_wp_attachment_image_alt', $_POST["db_wp_attachment_image_alt"] );
						}
						$msg = 'success_db';
						$output_html = $msg.','.'<div>'.__('Media').': <a href="'.get_permalink($db_array['ID']).'" target="_blank" style="text-decoration: none; color: green;">'.$this->esc_title($db_array['post_title']).'</a>: '.'<a href="'.MEDIAFROMFTP_PLUGIN_UPLOAD_URL.'/'.$filepath.'" target="_blank" style="text-decoration: none;">'.$filepath.'</a></div>';
					} else {
						$msg = 'success';
						$output_html = $msg.','.'<div>'.__('Thumbnail').': '.'<a href="'.MEDIAFROMFTP_PLUGIN_UPLOAD_URL.'/'.$filepath.'" target="_blank" style="text-decoration: none;">'.$filepath.'</a></div>';
					}
				} else {
					$error_string = __('No file!', 'media-from-ftp');
					$msg = '<div>'.$filepath.': '.$error_string.'</div>';
					$output_html = $msg.','.'<div>'.$filepath.'<span style="color: red;"> &#8811; '.$error_string.'</span></div>';
				}

				header('Content-type: text/html; charset=UTF-8');
				echo $output_html;
			}
		} else {
			status_header( '403' );
			echo 'Forbidden';
		}

		wp_die();

	}

	/* ==================================================
	 * Import Messages Callback
	 * 
	 * @since	9.40
	 */
	function mediafromftp_medialibraryimport_message_callback(){

		$error_count = intval($_POST["error_count"]);
		$error_update = $_POST["error_update"];
		$success_count = intval($_POST["success_count"]);
		$db_success_count = intval($_POST["db_success_count"]);

		$output_html = NULL;
		if ( $error_count > 0 ) {
			$error_message = sprintf(__('Errored to the registration of %1$d files.', 'media-from-ftp'), $error_count);
			$output_html .= '<div class="notice notice-error is-dismissible"><ul><li><div>'.$error_message.'</div>'.$error_update.'</li></ul></div>';
		}
		$success_message = sprintf(__('Succeeded to the registration of %1$d files and %2$d items for MediaLibrary.', 'media-from-ftp'), $success_count, $db_success_count);
		$output_html .= '<div class="notice notice-success is-dismissible"><ul><li><div>'.$success_message.'</li></ul></div>';

		header('Content-type: text/html; charset=UTF-8');
		echo $output_html;

		wp_die();

	}

	/* ==================================================
	 * Escape Title
	 * @param	string	$str
	 * @return	string	$str
	 * @since	9.41
	 */
	function esc_title($str){

		$str = esc_html($str);
		$str = str_replace(',', '&#44;', $str);

		return $str;
	}

}

?>
