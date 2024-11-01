<?php
namespace Reviewer\Taxonomies;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Class Review_Tag.
 *
 * Initialize and set up a custom taxonomy.
 *
 * @since 1.0.0
 */
class Review_Tag {


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
			'name'              => __( 'Tags', 'reviewer' ),
			'singular_name'     => __( 'Tag', 'reviewer' ),
			'search_items'      => __( 'Search Tags', 'reviewer' ),
			'all_items'         => __( 'All Tags', 'reviewer' ),
			'parent_item'       => __( 'Parent Tag', 'reviewer' ),
			'parent_item_colon' => __( 'Parent Tag:', 'reviewer' ),
			'edit_item'         => __( 'Edit Tag', 'reviewer' ),
			'update_item'       => __( 'Update Tag', 'reviewer' ),
			'add_new_item'      => __( 'Add New Tag', 'reviewer' ),
			'new_item_name'     => __( 'New Tag Name', 'reviewer' ),
			'menu_name'         => __( 'Tag', 'reviewer' ),
		);

		$args = array(
			'hierarchical'      => false,
			'labels'            => $labels,
			'show_ui'           => true,
			'show_in_nav_menus' => true,
			'show_in_menu'      => false,
			'show_admin_column' => true,
			'query_var'         => true,
			'rewrite'           => array( 'slug' => 'review-tag' ),
		);

		register_taxonomy( 'review_tag', 'review', $args );

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

		if ( isset( $_GET['taxonomy'] ) && $_GET['taxonomy'] == 'review_tag' ) :
			$parent_file = 'reviewer';
		endif;

	}


}
