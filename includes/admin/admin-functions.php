<?php
namespace Reviewer\Admin;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly


/**
 * Is current page is RV.
 *
 * Check if the current page being viewed is a RV related page.
 *
 * @since 1.0.0
 */
function is_reviewer_admin_page() {

	$current_screen = get_current_screen();
	$is_rv_page     = false;

	if ( get_post_type() == 'review' ) :
		$is_rv_page = true;
	elseif ( isset( $_GET['post_type'] ) && $_GET['post_type'] == 'review' ) :
		$is_rv_page = true;
	elseif ( isset( $_GET['page'] ) && $_GET['page'] == 'reviewer' ) :
		$is_rv_page = true;
	elseif ( $current_screen->id == 'reviews_page_reviewer-extensions' ) :
		$is_rv_page = true;
	endif;

	return apply_filters( 'reviewer\is_rv_admin_page', $is_rv_page );

}


/**
 * Add help tab content.
 *
 * Add content to the help tab on the Reviewer admin pages.
 *
 * @since 1.0.5
 */
function add_help_tab() {

	if ( is_reviewer_admin_page() ) {
		$screen = get_current_screen();

		if ( ! $screen ) return;

		ob_start();
			?><h2>Reviewer support</h2>
			<p>Looking for support? Feel free to reach out through the <a href="https://wpreviewer.com/support/?utm_source=helptab&utm_medium=wordpressadmin&utm_campaign=reviewer-plugin" target="_blank">support form on https://wpreviewr.com</a></p>
			<p>We'll do our best to respond to each message within 24 hours on business days.</p>
			<h2>Documentation</h2>
			<p>We've already documented a lot of frequent cases, you can take a look through the <a href="https://wpreviewer.com/documentation/?utm_source=helptab&utm_medium=wordpressadmin&utm_campaign=reviewer-plugin" target="_blank">documentation</a> to see if the answer you're looking for is already there.</p>
		<?php
		$support_tab_content = ob_get_clean();

		ob_start();
			?><h2>Customizing Reviewer</h2>
			<p>Reviewer can very easily be customized. Below are some resources where you can read more on how to do this.</p>
			<ul>
				<li><a href="https://wpreviewer.com/documentation/using-template-files/?utm_source=helptab&utm_medium=wordpressadmin&utm_campaign=reviewer-plugin" target="_blank">Changing the page templates</a></li>
				<li><a href="https://wpreviewer.com/documentation/review-images/?utm_source=helptab&utm_medium=wordpressadmin&utm_campaign=reviewer-plugin" target="_blank">Review images</a></li>
				<li><a href="https://wpreviewer.com/documentation/changing-star-rating/?utm_source=helptab&utm_medium=wordpressadmin&utm_campaign=reviewer-plugin" target="_blank">Changing the star rating</a></li>
			</ul>
			<?php
		$customizing_tab_content = ob_get_clean();

		$screen->add_help_tab( array(
			'id' => 'reviewer-support',
			'title' => 'Support',
			'content' => $support_tab_content,
		) );
		$screen->add_help_tab( array(
			'id' => 'reviewer-customizing',
			'title' => 'Customizing',
			'content' => $customizing_tab_content,
		) );
	}

}
add_action( 'current_screen', '\Reviewer\Admin\add_help_tab' );


/**
 * Enqueue scripts.
 *
 * Enqueue script as javascript and style sheets.
 *
 * @since 1.0.0
 */
function enqueue_scripts() {

	$min = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';

	// Scripts
	wp_register_script( 'blockit', plugins_url( 'assets/plugins/blockit/blockit' . $min . '.js', Reviewer()->file ), array( 'jquery' ), '0.1.0', true );
	wp_register_script( 'jquery-repeater', plugins_url( 'assets/plugins/repeater/jquery.repeater' . $min . '.js', Reviewer()->file ), array( 'jquery' ), '0.1.0', true );
	wp_register_script( 'reviewer', plugins_url( 'assets/admin/js/reviewer' . $min . '.js', Reviewer()->file ), array( 'jquery', 'jquery-ui-sortable' ), Reviewer()->version, true );
	wp_localize_script( 'reviewer', 'rv', array(
		'nonce'       => wp_create_nonce( 'reviewer-ajax-nonce' ),
		'admin_email' => get_option( 'admin_email' ),
	) );
	wp_register_script( 'reviewer-backbone-modal', plugins_url( 'assets/plugins/backbone-modal/backbone.modal-min.js', Reviewer()->file ), array( 'backbone' ), '1.1.5', true );

	// Stylesheets
	wp_register_style( 'reviewer', plugins_url( 'assets/admin/css/reviewer.min.css', Reviewer()->file ), array() );
	wp_register_style( 'reviewer-backbone-modal', plugins_url( 'assets/plugins/backbone-modal/backbone.modal.css', Reviewer()->file ), '1.1.5' );
	wp_register_style( 'reviewer-backbone-modal-theme', plugins_url( 'assets/plugins/backbone-modal/backbone.modal.theme.css', Reviewer()->file ), '1.1.5' );

	// Enqueue when needed
	if ( is_reviewer_admin_page() ) {
		wp_enqueue_script( 'reviewer' );
		wp_enqueue_style( 'reviewer' );
	}

}
add_action( 'admin_enqueue_scripts', 'Reviewer\Admin\enqueue_scripts' );


/**
 * Add body class.
 *
 * Add a 'reviewer' body class when its a Reviewer page.
 *
 * @since 1.0.0
 *
 * @param   string  $classes  String with all the existing classes.
 * @return  string            String with all the modified classes.
 */
function body_class( $classes ) {

	if ( is_reviewer_admin_page() ) :
		$classes .= ' reviewer';
	endif;

	return $classes;

}
add_filter( 'admin_body_class', 'Reviewer\Admin\body_class' );


/**************************************************************
 * Pages
 *************************************************************/

/**
 * Add admin pages.
 *
 * Add pages to the admin menu and register submenu pages.
 *
 * @since 1.0.0
 */
function admin_pages() {

	// Main menu
	add_menu_page( __( 'Reviews', 'reviewer' ), __( 'Reviews', 'reviewer' ), 'edit_posts', 'reviewer', null, 'dashicons-star-filled', '51.8' );

	// Add new review Submenu
	add_submenu_page(
		'reviewer',
		__( 'Add new Review', 'reviewer' ),
		__( 'Add new Review', 'reviewer' ),
		'edit_posts',
		'post-new.php?post_type=review'
	);

	// Tags Submenu
	add_submenu_page(
		'reviewer',
		__( 'Tags', 'reviewer' ),
		__( 'Tags', 'reviewer' ),
		'edit_posts',
		'edit-tags.php?taxonomy=review_tag'
	);

	// Category Submenu
	add_submenu_page(
		'reviewer',
		__( 'Categories', 'reviewer' ),
		__( 'Categories', 'reviewer' ),
		'edit_posts',
		'edit-tags.php?taxonomy=review_category'
	);

	// Settings submenu
	add_submenu_page(
		'reviewer',
		__( 'Reviewer Settings', 'reviewer' ),
		__( 'Settings', 'reviewer' ),
		'edit_posts',
		'reviewer',
		'\Reviewer\Admin\settings_page'
	);

	// Extensions submenu
	add_submenu_page(
		'reviewer',
		__( 'Extensions', 'reviewer' ),
		__( 'Extensions', 'reviewer' ),
		'edit_posts',
		'reviewer-extensions',
		'\Reviewer\Admin\extensions_page'
	);

}
add_action( 'admin_menu', '\Reviewer\Admin\admin_pages' );


/**
 * Settings page.
 *
 * This is the settings page callback function, outputting all the settings.
 *
 * @since 1.0.0
 */
function settings_page() {
	Reviewer()->admin->settings->output();
}


/**
 * Extensions page.
 *
 * Show the page with the extensions on it.
 *
 * @since 1.0.0
 */
function extensions_page() {

	if ( ! $extensions = get_transient( 'reviewer_extensions_list' ) ) {
		$response = wp_remote_get( 'https://wpreviewer.com/wp-content/themes/wpreviewer/reviewer-extensions.php' );
		if ( ! is_wp_error( $response ) ) {
			$extensions = json_decode( $response['body'], true );
			set_transient( 'reviewer_extensions_list', $extensions, DAY_IN_SECONDS );
		}
	}

	require 'views/extensions.php';

}


/**
 * Send 'notify me' request.
 *
 * Do a action when someone sends a 'notify me' request for a extension.
 *
 * @since 1.0.0
 */
function extensor_notify_me() {

	check_ajax_referer( 'reviewer-ajax-nonce', 'nonce' );

	if ( ! isset( $_POST['email'] ) || empty( $_POST['email'] ) ) {
		$email = get_option( 'admin_email', null );
	} else {
		$email = sanitize_email( $_POST['email'] );
	}

	$extension = isset( $_POST['extension'] ) ? sanitize_text_field( $_POST['extension'] ) : 'unknown';
	$site      = site_url();

	$message = "Hi you!

	I'm interested in hearing when a extension for Reviewer is available.

	Site: $site
	Email: $email
	Extension interested in: $extension";
	wp_mail( 'info@jeroensormani.com', 'Reviewer extension interest', $message );

}
add_action( 'wp_ajax_extensor_notify_me', '\Reviewer\Admin\extensor_notify_me' );


/**
 * Output a field.
 *
 * Output the HTML of a specific field type.
 *
 * @since 1.0.0
 *
 * @param  array   $args        List of arguments to create the field on.
 * @param  string  $setting_id  Unique ID of the setting
 */
function html_settings_field( $args, $setting_id = '' ) {

	$args = wp_parse_args( $args, array(
		'id'          => $setting_id, // Default its the key of the array, can be overridden by $args['id']
		'type'        => '',
		'name'        => '',
		'class'       => '',
		'value'       => null,
		'default'     => '',
		'desc'        => '',
		'placeholder' => '',
		'custom_attr' => array(),
	) );

	$custom_attr = array();
	if ( ! empty( $args['custom_attr'] ) && is_array( $args['custom_attr'] ) ) {
		foreach ( $args['custom_attr'] as $key => $value ) {
			$custom_attr[ $key ] = esc_attr( $key ) . '="' . esc_attr( $value ) . '"';
		}
	}

	// Value of the setting
	if ( ! is_null( $args['value'] ) ) :
		$value = $args['value'];
	else :
		$value = get_option( 'reviewer_' . $args['id'], $args['default'] );
	endif;

	// Classes
	$class = is_array( $args['class'] ) ? implode( ' ', array_map( 'sanitize_html_class', $args['class'] ) ) : sanitize_html_class( $args['class'] );

	switch ( $args['type'] ) :

		case 'text' :
		case 'number' :
			?><tr>
				<th scope="row">
					<label for="<?php echo esc_attr( $args['id'] ); ?>"><?php echo esc_html( $args['name'] ); ?></label>
				</th>
				<td>
					<input
						name="<?php echo esc_attr( $args['id'] ); ?>"
						type="<?php echo $args['type']; ?>"
						id="<?php echo esc_attr( $args['id'] ); ?>"
						value="<?php echo esc_attr( $value ); ?>"
						class="input-text <?php echo $class; ?>"
						<?php echo implode( ' ', $custom_attr ); ?>
						placeholder='<?php echo esc_attr( $args['placeholder'] ); ?>'
					><?php

					if ( ! empty( $args['desc'] ) ) :
						?><p class="description"><?php echo wp_kses_post( $args['desc'] ); ?></p><?php
					endif;
				?></td>
			</tr><?php
			break;

		case 'dropdown' :
		case 'select' :

			$options = is_array( $args['options'] ) ? $args['options'] : array();

			?><tr>
				<th scope="row">
					<label for="<?php echo esc_attr( $args['id'] ); ?>"><?php echo esc_html( $args['name'] ); ?></label>
				</th>
				<td>
					<select
						name="<?php echo esc_attr( $args['id'] ); ?>"
						id="<?php echo esc_attr( $args['id'] ); ?>"
						class="input-select <?php echo $class; ?>"
						<?php echo implode( ' ', $custom_attr ); ?>
					><?php

						foreach ( $options as $k => $v ) :
							$selected = is_array( $value ) ? in_array( $k, $value ) : $k == $value;
							?><option value="<?php echo esc_attr( $k ); ?>" <?php selected( $selected ); ?>><?php echo wp_kses_post( $v ); ?></option><?php
						endforeach;

					?></select><?php

					if ( ! empty( $args['desc'] ) ) :
						?><p class="description"><?php echo wp_kses_post( $args['desc'] ); ?></p><?php
					endif;
				?></td>
			</tr><?php
			break;

		case 'page_select' :

			?><tr>
				<th scope="row">
					<label for="<?php echo esc_attr( $args['id'] ); ?>"><?php echo esc_html( $args['name'] ); ?></label>
				</th>
				<td><?php
					wp_dropdown_pages( array(
						'name'              => $args['id'],
						'echo'              => 1,
						'show_option_none'  => __( 'Select a page', 'reviewer' ) . '&hellip;',
						'option_none_value' => '',
						'selected'          => $value
					) );

					if ( ! empty( $args['desc'] ) ) :
						?><p class="description"><?php echo wp_kses_post( $args['desc'] ); ?></p><?php
					endif;
				?></td>
			</tr><?php

			break;

		case 'textarea' :

			// Default custom attributes
			$custom_attr = wp_parse_args( $custom_attr, array(
				'cols' => 'cols="50"',
				'rows' => 'rows="3"',
			) );

			?><tr>
				<th scope="row">
					<label for="<?php echo esc_attr( $args['id'] ); ?>"><?php echo esc_html( $args['name'] ); ?></label>
				</th>
				<td>
					<textarea
						name="<?php echo esc_attr( $args['id'] ); ?>"
						id="<?php echo esc_attr( $args['id'] ); ?>"
						class="input-textarea <?php echo $class; ?>"
						<?php echo implode( ' ', $custom_attr ); ?>
						placeholder='<?php echo esc_attr( $args['placeholder'] ); ?>'
					><?php
						echo esc_textarea( $value );
					?></textarea><?php

					if ( ! empty( $args['desc'] ) ) :
						?><p class="description"><?php echo wp_kses_post( $args['desc'] ); ?></p><?php
					endif;
				?></td>
			</tr><?php
			break;

		case 'checkbox' :

			$args = wp_parse_args( $args, array(
				'options' => array(),
			) );

			?><tr>
				<th scope="row"><?php echo esc_html( $args['name'] ); ?></th>
				<td>
					<fieldset><?php
						if ( ! empty( $args['options'] ) ) :

							foreach ( $args['options'] as $k => $v ) :
								?><label for="<?php echo esc_attr( $args['id'] . '_' . $k ); ?>">
									<input
										name="<?php echo esc_attr( $args['id'] ); ?>[<?php echo esc_attr( $k ); ?>]"
										type="checkbox"
										id="<?php echo esc_attr( $args['id'] . '_' . $k ); ?>"
										value="yes"
										class="input-checkbox <?php echo $class; ?>"
										<?php echo checked( isset( $value[ $k ] ) && 'yes' == $value[ $k ] ); ?>
									><?php
									echo esc_html( $v );
								?></label><br/><?php
							endforeach;

						else :

							?><label for="<?php echo esc_attr( $args['id'] ); ?>">
								<input
									name="<?php echo esc_attr( $args['id'] ); ?>"
									type="checkbox"
									id="<?php echo esc_attr( $args['id'] ); ?>"
									value="yes"
									class="input-checkbox <?php echo $class; ?>"
									<?php echo checked( 'yes', $value ); ?>
								><?php
								echo esc_html( $args['name'] );
							?></label><br/><?php

						endif;
					?></fieldset><?php

					if ( ! empty( $args['desc'] ) ) :
						?><p class="description"><?php echo wp_kses_post( $args['desc'] ); ?></p><?php
					endif;
				?></td>
			</tr><?php
			break;

		case 'radio' :

			$args = wp_parse_args( $args, array(
				'options' => array(),
			) );

			?><tr>
				<th scope="row"><?php echo esc_html( $args['name'] ); ?></th>
				<td>
					<fieldset><?php
						if ( ! empty( $args['options'] ) ) :

							foreach ( $args['options'] as $k => $v ) :
								?><label for="<?php echo esc_attr( $args['id'] . '_' . $k ); ?>">
								<input name="<?php echo esc_attr( $args['id'] ); ?>[<?php echo esc_attr( $k ); ?>]" type="radio"
								       id="<?php echo esc_attr( $args['id'] . '_' . $k ); ?>" value="yes" class="input-radio <?php echo $class; ?>" <?php
								echo checked( isset( $value[ $k ] ) && 'yes' == $value[ $k ] ); ?>><?php
								echo esc_html( $v );
								?></label><br/><?php
							endforeach;

						else :

							?><label for="<?php echo esc_attr( $args['id'] ); ?>">
							<input name="<?php echo esc_attr( $args['id'] ); ?>" type="radio" id="<?php echo esc_attr( $args['id'] ); ?>"
							       value="yes" class="input-radio <?php echo $class; ?>" <?php echo checked( 'yes', $value ); ?>><?php
							echo esc_html( $args['name'] );
							?></label><br/><?php

						endif;
						?></fieldset><?php

					if ( ! empty( $args['desc'] ) ) :
						?><p class="description"><?php echo wp_kses_post( $args['desc'] ); ?></p><?php
					endif;
				?></td>
			</tr><?php
			break;

		case 'title' :

			?><tr>
				<th colspan="2">
					<h3 style='padding-left: 0;'><?php echo wp_kses_post( $args['name'] ); ?></h3>
					<p style='font-weight: normal; margin: 0;'><?php echo wp_kses_post( $args['desc'] ); ?></p>
				</th>
			</tr><?php
			break;

		case 'description' :

			?><tr>
				<td colspan="2" style='padding-left: 0;'><?php
					if ( ! empty( $args['name'] ) ) :
						?><p><?php echo wp_kses_post( $args['name'] ); ?></p><?php
					endif;
					if ( ! empty( $args['desc'] ) ) :
						?><p><?php echo wp_kses_post( $args['desc'] ); ?></p><?php
					endif;
				?></td>
			</tr><?php
			break;

		case 'image_size' :

			$value = wp_parse_args( $value, array(
				'width'  => 100,
				'height' => 100,
				'crop'   => 'yes',
			) );

			?><tr>
				<th scope="row">
					<label for="<?php echo esc_attr( $args['id'] ); ?>"><?php echo esc_html( $args['name'] ); ?></label>
				</th>
				<td>
					<input
						name="<?php echo esc_attr( $args['id'] ); ?>[width]"
						type="number"
						id="<?php echo esc_attr( $args['id'] ); ?>_width"
						value="<?php echo esc_attr( $value['width'] ); ?>"
						class="input-number input-image-size-number <?php echo $class; ?>"
					>
					x
					<input
						name="<?php echo esc_attr( $args['id'] ); ?>[height]"
						type="number"
						id="<?php echo esc_attr( $args['id'] ); ?>_height"
						value="<?php echo esc_attr( $value['height'] ); ?>"
						class="input-number input-image-size-number <?php echo $class; ?>"
					>
					<label for="<?php echo esc_attr( $args['id'] ); ?>_crop">
						<input
							name="<?php echo esc_attr( $args['id'] ); ?>[crop]"
							type="checkbox"
							id="<?php echo esc_attr( $args['id'] ); ?>_crop"
							value="yes"
							class="input-radio <?php echo $class; ?>"
							<?php echo checked( 'yes', $value['crop'] ); ?>
						><?php
						_e( 'Crop image?', 'reviewer' );
					?></label><br/><?php

					if ( ! empty( $args['desc'] ) ) :
						?><p class="description"><?php echo wp_kses_post( $args['desc'] ); ?></p><?php
					endif;
				?></td>
			</tr><?php
			break;

		default :
		case 'hook' :
			do_action( 'reviewer\admin_settings_field_' . $setting_id, $args, $setting_id );
			break;

	endswitch;

}


/**
 * Page install notice
 *
 * Display a notice when the reviews page is not present.
 *
 * @since 1.0.0
 */
function page_install_notice() {

	if ( isset( $_GET['reviewer_install_pages'] ) ) {
		if ( isset( $_GET['_wpnonce'] ) && wp_verify_nonce( $_GET['_wpnonce'] ) ) {
			if ( $_GET['reviewer_install_pages'] == 0 ) {
				update_option( 'reviewer_review_archive_page_id', '' );
			} elseif ( $_GET['reviewer_install_pages'] == 1 ) {
				require_once plugin_dir_path( Reviewer()->file ) . 'includes/installer.php';
				$installer = new \Reviewer\Installer();
				$installer->install_pages();
			}
		}
	}

	if ( get_option( 'reviewer_review_archive_page_id', null ) === null ) {

		?><div class='updated'>

			<p><strong><?php
				_e( 'Thanks for using Reviewer :-)', 'reviewer' );
				?></strong><br/><?php
				_e( 'Before we can display the reviews on the front-end we need to create a page to display them on.' ); ?>
			</p>

			<p class='submit'>
				<a class='button-primary' href='<?php echo esc_url( wp_nonce_url( add_query_arg( 'reviewer_install_pages', '1' ) ) ); ?>'><?php
					_e( 'Install page', 'reviewer' );
				?></a>
				<a class='skip button-secondary' href='<?php echo esc_url( wp_nonce_url( add_query_arg( 'reviewer_install_pages', '0' ) ) ); ?>'><?php
					_e( 'Dismiss', 'reviewer' );
				?></a>
			</p>

		</div><?php
	}

}
add_action( 'admin_notices', 'Reviewer\Admin\page_install_notice' );
