<?php get_header(); ?>
<?php include (TEMPLATEPATH.'/tools/get-theme-options.php'); ?>

<div id="content">
  <?php is_tag(); ?>
  <?php if (have_posts()) : ?>
  <?php $post = $posts[0]; // Hack. Set $post so that the_date() works. ?>
  <?php /* If this is a category archive */ if (is_category()) { ?>
  <h2 class="pagetitle"><?printf( __('%s','branfordmagazine'), single_cat_title('', false)); ?></h2>
  <?php /* If this is a tag archive */ } elseif( is_tag() ) { ?>
  <h2 class="pagetitle">
    <?php _e('Posts Tagged','branfordmagazine'); ?>
    &#8216;
    <?php single_tag_title(); ?>
    &#8217;</h2>
  <?php /* If this is a daily archive */ } elseif (is_day()) { ?>
  <h2 class="pagetitle"><?printf( __('Archive for %s','branfordmagazine'), get_the_time(__('F jS, Y','branfordmagazine'))); ?></h2>
  <?php /* If this is a monthly archive */ } elseif (is_month()) { ?>
  <h2 class="pagetitle"><?printf( __('Archive for %s','branfordmagazine'), get_the_time(__('F Y','branfordmagazine'))); ?></h2>
  <?php /* If this is a yearly archive */ } elseif (is_year()) { ?>
  <h2 class="pagetitle"><?printf( __('Archive for %s','branfordmagazine'), get_the_time('Y')); ?></h2>
  <?php /* If this is a search */ } elseif (is_search()) { ?>
  <h2 class="pagetitle">
    <?php __('Search Results','branfordmagazine'); ?>
  </h2>
  <?php /* If this is an author archive */ } elseif (is_author()) { ?>
  <h2 class="pagetitle">
    <?php _e('All entries by this author','branfordmagazine'); ?>
  </h2>
  <?php /* If this is a paged archive */ } elseif (isset($_GET['paged']) && !empty($_GET['paged'])) { ?>
    <h2 class="pagetitle">
      <?php _e('Blog Archives','branfordmagazine'); ?>
    </h2>
    <?php } ?>
  <div class="clearfloat"></div>
  <?php while (have_posts()) : the_post(); ?>
  <div class="post">
    <h4 id="post-<?php the_ID(); ?>"><a href="<?php the_permalink() ?>" rel="bookmark" title="<?php __('Permanent Link to','branfordmagazine');?> <?php the_title(); ?>">
      <?php the_title(); ?>
      </a></h4>
    <small>
    <?php the_time(__('M jS, Y','branfordmagazine')); ?>
    |
    <?php _e('By','branfordmagazine');?>
    <?php the_author_posts_link('namefl'); ?>
    <?php edit_post_link('Edit', ' | ', ''); ?>
    </small>
    <div class="entry">
     <?php // here the thumbnail image gets automatically generated fron the posts own image gallery ?>
     <?php postimage($prinz_archiveimage_width,$prinz_archiveimage_height); ?> 
      <?php the_excerpt() ?>
    </div>
    <p class="postmetadata">
      <?php _e('Posted in','branfordmagazine');?>
      <?php the_category(', '); ?>
      |
      <?php comments_popup_link(__ ('No Comments &#187;', 'branfordmagazine'), __ ('1 Comment &#187;', 'branfordmagazine'), __ngettext ('% comment', '% comments', get_comments_number (),'branfordmagazine')); ?>
      <br />
       <?php if ( function_exists('the_tags') ) {
			the_tags('<span class="tags"><strong>Tags:</strong> ', ', ', '</span>'); } ?>
    </p>
    <hr />
    <br />
  </div>
  <?php endwhile; ?>
  
  <ul id="paging">
  <li class="prev">
    <?php previous_posts_link(__('Previous entries','branfordmagazine')) ?>
  </li>

  <?php if (function_exists('the_paging_bar')) { ?>
    <li class="pages">
      <?php the_paging_bar() ?>
    </li>
  <?php } ?>

  <li class="next">
    <?php next_posts_link(__('Next entries','branfordmagazine')) ?>
  </li>
</ul>

 <?php else : ?>
  <h2 class="center">
    <?php __('Not Found','branfordmagazine') ?>
  </h2>
  <?php include (TEMPLATEPATH . '/searchform.php'); ?>
  <?php endif; ?>
</div>
<?php get_sidebar(); ?>
<?php get_footer(); ?>
