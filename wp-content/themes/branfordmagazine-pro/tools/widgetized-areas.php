<?php
add_action( 'init', 'register_my_widget_areas' );

function register_my_widget_areas() {

// Regular widgetized sidebar
register_sidebar(array(
	'name' =>'Regular Sidebar',
	'id' => 'regular',
	'description' => __('The regular sidebar on the right side of the pages.', 'branfordmagazine'),
	'before_widget' => '<div class="sidebar_widget">',
	'after_widget' => '</div>',
	'before_title' => '<h3>',
	'after_title' => '</h3>',
	));


// FOOTER WIDGETS //

register_sidebar(array(
	'name'=>'Footer Left',
	'id' => 'footerleft',
	'description' => __('The widgetized area of the alternative Footer', 'branfordmagazine'),
	'before_widget' => '<div class="sidebar_widget">',
	'after_widget' => '</div>',
	'before_title' => '<h4>',
	'after_title' => '</h4>',
	));

register_sidebar(array(
	'name'=>'Footer Middle 1',
	'id' => 'footermiddle1',
	'description' => __('The widgetized area of the alternative Footer', 'branfordmagazine'),
	'before_widget' => '<div class="sidebar_widget">',
	'after_widget' => '</div>',
	'before_title' => '<h4>',
	'after_title' => '</h4>',
	));
	
register_sidebar(array(
	'name'=>'Footer Middle 2',
	'id' => 'footermiddle2',
	'description' => __('The widgetized area of the alternative Footer', 'branfordmagazine'),
	'before_widget' => '<div class="sidebar_widget">',
	'after_widget' => '</div>',
	'before_title' => '<h4>',
	'after_title' => '</h4>',
	));
	
register_sidebar(array(
	'name'=>'Footer Right',
	'id' => 'footerright',
	'description' => __('The widgetized area of the alternative Footer', 'branfordmagazine'),
	'before_widget' => '<div class="sidebar_widget">',
	'after_widget' => '</div>',
	'before_title' => '<h4>',
	'after_title' => '</h4>',
	));


//FEATURED PAGE TEMPLATE //

// Textwidgets in featured page template
register_sidebar(array(
	'name'=>'Featured Page Textwidgets',
	'id' => 'featuredtext',
	'description' => __('The sidebar of the featured page right to the main content. A good position to put textwidgets.', 'branfordmagazine'),
	'before_widget' => '<div class="sidebar_widget">',
	'after_widget' => '</div>',
	'before_title' => '<h3>',
	'after_title' => '</h3>',
	));

// Topright area in featured page template
register_sidebar(array(
	'name'=>'Featured Page Topright',
	'id' => 'featuredtop',
	'description' => __('The featured page topright sidebar where the 5 most recent posts are located by default.', 'branfordmagazine'),
	'before_widget' => '<div class="sidebar_widget">',
	'after_widget' => '</div>',
	'before_title' => '<h3>',
	'after_title' => '</h3>',
	));


// 3-COLUMN PAGE TEMPLATE //

// 3 column page template left side
register_sidebar(array(
	'name'=>'3-Col Page Left',
	'id' => '3-col-left',
	'description' => __('The widget area in the 3-columns page template left column.', 'branfordmagazine'),
	'before_widget' => '<div class="sidebar_widget">',
	'after_widget' => '</div>',
	'before_title' => '<h3>',
	'after_title' => '</h3>',
	));

// 3 column page template middle
register_sidebar(array(
	'name'=>'3-Col Page Middle',
	'id' => '3-col-mid',
	'description' => __('The widget area in the 3-columns page template middle column.', 'branfordmagazine'),
	'before_widget' => '<div class="sidebar_widget">',
	'after_widget' => '</div>',
	'before_title' => '<h3>',
	'after_title' => '</h3>',
	));

// 3 column page template right side
register_sidebar(array(
	'name'=>'3-Col Page Right',
	'id' => '3-col-right',
	'description' => __('The widget area in the 3-columns page template right column.', 'branfordmagazine'),
	'before_widget' => '<div class="sidebar_widget">',
	'after_widget' => '</div>',
	'before_title' => '<h3>',
	'after_title' => '</h3>',
	));
} 
?>