<?php // Do not delete these lines
	if ('comments.php' == basename($_SERVER['SCRIPT_FILENAME']))
		die (__('Please do not load this page directly. Thanks!','branfordmagazine'));

	if (!empty($post->post_password)) { // if there's a password
		if ($_COOKIE['wp-postpass_' . COOKIEHASH] != $post->post_password) {  // and it doesn't match the cookie
			?>

<p class="nocomments">
  <?php __('This post is password protected. Enter the password to view comments.','branfordmagazine'); ?>
</p>
<?php
			return;
		}
	}

	/* This variable is for alternating comment background */
	$oddcomment = 'class="alt" ';
?>
<!-- You can start editing here. -->
<?php if ($comments) : ?>
<h3 id="comments">
  <?php comments_number(__('No comments','branfordmagazine'), __('One comment','branfordmagazine'), "% " . __('comments','branfordmagazine')); ?>
  <br />
  <a href="#respond" title="<?php _e('Leave a comment','branfordmagazine'); ?>">
  <?php _e('Leave a comment','branfordmagazine'); ?>
  &raquo;</a></h3>
<ol class="commentlist">
  <?php foreach ($comments as $comment) : ?>
  <li <?php echo $oddcomment; ?>id="comment-<?php comment_ID() ?>"> <small class="commentmetadata">
    <?php comment_author_link() ?>
    <?php __('on','branfordmagazine');?>
    <a href="#comment-<?php comment_ID() ?>" title="">
    <?php comment_date(__ ('F jS, Y', 'branfordmagazine')); ?>
    <?php __('at','branfordmagazine');?>
    <?php comment_time() ?>
    </a>
     <?php edit_comment_link('edit','&nbsp;&nbsp;',''); ?>
    </small>
    <?php if ($comment->comment_approved == '0') : ?>
    <em>
    <?php _e('Your comment is awaiting moderation.','branfordmagazine'); ?>
    </em>
    <?php endif; ?>
    <?php comment_text(); ?>
  </li>
  <?php
		/* Changes every other comment to a different class */
		$oddcomment = ( empty( $oddcomment ) ) ? 'class="alt" ' : '';
	?>
  <?php endforeach; /* end for each comment */ ?>
</ol>
<?php else : // this is displayed if there are no comments so far ?>
<?php if ('open' == $post->comment_status) : ?>
<!-- If comments are open, but there are no comments. -->
<?php else : // comments are closed ?>
<!-- If comments are closed. -->
<p class="nocomments">
  <?php _e('Comments are closed.','branfordmagazine'); ?>
</p>
<?php endif; ?>
<?php endif; ?>
<?php if ('open' == $post->comment_status) : ?>
<h3 id="respond">
  <?php _e('Leave Comment','branfordmagazine'); ?>
</h3>
<?php if ( get_option('comment_registration') && !$user_ID ) : ?>
<p>
  <?php _e('You must be','branfordmagazine'); ?>
  <a href="<?php echo get_option('siteurl'); ?>/wp-login.php?redirect_to=<?php echo urlencode(get_permalink()); ?>">
  <?php _e('logged in','branfordmagazine'); ?>
  </a>
  <?php _e('to post a comment.','branfordmagazine'); ?>
</p>
<?php else : ?>
<form action="<?php echo get_option('siteurl'); ?>/wp-comments-post.php" method="post" id="commentform">
  <?php if ( $user_ID ) : ?>
  <p>
    <?php _e('Logged in as','branfordmagazine'); ?>
    <a href="<?php echo get_option('siteurl'); ?>/wp-admin/profile.php"><?php echo $user_identity; ?></a>. <a href="<?php echo get_option('siteurl'); ?>/wp-login.php?action=logout" title="<?php _e('Log out of this account','branfordmagazine'); ?>">
    <?php _e('Logout &raquo;','branfordmagazine'); ?>
    </a></p>
  <?php else : ?>
  <p>
    <input type="text" name="author" id="author" value="<?php echo $comment_author; ?>" size="22" tabindex="1" />
    <label for="author"><small>
    <?php _e('Name','branfordmagazine'); ?>
    <?php if ($req) echo __('(required)','branfordmagazine'); ?>
    </small></label>
  </p>
  <p>
    <input type="text" name="email" id="email" value="<?php echo $comment_author_email; ?>" size="22" tabindex="2" />
    <label for="email"><small>
    <?php _e('Mail (will not be published)','branfordmagazine'); ?>
    <?php if ($req) echo __('(required)','branfordmagazine'); ?>
    </small></label>
  </p>
  <p>
    <input type="text" name="url" id="url" value="<?php echo $comment_author_url; ?>" size="22" tabindex="3" />
    <label for="url"><small>
    <?php _e('Website','branfordmagazine'); ?>
    </small></label>
  </p>
  <?php endif; ?>
  <p>
    <textarea name="comment" id="comment" cols="100%" rows="10" tabindex="4"></textarea>
  </p>
  <p>
    <input name="submit" class="button" type="submit" id="submit" tabindex="5" value="<?php _e('Submit Comment','branfordmagazine'); ?>" />
    <input type="hidden" name="comment_post_ID" value="<?php echo $id; ?>" />
  </p>
  <?php do_action('comment_form', $post->ID); ?>
</form>
<?php endif; // If registration required and not logged in ?>
<?php endif; // if you delete this the sky will fall on your head ?>
