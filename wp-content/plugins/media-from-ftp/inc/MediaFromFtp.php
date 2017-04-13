<?php
/**
 * Media from FTP
 * 
 * @package    Media from FTP
 * @subpackage MediaFromFtp Main Functions
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

class MediaFromFtp {

	/* ==================================================
	 * @param	string	$dir
	 * @param	string	$extpattern
	 * @param	array	$mediafromftp_settings
	 * @return	array	$list
	 * @since	1.0
	 */
	function scan_file($dir, $extpattern, $mediafromftp_settings) {

		// for mediafromftpcmd.php
		$cmdoptions = getopt("s:d:e:t:x:p:f:c:hgm");

		if ( isset($cmdoptions['f']) ) {
			$search_limit_number = $cmdoptions['f'];
		} else {
			$search_limit_number = $mediafromftp_settings['search_limit_number'];
		}

		$thumb_deep_search = FALSE;
		if ( isset($cmdoptions['m']) ) {
			$thumb_deep_search = TRUE;
		} else {
			$thumb_deep_search = $mediafromftp_settings['thumb_deep_search'];
		}
		if ( $thumb_deep_search ) {
			$excludefile = 'media-from-ftp-tmp';	// tmp dir file
		} else {
			$excludefile = '-[0-9]+x[0-9]+\.|media-from-ftp-tmp';	// thumbnail & tmp dir file
		}
		global $blog_id;
		if ( is_multisite() && is_main_site($blog_id) ) {
			$excludefile .= '|\/sites\/';
		}
		if ( isset($cmdoptions['e']) ) {
				$excludefile .= '|'.$cmdoptions['e'];
		} else {
			if( !empty($mediafromftp_settings['exclude']) ){
				$excludefile .= '|'.$mediafromftp_settings['exclude'];
			}
		}

		$ext2typefilter = $mediafromftp_settings['ext2typefilter'];
		if ( isset($cmdoptions['t']) ) {
			$ext2typefilter = $cmdoptions['t'];
		} else {
			if (!empty($_POST['ext2type'])){
				$ext2typefilter = $_POST['ext2type'];
			}
		}

		unset($cmdoptions);

		$searchtext = '.*';
		if (!empty($_POST['searchtext'])){
			$searchtext = $this->mb_encode_multibyte($_POST['searchtext'], $mediafromftp_settings['character_code']);
		}else if (!empty($_GET['searchtext'])){
			$searchtext = $this->mb_encode_multibyte($_GET['searchtext'], $mediafromftp_settings['character_code']);
		}

		$files = scandir($dir);
		$list = array();
		$count = 0;
		foreach ($files as $file) {
			if($file == '.' || $file == '..'){
				continue;
			}
			$fullpath = rtrim($dir, '/') . '/' . $file;
			if (is_file($fullpath)) {
				if (!preg_match("/".$excludefile."/", $fullpath)) {
					$exts = explode('.', $file);
					$ext = end($exts);
					$searchflag = @preg_match("/".$searchtext."/", $fullpath);
					if (!$searchflag) {
						// for "preg_match error: Compilation failed: missing terminating ] for character class"
						$searchflag = preg_match("/".preg_quote($searchtext,'/')."/", $fullpath);
					}
					if ($searchflag) {
						if (preg_match("/".$extpattern."/", $ext)) {
							if ( $ext2typefilter === wp_ext2type($ext) || $ext2typefilter === 'all' ) {
								++$count;
								if ( $count > $search_limit_number ) {
									break;
								}
								$list[] = $fullpath;
							}
						}
					}
				}
			}
			if (is_dir($fullpath)) {
				$list = array_merge($list, $this->scan_file($fullpath, $extpattern, $mediafromftp_settings));
			}
		}

	   	return $list;

	}

	/* ==================================================
	 * @param	string	$dir
	 * @return	array	$dirlist
	 * @since	2.1
	 */
	function scan_dir($dir) {

		$excludedir = 'media-from-ftp-tmp';	// tmp dir
		global $blog_id;
		if ( is_multisite() && is_main_site($blog_id) ) {
			$excludedir .= '|\/sites\/';
		}

		$files = scandir($dir);
		$list = array();
		foreach ($files as $file) {
			if($file == '.' || $file == '..'){
				continue;
			}
			$fullpath = rtrim($dir, '/') . '/' . $file;
			if (is_dir($fullpath)) {
				if (!preg_match("/".$excludedir."/", $fullpath)) {
					$list[] = $fullpath;
				}
				$list = array_merge($list, $this->scan_dir($fullpath));
			}
		}

		arsort($list);
		return $list;

	}

	/* ==================================================
	 * @param	string	$extfilter
	 * @return	string	$extpattern
	 * @since	2.2
	 */
	function extpattern($extfilter){

		$extpattern = NULL;

		if ( $extfilter === 'all' ) {
			global $user_ID;
			$mimes = get_allowed_mime_types($user_ID);
			foreach ($mimes as $ext => $mime) {
				$extpattern .= $ext.'|'.strtoupper($ext).'|';
			}
			$extpattern = substr($extpattern, 0, -1);
		} else {
			$extpattern = $extfilter.'|'.strtoupper($extfilter);
		}

		return $extpattern;

	}

	/* ==================================================
	 * @param	string	$suffix
	 * @return	string	$mimetype
	 * @since	1.0
	 */
	function mime_type($suffix){

		$suffix = str_replace('.', '', $suffix);

		global $user_ID;
		$mimes = get_allowed_mime_types($user_ID);

		foreach ($mimes as $ext => $mime) {
	    	if ( preg_match("/".$ext."/i", $suffix) ) {
				$mimetype = $mime;
			}
		}

		return $mimetype;

	}

	/* ==================================================
	 * @param	string	$ext
	 * @param	string	$file
	 * @param	string	$new_url
	 * @return	string 	$view_thumb_url
	 * @since	2.36
	 */
	function create_cash($ext, $file, $new_url){

		$cash_thumb_key = md5($new_url);
		$cash_thumb_filename = MEDIAFROMFTP_PLUGIN_TMP_DIR.'/'.$cash_thumb_key.'.'.$ext;
		$value_cash = get_transient( $cash_thumb_key );
		if ( $value_cash <> FALSE ) {
			if ( ! file_exists( $cash_thumb_filename )) {
				delete_transient( $cash_thumb_key );
				$value_cash = FALSE;
			}
		}
		if ( $value_cash == FALSE ) {
			if ( ! file_exists( $cash_thumb_filename )) {
				$cash_thumb = wp_get_image_editor( $file );
				if ( ! is_wp_error( $cash_thumb ) ) {
					$cash_thumb->resize( 40 ,40, true );
					$cash_thumb->save( $cash_thumb_filename );
					$view_thumb_url = MEDIAFROMFTP_PLUGIN_TMP_URL.'/'.$cash_thumb_key.'.'.$ext;
				} else {
					$view_thumb_url = MEDIAFROMFTP_PLUGIN_SITE_URL. WPINC . '/images/media/default.png';
				}
			} else {
				if ( file_exists( $cash_thumb_filename )) {
					$view_thumb_url = MEDIAFROMFTP_PLUGIN_TMP_URL.'/'.$cash_thumb_key.'.'.$ext;
				} else {
					$view_thumb_url = MEDIAFROMFTP_PLUGIN_SITE_URL. WPINC . '/images/media/default.png';
				}
			}
			set_transient( $cash_thumb_key, $view_thumb_url, DAY_IN_SECONDS);
		} else {
			$view_thumb_url = $value_cash;
			set_transient( $cash_thumb_key, $value_cash, DAY_IN_SECONDS);
		}

		return $view_thumb_url;

	}

	/* ==================================================
	 * @param	string	$ext
	 * @param	string	$new_url_attach
	 * @return	none
	 * @since	2.36
	 */
	function delete_cash($ext, $new_url_attach){

		if ( wp_ext2type($ext) === 'image' ){
			$del_cash_thumb_key = md5($new_url_attach);
			$del_cash_thumb_filename = MEDIAFROMFTP_PLUGIN_TMP_DIR.'/'.$del_cash_thumb_key.'.'.$ext;
			$value_del_cash = get_transient( $del_cash_thumb_key );
			if ( $value_del_cash <> FALSE ) {
				delete_transient( $del_cash_thumb_key );
				if ( file_exists( $del_cash_thumb_filename )) {
					unlink( $del_cash_thumb_filename );
				}
			}
		}

	}

	/* ==================================================
	 * @param	none
	 * @return	int		$del_cash_count(int)
	 * @since	7.5
	 */
	function delete_all_cash(){

		global $wpdb;
		$search_transients = MEDIAFROMFTP_PLUGIN_TMP_URL;
		$del_transients = $wpdb->get_results("
						SELECT	option_value
						FROM	$wpdb->options
						WHERE	option_value LIKE '%%$search_transients%%'
						");

		$del_cash_count = 0;
		foreach ( $del_transients as $del_transient ) {
			$delfile = pathinfo($del_transient->option_value);
			$del_cash_thumb_key = $delfile['filename'];
			$value_del_cash = get_transient( $del_cash_thumb_key );
			if ( $value_del_cash <> FALSE ) {
				delete_transient( $del_cash_thumb_key );
				++$del_cash_count;
			}
		}

		$del_cash_thumb_filename = MEDIAFROMFTP_PLUGIN_TMP_DIR.'/*.*';
		foreach ( glob($del_cash_thumb_filename) as $val ) {
			unlink($val);
			++$del_cash_count;
		}

		return $del_cash_count;

	}

	/* ==================================================
	 * @param	string	$file
	 * @param	string	$dateset
	 * @return	string	$date
	 * @since	2.36
	 */
	function get_date_check($file, $dateset){

		$date = get_date_from_gmt(date("Y-m-d H:i:s", filemtime($file)));

		if ( $dateset === 'exif' ) {
			// for wp_read_image_metadata
			include_once( ABSPATH . 'wp-admin/includes/image.php' );
			$exifdata = wp_read_image_metadata( $file );

			if ( $exifdata ) {
				$exif_ux_time = $exifdata['created_timestamp'];
				if ( !empty($exif_ux_time) ) {
					$date = date_i18n( "Y-m-d H:i:s", $exif_ux_time, FALSE );
				}
			}
		}

		$date = substr( $date , 0 , strlen($date)-3 );

		return $date;

	}

	/* ==================================================
	 * @param	string	$file
	 * @param	array	$attachments
	 * @param	string	$character_code
	 * @param	bool	$thumb_deep_search
	 * @return	array	$new_file(bool), $ext(string), $new_url(string)
	 * @since	2.36
	 */
	function input_url($file, $attachments, $character_code, $thumb_deep_search){

		$ext = NULL;
		$new_url = NULL;

		if ( is_dir($file) ) { // dirctory
			$new_file = FALSE;
		} else {
			$exts = explode('.', wp_basename($file));
			$ext = end($exts);
			$suffix_file = '.'.$ext;
			$file = wp_normalize_path($file);
			$upload_path = wp_normalize_path(MEDIAFROMFTP_PLUGIN_UPLOAD_DIR);
			$new_url = MEDIAFROMFTP_PLUGIN_UPLOAD_URL.str_replace($upload_path, '', $file);
			$new_titles = explode('/', $new_url);
			$new_title = str_replace($suffix_file, '', end($new_titles));
			$new_title_md5 = md5($new_title);
			$new_url_md5 = str_replace($new_title.$suffix_file, '', $new_url).$new_title_md5.$suffix_file;
			$new_file = TRUE;
			$new_url = $this->mb_utf8($new_url, $character_code);
			foreach ( $attachments as $attachment ){
				$attach_url = MEDIAFROMFTP_PLUGIN_UPLOAD_URL.'/'.get_post_meta( $attachment->ID, '_wp_attached_file', true );
				if ( $attach_url === $new_url || $attach_url === $new_url_md5 ) {
					$new_file = FALSE;
				} else {
					if ($thumb_deep_search) {
						$exts_attach_url = explode('.', wp_basename($attach_url));
						$delete_ext = '.'.end($exts_attach_url);
						$attach_url_base = rtrim($attach_url, $delete_ext);
						if ( strstr($new_url, $attach_url_base) || strstr($new_url_md5, $attach_url_base) ) {
							$thumb_pattern = '-[0-9]+x[0-9]+\.';
							if ( preg_match("/".$thumb_pattern."/", $new_url) || preg_match("/".$thumb_pattern."/", $new_url_md5) ) {
								$new_file = FALSE;
							}
						}
					}
				}
			}
		}

		return array($new_file, $ext, $new_url);

	}

	/* ==================================================
	 * @param	string	$ext
	 * @param	string	$file
	 * @param	string	$new_url
	 * @param	string	$postcount
	 * @param	array	$mediafromftp_settings
	 * @return	string	$input_html
	 * @since	9.30
	 */
	function input_html($ext, $file, $new_url, $postcount, $mediafromftp_settings){

		$input_html = NULL;

		if ($mediafromftp_settings['search_display_metadata']){
			$file_size = size_format(filesize($file));
			$mimetype = $ext.'('.$this->mime_type($ext).')';
			if ( wp_ext2type($ext) === 'image' ){
				$view_thumb_url = $this->create_cash($ext, $file, $new_url);
			} else if ( wp_ext2type($ext) === 'audio' ) {
				$view_thumb_url = MEDIAFROMFTP_PLUGIN_SITE_URL. WPINC . '/images/media/audio.png';
				$metadata_audio = wp_read_audio_metadata( $file );
				$file_size = size_format($metadata_audio['filesize']);
				$mimetype = $metadata_audio['fileformat'].'('.$metadata_audio['mime_type'].')';
				$length = $metadata_audio['length_formatted'];
			} else if ( wp_ext2type($ext) === 'video' ) {
				$view_thumb_url = MEDIAFROMFTP_PLUGIN_SITE_URL. WPINC . '/images/media/video.png';
				$metadata_video = wp_read_video_metadata( $file );
				$file_size = size_format($metadata_video['filesize']);
				$mimetype = $metadata_video['fileformat'].'('.$metadata_video['mime_type'].')';
				$length = $metadata_video['length_formatted'];
			} else {
				$filetype2 = wp_ext2type($ext);
				if ( empty($filetype2) ) { $filetype2 = 'default'; }
				$view_thumb_url = MEDIAFROMFTP_PLUGIN_SITE_URL. WPINC . '/images/media/'.$filetype2.'.png';
			}
			$input_html .= '<img width="40" height="40" src="'.$view_thumb_url.'" style="float: left; margin: 5px;">';
		}

		$input_html .= '<div style="overflow: hidden;">';
		$input_html .= '<div><a href="'.$new_url.'" target="_blank" style="text-decoration: none; word-break: break-all;">'.$new_url.'</a></div>';

		if ($mediafromftp_settings['search_display_metadata']){
			$input_html .= '<div>'.__('File type:').' '.$mimetype.'</div>';
			$input_html .= '<div>'.__('File size:').' '.$file_size.'</div>';
			if ( wp_ext2type($ext) === 'audio' || wp_ext2type($ext) === 'video' ) {
				$input_html .= '<div>'.__('Length:').' '.$length.'</div>';
			}
		}

		$date = $this->get_date_check($file, $mediafromftp_settings['dateset']);
		if ( $mediafromftp_settings['dateset'] === 'new' ) {
			$input_html .= '<input type="hidden" id="datetimepicker-mediafromftp'.$postcount.'" name="new_url_attaches['.$postcount.'][datetime]" value="'.$date.'" form="mediafromftp_ajax_update" >';
		} else {
			$input_html .= '<div style="float: left; margin: 5px 5px 0px 0px;">'.__('Edit date and time').'</div>';
			$input_html .= '<input type="text" id="datetimepicker-mediafromftp'.$postcount.'" name="new_url_attaches['.$postcount.'][datetime]" value="'.$date.'" form="mediafromftp_ajax_update" style="width: 160px;">';
		}
		$input_html .= '</div></div>';

		return $input_html;

	}

	/* ==================================================
	 * @param	array	$mediafromftp_settings
	 * @return	string	$form_html
	 * @since	9.50
	 */
	function form_html($mediafromftp_settings) {

		$scriptname = admin_url('admin.php?page=mediafromftp-search-register');

		$searchtext = NULL;
		if ( !empty($_POST['searchtext']) ) {
			$searchtext = $_POST['searchtext'];
		} else if ( !empty($_GET['searchtext']) ) {
			$searchtext = $_GET['searchtext'];
		}

		$pagemax = $mediafromftp_settings['pagemax'];
		$searchdir = $mediafromftp_settings['searchdir'];
		$ext2typefilter = $mediafromftp_settings['ext2typefilter'];
		$extfilter = $mediafromftp_settings['extfilter'];

		$dirs = $this->scan_dir(MEDIAFROMFTP_PLUGIN_UPLOAD_DIR);
		$linkselectbox = NULL;
		$wordpress_path = wp_normalize_path(ABSPATH);
		foreach ($dirs as $linkdir) {
			if ( strstr($linkdir, $wordpress_path ) ) {
				$linkdirenc = $this->mb_utf8(str_replace($wordpress_path, '', $linkdir), $mediafromftp_settings['character_code']);
			} else {
				$linkdirenc = MEDIAFROMFTP_PLUGIN_UPLOAD_PATH.$this->mb_utf8(str_replace(MEDIAFROMFTP_PLUGIN_UPLOAD_DIR, "", $linkdir), $mediafromftp_settings['character_code']);
			}
			if( $searchdir === $linkdirenc ){
				$linkdirs = '<option value="'.urlencode($linkdirenc).'" selected>'.$linkdirenc.'</option>';
			}else{
				$linkdirs = '<option value="'.urlencode($linkdirenc).'">'.$linkdirenc.'</option>';
			}
			$linkselectbox = $linkselectbox.$linkdirs;
		}
		if( $searchdir ===  MEDIAFROMFTP_PLUGIN_UPLOAD_PATH ){
			$linkdirs = '<option value="'.urlencode(MEDIAFROMFTP_PLUGIN_UPLOAD_PATH).'" selected>'.MEDIAFROMFTP_PLUGIN_UPLOAD_PATH.'</option>';
		}else{
			$linkdirs = '<option value="'.urlencode(MEDIAFROMFTP_PLUGIN_UPLOAD_PATH).'">'.MEDIAFROMFTP_PLUGIN_UPLOAD_PATH.'</option>';
		}
		$linkselectbox = $linkselectbox.$linkdirs;
		?>
		<div class="wp-filter" style="margin: 0px;"> <!-- wp-admin/css/list-tables.css -->
		<form method="post" action="<?php echo $scriptname; ?>">
			<?php wp_nonce_field('mff_search', 'media_from_ftp_search'); ?>
			<div style="font-size: small; font-weight: bold;"><code><?php echo $wordpress_path; ?></code></div>
				<select name="searchdir" style="width: 250px">
				<?php echo $linkselectbox; ?>
				</select>
				<?php submit_button( __('Search'), 'large', '', FALSE ); ?>
				<span style="margin-right: 1em;"></span>
				<select name="ext2type" style="width: 110px;">
				<option value="all" <?php if ($ext2typefilter === 'all') echo 'selected';?>><?php echo esc_attr( __('All types', 'media-from-ftp') ); ?></option> 
				<option value="image" <?php if ($ext2typefilter === 'image') echo 'selected';?>>image</option>
				<option value="audio" <?php if ($ext2typefilter === 'audio') echo 'selected';?>>audio</option>
				<option value="video" <?php if ($ext2typefilter === 'video') echo 'selected';?>>video</option>
				<option value="document" <?php if ($ext2typefilter === 'document') echo 'selected';?>>document</option>
				<option value="spreadsheet" <?php if ($ext2typefilter === 'spreadsheet') echo 'selected';?>>spreadsheet</option>
				<option value="interactive" <?php if ($ext2typefilter === 'interactive') echo 'selected';?>>interactive</option>
				<option value="text" <?php if ($ext2typefilter === 'text') echo 'selected';?>>text</option>
				<option value="archive" <?php if ($ext2typefilter === 'archive') echo 'selected';?>>archive</option>
				<option value="code" <?php if ($ext2typefilter === 'code') echo 'selected';?>>code</option>
				</select>
				<select name="extension" style="width: 120px;">
				<option value="all" <?php if ($extfilter === 'all') echo 'selected';?>><?php echo esc_attr( __('All extensions', 'media-from-ftp') ); ?></option>
				<?php
				$extensions = $this->scan_extensions($ext2typefilter);
				foreach ($extensions as $extselect) {
					?>
					<option value="<?php echo $extselect; ?>" <?php if ($extfilter === $extselect) echo 'selected';?>><?php echo $extselect; ?></option>
					<?php
				}
				?>
				</select>
				<?php
				if ( empty($searchtext) ) {
					?>
					<input name="searchtext" type="text" value="" placeholder="<?php echo __('Search'); ?>">
					<?php
				} else {
					?>
					<input name="searchtext" type="text" value="" placeholder="<?php echo $searchtext; ?>">
					<?php
				}
				submit_button( __('Filter'), 'large', '', FALSE );
				?>
		</form>
		</div>
		<?php

	}

	/* ==================================================
	 * @param	string	$ext
	 * @param	string	$new_url_attach
	 * @param	string	$new_url_datetime
	 * @param	string	$dateset
	 * @param	bool	$yearmonth_folders
	 * @param	string	$character_code
	 * @param	string	$cron_user
	 * @return	array	$attach_id(int), $new_attach_title(string), $new_url_attach(string), $metadata(array)
	 * @since	2.36
	 */
	function regist($ext, $new_url_attach, $new_url_datetime, $dateset, $yearmonth_folders, $character_code, $cron_user){

		// Rename and Move file
		$suffix_attach_file = '.'.$ext;
		$new_attach_titlenames = explode('/', $new_url_attach);
		$new_attach_title = str_replace($suffix_attach_file, '', end($new_attach_titlenames));
		// for utf8mb4 charcter
		$new_attach_title = $this->utf8mb4_html_numeric_encode($new_attach_title, $character_code);

		$path_parts = pathinfo($new_url_attach);
		$urlpath_dir = wp_make_link_relative($path_parts['dirname']);

		$relation_path_true = strstr(MEDIAFROMFTP_PLUGIN_UPLOAD_PATH, '../');
		if ( !$relation_path_true ) {
			$plus_path = ltrim(str_replace(MEDIAFROMFTP_PLUGIN_UPLOAD_PATH, '', strstr($urlpath_dir, MEDIAFROMFTP_PLUGIN_UPLOAD_PATH)), '/');
		} else {
			$plus_path_tmp = str_replace($urlpath_dir, '', str_replace('../', '', MEDIAFROMFTP_PLUGIN_UPLOAD_PATH).'/');
			$plus_path =  ltrim(str_replace($plus_path_tmp, '', $urlpath_dir), '/');
		}
		if ( !empty($plus_path) ) {
			$plus_path = trailingslashit($plus_path);
		}
		$filename = $plus_path.wp_basename( $new_url_attach );

		$err_copy = TRUE;
		$copy_file_org1 = NULL;
		$copy_file_org2 = NULL;
		$copy_file_org3 = NULL;
		$copy_file_new1 = NULL;
		$copy_file_new2 = NULL;
		$copy_file_new3 = NULL;
		$postdategmt = date_i18n( "Y-m-d H:i:s", FALSE, TRUE );
		if ( $dateset === 'server' || $dateset === 'exif' ) {
			$postdategmt = get_gmt_from_date($new_url_datetime.':00');
		}
		if ( strpos( $filename ,'/' ) === FALSE ) {
			$currentdir = '';
			$currentfile = $filename;
		} else {
			$currentfiles = explode('/', $filename);
			$currentfile = end($currentfiles);
			$currentdir = str_replace($currentfile, '', $filename);
		}
		if ( strpos($currentfile, ' ' ) ) {
			$oldfilename = $filename;
			$currentfile = str_replace(' ', '-', $currentfile);
			$filename = $currentdir.$currentfile;
			$new_url_attach = MEDIAFROMFTP_PLUGIN_UPLOAD_URL.'/'.$filename;
			$copy_file_org1 = $this->mb_encode_multibyte(MEDIAFROMFTP_PLUGIN_UPLOAD_DIR.'/'.$oldfilename, $character_code);
			$copy_file_new1 = $this->mb_encode_multibyte(MEDIAFROMFTP_PLUGIN_UPLOAD_DIR.'/'.$filename, $character_code);
			$err_copy = @copy( $copy_file_org1, $copy_file_new1 );
			if ( !$err_copy ) {
				return array(-1, $this->mb_utf8($copy_file_org1, $character_code), $this->mb_utf8(MEDIAFROMFTP_PLUGIN_UPLOAD_DIR.'/'.$plus_path, $character_code), NULL);
			}
		}
		if ( function_exists('mb_check_encoding') ) {
			if ( !mb_check_encoding($new_url_attach, 'ASCII') ) {
				if ( strpos( $filename ,'/' ) === FALSE ) {
					$currentdir = '';
					$currentfile = str_replace($suffix_attach_file, '', $filename);
				} else {
					$currentfiles = explode('/', $filename);
					$currentfile = end($currentfiles);
					$currentdir = str_replace($currentfile, '', $filename);
					$currentfile = str_replace($suffix_attach_file, '', $currentfile);
				}

				$oldfilename = $currentdir.$currentfile.$suffix_attach_file;
				$filename = $currentdir.md5($currentfile).$suffix_attach_file;
				$new_url_attach = MEDIAFROMFTP_PLUGIN_UPLOAD_URL.'/'.$filename;
				$copy_file_org2 = $this->mb_encode_multibyte(MEDIAFROMFTP_PLUGIN_UPLOAD_DIR.'/'.$oldfilename, $character_code);
				$copy_file_new2 = $this->mb_encode_multibyte(MEDIAFROMFTP_PLUGIN_UPLOAD_DIR.'/'.$filename, $character_code);
				$err_copy = @copy( $copy_file_org2, $copy_file_new2 );
				if ( !$err_copy ) {
					if (!empty($copy_file_new1)) {
						$copy_file_org2 = $copy_file_org1;
						unlink( $copy_file_new1 );
					}
					return array(-1, $this->mb_utf8($copy_file_org2, $character_code), $this->mb_utf8(MEDIAFROMFTP_PLUGIN_UPLOAD_DIR.'/'.$plus_path, $character_code), NULL);
				}
			}
		}

		// Move YearMonth Folders
		if ( $yearmonth_folders == 1 ) {
			$y = substr( $postdategmt, 0, 4 );
			$m = substr( $postdategmt, 5, 2 );
			$subdir = "/$y/$m";
			$filename_base = wp_basename($filename);
			if ( MEDIAFROMFTP_PLUGIN_UPLOAD_DIR.'/'.$filename <> MEDIAFROMFTP_PLUGIN_UPLOAD_DIR.$subdir.'/'.$filename_base ) {
				if ( !file_exists($this->mb_encode_multibyte(MEDIAFROMFTP_PLUGIN_UPLOAD_DIR.$subdir, $character_code)) ) {
					wp_mkdir_p($this->mb_encode_multibyte(MEDIAFROMFTP_PLUGIN_UPLOAD_DIR.$subdir, $character_code));
				}
				if ( file_exists($this->mb_encode_multibyte(MEDIAFROMFTP_PLUGIN_UPLOAD_DIR.$subdir.'/'.$filename_base, $character_code)) ) {
					$filename_base = wp_basename($filename, $suffix_attach_file).date_i18n( "dHis", FALSE, FALSE ).$suffix_attach_file;
				}
				$copy_file_org3 = $this->mb_encode_multibyte(MEDIAFROMFTP_PLUGIN_UPLOAD_DIR.'/'.$filename, $character_code);
				$copy_file_new3 = $this->mb_encode_multibyte(MEDIAFROMFTP_PLUGIN_UPLOAD_DIR.$subdir.'/'.$filename_base, $character_code);
				$err_copy = @copy( $copy_file_org3, $copy_file_new3 );
				if ( !$err_copy ) {
					if (!empty($copy_file_new1)) {
						$copy_file_org3 = $copy_file_org1;
						unlink( $copy_file_new1 );
					} else if (!empty($copy_file_new2)) {
						$copy_file_org3 = $copy_file_org2;
						unlink( $copy_file_new2 );
					}
					return array(-1, $this->mb_utf8($copy_file_org3, $character_code), $this->mb_utf8(MEDIAFROMFTP_PLUGIN_UPLOAD_DIR.$subdir, $character_code), NULL);
				}
				$filename = ltrim($subdir, '/').'/'.$filename_base;
				$new_url_attach = MEDIAFROMFTP_PLUGIN_UPLOAD_URL.'/'.$filename;
			}
		}

		$filename = $this->mb_utf8($filename, $character_code);
		$new_url_attach = $this->mb_utf8($new_url_attach, $character_code);

		// File Regist
		$newfile_post = array(
			'post_title' => $new_attach_title,
			'post_content' => '',
			'post_author' => $cron_user,
			'guid' => $new_url_attach,
			'post_status' => 'inherit', 
			'post_type' => 'attachment',
			'post_mime_type' => $this->mime_type($suffix_attach_file)
			);
		$attach_id = wp_insert_attachment( $newfile_post, $filename );

		if ( $attach_id == 0 ) { // error
			if (!empty($copy_file_new1)) { unlink( $copy_file_new1 ); }
			if (!empty($copy_file_new2)) { unlink( $copy_file_new2 ); }
			if (!empty($copy_file_new3)) { unlink( $copy_file_new3 ); }
			return array(-2, $new_attach_title, $new_url_attach, NULL);
		} else {
			if (!empty($copy_file_org1)) { unlink( $copy_file_org1 ); }
			if (!empty($copy_file_org2)) { unlink( $copy_file_org2 ); }
			if (!empty($copy_file_org3)) { unlink( $copy_file_org3 ); }
		}

		// Date Time Regist
		if ( $dateset <> 'new' ) {
			$postdate = get_date_from_gmt($postdategmt);
			$up_post = array(
							'ID' => $attach_id,
							'post_date' => $postdate,
							'post_date_gmt' => $postdategmt,
							'post_modified' => $postdate,
							'post_modified_gmt' => $postdategmt
						);
			wp_update_post( $up_post );
		}

		// for wp_read_audio_metadata and wp_read_video_metadata
		include_once( ABSPATH . 'wp-admin/includes/media.php' );
		// for wp_generate_attachment_metadata
		include_once( ABSPATH . 'wp-admin/includes/image.php' );

		// Meta data Regist
		$fullpath_filename = $this->mb_encode_multibyte(get_attached_file($attach_id), $character_code);
		if ( wp_ext2type($ext) === 'image' ){
			$metadata = wp_generate_attachment_metadata( $attach_id, $fullpath_filename );
		}else if ( wp_ext2type($ext) === 'video' ){
			$metadata = wp_read_video_metadata( $fullpath_filename );
		}else if ( wp_ext2type($ext) === 'audio' ){
			$metadata = wp_read_audio_metadata( $fullpath_filename );
		} else {
			$metadata = NULL;
		}
		if ( !empty($metadata) ) { // 'file' => UTF-8
			if ( function_exists('array_replace') ) {
				$metadata = array_replace($metadata, array('file' => $filename)); 
			} else {
				$metadata = $this->array_replace($metadata, array('file' => $filename)); 
			}
		}
		wp_update_attachment_metadata( $attach_id, $metadata );

		return array($attach_id, $new_attach_title, $new_url_attach, $metadata);

	}

	/* ==================================================
	 * @param	string	$ext
	 * @param	int		$attach_id
	 * @param	array	$metadata
	 * @param	string	$character_code
	 * @param	string	$exif_text_tag
	 * @return	array	$imagethumburls(string), $mimetype(string), $length(string), $stamptime(string), $file_size(string), $exif_text(string)
	 * @since	7.4
	 */
	function output_metadata($ext, $attach_id, $metadata, $character_code, $exif_text_tag){

		$imagethumburls = array();
		$mimetype = NULL;
		$length = NULL;
		$exif_text = NULL;
		$filetype = wp_check_filetype( $this->mb_encode_multibyte(get_attached_file($attach_id), $character_code) );
		if ( wp_ext2type($ext) === 'image' ){
			$imagethumburl_base = MEDIAFROMFTP_PLUGIN_UPLOAD_URL.'/'.rtrim($metadata['file'], wp_basename($metadata['file']));
			foreach ( $metadata as $key1 => $key2 ){
				if ( $key1 === 'sizes' ) {
					foreach ( $metadata[$key1] as $key2 => $key3 ){
						$imagethumburls[$key2] = $imagethumburl_base.$metadata['sizes'][$key2]['file'];
					}
				}
			}
			$mimetype =  $filetype['ext'].'('.$filetype['type'].')';
		}else if ( wp_ext2type($ext) === 'video'||  wp_ext2type($ext) === 'audio' ){
			$mimetype = $metadata['fileformat'].'('.$metadata['mime_type'].')';
			$length = $metadata['length_formatted'];
		} else {
			$metadata = NULL;
			$mimetype =  $filetype['ext'].'('.$filetype['type'].')';
		}

		$stamptime = get_the_time( 'Y-n-j ', $attach_id ).get_the_time( 'G:i', $attach_id );
		if ( isset( $metadata['filesize'] ) ) {
			$file_size = $metadata['filesize'];
		} else {
			$file_size = @filesize( $this->mb_encode_multibyte(get_attached_file($attach_id), $character_code) );
		}

		if ( $filetype['type'] === 'image/jpeg' || $filetype['type'] === 'image/tiff' ) {
			if ( !empty($exif_text_tag) ) {
				$exif_text = $this->exifcaption($attach_id, $metadata, $exif_text_tag);
			}
		}

		return array($imagethumburls, $mimetype, $length, $stamptime, $file_size, $exif_text);

	}

	/* ==================================================
	 * @param	string	$ext
	 * @param	string	$file_size
	 * @param	int		$attach_id
	 * @param	string	$new_attach_title
	 * @param	string	$new_url_attach
	 * @param	array	$imagethumburls
	 * @param	string	$mimetype
	 * @param	string	$length
	 * @param	string	$stamptime
	 * @param	string	$file_size
	 * @param	string	$exif_text
	 * @param	array	$image_attr_thumbnail
	 * @param	array	$mediafromftp_settings
	 * @return	string	$output_html
	 * @since	9.30
	 */
	function output_html_and_log($ext, $attach_id, $new_attach_title, $new_url_attach, $imagethumburls, $mimetype, $length, $stamptime, $file_size, $exif_text, $image_attr_thumbnail, $mediafromftp_settings){

		$thumbnails = array();

		$output_html = '<div style="border-bottom: 1px solid; padding-top: 5px; padding-bottom: 5px;">';
		$output_html .= '<img width="40" height="40" src="'.$image_attr_thumbnail[0].'" style="float: left; margin: 5px;">';
		$output_html .= '<div style="overflow: hidden;">';
		$output_html .= '<div>ID: '.$attach_id.'</div>';
		$output_html .= '<div>'.__('Title').': '.$new_attach_title.'</div>';
		$output_html .= '<div>'.__('Permalink:').' <a href="'.get_attachment_link($attach_id).'" target="_blank" style="text-decoration: none; word-break: break-all;">'.get_attachment_link($attach_id).'</a></div>';
		$output_html .= '<div>URL: <a href="'.$new_url_attach.'" target="_blank" style="text-decoration: none; word-break: break-all;">'.$new_url_attach.'</a></div>';
		$new_url_attachs = explode('/', $new_url_attach);
		$output_html .= '<div>'.__('File name:').' '.end($new_url_attachs).'</div>';

		$output_html .= '<div>'.__('Date/Time').': '.$stamptime.'</div>';
		if ( !$file_size ) {
			$file_size = '<font color="red">'.__('Could not retrieve.', 'media-from-ftp').'</font>';
		} else {
			$file_size = size_format($file_size);
		}
		$output_html .= '<div>'.__('File type:').' '.$mimetype.'</div>';
		$output_html .= '<div>'.__('File size:').' '.$file_size.'</div>';
		if ( wp_ext2type($ext) === 'image' ) {
			$output_html .= '<div>'.__('Images').': ';
			$thumb_count = 0;
			foreach ( $imagethumburls as $thumbsize => $imagethumburl ) {
				$output_html .= '[<a href="'.$imagethumburl.'" target="_blank" style="text-decoration: none; word-break: break-all;">'.$thumbsize.'</a>]';
				++$thumb_count;
				$thumbnails[$thumb_count] = $imagethumburl;
			}
			$output_html .= '</div>';
			if ( !empty($exif_text) ) {
				$output_html .= '<div>'.__('Caption').'[Exif]: '.$exif_text.'</div>';
			}
		} else {
			if ( wp_ext2type($ext) === 'video' || wp_ext2type($ext) === 'audio' ) {
				$output_html .= '<div>'.__('Length:').' '.$length.'</div>';
			}
		}
		$output_html .= '</div></div>';

		if ( $mediafromftp_settings['log'] ) {
			global $wpdb;
			$user = wp_get_current_user();
			$thumbnail = json_encode($thumbnails);
			$thumbnail = str_replace('\\', '', $thumbnail);
			// Log
			$log_arr = array(
				'id' => $attach_id,
				'user' => $user->display_name,
				'title' => $new_attach_title,
				'permalink' => get_attachment_link($attach_id),
				'url' => $new_url_attach,
				'filename' => end($new_url_attachs),
				'time' => $stamptime,
				'filetype' => $mimetype,
				'filesize' => $file_size,
				'exif' => $exif_text,
				'length' => $length,
				'thumbnail' => $thumbnail
				);
			$table_name = $wpdb->prefix.'mediafromftp_log';
			$wpdb->insert( $table_name, $log_arr);
			$wpdb->show_errors();
		}

		return $output_html;

	}

	/* ==================================================
	 * @param	string	$base
	 * @param	string	$relationalpath
	 * @return	string	realurl
	 * @since	7.7
	 */
	function realurl( $base, $relationalpath ){
	     $parse = array(
	          "scheme" => null,
	          "user" => null,
	          "pass" => null,
	          "host" => null,
	          "port" => null,
	          "query" => null,
	          "fragment" => null
	     );
	     $parse = parse_url( $base );

	     if( strpos($parse["path"], "/", (strlen($parse["path"])-1)) !== false ){
	          $parse["path"] .= ".";
	     }

	     if( preg_match("#^https?://#", $relationalpath) ){
	          return $relationalpath;
	     }else if( preg_match("#^/.*$#", $relationalpath) ){
	          return $parse["scheme"] . "://" . $parse["host"] . $relationalpath;
	     }else{
	          $basePath = explode("/", dirname($parse["path"]));
	          $relPath = explode("/", $relationalpath);
	          foreach( $relPath as $relDirName ){
	               if( $relDirName == "." ){
	                    array_shift( $basePath );
	                    array_unshift( $basePath, "" );
	               }else if( $relDirName == ".." ){
	                    array_pop( $basePath );
	                    if( count($basePath) == 0 ){
	                         $basePath = array("");
	                    }
	               }else{
	                    array_push($basePath, $relDirName);
	               }
	          }
	          $path = implode("/", $basePath);
	          return $parse["scheme"] . "://" . $parse["host"] . $path;
	     }

	}

	/* ==================================================
	 * @param	none
	 * @return	array	$upload_dir, $upload_url, $upload_path
	 * @since	7.8
	 */
	function upload_dir_url_path(){

		$wp_uploads = wp_upload_dir();

		$relation_path_true = strpos($wp_uploads['baseurl'], '../');
		if ( $relation_path_true > 0 ) {
			$relationalpath = substr($wp_uploads['baseurl'], $relation_path_true);
			$basepath = substr($wp_uploads['baseurl'], 0, $relation_path_true);
			$upload_url = $this->realurl($basepath, $relationalpath);
			$upload_dir = wp_normalize_path(realpath($wp_uploads['basedir']));
		} else {
			$upload_url = $wp_uploads['baseurl'];
			$upload_dir = wp_normalize_path($wp_uploads['basedir']);
		}

		if(is_ssl()){
			$upload_url = str_replace('http:', 'https:', $upload_url);
		}

		if ( $relation_path_true > 0 ) {
			$upload_path = $relationalpath;
		} else {
			$wordpress_path = wp_normalize_path(ABSPATH);
			$upload_path = str_replace($wordpress_path, '', $upload_dir);
		}

		$upload_dir = untrailingslashit($upload_dir);
		$upload_url = untrailingslashit($upload_url);
		$upload_path = untrailingslashit($upload_path);

		return array($upload_dir, $upload_url, $upload_path);

	}

	/* ==================================================
	 * @param	none
	 * @return	$siteurl
	 * @since	8.5
	 */
	function siteurl(){
		if ( is_multisite() ) {
			global $blog_id;
			$siteurl = trailingslashit(get_blog_details($blog_id)->siteurl);
		} else {
			$siteurl = site_url('/');
		}
		return $siteurl;
	}

	/* ==================================================
	 * @param	string	$ext2typefilter
	 * @return	array	$extensions
	 * @since	8.2
	 */
	function scan_extensions($ext2typefilter){

		$extensions = array();
		global $user_ID;
		$mimes = get_allowed_mime_types($user_ID);

		foreach ($mimes as $extselect => $mime) {
			if( strpos( $extselect, '|' ) ){
				$extselects = explode('|',$extselect);
				foreach ( $extselects as $extselect2 ) {
					if ( $ext2typefilter === 'all' || $ext2typefilter === wp_ext2type($extselect2) ) {
						$extensions[] = $extselect2;
					}
				}
			} else {
				if ( $ext2typefilter === 'all' || $ext2typefilter === wp_ext2type($extselect) ) {
					$extensions[] = $extselect;
				}
			}
		}

		asort($extensions);
		return $extensions;

	}

	/* ==================================================
	 * @param	int		$attach_id
	 * @param	array	$metadata
	 * @param	string	$exif_text_tag
	 * @return	string	$exif_text
	 * @since	8.9
	 */
	function exifcaption($attach_id, $metadata, $exif_text_tag){

		$exifdatas = array();
		if ( $metadata['image_meta']['title'] ) {
			$exifdatas['title'] = $metadata['image_meta']['title'];
		}
		if ( $metadata['image_meta']['credit'] ) {
			$exifdatas['credit'] = $metadata['image_meta']['credit'];
		}
		if ( $metadata['image_meta']['camera'] ) {
			$exifdatas['camera'] = $metadata['image_meta']['camera'];
		}
		if ( $metadata['image_meta']['caption'] ) {
			$exifdatas['caption'] = $metadata['image_meta']['caption'];
		}
		$exif_ux_time = $metadata['image_meta']['created_timestamp'];
		if ( !empty($exif_ux_time) ) {
			$exifdatas['created_timestamp'] = date_i18n( "Y-m-d H:i:s", $exif_ux_time, FALSE );
		}
		if ( $metadata['image_meta']['copyright'] ) {
			$exifdatas['copyright'] = $metadata['image_meta']['copyright'];
		}
		if ( $metadata['image_meta']['aperture'] ) {
			$exifdatas['aperture'] = 'f/'.$metadata['image_meta']['aperture'];
		}
		if ( $metadata['image_meta']['shutter_speed'] ) {
			if ( $metadata['image_meta']['shutter_speed'] < 1 ) {
				$shutter = round( 1 / $metadata['image_meta']['shutter_speed'] );
				$exifdatas['shutter_speed'] = '1/'.$shutter.'sec';
			} else {
				$exifdatas['shutter_speed'] = $metadata['image_meta']['shutter_speed'].'sec';
			}
		}
		if ( $metadata['image_meta']['iso'] ) {
			$exifdatas['iso'] = 'ISO-'.$metadata['image_meta']['iso'];
		}
		if ( $metadata['image_meta']['focal_length'] ) {
			$exifdatas['focal_length'] = $metadata['image_meta']['focal_length'].'mm';
		}

		$exif_text = NULL;
		if ( $exifdatas ) {
			$exif_text = $exif_text_tag;
			foreach($exifdatas as $item => $exif) {
				$exif_text = str_replace('%'.$item.'%', $exif, $exif_text);
			}
			preg_match_all('/%(.*?)%/', $exif_text, $exif_text_per_match);
			foreach($exif_text_per_match as $key1) {
				foreach($key1 as $key2) {
					$exif_text = str_replace('%'.$key2.'%', '', $exif_text);
				}
			}
		}

		if ( !empty($exif_text) ) {
			// Change DB Attachement post
			global $wpdb;
			$update_array = array(
							'post_excerpt'=> $exif_text
						);
			$id_array= array('ID'=> $attach_id);
			$wpdb->update( $wpdb->posts, $update_array, $id_array, array('%s'), array('%d') );
			unset($update_array, $id_array);
		}

		return $exif_text;

	}

	/* ==================================================
	 * @param	string	$str
	 * @param	string	$character_code
	 * @return	string	$ret
	 * @since	9.02
	 */
	function utf8mb4_html_numeric_encode($str, $character_code) {

		if ( function_exists('mb_language') && $character_code <> 'none' ) {
			$length = mb_strlen($str, 'UTF-8');
			$ret = '';

			for ($i = 0; $i < $length; ++$i) {
				$buf = mb_substr($str, $i, 1, 'UTF-8');

				if (mb_strlen($buf, '8bit') === 4) {
					$buf = mb_encode_numericentity($buf, array(0x10000, 0x10FFFF, 0, 0xFFFFFF), 'UTF-8');
				}

				$ret .= $buf;
			}
		} else {
			$ret = $str;
		}

		return $ret;

	}

	/* ==================================================
	 * @param	string	$character_code
	 * @return	string	none
	 * @since	9.05
	 */
	function mb_initialize($character_code) {

		if ( function_exists('mb_language') && $character_code <> 'none' ) {
			if( get_locale() === 'ja' ) {
				mb_language('Japanese');
			} else if( get_locale() === 'en_US' ) {
				mb_language('English');
			} else {
				mb_language('uni');
			}
		}

	}

	/* ==================================================
	 * @param	string	$str
	 * @param	string	$character_code
	 * @return	string	$str
	 * @since	9.10
	 */
	function mb_encode_multibyte($str, $character_code) {

		if ( function_exists('mb_language') && $character_code <> 'none' ) {
			$str = mb_convert_encoding($str, $character_code, "auto");
		}

		return $str;

	}

	/* ==================================================
	 * @param	string	$str
	 * @param	string	$character_code
	 * @return	string	$str
	 * @since	9.05
	 */
	function mb_utf8($str, $character_code) {

		if ( function_exists('mb_convert_encoding') && $character_code <> 'none' ) {
			$str = mb_convert_encoding($str, "UTF-8", "auto");
		}

		return $str;

	}

	/* ==================================================
	 * for php < 5.3.0
	 * @param	array	$array
	 * @return	array	$array
	 * @since	9.11
	 */
	function array_replace() {

		$array = array();
		$n = func_num_args();

		while ( $n-- >0 ) {
			$array+=func_get_arg($n);
		}
		return $array;
	}


	/* ==================================================
	* for Media Library Import
	* @param	string	$filename
	* @return	array	$authors
	* @since	9.43
	*/
	function author_select($filename) {

		$scriptname = admin_url('admin.php?page=mediafromftp-import');

		$s = @file_get_contents($filename);
		$controlCode = array("\x00", "\x01", "\x02", "\x03", "\x04", "\x05", "\x06", "\x07", "\x08", "\x0b", "\x0c", "\x0e", "\x0f");
		$s = str_replace($controlCode, '', $s);
		$xml = simplexml_load_string($s);

		$authors = array();
		$namespaces = $xml->getDocNamespaces();
		foreach ( $xml->xpath('/rss/channel/wp:author') as $author_arr ) {
			$a = $author_arr->children( $namespaces['wp'] );
			$authors[] = array(
							'author_login' => (string) $a->author_login,
							'author_display_name' => (string) $a->author_display_name,
							);
		}


		$form_select = NULL;
		$count = 0;
		if ( current_user_can( 'manage_options' ) )  {
			$blogusers = get_users();
			foreach ( $authors as $key => $value) {
				++$count;
				$form_select .= '<div style="display: block; padding: 5px 10px">'.$count.'.'.__('Import author:', 'media-from-ftp').'<strong>'.$value['author_display_name'].'('.$value['author_login'].')'.'</strong></div>';
				$form_select .= '<div style="display: block; padding: 5px 30px">'.__('Assign posts to an existing user:', 'media-from-ftp').'<select name="'.$value['author_login'].'">';
				$form_select .= '<option value="-1" select>'.__('Select').'</option>';
				foreach ( $blogusers as $user ) {
					$form_select .= '<option value="'.$user->ID.'">'.$user->display_name.'('.$user->user_login.')</option>';
				}
				$form_select .= '</select></div>';
			}
			$current_user = wp_get_current_user();
			$current_user_html = '<strong>'.$current_user->display_name.'('.$current_user->user_login.')</strong>';
			$form_select .= '<div style="display: block; padding: 10px 0px">'.sprintf(__('If not selected, assign posts to %1$s.', 'media-from-ftp'), $current_user_html).'</div>';
		} else {
			$user = wp_get_current_user();
			foreach ( $authors as $key => $value) {
				++$count;
				$form_select .= '<div style="display: block; padding: 5px 10px">'.$count.'.'.__('Import author:', 'media-from-ftp').'<strong>'.$value['author_display_name'].'('.$value['author_login'].')'.'</strong></div>';
				$current_user_html = '<strong>'.$user->display_name.'('.$user->user_login.')</strong>';
				$form_select .= '<div style="display: block; padding: 5px 30px">'.sprintf(__('Assign posts to %1$s', 'media-from-ftp'), $current_user_html).'</div>';

			}
		}
		$button_value = get_submit_button( __('Apply'), 'large', 'select_author', FALSE );
		$nonce_field = wp_nonce_field('mff_select_author', 'media_from_ftp_select_author');

$author_form = <<<MEDIAFROMFTP_AUTHOR_SELECT

<!-- BEGIN: Media from FTP Media Library Import -->
<form method="post" action="$scriptname">
$nonce_field
$form_select
<div style="display: block; padding: 20px 0px">$button_value</div>
<input type="hidden" name="mediafromftp_select_author" value="1" />
<input type="hidden" name="mediafromftp_xml_file" value="$filename" />
</form>

<!-- END: Media from FTP Media Library Import -->

MEDIAFROMFTP_AUTHOR_SELECT;

		return $author_form;

	}

	/* ==================================================
	* for Media Library Import
	* @param	string	$filename
	* @param	array	$select_author
	* @return	string	$add_js
	* @since	9.40
	*/
	function make_object($filename, $select_author) {

		$s = @file_get_contents($filename);
		$controlCode = array("\x00", "\x01", "\x02", "\x03", "\x04", "\x05", "\x06", "\x07", "\x08", "\x0b", "\x0c", "\x0e", "\x0f");
		$s = str_replace($controlCode, '', $s);
		$xml = simplexml_load_string($s);
		$data = array();
		foreach ($xml->channel->item as $item) {
			$x = array();
			$x['title'] = (string)$item->title;
			$x['link'] = (string)$item->link;
			$x['pubDate'] = (string)$item->pubDate;
			$x['creator'] = (string)$item->children('dc',true)->creator;
			$x['guid'] = (string)$item->guid;
			$x['guid_atr'] = (string)$item->guid->attributes()->isPermaLink;
			$x['description'] = (string)$item->description;
			$x['content_encoded'] = (string)$item->children('content',true)->encoded;
			$x['excerpt_encoded'] = (string)$item->children('excerpt',true)->encoded;
			$x['post_id'] = (int)$item->children('wp',true)->post_id;
			$x['post_date'] = (string)$item->children('wp',true)->post_date;
			$x['post_date_gmt'] = (string)$item->children('wp',true)->post_date_gmt;
			$x['comment_status'] = (string)$item->children('wp',true)->comment_status;
			$x['ping_status'] = (string)$item->children('wp',true)->ping_status;
			$x['post_name'] = (string)$item->children('wp',true)->post_name;
			$x['status'] = (string)$item->children('wp',true)->status;
			$x['post_parent'] = (int)$item->children('wp',true)->post_parent;
			$x['menu_order'] = (int)$item->children('wp',true)->menu_order;
			$x['post_type'] = (string)$item->children('wp',true)->post_type;
			$x['post_password'] = (string)$item->children('wp',true)->post_password;
			$x['is_sticky'] = (int)$item->children('wp',true)->is_sticky;
			$x['attachment_url'] = (string)$item->children('wp',true)->attachment_url;

			$postmeta_count = count($item->children('wp',true)->postmeta);
			for ($i = 0; $i < $postmeta_count; $i++){
				$wp_postmeta_node = $item->children('wp',true)->postmeta[$i];
				$post_meta_key = (string)$wp_postmeta_node->meta_key;
				$post_meta_value = (string)$wp_postmeta_node->meta_value;
				if ( $post_meta_key === '_wp_attached_file' ) {
					$x['postmeta_wp_attached_file'] = $post_meta_key;
					$x['postmeta_wp_attached_file_value'] = $post_meta_value;
				} else if ( $post_meta_key === '_thumbnail_id' ) {
					$x['postmeta_thumbnail_id'] = $post_meta_key;
					$x['postmeta_thumbnail_id_value'] = $post_meta_value;
				} else if ( $post_meta_key === '_cover_hash' ) {
					$x['postmeta_cover_hash'] = $post_meta_key;
					$x['postmeta_cover_hash_value'] = $post_meta_value;
				} else if ( $post_meta_key === '_wp_attachment_metadata' ) {
					$x['postmeta_wp_attachment_metadata'] = $post_meta_key;
					$x['postmeta_wp_attachment_metadata_value'] = $post_meta_value;
				} else if ( $post_meta_key === '_wp_attachment_image_alt' ) {
					$x['postmeta_wp_attachment_image_alt'] = $post_meta_key;
					$x['postmeta_wp_attachment_image_alt_value'] = $post_meta_value;
				}
			}

			$data[] = $x;
		}

		$count = 0;
		$file_array = array();
		$db_array = array();
		$db_wp_attachment_metadata_array = array();
		$db_thumbnail_id_array = array();
		$db_cover_hash_array = array();
		$db_wp_attachment_image_alt_array = array();
		foreach ($data as $key => $value) {
			if ($value['post_type'] === 'attachment') {
				$file = MEDIAFROMFTP_PLUGIN_UPLOAD_DIR.'/'.$value['postmeta_wp_attached_file_value'];
				$filetype = wp_check_filetype( basename( $file ), null );

				$user = wp_get_current_user();
				$loginuser = $user->ID;
				foreach ( $select_author as $authorkey => $authorvalue ) {
					if ( $value['creator'] === $authorkey) {
						$loginuser = $authorvalue;
					}
				}

				$db_array[$count] = array(
								'ID'						=>	$value['post_id'],
								'post_author'				=>	$loginuser,
								'post_date'					=>	$value['post_date'],
								'post_date_gmt'				=>	$value['post_date_gmt'],
								'post_content'				=>	$value['content_encoded'],
								'post_title'				=>	$value['title'],
								'post_excerpt'				=>	$value['excerpt_encoded'],
								'post_status'				=>	$value['status'],
								'comment_status'			=>	$value['comment_status'],
								'ping_status'				=>	$value['ping_status'],
								'post_password'				=>	$value['post_password'],
								'post_name'					=>	$value['post_name'],
								'post_parent'				=>	$value['post_parent'],
								'guid'						=>	$value['guid'],
								'menu_order'				=>	$value['menu_order'],
								'post_type'					=>	$value['post_type'],
								'post_mime_type'			=>	$filetype['type']
							);

				$file_array[$count] = $file;

				if ( array_key_exists( "postmeta_wp_attachment_metadata_value", $value ) ) {
					$db_wp_attachment_metadata_array[$count] = $value['postmeta_wp_attachment_metadata_value'];
					if ( strrpos($value['postmeta_wp_attached_file_value'], '/') ) {
						$monthdir = '/'.substr($value['postmeta_wp_attached_file_value'], 0 , strrpos($value['postmeta_wp_attached_file_value'], '/'));
						$dir = MEDIAFROMFTP_PLUGIN_UPLOAD_DIR.$monthdir;
					}
					$thumbnails = maybe_unserialize($value['postmeta_wp_attachment_metadata_value']);
					if ( is_array($thumbnails) ) {
						foreach ( $thumbnails as $key1 => $value1 ) {
							if ( is_array($value1) ) {
								foreach ( $value1 as $key2 => $value2 ) {
									if ( is_array($value2) ) {
										foreach ( $value2 as $key3 => $value3 ) {
											if ( $key3 === 'file' ) {
												$thumbnail = $dir.'/'.$value3;
												++$count;
												$file_array[$count] = $thumbnail;
											}
										}
									}
								}
							}
						}
					}
				}
				if ( array_key_exists( "postmeta_thumbnail_id_value", $value ) ) {
					$db_thumbnail_id_array[$count] = $value['postmeta_thumbnail_id_value'];
				}
				if ( array_key_exists( "postmeta_cover_hash_value", $value ) ) {
					$db_cover_hash_array[$count] = $value['postmeta_cover_hash_value'];
				}
				if ( array_key_exists( "postmeta_wp_attachment_image_alt_value", $value ) ) {
					$db_wp_attachment_image_alt_array[$count] = $value['postmeta_wp_attachment_image_alt_value'];
				}

				++$count;
			}
		}

		$file_obj = json_encode($file_array);
		$db_array_obj = json_encode($db_array);
		$db_wp_attachment_metadata_obj = json_encode($db_wp_attachment_metadata_array);
		$db_thumbnail_id_obj = json_encode($db_thumbnail_id_array);
		$db_cover_hash_obj = json_encode($db_cover_hash_array);
		$db_wp_attachment_image_alt_obj = json_encode($db_wp_attachment_image_alt_array);

// JS
$add_js = <<<MEDIAFROMFTP

<!-- BEGIN: Media from FTP Media Library Import -->
<script type="text/javascript">
/* <![CDATA[ */
var medialibraryimport_maxcount = $count;
var medialibraryimport_file = $file_obj;
var medialibraryimport_db_array = $db_array_obj;
var medialibraryimport_db_wp_attachment_metadata = $db_wp_attachment_metadata_obj;
var medialibraryimport_db_thumbnail_id = $db_thumbnail_id_obj;
var medialibraryimport_db_cover_hash = $db_cover_hash_obj;
var medialibraryimport_db_wp_attachment_image_alt = $db_wp_attachment_image_alt_obj;
/* ]]> */
</script>
<!-- END: Media from FTP Media Library Import -->

MEDIAFROMFTP;

	return $add_js;

	}

}

?>