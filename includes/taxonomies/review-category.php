<?php
namespace Reviewer\Taxonomies;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Class Review_Category.
 *
 * Initialize and set up a custom taxonomy.
 *
 * @since 1.0.0
 */
class Review_Category {


	/**
	 * Constructor.
	 *
	 * Initialize this class including hooks.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {

		// Register Taxonomy
		add_action( 'init', array( $this, 'register' ) );

		// Menu highlight control
		add_action( 'admin_head', array( $this, 'menu_highlight' ) );

	}


	/**
	 * Register taxonomy.
	 *
	 * Register the custom taxonomy.
	 *
	 * @since 1.0.0
	 */
	public function register() {

		$labels = array(
			'name'              => __( 'Categories', 'reviewer' ),
			'singular_name'     => __( 'Category', 'reviewer' ),
			'search_items'      => __( 'Search Categories', 'reviewer' ),
			'all_items'         => __( 'All Categories', 'reviewer' ),
			'parent_item'       => __( 'Parent Category', 'reviewer' ),
			'parent_item_colon' => __( 'Parent Category:', 'reviewer' ),
			'edit_item'         => __( 'Edit Category', 'reviewer' ),
			'update_item'       => __( 'Update Category', 'reviewer' ),
			'add_new_item'      => __( 'Add New Category', 'reviewer' ),
			'new_item_name'     => __( 'New Category Name', 'reviewer' ),
			'menu_name'         => __( 'Category', 'reviewer' ),
		);

		$args = array(
			'hierarchical'      => true,
			'labels'            => $labels,
			'show_ui'           => true,
			'show_in_nav_menus' => true,
			'show_in_menu'      => false,
			'show_admin_column' => true,
			'query_var'         => true,
			'rewrite'           => array( 'slug' => 'review-category' ),
		);

		register_taxonomy( 'review_category', 'review', $args );

	}


	/**
	 * Keep menu open.
	 *
	 * Highlights the correct top level admin menu item for post type add screens.
	 *
	 * @since 1.0.5
	 */
	public function menu_highlight() {

		global $parent_file;

		if ( isset( $_GET['taxonomy'] ) && $_GET['taxonomy'] == 'review_category' ) :
			$parent_file = 'reviewer';
		endif;

	}


}
