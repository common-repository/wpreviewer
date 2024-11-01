<?php
/**
 * Template file for showing the review contents.
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

?><div class="review-rating review-intro"><?php

	include __DIR__ . '/../single-review/rating.php';

?></div>
