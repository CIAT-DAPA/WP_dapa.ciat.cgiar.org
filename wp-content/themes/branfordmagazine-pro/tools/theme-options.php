<?php

$themename = "BranfordMagazine";
$shortname = "prinz";

// Create theme options

$options = array (
				  
				  array( "name" => $themename." Options",
					"type" => "title"),


				array( 	"name" => "Setup the Header",
						"desc" => "Choose if you like to use a Logo or your Blogname and Description in the Header. By default the Logo is used. You can change the Logo in the \"Header\" Section of your Wordpress Administration (Wordpress 3.0 or higher required).",
						"type" => "section"),
				array( 	"type" => "open"),
							
				array(	"name" => "Header Logo or Blogname?",
						"desc" => "Check this if you want to use your Blogname and Description instead of a Logo (default = Logo).",
						"id" => $shortname."_nologo",
						"std" => "false",
						"type" => "checkbox"),
						
				array( 	"type" => "close"),
				
				
				array( 	"name" => "Setup Pages and Categories Menue",
						"type" => "section"),
				array( 	"type" => "open"),
							
				array(	"name" => "Remove default Page Menu",
						"desc" => "Check this if you want to REMOVE the horizontal page menu",
						"id" => $shortname."_pagemenuon",
						"std" => "false",
						"type" => "checkbox"),
						
				array(	"name" => __('Exclude Pages'),
						"desc" => __("Enter the IDs of the pages you want to EXCLUDE from the page menue bar"),
						"id" => $shortname."_menuepages",
						"std" => __(""),
						"type" => "text"),
				
				array(	"name" => __('Remove default Category Menu'),
						"desc" => __("Check this if you want to REMOVE the horizontal category menu"),
						"id" => $shortname."_catmenuon",
						"std" => __("false"),
						"type" => "checkbox"),
						
				array(	"name" => __('Exclude Categories'),
						"desc" => __("Enter the IDs of the categories you want to EXCLUDE from the categories menue bar.<br />The default ID 9999 is a workaround because it doesn´t work if no ID is put here. So leave it or change it to your own IDs but don´t leave it blank. "),
						"id" => $shortname."_menuecats",
						"std" => __("9999"),
						"type" => "text"),
				
				array(	"name" => __('Use custom WordPress Menu'),
						"desc" => __("Check this if you want to USE the WordPress custom menu function in the horizontal menu-bar. You need to setup a custom menue in your Wordpress backend before you can use this option (WordPress 3.0 and higher required)."),
						"id" => $shortname."_wpmenuon",
						"std" => __("false"),
						"type" => "checkbox"),
				
				array( 	"type" => "close"),


				array( 	"name" => "Setup the Leadarticle",
						"desc" => __("The leadarticle is the most recent newsarticle displayed in the tabbed area of the homepage."),
						"type" => "section"),
				array( 	"type" => "open"),
										
				array(	"name" => __('Leadarticle category'),
						"desc" => __("Enter the ID of the category that will be used as the leadarticle category"),
						"id" => $shortname."_lead",
						"std" => __("1"),
						"type" => "text"),
						
				array(	"name" => __('Leadarticle number of posts'),
						"desc" => __("Enter a number of posts displayed in the leadarticle (default = 1)"),
						"id" => $shortname."_leadnumber",
						"std" => __("1"),
						"type" => "text"),
						
				array( 	"type" => "close"),
						
				array( 	"name" => "Setup the Featured Articles in the left Column",
						"desc" => __("The featured articles are the articles (three by default) in the left column of the homepage. They are taken from one specific category, typicallly called \"featured articles\"."),
						"type" => "section"),
				array( 	"type" => "open"),

				array(	"name" => __('Featured articles category'),
						"desc" => __("Enter the ID of the category that will be used as the featured article category"),
						"id" => $shortname."_featured",
						"std" => __("1"),
						"type" => "text"),
						
				array(	"name" => __('Featured articles number of posts'),
						"desc" => __("Enter a number of posts displayed in the featured articles (default = 3)"),
						"id" => $shortname."_featurednumber",
						"std" => __("3"),
						"type" => "text"),
						
				array( 	"type" => "close"),

				array( 	"name" => "Setup the Homepage middle Column",
						"desc" => __("In the middle column of the homepage all other categories are displayed. Always the most recent article from the categories defined here will be displayed."),
						"type" => "section"),
				array( 	"type" => "open"),

				array(	"name" => __('Homepage categories'),
						"desc" => __("Enter the IDs of the categories separated by a comma (e.g. 3,5,7,8,9)"),
						"id" => $shortname."_homecats",
						"std" => __("1,2,3"),
						"type" => "text"),

				array(	"name" => __('Homepage categories number of posts per category'),
						"desc" => __("Enter a number of posts per category displayed in the in the middle column of the homepage (default = 1)"),
						"id" => $shortname."_homecatsnumber",
						"std" => __("1"),
						"type" => "text"),
						
				array( 	"type" => "close"),
						
						
				array( 	"name" => "Setup the Sidebar",
						"desc" => __("Here you can define how many newsheadlines are displayed on the homepage sidebar and which categories should NOT appear in the list of categories. 
						If you use widgets in the sidebar then you don´t really need to care about this section. 
						Widgets will completely replace most of the default stuff."),
						"type" => "section"),
				array( 	"type" => "open"),
						
				array(	"name" => __('Number of news in the homepage sidebar'),
						"desc" => __("Enter the number of news you want to display (default = 5)"),
						"id" => $shortname."_sidenewsnumber",
						"std" => __("5"),
						"type" => "text"),

				array(	"name" => __('Sidebar categories to exclude'),
						"desc" => __("Enter the IDs of the categories separated by a comma (e.g. 4,6)"),
						"id" => $shortname."_sidecats",
						"std" => __(""),
						"type" => "text"),
					
				array( 	"type" => "close"),


				array( 	"name" => "Setup the 3-column page template",
						"desc" => "The 3-column page is a special template to display content from three different categories in a very prominent way.",
						"type" => "section"),
				array( 	"type" => "open"),
										
				array(	"name" => __('3 column page left side category'),
						"desc" => __("Enter the ID of the category that will be used in the left column of the 3-column page"),
						"id" => $shortname."_3col_left",
						"std" => __("1"),
						"type" => "text"),
						
				array(	"name" => __('3-column page left number of posts per category'),
						"desc" => __("Enter a number of posts displayed in the in the left column of the 3-column page (default = 1)"),
						"id" => $shortname."_3col_leftnum",
						"std" => __("1"),
						"type" => "text"),
						
				array(	"name" => __('3 column page middle category'),
						"desc" => __("Enter the ID of the category that will be used in the middle column of the 3-column page"),
						"id" => $shortname."_3col_middle",
						"std" => __("1"),
						"type" => "text"),
						
				array(	"name" => __('3-column page middle number of posts per category'),
						"desc" => __("Enter a number of posts displayed in the in the middle column of the 3-column page (default = 1)"),
						"id" => $shortname."_3col_middlenum",
						"std" => __("1"),
						"type" => "text"),
						
				array(	"name" => __('3 column page right side category'),
						"desc" => __("Enter the ID of the category that will be used in the right column of the 3-column page"),
						"id" => $shortname."_3col_right",
						"std" => __("1"),
						"type" => "text"),
						
				array(	"name" => __('3-column page right number of posts per category'),
						"desc" => __("Enter a number of posts displayed in the in the right column of the 3-column page (default = 1)"),
						"id" => $shortname."_3col_rightnum",
						"std" => __("1"),
						"type" => "text"),
						
				array( 	"type" => "close"),
				
				
				array( 	"name" => "Google AdSense and Analytics code.",
						"type" => "section"),
				array( 	"type" => "open"),
				
				array(  "name" => __('Sidebar advert code'),
						"desc" => __("Here you can put your Google AdSense code for the 250x250px sidebar ad. Copy and paste your advert code (250x250px) into the box (e.g. Google AdSense)"),
						"id" => $shortname."_250x250_ads",
						"std" => __(""),
						"type" => "textarea",
						"options" => array(
						"rows" => "12",
						"cols" => "85") ),
				
				array(  "name" => __('Google Analytics'),
						"desc" => __("Copy and paste your Google Analytics (or onother tracking) code into the box."),
						"id" => $shortname."_analytics",
						"std" => __(""),
						"type" => "textarea",
						"options" => array(
						"rows" => "12",
						"cols" => "85") ),	
				
				array( 	"type" => "close"),

							);
							

add_action('admin_init', 'mytheme_add_init');
add_action('admin_menu', 'mytheme_add_admin');

function mytheme_add_admin() {

global $themename, $shortname, $options;

if ( $_GET['page'] == basename(__FILE__) ) {

	if ( 'save' == $_REQUEST['action'] ) {

		foreach ($options as $value) {
		update_option( $value['id'], $_REQUEST[ $value['id'] ] ); }

foreach ($options as $value) {
	if( isset( $_REQUEST[ $value['id'] ] ) ) { update_option( $value['id'], $_REQUEST[ $value['id'] ]  ); } else { delete_option( $value['id'] ); } }

	header("Location: admin.php?page=functions.php&saved=true");
die;

}
else if( 'reset' == $_REQUEST['action'] ) {

	foreach ($options as $value) {
		delete_option( $value['id'] ); }

	header("Location: admin.php?page=functions.php&reset=true");
die;

}
}

    add_theme_page($themename."Options", "BranfordMagazine Options", 'edit_themes', basename(__FILE__), 'mytheme_admin');
}

function mytheme_add_init() {
$file_dir=get_bloginfo('template_directory');
wp_enqueue_style("theme-options", $file_dir."/tools/theme-options.css", false, "1.0", "all");
wp_enqueue_script("rm_script", $file_dir."/tools/rm_script.js", false, "1.0");
}


function mytheme_admin() {

global $themename, $shortname, $options;
$i=0;
if ( $_REQUEST['saved'] ) echo '<div id="message" class="updated fade"><p><strong>'.$themename.' settings saved.</strong></p></div>';
if ( $_REQUEST['reset'] ) echo '<div id="message" class="updated fade"><p><strong>'.$themename.' settings reset.</strong></p></div>';
?>

<div class="wrap rm_wrap">
<div id="prinzlogo"><img src="http://www.der-prinz.com/wp-content/themes/denovo/img/logo_neu.png" /></div>
<h2><?php echo $themename; ?> Options</h2>
<div class="rm_opts">
<form method="post">
  <?php foreach ($options as $value) {
switch ( $value['type'] ) {

case "open":
?>
  <?php break;

case "close":
?>
  </div>
  </div>
  <br />
  <?php break;

case "title":
?>
 <p>If you want to stay updated on periodic WordPress tipps & tricks please visit 
  my <a href="http://www.michaeloeser.de" title="www.michaeloeser.de">Business 
  Website (German)</a>, or my <a href="http://www.der-prinz.com" title="www.der-prinz.com">Themes 
  Website (English)</a> for themes updates and support.</a> For all kind of issues concerning my themes I do offer support via the <a href="http://www.der-prinz.com/category/tutorials" title="Tutorials">Tutorials</a> and my <a href="http://www.der-prinz.com/support-forum/" title="Support Forum">Support 
  Forum</a> only.</p>
<p><strong>INFO:</strong> All preset IDs are just examples and do match the categories 
  in my demodatabase. In most cases they will not match the IDs in YOUR database!</p>
<p>Click on the headlines to expand the options sections.</p>
  <?php break;

case 'text':
?>
  <div class="rm_input rm_text">
    <label for="<?php echo $value['id']; ?>"> <?php echo $value['name']; ?> </label>
    <input name="<?php echo $value['id']; ?>" id="<?php echo $value['id']; ?>" type="<?php echo $value['type']; ?>" value="<?php if ( get_settings( $value['id'] ) != "") { echo stripslashes(get_settings( $value['id'])  ); } else { echo $value['std']; } ?>" />
    <small> <?php echo $value['desc']; ?> </small>
    <div class="clearfix"></div>
  </div>
  <?php
break;

case 'textarea':
?>
  <div class="rm_input rm_textarea">
    <label for="<?php echo $value['id']; ?>"> <?php echo $value['name']; ?> </label>
    <textarea name="<?php echo $value['id']; ?>" type="<?php echo $value['type']; ?>" cols="" rows=""><?php if ( get_settings( $value['id'] ) != "") { echo stripslashes(get_settings( $value['id']) ); } else { echo $value['std']; } ?>
</textarea>
    <small> <?php echo $value['desc']; ?> </small>
    <div class="clearfix"></div>
  </div>
  <?php
break;

case 'select':
?>
  <div class="rm_input rm_select">
    <label for="<?php echo $value['id']; ?>"> <?php echo $value['name']; ?> </label>
    <select name="<?php echo $value['id']; ?>" id="<?php echo $value['id']; ?>">
      <?php foreach ($value['options'] as $option) { ?>
      <option <?php if (get_settings( $value['id'] ) == $option) { echo 'selected="selected"'; } ?>> <?php echo $option; ?> </option>
      <?php } ?>
    </select>
    <small> <?php echo $value['desc']; ?> </small>
    <div class="clearfix"></div>
  </div>
  <?php
break;

case "checkbox":
?>
  <div class="rm_input rm_checkbox">
    <label for="<?php echo $value['id']; ?>"> <?php echo $value['name']; ?> </label>
    <?php if(get_option($value['id'])){ $checked = "checked=\"checked\""; }else{ $checked = "";} ?>
    <input type="checkbox" name="<?php echo $value['id']; ?>" id="<?php echo $value['id']; ?>" value="true" <?php echo $checked; ?> />
    <small> <?php echo $value['desc']; ?> </small>
    <div class="clearfix"></div>
  </div>
  <?php break;
case "section":

$i++;

?>
  <div class="rm_section">
  <div class="rm_title">
    <h3><img src="<?php bloginfo('template_directory')?>/tools/images/trans.gif" class="inactive" alt="""> <?php echo $value['name']; ?> </h3>
    <span class="submit">
    <input name="save<?php echo $i; ?>" type="submit" value="Save changes" />
    </span> <span class="optionsdescription"><?php echo $value['desc']; ?></span>
    <div class="clearfix"></div>
  </div>
  <div class="rm_options">
  <?php break;

}
}
?>
  <input type="hidden" name="action" value="save" />
</form>
<form method="post">
  <p class="submit">
    <input name="reset" type="submit" value="Reset" />
    <input type="hidden" name="action" value="reset" />
  </p>
</form>
<div style="font-size:9px; margin-bottom:10px;">Original Optionspanel by <a href="http://net.tutsplus.com/tutorials/wordpress/how-to-create-a-better-wordpress-options-panel">nettuts+</a><br />
  Icons by <a href="http://dryicons.com/free-icons/preview/colorful-stickers-part-2-icons-set/">dryicons</a></div>
</div>
<?php
}

?>