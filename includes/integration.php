<?php
namespace Reviewer;


/**
 * Integration class.
 *
 * Integrations are between two reviewer extension, for example User Reviews and Pros/Cons.
 * This class can be extend inherit some helper functionality.
 */
abstract class Integration {


	/**
	 * The key can be a plugin slug OR function name. The value the minimum required version number.
	 * Each item in the list must be active before the integration will load.
	 * When a array is passed it must match at least one of the passed items.
	 *
	 * @var array List of required plugins before this integration is loaded
	 */
	protected $required_plugins = array();


	/**
	 * Constructor.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		if ( $this->can_init() ) {
			$this->init();
		}
	}


	/**
	 * Initialise integration.
	 *
	 * @since 1.0.0
	 */
	abstract public function init();


	/**
	 * Check if integration can init.
	 *
	 * @since 1.0.5
	 *
	 * @return bool True when the integration is good to load, false otherwise.
	 */
	public function can_init() {

		if ( ! $this->required_plugins_active( $this->required_plugins ) ) {
			return false;
		}

		return true;

	}


	/**
	 * Are required plugins are active.
	 *
	 * Checks if required plugin(s) are active. Passing plugins can be done in various ways.
	 *  1) Plugin slug, the version number will be gotten from the plugin headers.
	 *  2) Function name of instance class. Version number should be available as property to the passed function.
	 *  3) String of a named constant. The constant value should be the version number of the plugin.
	 *
	 * @since 1.0.5
	 *
	 * @param array $plugins List of plugins to check for.
	 * @return bool True when ALL the required plugins are active, false otherwise.
	 */
	public function required_plugins_active( $plugins = array() ) {

		require_once( ABSPATH . '/wp-admin/includes/plugin.php' );
		foreach ( $plugins as $k => $v ) {

			if ( is_array( $v ) ) {

				$all_match = false;
				foreach ( $v as $key => $version ) {
					if ( $this->required_plugins_active( array( $key => $version ) ) ) {
						$all_match = true;
					}
				}
				return $all_match;

			} else {

				// Plugin slug
				if ( is_plugin_active( $k ) && $data = get_plugin_data( trailingslashit( WP_PLUGIN_DIR ) . $k ) ) {
					if ( version_compare( $data['Version'], $v, '>=' ) )  {
						return true;
					}

				// Plugin instance class w/ 'version' property
				} elseif ( function_exists( $k ) && property_exists( $k(), 'version' ) ) {
					if ( version_compare( $k()->version, $v, '>=' ) )  {
						return true;
					}

				// Version number constant
				} elseif ( defined( $k ) && version_compare( constant( $k ), $v, '>=' ) ) {
					return true;
				}
			}
		}

		return false;

	}


}
