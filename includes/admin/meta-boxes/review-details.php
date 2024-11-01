<?php
namespace Reviewer\Admin\Meta_Boxes;

use Reviewer\Review;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Class Review_Details.
 *
 * Review details meta box class.
 *
 * @since 1.0.0
 */
class Review_Details extends Abstract_Meta_Box {


	/**
	 * Constructor.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {

		$this->id        = 'review_details';
		$this->title     = __( 'Review details', 'reviewer' );
		$this->post_type = 'review';
		$this->context   = 'normal';
		$this->priority  = 'high';

		// Required
		parent::__construct();

	}


	/**
	 * Get the tabs.
	 *
	 * Get the tabs that are displayed at the review details meta box.
	 *
	 * @since 1.0.0
	 *
	 * @return  array  List of tabs to display.
	 */
	public function get_tabs() {

		$tabs = array(
			'general'    => array(
				'name'    => __( 'General', 'reviewer' ),
				'classes' => array(),
			),
			'attributes' => array(
				'name'    => __( 'Attributes', 'reviewer' ),
				'classes' => array(),
			),
			'categories' => array(
				'name'    => __( 'Categories', 'reviewer' ),
				'classes' => array(),
			),
			'tags'       => array(
				'name'    => __( 'Tags', 'reviewer' ),
				'classes' => array(),
			),
		);

		return apply_filters( 'reviewer\review_details\get_tabs', $tabs );

	}


	/**
	 * Output settings.
	 *
	 * Output the meta box setting fields.
	 *
	 * @since 1.0.0
	 *
	 * @param  \WP_Post  Post being shown.
	 */
	public function output( $post ) {

		$review = new Review( $post->ID );
		$tabs   = $this->get_tabs();
		require_once 'views/review-details.php';

	}


	/**
	 * Save settings.
	 *
	 * Save the settings that are set in this meta box. Nonce verification is
	 * already done at this point.
	 *
	 * @since 1.0.0
	 *
	 * @param  int  $post_id  ID of the review post.
	 */
	public function save( $post_id ) {

		if ( ! current_user_can( 'edit_posts' ) ) :
			return;
		endif;

		$sanitized_values = array();
		$data_to_save     = array(
			'_rating'     => '',
			'_attributes' => '',
		);

		foreach ( $data_to_save as $k => $value ) {

			switch ( $k ) {

				case '_rating' :
					$sanitized_values['_rating'] = ! empty( $_POST['_rating'] ) ? floatval( $_POST['_rating'] ) : '';
					break;

				case '_attributes' :
					$sanitized_attributes = array();
					$attributes           = isset( $_POST['_attributes'] ) ? $_POST['_attributes'] : array();
					foreach ( $attributes as $attribute ) {
						$sanitized_attributes[] = array(
							'name'  => wp_kses_post( $attribute['name'] ),
							'value' => wp_kses_post( $attribute['value'] ),
						);
					}
					$sanitized_values[ $k ] = $sanitized_attributes;
					break;

			}

		}

		// Actually save the data
		foreach ( $sanitized_values as $k => $v ) {
			update_post_meta( $post_id, $k, $v );
		}

	}


}
