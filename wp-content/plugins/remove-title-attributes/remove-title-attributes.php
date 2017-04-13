<?php
/*
Plugin Name: Remove Title Attributes
Plugin URI: http://www.technokinetics.com/plugins/remove-title-attributes/
Description: Improves accessibility by removing the redundant title attributes that WordPress automatically adds to your website's Page lists, category lists, archives, and tag clouds.
Version: 1.0
Author: Tim Holt
Author URI: http://www.technokinetics.com/

    Copyright 2009 Tim Holt (tim@technokinetics.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

register_activation_hook(__FILE__,'remove_title_attributes_install');
register_deactivation_hook( __FILE__, 'remove_title_attributes_uninstall' );
add_action('admin_menu', 'add_remove_title_attributes_admin_menu');

// ACTIVATION
function remove_title_attributes_install() {
	if (!get_option('rta_from_page_lists')) {
		add_option('rta_from_page_lists', 'on');
	}
	if (!get_option('rta_from_category_lists')) {
		add_option('rta_from_category_lists', 'on');
	}
	if (!get_option('rta_from_archive_links')) {
		add_option('rta_from_archive_links', 'on');
	}
	if (!get_option('rta_from_tag_clouds')) {
		add_option('rta_from_tag_clouds', 'on');
	}
	if (!get_option('rta_from_category_links')) {
		add_option('rta_from_category_links', 'on');
	}
	if (!get_option('rta_from_post_edit_links')) {
		add_option('rta_from_post_edit_links', 'on');
	}
	if (!get_option('rta_from_edit_comment_links')) {
		add_option('rta_from_edit_comment_links', 'on');
	}
}

// DEACTIVATION
function remove_title_attributes_uninstall() {
	if (get_option('rta_delete_data_on_deactivation') == 'on') {
		delete_option('rta_from_page_lists');
		delete_option('rta_from_category_lists');
		delete_option('rta_from_archive_links');
		delete_option('rta_from_tag_clouds');
		delete_option('rta_from_category_links');
		delete_option('rta_from_post_edit_links');
		delete_option('rta_from_edit_comment_links');
		delete_option('rta_delete_data_on_deactivation');
	}
}

// ADMIN MENU
function add_remove_title_attributes_admin_menu() {
	add_options_page('Remove Title Attributes', 'Remove Title Attributes', 'activate_plugins', __FILE__, 'remove_title_attributes_admin_menu');
}

function remove_title_attributes_admin_menu() { ?>
	<div id="remove_title_attributes" class="wrap">
		<h2>Remove Title Attributes Options</h2>
		<form method="post" action="options.php">
			<p>Remove title attributes from...</p>
			<ul style="list-style: none;">
				<li><input type="checkbox" id="rta_from_page_lists" name="rta_from_page_lists" <?php if (get_option('rta_from_page_lists') == "on") { echo 'checked="checked" '; } ?>/><label for="rta_from_page_lists">Page lists</label></li>
				<li><input type="checkbox" id="rta_from_category_lists" name="rta_from_category_lists" <?php if (get_option('rta_from_category_lists') == "on") { echo 'checked="checked" '; } ?>/><label for="rta_from_category_lists">Category lists</label></li>
				<li><input type="checkbox" id="rta_from_archive_links" name="rta_from_archive_links" <?php if (get_option('rta_from_archive_links') == "on") { echo 'checked="checked" '; } ?>/><label for="rta_from_archive_links">Archive links</label></li>
				<li><input type="checkbox" id="rta_from_tag_clouds" name="rta_from_tag_clouds" <?php if (get_option('rta_from_tag_clouds') == "on") { echo 'checked="checked" '; } ?>/><label for="rta_from_tag_clouds">Tag clouds</label></li>
				<li><input type="checkbox" id="rta_from_category_links" name="rta_from_category_links" <?php if (get_option('rta_from_category_links') == "on") { echo 'checked="checked" '; } ?>/><label for="rta_from_category_links">Category links</label></li>
				<li><input type="checkbox" id="rta_from_post_edit_links" name="rta_from_post_edit_links" <?php if (get_option('rta_from_post_edit_links') == "on") { echo 'checked="checked" '; } ?>/><label for="rta_from_post_edit_links">Post edit links</label></li>
				<li><input type="checkbox" id="rta_from_edit_comment_links" name="rta_from_edit_comment_links" <?php if (get_option('rta_from_edit_comment_links') == "on") { echo 'checked="checked" '; } ?>/><label for="rta_from_edit_comment_links">Edit comment links</label></li>
			</ul>
			<h3>Plugin Options</h3>
			<ul style="list-style: none;">
				<li><input type="checkbox" id="rta_delete_data_on_deactivation" name="rta_delete_data_on_deactivation" <?php if (get_option('rta_delete_data_on_deactivation') == "on") { echo 'checked="checked" '; } ?>/> <label for="rta_delete_data_on_deactivation">Delete plugin data on deactivation</label></li>
			</ul>
			<h3>Feedback</h3>
			<p>If you've found Remove Title Attributes useful, then please consider <a href="http://wordpress.org/extend/plugins/remove-title-attributes/">rating it</a>, linking to <a href="http://www.technokinetics.com/">my website</a>, or <a href="http://www.technokinetics.com/donations/">making a donation</a>.</p>
			<p>If you haven't found it useful, then please consider <a href="mailto:tim@technokinetics.com?subject=RTA Bug Report">filing a bug report</a> or <a href="mailto:tim@technokinetics.com?subject=RTA Feature Request">making a feature request</a>.</p>
			<p>Thanks!</p>
			<p>- Tim Holt, <a href="http://www.technokinetics.com/">Technokinetics</a></p>
			<p>
				<?php wp_nonce_field('update-options'); ?>
				<input type="hidden" name="action" value="update" />
				<input type="hidden" name="page_options" value="rta_from_page_lists,rta_from_category_lists,rta_from_archive_links,rta_from_tag_clouds,rta_from_category_links,rta_from_post_edit_links,rta_from_edit_comment_links,rta_delete_data_on_deactivation" />
			</p>
			<p class="submit"><input type="submit" name="Submit" value="<?php _e('Save Changes') ?>" /></p>
		</form>		
	</div><?php
}

// For Page lists
function wp_list_pages_remove_title_attributes($output) {
	if (get_option('rta_from_page_lists') == 'on') {
		$output = preg_replace('` title="(.+)"`', '', $output);
	}
	return $output;
}
add_filter('wp_list_pages', 'wp_list_pages_remove_title_attributes');

// For category lists
function wp_list_categories_remove_title_attributes($output) {
	if (get_option('rta_from_category_lists') == 'on') {
		$output = preg_replace('` title="(.+)"`', '', $output);
	}
	return $output;
}
add_filter('wp_list_categories', 'wp_list_categories_remove_title_attributes');

// For archives
function get_archives_link_remove_title_attributes($link_html) {
	if (get_option('rta_from_archive_links') == 'on') {
		// N.B. This function uses single quotes
		$link_html = preg_replace("` title='(.+)'`", "", $link_html);
	}
	return $link_html;
}
add_filter('get_archives_link', 'get_archives_link_remove_title_attributes');

// For tag clouds
function wp_tag_cloud_remove_title_attributes($return) {
	if (get_option('rta_from_tag_clouds') == 'on') {
		// N.B. This function uses single quotes
		$return = preg_replace("` title='(.+)'`", "", $return);
	}
	return $return;
}
add_filter('wp_tag_cloud', 'wp_tag_cloud_remove_title_attributes');

// For post category links
function the_category_remove_title_attributes($thelist) {
	if (get_option('rta_from_category_links') == 'on') {
		$thelist = preg_replace('` title="(.+)"`', '', $thelist);
	}
	return $thelist;
}
add_filter('the_category', 'the_category_remove_title_attributes');

// For post edit links
function edit_post_link_remove_title_attributes($link) {
	if (get_option('rta_from_post_edit_links') == 'on') {
		$link = preg_replace('` title="(.+)"`', '', $link);
	}
	return $link;
}
add_filter('edit_post_link', 'edit_post_link_remove_title_attributes');

// For edit comment links
function edit_comment_link_remove_title_attributes($link) {
	if (get_option('rta_from_edit_comment_links') == 'on') {
		$link = preg_replace('` title="(.+)"`', '', $link);
	}
	return $link;
}
add_filter('edit_comment_link', 'edit_comment_link_remove_title_attributes');

// The built-in Recent Posts widget hard-codes title attributes. This duplicate widget doesn't.
class WP_Widget_Recent_Posts_No_Title_Attributes extends WP_Widget {

	function __construct() {
		$widget_ops = array('classname' => 'widget_recent_entries', 'description' => __( "The most recent posts on your blog") );
		parent::__construct('recent-posts-no-title-attributes', __('Recent Posts (No Title Attributes)'), $widget_ops);
		$this->alt_option_name = 'widget_recent_entries';

		add_action( 'save_post', array(&$this, 'flush_widget_cache') );
		add_action( 'deleted_post', array(&$this, 'flush_widget_cache') );
		add_action( 'switch_theme', array(&$this, 'flush_widget_cache') );
	}

	function widget($args, $instance) {
		$cache = wp_cache_get('widget_recent_posts', 'widget');

		if ( !is_array($cache) )
			$cache = array();

		if ( isset($cache[$args['widget_id']]) ) {
			echo $cache[$args['widget_id']];
			return;
		}

		ob_start();
		extract($args);

		$title = apply_filters('widget_title', empty($instance['title']) ? __('Recent Posts') : $instance['title']);
		if ( !$number = (int) $instance['number'] )
			$number = 10;
		else if ( $number < 1 )
			$number = 1;
		else if ( $number > 15 )
			$number = 15;

		$r = new WP_Query(array('showposts' => $number, 'nopaging' => 0, 'post_status' => 'publish', 'caller_get_posts' => 1));
		if ($r->have_posts()) :
?>
		<?php echo $before_widget; ?>
		<?php if ( $title ) echo $before_title . $title . $after_title; ?>
		<ul>
		<?php  while ($r->have_posts()) : $r->the_post(); ?>
		<li><a href="<?php the_permalink() ?>"><?php if ( get_the_title() ) the_title(); else the_ID(); ?> </a></li>
		<?php endwhile; ?>
		</ul>
		<?php echo $after_widget; ?>
<?php
			wp_reset_query();  // Restore global post data stomped by the_post().
		endif;

		$cache[$args['widget_id']] = ob_get_flush();
		wp_cache_add('widget_recent_posts', $cache, 'widget');
	}

	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['number'] = (int) $new_instance['number'];
		$this->flush_widget_cache();

		$alloptions = wp_cache_get( 'alloptions', 'options' );
		if ( isset($alloptions['widget_recent_entries']) )
			delete_option('widget_recent_entries');

		return $instance;
	}

	function flush_widget_cache() {
		wp_cache_delete('widget_recent_posts', 'widget');
	}

	function form( $instance ) {
		$title = esc_attr($instance['title']);
		if ( !$number = (int) $instance['number'] )
			$number = 5;
?>
		<p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" /></p>

		<p><label for="<?php echo $this->get_field_id('number'); ?>"><?php _e('Number of posts to show:'); ?></label>
		<input id="<?php echo $this->get_field_id('number'); ?>" name="<?php echo $this->get_field_name('number'); ?>" type="text" value="<?php echo $number; ?>" size="3" /><br />
		<small><?php _e('(at most 15)'); ?></small></p>
<?php
	}
}
function remove_title_attributes_widgets_init() {
	register_widget('WP_Widget_Recent_Posts_No_Title_Attributes');
}
add_action('init', 'remove_title_attributes_widgets_init', 1);
?>
