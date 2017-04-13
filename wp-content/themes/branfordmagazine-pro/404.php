<?php get_header(); ?>

	<div id="content">

		<h2 class=""><?php _e('Nothing found','branfordmagazine'); ?></h2>
	<div class="entry">	
    <p class=""><?php _e('Sorry, but it seems as if the content you are looking for has been moved or deleted. Please try the sitesearch, look up the archives or go to the homepage.','branfordmagazine'); ?></p>
        <h4><?php __('Archives by Month:','branfordmagazine'); ?></h4>

<?php include (TEMPLATEPATH . '/searchform.php'); ?>
<br /><br />
<h4><?php _e('Archives by Month:','branfordmagazine'); ?></h4>
  <ul class="bullets">
    <?php wp_get_archives('type=monthly'); ?>
  </ul>
<br /><br />
<h4><?php _e('Archives by Subject:','branfordmagazine'); ?></h4>
  <ul class="bullets">
     <?php wp_list_categories(); ?>
  </ul>

        <p><a href="<?php echo get_option('home'); ?>/">Homepage</a></p>
	</div>
</div>
<?php get_sidebar(); ?>

<?php get_footer(); ?>