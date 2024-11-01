<?php
/**
 * Template file for showing the review contents.
 *
 * Override this template if desired by creating a file in wp-content/themes/your-theme/reviewer/single-review/content.php
 *
 * In some occasions the template files will be updated during updates. In such event
 * it is important to also update the template files in your theme to maintain maximum compatibility.
 *
 * @author 		Jeroen Sormani
 * @version     1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

?><div class="review-content"><?php
	the_content();
?></div>
