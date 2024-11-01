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
class Review_List extends Abstract_Widget {


	/**
	 * Constructor.
	 *
	 * Initialize this class including hooks.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {

		$name           = __( 'Review List', 'reviewer' );
		$widget_options = array(
			'description' => __( 'List reviews', 'reviewer' ),
		);

		parent::__construct( $id = 'review_list', $name, $widget_options );

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

		if ( ! empty( $instance['title'] ) ) {
			echo $args['before_title'] . apply_filters( 'widget_title', $instance['title'] ) . $args['after_title'];
		}

		$number  = ! empty( $instance['number'] ) ? absint( $instance['number'] ) : 5;
		$orderby = ! empty( $instance['order_by'] ) ? sanitize_text_field( $instance['order_by'] ) : '';

		$query_args = array(
			'posts_per_page' => $number,
			'no_found_rows'  => true,
			'post_status'    => 'publish',
			'post_type'      => 'review',
		);


		// Order args
		$query_args['order'] = 'DESC';
		switch ( $orderby ) {
			case 'latest' :
				$query_args['orderby'] = 'post_date';
				break;

			case 'best-rated' :
				$query_args['orderby']  = 'meta_value_num';
				$query_args['meta_key'] = '_rating';
				break;
		}

		$reviews = new \WP_Query( apply_filters( 'reviewer\widget\list\args', $query_args ) );

		echo $args['before_widget'];
		\Reviewer\get_template( 'widgets/list.php', array( 'reviews' => $reviews ) );
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

		$instance             = array();
		$instance['title']    = ! empty( $new_instance['title'] ) ? sanitize_text_field( $new_instance['title'] ) : '';
		$instance['count']    = ! empty( $new_instance['count'] ) ? absint( $new_instance['count'] ) : 5;
		$instance['order_by'] = ! empty( $new_instance['order_by'] ) ? sanitize_text_field( $new_instance['order_by'] ) : '';

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

		$title   = ! empty( $instance['title'] ) ? esc_attr( $instance['title'] ) : '';
		$orderby = ! empty( $instance['order_by'] ) ? sanitize_text_field( $instance['order_by'] ) : '';
		$count   = ! empty( $instance['count'] ) ? absint( $instance['count'] ) : 5;
		?><p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo $title; ?>">
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'count' ); ?>"><?php _e( 'Number of reviews to show', 'reviewer' ); ?></label>
			<input class="number tiny-text" id="<?php echo $this->get_field_id( 'count' ); ?>" name="<?php echo $this->get_field_name( 'count' ); ?>" type="number" value="<?php echo $count; ?>">
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'order_by' ); ?>"><?php _e( 'Order by', 'reviewer' ); ?></label>
			<select class="select" name="<?php echo $this->get_field_name( 'order_by' ); ?>">
				<option value="latest" <?php selected( $orderby, 'latest' ); ?>><?php _e( 'Latest', 'reviewer' ); ?></option>
				<option value="best-rated" <?php selected( $orderby, 'best-rated' ); ?>><?php _e( 'Best rated', 'reviewer' ); ?></option>
			</select>
		</p><?php

		return 'form';

	}


}
