<?php
namespace Reviewer;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

abstract class Abstract_Shortcode {

	/**
	 * @var string Shortcode slug.
	 */
	public $shortcode = null;

	/**
	 * @var array Default attributes for the shortcode.
	 */
	private $defaults = array();


	/**
	 * Constructor.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		add_shortcode( $this->shortcode, array( $this, 'callback' ) );
	}


	/**
	 * Callback for the shortcode.
	 *
	 * Perform the actions related to this shortcode.
	 *
	 * @since 1.0.0
	 *
	 * @param   array        $atts     List of passed attributes.
	 * @param   null|string  $content  Contents of the shortcode if any.
	 * @return  string                 Output of the shortcode.
	 */
	abstract function callback( $atts, $content = null );

}
