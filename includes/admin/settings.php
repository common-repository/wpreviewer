<?php
namespace Reviewer\Admin;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly


/**
 * Settings class.
 *
 * Object that handles everything regarding the settings page.
 *
 * @since 1.0.0
 */
class Settings {


	/**
	 * Constructor
	 *
	 * @since 1.0.0
	 */
	public function __construct() {}


	/**
	 * Initialize class hooks.
	 *
	 * @since 1.0.0
	 */
	public function init() {

		// Custom field type
		add_action( 'reviewer_admin_settings_field_system_info', array( $this, 'system_info_field_type' ), 10, 2 );

		// Save settings
		add_action( 'admin_init', array( $this, 'save' ) );

	}


	/**
	 * Get the available tabs.
	 *
	 * Get a  list with the available tabs + names.
	 *
	 * @since 1.0.0
	 *
	 * @return  array  List of tab keys => names.
	 */
	public function get_tabs() {

		return apply_filters( 'reviewer\admin\settings\tabs', array(
			'general' => __( 'General', 'reviewer' ),
			'reviews' => __( 'Reviews', 'reviewer' ),
			'system'  => __( 'System', 'reviewer' ),
		) );

	}


	/**
	 * Current active tab.
	 *
	 * Get the currently active tab (or fallback to default)
	 *
	 * @since 1.0.0
	 *
	 * @return  string
	 */
	public function active_tab() {

		return isset( $_GET['tab'] ) && in_array( $_GET['tab'], array_keys( $this->get_tabs() ) ) ? $_GET['tab'] : 'general';

	}


	/**
	 * Get settings for a tab.
	 *
	 * Get a list of the settings for a tab.
	 *
	 * @since 1.0.0
	 *
	 * @param   string  $tab  Tab to get the settings for.
	 * @return  array         List of settings
	 */
	public function get_settings( $tab = null ) {

		$settings = apply_filters( 'reviewer\admin\settings\get_settings', array(
			'general' => $this->get_general_settings(),
			'reviews' => $this->get_review_settings(),
			'system'  => $this->get_system_settings(),
		) );

		if ( ! is_null( $tab ) ) {
			return isset( $settings[ $tab ] ) ? $settings[ $tab ] : array();
		}

		return $settings;

	}


	/**
	 * Get general settings.
	 *
	 * Get a list with the general settings fields.
	 *
	 * @since 1.0.0
	 *
	 * @return  array  List of general settings.
	 */
	private function get_general_settings() {

		return apply_filters( 'reviewer\admin\settings\get_general_settings', array(
			'max_rating'  => array(
				'type'        => 'number',
				'name'        => __( 'Max rating score', 'reviewer' ),
				'desc'        => __( 'The maximum allowed score to give a review', 'reviewer' ),
				'default'     => 5,
				'class'       => array(),
				'custom_attr' => array(
					'min'  => '0',
					'step' => 1,
				),
			),
			'rating_step' => array(
				'type'    => 'dropdown',
				'name'    => __( 'Rating step', 'reviewer' ),
				'desc'    => __( 'The interval you can set the rating with', 'reviewer' ),
				'default' => '0.5',
				'class'   => array(),
				'options' => array(
					'0.1' => __( 'Per 0.1', 'reviewer' ),
					'0.5' => __( 'Per 0.5', 'reviewer' ),
					'1'   => __( 'Per 1', 'reviewer' ),
				),
			),
		) );

	}


	/**
	 * Get reviews settings.
	 *
	 * Get a list with the reviews settings fields.
	 *
	 * @since 1.0.0
	 *
	 * @return  array  List of status settings.
	 */
	private function get_review_settings() {

		return apply_filters( 'reviewer\admin\settings\get_review_settings', array(
			'review_archive_page_id' => array(
				'type' => 'page_select',
				'name' => __( 'Page to use for review overview', 'reviewer' ),
				'desc' => __( 'Select the page you\'d like to use to display the review archive', 'reviewer' ),
			),
			'images_heading'         => array(
				'type' => 'title',
				'name' => __( 'Review images', 'reviewer' ),
				'desc' => sprintf( __( 'After setting a new size you may need to %s', 'reviewer' ), '<a href="https://wordpress.org/plugins/regenerate-thumbnails/" target="_blank">Regenerate thumbnails</a>' )
			),
			'archive_image_size'     => array(
				'type'    => 'image_size',
				'name'    => __( 'Archive image size', 'reviewer' ),
				'desc'    => __( 'The images size for the archive page (in px)', 'reviewer' ),
				'default' => array(
					'width'  => 150,
					'height' => 150,
					'crop'   => 'yes',
				),
			),
			'review_image_size'      => array(
				'type'    => 'image_size',
				'name'    => __( 'Review image size', 'reviewer' ),
				'desc'    => __( 'The image size for the single review pages (in px)', 'reviewer' ),
				'default' => array(
					'width'  => 9999,
					'height' => 300,
					'crop'   => 'yes',
				),
			),
		) );

	}


	/**
	 * Get status settings.
	 *
	 * Get a list with the status settings fields.
	 *
	 * @since 1.0.0
	 *
	 * @return  array  List of status settings.
	 */
	private function get_system_settings() {

		return apply_filters( 'reviewer\admin\settings\get_system_settings', array(
			'wipe_data'   => array(
				'type' => 'checkbox',
				'name' => __( 'Wipe data on plugin delete', 'reviewer' ),
				'desc' => __( 'All data related to Reviewer will be wiped (including reviews!). It will be like this plugin was never installed. This <strong>CANNOT</strong> be undone. Make sure to create a backup before doing this.', 'reviewer' ),
			),
			'system_info' => array(
				'type' => 'hook',
				'name' => __( 'System info report', 'reviewer' ),
			),

		) );

	}


	/**
	 * Output the settings.
	 *
	 * Output the settings fields based on the current active tab.
	 *
	 * @since 1.0.0
	 */
	public function output() {

		$current_tab = $this->active_tab();
		$tabs        = $this->get_tabs();
		$settings    = $this->get_settings( $current_tab );

		require 'views/settings.php';

	}


	/**
	 * Save settings.
	 *
	 * Sanitize and save the settings.
	 *
	 * @since 1.0.0
	 * @return  bool
	 */
	public function save() {

		// Verify nonce
		if ( ! isset( $_POST['reviewer_settings_nonce'] ) || ! wp_verify_nonce( $_POST['reviewer_settings_nonce'], 'reviewer_save_settings' ) ) :
			return false;
		endif;

		// Verify current tab
		if ( ! isset( $_POST['current_tab'] ) || ! in_array( $_POST['current_tab'], array_keys( $this->get_tabs() ) ) ) :
			$_POST['tab'] = 'general';
		endif;

		$current_tab = $_POST['current_tab'];
		$settings    = $this->get_settings( $current_tab );

		foreach ( $settings as $setting_id => $args ) :

			$post_value = isset( $_POST[ $setting_id ] ) ? $_POST[ $setting_id ] : null;

			$value = $this->sanitize_setting_value( $post_value, $args, $setting_id );

			// Only allow sanitized values (+ don't save custom fields automatically)
			if ( ! is_null( $value ) ) :
				update_option( 'reviewer_' . $setting_id, $value );
			endif;

		endforeach;

		/**
		 * Save settings action hook.
		 *
		 * @since 1.0.0
		 *
		 * @param  string  $current_tab  Settings tab currently being saved.
		 */
		do_action( 'reviewer\save_settings', $current_tab );

		add_settings_error( 'general', 'settings_updated', __( 'Settings saved.' ), 'updated' );

//		wp_redirect( esc_url_raw( add_query_arg( 'tab', $current_tab, admin_url( 'admin.php?page=reviewer-settings' ) ) ) );
//		exit;

	}


	/**
	 * Sanitize setting value.
	 *
	 * Sanitize the value of a posted setting.
	 *
	 * @since 1.0.0
	 *
	 * @param   mixed   $post_value  Post value.
	 * @param   array   $args        Setting arguments.
	 * @param   string  $setting_id  ID of the setting.
	 * @return  mixed                Sanitized value.
	 */
	public function sanitize_setting_value( $post_value, $args, $setting_id ) {

		// Custom callbacks
		if ( isset( $args['sanitize_callback'] ) && function_exists( $args['sanitize_callback'] ) ) {
			return call_user_func( $args['sanitize_callback'], $post_value, $args, $setting_id );
		}

		$value = null;
		switch ( $args['type'] ) :

			case 'text' :
				$value = sanitize_text_field( $post_value );
				break;

			case 'number' :
				$value = intval( $post_value );
				break;

			case 'textarea' :
				$value = wp_kses_post( $post_value );
				break;

			case 'checkbox' :
				if ( isset( $args['options'] ) && ! empty( $args['options'] ) ) :
					foreach ( $args['options'] as $k => $v ) :
						$value[ $k ] = ! isset( $post_value[ $k ] ) || is_null( $post_value[ $k ] ) ? 'no' : 'yes';
					endforeach;
				else :
					$value = is_null( $post_value ) ? 'no' : 'yes';
				endif;
				break;

			case 'radio' :
				if ( isset( $args['options'] ) && ! empty( $args['options'] ) ) :
					foreach ( $args['options'] as $k => $v ) :
						$value[ $k ] = ! isset( $post_value[ $k ] ) || is_null( $post_value[ $k ] ) ? 'no' : 'yes';
					endforeach;
				else :
					$value = is_null( $post_value ) ? 'no' : 'yes';
				endif;
				break;

			case 'page_select' :
				$value = absint( $post_value );
				break;

			case 'select' :
			case 'dropdown' :
				if ( is_array( $post_value ) ) {
					$value = array_map( 'esc_attr', $post_value );
					$value = array_filter( $value );
				} else {
					$value = esc_attr( $post_value );
				}
				break;

			case 'image_size' :
				$value = array(
					'width'  => absint( $post_value['width'] ),
					'height' => absint( $post_value['height'] ),
					'crop'   => ! isset( $post_value['crop'] ) ? 'no' : 'yes',
				);
				break;

			default :
				$value = apply_filters( 'reviewer\admin\settings\sanitize_field_' . sanitize_key( $setting_id ), $post_value, $args );
				break;

		endswitch;

		return $value;

	}


	/**************************************************************
	 * Custom fields
	 *************************************************************/


	/**
	 * System status field.
	 *
	 * A custom field to output the system status report.
	 *
	 * @since 1.0.0
	 *
	 * @param  array   $args        List of arguments of the field.
	 * @param  string  $setting_id  ID of the setting.
	 */
	public function system_info_field_type( $args, $setting_id ) {

		global $wpdb;

		// Get theme info
		$theme_data = wp_get_theme();
		$theme      = $theme_data->Name . ' ' . $theme_data->Version;

		ob_start();
			require_once 'views/system-status-report.php';
		$system_status = ob_get_clean();
		?><tr>
			<th scope="row">
				<label for="<?php echo esc_attr( $args['id'] ); ?>"><?php echo esc_html( $args['name'] ); ?></label>
			</th>
			<td>
				<textarea cols="75" rows="20" style="font-family: Menlo, Monaco, monospace;"><?php
					echo esc_html( $system_status );
				?></textarea>
			</td>
		</tr><?php

	}


}
