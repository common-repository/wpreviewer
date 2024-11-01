<?php
/**
 * Template file for showing the review.
 *
 * Override this template if desired by creating a file in wp-content/themes/your-theme/reviewer/single-review.php
 *
 * In some occasions the template files will be updated during updates. In such event
 * it is important to also update the template files in your theme to maintain maximum compatibility.
 *
 * @author 		Jeroen Sormani
 * @version     1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly


get_header( 'review' );

?><div id="primary" class="site-content"><?php


	/**
	 * Hook for description/filters etc.
	 */
	do_action( 'reviewer\loop\before' );


	?><div class="archive-posts"><?php


		if ( have_posts() ) :

			while ( have_posts() ) :
				the_post();

				\Reviewer\get_template( 'content-review.php' );

			endwhile;

		else :

			/**
			 * @hooked \Reviewer\view_no_reviews_found - 20
			 */
			do_action( 'reviewer\loop\no_reviews_found' );


		endif;

	?></div><?php

	/**
	 * Hook for description/filters etc.
	 */
	do_action( 'reviewer\loop\after' );


?></div><?php


/**
 * Sidebar hook.
 */
do_action( 'review_sidebar' );


get_footer( 'review' );
