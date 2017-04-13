<?php


/*	##################################
	MORE FROM THIS CATEGORY WIDGET
	################################## */
	
// $postAmount = the amount of posts to display
function prinz_categoryPosts($args = array(), $postAmount = 5) {

	// make sure it's a single post
	if(is_single()) {
	
		global $singlePostId;
		
		extract($args);
		
		$prinz_cats = get_the_category($singlePostId['id']);
		
		foreach($prinz_cats as $prinz_c) {

			$wpq = new WP_Query();
			$query = 'posts_per_page=' . $postAmount . '&cat=' . $prinz_c->cat_ID;
			$wpq->query($query);
			
			if ($wpq->have_posts()) {
			
				echo $before_widget;
?>
		<h3><?php _e('More in','branfordmagazine'); ?> <?php echo $prinz_c->name; ?></h3>
		<ul class="bullets">
<?php	
				while ($wpq->have_posts()) {
					$wpq->the_post();
					prinz_ignorePost($wpq->post->ID);
?>
			<li><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></li>
<?php
				} ?>
                	
				<?php wp_reset_query(); ?>
				</ul>
			<?php echo $after_widget; ?>
		
<?php
			}
			
		}
		
	}
}

register_sidebar_widget('PRiNZ more from this Category', 'prinz_categoryPosts');



/*	##################################
	MORE FROM THIS AUTHOR WIDGET
	################################## */

// $postAmount = the amount of posts to display
function prinz_authorPosts($args = array(), $postAmount = 5) {
	
	// make sure it's a single post
	if(is_single()) {
	
		extract($args);
		echo $before_widget;	
	
		global $wp_query;
		$postAuthor = $wp_query->post->post_author;
		
		$wpq = new WP_Query();
		$query = 'posts_per_page=' . $postAmount . '&author=' . $postAuthor;
		$wpq->query($query);
			
		if ($wpq->have_posts()) {
?>
		<h3><?php _e('More from','branfordmagazine'); ?> <?php echo get_the_author(); ?></h3>
		<ul class="bullets">
<?php	
			while ($wpq->have_posts()) {
				$wpq->the_post();
				prinz_ignorePost($wpq->post->ID);
?>
			<li><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></li>
<?php
			}
			
			wp_reset_query();
?>
		</ul>
<?php
		}
		
		echo $after_widget;

	}
}

register_sidebar_widget('PRiNZ Author Posts','prinz_authorPosts');



/*	##################################
	NEWSLETTER WIDGET
	################################## */

class PRiNZ_Newsletter extends WP_Widget {
 
	function PRiNZ_Newsletter() {
        $widget_ops = array('classname' => 'widget_PRiNZ_newsletter', 'description' => __('Newsletter form for Feedburner eMail Subscription','branfordmagazine') );
		$this->WP_Widget('PRiNZ_newsletter', __('PRiNZ Newsletter','branfordmagazine'), $widget_ops);
    
    }
 
    function widget($args, $instance) {        
        extract( $args );
        
        $title 	= strip_tags($instance['title']);
        $user	= empty($instance['user']) ? get_option('prinz_newsletter') : $instance['user'];
        $text	= empty($instance['text']) ? __('Signup to our Newsletter','branfordmagazine') : $instance['text'];
 
        ?>
<?php echo $before_widget; ?>
<?php if($title) echo $before_title . $title . $after_title; ?>
<div id="newsletter">
    <form action="http://feedburner.google.com/fb/a/mailverify" method="post" target="popupwindow" onsubmit="window.open('http://feedburner.google.com/fb/a/mailverify?uri=<?php echo $user; ?>', 'popupwindow', 'scrollbars=yes,width=550,height=520');return true" class="clearfix">
      <input type="text" id="newsletter-text" class="newsletter" name="email" value="<?php echo $text; ?>..." onfocus="if (this.value == '<?php echo $text; ?>...') {this.value = '';}" onblur="if (this.value == '') {this.value = '<?php echo $text; ?>...';}" />
      <input type="hidden" value="<?php echo $user; ?>" name="uri" />
      <input type="hidden" name="loc" value="<?php bloginfo('language'); ?>"/>
      <input name="submit" class="newsletter-button" type="submit" id="newsleter-submit" tabindex="5" value="<?php _e('Submit','branfordmagazine'); ?>" />
    </form>
  </div>
<?php echo $after_widget; ?>
<?php
    }

    function update($new_instance, $old_instance) {  
    
    	$instance['title'] = strip_tags($new_instance['title']);
    	$instance['user'] = strip_tags($new_instance['user']);
    	$instance['text'] = strip_tags($new_instance['text']);
                  
        return $new_instance;
    }
 
    function form($instance) {
        
		$instance	= wp_parse_args( (array) $instance, array( 'title' => '', 'user' => '', 'text' => '') );
		$title 		= strip_tags($instance['title']);
		$user		= empty($instance['user']) ? get_option('prinz_newsletter') : $instance['user'];
		$text		= empty($instance['text']) ? __('Signup to our Newsletter','branfordmagazine') : $instance['text'];
		
?>
<p>
  <label for="<?php echo $this->get_field_id('title'); ?>">
    <?php _e('Title'); ?>
    :
    <input id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo attribute_escape($title); ?>" />
  </label>
</p>
<p>
  <label for="<?php echo $this->get_field_id('user'); ?>">
    <?php _e('Feedburner ID'); ?>
    :
    <input id="<?php echo $this->get_field_id('user'); ?>" name="<?php echo $this->get_field_name('user'); ?>" type="text" value="<?php echo attribute_escape($user); ?>" />
  </label>
</p>
<p>
  <label for="<?php echo $this->get_field_id('text'); ?>">
    <?php _e('Text'); ?>
    :
    <input id="<?php echo $this->get_field_id('text'); ?>" name="<?php echo $this->get_field_name('text'); ?>" type="text" value="<?php echo attribute_escape($text); ?>" />
  </label>
</p>
<?php
	}

}
 
register_widget('PRiNZ_Newsletter');


/*	##################################
	TWITTER WIDGET
	################################## */

class PRiNZ_Twitter extends WP_Widget {
 
	function PRiNZ_Twitter() {
        $widget_ops = array('classname' => 'widget_PRiNZ_twitter', 'description' => __('Display latest Tweets','branfordmagazine') );
		$this->WP_Widget('PRiNZ_twitter', __('PRiNZ Twitter','branfordmagazine'), $widget_ops);
    
    }
 
    function widget($args, $instance) {        
        extract( $args );
        
        $title	= empty($instance['title']) ? __('Latest Tweets','branfordmagazine') : $instance['title'];
        $user	= empty($instance['user']) ? get_option('prinz_twitter') : $instance['user'];
        $link	= $instance['twitter_link'] ? '1' : '0';
        $label	= empty($instance['twitter_label']) ? __('More updates on Twitter','branfordmagazine') : $instance['twitter_label'];
        if ( !$nr = (int) $instance['twitter_nr'] )
			$nr = 5;
		else if ( $nr < 1 )
			$nr = 1;
		else if ( $nr > 15 )
			$nr = 15;
 
        ?>
<?php echo $before_widget; ?> <?php echo $before_title . $title . $after_title; ?>
  <ul id="twitter_update_list">
    <li></li>
  </ul>
<script type="text/javascript" src="http://twitter.com/javascripts/blogger.js"></script>
<script type="text/javascript" src="http://twitter.com/statuses/user_timeline/<?php echo $user; ?>.json?callback=twitterCallback2&amp;count=<?php echo $nr; ?>"></script>
<?php if($link) : ''; ?>
<p style="margin-left:20px;" ><a href="http://twitter.com/<?php echo $user; ?>"><span><?php echo $label; ?></span></a></p>
<?php endif; ?>
<?php echo $after_widget; ?>
<?php
    }

    function update($new_instance, $old_instance) {  
    
    	$instance['title'] = strip_tags($new_instance['title']);
    	$instance['user'] = strip_tags($new_instance['user']);
    	$instance['twitter_link'] = $new_instance['twitter_link'] ? 1 : 0;
    	$instance['twitter_label'] = strip_tags($new_instance['twitter_label']);
    	$instance['twitter_nr'] = (int) $new_instance['twitter_nr'];
                  
        return $new_instance;
    }
 
    function form($instance) {
        
		$instance	= wp_parse_args( (array) $instance, array( 'title' => '', 'user' => '', 'twitter_link' => '', 'twitter_label' => '', 'twitter_nr' => '') );
		$title 		= strip_tags($instance['title']);
		$user		= empty($instance['user']) ? get_option('prinz_twitter') : $instance['user'];
		$link 		= strip_tags($instance['twitter_link']);
		$label 		= strip_tags($instance['twitter_label']);
		if (!$nr = (int) $instance['twitter_nr']) $nr = 5;
?>
<p>
  <label for="<?php echo $this->get_field_id('title'); ?>">
    <?php _e('Title'); ?>
    :
    <input id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo attribute_escape($title); ?>" />
  </label>
</p>
<p>
  <label for="<?php echo $this->get_field_id('user'); ?>">
    <?php _e('User'); ?>
    :
    <input id="<?php echo $this->get_field_id('user'); ?>" name="<?php echo $this->get_field_name('user'); ?>" type="text" value="<?php echo attribute_escape($user); ?>" />
  </label>
</p>
<p>
  <label for="<?php echo $this->get_field_id('twitter_nr'); ?>">
    <?php _e('Number of tweets to show','branfordmagazine'); ?>
    :</label>
  <input id="<?php echo $this->get_field_id('twitter_nr'); ?>" name="<?php echo $this->get_field_name('twitter_nr'); ?>" type="text" value="<?php echo $nr; ?>" size="3" />
  <br />
  <small>
  <?php _e('(max. 15)'); ?>
  </small> </p>
<p>
  <input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id('twitter_link'); ?>" name="<?php echo $this->get_field_name('twitter_link'); ?>"<?php checked( $link ); ?> />
  <label for="<?php echo $this->get_field_id('twitter_link'); ?>">
    <?php _e('Show link to Twitter','branfordmagazine'); ?>
  </label>
</p>
<p>
  <label for="<?php echo $this->get_field_id('twitter_label'); ?>">
    <?php _e('Link label','branfordmagazine'); ?>
    :
    <input id="<?php echo $this->get_field_id('twitter_label'); ?>" name="<?php echo $this->get_field_name('twitter_label'); ?>" type="text" value="<?php echo attribute_escape($label); ?>" />
  </label>
</p>
<?php
	}

}
 
register_widget('PRiNZ_Twitter');



/*	##################################
	RECENT POSTS WIDGET
	################################## */

class PRiNZ_Recent extends WP_Widget {

	function PRiNZ_Recent() {
	
		$widget_ops = array('classname' => 'widget_prinz_recent', 'description' => __('Display recent posts','branfordmagazine') );
		$this->WP_Widget('prinz_recent', __('PRiNZ Recent Posts','branfordmagazine'), $widget_ops);
		$this->alt_option_name = 'widget_recent_entries';

		add_action( 'save_post', array(&$this, 'flush_widget_cache') );
		add_action( 'deleted_post', array(&$this, 'flush_widget_cache') );
		add_action( 'switch_theme', array(&$this, 'flush_widget_cache') );
	}

	function widget($args, $instance) {
		$cache = wp_cache_get('widget_prinz_recent', 'widget');

		if ( !is_array($cache) )
			$cache = array();

		if ( isset($cache[$args['widget_id']]) ) {
			echo $cache[$args['widget_id']];
			return;
		}

		ob_start();
		extract($args);

		$title = apply_filters('widget_title', empty($instance['title']) ? __('Recent Posts','branfordmagazine') : $instance['title']);
		if ( !$number = (int) $instance['number'] )
			$number = 10;
		else if ( $number < 1 )
			$number = 1;
		else if ( $number > 15 )
			$number = 15;
			

		$r = new WP_Query(array('showposts' => $number, 'nopaging' => 0, 'post_status' => 'publish', 'caller_get_posts' => 1));
		if ($r->have_posts()) :
?>
		<?php echo $before_widget; ?>
		<?php if ( $title ) echo $before_title . $title . $after_title; ?>
			<?php  while ($r->have_posts()) : $r->the_post(); ?>
			<ul class="bullets">
			<li><a href="<?php the_permalink() ?>" title="<?php echo esc_attr(get_the_title() ? get_the_title() : get_the_ID()); ?>"><?php if ( get_the_title() ) the_title(); else the_ID(); ?> </a></li>
			</ul>
			<?php endwhile; ?>
		<?php echo $after_widget; ?>
<?php
			wp_reset_query();  // Restore global post data stomped by the_post().
		endif;

		$cache[$args['widget_id']] = ob_get_flush();
		wp_cache_add('widget_prinz_recent', $cache, 'widget');
	}

	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['number'] = (int) $new_instance['number'];
		$this->flush_widget_cache();

		$alloptions = wp_cache_get( 'alloptions', 'options' );

		return $instance;
	}

	function flush_widget_cache() {
		wp_cache_delete('widget_prinz_recent', 'widget');
	}

	function form( $instance ) {
		$title = esc_attr($instance['title']);
		if ( !$number = (int) $instance['number'] ) $number = 10;
?>
		<p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title'); ?>:</label>
		<input id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" /></p>

		<p><label for="<?php echo $this->get_field_id('number'); ?>"><?php _e('Number of posts to show:'); ?></label>
		<input id="<?php echo $this->get_field_id('number'); ?>" name="<?php echo $this->get_field_name('number'); ?>" type="text" value="<?php echo $number; ?>" size="3" /><br />
		<small><?php _e('(max. 15)'); ?></small></p>
		
<?php
	}
}

register_widget('PRiNZ_Recent');




/*	##################################
   	RECENT COMMENTS WIDGET
	################################## */ 
	
class PRiNZ_Comments extends WP_Widget {
 
	function PRiNZ_Comments() {
        $widget_ops = array('classname' => 'widget_PRiNZ_comments', 'description' => __('Display recent comments','branfordmagazine') );
		$this->WP_Widget('PRiNZ_comments', __('PRiNZ Comments','branfordmagazine'), $widget_ops);
    
    }
 
    function widget($args, $instance) {        
        extract( $args );
        
        $title	= empty($instance['title']) ? __('Recent Comments','branfordmagazine') : apply_filters('widget_title', $instance['title']);
        
        if ( !$nr = (int) $instance['comments_nr'] )
			$nr = 5;
		else if ( $nr < 1 )
			$nr = 1;
		else if ( $nr > 15 )
			$nr = 15;
			
		if ( !$exc = (int) $instance['comments_exc'] ) $exc = 75;
		
		
 
        ?>
<?php echo $before_widget; ?> <?php echo $before_title . $title . $after_title; ?>
    <?php PRiNZ_recent_comments($nr,$exc); ?>
<?php echo $after_widget; ?>
<?php
    }

    function update($new_instance, $old_instance) {  
    
    	$instance['title'] = strip_tags($new_instance['title']);
    	$instance['comments_nr'] = (int) $new_instance['comments_nr'];
    	$instance['comments_exc'] = (int) $new_instance['comments_exc'];
                  
        return $new_instance;
    }
 
    function form($instance) {
        
		$instance = wp_parse_args( (array) $instance, array( 'title' => '', 'comments_nr' => '', 'boxed' => '') );
		$title = strip_tags($instance['title']);
		if (!$nr = (int) $instance['comments_nr']) $nr = 5;
		if (!$exc = (int) $instance['comments_exc']) $exc = 75;
?>
<p>
  <label for="<?php echo $this->get_field_id('title'); ?>">
    <?php _e('Title'); ?>
    :
    <input id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo attribute_escape($title); ?>" />
  </label>
</p>
<p>
  <label for="<?php echo $this->get_field_id('comments_nr'); ?>">
    <?php _e('Number of comments','branfordmagazine'); ?>
    :</label>
  <input id="<?php echo $this->get_field_id('comments_nr'); ?>" name="<?php echo $this->get_field_name('comments_nr'); ?>" type="text" value="<?php echo $nr; ?>" size="3" />
  <br />
  <small>
  <?php _e('(max. 15)'); ?>
  </small> </p>
<p>
  <label for="<?php echo $this->get_field_id('comments_exc'); ?>">
    <?php _e('Length of comment excerpt','branfordmagazine'); ?>
    :</label>
  <input id="<?php echo $this->get_field_id('comments_exc'); ?>" name="<?php echo $this->get_field_name('comments_exc'); ?>" type="text" value="<?php echo $exc; ?>" size="3" />
</p>
<?php
	}

}

function PRiNZ_recent_comments($rc_count=5, $rc_length=75, $rc_pre='<ul class="bullets">', $rc_post='</ul>') {

global $wpdb;

$sql = "SELECT DISTINCT ID,
		post_title, post_password, comment_ID, comment_post_ID, comment_author, comment_date_gmt, comment_approved, comment_type, 
		SUBSTRING(comment_content,1,$rc_length) AS com_excerpt 
		FROM $wpdb->comments 
		LEFT OUTER JOIN $wpdb->posts ON ($wpdb->comments.comment_post_ID = $wpdb->posts.ID) 
		WHERE comment_approved = '1' AND comment_type = '' AND post_password = '' 
		ORDER BY comment_date_gmt DESC 
		LIMIT $rc_count";
		
$comments = $wpdb->get_results($sql);

$output = $rc_pre;
foreach ($comments as $comment) {
	$rc_dots = "...";
	$output .= "\n\t<li><p>" . $comment->comment_author . " ".__('on','branfordmagazine')." <a href=\"" . get_permalink($comment->ID) . "#comment-" . $comment->comment_ID  . "\" title=\"" . $comment->post_title . "\">" . $comment->post_title . "</a>:<br />" . strip_tags($comment->com_excerpt) . $rc_dots . "</p></li>";
}
$output .= $rc_post;
	
echo $output;

}

register_widget('PRiNZ_Comments');


/*	##################################
	ABOUT WIDGET
	################################## */

class PRiNZ_About extends WP_Widget {
 
	function PRiNZ_About() {
        $widget_ops = array('classname' => 'widget_PRiNZ_about', 'description' => __('Display an about section in the Sidebar','branfordmagazine') );
		$this->WP_Widget('PRiNZ_about', __('PRiNZ About','branfordmagazine'), $widget_ops);
    
    }
 
    function widget($args, $instance) {        
        extract( $args );
        
        $title	= empty($instance['title']) ? __('About this Site','branfordmagazine') : $instance['title'];
        $avatar	= $instance['about_avatar'] ? '1' : '0';
        $text	= empty($instance['about_text']) ? __('Your text about you.','branfordmagazine') : $instance['about_text'];
        $link 	= (int) $instance['about_link'];
        $label	= empty($instance['about_label']) ? __('More about us','branfordmagazine') : $instance['about_label'];
 
        ?>
<?php echo $before_widget; ?>
<?php echo $before_title . $title . $after_title; ?>
<?php if($avatar) : ?>
<div class="alignleft"><?php echo get_avatar(get_bloginfo('admin_email'),'80',get_bloginfo('template_url').'/img/default-avatar.png'); ?></div>
<?php endif; ?>
<p><?php echo nl2br($text); ?></p>
<div class="clear"></div>
<?php if($link) : $size = (get_option('prinz_size')) ? '-big' : ''; ?>
<p style="margin:0"><a href="<?php echo get_permalink($link); ?>"><span><?php echo $label; ?></span></a></p>
<?php endif; ?>
<?php echo $after_widget; ?>
<?php
    }

    function update($new_instance, $old_instance) {  
    
    	$instance['title'] = strip_tags($new_instance['title']);
    	$instance['about_avatar'] = $new_instance['about_avatar'] ? 1 : 0;
    	$instance['about_text'] = strip_tags($new_instance['about_text']);
    	$instance['about_link'] = (int) $new_instance['about_link'];
    	$instance['about_label'] = strip_tags($new_instance['about_label']);
                  
        return $new_instance;
    }
 
    function form($instance) {
        
		$instance	= wp_parse_args( (array) $instance, array( 'title' => '', 'about_avatar' => '', 'about_text' => '', 'about_link' => '', 'about_label' => '') );
		$title 		= strip_tags($instance['title']);
		$avatar 	= (bool) $instance['about_avatar'];
		$text 		= strip_tags($instance['about_text']);
		if (!$link 	= (int) $instance['about_link']) $link = '';
		$label 		= strip_tags($instance['about_label']);
?>
<p>
  <label for="<?php echo $this->get_field_id('title'); ?>">
    <?php _e('Title'); ?>
    :
    <input id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo attribute_escape($title); ?>" />
  </label>
</p>
<p>
  <input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id('about_avatar'); ?>" name="<?php echo $this->get_field_name('about_avatar'); ?>"<?php checked( $avatar ); ?> />
  <label for="<?php echo $this->get_field_id('about_avatar'); ?>">
    <?php _e('Show admin\'s avatar','branfordmagazine'); ?>
  </label>
</p>
<p>
  <label for="<?php echo $this->get_field_id('about_text'); ?>">
    <?php _e('Text','branfordmagazine'); ?>
    :
    <textarea rows="5" cols="20" id="<?php echo $this->get_field_id('about_text'); ?>" name="<?php echo $this->get_field_name('about_text'); ?>"><?php echo attribute_escape($text); ?></textarea>
  </label>
</p>
<p>
  <label for="<?php echo $this->get_field_id('about_link'); ?>">
    <?php _e('Post/page ID for link','branfordmagazine'); ?>
    :</label>
  <input id="<?php echo $this->get_field_id('about_link'); ?>" name="<?php echo $this->get_field_name('about_link'); ?>" type="text" value="<?php echo $link; ?>" size="3" />
</p>
<p>
  <label for="<?php echo $this->get_field_id('about_label'); ?>">
    <?php _e('Link label','branfordmagazine'); ?>
    :
    <input id="<?php echo $this->get_field_id('about_label'); ?>" name="<?php echo $this->get_field_name('about_label'); ?>" type="text" value="<?php echo attribute_escape($label); ?>" />
  </label>
</p>
<?php
	}

}
 
register_widget('PRiNZ_About');


/*	##################################
	CONTACT WIDGET
	################################## */

class PRiNZ_Contact extends WP_Widget {
 
	function PRiNZ_Contact() {
        $widget_ops = array('classname' => 'widget_PRiNZ_contact', 'description' => __('Display contact info in the Sidebar','branfordmagazine') );
		$this->WP_Widget('PRiNZ_contact', __('PRiNZ Contact Info','branfordmagazine'), $widget_ops);
    
    }
 
    function widget($args, $instance) {        
        extract( $args );
        
        $title	= empty($instance['title']) ? __('Contact Info','branfordmagazine') : $instance['title'];
        $name	= empty($instance['contact_name']) ? '' : $instance['contact_name'];
        $street	= empty($instance['contact_street']) ? '' : $instance['contact_street'];
        $city	= empty($instance['contact_city']) ? '' : $instance['contact_city'];
        $phone	= empty($instance['contact_phone']) ? '' : $instance['contact_phone'];
        $email	= empty($instance['contact_email']) ? '' : $instance['contact_email'];
 
        ?>
<?php echo $before_widget; ?> <?php echo $before_title . $title . $after_title; ?>
<ul style="margin:0">
  <?php if($name) echo '<li>'.$name.'</li>'; ?>
  <?php if($street) echo '<li>'.$street.'</li>'; ?>
  <?php if($city) echo '<li>'.$city.'</li>'; ?>
  <?php if($phone) echo '<li>'.$phone.'</li>'; ?>
  <?php if($email) echo '<li><a href="mailto:'.antispambot($email).'">'.__('Send us an Email','branfordmagazine').'</a></li>'; ?>
</ul>
<?php echo $after_widget; ?>
<?php
    }

    function update($new_instance, $old_instance) {  
    
    	$instance['title'] = strip_tags($new_instance['title']);
    	$instance['contact_name'] = strip_tags($new_instance['contact_name']);
    	$instance['contact_street'] = strip_tags($new_instance['contact_street']);
    	$instance['contact_city'] = strip_tags($new_instance['contact_city']);
    	$instance['contact_phone'] = strip_tags($new_instance['contact_phone']);
    	$instance['contact_email'] = strip_tags($new_instance['contact_email']);
                  
        return $new_instance;
    }
 
    function form($instance) {
        
		$instance	= wp_parse_args( (array) $instance, array( 'title' => '', 'contact_name' => '', 'contact_street' => '', 'contact_city' => '', 'contact_phone' => '', 'contact_email' => '') );
		$title 		= strip_tags($instance['title']);
		$name 		= strip_tags($instance['contact_name']);
		$street		= strip_tags($instance['contact_street']);
		$city 		= strip_tags($instance['contact_city']);
		$phone 		= strip_tags($instance['contact_phone']);
		$email 		= strip_tags($instance['contact_email']);
?>
<p>
  <label for="<?php echo $this->get_field_id('title'); ?>">
    <?php _e('Title'); ?>
    :
    <input id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo attribute_escape($title); ?>" />
  </label>
</p>
<p>
  <label for="<?php echo $this->get_field_id('contact_name'); ?>">
    <?php _e('Name','branfordmagazine'); ?>
    :
    <input id="<?php echo $this->get_field_id('contact_name'); ?>" name="<?php echo $this->get_field_name('contact_name'); ?>" type="text" value="<?php echo attribute_escape($name); ?>" />
  </label>
</p>
<p>
  <label for="<?php echo $this->get_field_id('contact_street'); ?>">
    <?php _e('Street','branfordmagazine'); ?>
    :
    <input id="<?php echo $this->get_field_id('contact_street'); ?>" name="<?php echo $this->get_field_name('contact_street'); ?>" type="text" value="<?php echo attribute_escape($street); ?>" />
  </label>
</p>
<p>
  <label for="<?php echo $this->get_field_id('contact_city'); ?>">
    <?php _e('ZIP City','branfordmagazine'); ?>
    :
    <input id="<?php echo $this->get_field_id('contact_city'); ?>" name="<?php echo $this->get_field_name('contact_city'); ?>" type="text" value="<?php echo attribute_escape($city); ?>" />
  </label>
</p>
<p>
  <label for="<?php echo $this->get_field_id('contact_phone'); ?>">
    <?php _e('Phone','branfordmagazine'); ?>
    :
    <input id="<?php echo $this->get_field_id('contact_phone'); ?>" name="<?php echo $this->get_field_name('contact_phone'); ?>" type="text" value="<?php echo attribute_escape($phone); ?>" />
  </label>
</p>
<p>
  <label for="<?php echo $this->get_field_id('contact_email'); ?>">
    <?php _e('Email (spam-protected)','branfordmagazine'); ?>
    :
    <input id="<?php echo $this->get_field_id('contact_email'); ?>" name="<?php echo $this->get_field_name('contact_email'); ?>" type="text" value="<?php echo attribute_escape($email); ?>" />
  </label>
</p>
<?php
	}

}
 
register_widget('PRiNZ_Contact');



/*	##################################
	UNREGISTER WIDGETS
	################################## */
	
function unregister_default_wp_widgets() {

    // unregister_widget('WP_Widget_Recent_Posts');
}

add_action('widgets_init', 'unregister_default_wp_widgets', 1);

?>