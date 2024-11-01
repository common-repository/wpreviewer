<div id="review-general-panel">

	<div class="options_group">
		<p class="input-field-wrap star-rating-field ">
			<label for="_rating"><?php _e( 'Rating', 'reviewer' ); ?></label>
			<input
				type="number"
				class=""
				name="_rating"
				id="_rating"
				value="<?php echo esc_attr( $review->get_rating() ); ?>"
			    min="0"
			    max="<?php echo esc_attr( $review->get_max_rating() ); ?>"
			    step="<?php echo esc_attr( $review->get_rating_step() ); ?>"
			    placeholder="<?php _e( 'Enter your rating', 'reviewer' ); ?>"
			>
			<small><?php _e( 'When no rating is given, the rating visualisation will not show up.', 'reviewer' ); ?></small>
		</p>

	</div>

	<hr>

	<div class="options_group">
		<p class="input-field-wrap summary-field ">
			<label for="excerpt"><?php _e( 'Summary', 'reviewer' ); ?></label>
			<small><?php _e( 'The summary is used on the archive page', 'reviewer' ); ?></small><?php
			wp_editor( $review->post_excerpt, 'excerpt', array(
				'textarea_rows' => 5,
			) );
		?></p>

	</div>

</div>
