<?php
/*
Plugin Name: Post's Categories in Sidebar
Plugin URI: http://wordpress.org/extend/plugins/posts-categories-in-sidebar/
Description: Displays the Categories for a single post, as a widget.
Author: Corey Zev
Version: 1.0
Author URI: http://zevdesigns.com
    
*/

class Post_Cat_Widget extends WP_Widget {

	function Post_Cat_Widget() {
		$widget_ops = array('classname' => 'widget_cz_post_cat', 'description' => __('Displays the Categories for a single post, as a widget.'));
		$control_ops = array('width' => 200, 'height' => 200);
		$this->WP_Widget('cz_post_cat', __("Post's Categories in Sidebar"), $widget_ops, $control_ops);
	}

	function widget( $args, $instance ) {
		extract($args);
		if(is_single()){
		// Would not work on any other page than the "Single"
			$title = apply_filters( 'widget_title', empty($instance['title']) ? '' : $instance['title'], $instance );
			echo $before_widget;
			echo $before_title . $title . $after_title;
			echo '<div class="cz-post-cats">';
		 	global $post;
			$sing_categories = get_the_category($post->ID);
			//Display The Post's Categories
			echo '<ul class="cz-cat-ul">';
			foreach($sing_categories as $category) { 
			echo '<li class="cz-cat-li">';
			echo '<a href="'.get_category_link($category->term_id ).'">'.$category->cat_name.'</a>';
			echo '</li>';
			}		
			echo '</ul>';	
			echo '</div>';
		echo $after_widget;
		}
	}

	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
		if ( current_user_can('unfiltered_html') )
			$instance['text'] =  $new_instance['text'];
		else
			$instance['text'] = stripslashes( wp_filter_post_kses( $new_instance['text'] ) );
		$instance['filter'] = isset($new_instance['filter']);
		return $instance;
	}

	function form( $instance ) {
		$instance = wp_parse_args( (array) $instance, array( 'title' => '', 'text' => '' ) );
		$title = strip_tags($instance['title']);
?>
		<p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" /></p>
		
<?php
	}
}

add_action('widgets_init', create_function('', 'return register_widget("Post_Cat_Widget");'));