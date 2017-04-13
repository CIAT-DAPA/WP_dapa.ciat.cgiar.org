<?php include (TEMPLATEPATH.'/tools/get-theme-options.php'); ?></div>
<? if ($prinz_altfooter == "true") { ?>
<!-- ALTERNATIVE FOOTER START -->
<div id="alternative_footer"> 
  <div class="alternative_footer_left"> 
    <?php 	/* Widgetized sidebar, if you have the plugin installed. */
					if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar('Footer Left') ) : ?>
    <h4> 
      <?php _e('Footer Widgets','branfordmagazine');?>
    </h4>
    <p>This is another widgetized area. You can use any kind of Widget to improve your Website. 
	No matter if you use Textwidgets, any other default WordPress widget or the fantastic custom Widgets that are included in BranfordMagazine.</p>
    <?php endif; ?>
  </div>

  <div class="alternative_footer_middle1"> 
    <?php 	/* Widgetized sidebar, if you have the plugin installed. */
					if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar('Footer Middle 1') ) : ?>
    <h4> 
      <?php _e('Footer Widgets','branfordmagazine');?>
    </h4>
    <p>This is another widgetized area. You can use any kind of Widget to improve your Website. 
	No matter if you use Textwidgets, any other default WordPress widget or the fantastic custom Widgets that are included in BranfordMagazine.</p>
    <?php endif; ?>
  </div>
  
  <div class="alternative_footer_middle2"> 
    <?php 	/* Widgetized sidebar, if you have the plugin installed. */
					if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar('Footer Middle 2') ) : ?>
    <h4> 
      <?php _e('Footer Widgets','branfordmagazine');?>
    </h4>
    <p>This is another widgetized area. You can use any kind of Widget to improve your Website. 
	No matter if you use Textwidgets, any other default WordPress widget or the fantastic custom Widgets that are included in BranfordMagazine.</p>
    <?php endif; ?>
  </div>

  <div class="alternative_footer_right"> 
    <?php 	/* Widgetized sidebar, if you have the plugin installed. */
					if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar('Footer Right') ) : ?>
    <h4> 
      <?php _e('Footer Widgets','branfordmagazine');?>
    </h4>
    <p>This is another widgetized area. You can use any kind of Widget to improve your Website. 
	No matter if you use Textwidgets, any other default WordPress widget or the fantastic custom Widgets that are included in BranfordMagazine.</p>
    <?php endif; ?>
  </div>
  <div class="clearfix"></div>
</div>

<!-- ALTERNATIVE FOOTER END -->
<?php } ?>
<!-- REGULAR FOOTER START -->
<div id="footer"> 
  <?php wp_footer(); ?>
</div>
<!-- REGULAR FOOTER END -->

<!-- Google (or other) Analytics code (if set in the options) -->
<?php echo stripslashes($prinz_analytics); ?> 
<!-- End Google Analytics -->
</body>
</html>
