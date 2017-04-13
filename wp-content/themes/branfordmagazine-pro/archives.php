<?php
/*
Template Name: Archives
*/
?>

<?php get_header(); ?>

<div id="content">
<div class="entry">
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
</div>
</div>	


<?php get_sidebar(); ?>
<?php get_footer(); ?>
