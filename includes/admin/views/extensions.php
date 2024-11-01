<div class="wrap">
	<h1><?php _e( 'Reviewer extensions', 'reviewer' ); ?></h1>
	<p><?php _e( 'These are the official Reviewer extensions that are available and/or planned to be build.', 'reviewer' ); ?></p>
	<div id="post-body"><?php

		if ( $extensions ) :
			foreach ( $extensions as $extension ) :

				// Defaults
				$extension = wp_parse_args( $extension, array(
					'name'        => '',
					'image'       => array(
						'src'    => '',
						'height' => '',
						'width'  => '',
					),
					'description' => '',
					'ribbon'      => false,
					'links'       => array(),
				) );

				?><div class="extensor-box">

					<div class="extensor-header"><?php
						if ( ! empty( $extension['ribbon'] ) ) :
							?><span class="ribbon"><?php echo wp_kses_post( $extension['ribbon'] ); ?></span><?php
						endif;
						if ( ! empty( $extension['image']['src'] ) ) :
							?><img src="<?php echo esc_url( $extension['image']['src'] ); ?>" width="<?php echo absint( $extension['image']['width'] ); ?>" height="<?php echo absint( $extension['image']['height'] ); ?>"><?php
						endif;
					?></div><?php

					if ( ! empty( $extension['name'] ) ) :
						?><h2 class="extensor-title"><?php echo wp_kses_post( $extension['name'] ); ?></h2><?php
					endif;

					if ( ! empty( $extension['description'] ) ) :
						?><div class="extensor-content">
							<p><?php echo $extension['description']; ?></p>
						</div><?php
					endif;

					?><div class="extensor-footer"><?php
						foreach ( $extension['links'] as $link ) :
							?><a href="<?php echo esc_url( $link['href'] ); ?>" title="<?php echo esc_attr( $link['title'] ); ?>" class="<?php echo implode( ' ', array_map( 'sanitize_html_class', explode( ' ', $link['class'] ) ) ); ?>"><?php echo wp_kses_post( $link['text'] ); ?></a><?php
						endforeach;
					?></div>
				</div><?php

			endforeach;
		else :
			?><strong><?php _e( 'Couldn\'t get the extensions, please try again later. If this issue persists, please get in touch.', 'reviewer' ); ?></strong><?php
			?><br/><?php _e( 'You can always take a look at the extension on <a href="https://wpreviewer.com/extensions">wpreviewer.com/extensions</a>', 'reviewer' );
		endif;

	?></div>
</div>
