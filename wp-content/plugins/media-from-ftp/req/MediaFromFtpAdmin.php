<?php
/**
 * Media from FTP
 * 
 * @package    Media from FTP
 * @subpackage MediafromFTPAdmin Main & Management screen
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

class MediaFromFtpAdmin {

	/* ==================================================
	 * Add a "Settings" link to the plugins page
	 * @since	1.0
	 */
	function settings_link( $links, $file ) {
		static $this_plugin;
		if ( empty($this_plugin) ) {
			$this_plugin = MEDIAFROMFTP_PLUGIN_BASE_FILE;
		}
		if ( $file == $this_plugin ) {
			$links[] = '<a href="'.admin_url('admin.php?page=mediafromftp').'">Media from FTP</a>';
			$links[] = '<a href="'.admin_url('admin.php?page=mediafromftp-search-register').'">'.__('Search & Register', 'media-from-ftp').'</a>';
			$links[] = '<a href="'.admin_url('admin.php?page=mediafromftp-settings').'">'.__( 'Settings').'</a>';
			$links[] = '<a href="'.admin_url('admin.php?page=mediafromftp-log').'">'.__('Log', 'media-from-ftp').'</a>';
			$links[] = '<a href="'.admin_url('admin.php?page=mediafromftp-import').'">'.__('Import').'</a>';
		}
			return $links;
	}

	/* ==================================================
	 * Add page
	 * @since	1.0
	 */
	function add_pages() {
		add_menu_page(
				'Media from FTP',
				'Media from FTP',
				'upload_files',
				'mediafromftp',
				array($this, 'manage_page'),
				'dashicons-upload'
		);
		add_submenu_page(
				'mediafromftp',
				__('Search & Register', 'media-from-ftp'),
				__('Search & Register', 'media-from-ftp'),
				'upload_files',
				'mediafromftp-search-register',
				array($this, 'search_register_page')
		);
		add_submenu_page(
				'mediafromftp',
				__('Settings'),
				__('Settings'),
				'upload_files',
				'mediafromftp-settings',
				array($this, 'settings_page')
		);
		add_submenu_page(
				'mediafromftp',
				__('Log', 'media-from-ftp'),
				__('Log', 'media-from-ftp'),
				'upload_files',
				'mediafromftp-log',
				array($this, 'log_page')
		);
		add_submenu_page(
				'mediafromftp',
				__('Import'),
				__('Import'),
				'upload_files',
				'mediafromftp-import',
				array($this, 'medialibrary_import_page')
		);
	}

	/* ==================================================
	 * Show Screen Option Search & Register
	 * @since	9.52
	 */
	function search_register_show_screen_options( $status, $args ) {

		$mediafromftp_settings = get_option($this->wp_options_name());

		$return = $status;
			if ( $args->base == 'media-from-ftp_page_mediafromftp-search-register' ) {
				$return .= '<div style="display: block; padding: 5px 15px">';
				$return .= '<div class="item-mediafromftp-settings">';
				$return .= '<legend>'.__('Pagination').'</legend>';
				$return .= '<label>'.__('Number of items per page:').'</label>';
				$return .= '<input type="number" step="1" min="1" max="999" class="screen-per-page" name="mediafromftp_pagemax" maxlength="3" value="'.$mediafromftp_settings['pagemax'].'">';
				$return .= '</div>';

				$return .= '<div class="item-mediafromftp-settings">';
				$return .= '<legend>'.__('Display of search results', 'media-from-ftp').'</legend>';
				$return .= '<div style="display: block;padding:5px 5px">';
				if ($mediafromftp_settings['search_display_metadata'] == TRUE) {
					$return .= '<input type="radio" name="search_display_metadata" value="1" checked>';
				} else {
					$return .= '<input type="radio" name="search_display_metadata" value="1">';
				}
				$return .= __('Usual selection. It is user-friendly. It displays a thumbnail and metadata. It is low speed.', 'media-from-ftp');
				$return .= '</div>';
				$return .= '<div style="display: block;padding:5px 5px">';
				if ($mediafromftp_settings['search_display_metadata'] == FALSE) {
					$return .= '<input type="radio" name="search_display_metadata" value="0" checked>';
				} else {
					$return .= '<input type="radio" name="search_display_metadata" value="0">';
				}
				$return .= __('Unusual selection. Only the file name and output. It is suitable for the search of large amounts of data. It is hi speed.', 'media-from-ftp');
				$return .= '</div>';
				$return .= '</div>';

				$return .= '<div class="item-mediafromftp-settings">';
				$return .= '<legend>'.__('Exclude file', 'media-from-ftp').'</legend>';
				$return .= '<div style="display: block;padding:5px 5px">';
				$return .= '<div>'.__('Regular expression is possible.', 'media-from-ftp').'</div>';
				$return .= '<textarea id="mediafromftp_exclude" name="mediafromftp_exclude" rows="3" style="width: 100%;">'.$mediafromftp_settings['exclude'].'</textarea>';
				$return .= '</div>';
				$return .= '</div>';

				$return .= '<div class="item-mediafromftp-settings">';
				$return .= '<legend>'.__('Search method for the exclusion of the thumbnail', 'media-from-ftp').'</legend>';
				$return .= '<div style="display: block;padding:5px 5px">';
				if ($mediafromftp_settings['thumb_deep_search'] == FALSE) {
					$return .= '<input type="radio" name="mediafromftp_thumb_deep_search" value="0" checked>';
				} else {
					$return .= '<input type="radio" name="mediafromftp_thumb_deep_search" value="0">';
				}
				$return .= __('Usual selection. It is hi speed.', 'media-from-ftp');
				$return .= '</div>';
				$return .= '<div style="display: block;padding:5px 5px">';
				if ($mediafromftp_settings['thumb_deep_search'] == TRUE) {
					$return .= '<input type="radio" name="mediafromftp_thumb_deep_search" value="1" checked>';
				} else {
					$return .= '<input type="radio" name="mediafromftp_thumb_deep_search" value="1">';
				}
				$return .= __('Unusual selection. if you want to search for filename that contains such -0x0. It is low speed.', 'media-from-ftp');
				$return .= '</div>';
				$return .= '</div>';
				$return .= '<div style="display: block;padding:5px 5px">'.get_submit_button( __( 'Apply' ), 'primary', 'media-from-ftp-screen-options-apply', FALSE ).'</div>';
				$return .= '</div>';

				$return .= '<input type="hidden" name="wp_screen_options[option]" value="media_from_ftp_show_screen" />';
				$return .= '<input type="hidden" name="wp_screen_options[value]" value="2" />';
			}
			return $return;
	}

	/* ==================================================
	 * Save Screen Option Search & Register
	 * @since	9.52
	 */
	function search_register_set_screen_options($status, $option, $value) {
		if ( 'media_from_ftp_show_screen' == $option ) { 
			$this->options_updated($value);
			return $value;
		}
		return $status;
	}

	/* ==================================================
	 * Help Tab
	 * @since	9.53
	 */
	function search_register_help_tab($help, $screen_id, $screen) {

		if( $screen_id === 'media-from-ftp_page_mediafromftp-search-register' || $screen_id === 'media-from-ftp_page_mediafromftp-settings' || $screen_id === 'media-from-ftp_page_mediafromftp-log' ||  $screen_id === 'media-from-ftp_page_mediafromftp-import' ) {
			$sidebar = '<p><strong>'.__('For more information:').'</strong></p>';
			$sidebar .= '<p><a href="'.__('https://wordpress.org/plugins/media-from-ftp/faq', 'media-from-ftp').'" target="_blank">'.__('FAQ').'</a></p>';
			$sidebar .= '<p><a href="https://wordpress.org/support/plugin/media-from-ftp" target="_blank">'.__('Support Forums').'</a></p>';
			$sidebar .= '<p><a href="https://wordpress.org/support/view/plugin-reviews/media-from-ftp" target="_blank">'.__('Reviews', 'media-from-ftp').'</a></p>';
			$sidebar .= '<p><a href="https://translate.wordpress.org/projects/wp-plugins/media-from-ftp" target="_blank">'.sprintf(__('Translations for %s'), 'Media from FTP').'</a></p>';
			$sidebar .= '<p><a href="https://pledgie.com/campaigns/28307" target="_blank">'.__('Donate to this plugin &#187;').'</a></p>';

			$tabs = $this->get_help_message($screen_id);
			foreach($tabs as $tab) {
				$screen->add_help_tab($tab);
			}
			$screen->set_help_sidebar($sidebar);
		}
	}

	/* ==================================================
	 * Help Tab for message
	 * @param	string	$screen_id
	 * @return	array	$tab
	 * @since	9.53
	 */
	function get_help_message($screen_id) {

		$upload_dir_html = '<span style="color: red;">'.MEDIAFROMFTP_PLUGIN_UPLOAD_PATH.'</span>';

		switch ($screen_id) {
			case "media-from-ftp_page_mediafromftp-search-register":
				$outline = '<p>'.sprintf(__('Search the upload directory(%1$s) and display files that do not exist in the media library.', 'media-from-ftp'), $upload_dir_html).'</p>';
				$outline .= '<p>'.sprintf(__('Please check and press the "%1$s" button.', 'media-from-ftp'), __('Update Media')).'</p>';
				$outline .= '<p>'.sprintf(__('Options for searching can be specified with "%1$s".', 'media-from-ftp'), __('Screen Options')).'</p>';
				break;
			case "media-from-ftp_page_mediafromftp-settings":
				$outline = '<p>'.sprintf(__('"%1$s" sets options for %2$s registration.', 'media-from-ftp'), __('Register'), __('Media Library')).'</p>';
				$outline .= '<p>'.sprintf(__('"%1$s" sets other options.', 'media-from-ftp'), __('Other', 'media-from-ftp')).'</p>';
				$outline .= '<p>'.sprintf(__('"%1$s" shows how to use bundled %2$s.', 'media-from-ftp'), __('Command-line', 'media-from-ftp'), '<code>mediafromftpcmd.php</code>').'</p>';
				break;
			case "media-from-ftp_page_mediafromftp-log":
				$outline = '<p>'.__('Display history of registration.', 'media-from-ftp').'</p>';
				$outline .= '<p>'.__('You can export to CSV format.', 'media-from-ftp').'</p>';
				break;
			case "media-from-ftp_page_mediafromftp-import":
				$outline = '<p>'.__('This page does the independent processing from other pages in "Media from FTP".', 'media-from-ftp').'</p>';
				$outline .= '<p>'.__('To Import the files to Media Library from a WordPress export file.', 'media-from-ftp').'</p>';
				$outline .= '<p>'.sprintf(__('In uploads directory(%1$s), that you need to copy the file to the same state as the import source by FTP.', 'media-from-ftp'), $upload_dir_html).'</p>';
				break;
		}

		$tabs = array(
			array(
				'title' => __('Overview'),
				'id' => 'outline',
				'content' => $outline
				)
		);

		return $tabs;
	}

	/* ==================================================
	 * Add Css and Script
	 * @since	2.23
	 */
	function load_custom_wp_admin_style() {
		if ($this->is_my_plugin_screen()) {
			wp_enqueue_style( 'jquery-datetimepicker', MEDIAFROMFTP_PLUGIN_URL.'/css/jquery.datetimepicker.css' );
			wp_enqueue_style( 'jquery-responsiveTabs', MEDIAFROMFTP_PLUGIN_URL.'/css/responsive-tabs.css' );
			wp_enqueue_style( 'jquery-responsiveTabs-style', MEDIAFROMFTP_PLUGIN_URL.'/css/style.css' );
			wp_enqueue_style( 'mediafromftp',  MEDIAFROMFTP_PLUGIN_URL.'/css/mediafromftp.css' );
			wp_enqueue_script( 'jquery' );
			wp_enqueue_script( 'jquery-datetimepicker', MEDIAFROMFTP_PLUGIN_URL.'/js/jquery.datetimepicker.js', null, '2.3.4' );
			wp_enqueue_script( 'jquery-responsiveTabs', MEDIAFROMFTP_PLUGIN_URL.'/js/jquery.responsiveTabs.min.js' );
			$handle = 'mediafromftp-ajax-script';
			$action1 = 'mediafromftp-update-ajax-action';
			$action2 = 'mediafromftp-import-ajax-action';
			wp_enqueue_script( $handle, MEDIAFROMFTP_PLUGIN_URL.'/js/jquery.mediafromftp.js', array('jquery') );
			wp_localize_script( $handle, 'MEDIAFROMFTPUPDATE', array(
				'ajax_url' => admin_url('admin-ajax.php'),
				'action' => $action1,
				'nonce' => wp_create_nonce( $action1 )
			));
			wp_localize_script( $handle, 'MEDIAFROMFTPIMPORT', array(
				'ajax_url' => admin_url('admin-ajax.php'),
				'action' => $action2,
				'nonce' => wp_create_nonce( $action2 )
			));
		}
	}

	/* ==================================================
	 * Add Script on footer
	 * @since	1.0
	 */
	function load_custom_wp_admin_style2() {
		if ($this->is_my_plugin_screen2()) {
			if ( isset($_POST['media_from_ftp_select_author']) && $_POST['media_from_ftp_select_author'] ) {
				if ( check_admin_referer('mff_select_author', 'media_from_ftp_select_author') ) {
					if ( !empty($_POST['mediafromftp_select_author']) && !empty($_POST['mediafromftp_xml_file']) ) {
						if ( is_file($_POST['mediafromftp_xml_file']) ) {
							$select_author = array();
							foreach (array_keys($_POST) as $key) {
								if ( $key === 'select_author' || $key === 'mediafromftp_select_author' || $key === 'mediafromftp_xml_file' ) {	// skip
								} else {
									if ( $_POST[$key] <> -1 ) {
										$select_author[$key] = $_POST[$key];
									}
								}
							}
							$filename = $_POST['mediafromftp_xml_file'];
							include_once MEDIAFROMFTP_PLUGIN_BASE_DIR.'/inc/MediaFromFtp.php';
							$mediafromftp = new MediaFromFtp();
							echo $mediafromftp->make_object($filename, $select_author);
							unset($mediafromftp);
							unlink($filename);
						}
					}
				}
			}
		}
	}

	/* ==================================================
	 * For only admin style
	 * @since	8.82
	 */
	function is_my_plugin_screen() {
		$screen = get_current_screen();
		if (is_object($screen) && $screen->id == 'toplevel_page_mediafromftp') {
			return TRUE;
		} else if (is_object($screen) && $screen->id == 'media-from-ftp_page_mediafromftp-settings') {
			return TRUE;
		} else if (is_object($screen) && $screen->id == 'media-from-ftp_page_mediafromftp-search-register') {
			return TRUE;
		} else if (is_object($screen) && $screen->id == 'media-from-ftp_page_mediafromftp-log') {
			return TRUE;
		} else if (is_object($screen) && $screen->id == 'media-from-ftp_page_mediafromftp-import') {
			return TRUE;
		} else {
			return FALSE;
		}
	}

	/* ==================================================
	 * For only admin style
	 * @since	8.82
	 */
	function is_my_plugin_screen2() {
		$screen = get_current_screen();
		if (is_object($screen) && $screen->id == 'media-from-ftp_page_mediafromftp-import') {
			return TRUE;
		} else {
			return FALSE;
		}
	}

	/* ==================================================
	 * Main
	 */
	function manage_page() {

		if ( !current_user_can( 'upload_files' ) )  {
			wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
		}

		$plugin_datas = get_file_data( MEDIAFROMFTP_PLUGIN_BASE_DIR.'/mediafromftp.php', array('version' => 'Version') );
		$plugin_version = __('Version:').' '.$plugin_datas['version'];

		?>

		<div class="wrap">

		<h2>Media from FTP
			<a href="<?php echo admin_url('admin.php?page=mediafromftp-search-register'); ?>" class="page-title-action"><?php _e('Search & Register', 'media-from-ftp'); ?></a>
			<a href="<?php echo admin_url('admin.php?page=mediafromftp-settings'); ?>" class="page-title-action"><?php _e('Settings'); ?></a>
			<a href="<?php echo admin_url('admin.php?page=mediafromftp-log'); ?>" class="page-title-action"><?php _e('Log', 'media-from-ftp'); ?></a>
			<a href="<?php echo admin_url('admin.php?page=mediafromftp-import'); ?>" class="page-title-action"><?php _e('Import'); ?></a>
		</h2>
		<div style="clear: both;"></div>

		<h3><?php _e('Register to media library from files that have been uploaded by FTP.', 'media-from-ftp'); ?></h3>
		<span style="font-weight: bold;">
		<div>
		<?php echo $plugin_version; ?> | 
		<a style="text-decoration: none;" href="<?php _e('https://wordpress.org/plugins/media-from-ftp/faq', 'media-from-ftp'); ?>" target="_blank"><?php _e('FAQ'); ?></a> | <a style="text-decoration: none;" href="https://wordpress.org/support/plugin/media-from-ftp" target="_blank"><?php _e('Support Forums'); ?></a>
		</div>
		<div>
		<a style="text-decoration: none;" href="https://wordpress.org/support/view/plugin-reviews/media-from-ftp" target="_blank"><?php _e('Reviews', 'media-from-ftp'); ?></a> | 
		<a style="text-decoration: none;" href="https://translate.wordpress.org/projects/wp-plugins/media-from-ftp" target="_blank"><?php echo sprintf(__('Translations for %s'), 'Media from FTP'); ?></a>
		</div>
		</span>

		<div style="width: 250px; height: 180px; margin: 5px; padding: 5px; border: #CCC 2px solid;">
		<h3><?php _e('Please make a donation if you like my work or would like to further the development of this plugin.', 'media-from-ftp'); ?></h3>
		<div style="text-align: right; margin: 5px; padding: 5px;"><span style="padding: 3px; color: #ffffff; background-color: #008000">Plugin Author</span> <span style="font-weight: bold;">Katsushi Kawamori</span></div>
<a style="margin: 5px; padding: 5px;" href='https://pledgie.com/campaigns/28307' target="_blank"><img alt='Click here to lend your support to: Various Plugins for WordPress and make a donation at pledgie.com !' src='https://pledgie.com/campaigns/28307.png?skin_name=chrome' border='0' ></a>
		</div>

		</div>
		<?php

	}

	/* ==================================================
	 * Sub Menu
	 */
	function settings_page() {

		if ( !current_user_can( 'upload_files' ) )  {
			wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
		}

		if ( isset($_POST['media_from_ftp_settings']) && $_POST['media_from_ftp_settings'] ) {
			if ( check_admin_referer('mff_settings', 'media_from_ftp_settings') ) {
				$submenu = 1;
				$this->options_updated($submenu);
			}
		}
		if ( isset($_POST['media_from_ftp_clear_cash']) && $_POST['media_from_ftp_clear_cash'] ) {
			if ( check_admin_referer('mff_clear_cash', 'media_from_ftp_clear_cash') ) {
				$submenu = 3;
				$this->options_updated($submenu);
			}
		}
		if ( isset($_POST['media_from_ftp_run_cron']) && $_POST['media_from_ftp_run_cron'] ) {
			if ( check_admin_referer('mff_run_cron', 'media_from_ftp_run_cron') ) {
				$submenu = 4;
				$this->options_updated($submenu);
			}
		}
		$mediafromftp_settings = get_option($this->wp_options_name());

		$def_max_execution_time = ini_get('max_execution_time');
		$scriptname = admin_url('admin.php?page=mediafromftp-settings');

		?>

		<div class="wrap">

		<h2>Media from FTP <a href="<?php echo admin_url('admin.php?page=mediafromftp-settings'); ?>" style="text-decoration: none;"><?php _e('Settings'); ?></a>
			<a href="<?php echo admin_url('admin.php?page=mediafromftp-search-register'); ?>" class="page-title-action"><?php _e('Search & Register', 'media-from-ftp'); ?></a>
			<a href="<?php echo admin_url('admin.php?page=mediafromftp-log'); ?>" class="page-title-action"><?php _e('Log', 'media-from-ftp'); ?></a>
			<a href="<?php echo admin_url('admin.php?page=mediafromftp-import'); ?>" class="page-title-action"><?php _e('Import'); ?></a>
		</h2>
		<div style="clear: both;"></div>

		<div id="mediafromftp-settings-tabs">
			<ul>
			<li><a href="#mediafromftp-settings-tabs-1"><?php _e('Register'); ?></a></li>
			<li><a href="#mediafromftp-settings-tabs-2"><?php _e('Other', 'media-from-ftp'); ?></a></li>
			<li><a href="#mediafromftp-settings-tabs-3"><?php _e('Command-line', 'media-from-ftp'); ?></a></li>
			</ul>

			<div id="mediafromftp-settings-tabs-1">
			<div style="display: block; padding: 5px 15px">
				<div class="item-mediafromftp-settings">
					<h3><?php _e('Date'); ?></h3>
					<div style="display: block;padding:5px 5px">
					<input type="radio" name="mediafromftp_dateset" form="mediafromftp_settings_form" value="new" <?php if ($mediafromftp_settings['dateset'] === 'new') echo 'checked'; ?>>
					<?php _e('Update to use of the current date/time.', 'media-from-ftp'); ?>
					</div>
					<div style="display: block;padding:5px 5px">
					<input type="radio" name="mediafromftp_dateset" form="mediafromftp_settings_form" value="server" <?php if ($mediafromftp_settings['dateset'] === 'server') echo 'checked'; ?>>
					<?php _e('Get the date/time of the file, and updated based on it. Change it if necessary.', 'media-from-ftp'); ?>
					</div>
					<div style="display: block; padding:5px 5px">
					<input type="radio" name="mediafromftp_dateset" form="mediafromftp_settings_form" value="exif" <?php if ($mediafromftp_settings['dateset'] === 'exif') echo 'checked'; ?>>
					<?php
					_e('Get the date/time of the file, and updated based on it. Change it if necessary.', 'media-from-ftp');
					_e('Get by priority if there is date and time of the Exif information.', 'media-from-ftp');
					?>
					</div>
					<div style="display: block; padding:5px 5px">
					<?php
					if ( current_user_can('manage_options') ) {
						?>
						<input type="checkbox" name="move_yearmonth_folders" form="mediafromftp_settings_form" value="1" <?php checked('1', get_option('uploads_use_yearmonth_folders')); ?> />
						<?php
					} else {
						?>
						<input type="checkbox" form="mediafromftp_settings_form" disabled="disabled" value="1" <?php checked('1', get_option('uploads_use_yearmonth_folders')); ?> />
						<input type="hidden" name="move_yearmonth_folders" form="mediafromftp_settings_form" value="<?php echo get_option('uploads_use_yearmonth_folders'); ?>">
						<?php
					}
					_e('Organize my uploads into month- and year-based folders');
					?>
					</div>
				</div>

				<div class="item-mediafromftp-settings">
					<h3>Exif <?php _e('Caption'); ?></h3>
					<div style="display:block;padding:5px 0">
					<?php _e('Register the Exif data to the caption.', 'media-from-ftp'); ?>
					</div>
					<div style="display:block;padding:5px 0">
					<input type="checkbox" name="mediafromftp_caption_apply" form="mediafromftp_settings_form" value="1" <?php checked('1', $mediafromftp_settings['caption']['apply']); ?> />
					<?php _e('Apply'); ?>
					</div>
					<div style="display: block; padding:5px 20px;">
						Exif <?php _e('Tags'); ?>
						<?php submit_button( __('Default'), 'button', 'mediafromftp_exif_default', FALSE, array( 'style' => 'position:relative; top:-5px;', 'form' => 'mediafromftp_settings_form' ) ); ?>
						<div style="display: block; padding:5px 20px;">
						<textarea name="mediafromftp_exif_text" form="mediafromftp_settings_form" style="width: 100%;"><?php echo $mediafromftp_settings['caption']['exif_text']; ?></textarea>
							<div>
							<a href="https://codex.wordpress.org/Function_Reference/wp_read_image_metadata#Return%20Values" target="_blank" style="text-decoration: none; word-break: break-all;"><?php _e('For Exif tags, please read here.', 'media-from-ftp'); ?></a>
							</div>
						</div>
					</div>
					<div>
					<?php
						if ( is_multisite() ) {
							$exifcaption_install_url = network_admin_url('plugin-install.php?tab=plugin-information&plugin=exif-caption');
						} else {
							$exifcaption_install_url = admin_url('plugin-install.php?tab=plugin-information&plugin=exif-caption');
						}
						$exifcaption_install_html = '<a href="'.$exifcaption_install_url.'" target="_blank" style="text-decoration: none; word-break: break-all;">Exif Caption</a>';
						echo sprintf(__('If you want to insert the Exif in the media that have already been registered in the media library, Please use the %1$s.','media-from-ftp'), $exifcaption_install_html);
					?>
					</div>
				</div>

				<div class="item-mediafromftp-settings">
					<h3><?php _e('Schedule', 'media-from-ftp'); ?></h3>
					<div style="display:block;padding:5px 0">
					<?php _e('Set the schedule.', 'media-from-ftp'); ?>
					<?php _e('Will take some time until the [Next Schedule] is reflected.', 'media-from-ftp'); ?>
					</div>
					<div style="display:block;padding:5px 0">
					<?php
					$cron_args = array( 'wp_options_name' => $this->wp_options_name() );
					if ( wp_next_scheduled( 'MediaFromFtpCronHook', $cron_args ) ) {
						?>
						<form method="post" action="<?php echo $scriptname; ?>" />
						<?php wp_nonce_field('mff_run_cron', 'media_from_ftp_run_cron'); ?>
						<input type="hidden" name="mediafromftp_run_cron" value="1" />
						<?php echo __('Next Schedule:', 'media-from-ftp').' '.get_date_from_gmt(date("Y-m-d H:i:s", wp_next_scheduled( 'MediaFromFtpCronHook', $cron_args ))).' ';
						submit_button( __('Execute now', 'media-from-ftp'), 'large', '', FALSE, array( 'style' => 'position:relative; top:-5px;' ) ); ?>
						</form>
						<?php
					} else {
						echo __('Next Schedule:', 'media-from-ftp').' '.__('None');
					}
					?>
					</div>
					<div style="display:block;padding:5px 0">
					<input type="checkbox" name="mediafromftp_cron_apply" form="mediafromftp_settings_form" value="1" <?php checked('1', $mediafromftp_settings['cron']['apply']); ?> />
					<?php _e('Apply Schedule', 'media-from-ftp'); ?>
					</div>
					<div style="display:block;padding:5px 10px">
					<input type="radio" name="mediafromftp_cron_schedule" form="mediafromftp_settings_form" value="hourly" <?php checked('hourly', $mediafromftp_settings['cron']['schedule']); ?>>
					<?php _e('hourly', 'media-from-ftp'); ?>
					</div>
					<div style="display:block;padding:5px 10px">
					<input type="radio" name="mediafromftp_cron_schedule" form="mediafromftp_settings_form" value="twicedaily" <?php checked('twicedaily', $mediafromftp_settings['cron']['schedule']); ?>>
					<?php _e('twice daily', 'media-from-ftp'); ?>
					</div>
					<div style="display:block;padding:5px 10px">
					<input type="radio" name="mediafromftp_cron_schedule" form="mediafromftp_settings_form" value="daily" <?php checked('daily', $mediafromftp_settings['cron']['schedule']); ?>>
					<?php _e('daily', 'media-from-ftp'); ?>
					</div>
					<div style="display:block;padding:5px 10px">
					<input type="checkbox" name="mediafromftp_cron_limit_number" form="mediafromftp_settings_form" value="1" <?php checked('1', $mediafromftp_settings['cron']['limit_number']); ?> />
					<?php _e('Apply limit number of update files.', 'media-from-ftp'); ?>
					</div>
					<div style="display:block;padding:5px 20px">
						<?php echo __('Limit number of update files', 'media-from-ftp').': '.$mediafromftp_settings['pagemax']; ?>
						= <a href="<?php echo admin_url('admin.php?page=mediafromftp-search-register'); ?>"><?php _e('Number of items per page:'); ?></a>
					</div>
					<div style="display:block;padding:5px 10px">
					<input type="checkbox" name="mediafromftp_cron_mail_apply" form="mediafromftp_settings_form" value="1" <?php checked('1', $mediafromftp_settings['cron']['mail_apply']); ?> />
					<?php _e('Email me whenever'); ?>
					</div>
					<div style="display:block;padding:5px 20px">
						<?php echo __('Your Email').': '.$mediafromftp_settings['cron']['mail']; ?>
					</div>
				</div>

				<div class="item-mediafromftp-settings">
					<h3><?php _e('Log', 'media-from-ftp'); ?></h3>
					<div style="display:block;padding:5px 0">
					<?php _e('Record the registration result.', 'media-from-ftp'); ?>
					</div>
					<div style="display:block;padding:5px 0">
					<input type="checkbox" name="mediafromftp_apply_log" form="mediafromftp_settings_form" value="1" <?php checked('1', $mediafromftp_settings['log']); ?> />
					<?php _e('Create log', 'media-from-ftp'); ?>
					</div>
				</div>

				<div style="clear: both;"></div>

			<form method="post" id="mediafromftp_settings_form" action="<?php echo $scriptname; ?>">
				<?php wp_nonce_field('mff_settings', 'media_from_ftp_settings'); ?>
				<div style="display: block;padding:5px 5px">
				<?php submit_button( __('Save Changes'), 'large', 'media-from-ftp-settings-options-apply', FALSE );	?>
				</div>
			</form>

			</div>
			</div>

			<div id="mediafromftp-settings-tabs-2">
			<div style="display: block; padding: 5px 15px">

				<div class="item-mediafromftp-settings">
					<h3><?php _e('Limit number of search files', 'media-from-ftp'); ?></h3>
					<p>
					<?php _e('If you can not search because there are too many files, please reduce this number.', 'media-from-ftp'); ?>
					</p>
					<div style="display:block;padding:5px 0">
					<input type="number" step="100" min="100" max="100000" name="mediafromftp_search_limit_number" value="<?php echo $mediafromftp_settings['search_limit_number']; ?>" form="mediafromftp_settings_form" >
					</div>
					<div style="clear: both;"></div>
				</div>

				<div class="item-mediafromftp-settings">
					<h3><?php _e('Execution time', 'media-from-ftp'); ?></h3>
					<div style="display:block; padding:5px 5px">
						<?php
							$max_execution_time = $mediafromftp_settings['max_execution_time'];
							if ( !@set_time_limit($max_execution_time) ) {
								$limit_seconds_html =  '<font color="red">'.$def_max_execution_time.__('seconds', 'media-from-ftp').'</font>';
								$command_line_html = '<a href="'.admin_url('admin.php?page=mediafromftp-settings#mediafromftp-settings-tabs-3').'">'.__('Command-line', 'media-from-ftp').'</a>';
								?>
								<?php echo sprintf(__('Execution time for this server is fixed at %1$s. If this limit is exceeded, the search times out&#40;%2$s, %3$s&#41;. Please use the %4$s if you do not want to be bound by this restriction.', 'media-from-ftp'), $limit_seconds_html, __('Search'), __('Log', 'media-from-ftp'), $command_line_html); ?>
								<input type="hidden" name="mediafromftp_max_execution_time" form="mediafromftp_settings_form" value="<?php echo $def_max_execution_time; ?>" />
							<?php
							} else {
								$max_execution_time_text = __('The number of seconds a script is allowed to run.', 'media-from-ftp').'('.__('The max_execution_time value defined in the php.ini.', 'media-from-ftp').'[<font color="red">'.$def_max_execution_time.'</font>]'.')';
								_e('This is to suppress the timeout when retrieving a large amount of data when displaying the search screen and log screen.', 'media-from-ftp');
								_e('It does not matter on the registration screen.', 'media-from-ftp');
								?>
								<div style="float: left;"><?php echo $max_execution_time_text; ?>:<input type="number" step="1" min="1" max="999" class="screen-per-page" maxlength="3" name="mediafromftp_max_execution_time" form="mediafromftp_settings_form" value="<?php echo $max_execution_time; ?>" /></div>
							<?php
							}
						?>
					</div>
					<div style="clear: both;"></div>
				</div>

				<?php
				if ( function_exists('mb_check_encoding') ) {
				?>
				<div class="item-mediafromftp-settings">
					<h3><?php _e('Character Encodings for Server', 'media-from-ftp'); ?></h3>
					<p>
					<?php _e('It may fail to register if you are using a multi-byte name in the file name or folder name. In that case, please change.', 'media-from-ftp');
					$characterencodings_none_html = '<a href="'.__('https://en.wikipedia.org/wiki/Variable-width_encoding', 'media-from-ftp').'" target="_blank" style="text-decoration: none; word-break: break-all;">'.__('variable-width encoding', 'media-from-ftp').'</a>';
					echo sprintf(__('If you do not use the filename or directory name of %1$s, please choose "%2$s".','media-from-ftp'), $characterencodings_none_html, '<font color="red">none</font>');
					?>
					</p>
					<select name="mediafromftp_character_code" form="mediafromftp_settings_form" style="width: 210px">
					<?php
					if ( 'none' === $mediafromftp_settings['character_code'] ) {
						?>
						<option value="none" selected>none</option>
						<?php
					} else {
						?>
						<option value="none">none</option>
						<?php
					}
					foreach (mb_list_encodings() as $chrcode) {
						if ( $chrcode <> 'pass' && $chrcode <> 'auto' ) {
							if ( $chrcode === $mediafromftp_settings['character_code'] ) {
								?>
								<option value="<?php echo $chrcode; ?>" selected><?php echo $chrcode; ?></option>
								<?php
							} else {
								?>
								<option value="<?php echo $chrcode; ?>"><?php echo $chrcode; ?></option>
								<?php
							}
						}
					}
					?>
					</select>
					<div style="clear: both;"></div>
				</div>
				<?php
				}
				?>

				<div class="item-mediafromftp-settings">
					<h3><?php _e('Remove Thumbnails Cache', 'media-from-ftp'); ?></h3>
					<div style="display:block;padding:5px 0">
						<?php _e('Remove the cache of thumbnail used in the search screen. Please try out if trouble occurs in the search screen. It might become normal.', 'media-from-ftp'); ?>
					</div>
					<form method="post" action="<?php echo $scriptname; ?>" />
						<?php wp_nonce_field('mff_clear_cash', 'media_from_ftp_clear_cash'); ?>
						<input type="hidden" name="mediafromftp_clear_cash" value="1" />
						<div>
						<?php submit_button( __('Remove Thumbnails Cache', 'media-from-ftp'), 'delete', '', FALSE); ?>
						</div>
					</form>
				</div>

				<div style="clear: both;"></div>

				<div style="display: block;padding:5px 5px">
				<?php submit_button( __('Save Changes'), 'large', 'media-from-ftp-settings-options-apply', FALSE, array( 'form' => 'mediafromftp_settings_form' ) ); ?>
				</div>

			</div>
			</div>

			<div id="mediafromftp-settings-tabs-3">
				<h3><?php _e('Command-line', 'media-from-ftp'); ?></h3>
				<div style="display:block; padding:5px 10px; font-weight: bold;">
				1. <?php _e('Please [mediafromftpcmd.php] rewrite the following manner.', 'media-from-ftp'); ?>
				</div>
				<div style="display:block;padding:5px 20px">
				<?php
				$commandline_host = $_SERVER['HTTP_HOST'];
				$commandline_server = $_SERVER['SERVER_NAME'];
				$commandline_uri = untrailingslashit(wp_make_link_relative(MEDIAFROMFTP_PLUGIN_SITE_URL));
				$commandline_wpload = wp_normalize_path(ABSPATH).'wp-load.php';
				$commandline_pg = wp_normalize_path(MEDIAFROMFTP_PLUGIN_BASE_DIR.'/mediafromftpcmd.php');
				$commandline_wget = MEDIAFROMFTP_PLUGIN_URL.'/mediafromftpcmd.php';
$commandline_set = <<<COMMANDLINESET

&#x24_SERVER = array(
"HTTP_HOST" => "$commandline_host",
"SERVER_NAME" => "$commandline_server",
"REQUEST_URI" => "$commandline_uri",
"REQUEST_METHOD" => "GET",
"HTTP_USER_AGENT" => "mediafromftp"
            );
require_once('$commandline_wpload');

COMMANDLINESET;

				$commandline_wp_options_name = $this->wp_options_name();
$commandline_set2 = <<<COMMANDLINESET2

	&#x24mediafromftpcron->CronDo('$commandline_wp_options_name');

COMMANDLINESET2;

				?>
				<?php echo sprintf(__('The line %2$d from line %1$d.', 'media-from-ftp'), 58, 65); ?>
				<textarea readonly rows="9" style="font-size: 12px; width: 100%;">
				<?php echo $commandline_set; ?>
				</textarea>
				<?php echo sprintf(__('The line %1$d.', 'media-from-ftp'), 70); ?>
				<textarea readonly rows="2" style="font-size: 12px; width: 100%;">
				<?php echo $commandline_set2; ?>
				</textarea>
				</div>
				<div style="display:block; padding:5px 10px; font-weight: bold;">
				2. <?php _e('The execution of the command line.', 'media-from-ftp'); ?>
				</div>
				<div style="display:block; padding:5px 10px;">
				<div>% <code>/usr/bin/php <?php echo $commandline_pg; ?></code></div>
				<div style="display:block; padding:5px 15px; color:red;"><code>/usr/bin/php</code> >> <?php _e('Please check with the server administrator.', 'media-from-ftp'); ?></div>
					<div style="display:block;padding:5px 20px">
					<li style="font-weight: bold;"><?php _e('command line argument list', 'media-from-ftp'); ?></li>
						<div style="display:block;padding:5px 40px">
						<div><code>-s</code> <?php _e('Search directory', 'media-from-ftp'); ?></div>
						</div>
							<div style="display:block;padding:5px 60px">
							<div><?php _e('Example:', 'media-from-ftp'); ?> <code>-s wp-content/uploads</code></div>
							</div>
						<div style="display:block;padding:5px 40px">
						<div><code>-d</code> <?php _e('Date time settings', 'media-from-ftp'); ?> (new, server, exif)</div>
						</div>
							<div style="display:block;padding:5px 60px">
							<div><?php _e('Example:', 'media-from-ftp'); ?> <code>-d exif</code></div>
							</div>
						<div style="display:block;padding:5px 40px">
						<div><code>-e</code> <?php _e('Exclude file', 'media-from-ftp'); ?> (<?php _e('Regular expression is possible.', 'media-from-ftp'); ?>)</div>
						</div>
							<div style="display:block;padding:5px 60px">
							<div><?php _e('Example:', 'media-from-ftp'); ?> <code>-e "(.ktai.)|(.backwpup_log.)|(.ps_auto_sitemap.)|\.php|\.js"</code></div>
							</div>
						<div style="display:block;padding:5px 40px">
						<div><code>-t</code> <?php _e('File type:'); ?> (all, image, audio, video, document, spreadsheet, interactive, text, archive, code)</div>
						</div>
							<div style="display:block;padding:5px 60px">
							<div><?php _e('Example:', 'media-from-ftp'); ?> <code>-t image</code></div>
							</div>
						<div style="display:block;padding:5px 40px">
						<div><code>-x</code> <?php _e('File extension' , 'media-from-ftp'); ?></div>
						</div>
							<div style="display:block;padding:5px 60px">
							<div><?php _e('Example:', 'media-from-ftp'); ?> <code>-x jpg</code></div>
							</div>
						<div style="display:block;padding:5px 40px">
						<div><code>-p</code> <?php _e('Limit number of update files' , 'media-from-ftp'); ?></div>
						</div>
							<div style="display:block;padding:5px 60px">
							<div><?php _e('Example:', 'media-from-ftp'); ?> <code>-p 10</code></div>
							</div>
						<div style="display:block;padding:5px 40px">
						<div><code>-f</code> <?php _e('Limit number of search files' , 'media-from-ftp'); ?></div>
						</div>
							<div style="display:block;padding:5px 60px">
							<div><?php _e('Example:', 'media-from-ftp'); ?> <code>-f 100</code></div>
							</div>
						<div style="display:block;padding:5px 40px">
						<div><code>-c</code> <?php _e('Exif tags for registering in the caption' , 'media-from-ftp'); ?></div>
						</div>
							<div style="display:block;padding:5px 60px">
							<div><?php _e('Example:', 'media-from-ftp'); ?> <code>-c "%title% %credit% %camera% %caption% %created_timestamp% %copyright% %aperture% %shutter_speed% %iso% %focal_length%"</code></div>
							</div>
					<div><?php _e('If the argument is empty, use the set value of the management screen.', 'media-from-ftp'); ?></div>
					</div>
					<div style="display:block;padding:5px 20px">
					<li style="font-weight: bold;"><?php _e('command line switch', 'media-from-ftp'); ?></li>
						<div style="display:block;padding:5px 40px">
						<div><code>-h</code> <?php _e('Hides the display of the log.', 'media-from-ftp'); ?></div>
						</div>
							<div style="display:block;padding:5px 60px">
							<div><?php _e('Example:', 'media-from-ftp'); ?> <code>-h</code></div>
							</div>
						<div style="display:block;padding:5px 40px">
						<div><code>-g</code> <?php _e('Create log to database.', 'media-from-ftp'); ?></div>
						</div>
							<div style="display:block;padding:5px 60px">
							<div><?php _e('Example:', 'media-from-ftp'); ?> <code>-g</code></div>
							</div>
						<div style="display:block;padding:5px 40px">
						<div><code>-m</code> <?php _e('If you want to search for filename that contains such -0x0. It is low speed.', 'media-from-ftp'); ?></div>
						</div>
							<div style="display:block;padding:5px 60px">
							<div><?php _e('Example:', 'media-from-ftp'); ?> <code>-m</code></div>
							</div>
					</div>
					<div style="color:red;"><?php _e('Command-line, please use by activate sure the plug-in.', 'media-from-ftp'); ?></div>
				</div>
				<div style="display:block; padding:5px 10px; font-weight: bold;">
				3. <?php _e('Register the command-line to the server cron.', 'media-from-ftp'); ?> (<?php _e('Example:', 'media-from-ftp'); ?> <?php _e('Run every 10 minutes.', 'media-from-ftp'); ?>)
				</div>
				<div style="display:block; padding:5px 30px;">
				<li style="font-weight: bold;"><?php _e('example:'); ?>1</li>
				<div><code>0,10,20,30,40,50 * * * * /usr/bin/php <?php echo $commandline_pg; ?></code></div>
				<div style="display:block; padding:5px 25px; color:red;"><code>/usr/bin/php</code> >> <?php _e('Please check with the server administrator.', 'media-from-ftp'); ?></div>
				<li style="font-weight: bold;"><?php _e('example:'); ?>2</li>
				<div><code>0,10,20,30,40,50 * * * * /usr/bin/wget <?php echo $commandline_wget; ?></code></div>
				<div style="display:block; padding:5px 25px; color:red;"><code>/usr/bin/wget</code> >> <?php _e('Please check with the server administrator.', 'media-from-ftp'); ?></div>
				</div>
			</div>

		</div>
		</div>
		<?php

	}


	/* ==================================================
	 * Sub Menu
	 */
	function search_register_page(){

		if ( !current_user_can( 'upload_files' ) )  {
			wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
		}

		if ( isset($_POST['media_from_ftp_search']) && $_POST['media_from_ftp_search'] ) {
			if ( check_admin_referer('mff_search', 'media_from_ftp_search') ) {
				$submenu = 2;
				$this->options_updated($submenu);
			}
		}
		$mediafromftp_settings = get_option($this->wp_options_name());

		$def_max_execution_time = ini_get('max_execution_time');
		$max_execution_time = $mediafromftp_settings['max_execution_time'];

		$limit_seconds_html =  '<font color="red">'.$def_max_execution_time.__('seconds', 'media-from-ftp').'</font>';

		if ( !@set_time_limit($max_execution_time) ) {
			echo '<div class="notice notice-info is-dismissible"><ul><li>'.sprintf(__('Execution time for this server is fixed at %1$s. If this limit is exceeded, times out&#40;%2$s&#41;. Please note the "Number of items per page" so as not to exceed this limit.', 'media-from-ftp'), $limit_seconds_html, __('Search')).'</li></ul></div>';
		}

	    ?>
		<div class="wrap">

			<h2>Media from FTP <a href="<?php echo admin_url('admin.php?page=mediafromftp-search-register'); ?>" style="text-decoration: none;"><?php _e('Search & Register', 'media-from-ftp'); ?></a>
				<a href="<?php echo admin_url('admin.php?page=mediafromftp-settings'); ?>" class="page-title-action"><?php _e('Settings'); ?></a>
				<a href="<?php echo admin_url('admin.php?page=mediafromftp-log'); ?>" class="page-title-action"><?php _e('Log', 'media-from-ftp'); ?></a>
				<a href="<?php echo admin_url('admin.php?page=mediafromftp-import'); ?>" class="page-title-action"><?php _e('Import'); ?></a>
			</h2>
			<div style="clear: both;"></div>

			<div id="mediafromftp-loading"><img src="<?php echo MEDIAFROMFTP_PLUGIN_URL.'/css/loading.gif'; ?>"></div>
			<div id="mediafromftp-loading-container">
				<?php
				include_once MEDIAFROMFTP_PLUGIN_BASE_DIR.'/inc/MediaFromFtp.php';
				$mediafromftp = new MediaFromFtp();
				$formhtml = $mediafromftp->form_html($mediafromftp_settings);
				unset($mediafromftp);
				require_once( MEDIAFROMFTP_PLUGIN_BASE_DIR.'/req/MediaFromFtpListTable.php' );
			    $MediaFromFtpListTable = new TT_MediaFromFtp_List_Table();
			    $MediaFromFtpListTable->prepare_items($mediafromftp_settings);
				if ( $MediaFromFtpListTable->max_items > 0 ) {
					$update_button = get_submit_button( __('Update Media'), 'primary', '', FALSE, array('form' => 'mediafromftp_ajax_update') );
					$update_upper_button = '<div style="padding: 15px 15px 0px;">'.$update_button.'</div>';
					$update_lower_button = '<div style="padding: 0px 15px;">'.$update_button.'</div>';
				} else {
					$update_upper_button = NULL;
					$update_lower_button = NULL;
				}
				?>
				<div><?php echo $formhtml; ?></div>
				<form method="post" id="mediafromftp_ajax_update">
					<form id="media-from-ftp-filter" method="get">
						<input type="hidden" name="page" value="<?php echo $_REQUEST['page']; ?>" />
						<?php echo $update_upper_button; ?>
						<?php $MediaFromFtpListTable->display(); ?>
						<?php echo $update_lower_button; ?>
					</form>
				</form>
			</div>
		</div>
	    <?php
	}

	/* ==================================================
	 * Sub Menu
	 */
	function log_page() {

		if ( !current_user_can( 'upload_files' ) )  {
			wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
		}

		$mediafromftp_settings = get_option($this->wp_options_name());
		if ( !$mediafromftp_settings['log'] ) {
			echo '<div class="notice notice-info is-dismissible"><ul><li>'.__('Current, log is not created. If you want to create a log, please put a check in the [Create log] in the settings.', 'media-from-ftp').'</li></ul></div>';
		}
		$def_max_execution_time = ini_get('max_execution_time');
		$max_execution_time = $mediafromftp_settings['max_execution_time'];

		$limit_seconds_html =  '<font color="red">'.$def_max_execution_time.__('seconds', 'media-from-ftp').'</font>';
		if ( !@set_time_limit($max_execution_time) ) {
			echo '<div class="notice notice-info is-dismissible"><ul><li>'.sprintf(__('Execution time for this server is fixed at %1$s. If this limit is exceeded, times out. Please run the frequently "Delete log" and "Export to CSV" so as not to exceed this limit.', 'media-from-ftp'), $limit_seconds_html).'</li></ul></div>';
		}

		?>
		<div class="wrap">

		<h2>Media from FTP <a href="<?php echo admin_url('admin.php?page=mediafromftp-log'); ?>" style="text-decoration: none;"><?php _e('Log', 'media-from-ftp'); ?></a>
			<a href="<?php echo admin_url('admin.php?page=mediafromftp-search-register'); ?>" class="page-title-action"><?php _e('Search & Register', 'media-from-ftp'); ?></a>
			<a href="<?php echo admin_url('admin.php?page=mediafromftp-settings'); ?>" class="page-title-action"><?php _e('Settings'); ?></a>
			<a href="<?php echo admin_url('admin.php?page=mediafromftp-import'); ?>" class="page-title-action"><?php _e('Import'); ?></a>
		</h2>
		<div style="clear: both;"></div>

		<div id="mediafromftp-loading"><img src="<?php echo MEDIAFROMFTP_PLUGIN_URL.'/css/loading.gif'; ?>"></div>
		<div id="mediafromftp-loading-container">
		<?php
		global $wpdb;

		$user = wp_get_current_user();

		$table_name = $wpdb->prefix.'mediafromftp_log';

		if ( isset($_POST['media_from_ftp_clear_log']) && $_POST['media_from_ftp_clear_log'] ) {
			if ( check_admin_referer('mff_clear_log', 'media_from_ftp_clear_log') ) {
				if ( !empty($_POST['mediafromftp_clear_log']) && $_POST['mediafromftp_clear_log'] == 1 ) {
					if ( current_user_can('administrator') ) {
						$wpdb->query("DELETE FROM $table_name");
						echo '<div class="notice notice-success is-dismissible"><ul><li>'.__('Removed all of the log.', 'media-from-ftp').'</li></ul></div>';
					} else {
						$delete_count = $wpdb->delete($table_name, array( 'user' => $user->display_name ));
						if ( $delete_count > 0 ) {
							echo '<div class="notice notice-success is-dismissible"><ul><li>'.sprintf(__('%1$s of the log has been deleted %2$d.', 'media-from-ftp'), $user->display_name, $delete_count ).'</li></ul></div>';
						} else {
							echo '<div class="notice notice-info is-dismissible"><ul><li>'.sprintf(__('%1$s do not have a possible deletion log.', 'media-from-ftp'), $user->display_name ).'</li></ul></div>';
						}
					}
				}
			}
		}

		$records = $wpdb->get_results("SELECT * FROM $table_name");

		$csv = NULL;
		$max_thumbnail_count = 0;
		$html = '<table>';

		foreach ( $records as $record ) {
			$csvs = '"'.$record->id.'","'.$record->user.'","'.$record->title.'","'.$record->permalink.'","'.$record->url.'","'.$record->filename.'","'.$record->time.'","'.$record->filetype.'","'.$record->filesize.'","'.$record->exif.'","'.$record->length.'"';
			$html_thumbnail = NULL;
			if ( $record->thumbnail ) {
				$thumbnails = json_decode($record->thumbnail, true);
				if ( $max_thumbnail_count < count($thumbnails) ) {
					$max_thumbnail_count = count($thumbnails);
				}
				$count = 0;
				foreach ( $thumbnails as $thumbnail ) {
					++$count;
					$html_thumbnail .= '<tr><th align="right" style="white-space: nowrap;">'.__('Featured Image').$count.':</th><td>'.$thumbnail.'</td></tr>';
					$csvs .= ',"'.$thumbnail.'"';
				}
			}
			$csvs .= "\n";
			$csv .= $csvs;
			$html .= '<tr><th>&nbsp;</th><td>&nbsp;</td></tr>';
			$html .= '<tr><th align="right" style="background-color: #cccccc;">ID:</th><td>'.$record->id.'</td></tr>';
			$html .= '<tr><th align="right" style="white-space: nowrap;">'.__('Author').':</th><td>'.$record->user.'</td></tr>';
			$html .= '<tr><th align="right" style="white-space: nowrap;">'.__('Title').':</th><td>'.$record->title.'</td></tr>';
			$html .= '<tr><th align="right" style="white-space: nowrap;">'.__('Permalink:').'</th><td>'.$record->permalink.'</td></tr>';
			$html .= '<tr><th align="right" style="white-space: nowrap;">URL:</th><td>'.$record->url.'</td>';
			$html .= '<tr><th align="right" style="white-space: nowrap;">'.__('File name:').'</th><td>'.$record->filename.'</td></tr>';
			$html .= '<tr><th align="right" style="white-space: nowrap;">'.__('Date/Time').':</th><td>'.$record->time.'</td></tr>';
			$html .= '<tr><th align="right" style="white-space: nowrap;">'.__('File type:').'</th><td>'.$record->filetype.'</td></tr>';
			$html .= '<tr><th align="right" style="white-space: nowrap;">'.__('File size:').'</th><td>'.$record->filesize.'</td></tr>';
			if ( $record->exif ) {
				$html .= '<tr><th align="right" style="white-space: nowrap;">'.__('Caption').'[Exif]:</th><td>'.$record->exif.'</td></tr>';
			}
			if ( $record->length ) {
				$html .= '<tr><th align="right" style="white-space: nowrap;">'.__('Length:').'</th><td>'.$record->length.'</td></tr>';
			}
			$html .= $html_thumbnail;
		}
		$html .= '</table>'."\n";
		$csv_head = '"ID","'.__('Author').'","'.__('Title').':","'.__('Permalink:').'","URL:","'.__('File name:').'","'.__('Date/Time').':","'.__('File type:').'","'.__('File size:').'","'.__('Caption').'[Exif]:","'.__('Length:').'"';
		for ($i = 1 ; $i <= $max_thumbnail_count; $i++) {
			$csv_head .= ',"'.__('Featured Image').$i.'"';
		}
		$csv = $csv_head."\n".$csv;

		$csvFileName = MEDIAFROMFTP_PLUGIN_TMP_DIR.'/'.$table_name.'.csv';
		if ( isset($_POST['media_from_ftp_put_log']) && $_POST['media_from_ftp_put_log'] ) {
			if ( check_admin_referer('mff_put_log', 'media_from_ftp_put_log') ) {
				if ( !empty($_POST['mediafromftp_put_log']) && $_POST['mediafromftp_put_log'] == 1 ) {
					file_put_contents($csvFileName, pack('C*',0xEF,0xBB,0xBF)); //UTF-8 BOM
					file_put_contents($csvFileName, $csv, FILE_APPEND | LOCK_EX);
				}
			}
		} else {
			if ( file_exists($csvFileName) ) {
				unlink($csvFileName);
			}
		}

		if ( !empty($records) ) {
			?>
			<div style="display: block; padding: 10px 10px">
			<form style="float: left;" method="post" action="<?php echo admin_url('admin.php?page=mediafromftp-log'); ?>" />
				<?php wp_nonce_field('mff_clear_log', 'media_from_ftp_clear_log'); ?>
				<input type="hidden" name="mediafromftp_clear_log" value="1" />
				<div>
				<?php submit_button( __('Delete log', 'media-from-ftp'), 'large', '', FALSE ); ?>
				</div>
			</form>
			<form style="float: left; margin-left: 0.5em; margin-right: 0.5em;" method="post" action="<?php echo admin_url('admin.php?page=mediafromftp-log'); ?>" />
				<?php wp_nonce_field('mff_put_log', 'media_from_ftp_put_log'); ?>
				<input type="hidden" name="mediafromftp_put_log" value="1" />
				<div>
				<?php submit_button( __('Export to CSV', 'media-from-ftp'), 'large', '', FALSE ); ?>
				</div>
			</form>
			<?php
			if ( file_exists($csvFileName) ) {
				?>
				<form method="post" action="<?php echo MEDIAFROMFTP_PLUGIN_TMP_URL.'/'.$table_name.'.csv'; ?>" />
					<?php wp_nonce_field('mff_download', 'media_from_ftp_download'); ?>
					<div>
					<input type="hidden" name="mediafromftp_download" value="1" />
					<?php submit_button( __('Download CSV', 'media-from-ftp'), 'large', '', FALSE ); ?>
					</div>
				</form>
				<?php
			}
			?>
			</div>
			<div style="clear: both;"></div>
			<div style="display: block; padding: 10px 10px">
			<?php echo $html;
			?>
			</div>
			<?php
		} else {
			if ( $mediafromftp_settings['log'] ) {
				echo '<div class="notice notice-info is-dismissible"><ul><li>'.__('There is no log.', 'media-from-ftp').'</li></ul></div>';
			}
		}
		?>
		</div>

		</div>

		<?php

	}

	/* ==================================================
	 * Sub Menu
	 */
	function medialibrary_import_page() {

		if ( !current_user_can( 'upload_files' ) )  {
			wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
		}

		$scriptname = admin_url('admin.php?page=mediafromftp-import');

		?>
		<div class="wrap">
		<h2>Media from FTP <a href="<?php echo $scriptname; ?>" style="text-decoration: none;"><?php _e('Import'); ?></a>
			<a href="<?php echo admin_url('admin.php?page=mediafromftp-search-register'); ?>" class="page-title-action"><?php _e('Search & Register', 'media-from-ftp'); ?></a>
			<a href="<?php echo admin_url('admin.php?page=mediafromftp-settings'); ?>" class="page-title-action"><?php _e('Settings'); ?></a>
			<a href="<?php echo admin_url('admin.php?page=mediafromftp-log'); ?>" class="page-title-action"><?php _e('Log', 'media-from-ftp'); ?></a>
		</h2>
		<div style="clear: both;"></div>

		<div id="mediafromftp-loading"><img src="<?php echo MEDIAFROMFTP_PLUGIN_URL.'/css/loading.gif'; ?>"></div>
		<div id="medialibraryimport-loading-container">

		<?php
		if ( isset($_POST['media_from_ftp_file_load']) && $_POST['media_from_ftp_file_load'] ) {
			if ( check_admin_referer('mff_file_load', 'media_from_ftp_file_load') ) {
				if ( !empty($_FILES['filename']['name']) ) {
					$filename = $_FILES['filename']['tmp_name'];
					$name = basename($filename);
					move_uploaded_file($filename, MEDIAFROMFTP_PLUGIN_TMP_DIR.'/'.$name);

					include_once MEDIAFROMFTP_PLUGIN_BASE_DIR.'/inc/MediaFromFtp.php';
					$mediafromftp = new MediaFromFtp();
					?>
					<h4><?php _e('Assign Authors', 'media-from-ftp'); ?></h4>
					<?php
					echo $mediafromftp->author_select(MEDIAFROMFTP_PLUGIN_TMP_DIR.'/'.$name);
				}
			}
		} else if ( isset($_POST['media_from_ftp_select_author']) && $_POST['media_from_ftp_select_author'] ) {
			if ( check_admin_referer('mff_select_author', 'media_from_ftp_select_author') ) {
				if ( !empty($_POST['mediafromftp_select_author']) && !empty($_POST['mediafromftp_xml_file']) ) {
					?>
					<h4><?php _e('Ready to import. Press the following button to start the import.', 'media-from-ftp'); ?></h4>
					<form method="post" id="medialibraryimport_ajax_update">
						<?php submit_button( __('Import'), 'primary', '', FALSE ); ?>
					</form>
					<?php
				}
			}
		} else {
			?>
			<form method="post" action="<?php echo $scriptname; ?>" enctype="multipart/form-data">
			<?php wp_nonce_field('mff_file_load', 'media_from_ftp_file_load'); ?>
			<h4><?php _e('Select File'); ?>[WordPress eXtended RSS (WXR)(.xml)]</h4>
			<div><input name="filename" type="file" size="80" /></div>
			<div>
			<?php submit_button( __('File Load', 'media-from-ftp'), 'large', '', FALSE ); ?>
			</div>
			</form>
			<?php
		}
		?>

		</div>
		</div>

		<?php
	}

	/* ==================================================
	 * Update wp_options table.
	 * @param	int		$submenu
	 * @since	2.36
	 */
	function options_updated($submenu){

		include_once MEDIAFROMFTP_PLUGIN_BASE_DIR.'/req/MediaFromFtpCron.php';
		$mediafromftpcron = new MediaFromFtpCron();

		include_once MEDIAFROMFTP_PLUGIN_BASE_DIR.'/inc/MediaFromFtp.php';
		$mediafromftp = new MediaFromFtp();

		$mediafromftp_settings = get_option($this->wp_options_name());

		switch ($submenu) {
			case 1:
				if ( !empty($_POST['mediafromftp_cron_apply']) ) {
					$mediafromftp_cron_apply = $_POST['mediafromftp_cron_apply'];
				} else {
					$mediafromftp_cron_apply = FALSE;
				}
				if ( !empty($_POST['mediafromftp_cron_limit_number']) ) {
					$mediafromftp_cron_limit_number = $_POST['mediafromftp_cron_limit_number'];
				} else {
					$mediafromftp_cron_limit_number = FALSE;
				}
				if ( !empty($_POST['mediafromftp_cron_mail_apply']) ) {
					$mediafromftp_cron_mail_apply = $_POST['mediafromftp_cron_mail_apply'];
				} else {
					$mediafromftp_cron_mail_apply = FALSE;
				}
				if ( !empty($_POST['mediafromftp_caption_apply']) ) {
					$mediafromftp_caption_apply = $_POST['mediafromftp_caption_apply'];
				} else {
					$mediafromftp_caption_apply = FALSE;
				}
				if ( !empty($_POST['mediafromftp_exif_text']) ) {
					$exif_text = $_POST['mediafromftp_exif_text'];
				} else {
					$exif_text = $mediafromftp_settings['caption']['exif_text'];
				}
				if ( !empty($_POST['mediafromftp_exif_default']) ) {
					$exif_text = '%title% %credit% %camera% %caption% %created_timestamp% %copyright% %aperture% %shutter_speed% %iso% %focal_length%';
				}
				if ( !empty($_POST['mediafromftp_apply_log']) ) {
					$mediafromftp_apply_log = $_POST['mediafromftp_apply_log'];
				} else {
					$mediafromftp_apply_log = FALSE;
				}
				if ( !empty($_POST['mediafromftp_search_limit_number']) ) {
					if ( ctype_digit($_POST['mediafromftp_search_limit_number']) ) {
						$search_limit_number = intval($_POST['mediafromftp_search_limit_number']);
						if ( $search_limit_number < 100 ) {
							$search_limit_number = 100;
						}
					} else {
						$search_limit_number = 100000;
					}
				} else {
					$search_limit_number = 100000;
				}
				$mediafromftp_tbl = array(
									'pagemax' => $mediafromftp_settings['pagemax'],
									'basedir' => $mediafromftp_settings['basedir'],
									'searchdir' => $mediafromftp_settings['searchdir'],
									'ext2typefilter' => $mediafromftp_settings['ext2typefilter'],
									'extfilter' => $mediafromftp_settings['extfilter'],
									'search_display_metadata' => $mediafromftp_settings['search_display_metadata'],
									'dateset' => $_POST['mediafromftp_dateset'],
									'max_execution_time' => intval($_POST['mediafromftp_max_execution_time']),
									'character_code' => $_POST['mediafromftp_character_code'],
									'exclude' => $mediafromftp_settings['exclude'],
									'thumb_deep_search' => $mediafromftp_settings['thumb_deep_search'],
									'search_limit_number' => $search_limit_number,
									'cron' => array(
												'apply' => $mediafromftp_cron_apply,
												'schedule' => $_POST['mediafromftp_cron_schedule'],
												'limit_number' => $mediafromftp_cron_limit_number,
												'mail_apply' => $mediafromftp_cron_mail_apply,
												'mail' => $mediafromftp_settings['cron']['mail'],
												'user' => $mediafromftp_settings['cron']['user']
												),
									'caption' => array(
												'apply' => $mediafromftp_caption_apply,
												'exif_text' => $exif_text
												),
									'log' => $mediafromftp_apply_log
									);
				update_option( $this->wp_options_name(), $mediafromftp_tbl );
				if ( !empty($_POST['move_yearmonth_folders']) ) {
					update_option( 'uploads_use_yearmonth_folders', $_POST['move_yearmonth_folders'] );
				} else {
					update_option( 'uploads_use_yearmonth_folders', '0' );
				}
				if ( !$mediafromftp_cron_apply ) {
					$mediafromftpcron->CronStop($this->wp_options_name());
				} else {
					$mediafromftpcron->CronStart($this->wp_options_name());
				}
				echo '<div class="notice notice-success is-dismissible"><ul><li>'.__('Settings').' --> '.__('Changes saved.').'</li></ul></div>';
				break;
			case 2:
				if (!empty($_POST['mediafromftp_pagemax'])){
					$pagemax = intval($_POST['mediafromftp_pagemax']);
				} else {
					$pagemax = $mediafromftp_settings['pagemax'];
				}
				$basedir = $mediafromftp_settings['basedir'];
				if (!empty($_POST['searchdir'])){
					$searchdir = urldecode($_POST['searchdir']);
				} else {
					$searchdir = $mediafromftp_settings['searchdir'];
					if ( MEDIAFROMFTP_PLUGIN_UPLOAD_PATH <> $basedir ) {
						$searchdir = MEDIAFROMFTP_PLUGIN_UPLOAD_PATH;
						$basedir = MEDIAFROMFTP_PLUGIN_UPLOAD_PATH;
					}
				}
				if (!empty($_POST['ext2type'])){
					$ext2typefilter = $_POST['ext2type'];
				} else {
					$ext2typefilter = $mediafromftp_settings['ext2typefilter'];
				}
				if (!empty($_POST['extension'])){
					if ( $_POST['extension'] === 'all') {
						$extfilter = 'all';
					} else {
						if ( $ext2typefilter === 'all' || $ext2typefilter === wp_ext2type($_POST['extension']) ) {
							$extfilter = $_POST['extension'];
						} else {
							$extfilter = 'all';
						}
					}
				} else {
					$extfilter = $mediafromftp_settings['extfilter'];
				}
				if (isset($_POST['search_display_metadata'])){
					$search_display_metadata = $_POST['search_display_metadata'];
				} else {
					$search_display_metadata = $mediafromftp_settings['search_display_metadata'];
				}
				if (!empty($_POST['mediafromftp_exclude'])){
					$mediafromftp_exclude = stripslashes($_POST['mediafromftp_exclude']);
				} else {
					$mediafromftp_exclude = $mediafromftp_settings['exclude'];
				}
				if (isset($_POST['mediafromftp_thumb_deep_search'])){
					$mediafromftp_thumb_deep_search = $_POST['mediafromftp_thumb_deep_search'];
				} else {
					$mediafromftp_thumb_deep_search = $mediafromftp_settings['thumb_deep_search'];
				}
				$mediafromftp_tbl = array(
									'pagemax' => $pagemax,
									'basedir' => $basedir,
									'searchdir' => $searchdir,
									'ext2typefilter' => $ext2typefilter,
									'extfilter' => $extfilter,
									'search_display_metadata' => $search_display_metadata,
									'dateset' => $mediafromftp_settings['dateset'],
									'max_execution_time' => $mediafromftp_settings['max_execution_time'],
									'character_code' => $mediafromftp_settings['character_code'],
									'exclude' => $mediafromftp_exclude,
									'thumb_deep_search' => $mediafromftp_thumb_deep_search,
									'search_limit_number' => $mediafromftp_settings['search_limit_number'],
									'cron' => array(
												'apply' => $mediafromftp_settings['cron']['apply'],
												'schedule' => $mediafromftp_settings['cron']['schedule'],
												'limit_number' => $mediafromftp_settings['cron']['limit_number'],
												'mail_apply' => $mediafromftp_settings['cron']['mail_apply'],
												'mail' => $mediafromftp_settings['cron']['mail'],
												'user' => $mediafromftp_settings['cron']['user']
												),
									'caption' => array(
													'apply' => $mediafromftp_settings['caption']['apply'],
													'exif_text' => $mediafromftp_settings['caption']['exif_text']
												),
									'log' => $mediafromftp_settings['log']
									);
				update_option( $this->wp_options_name(), $mediafromftp_tbl );
				break;
			case 3:
				if ( !empty($_POST['mediafromftp_clear_cash']) ) {
					$del_cash_count = $mediafromftp->delete_all_cash();
					if ( $del_cash_count > 0 ) {
						echo '<div class="notice notice-success is-dismissible"><ul><li>'.__('Thumbnails Cache', 'media-from-ftp').' --> '.__('Delete').'</li></ul></div>';
					} else {
						echo '<div class="notice notice-info is-dismissible"><ul><li>'.__('No Thumbnails Cache', 'media-from-ftp').'</li></ul></div>';
					}
				}
				break;
			case 4:
				if ( !empty($_POST['mediafromftp_run_cron']) ) {
					$cron_args = array( 'wp_options_name' => $this->wp_options_name() );
					$crons = _get_cron_array();
					foreach ( $crons as $time => $cron ) {
						foreach ( $cron as $procname => $task ) {
							if ( $procname === 'MediaFromFtpCronHook' ) {
								delete_transient( 'doing_cron' );
								wp_schedule_single_event( time() - 1, 'MediaFromFtpCronHook', $cron_args );
								spawn_cron();
								echo '<div class="notice notice-success is-dismissible"><ul><li>'.__('Schedule was executed.', 'media-from-ftp').'</li></ul></div>';
							}
						}
					}
				}
				break;
		}

	}

	/* ==================================================
	 * @param	none
	 * @return	string	$wp_options_name
	 * @since	9.18
	 */
	function wp_options_name(){

		$user = wp_get_current_user();
		$cron_user = $user->ID;

		$wp_options_name = 'mediafromftp_settings'.'_'.$cron_user;

		return $wp_options_name;

	}

}

?>