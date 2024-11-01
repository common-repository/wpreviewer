<?php
namespace Reviewer\Widgets;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Categories widget.
 *
 * The categories widget lists all the available categories.
 *
 * @since 1.0.0
 */
class Categories extends Abstract_Widget {


	/**
	 * Constructor.
	 *
	 * Initialize this class including hooks.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {

		$name = __( 'Reviewer Categories', 'reviewer' );
		$widget_options = array(
			'description' => __( 'List the review categories', 'reviewer' ),
		);

		parent::__construct( $id = 'review_categories', $name, $widget_options );

	}


	/**
	 * Actual output.
	 *
	 * Actual output that the widget does.
	 *
	 * @since 1.0.0
	 *
	 * @param  array  $args      Arguments available for the widget.
	 * @param  array  $instance  Settings instance of the widget.
	 */
	public function widget( $args, $instance ) {

		echo $args['before_widget'];

		if ( ! empty( $instance['title'] ) ) {
			echo $args['before_title'] . apply_filters( 'widget_title', $instance['title'] ) . $args['after_title'];
		}

		$category_args = array(
			'title_li'   => '',
			'hide_empty' => ! empty( $instance['show_empty'] ) ? false : true,
			'order'      => 'ASC',
			'orderby'    => 'name',
			'taxonomy'   => 'review_category',
			'show_count' => ! empty( $instance['show_count'] ) ? true : false,
			'separator'  => '<br/>',
		);

		wp_list_categories( apply_filters( '\reviewer\widget\categories\args', $category_args ) );

		echo $args['after_widget'];

	}


	/**
	 * Set and sanitize settings.
	 *
	 * @since 1.0.0
	 *
	 * @param   array  $new_instance  New values posted through the form.
	 * @param   array  $old_instance  Old values.
	 * @return  array                 Sanitized values to be saved.
	 */
	public function update( $new_instance, $old_instance ) {

		$instance               = array();
		$instance['title']      = ! empty( $new_instance['title'] ) ? sanitize_text_field( $new_instance['title'] ) : '';
		$instance['show_count'] = ! empty( $new_instance['show_count'] ) ? true : false;
		$instance['show_empty'] = ! empty( $new_instance['show_empty'] ) ? true : false;

		return $instance;

	}


	/**
	 * Output widget admin form.
	 *
	 * Output the contents of the widget settings form.
	 *
	 * @since 1.0.0
	 *
	 * @param   array   $instance  Instance of the widget.
	 * @return  string             No idea why this function needs a return (parent::form).
	 */
	public function form( $instance ) {

		$title = ! empty( $instance['title'] ) ? $instance['title'] : '';
		?><p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
		</p>
		<p>
			<input class="checkbox " id="<?php echo $this->get_field_id( 'show_count' ); ?>" name="<?php echo $this->get_field_name( 'show_count' ); ?>" type="checkbox" <?php echo checked( ! empty( $instance['show_count'] ) ); ?> value="1">
			<label for="<?php echo $this->get_field_id( 'show_count' ); ?>"><?php _e( 'Show the number of reviews', 'reviewer' ); ?></label>
		</p>
		<p>
			<input class="checkbox " id="<?php echo $this->get_field_id( 'show_empty' ); ?>" name="<?php echo $this->get_field_name( 'show_empty' ); ?>" type="checkbox" <?php echo checked( ! empty( $instance['show_empty'] ) ); ?> value="1">
			<label for="<?php echo $this->get_field_id( 'show_empty' ); ?>"><?php _e( 'Show empty categories', 'reviewer' ); ?></label>
		</p><?php

		return 'form';

	}


}
