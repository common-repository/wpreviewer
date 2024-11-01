<?php
/**
 * Template file for showing a single review in the archive loop.
 *
 * Override this template if desired by creating a file in wp-content/themes/your-theme/reviewer/content-single-review.php
 *
 * In some occasions the template files will be updated during updates. In such event
 * it is important to also update the template files in your theme to maintain maximum compatibility.
 *
 * @author 		Jeroen Sormani
 * @version     1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly


?><article <?php post_class( 'clearfix' ); ?>><?php


	/**
	 * reviewer\loop\row\before hook.
	 *
	 * @hooked
	 */
	do_action( 'reviewer\loop\row\before' );


	/**
	 * reviewer\loop\row hook.
	 *
	 * @hooked \Reviewer\view_review_title - 15
	 * @hooked \Reviewer\view_review_thumbnail - 20
	 * @hooked \Reviewer\view_review_content - 25
	 */
	do_action( 'reviewer\loop\row' );


	/**
	 * reviewer\loop\row\after hook.
	 *
	 * @hooked
	 */
	do_action( 'reviewer\loop\row\after' );


?></article>
