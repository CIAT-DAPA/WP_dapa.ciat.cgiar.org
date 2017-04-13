<?php
/*
Template Name: 3-Column-Page
*/
?>
<?php include (TEMPLATEPATH.'/tools/get-theme-options.php'); ?>
<?php get_header(); ?>

<div id="pageleft">
      <?php 
   query_posts("cat=$prinz_3col_left;&showposts=$prinz_3col_leftnum;"); ?>
      <?php while (have_posts()) : the_post(); ?>
 	  <h3>
        <?php 
	wp_list_categories("include=$prinz_3col_left;&title_li=&style=none"); ?>
      </h3>
          <?php // here the thumbnail image gets automatically generated fron the posts own image gallery ?>
      <?php postimage(283,150); ?> 
<br /><br />
      <a href="<?php the_permalink() ?>" rel="bookmark" title="<?php __('Permanent Link to','branfordmagazine')?> <?php the_title(); ?>" class="title">
      <?php 
	the_title(); ?>
      </a>
      <?php 
	the_excerpt() ; ?> 
      <?php endwhile; ?>  
   <?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar('3-Col Page Left') ) : ?>      
  
  <strong>
    <br />
  <?php _e('More from this category','branfordmagazine');?>
  <br />
  <br />
  </strong>
  <?php 
// this is where the last three headlines are pulled from the category	    
		query_posts("offset=1&showposts=3&cat=$prinz_3col_left;"); 		
		?>
  <ul class="bullets">
    <?php while (have_posts()) : the_post(); ?>
    <li><a href="<?php the_permalink() ?>" rel="bookmark">
      <?php the_title(); ?>
      </a></li>
    <?php endwhile; ?>
  </ul>
  	<?php endif; ?>
</div>
<!-- END LEFT COLUMN -->
<div id="pagemiddle">
      <?php 
   query_posts("cat=$prinz_3col_middle;&showposts=$prinz_3col_middlenum;"); ?>
   
      <?php while (have_posts()) : the_post(); ?>
 	  <h3>
        <?php 
	wp_list_categories("include=$prinz_3col_middle;&title_li=&style=none"); ?>
      </h3>
          <?php // here the thumbnail image gets automatically generated fron the posts own image gallery ?>
     <?php postimage(283,150); ?>
<br /><br />
      <a href="<?php the_permalink() ?>" rel="bookmark" title="<?php __('Permanent Link to','branfordmagazine')?> <?php the_title(); ?>" class="title">
      <?php 
	the_title(); ?>
      </a>
      <?php 
	the_excerpt() ; ?> 
      <?php endwhile; ?>  
        
   <?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar('3-Col Page Middle') ) : ?>  
   
  <strong>
  <br />
  <?php _e('More from this category','branfordmagazine');?>
  <br />
  <br />
  </strong>
  <?php 
// this is where the last three headlines are pulled from the News (or whatever) category	  
		query_posts("offset=1&showposts=3&cat=$prinz_3col_middle;"); 		
		?>
  <ul class="bullets">
    <?php while (have_posts()) : the_post(); ?>
    <li><a href="<?php the_permalink() ?>" rel="bookmark">
      <?php the_title(); ?>
      </a></li>
    <?php endwhile; ?>
  </ul>
  	<?php endif; ?>
</div>
<!-- END MIDDLE COLUMN -->
<div id="pageright">
      <?php 
   query_posts("cat=$prinz_3col_right;&showposts=$prinz_3col_rightnum;"); ?>
   
      <?php while (have_posts()) : the_post(); ?>
 	  <h3>
        <?php 
	wp_list_categories("include=$prinz_3col_right;&title_li=&style=none"); ?>
      </h3>
          <?php // here the thumbnail image gets automatically generated fron the posts own image gallery ?>
      <?php postimage(283,150); ?>
<br /><br />
      <a href="<?php the_permalink() ?>" rel="bookmark" title="<?php __('Permanent Link to','branfordmagazine')?> <?php the_title(); ?>" class="title">
      <?php 
	the_title(); ?>
      </a>
      <?php 
	the_excerpt() ; ?> 
      <?php endwhile; ?>  
        
    <?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar('3-Col Page Right') ) : ?>  
  
  <strong>
  <br />
  <?php _e('More from this category','branfordmagazine');?>
  <br />
  <br />
  </strong>
  <?php 
// this is where the last three headlines are pulled from the category	  
		query_posts("offset=1&showposts=3&cat=$prinz_3col_right;"); 		
		?>
  <ul class="bullets">
    <?php while (have_posts()) : the_post(); ?>
    <li><a href="<?php the_permalink() ?>" rel="bookmark">
      <?php the_title(); ?>
      </a></li>
    <?php endwhile; ?>
  </ul>
  <?php endif; ?>
</div>
<!-- END RIGHT COLUMN -->
<?php get_footer(); ?>
