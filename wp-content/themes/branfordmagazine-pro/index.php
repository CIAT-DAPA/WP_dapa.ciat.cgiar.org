<?php get_header(); ?>
<?php include (TEMPLATEPATH.'/tools/get-theme-options.php'); ?>

<div id="content">
  <?php
 // Include tabs with the lead story 
	include(TEMPLATEPATH . '/includes/ui.tabs.php'); ?>
  <div id="leftcol">
    <?php 
// "Featured articles" module begins	  
	$featured_query = new WP_Query("showposts=$prinz_featurednumber;&cat=$prinz_featured;"); ?>
    <h3>
      <?php 
	// name of the "featured articles" category gets printed	  
	wp_list_categories("include=$prinz_featured;&title_li=&style=none"); ?>
    </h3>
    <?php while ($featured_query->have_posts()) : $featured_query->the_post(); ?>
    <div class="feature">
      <?php // This will show the image and link the image to the post. Alter the width and height (in both places) to your needs. ?>
      <?php postimage(250,$prinz_featuredimage_height); ?>
      <a href="<?php the_permalink() ?>" rel="bookmark" class="title">
      <?php 
// title of the "featured articles"	  
	  the_title(); ?>
      </a>
      <?php the_excerpt(); ?>
    </div>
    <?php endwhile; ?>
    <?php wp_reset_query(); ?>
  </div>
  <!--END LEFTCOL-->
  <div id="rightcol">
    <?php
$display_categories = explode(",",$prinz_homecats);
for ($x = 0; $x < sizeof($display_categories); ++$x) {
?>
    <div class="clearfloat">
      <?php $homecats_query = new WP_Query("showposts=$prinz_homecatsnumber&cat=".current($display_categories).""); ?>
      
      <?php // Name and link of each category headline gets printed	?>
      <h3><?php wp_list_categories("include=".current($display_categories).";&title_li=&style=none"); ?></h3>
      
      <?php next($display_categories); ?>
      <?php while ($homecats_query->have_posts()) : $homecats_query->the_post(); ?>
      <?php // This will show the image and link the image to the post. ?>
      <?php postimage($prinz_catimage_width,$prinz_catimage_height); ?>
      <a href="<?php the_permalink() ?>" rel="bookmark" class="title">
      <?php // this is where title of the article gets printed 
	  the_title(); ?>
      </a><br />
      <?php the_excerpt() ; ?>
      <?php endwhile; ?>
      <?php wp_reset_query(); ?>
    </div>
    <?php } ?>
  </div>
  <!--END RIGHTCOL-->
</div>
<!--END CONTENT-->
<?php get_sidebar(); ?>
<?php get_footer(); ?>
