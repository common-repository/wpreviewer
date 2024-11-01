<?php
/**
 * Template file for showing the review contents.
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


/**
 * reviewer_before_single_review hook
 */
do_action( 'reviewer\review\before' );


	?><div class="review-head"><?php


		/**
		 * Review head hook.
		 *
		 * Contains content like featured image, title, meta, rating.
		 *
		 * @since 1.0.0
		 *
		 * @hooked \Reviewer\view_review_featured_image - 10
		 * @hooked \Reviewer\view_header_open - 13
		 * @hooked \Reviewer\view_review_title - 15
		 * @hooked \Reviewer\view_review_meta - 16
		 * @hooked \Reviewer\view_header_close - 17
		 * @hooked \Reviewer\view_review_rating - 25
		 * @hooked \Reviewer\view_review_attributes - 30
		 */
		do_action( 'reviewer\review\head' );


	?></div>

	<div class="review-content entry-content"><?php


		/**
		 * Review content hook.
		 *
		 * @since 1.0.0
		 *
		 * @hooked \Reviewer\view_review_content - 20
		 */
		do_action( 'reviewer\review\content' );


	?></div>

	<meta itemprop="url" content="<?php the_permalink(); ?>"/><?php


/**
 * @hooked \Reviewer\view_review_tags - 25
 * @hooked \Reviewer\view_review_comments - 30
 */
do_action( 'reviewer\review\after' );
