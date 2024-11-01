<?php
namespace Reviewer\Admin;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Admin {


	/**
	 * Instance of Reviewer Filters Admin.
	 *
	 * @since 1.0.0
	 * @access private
	 * @var object $instance The singleton instance of the class.
	 */
	private static $instance;


	/**
	 * @var Settings $settings Settings instance.
	 */
	public $settings;


	/**
	 * Construct.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {}


	/**
	 * Instance.
	 *
	 * An global instance of the class. Used to retrieve the instance
	 * to use on other files/plugins/themes.
	 *
	 * @since 1.0.0
	 * @return  object  Instance of the class.
	 */
	public static function instance() {

		if ( is_null( self::$instance ) ) :
			self::$instance = new self();
		endif;

		return self::$instance;

	}


	/**
	 * Init.
	 *
	 * Initialize plugin parts.
	 *
	 * @since 1.0.0
	 */
	public function init() {

		$this->includes();

		$this->settings = new Settings();
		$this->settings->init();

		do_action( 'reviewer\admin\init' );

	}


	/**
	 * Include files.
	 *
	 * Including files manually till there's a good solution for autoloading.
	 *
	 * @since 1.0.0
	 */
	private function includes() {

		require_once plugin_dir_path( __FILE__ ) . 'admin-functions.php';
		require_once plugin_dir_path( __FILE__ ) . 'plugin-page-functions.php';
		require_once plugin_dir_path( __FILE__ ) . 'meta-boxes/abstract-meta-box.php';
		require_once plugin_dir_path( __FILE__ ) . 'meta-boxes/review-details.php';
		require_once plugin_dir_path( __FILE__ ) . 'settings.php';

	}


}
