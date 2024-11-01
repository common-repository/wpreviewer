### Begin System Info ###

-- Site Info

Site URL:                 <?php echo site_url() . PHP_EOL; ?>
Home URL:                 <?php echo home_url() . PHP_EOL; ?>
Multisite:                <?php echo is_multisite() ? 'Yes' : 'No' . PHP_EOL; ?>

-- WordPress Configuration

Version:                  <?php echo get_bloginfo( 'version' ) . PHP_EOL; ?>
Language:                 <?php echo defined( 'WPLANG' ) && WPLANG ? WPLANG : 'en_US' . PHP_EOL; ?>
Permalink Structure:      <?php echo get_option( 'permalink_structure' ) ? get_option( 'permalink_structure' ) : 'Default'; echo PHP_EOL ?>
Active Theme:             <?php echo $theme . PHP_EOL; ?>
Show On Front:            <?php echo get_option( 'show_on_front' ) . PHP_EOL;

// Make sure wp_remote_post() is working
$request['cmd'] = '_notify-validate';

$params = array(
	'sslverify'  => false,
	'timeout'    => 60,
	'user-agent' => 'RV/' . \Reviewer\Reviewer()->version,
	'body'       => $request
);

$response = wp_remote_post( 'https://www.paypal.com/cgi-bin/webscr', $params );

if ( ! is_wp_error( $response ) && $response['response']['code'] >= 200 && $response['response']['code'] < 300 ) {
	$wp_remote_post = 'wp_remote_post() works';
} else {
	$wp_remote_post = 'wp_remote_post() does not work';
}
?>
Remote Post:              <?php echo $wp_remote_post . PHP_EOL; ?>
Table Prefix:             <?php echo 'Length: ' . strlen( $wpdb->prefix ) . '   Status: ' . ( strlen( $wpdb->prefix ) > 16 ? 'ERROR: Too long' : 'Acceptable' ) . PHP_EOL; ?>
WP_DEBUG:                 <?php echo ( defined( 'WP_DEBUG' ) ? ( WP_DEBUG ? 'Enabled' : 'Disabled' ) : 'Not set' ) . PHP_EOL; ?>
Memory Limit:             <?php echo WP_MEMORY_LIMIT . PHP_EOL; ?>
Registered Post Stati:    <?php echo implode( ', ', get_post_stati() ) . PHP_EOL; ?>

-- WP Advanced Emails Configuration

Version:                  <?php echo \Reviewer\Reviewer()->version . PHP_EOL; ?>
DB version:               <?php echo \Reviewer\Reviewer()->version . PHP_EOL; ?>
File path:                <?php echo \Reviewer\Reviewer()->file . PHP_EOL; ?>

<?php
// Get plugins that have an update
$updates = get_plugin_updates();

// Must-use plugins
// NOTE: MU plugins can't show updates!
if ( $muplugins = get_mu_plugins() ) {
	?>-- Must-Use Plugins
	<?php
	foreach ( $muplugins as $plugin => $plugin_data ) {
		echo $plugin_data['Name'] . ': ' . $plugin_data['Version'] . PHP_EOL;
	}
}

?>
-- WordPress Active Plugins

<?php
$plugins        = get_plugins();
$active_plugins = get_option( 'active_plugins', array() );

foreach ( $plugins as $plugin_path => $plugin ) {
	if ( ! in_array( $plugin_path, $active_plugins ) ) {
		continue;
	}

	$update = ( array_key_exists( $plugin_path, $updates ) ) ? ' (needs update - ' . $updates[ $plugin_path ]->update->new_version . ')' : '';
	echo $plugin['Name'] . ': ' . $plugin['Version'] . $update . PHP_EOL;
}
?>

-- WordPress Inactive Plugins

<?php
foreach ( $plugins as $plugin_path => $plugin ) {
	if ( in_array( $plugin_path, $active_plugins ) ) {
		continue;
	}

	$update = ( array_key_exists( $plugin_path, $updates ) ) ? ' (needs update - ' . $updates[ $plugin_path ]->update->new_version . ')' : '';
	echo $plugin['Name'] . ': ' . $plugin['Version'] . $update . PHP_EOL;
}

if ( is_multisite() ) {
	?>-- Network Active Plugins

	<?php
	$plugins        = wp_get_active_network_plugins();
	$active_plugins = get_site_option( 'active_sitewide_plugins', array() );

	foreach ( $plugins as $plugin_path ) {
		$plugin_base = plugin_basename( $plugin_path );

		if ( ! array_key_exists( $plugin_base, $active_plugins ) ) {
			continue;
		}

		$update = ( array_key_exists( $plugin_path, $updates ) ) ? ' (needs update - ' . $updates[ $plugin_path ]->update->new_version . ')' : '';
		$plugin = get_plugin_data( $plugin_path );
		echo $plugin['Name'] . ': ' . $plugin['Version'] . $update . PHP_EOL;
	}

}

?>

-- Webserver Configuration

PHP Version:              <?php echo PHP_VERSION . PHP_EOL; ?>
MySQL Version:            <?php echo $wpdb->db_version() . PHP_EOL; ?>
Webserver Info:           <?php echo $_SERVER['SERVER_SOFTWARE'] . PHP_EOL; ?>

-- PHP Configuration

Safe Mode:                <?php echo ini_get( 'safe_mode' ) ? 'Enabled' : 'Disabled' . PHP_EOL; ?>
Memory Limit:             <?php echo ini_get( 'memory_limit' ) . PHP_EOL; ?>
Upload Max Size:          <?php echo ini_get( 'upload_max_filesize' ) . PHP_EOL; ?>
Post Max Size:            <?php echo ini_get( 'post_max_size' ) . PHP_EOL; ?>
Upload Max Filesize:      <?php echo ini_get( 'upload_max_filesize' ) . PHP_EOL; ?>
Time Limit:               <?php echo ini_get( 'max_execution_time' ) . PHP_EOL; ?>
Max Input Vars:           <?php echo ini_get( 'max_input_vars' ) . PHP_EOL; ?>
Display Errors:           <?php echo ini_get( 'display_errors' ) ? 'On (' . ini_get( 'display_errors' ) . ')' : 'N/A' . PHP_EOL;
?>


-- PHP Extensions

cURL:                     <?php echo function_exists( 'curl_init' ) ? 'Supported' : 'Not Supported'; echo PHP_EOL ?>
fsockopen:                <?php echo function_exists( 'fsockopen' ) ? 'Supported' : 'Not Supported'; echo PHP_EOL ?>
SOAP Client:              <?php echo class_exists( 'SoapClient' ) ? 'Installed' : 'Not Installed'; echo PHP_EOL ?>
Suhosin:                  <?php echo extension_loaded( 'suhosin' ) ? 'Installed' : 'Not Installed'; echo PHP_EOL ?>

### End System Info ###
