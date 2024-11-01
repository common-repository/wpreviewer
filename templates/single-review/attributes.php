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

global $review;

if ( $attributes = $review->get_attributes() ) :

	?><div class="review-attributes-wrap">

		<h3><?php _e( 'Details', 'reviewer' ); ?></h3>

		<div class="review-attributes"><?php
			foreach ( $attributes as $k => $v ) :

				?><div class="review-attribute">
					<span class="review-attribute-name"><?php echo $v['name']; ?></span>
					<span class="review-attribute-value"><?php echo $v['value']; ?></span>
				</div><?php

			endforeach;
		?></div>

	</div><?php

endif;
