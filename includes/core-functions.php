<?php
namespace Reviewer;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly


/**
 * Get reviews.
 *
 * Get Review objects based on the passed arguments.
 *
 * @since 1.0.5
 *
 * @param array $args List of args.
 * @return Review[] List of reviews.
 */
function get_reviews( $args = array() ) {

	$query_args = wp_parse_args( $args, array(
		'post_type' => 'review',
	) );
	$post_query = new \WP_Query( $query_args );
	$posts = $post_query->posts;

	$reviews = array();
	foreach ( $posts as $post ) {
		$reviews[] = rv_get_review( $post->ID );
	}

	return $reviews;

}

/**
 * Is current page Reviewer.
 *
 * Check if the current page being viewed is a Reviewer related page.
 *
 * @since 1.0.0
 */
function is_reviewer_page() {

	$reviewer_page = false;

	if ( 'review' == get_post_type() ) :
		$reviewer_page = true;
	elseif ( get_the_ID() == get_option( 'reviewer_review_archive_page_id' ) ) :
		$reviewer_page = true;
	endif;

	return apply_filters( 'reviewer\is_reviewer_page', $reviewer_page );

}


/**
 * Enqueue scripts.
 *
 * Enqueue script as javascript and style sheets.
 *
 * @since 1.0.0
 */
function enqueue_scripts() {

	$min = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';

	// Scripts
//	wp_register_script( 'reviewer', plugins_url( 'assets/front-end/js/reviewer' . $min . '.js', Reviewer()->file ), array( 'jquery' ), Reviewer()->version, true );
//	wp_localize_script( 'reviewer', 'rv', array(
//		'nonce' => wp_create_nonce( 'reviewer_nonce' ),
//	) );

	// Stylesheets
	wp_register_style( 'reviewer', plugins_url( 'assets/front-end/css/reviewer.min.css', \Reviewer\Reviewer()->file ), array(), \Reviewer\Reviewer()->version );

	// External plugins
	wp_register_script( 'blockit', plugins_url( 'assets/plugins/blockit/blockit' . $min . '.js', Reviewer()->file ), array( 'jquery' ), '0.1.0', true );


	// Enqueue when needed
	if ( is_reviewer_page() ) {
//		wp_enqueue_script( 'reviewer' );
		wp_enqueue_style( 'reviewer' );
	}

}
add_action( 'wp_enqueue_scripts', 'Reviewer\enqueue_scripts' );


/**
 * Register image sizes.
 *
 * Register the custom image sizes used by the plugin.
 *
 * @since 1.0.0
 */
function register_image_sizes() {

	// Archive size
	$archive_size = get_option( 'reviewer_archive_image_size', array( 'width' => 150, 'height' => 150, 'crop' => 'yes' ) );
	$archive_crop = $archive_size['crop'] == 'yes' ? array( 'center', 'center' ) : false;
	add_image_size( 'review-thumbnail', $archive_size['width'], $archive_size['height'], $archive_crop );

	// Review size
	$single_size = get_option( 'reviewer_review_image_size', array( 'width' => 9999, 'height' => 300, 'crop' => 'yes' ) );
	$single_crop = $single_size['crop'] == 'yes' ? array( 'center', 'center' ) : false;
	add_image_size( 'review-single', $single_size['width'], $single_size['height'], $single_crop );

}
add_action( 'after_setup_theme', 'Reviewer\register_image_sizes' );


/**
 * Register widgets.
 *
 * Register new widgets.
 *
 * @since 1.0.0
 */
function register_widgets() {

	register_widget( '\Reviewer\Widgets\Search' );
	register_widget( '\Reviewer\Widgets\Categories' );
	register_widget( '\Reviewer\Widgets\Review_List' );

}
add_action( 'widgets_init', '\Reviewer\register_widgets' );


/**
 * Get URL slug.
 *
 * Get the slug that is used for the URL.
 *
 * @since 1.0.0
 *
 * @return  mixed|void
 */
function get_slug() {
	return apply_filters( 'reviewer\url_slug', 'review' );
}
