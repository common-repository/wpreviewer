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
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

$date = sprintf( '<time class="entry-date" datetime="%1$s">%2$s</time>',
	esc_attr( get_the_date( 'c' ) ),
	esc_html( get_the_date() )
);

$author = sprintf( '<span class="author vcard"><a class="url fn n" href="%1$s" title="%2$s" rel="author">%3$s</a></span>',
	esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ),
	esc_attr( sprintf( __( 'Reviewed by %s', 'reviewer' ), get_the_author() ) ),
	get_the_author()
);

?><div class="review-meta review-intro"><?php

	?><span class="review-meta-part"><?php echo sprintf( __( 'Posted on %s', 'reviewer' ), $date ); ?>&nbsp;&nbsp;&nbsp;&nbsp;</span><?php
	?><span class="review-meta-part"><?php echo sprintf( __( 'Reviewed by %s', 'reviewer' ), $author ); ?>&nbsp;&nbsp;&nbsp;&nbsp;</span><?php

	if ( comments_open() ) :
		?><span class="review-meta-part"><?php
			comments_popup_link( '<span class="leave-reply">' . __( 'Leave a comment', 'reviewer' ) . '</span>', __( '1 Comment', 'reviewer' ), __( '% Comments', 'reviewer' ) );
		?></span><?php
	endif;

?></div>
