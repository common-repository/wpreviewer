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
abstract class Abstract_Widget extends \WP_Widget {


	/**
	 * @var string $name Name of the widget
	 */
	public $name;


	/**
	 * @var string $description Description of the widget
	 */
	public $description;


	/**
	 * Constructor.
	 *
	 * Initialize this class including hooks.
	 *
	 * @since 1.0.0
	 */
	public function __construct( $id, $name, $options ) {

		$this->id_base     = $id;
		$this->name        = $name;
		$this->description = isset( $options['description'] ) ? $options['description'] : '';

		$widget_options = wp_parse_args( $options, array(
			'description' => $this->description,
		) );

		parent::__construct( $this->id_base, $this->name, $widget_options );

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
