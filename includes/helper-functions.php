<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly


/**
 * Get a review.
 *
 * Get a review based on the review ID. Returns Review object when available.
 * Can be extended to return a User review object for example.
 *
 * @since 1.0.0
 *
 * @param int $review_id Review ID to get.
 * @return bool|\Reviewer\Review Review object when available, false otherwise.
 */
function rv_get_review( $review_id ) {

	$review = false;

	if ( is_a( $review_id, '\Reviewer\Review' ) ) {
		return $review_id;
	}

	if ( get_post_type( $review_id ) == 'review' ) {
		return new \Reviewer\Review( $review_id );
	}

	return apply_filters( 'reviewer\get_review', $review, $review_id );

}
