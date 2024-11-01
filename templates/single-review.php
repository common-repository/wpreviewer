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
	 * Before content hook.
	 */
	do_action( 'reviewer\review\content\before' );


	while ( have_posts() ) :
		the_post();

		\Reviewer\get_template( 'content-single-review.php' );

	endwhile;


	/**
	 * Hook after the review content.
	 */
	do_action( 'reviewer\review\content\after' );


?></div><?php


	/**
	 * Sidebar hook.
	 */
	do_action( 'review_sidebar' );


get_footer( 'review' );
