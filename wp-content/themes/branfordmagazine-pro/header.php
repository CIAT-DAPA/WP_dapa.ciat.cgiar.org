<?php load_theme_textdomain('branfordmagazine'); ?>
<?php include (TEMPLATEPATH.'/tools/get-theme-options.php'); 
//check for correct WP version	
global $wp_version;
if ( !version_compare( $wp_version, '3.0-beta', '>=' ) ) {
    wp_die( 'This Theme needs at least WordPress 3.0. Please update your Wordpress installation.', 'Update WordPress!');
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" <?php language_attributes(); ?>>
<head profile="http://gmpg.org/xfn/11">
<meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>" />
<?php 
echo '	<title>';
if ( is_home() ) {
	// Blog's Home
	echo get_bloginfo('name') . '  &raquo; '; bloginfo('description') ; 
} elseif ( is_single() or is_page() ) {
	// Single blog post or page
	wp_title(''); echo ' - ' . get_bloginfo('name');
} elseif ( is_category() ) {
	// Archive: Category
	echo get_bloginfo('name') . ' &raquo;  '; single_cat_title();
} elseif ( is_day() ) {
	// Archive: By day
	echo get_bloginfo('name') . ' &raquo; ' . get_the_time('d') . '. ' . get_the_time('F') . ' ' . get_the_time('Y');
} elseif ( is_month() ) {
	// Archive: By month
	echo get_bloginfo('name') . ' &raquo; ' . get_the_time('F') . ' ' . get_the_time('Y');
} elseif ( is_year() ) {
	// Archive: By year
	echo get_bloginfo('name') . ' &raquo; ' . get_the_time('Y');
} elseif ( is_search() ) {
	// Search
	echo get_bloginfo('name') . ' &raquo; &lsaquo;' . wp_specialchars($s, 1) . '&rsaquo;';
} elseif ( is_404() ) {
	// 404
	echo get_bloginfo('name') . '  &raquo; 404 - ERROR';
} else {
	// Everything else. Fallback
	bloginfo('name'); wp_title();
}
echo '</title>';
?>
<link rel="stylesheet" type="text/css" media="screen" href="<?php bloginfo('stylesheet_url'); ?>" />
<link rel="stylesheet" type="text/css" media="screen" href="<?php bloginfo('template_url'); ?>/styles/nav.css" />
<link rel="stylesheet" type="text/css" media="screen" href="<?php bloginfo('template_url'); ?>/styles/plugins.css" />
<link rel="stylesheet" type="text/css" media="screen" href="<?php bloginfo('template_url'); ?>/styles/template-style.css" />
<link rel="stylesheet" type="text/css" media="print" href="<?php bloginfo('template_url'); ?>/styles/print.css" />
<link rel="stylesheet" type="text/css" media="screen" href="<?php bloginfo('template_url'); ?>/styles/ui.tabs.css" />
<link rel="stylesheet" type="text/css" media="screen" href="<?php bloginfo('template_url'); ?>/styles/custom-style.css" />

<!-- leave this for stats -->
<?php wp_head(); ?>
<link rel="alternate" type="application/rss+xml" title="<?php bloginfo('name'); ?> RSS Feed" href="<?php bloginfo('rss2_url'); ?>" />
<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />
</head>
<body <?php body_class(); ?>>
<div id="page" class="clearfloat">
<div class="clearfloat">
  <div id="logo" class="left" onclick="location.href='<?php echo get_settings('home'); ?>';" style="cursor: pointer;">
    <?php  
   if ($prinz_nologo == "false") { ?>
    <img style="border:none;" src="<?php header_image(); ?>" width="<?php echo HEADER_IMAGE_WIDTH; ?>" height="<?php echo HEADER_IMAGE_HEIGHT; ?>" alt="<?php bloginfo('name'); ?> - <?php bloginfo('description'); ?>"title="<?php bloginfo('name'); ?> - <?php bloginfo('description'); ?>" border="none" />
    <?php } else { ?>
    <div class="blogtitle" ><a href="<?php echo get_option('home'); ?>/">
      <?php bloginfo('name'); ?>
      </a></div>
    <div class="description">
      <?php bloginfo('description'); ?>
    </div>
    <?php } ?>
  </div>
  <div class="right">
    <?php include (TEMPLATEPATH . '/searchform.php'); // the searchform ?>
  </div>
</div>
<? if ($prinz_pagemenuon == "false") { // the horizontal page menu ?>
<ul id="nav" class="clearfloat">
  <li><a href="<?php echo get_option('home'); ?>/" class="on">Home</a></li>
  <?php wp_list_pages("exclude=$prinz_menuepages;&title_li="); ?>
</ul>
<?php } ?>
<? if ($prinz_catmenuon == "false") { // the horizontal categories menu ?>
<ul id="catnav" class="clearfloat">
  <?php wp_list_categories("exclude=$prinz_menuecats;&title_li="); ?>
</ul>
<?php } ?>
<? if ($prinz_wpmenuon == "true") { //the horizontal custom Wordpress menu (WP 3.0 or higher required) ?>
<div id="wp_nav_menu">
	<?php wp_nav_menu( array( 'sort_column' => 'menu_order', 'container_class' => 'menu-header', 'theme_location' => 'primary' ) ); ?>
</div>
<?php } ?>