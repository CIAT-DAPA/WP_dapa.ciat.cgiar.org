<?php
/*
  Template Name: Publications Content
 */

get_header();
?>
<style>
  h5 {color:#2E9366!important;margin-top:2em!important;}
  p {margin-right:2em!important;}
  h1 {font-size:30px!important;margin-bottom:1em!important;}
</style>
<div class="art-layout-wrapper">
  <div class="art-content-layout">
    <div class="art-content-layout-row">
      <div class="art-layout-cell art-content">	
        <?php get_sidebar('top'); ?>
        <div id="miga">
            <a href="<?php bloginfo('url'); ?>">DAPA</a> / <?php the_title(); ?> </div>
        <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
            <div class="art-box art-post post-<?php the_ID(); ?> page type-page status-publish hentry" id="post-<?php the_ID(); ?>">
              <div class="art-box-body art-post-body">
	            <div class="art-post-inner art-article">
              <h1 class="art-postheader"><?php the_title(); ?></h1>
              <div class="art-postcontent">
                <?php the_content('<p class="serif">Read the rest of this page »</p>'); ?>
              </div>
              </div>
              </div>
            </div>
          <?php
          endwhile;
        endif;
        ?>
<?php get_sidebar('bottom'); ?>
        <div class="cleared"></div>
      </div>
      <div class="art-layout-cell art-sidebar1">
<?php get_sidebar('default'); ?>
        <div class="cleared"></div>
      </div>
    </div>
  </div>
</div>
<div class="cleared"></div>
<?php get_footer(); ?>
