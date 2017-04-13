<?php
/*
 * Plugin Name: Themedy - 468x60 Ad Unit
 * Description: A widget that allows the selection and configuration of a single 468x60 Banner
 * Version: 1.0
 */

/*
 * Add function to widgets_init that'll load our widget.
 */
add_action( 'widgets_init', 'themedy_ad468_widgets' );

/*
 * Register widget.
 */
function themedy_ad468_widgets() {
	register_widget( 'Themedy_Ad468_Widget' );
}

/*
 * Widget class.
 */
class themedy_ad468_widget extends WP_Widget {

	/* ---------------------------- */
	/* -------- Widget setup -------- */
	/* ---------------------------- */
	
	function Themedy_Ad468_Widget() {
	
		/* Widget settings */
		$widget_ops = array( 'classname' => 'themedy_ad468_widget', 'description' => __('A widget that allows the display and configuration of of a single 468x60 Banner.', 'framework') );

		/* Widget control settings */
		//$control_ops = array( 'width' => 468, 'height' => 350, 'id_base' => 'themedy_ad468_widget' );

		/* Create the widget */
		$this->WP_Widget( 'themedy_ad468_widget', __('Themedy - 468x60 Ad', 'framework'), $widget_ops, $control_ops );
	}

	/* ---------------------------- */
	/* ------- Display Widget -------- */
	/* ---------------------------- */
	
	function widget( $args, $instance ) {
		extract( $args );

		/* Our variables from the widget settings. */
		$title = apply_filters('widget_title', $instance['title'] );
		$ad = $instance['ad'];
		$link = $instance['link'];

		/* Before widget (defined by themes). */
		echo $before_widget;

		/* Display the widget title if one was input (before and after defined by themes). */
		if ( $title )
			echo $before_title . $title . $after_title;
			
		/* Display a containing div */
		echo '<div class="ads-468">';

		/* Display Ad */
		if ( $link )
			echo '<a href="' . $link . '"><img src="' . $ad . '" width="468" height="60" alt="" /></a>';
			
		elseif ( $ad )
		 	echo '<img src="' . $ad . '" width="468" height="60" alt="" />';
			
		echo '</div>';

		/* After widget (defined by themes). */
		echo $after_widget;
	}

	/* ---------------------------- */
	/* ------- Update Widget -------- */
	/* ---------------------------- */
	
	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;

		/* Strip tags for title and name to remove HTML (important for text inputs). */
		$instance['title'] = strip_tags( $new_instance['title'] );

		/* No need to strip tags */
		$instance['ad'] = $new_instance['ad'];
		$instance['link'] = $new_instance['link'];

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
		'title' => '',
		'ad' => get_bloginfo('stylesheet_directory')."/images/ad-468x60.png",
		'link' => 'http://www.themedy.com',
		);
		$instance = wp_parse_args( (array) $instance, $defaults ); ?>

		<!-- Widget Title: Text Input -->
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e('Title:', 'framework') ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" />
		</p>

		<!-- Ad image url: Text Input -->
		<p>
			<label for="<?php echo $this->get_field_id( 'ad' ); ?>"><?php _e('Ad image url:', 'framework') ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'ad' ); ?>" name="<?php echo $this->get_field_name( 'ad' ); ?>" value="<?php echo $instance['ad']; ?>" />
		</p>
		
		<!-- Ad link url: Text Input -->
		<p>
			<label for="<?php echo $this->get_field_id( 'link' ); ?>"><?php _e('Ad link url:', 'framework') ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'link' ); ?>" name="<?php echo $this->get_field_name( 'link' ); ?>" value="<?php echo $instance['link']; ?>" />
		</p>
		
	<?php
	}
}
?>