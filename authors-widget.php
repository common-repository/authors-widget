<?php
/*
Plugin Name: List Authors Widget
Description: Enables a widget which lists the authors on a blog.
Author: Frankie Roberto
Author URI: http://www.frankieroberto.com
Version: 0.1
*/

class AuthorsWidget extends WP_Widget {

	function AuthorsWidget() {
		$widget_ops = array('classname' => 'authors_widget', 'description' => __('Display a list of authors'));
		$this->WP_Widget('authors', __('Authors'), $widget_ops, $control_ops);

	}

	function widget( $args, $instance ) {
		global $wpdb;

		extract($args);
		$title = apply_filters('widget_title', empty($instance['title']) ? '' : $instance['title']);

		$authors = $wpdb->get_results("SELECT ID from $wpdb->users;");

		echo $before_widget;
		echo $before_title;
		echo $title;

		echo $after_title;?>

			<ul>
				<?php foreach ($authors as $author) :
					$author = get_userdata( $author->ID );
					$num_posts = get_usernumposts($author->ID);
					if ($num_posts > 0) :
				 ?>
				<li><a href="<?php echo get_author_posts_url($author->ID); ?>"><?php echo $author->display_name;?></a></li>
				<?php endif; endforeach; ?>
			</ul>

		<?php
		echo $after_widget;
	}

	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
		return $instance;
	}

	function form( $instance ) {
		$title = strip_tags($instance['title']);
?>
		<p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" /></p>
<?php
	}
}

add_action('widgets_init', create_function('', 'return register_widget("AuthorsWidget");'));


?>