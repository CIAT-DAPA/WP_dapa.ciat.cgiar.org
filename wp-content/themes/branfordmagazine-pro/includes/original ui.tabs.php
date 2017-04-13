<?php include (TEMPLATEPATH.'/tools/get-theme-options.php'); ?>
<!-- START TABBED SECTION -->

<div id="container-4">
  <ul>
    <li><a class="ui-tabs" href="#fragment-1">Lead Article</a></li>
    <li><a class="ui-tabs" href="#fragment-2">Recent Posts</a></li>
    <li><a class="ui-tabs" href="#fragment-3">About this Theme</a></li>
    <!-- Just add tabs as you like by following this scheme:
    <li><a class="ui-tabs" href="#fragment-X">Link name here</a></li> -->
  </ul>
  <!-- LEAD ARTICLE -->
  <div id="fragment-1">
	  <?php 
// Lead Story module begins 
   $lead_query = new WP_Query("cat=$prinz_lead;&showposts=$prinz_leadnumber;"); ?>
      <?php while ($lead_query->have_posts()) : $lead_query->the_post(); ?>
      <?php // here the thumbnail image gets automatically generated fron the posts own image gallery ?>
      <?php postimage($prinz_leadimage_width,$prinz_leadimage_height); ?>
      <h3>
        <?php 
	// this is where the name of the Lead Story category gets printed	  
	wp_list_categories("include=$prinz_lead;&title_li=&style=none"); ?>
      </h3>
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
  <!-- END LEAD ARTICLE -->
  <!-- RECENT POSTS -->
  <div id="fragment-2" class="bullets">
    <h3 class="title">Recent Posts</h3>
    <p>To show the recent posts is just one thing you can use this tabbed section
      for. There are many more. It&acute;s up to your creativity.</p>
    <ul>
	<?php wp_get_archives('type=postbypost&limit=7'); ?>
    </ul>
  </div>
  <!-- END RECENT POSTS -->
  <!-- ABOUT -->
  <div id="fragment-3">
      <h3 class="title">About this Theme</h3>
      <p>The first version of this theme back in January 2008 was  inspired by the great magazine style themes of Brian
        Gardner and Darren Hoyt. I took those elements that I liked the most
        in every theme and combined them together in one single theme. The different
        page templates are inspired by Brian Gardners
        &quot;Revolution&quot; theme, other elements are taken from  &quot;Mimbo&quot; by Darren Hoyt. The Tabbed section
        is created by using jQuery ui.tabs by Klaus Hartl (stilbuero.de). Meanwihle the theme walked through a lot of development and got its own individual face and functions.</p>
      <p>The Name of the theme was inspired by the famous American jazz sax-player,
        Branford Marsalis. Although I&acute;m German, I decided to present this
        theme in english in order to make it available for a greater audience.      </p>
      <p><strong>Find further information, tutorials and support forum  <a href="http://www.der-prinz.com/" target="_blank">on my Website.</a></strong></p>
  </div>
  <!-- END ABOUT -->
</div>
<!-- END TABBED SECTION -->
