<?php
/**
 * Template file for showing the review contents.
 *
 * Override this template if desired by creating a file in wp-content/themes/your-theme/reviewer/{file-path}.php
 *
 * In some occasions the template files will be updated during updates. In such event
 * it is important to also update the template files in your theme to maintain maximum compatibility.
 *
 * @author 		Jeroen Sormani
 * @version     1.0.0
 * @since       NEWVERSION
 *
 * @var \Reviewer\Review $review
 * @var int|float $rating Review rating.
 * @var int $max_rating Maximum number of rating stars
 * @var int|float $color_width Width the colored part should be
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

?><div class="review-rating-wrap" title="<?php echo sprintf( __( '%s out of %s', 'reviewer' ), $rating, $max_rating ); ?>">

	<!-- Colored stars -->
	<div class="review-rating review-rating-color" style="width: <?php echo $color_width . '%'; ?>;"><?php

		for ( $i = 1; $i <= $max_rating; $i++ ) :
			?><span class="star star-colored"><?php
				echo file_get_contents( plugins_url( 'assets/img/star.svg', \Reviewer\Reviewer()->file ) );
			?></span><?php
		endfor;

	?></div>

	<!-- Gray stars -->
	<div class="review-rating review-rating-uncolored"><?php

		for ( $i = 1; $i <= $max_rating; $i++ ) :
			?><span class="star"><?php
				echo file_get_contents( plugins_url( 'assets/img/star.svg', \Reviewer\Reviewer()->file ) );
			?></span><?php
		endfor;

	?></div>

</div>
