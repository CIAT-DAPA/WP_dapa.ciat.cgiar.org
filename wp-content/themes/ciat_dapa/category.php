<?php get_header(); ?>
 
<div class="art-layout-wrapper">
<?php if (is_home() ) {?>
	    <div class="slider_1">
	     <?php include (ABSPATH . '/wp-content/plugins/wp-featured-content-slider/content-slider.php'); ?>
	    </div>
	    <?php } ?>
    <div class="art-content-layout">
        <div class="art-content-layout-row">
            <div class="art-layout-cell art-content">
			
			
			<?php 
				
					/* Display navigation to next/previous pages when applicable */
					if ( theme_get_option('theme_' . (theme_is_home() ? 'home_' : '') . 'top_posts_navigation' ) ) {				   
						/*theme_page_navigation();*/
					/* echo '<h2 class="last-news-1"></h2>'; */
                                         echo '<div id="miga">';
					     the_breadcrumb();
                                         echo '</div>';
					}
       /* 
		    echo '<div id="description-categoria">';
                    $ncategorias = single_cat_title("", false);
                    $numero_categoria = get_category_id($ncategorias);
                    $cat = get_category($numero_categoria);
                    $nombre_archivo = $cat->slug;
                    echo '<img src="/images/',$nombre_archivo,'.jpg">';
                    echo '<h2>', single_cat_title(), '</h2>';
                    echo '<p>', category_description( $category_id ), '</p>';
                    echo '</div>';
      */
                    echo '<div id="description-latest">';
                    echo '<h3>Latest News</h3>';
                    echo '</div>';
                         if(have_posts()) {
					/* Start the Loop */ 
					while (have_posts()) {
                                                          the_post();
						$category = get_the_category();
						get_template_part('content-category', get_post_format());
					}
					
					/* Display navigation to next/previous pages when applicable */
					if (theme_get_option('theme_bottom_posts_navigation')) {
						 theme_page_navigation();
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