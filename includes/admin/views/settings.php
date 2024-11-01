<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

?><div class='reviewer-settings wrap'><?php

	settings_errors();

	?><form method="post" action="">
		<input type="hidden" name="option_page" value="reviewer-settings">
		<input type="hidden" name="current_tab" value="<?php echo $current_tab; ?>">

		<!-- Tabs -->
		<h2 class="nav-tab-wrapper"><?php

			foreach ( $tabs as $tab_key => $tab_name ) :
				$active = $current_tab == $tab_key ? 'nav-tab-active' : '';
				?><a
					href="<?php echo esc_url( add_query_arg( 'tab', $tab_key, admin_url( 'admin.php?page=reviewer' ) ) ); ?>"
					title="<?php echo esc_html( $tab_name ); ?>"
					class="nav-tab reviewer-settings-tab-<?php echo sanitize_html_class( $tab_key ); ?> <?php echo $active; ?>"
				><?php
					echo esc_html( $tab_name );
				?></a><?php
			endforeach;

		?></h2>


		<!-- Settings -->
		<div class='reviewer-settings reviewer-settings-<?php echo sanitize_html_class( $current_tab ); ?>'>
			<table class="form-table">
				<tbody><?php

					foreach ( $settings as $setting_id => $args ) :
						\Reviewer\Admin\html_settings_field( $args, $setting_id );
					endforeach;

				?></tbody>
			</table>
		</div>

		<p class="submit">
			<input type="submit" name="submit" id="submit" class="button button-primary" value="<?php _e( 'Save Changes', 'reviewer' ); ?>"><?php
			wp_nonce_field( 'reviewer_save_settings', 'reviewer_settings_nonce' );
		?></p>

	</form>

</div>
