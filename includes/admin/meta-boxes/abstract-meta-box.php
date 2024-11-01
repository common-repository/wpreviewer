<?php
namespace Reviewer\Admin\Meta_Boxes;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly


/**
 * Class Abstract_Meta_Box.
 *
 * Abstract meta box class.
 *
 * @since 1.0.0
 */
abstract class Abstract_Meta_Box {


	/**
	 * Meta box ID.
	 *
	 * @since 1.0.0
	 * @var string $id Unique Identifier of the meta box.
	 */
	public $id = '';


	/**
	 * Title.
	 *
	 * @since 1.0.0
	 * @var string $title Title of the meta box.
	 */
	public $title = '';


	/**
	 * Post type.
	 *
	 * @since 1.0.0
	 * @var string $post_type Post type to show the meta box for.
	 */
	public $post_type = null;


	/**
	 * Context.
	 *
	 * @since 1.0.0
	 * @var string $context Meta box context.
	 */
	public $context = null;


	/**
	 * Priority.
	 *
	 * @since 1.0.0
	 * @var string $priority 'high', 'core', 'default' or 'low'.
	 */
	public $priority = 'default';


	/**
	 * Callback args.
	 *
	 * @since 1.0.0
	 * @var array $callback_args List of callback args.
	 */
	public $callback_args = array();


	/**
	 * Settings.
	 *
	 * @since 1.0.0
	 * @var array $settings List of meta box settings.
	 */
	public $settings = null;


	/**
	 * Constructor.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {

		// Save data - @hook reviewer\save_meta_ . $post_type
		add_action( 'reviewer\save_meta_' . $this->post_type, array( $this, 'save_data' ), 10 );

	}


	/**
	 * Output data.
	 *
	 * Output the settings Including nonce field.
	 * This method is a wrapper for $this->output. Should NOT be overridden.
	 *
	 * @since 1.0.0
	 *
	 * @param  \WP_Post  $post  Post object.
	 */
	final function output_data( $post ) {

		// Add nonce field
		wp_nonce_field( 'reviewer_save_data_' . $this->id, 'reviewer_nonce_' . $this->id );

		// Meta box contents
		$this->output( $post );

	}


	/**
	 * Output settings.
	 *
	 * Output the settings in the meta box. Required to override.
	 *
	 * @since 1.0.0
	 *
	 * @param  \WP_Post  $post  Post object.
	 */
	abstract public function output( $post );


	/**
	 * Save data.
	 *
	 * Make sure nonces/permissions are verified before saving.
	 * This method is a wrapper for $this->save_data(). Should NOT be overridden.
	 *
	 * @since 1.0.0
	 *
	 * @param   int   $post_id  ID of the post being saved.
	 * @return  bool            Return false when nonce could not be verified, true otherwise.
	 */
	final function save_data( $post_id ) {

		// Automatically check nonce
		if ( ! isset( $_POST['reviewer_nonce_' . $this->id ] ) || ! wp_verify_nonce( $_POST['reviewer_nonce_' . $this->id ], 'reviewer_save_data_' . $this->id ) ) :
			return false;
		endif;

		$this->save( $post_id );

		return true;

	}


	/**
	 * Save data.
	 *
	 * Called by $this->save_data() which pre-verifies nonce and permissions.
	 * Required to override.
	 *
	 * @since 1.0.0
	 *
	 * @param  int  $post_id  ID of the post being saved.
	 */
	abstract public function save( $post_id );


}
