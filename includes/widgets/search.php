<?php
namespace Reviewer\Widgets;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Search widget.
 *
 * The search widget allows one to search specifically for reviews.
 *
 * @since 1.0.0
 */
class Search extends Abstract_Widget {


	/**
	 * Constructor.
	 *
	 * Initialize this class including hooks.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {

		$name = __( 'Reviewer Search', 'reviewer' );
		$widget_options = array(
			'description' => __( 'A search form for searching through reviews', 'reviewer' ),
		);

		parent::__construct( $id = 'review_search', $name, $widget_options );

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

		\Reviewer\get_template( 'global/search-form.php' );

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

		$instance          = array();
		$instance['title'] = ! empty( $new_instance['title'] ) ? sanitize_text_field( $new_instance['title'] ) : '';

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
		</p><?php

		return 'form';

	}


}
