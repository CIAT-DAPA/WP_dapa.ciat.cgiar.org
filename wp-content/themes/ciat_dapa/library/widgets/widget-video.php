<?php
/*
 * Plugin Name: Themedy - Video Widget
 * Description: A widget that displays your latest video
 * Version: 1.0
 */

/*
 * Add function to widgets_init that'll load our widget.
 */
add_action( 'widgets_init', 'themedy_video_widgets' );

/*
 * Register widget.
 */
function themedy_video_widgets() {
	register_widget( 'Themedy_Video_Widget' );
}

/*
 * Widget class.
 */
class themedy_video_widget extends WP_Widget {

	/* ---------------------------- */
	/* -------- Widget setup -------- */
	/* ---------------------------- */
	
	function Themedy_Video_Widget() {
	
		/* Widget settings. */
		$widget_ops = array( 'classname' => 'themedy_video_widget', 'description' => __('A widget that displays your YouTube or Vimeo Video.', 'framework') );

		/* Widget control settings. */
		$control_ops = array( 'width' => 300, 'height' => 350, 'id_base' => 'themedy_video_widget' );

		/* Create the widget. */
		$this->WP_Widget( 'themedy_video_widget', __('Themedy - Video Widget', 'framework'), $widget_ops, $control_ops );
	}

	/* ---------------------------- */
	/* ------- Display Widget -------- */
	/* ---------------------------- */
	
	function widget( $args, $instance ) {
		extract( $args );

		/* Our variables from the widget settings. */
		$title = apply_filters('widget_title', $instance['title'] );
		$embed = $instance['embed'];
		$desc = $instance['desc'];

		/* Before widget (defined by themes). */
		echo $before_widget;

		/* Display the widget title if one was input (before and after defined by themes). */
		if ( $title )
			echo $before_title . $title . $after_title;

		/* Display Widget */
		?>
			
			<div class="themedy_video">
			<?php echo $embed ?>
			</div>
			<p class="themedy_video_desc"><?php echo $desc ?></p>
		
		<?php

		/* After widget (defined by themes). */
		echo $after_widget;
	}

	/* ---------------------------- */
	/* ------- Update Widget -------- */
	/* ---------------------------- */
	
	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;

		/* Strip tags to remove HTML (important for text inputs). */
		$instance['title'] = strip_tags( $new_instance['title'] );
		
		/* Stripslashes for html inputs */
		$instance['desc'] = stripslashes( $new_instance['desc']);
		$instance['embed'] = stripslashes( $new_instance['embed']);

		/* No need to strip tags for.. */

		return $instance;
	}
	
	/* ---------------------------- */
	/* ------- Widget Settings ------- */
	/* ---------------------------- */
	
	/**
	 * Displays the widget settings controls on the widget panel.
	 * Make use of the get_field_id() and get_field_name() function
	 * when creating your form elements. This handles the confusing stuff.
	 */
	 
	function form( $instance ) {

		/* Set up some default widget settings. */
		$defaults = array(
		'title' => 'My Video',
		'embed' => stripslashes( '<iframe src="http://player.vimeo.com/video/13085676?title=0&amp;byline=0&amp;portrait=0" width="300" height="225" frameborder="0"></iframe>'),
		'desc' => 'Type your description here!',
		);
		$instance = wp_parse_args( (array) $instance, $defaults ); ?>

		<!-- Widget Title: Text Input -->
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e('Title:', 'framework') ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" />
		</p>

		<!-- Embed Code: Text Input -->
		<p>
			<label for="<?php echo $this->get_field_id( 'embed' ); ?>"><?php _e('Embed Code (Best at 300px wide)', 'framework') ?></label>
			<textarea style="height:200px;" class="widefat" id="<?php echo $this->get_field_id( 'embed' ); ?>" name="<?php echo $this->get_field_name( 'embed' ); ?>"><?php echo stripslashes(htmlspecialchars(( $instance['embed'] ), ENT_QUOTES)); ?></textarea>
		</p>
		
		<!-- Description: Text Input -->
		<p>
			<label for="<?php echo $this->get_field_id( 'desc' ); ?>"><?php _e('Short Description:', 'framework') ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'desc' ); ?>" name="<?php echo $this->get_field_name( 'desc' ); ?>" value="<?php echo stripslashes(htmlspecialchars(( $instance['desc'] ), ENT_QUOTES)); ?>" />
		</p>
		
	<?php
	}
}
?>