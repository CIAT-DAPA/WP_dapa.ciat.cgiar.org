<?php
// New WordPress 3.0 stuff

// This theme uses post thumbnails
add_theme_support( 'post-thumbnails' );

// This theme uses wp_nav_menu() in one location.
	register_nav_menus( array(
		'primary' => __( 'Primary Navigation', 'branfordmagazine' ),
	) );

// This theme allows users to set a custom background
add_custom_background();

// Custom header image
define( 'HEADER_IMAGE', '%s/images/logo.png' ); // The default logo located in themes folder
define( 'HEADER_IMAGE_WIDTH', apply_filters( '', 625 ) ); // Width of Logo
define( 'HEADER_IMAGE_HEIGHT', apply_filters( '', 100 ) ); // Height of Logo
define( 'NO_HEADER_TEXT', true );
add_custom_image_header( '', 'admin_header_style' ); // This Enables the Appearance > Header
// Following Code is for Styling the Admin Side
if ( ! function_exists( 'admin_header_style' ) ) :
function admin_header_style() {
?>
<style type="text/css">
#headimg {
height: <?php echo HEADER_IMAGE_HEIGHT; ?>px;
width: <?php echo HEADER_IMAGE_WIDTH; ?>px;
}
#headimg h1, #headimg #desc {
display: none;
}
</style>
<?php
}
endif;
?>
