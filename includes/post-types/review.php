<?php
namespace Reviewer\Post_Types;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Class Post_Type.
 *
 * Initialize and set up post types and everything related.
 *
 * @since 1.0.0
 */
class Review {


	/**
	 * Constructor.
	 *
	 * Initialize this class including hooks.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {

		// Register post type
		add_action( 'init', array( $this, 'register' ) );

		// Admin specific
		if ( is_admin() ) {
			add_action( 'save_post_review', array( $this, 'save_review' ), 10, 1 );
		}

		// Add custom columns
		add_action( 'manage_edit-review_columns', array( $this, 'custom_columns' ), 11 );
		// Add contents to the new columns
		add_action( 'manage_review_posts_custom_column', array( $this, 'custom_column_contents' ), 10, 2 );

	}


	/**
	 * Post type.
	 *
	 * Register the custom post type.
	 *
	 * @since 1.0.0
	 */
	public function register() {

		$labels = apply_filters( 'reviewer\post_type_labels', array(
			'name'               => __( 'Review ', 'reviewer' ),
			'singular_name'      => __( 'Review', 'reviewer' ),
			'menu_name'          => __( 'Reviews', 'reviewer' ),
			'name_admin_bar'     => __( 'Review', 'reviewer' ),
			'add_new'            => __( 'Add New', 'reviewer' ),
			'add_new_item'       => __( 'Add New Review', 'reviewer' ),
			'new_item'           => __( 'New Review', 'reviewer' ),
			'edit_item'          => __( 'Edit Review', 'reviewer' ),
			'view_item'          => __( 'View Review', 'reviewer' ),
			'all_items'          => __( 'Reviews', 'reviewer' ),
			'search_items'       => __( 'Search Reviews', 'reviewer' ),
			'parent_item_colon'  => __( 'Parent Reviews:', 'reviewer' ),
			'not_found'          => __( 'No Reviews found.', 'reviewer' ),
			'not_found_in_trash' => __( 'No Reviews found in Trash.', 'reviewer' )
		) );

		$review_args = array(
			'labels'               => $labels,
			'public'               => true,
			'publicly_queryable'   => true,
			'show_ui'              => true,
			'show_in_menu'         => 'reviewer',
			'query_var'            => true,
			'rewrite'              => array( 'slug' => \Reviewer\get_slug() ),
			'capability_type'      => 'post',
			'has_archive'          => true,
			'hierarchical'         => false,
			'menu_position'        => null,
			'menu_icon'            => 'dashicons-star-filled',
			'taxonomies'           => array(
				'review_category',
				'review_tag',
			),
			'supports'             => array( 'title', 'editor', 'thumbnail', 'comments' ),
			'register_meta_box_cb' => array( $this, 'register_meta_boxes' ),
		);

		register_post_type( 'review', $review_args );

	}


	/**
	 * Register meta boxes.
	 *
	 * Register the meta box(es) for this post type.
	 *
	 * @since 1.0.0
	 *
	 * @param  \WP_Post  $post  Post object.
	 */
	public function register_meta_boxes( $post ) {

		$meta_box = new \Reviewer\Admin\Meta_Boxes\Review_Details();
		add_meta_box( $meta_box->id, $meta_box->title, array( $meta_box, 'output_data' ), $meta_box->post_type, $meta_box->context, $meta_box->priority, $meta_box->callback_args );

		do_action( 'reviewer\post_type\review\register_meta_boxes' );

	}


	/**
	 * Save Review.
	 *
	 * Action to execute when a review is being saved.
	 *
	 * @since 1.0.0
	 *
	 * @param   int    $post_id  ID of the post being saved.
	 * @return  mixed
	 */
	public function save_review( $post_id ) {

		// Don't save on auto save
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) :
			return $post_id;
		endif;

		// Don't save on revisions
		if ( wp_is_post_revision( $post_id ) || wp_is_post_autosave( $post_id ) ) :
			return $post_id;
		endif;

		// Only save meta on used post types
		if ( ! in_array( get_post_type( $post_id ), array( 'review' ) ) ) :
			return $post_id;
		endif;

		// Make sure the user has edit permissions for the post type
		if ( ! current_user_can( 'edit_posts', $post_id ) ) :
			return $post_id;
		endif;

		// Initialize elements to save
		new \Reviewer\Admin\Meta_Boxes\Review_Details();

		// Prevent infinite loop
		remove_action( current_filter(), array( $this, __FUNCTION__ ) );

		// Update post meta
		do_action( 'reviewer\save_meta_review', $post_id );

		// Re-add saving filter
		add_action( current_filter(), array( $this, __FUNCTION__ ) );

	}


	/**
	 * Post columns.
	 *
	 * Set custom columns for the custom post type.
	 *
	 * @since 1.0.0
	 *
	 * @param   array  $columns  List of existing post columns.
	 * @return  array            List of edited columns.
	 */
	public function custom_columns( $columns ) {

		$new_columns['cb']        = $columns['cb'];
		$new_columns['thumbnail'] = '<span class="dashicons dashicons-format-image"></span>';
		$new_columns['title']     = $columns['title'];
		$new_columns['rating']    = __( 'Rating', 'reviewer' );
		$new_columns['comments']  = $columns['comments'];
		$new_columns['date']      = $columns['date'];

		return $new_columns;

	}


	/**
	 * Columns contents.
	 *
	 * Output the custom columns contents.
	 *
	 * @since 1.0.0
	 *
	 * @param  string  $column   Slug of the current columns to output data for.
	 * @param  int     $post_id  ID of the current post.
	 */
	public function custom_column_contents( $column, $post_id ) {

		$review = rv_get_review( $post_id );
		switch ( $column ) :

			case 'thumbnail' :
				$review->get_the_thumbnail();
				break;

			case 'rating' :

				?><span class="review-rating"><?php
					$rating = $review->get_rating();
					if ( ! empty( $rating ) ) {
						echo $review->get_rating() . '/' . $review->get_max_rating();
					} else {
						echo __( 'Not set', 'reviewer' );
					}
				?></span><?php

				break;

		endswitch;

	}


}
