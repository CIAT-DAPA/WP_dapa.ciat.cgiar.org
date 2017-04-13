<?php include (TEMPLATEPATH.'/tools/get-theme-options.php'); ?>
<!-- START TABBED SECTION -->

<div id="container-4">
  <ul>
    <li><a class="ui-tabs" href="#fragment-1">Spotlight</a></li>
    <li><a class="ui-tabs" href="#fragment-3">Latest Posts</a></li>
    <li><a class="ui-tabs" href="#fragment-4">Latest from CCAFS</a></li>
    <li><a class="ui-tabs" href="#fragment-5">Latest from AESCE</a></li>

    <!-- Just add tabs as you like by following this scheme:
    <li><a class="ui-tabs" href="#fragment-X">Link name here</a></li> -->
  </ul>

<!-- SPOTLIGHT -->
  <div id="fragment-1">
    <?php
    // Lead Story module begins
    $lead_query = new WP_Query("cat=$prinz_lead;&showposts=$prinz_leadnumber;"); ?>
    <?php while ($lead_query->have_posts()) : $lead_query->the_post(); ?>
      <?php // here the thumbnail image gets automatically generated fron the posts own image gallery ?>
      <?php postimage($prinz_leadimage_width,$prinz_leadimage_height); ?>
      <a href="<?php the_permalink() ?>" rel="bookmark" title="<?php the_title(); ?>" class="title">
      <?php
      // this is where the title of the Lead Story gets printed
      the_title(); ?>
      </a>
      <?php
      // this is where the excerpt of the Lead Story gets printed
      the_excerpt() ; ?>
      <span class="read-on"> <a href="<?php the_permalink() ?>" rel="bookmark" title="<?php the_title(); ?>">
      <?php _e('[continue reading...]','branfordmagazine'); ?>
      </a> </span>
    <?php endwhile; ?>
    <?php wp_reset_query(); ?>
  </div>
<!-- END SPOTLIGHT -->

<!-- RECENT POSTS -->
  <div id="fragment-3" class="bullets">
    <ul>
	<img style="border: 1px solid #999999; margin: 3px 30px 60px 0px; padding: 1px; float: left; width: 75px; height: 75px;" src="/wp-content/uploads/2010/12/mali-crop-basket.jpg"/><?php wp_get_archives('type=postbypost&limit=6'); ?>
    </ul>
  </div>
  <!-- END RECENT POSTS -->

<!-- CCAFS  -->
  <div id="fragment-4" class="bullets">
   <img style="border: 1px solid #999999; margin: 3px 30px 10px 0px; padding: 1px; float: left; width: 75px; height: 75px;" src="/wp-content/uploads/2010/12/ccafs-thumb.jpg"/>
   <script language="JavaScript" src="http://feed2js.org//feed2js.php?src=http%3A%2F%2Fccafs.cgiar.org%2Ffeeds%2Fblogs&amp;num=4"  type="text/javascript"></script>
   <br/><a href="http://ccafs.cgiar.org/about" target="_blank">More </a>on The Challenge Program on Climate Change, Agriculture and Food Security (CCAFS)...
  </div>
<!-- END CCAFS -->

<!-- AESCE  -->
  <div id="fragment-5" class="bullets">
    <img style="border: 1px solid #999999; margin: 3px 30px 10px 0px; padding: 1px; float: left; width: 75px; height: 75px;" src="wp-content/uploads/2011/11/AESCE.jpg"/>
    <script language="JavaScript" src="http://feed2js.org//feed2js.php?src=http%3A%2F%2Fwww.frutisitio.org%2Ffeed%2F&num=4&utf=y"  type="text/javascript"></script>
   <br/>Más acerca de: <a href="http://www.frutisitio.org/about/" target="_blank">Agricultura Especícfica por Sitio Compartiendo Experiencia s (AESCE)</a>
  </div>
<!-- END AESCE -->


</div>
<!-- END TABBED SECTION -->
