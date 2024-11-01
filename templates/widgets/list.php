<?php
/**
 * Template file for showing the review.
 *
 * Override this template if desired by creating a file in wp-content/themes/your-theme/reviewer/widgets/list.php
 *
 * In some occasions the template files will be updated during updates. In such event
 * it is important to also update the template files in your theme to maintain maximum compatibility.
 *
 * @author 		Jeroen Sormani
 * @version     1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * @var WP_Query $reviews List of reviews.
 */
if ( $reviews->have_posts() ) :

	?><ul><?php

		while ( $reviews->have_posts() ) : $reviews->the_post();
			$review = rv_get_review( get_the_ID() );

			?><li class="clear"><?php
				// Thumbnail
				?><div class="review-thumbnail">
					<a href="<?php the_permalink(); ?>"><?php echo get_the_post_thumbnail( get_the_ID(), array( 50, 50 ) ); ?></a>
				</div>

				<div class="body">
					<div><a href="<?php the_permalink(); ?>"><?php echo $review->get_title(); ?></a></div><?php
					echo $review->get_rating_html();
				?></div><?php

			?></li><?php

		endwhile;

	?></ul><?php

	// Reset the global $the_post as this query will have stomped on it
	wp_reset_postdata();

endif;
