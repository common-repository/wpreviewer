<?php
namespace Reviewer;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly


/**
 * Installer.
 *
 * Handles all the installing features and requirements.
 *
 * @since 1.0.0
 */
class Installer {


	/**
	 * Constructor.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {

	}


	/**
	 * Executed on plugin activation.
	 *
	 * @since 1.0.0
	 */
	public function activate() {

		Reviewer()->init();

		// Flush rewrite rules
		$this->flush_rewrite_rules();

		// Set default settings
		$settings = new \Reviewer\Admin\Settings;

		foreach ( $settings->get_settings() as $section => $section_settings ) {
			foreach ( $section_settings as $k => $values ) {
				if ( ! isset( $values['default'] ) ) continue;

				add_option( 'reviewer_' . $k, $values['default'] );
			}
		}

	}


	/**
	 * Flush the rewrite rules.
	 *
	 * Flush them after registering the post types.
	 *
	 * @since 1.0.0
	 */
	public function flush_rewrite_rules() {

		$review = new \Reviewer\Post_Types\Review();
		$review->register();

		$review_tag = new \Reviewer\Taxonomies\Review_Tag();
		$review_tag->register();

		$review_category = new \Reviewer\Taxonomies\Review_Category();
		$review_category->register();

		flush_rewrite_rules();

	}


	/**
	 * Install the pages.
	 *
	 * Install the pages used by the plugin.
	 *
	 * @since 1.0.0
	 */
	public function install_pages() {

		if ( get_option( 'reviewer_review_archive_page_id' ) && get_post_status( get_option( 'reviewer_review_archive_page_id' ) ) == 'publish' ) {
			return;
		}

		$page_id = wp_insert_post( array(
			'post_type'    => 'page',
			'post_title'   => __( 'Reviews', 'reviewer' ),
			'post_status'  => 'publish',
			'post_content' => '',
		) );

		update_option( 'reviewer_review_archive_page_id', $page_id );

	}


}
