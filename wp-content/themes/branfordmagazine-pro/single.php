<?php get_header(); ?>
<?php // ignore post from related posts in sidebar
	prinz_ignorePost($post->ID); ?>
    
<div id="content">
  <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
 
  <div class="post" id="post-<?php the_ID(); ?>">
    <h2>
      <?php the_title(); ?>
    </h2>
    <small><?php the_time(__('M jS, Y','branfordmagazine')); ?> | <?php _e('By','branfordmagazine');?> <?php the_author_posts_link('namefl'); ?> | <?php _e('Category:','branfordmagazine');?>
    <?php the_category(', ') ?>
	<?php edit_post_link('Edit', ' | ', ''); ?>
    
    </small>
    <div class="entry">
       <?php the_content("<p class=\"serif\">" . __('Read the rest of this page', 'branfordmagazine') ." &raquo;</p>"); ?>

      <?php wp_link_pages("<p><strong>" . __('Pages', 'branfordmagazine') . ":</strong>", '</p>', __('number','branfordmagazine')); ?>
    </div>
    <?php if ( function_exists('the_tags') ) {
			the_tags('<span class="tags"><strong>Tags:</strong> ', ', ', '</span>'); } ?>
            
  </div>
  <?php comments_template(); ?>
  <?php endwhile; else: ?>
  <p><?php __('Sorry, no posts matched your criteria.','branfordmagazine');?></p>
  <?php endif; ?>
</div>
<?php get_sidebar(); ?>
<?php get_footer(); ?>
