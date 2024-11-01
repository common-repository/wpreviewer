<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) exit(); // Exit if uninstall is not initialized

if ( 'yes' === get_option( 'reviewer_wipe_data', 'no' ) ) :

	global $wpdb;

	// Remove all 'review' posts - currently limited 999 reviews
	$post_ids = $wpdb->get_var( "SELECT ID FROM $wpdb->posts WHERE post_type = 'review' LIMIT 999" );

	// Loop and delete
	foreach ( $post_ids as $post_id ) {
		wp_delete_post( $post_id, true );
	}

	// Overview page
	if ( $page_id = get_option( 'reviewer_review_archive_page_id' ) ) {
		wp_delete_post( $page_id, true );
	}

	// Remove options
	$wpdb->query( "DELETE FROM $wpdb->options WHERE option_name LIKE 'reviewer_%'" );


endif;
