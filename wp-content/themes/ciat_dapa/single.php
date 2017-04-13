<?php 

/**
 *
 * single.php
 *
 * The single post template. Used when a single post is queried.
 * 
 */	

get_header(); ?>
<div class="art-layout-wrapper">
    <div class="art-content-layout">
        <div class="art-content-layout-row">
            <div class="art-layout-cell art-content">
			<?php get_sidebar('top');  ?>
			<!-- Breadcrumb -->
			<div id="miga">
            <?php the_breadcrumb(); ?>
            <?php get_category_id(); ?>
			</div>
            <!-- Fin Breadcrumb -->
			<?php 
				if (have_posts()){
					/* Display navigation to next/previous posts when applicable */
					/*
					if (theme_get_option('theme_top_single_navigation')) {
						theme_page_navigation(
							array(
								'next_link' => theme_get_previous_post_link('&laquo; %link'),
								'prev_link' => theme_get_next_post_link('%link &raquo;')
							)
						);
					}
					*/
					while (have_posts())  
					{
						the_post();
						get_template_part('content', 'single');
						/*post relacionados*/
$tags = wp_get_post_tags($post->ID);
if ($tags) {
	$tag_ids = array();
	foreach($tags as $individual_tag) $tag_ids[] = $individual_tag->term_id;

	$args=array(
		'tag__in' => $tag_ids,
		'post__not_in' => array($post->ID),
		'showposts'=>5, // Number of related posts that will be shown.
		'caller_get_posts'=>1
	);
	$my_query = new wp_query($args);
	if( $my_query->have_posts() ) {
	   echo '<div class="related-posts">';
		echo '<h3>Related Posts</h3><ul>';
		while ($my_query->have_posts()) {
			$my_query->the_post();
		?>
			<li><a href="<?php the_permalink() ?>" rel="bookmark" title="Permanent Link to <?php the_title_attribute(); ?>"><?php the_title(); ?></a></li>
		<?php
		}
		echo '</ul>';
		echo '</div>';
	}
}
/*fin post relacionados*/
						/* Display comments */
						if ( theme_get_option('theme_allow_comments')) {
							comments_template();
						}
					}
					
					/* Display navigation to next/previous posts when applicable */
					if (theme_get_option('theme_bottom_single_navigation')) {
						theme_page_navigation(
							array(
								'next_link' => theme_get_previous_post_link('&laquo; %link'),
								'prev_link' => theme_get_next_post_link('%link &raquo;')
							)
						);
					}
					
				} else {    
				  
					theme_404_content();
					
				} 
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
