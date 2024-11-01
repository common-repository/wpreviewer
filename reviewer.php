<?php
/**
 * Plugin Name:     Reviewer
 * Plugin URI:      https://wpreviewer.com
 * Description:     Start writing reviews of the things you love and become a reviewer in minutes.
 * Version:         1.0.5
 * Author:          Jeroen Sormani
 * Author URI:      http://jeroensormani.com/
 * Text Domain:     reviewer
 *
 * Copyright 2016 - Jeroen Sormani
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */


if ( ! function_exists( 'add_filter' ) ) {
	header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	exit();
}

/**
 * Display PHP 5.3 required notice.
 *
 * Display a notice when the required PHP version is not met.
 *
 * @since 1.0.0
 */
function reviewer_php_version_notices() {

	?><div class='updated'>
		<p><?php echo sprintf( __( 'Reviewer requires PHP 5.3 or higher and your current PHP version is %s. Please (contact your host to) update your PHP version.', 'reviewer' ), PHP_VERSION ); ?></p>
	</div><?php

}

if ( version_compare( PHP_VERSION, '5.3', 'lt' ) ) {
	add_action( 'admin_notices', 'reviewer_php_version_notices' );
	return;
}

define( 'REVIEWER_FILE', __FILE__ );
require 'class-reviewer.php';
