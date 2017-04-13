<?php get_header(); ?>
<?php $sitios = $_SERVER['REQUEST_URI']; ?> 
<div class="art-layout-wrapper">
<?php if (is_home() ) {?>
	    <!--<div class="slider_1">
	     <?php include (ABSPATH . '/wp-content/plugins/wp-featured-content-slider/content-slider.php'); ?>
	    </div> -->
	    <?php } ?>
    <div class="art-content-layout">
        <div class="art-content-layout-row">
            <div class="art-layout-cell art-content">	
		<div ID="first-content-uno">
                  <?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar('Climate Change Content Widget Area') ) : ?>  
                  <?php endif; ?>  
                </div>
                <div ID="second-content-dos">
                   <?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar('Ecosystem Services Content Widget Area') ) : ?>
                   <?php endif; ?>  
                 </div>
        <div ID="third-content-tres">
          <?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar('Linking Farmers to Markets Content Widget Area') ) : ?>
                  <?php endif; ?>
        </div>
        <div ID="fourth-content-cuatro">
         <?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar('Impact and Strategic Studies Content Widget Area') ) : ?>
                  <?php endif; ?>                
        </div>
        <div ID="fifth-content-cinco">
              <?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar('Gender Content Widget Area') ) : ?>
                 <?php endif; ?>        
        </div>
          <div ID="sixth-content-seis">
                <?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar('Data and Information Content Widget Area') ) : ?>
                  <?php endif; ?> 
          </div>
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
