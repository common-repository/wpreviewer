<?php

// Global namespace helper function for easier access
namespace {
	function Reviewer() {
		return \Reviewer\Reviewer();
	}
}


namespace Reviewer {

	use Reviewer\Admin\Admin;

	if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

	/**
	 * Class Reviewer.
	 *
	 * Main Reviewer class initializes the plugin.
	 * If you'd like to call the Reviewer plugin, use Reviewer().
	 *
	 * @since  1.0.0
	 * @author Jeroen Sormani
	 */
	class Reviewer {


		/**
		 * @since 1.0.0
		 * @var string $version Plugin version number.
		 */
		public $version = '1.0.5';


		/**
		 * @since 1.0.0
		 * @var string $file Plugin file path.
		 */
		public $file = REVIEWER_FILE;


		/**
		 * @var array Post types registered by this plugin.
		 */
		public $post_types = null;


		/**
		 * @var array Taxonomies registered by this plugin.
		 */
		public $taxonomies = null;


		/**
		 * @var array Shortcodes registered by this plugin.
		 */
		public $shortcodes = null;


		/**
		 * Instance of Reviewer.
		 *
		 * @since 1.0.0
		 * @access private
		 * @var object $instance The instance of Reviewer.
		 */
		private static $instance;


		/**
		 * @since 1.0.0
		 * @var Admin $admin Admin class instance.
		 */
		public $admin = null;


		/**
		 * Construct.
		 *
		 * Initialize the class and plugin.
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

			$this->post_types = array(
				'review' => new \Reviewer\Post_Types\Review(),
			);
			$this->taxonomies = array(
				'review_category' => new \Reviewer\Taxonomies\Review_Category(),
				'review_tag'      => new \Reviewer\Taxonomies\Review_Tag(),
			);

			// Load textdomain
			$this->load_textdomain();

			do_action( 'reviewer\init' );

		}


		/**
		 * Include files.
		 *
		 * Including files manually till there's a good solution for autoloading.
		 *
		 * @since 1.0.0
		 */
		private function includes() {

			// Classes
			require_once 'includes/integration.php';
			require_once 'includes/post-types/review.php';
			require_once 'includes/review.php';
			require_once 'includes/shortcodes/abstract-shortcode.php';
			require_once 'includes/taxonomies/review-category.php';
			require_once 'includes/taxonomies/review-tag.php';
			require_once 'includes/widgets/abstract-widget.php';
			require_once 'includes/widgets/search.php';
			require_once 'includes/widgets/categories.php';
			require_once 'includes/widgets/list.php';

			// Functions
			require_once 'includes/helper-functions.php';
			require_once 'includes/core-functions.php';
			require_once 'includes/template-functions.php';

			if ( is_admin() ) {
				require_once 'includes/admin/admin.php';
				$this->admin = new Admin();
				$this->admin->init();
			}

		}


		/**
		 * Textdomain.
		 *
		 * Load the textdomain based on WP language.
		 *
		 * @since 1.0.0
		 */
		public function load_textdomain() {

			$locale = apply_filters( 'plugin_locale', get_locale(), 'reviewer' );

			// Load textdomain
			load_textdomain( 'reviewer', WP_LANG_DIR . '/reviewer/reviewer-' . $locale . '.mo' );
			load_plugin_textdomain( 'reviewer', false, basename( dirname( __FILE__ ) ) . '/languages' );

		}


	}


	/**
	 * The main function responsible for returning the Reviewer object.
	 *
	 * Use this function like you would a global variable, except without needing to declare the global.
	 *
	 * Example: <?php Reviewer()->method_name(); ?>
	 *
	 * @since 1.0.0
	 *
	 * @return  Reviewer  Reviewer class object.
	 */
	if ( ! function_exists( '\Reviewer\Reviewer' ) ) :

		function Reviewer() {

			return Reviewer::instance();

		}

	endif;

	// Initialize plugin parts
	add_action( 'plugins_loaded', array( Reviewer(), 'init' ), 5 );
//	Reviewer()->init();


	// Activation hooks
	register_activation_hook( REVIEWER_FILE, function () {

		require_once 'includes/installer.php';

		$installer = new \Reviewer\Installer();
		$installer->activate();

	} );

}
