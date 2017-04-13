<?php
/**
 * Plugin Name: Stealth Publish
 * Version:     2.6
 * Plugin URI:  http://coffee2code.com/wp-plugins/stealth-publish/
 * Author:      Scott Reilly
 * Author URI:  http://coffee2code.com
 * Text Domain: stealth-publish
 * License:     GPLv2 or later
 * License URI: http://www.gnu.org/licenses/gpl-2.0.html
 * Description: Prevent specified posts from being featured on the front page or in feeds, and from notifying external services of publication.
 *
 * Compatible with WordPress 3.6+ through 4.5+
 *
 * TODO:
 * - Split functionality into separate checkboxes:
 * - Hide from front page
 * - Hide from feeds
 * - Don't prevent a stealth post from being stealthy if it is explicitly
 *    requested (by ID) in the query
 * - Add functions stealthify_post( $post_id ), unstealthify_post( $post_id, $delete = false ),
 *   is_stealth_post( $post_id )
 *
 * =>> Read the accompanying readme.txt file for instructions and documentation.
 * =>> Also, visit the plugin's homepage for additional information and updates.
 * =>> Or visit: https://wordpress.org/plugins/stealth-publish/
 *
 * @package Stealth_Publish
 * @author  Scott Reilly
 * @version 2.6
*/

/*
	Copyright (c) 2007-2016 by Scott Reilly (aka coffee2code)

	This program is free software; you can redistribute it and/or
	modify it under the terms of the GNU General Public License
	as published by the Free Software Foundation; either version 2
	of the License, or (at your option) any later version.

	This program is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
	GNU General Public License for more details.

	You should have received a copy of the GNU General Public License
	along with this program; if not, write to the Free Software
	Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
*/

defined( 'ABSPATH' ) or die();

if ( ! class_exists( 'c2c_StealthPublish' ) ) :

class c2c_StealthPublish {

	/**
	 * The name of the associated form field.
	 *
	 * @access private
	 * @var string
	 */
	private static $field          = 'stealth_publish';

	/**
	 * The name of the post meta key.
	 *
	 * Note: Filterable via 'c2c_stealth_publish_meta_key' filter.
	 *
	 * @access private
	 * @var string
	 */
	private static $meta_key       = '_stealth-publish';

	/**
	 * The name of the transient.
	 *
	 * @access private
	 * @var string
	 */
	private static $transient_name = 'c2c_stealh_publish_stealth_ids';

	/**
	 * Returns version of the plugin.
	 *
	 * @since 2.2.1
	 */
	public static function version() {
		return '2.6';
	}

	/**
	 * Initializer
	 */
	public static function init() {
		add_action( 'init', array( __CLASS__, 'do_init' ) );
	}

	/**
	 * Resets any cached values.
	 *
	 * @since 2.4
	 */
	public static function reset() {
		delete_transient( self::$transient_name );
	}

	/**
	 * Registers actions/filters and allow for configuration.
	 *
	 * @since 2.0
	 * @uses apply_filters() Calls 'c2c_stealth_publish_meta_key' with default meta key name.
	 */
	public static function do_init() {

		// Load textdomain.
		load_plugin_textdomain( 'stealth-publish' );

		// Deprecated as of 2.3.
		$meta_key = apply_filters( 'stealth_publish_meta_key', self::$meta_key );

		// Apply custom filter to obtain meta key name.
		$meta_key = apply_filters( 'c2c_stealth_publish_meta_key', $meta_key );

		// Only override the meta key name if one was specified. Otherwise the
		// default remains (since a meta key is necessary).
		if ( $meta_key ) {
			self::$meta_key = $meta_key;
		}

		// Register hooks.
		add_filter( 'posts_where',                 array( __CLASS__, 'stealth_publish_where' ), 1, 2 );
		//add_action( 'pre_get_posts',               array( __CLASS__, 'exclude_stealth_posts' ), 1000 );
		add_action( 'post_submitbox_misc_actions', array( __CLASS__, 'add_ui' ) );
		add_filter( 'wp_insert_post_data',         array( __CLASS__, 'save_stealth_publish_status' ), 2, 2 );
		add_action( 'publish_post',                array( __CLASS__, 'publish_post' ), 1, 1 );

		add_action( 'quick_edit_custom_box',       array( __CLASS__, 'add_to_quick_edit' ), 10, 2 );
		add_action( 'admin_enqueue_scripts',       array( __CLASS__, 'admin_enqueue_scripts' ) );
		add_filter( 'post_date_column_time',       array( __CLASS__, 'add_icon_to_post_date_column' ), 10, 4 );

	}

	/**
	 * Outputs a dashicon lock if the post is configured to be stealth updated.
	 *
	 * @since 2.6
	 *
	 * @param string  $t_time      The published time.
	 * @param WP_Post $post        Post object.
	 * @param string  $column_name The column name.
	 * @param string  $mode        The list display mode ('excerpt' or 'list').
	 */
	public static function add_icon_to_post_date_column( $h_time, $post, $column_name, $mode ) {
		echo $h_time;

		if ( get_post_meta( $post->ID, self::$meta_key, true ) ) {
			echo ' <span class="' . esc_attr( self::$field ) . ' dashicons dashicons-hidden" title="' . esc_attr__( 'Post has stealth publish enabled.', 'stealth-publish' ) . '"></span>';
		}
	}

	/**
	 * Enqueues the admin JS.
	 *
	 * @since 2.6
	 *
	 * @param string $hook_name The hook (aka page) name.
	 */
	public static function admin_enqueue_scripts( $hook_name ) {
		if ( 'edit.php' !== $hook_name ) {
			return;
		}

		wp_enqueue_script( self::$field, plugins_url( 'assets/admin.js', __FILE__ ), array( 'jquery' ), self::version(), true );
	}

	/**
	 * Should stealth posts be excluded in current context?
	 *
	 * Checks if the query is being performed on the home page or a feed.
	 *
	 * @since 2.4
	 *
	 * @param  WP_Query $wp_query Query object.
	 *
	 * @return bool     If true, then stealth posts should be excluded.
	 */
	private static function should_exclude_stealth_posts( $wp_query ) {
		$siteurl = explode( '://', get_option( 'siteurl' ) );
		$siteurl = array_pop( $siteurl );

		return (
			$wp_query->is_home ||
			$wp_query->is_feed ||
			$wp_query->is_front_page() ||
			( trailingslashit( $siteurl ) == trailingslashit( $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'] ) )
		);
	}

	/**
	 * Excludes stealth posts where appropriate.
	 *
	 * If no meta_query is defined, it defines one to only grab non-stealth
	 * posts in the original query. Otherwise, it hooks posts_where to make
	 * a separate query for all stealth post IDs and adds them as NOT IN
	 * values for the query.
	 *
	 * @since 2.4
	 * @deprecated 2.5 No longer actively used due to occasional interference with custom queries.
	 *
	 * @param WP_Query Query object.
	 */
	public static function exclude_stealth_posts( $wp_query ) {
		remove_filter( 'posts_where', array( __CLASS__, 'stealth_publish_where' ), 1, 2 );

		if ( self::should_exclude_stealth_posts( $wp_query ) ) {
			// If there isn't an existing meta_query, then one can be defined to
			// limit the query to non-stealth posts.
			if ( empty( $wp_query->query_vars['meta_query'] ) ) {
				$wp_query->query_vars['meta_query'] = array(
					'relation' => 'OR',
					array(
						'key'     => self::$meta_key,
						'value'   => '', // This is needed to work around core bug #23268
						'compare' => 'NOT EXISTS',
					),
					array(
						'key'     => self::$meta_key,
						'value'   => '1',
						'compare' => '!=',
					)
				);
			// Else if a meta_query exists, we have to hook 'posts_where' and
			// perform a separate query to get the stealth post IDs.
			} else {
				add_filter( 'posts_where', array( __CLASS__, 'stealth_publish_where' ), 1, 2 );
			}
		}
	}

	/**
	 * Draws the UI to prompt user if stealth publish should be enabled for the post.
	 *
	 * @since 2.0
	 * @uses apply_filters() Calls 'c2c_stealth_publish_default' with stealth publish state default (false).
	 */
	public static function add_ui() {
		global $post;

		if ( apply_filters( 'c2c_stealth_publish_default', false, $post ) ) {
			$value = '1';
		} else {
			$value = get_post_meta( $post->ID, self::$meta_key, true );
		}

		$checked = checked( $value, '1', false );

		echo "<div class='misc-pub-section'><label class='selectit c2c-stealth-publish' for='" . esc_attr( self::$field ) . "' title='";
		esc_attr_e( 'If checked, the post will not appear on the front page or in the main feed.', 'stealth-publish' );
		echo "'>\n";
		echo "<input id='" . esc_attr( self::$field ) . "' type='checkbox' $checked value='1' name='" . esc_attr( self::$field ) . "' />\n";
		_e( 'Stealth publish?', 'stealth-publish' );
		echo '</label></div>' . "\n";
	}

	/**
	 * Adds the checkbox to the quick edit panel.
	 *
	 * @since 2.6
	 *
	 * @param string $column_name Name of the column being output to quick edit.
	 * @param string $post_type   The post type of the post.
	 */
	public static function add_to_quick_edit( $column_name, $post_type ) {
		if ( did_action( 'quick_edit_custom_box' ) > 1 ) {
			return;
		}

		self::add_ui();
	}

	/**
	 * Updates the value of the stealth publish custom field.
	 *
	 * @since 2.0
	 *
	 * @param array  $data    Data.
	 * @param array  $postarr Array of post fields and values for post being saved.
	 *
	 * @return array The unmodified $data.
	 */
	public static function save_stealth_publish_status( $data, $postarr ) {
		if ( isset( $postarr['post_type'] ) &&
			 ( 'revision' != $postarr['post_type'] ) &&
			 ! ( isset( $_POST['action'] ) && 'inline-save' == $_POST['action'] )
			) {
			// Update the value of the stealth update custom field.
			if ( isset( $postarr[ self::$field ] ) && $postarr[ self::$field ] ) {
				update_post_meta( $postarr['ID'], self::$meta_key, '1' );
			} else {
				delete_post_meta( $postarr['ID'], self::$meta_key );
			}

			// Reset cached values.
			self::reset();
		}

		return $data;
	}

	/**
	 * Returns and caches an array of post IDs that are to be stealth published.
	 *
	 * @since 1.0
	 *
	 * @return array Post IDs of all stealth published posts.
	 */
	public static function find_stealth_published_post_ids() {
		if ( false === ( $stealth_published_posts = get_transient( self::$transient_name ) ) ) {
			global $wpdb;

			$sql = "SELECT DISTINCT ID FROM $wpdb->posts AS p
					LEFT JOIN $wpdb->postmeta AS pm ON (p.ID = pm.post_id)
					WHERE pm.meta_key = %s AND pm.meta_value = '1'
					GROUP BY pm.post_id";
			$stealth_published_posts = $wpdb->get_col( $wpdb->prepare( $sql, self::$meta_key ) );

			set_transient( self::$transient_name, $stealth_published_posts, 12 * HOUR_IN_SECONDS );
		}

		return $stealth_published_posts;
	}

	/**
	 * Modifies the WP query to exclude stealth published posts from feeds and the home page.
	 *
	 * @since 1.0
	 *
	 * @param string   $where    The current WHERE condition string.
	 * @param WP_Query $wp_query The query object (not provided by WP prior to WP 3.0).
	 *
	 * @return string  The potentially amended WHERE condition string to exclude stealth published posts.
	 */
	public static function stealth_publish_where( $where, $wp_query = null ) {
		global $wpdb;

		if ( ! $wp_query ) {
			global $wp_query;
		}

		if ( self::should_exclude_stealth_posts( $wp_query ) ) {
			$stealth_published_posts = implode( ',', self::find_stealth_published_post_ids() );
			if ( $stealth_published_posts ) {
				$where .= " AND {$wpdb->posts}.ID NOT IN ( {$stealth_published_posts} )";
			}
		}
		return $where;
	}

	/**
	 * Handles silent publishing if the associated checkbox is checked.
	 *
	 * @since 2.0
	 * @uses apply_filters() Calls 'c2c_stealth_publish_silent' with stealth publish silent state default (true).
	 *
	 * @param int $post_id Post ID.
	 */
	public static function publish_post( $post_id ) {
		// Deprecated as of 2.3.
		$stealth_publish_silent = (bool) apply_filters( 'stealth_publish_silent', true, $post_id );

		// Trick WP into being silent by invoking its import mode.
		if ( isset( $_POST[ self::$field ] ) && $_POST[ self::$field ] && (bool) apply_filters( 'c2c_stealth_publish_silent', $stealth_publish_silent, $post_id ) ) {
			define( 'WP_IMPORTING', true );
		}
	}

} // end class

c2c_StealthPublish::init();

endif;
