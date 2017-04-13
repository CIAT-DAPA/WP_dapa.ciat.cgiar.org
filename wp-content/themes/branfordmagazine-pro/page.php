<?php get_header(); ?>

	<div id="content">

		<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
		<div class="post" id="post-<?php the_ID(); ?>">
		<h2><?php the_title(); ?></h2>
			<div class="entry">
				 <?php the_content("<p class=\"serif\">" . __('Read the rest of this page', 'branfordmagazine') ." &raquo;</p>"); ?>

				<?php wp_link_pages("<p><strong>" . __('Pages', 'branfordmagazine') . ":</strong>", '</p>', __('number','branfordmagazine')); ?>

			</div>
		</div>
		<?php endwhile; endif; ?>
	<?php edit_post_link('Edit', '<p>', '</p>'); ?>
	</div>

<?php get_sidebar(); ?>

<?php get_footer(); ?>