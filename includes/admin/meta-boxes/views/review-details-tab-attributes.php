<div id="add-attribute-wrap">

	<em style="color: #999;"><?php _e( 'Attributes can be used to add details to the review like Creator, Genre, Release date etc.', 'reviewer' ); ?></em>
	<hr>

	<div class="attribute-row-add">
		<div class="attribute-input">
			<label class=""><?php
				_e( 'Name', 'reviewer' );
				?><input type="text" id="add-attribute-name" aria-required="true">
			</label>
		</div>
		<div class="attribute-input">
			<label class=""><?php
				_e( 'Value', 'reviewer' );
				?><input type="text" id="add-attribute-value">
			</label>
		</div>
		<div class="attribute-actions">
			<a href="javascript:void(0);" class="button button-secondary" id="add-attribute"><?php _e( 'Add', 'reviewer' ); ?></a>
		</div>
	</div>
</div>

<hr>

<h4><?php _e( 'Setup attributes', 'reviewer' ); ?></h4>
<div class="review-attributes"><?php
	foreach ( $review->get_attributes() as $k => $attribute ) :

		?><div class="attribute-row">
			<div class="attribute-input">
				<input type="text" name="_attributes[<?php echo absint( $k ); ?>][name]" value="<?php echo wp_kses_post( $attribute['name'] ); ?>">
			</div>
			<div class="attribute-input">
				<input type="text" name="_attributes[<?php echo absint( $k ); ?>][value]" value="<?php echo wp_kses_post( $attribute['value'] ); ?>">
			</div>
			<div class="attribute-actions">
				<a href="javascript:void(0);" class="button button-secondary delete-attribute"><?php _e( 'Delete', 'reviewer' ); ?></a>
			</div>
		</div><?php

	endforeach;

	if ( ! $review->get_attributes() ) {
		?><p class="no-attributes"><?php _e( 'There are no attributes yet.', 'reviewer' ); ?></p><?php
	}

?></div>

<!-- Template attribute row -->
<div class="attribute-row template hidden">
	<div class="attribute-input">
		<input type="text" class="add-attribute-name">
	</div>
	<div class="attribute-input">
		<input type="text" class="add-attribute-value">
	</div>
	<div class="attribute-actions">
		<a href="javascript:void(0);" class="button button-secondary delete-attribute"><?php _e( 'Delete', 'reviewer' ); ?></a>
	</div>
</div>
