<?php
/**
 * Template file for showing the review search form.
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

?><form role="search" method="get" class="reviewer-review-search" action="<?php echo esc_url( home_url( '/' ) ); ?>">
	<label class="screen-reader-text" for="reviewer-search-field"><?php _e( 'Search for:', 'reviewer' ); ?></label>
	<input type="search" id="reviewer-search-field" class="search-field" placeholder="<?php echo esc_attr_x( 'Search reviews&hellip;', 'placeholder', 'reviewer' ); ?>" value="<?php echo get_search_query(); ?>" name="s" title="<?php echo esc_attr_x( 'Search for:', 'label', 'reviewer' ); ?>" />
	<input type="submit" value="<?php echo esc_attr_x( 'Search', 'submit button', 'reviewer' ); ?>" />
	<input type="hidden" name="post_type" value="review" />
</form>
