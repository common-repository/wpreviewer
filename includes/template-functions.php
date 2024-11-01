<?php
namespace Reviewer;

use Reviewer\Review;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly


/**
 * Locate template.
 *
 * Locate the called template.
 * Search Order:
 * 1. /themes/theme/reviewer/$template_name
 * 2. /themes/theme/$template_name
 * 3. /plugins/reviewer/templates/$template_name.
 *
 * @since 1.0.0
 *
 * @param   string  $template_name  Template to load.
 * @param   string  $template_path  Path to templates.
 * @param   string  $default_path   Default path to template files.
 * @return  string                  Path to the template file.
 */
function locate_template( $template_name, $template_path = '', $default_path = '' ) {

	// Set variable to search in reviewer folder of theme.
	if ( ! $template_path ) :
		$template_path = 'reviewer/';
	endif;

	// Set default plugin templates path.
	if ( ! $default_path ) :
		$default_path = plugin_dir_path( \Reviewer\Reviewer()->file ) . 'templates/'; // Path to the template folder
	endif;

	// Search template file in theme folder.
	$template = \locate_template( array(
		$template_path . $template_name,
		$template_name
	) );

	// Get plugins template file.
	if ( ! $template ) :
		$template = $default_path . $template_name;
	endif;

	return apply_filters( 'reviewer\locate_template', $template, $template_name, $template_path, $default_path );

}


/**
 * Get template.
 *
 * Search for the template and include the file.
 *
 * @since 1.0.0
 *
 * @param  string  $template_name  Template to load.
 * @param  array   $args           Args passed for the template file.
 * @param  string  $template_path  Path to templates.
 * @param  string  $default_path   Default path to template files.
 */
function get_template( $template_name, $args = array(), $template_path = '', $default_path = '' ) {

	if ( is_array( $args ) && isset( $args ) ) :
		extract( $args );
	endif;

	$template_file = locate_template( $template_name, $template_path, $default_path );

	if ( ! file_exists( $template_file ) ) :
		return _doing_it_wrong( __FUNCTION__, sprintf( '<code>%s</code> does not exist.', $template_file ), '1.0.0' );
	endif;

	include $template_file;

}


/**
 * Get template HTML.
 *
 * Same as get_template only this returns the HTML.
 *
 * @since 1.0.0
 *
 * @see wcpt_locate_template()
 *
 * @param   string  $template_name  Template to load.
 * @param   array   $args           Args passed for the template file.
 * @param   string  $template_path  Path to templates.
 * @param   string  $default_path   Default path to template files.
 * @return  string                  The HTML of the template.
 */
function get_template_html( $template_name, $args = array(), $template_path = '', $default_path = '' ) {

	ob_start();
		get_template( $template_name, $args, $template_path, $default_path );
	$html = ob_get_clean();

	return $html;

}


/**
 * Template loader.
 *
 * The template loader will check if WP is loading a template
 * for a specific Post Type and will try to load the template
 * from out 'templates' directory.
 *
 * @since 1.0.0
 *
 * @param   string  $template  Template file that is being loaded.
 * @return  string             Template file that should be loaded.
 */
function template_loader( $template ) {

	$find = array();
	$file = '';

	if ( is_singular( 'review' ) ) {
		$file = 'single-review.php';

		if ( file_exists( locate_template( $file ) ) ) {
			$template = locate_template( $file );
		}

	} elseif ( is_post_type_archive( 'review' ) || is_tax( 'review_category' ) || is_tax( 'review_tag' ) || get_the_ID() == get_option( 'reviewer_review_archive_page_id' ) ) {
		$file = 'archive-review.php';

		if ( file_exists( locate_template( $file ) ) ) {
			$template = locate_template( $file );
		}

	}

	return $template;

}
add_filter( 'template_include', '\Reviewer\template_loader' );

/**
 * Manually mark active menu.
 *
 * Manually mark the Review archive page as being the active menu.
 * This isn't working due to the change in the WP Query.
 *
 * @since 1.0.0
 *
 * @param          $classes
 * @param          $item
 * @return  array
 */
function mark_menu_item_as_active( $classes, $item ) {

	if ( isset( $item->object_id ) && $item->object_id == get_option( 'reviewer_review_archive_page_id' ) && is_post_type_archive( 'review' ) ) {
		$classes[] = 'current-menu-item';
	}

	return $classes;

}
add_filter( 'nav_menu_css_class', '\Reviewer\mark_menu_item_as_active', 10, 2 );

/**************************************************************
 * Review archive
 *************************************************************/

/**
 * Ensure the 'Reviews' page works.
 *
 * Make sure the 'Reviews' overview/archive page works appropriately.
 *
 * @since 1.0.0
 *
 * @param  \WP_Query  $q  The current query being executed.
 */
function pre_get_posts_review_archive( $q ) {

	if ( ! $q->is_main_query() || is_admin() ) {
		return;
	}

	// Fix for verbose page rules
	if ( isset( $q->queried_object->ID ) && $q->queried_object->ID == get_option( 'reviewer_review_archive_page_id' ) ) {
		$q->set( 'post_type', 'review' );
		$q->set( 'page', '' );
		$q->set( 'pagename', '' );

		// Fix conditional Functions
		$q->is_archive           = true;
		$q->is_post_type_archive = true;
		$q->is_singular          = false;
		$q->is_page              = false;
	}

	// Check for sites with the review archive on the front page - Thanks to WC
	if ( $q->is_page() && 'page' === get_option( 'show_on_front' ) && absint( $q->get( 'page_id' ) ) === absint( get_option( 'reviewer_review_archive_page_id' ) ) ) {

		$q->set( 'post_type', 'review' );
		$q->set( 'page_id', '' );
		$q->set( 'page', '' );
		$q->set( 'pagename', '' );

		if ( isset( $q->query['paged'] ) ) {
			$q->set( 'paged', $q->query['paged'] );
		}

		// Get the actual WP page to avoid errors and let us use is_front_page()
		// This is hacky but works. Awaiting https://core.trac.wordpress.org/ticket/21096
		global $wp_post_types;

		$review_page = get_post( get_option( 'reviewer_review_archive_page_id' ) );

		$wp_post_types['review']->ID         = $review_page->ID;
		$wp_post_types['review']->post_title = $review_page->post_title;
		$wp_post_types['review']->post_name  = $review_page->post_name;
		$wp_post_types['review']->post_type  = $review_page->post_type;
		$wp_post_types['review']->ancestors  = get_ancestors( $review_page->ID, $review_page->post_type );

		// Fix conditional Functions like is_front_page
		$q->is_singular          = false;
		$q->is_post_type_archive = true;
		$q->is_archive           = true;
		$q->is_page              = true;

		// Remove post type archive name from front page title tag
		add_filter( 'post_type_archive_title', '__return_empty_string', 5 );

	}


	remove_action( 'pre_get_posts', '\Reviewer\pre_get_posts_review_archive' );

}
add_action( 'pre_get_posts', '\Reviewer\pre_get_posts_review_archive' );


/**************************************************************
 * Template hooks
 *************************************************************/

/**
 * Add template hooks.
 *
 * Add the hooks for the set actions in the template files and attach
 * the related template parts to the templates.
 *
 * @since 1.0.0
 */
function template_hooks() {

	// Single review
	add_action( 'reviewer\review\head', '\Reviewer\view_review_featured_image', 10 );
	add_action( 'reviewer\review\head', '\Reviewer\view_header_open', 13 );
	add_action( 'reviewer\review\head', '\Reviewer\view_review_title', 15 );
	add_action( 'reviewer\review\head', '\Reviewer\view_review_meta', 16 );
	add_action( 'reviewer\review\head', '\Reviewer\view_header_close', 17 );
	add_action( 'reviewer\review\content', '\Reviewer\view_review_content', 20 );
	add_action( 'reviewer\review\head', '\Reviewer\view_review_rating', 25 );
	add_action( 'reviewer\review\head', '\Reviewer\view_review_attributes', 30 );
	add_action( 'reviewer\review\after', '\Reviewer\view_review_tags', 25 );
	add_action( 'reviewer\review\after', '\Reviewer\view_review_comments', 30 );

	// Sidebar
	add_action( 'review_sidebar', '\Reviewer\view_sidebar', 20 );

	// Archive
	add_action( 'reviewer\loop\row', '\Reviewer\view_loop_link_open', 18 );
	add_action( 'reviewer\loop\row', '\Reviewer\view_loop_featured_image', 20 );
	add_action( 'reviewer\loop\row', '\Reviewer\view_loop_link_close', 22 );

	add_action( 'reviewer\loop\row', '\Reviewer\view_header_open', 23 );
	add_action( 'reviewer\loop\row', '\Reviewer\view_loop_title', 25 );
	add_action( 'reviewer\loop\row', '\Reviewer\view_loop_meta', 26 );
	add_action( 'reviewer\loop\row', '\Reviewer\view_loop_rating', 28 );
	add_action( 'reviewer\loop\row', '\Reviewer\view_header_close', 27 );
	add_action( 'reviewer\loop\row', '\Reviewer\view_loop_summary', 30 );

	add_action( 'reviewer\loop\after', '\Reviewer\view_loop_pagination', 20 );
	add_action( 'reviewer\loop\no_reviews_found', '\Reviewer\view_no_reviews_found', 20 );

}
add_action( 'init', '\Reviewer\template_hooks' );


/**
 * Setup $review global.
 *
 * Setup the $review global variable so its accessible from
 * within the template files.
 *
 * @param  \WP_Post  $post  Post object.
 */
function setup_review_global( $post ) {

	if ( ! in_array( $post->post_type, array_keys( Reviewer()->post_types ) ) ) {
		return;
	}

	global $review;

	$review = rv_get_review( $post->ID );

}
add_action( 'the_post', '\Reviewer\setup_review_global' );


/**************************************************************
 * Template hook functions
 *************************************************************/

// Single
function view_review_title() {

	get_template( 'single-review/title.php' );

}


function view_review_meta() {

	get_template( 'single-review/meta.php' );

}


function view_review_content() {

	get_template( 'single-review/content.php' );

}


function view_review_rating( $review = null ) {

	if ( ! is_a( $review, '\Reviewer\Review' ) ) {
		global $review;
	}

	$rating      = $review->get_rating();
	$max_rating  = $review->get_max_rating();
	$color_width = 100 / $max_rating * $rating;

	if ( empty( $rating ) ) {
		return;
	}

	get_template( 'global/rating.php', compact( 'review', 'rating', 'max_rating', 'color_width' ) );

}


function view_review_featured_image() {

	get_template( 'single-review/featured-image.php' );

}


function view_review_thumbnail() {

	get_template( 'single-review/thumbnail.php' );

}


function view_review_comments() {

//	get_template( 'single-review/comments.php' );
	comments_template();

}


function view_review_tags() {

	get_template( 'single-review/tags.php' );

}


function view_review_attributes() {

	get_template( 'single-review/attributes.php' );

}


function view_review_link_open() {

	?><a href="<?php echo get_permalink(); ?>" class="review-link"><?php

}


function view_review_link_close() {

	?></a><?php

}


// Loop

function view_no_reviews_found() {

	get_template( 'loop/no-reviews-found.php' );

}


function view_loop_summary() {

	get_template( 'loop/summary.php' );

}


function view_loop_pagination() {

	get_template( 'loop/pagination.php' );

}


function view_loop_title() {

	get_template( 'loop/title.php' );

}


function view_loop_rating() {

	get_template( 'loop/rating.php' );

}


function view_header_open() {

	?><header class="entry-header"><?php

}


function view_header_close() {

	?></header><?php

}


function view_loop_link_open() {

	?><a href="<?php the_permalink(); ?>" rel="bookmark"><?php

}


function view_loop_link_close() {

	?></a><?php

}


function view_loop_featured_image() {

	get_template( 'loop/featured-image.php' );

}


function view_loop_meta() {

	get_template( 'loop/meta.php' );

}


// Sidebar
function view_sidebar() {

	get_template( 'global/sidebar.php' );

}
