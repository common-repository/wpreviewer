<?php
namespace Reviewer\Admin;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly


/**
 * Plugin row meta.
 *
 * Add extra plugin row meta, these are links / meta below the plugin description.
 *
 * @since 1.0.0
 *
 * @param   array   $links  List of existing links.
 * @param   string  $file   Name of the current plugin being looped.
 * @return  array           List of modified links.
 */
function add_plugin_row_meta( $links, $file ) {

	if ( $file == plugin_basename( Reviewer()->file ) ) :
		$links[] = '<a href="https://wpreviewer.com/documentation/" target="_blank">' . __( 'Documentation', 'reviewer' ) . '</a>';
		$links[] = '<a href="https://wpreviewer.com/extensions/" target="_blank">' . __( 'Extensions', 'reviewer' ) . '</a>';
		$links[] = '<a href="https://wpreviewer.com/support/" target="_blank">' . __( 'Support', 'reviewer' ) . '</a>';
	endif;

	return $links;

}
add_filter( 'plugin_row_meta', '\Reviewer\Admin\add_plugin_row_meta', 10, 2 );


/**
 * Plugin action links.
 *
 * Add links to the plugins.php page below the plugin name
 * and besides the 'activate', 'edit', 'delete' action links.
 *
 * @since 1.1.8
 *
 * @param   array   $links  List of existing links.
 * @param   string  $file   Name of the current plugin being looped.
 * @return  array           List of modified links.
 */
function add_plugin_action_links( $links, $file ) {

	if ( $file == plugin_basename( Reviewer()->file ) ) :
		$links = array_merge( array(
			'<a href="' . esc_url( admin_url( 'admin.php?page=reviewer' ) ) . '">' . __( 'Settings', 'reviewer' ) . '</a>'
		), $links );
	endif;

	return $links;

}
add_filter( 'plugin_action_links_' . plugin_basename( Reviewer()->file ), '\Reviewer\Admin\add_plugin_action_links', 10, 2 );
