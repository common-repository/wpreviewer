<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

$tab_keys   = array_keys( $tabs );
$first_tab  = reset( $tab_keys );
$active_tab = ( isset( $_GET['tab'] ) && array_search( $_GET['tab'], $tab_keys ) !== false ) ? $_GET['tab'] : 'general';

?><div class='reviewer-review-details'>

	<div class='inside'>

		<div class='tabs-panels-wrap'>
			<div class='tabs'>
				<ul><?php
					foreach ( $tabs as $key => $tab ) :
						$class = $key == $active_tab ? 'active' : '';
						?><li class='<?php echo $class; ?>'><a href='javascript:void(0);' data-target='review-detail-panel-<?php echo esc_attr( $key ); ?>' data-key="<?php echo esc_attr( $key ); ?>"><?php echo esc_html( $tab['name'] ); ?></a></li><?php
					endforeach;
				?></ul>
			</div>

			<div class='panels reviewer-panels'><?php

				foreach ( $tabs as $key => $tab ) :

					$active = $key == $active_tab ? 'active' : '';
					?><div
						id='review-detail-panel-<?php echo esc_attr( $key ); ?>'
						class='panel <?php echo $active; ?> <?php echo implode( ' ', array_map( 'sanitize_html_class', $tab['classes'] ) ); ?>'
						style='<?php echo $key != $active_tab ? 'display: none;' : ''; ?>'
					><?php

						if ( file_exists( plugin_dir_path( __FILE__ ) . 'review-details-tab-' . $key . '.php' ) ) {
							include plugin_dir_path( __FILE__ ) . 'review-details-tab-' . $key . '.php';
						}

						do_action( 'reviewer\details_after_tab_' . $key, $review );

					?></div><?php

				endforeach;

			?></div>

			<div class='clear'></div>
		</div>

	</div>

</div>
