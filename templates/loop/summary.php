<?php
/**
 * Template file for showing the review summary.
 *
 * Override this template if desired by creating a file in wp-content/themes/your-theme/reviewer/{file-path}.php
 *
 * In some occasions the template files will be updated during updates. In such event
 * it is important to also update the template files in your theme to maintain maximum compatibility.
 *
 * @author 		Jeroen Sormani
 * @version     1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $post;

?><div class="review-summary review-intro entry-content"><?php
	the_excerpt( __( 'Continue reading <span class="meta-nav">&rarr;</span>', 'reviewer' ) );
?></div>
