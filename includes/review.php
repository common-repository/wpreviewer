<?php
namespace Reviewer;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly


/**
 * Review.
 *
 * This class represents a single review object and should be
 * used to mange and get the review properties.
 *
 * @since 1.0.0
 *
 * @inheritdoc WP_Post
 * @extends WP_Post
 *
 * @property-read int $ID Post ID.
 * @property-read string $post_author ID of post author.
 * @property-read string $post_date The post's local publication time.
 * @property-read string $post_date_gmt The post's GMT publication time.
 * @property-read string $post_content The post's content.
 * @property-read string $post_title The post's title.
 * @property-read string $post_excerpt The post's excerpt.
 * @property-read string $post_status The post's status.
 * @property-read string $comment_status Whether comments are allowed.
 * @property-read string $ping_status Whether pings are allowed.
 * @property-read string $post_password The post's password in plain text.
 * @property-read string $post_name The post's slug.
 * @property-read string $to_ping URLs queued to be pinged.
 * @property-read string $pinged URLs that have been pinged.
 * @property-read string $post_modified The post's local modified time.
 * @property-read string $post_modified_gmt The post's GMT modified time.
 * @property-read string $post_content_filtered A utility DB field for post content.
 * @property-read int $post_parent ID of a post's parent post.
 * @property-read string $guid The unique identifier for a post, not necessarily a URL, used as the feed GUID.
 * @property-read int $menu_order A field used for ordering posts.
 * @property-read string $post_type The post's type, like post or page.
 * @property-read string $post_mime_type An attachment's mime type.
 * @property-read string $comment_count Cached comment count.
 * @property-read string $filter Stores the post object's sanitization level.
 */
class Review {

	/**
	 * @var int Review (Post) ID.
	 */
	public $id;

	/**
	 * @var array List of ratings for the reviewed items.
	 */
	private $rating;

	/**
	 * @var float Maximum rating.
	 */
	private $max_rating;

	/**
	 * @var float Rating step interval.
	 */
	private $rating_step;

	/**
	 * @var array List of attributes.
	 */
	private $attributes;

	/**
	 * @var string Review status.
	 */
	private $status;


	/**
	 * Constructor.
	 *
	 * @since 1.0.0

	 * @param  int  $review_id  ID of the review to get.
	 */
	public function __construct( $review_id ) {

		if ( ! get_post_type( $review_id ) == 'review' ) {
			return false;
		}

		$this->setup( $review_id );

	}


	/**
	 * Magic __get method.
	 *
	 * Since we cannot extend the final class WP_Post we need another seamless way to access the
	 * properties of the post object. This is done within the magic method.
	 *
	 * @since 1.0.0
	 *
	 * @param   string      $name  Property being called.
	 * @return  mixed|null         The property value if it exists, null otherwise.
	 */
	public function __get( $name ) {

		$post = get_post( $this->id );

		if ( isset( $post->{$name} ) ) {
			return $post->{$name};
		}

		return null;

	}


	/**
	 * Magic __isset method.
	 *
	 * Since we cannot extend the final class WP_Post we need another seamless way to access the
	 * properties of the post object. This is done within the magic method. This magic method helps
	 * to do a check whether one of the magic properties exists or not.
	 *
	 * @since 1.0.0
	 *
	 * @param   string      $name  Property being called
	 * @return  mixed|null         The property value if it exists, null otherwise.
	 */
	public function __isset( $name ) {

		if ( ! is_null( $this->{$name} ) ) {
			return true;
		}

		return false;

	}

	/**
	 * Setup the review object.
	 *
	 * @since 1.0.0
	 *
	 * @param  int  $id  ID of the review.
	 */
	private function setup( $id ) {

		$this->id = absint( $id );

	}


	/**
	 * Get the review ID.
	 *
	 * Get the unique ID of the review (also known as the post ID).
	 *
	 * @since 1.0.4
	 *
	 * @return int Review ID.
	 */
	public function get_id() {
		return $this->id;
	}


	/**
	 * Get the review title.
	 *
	 * @since 1.0.0
	 *
	 * @return  string  The review title.
	 */
	public function get_title() {
		return apply_filters( 'reviewer\review\get_title', $this->post_title, $this );
	}


	/**
	 * Get a single rating.
	 *
	 * Get the rating value of a single part of the ratings given.
	 *
	 * @since 1.0.0
	 *
	 * @return  float|null  Rating number if available, false otherwise.
	 */
	public function get_rating() {

		if ( empty( $this->rating ) && $rating = get_post_meta( $this->id, '_rating', true ) ) {
			$this->rating = number_format( $rating, 1 );
		}

		return apply_filters( 'reviewer\review\get_rating', is_numeric( $this->rating ) ? number_format( $this->rating, 1 ) : null );

	}


	/**
	 * Get max rating.
	 *
	 * Get the maximum rating for this review.
	 *
	 * @return  float  The maximum rating for this review.
	 */
	public function get_max_rating() {

		if ( is_null( $this->max_rating ) ) {
			$this->max_rating = get_option( 'reviewer_max_rating', 5 );
		}

		return $this->max_rating;

	}


	/**
	 * Get rating step.
	 *
	 * @since 1.0.0
	 *
	 * @return  float  Interval between steps.
	 */
	public function get_rating_step() {

		if ( is_null( $this->rating_step ) ) {
			$this->rating_step = get_option( 'reviewer_rating_step', 0.5 );
		}

		return $this->rating_step;

	}


	/**
	 * Get the rating HTML.
	 *
	 * Get the formatted HTML from the review template that displays the star rating.
	 *
	 * @since 1.0.0
	 *
	 * @return  string  Formatted HTML rating.
	 */
	public function get_rating_html() {
		return \Reviewer\view_review_rating( $this );
	}


	/**
	 * Get status.
	 *
	 * Get the status of the review.
	 *
	 * @since 1.0.0
	 *
	 * @return  string  Review status.
	 */
	public function get_status() {

		if ( is_null( $this->status ) ) {
			$status       = get_post_status( $this->id );
			$status       = $status ?: '';
			$this->status = apply_filters( 'reviewer\review\get_status', $status, $this );
		}

		return $this->status;

	}


	/**
	 * Get attributes.
	 *
	 * Get a list of the review attributes. List if formatted as following:
	 * array(
	 *     array(
	 *         'name' => 'Genre',
	 *         'value' => 'Action',
	 *     ),
	 *     [...]
	 * ),
	 *
	 * @since 1.0.0
	 *
	 * @return  array  List of attributes.
	 */
	public function get_attributes() {

		if ( is_null( $this->attributes ) ) {
			$attributes = get_post_meta( $this->id, '_attributes', true );

			if ( ! $attributes ) {
				$attributes = array();
			}

			$this->attributes = apply_filters( 'reviewer\review\get_attributes', $attributes, $this );
		}

		return $this->attributes;

	}


	/**
	 * Get a single attribute.
	 *
	 * Get the value of a single attribute, or the default/fallback
	 * when it isn't set.
	 *
	 * @since 1.0.0
	 *
	 * @param   string  $attribute  Name of the attribute to get.
	 * @param   string  $default    Default/fallback value to return when the attribute is not set.
	 * @return  array               Attribute list with all its settings.
	 */
	public function get_attribute( $attribute, $default = null ) {

		$attributes = $this->get_attributes();

		if ( ! isset( $attributes[ $attribute ] ) ) {
			return $default;
		}

		return $attributes[ $attribute ];

	}


	/**
	 * Thumbnail in the proper size.
	 *
	 * Get the thumbnail in the proper size, or closest to it.
	 *
	 * @since 1.0.0
	 *
	 * @param   bool    $echo  Whether to output or return the image.
	 * @param   string  $attr  Attribute to be passed on to the thumbnail function.
	 * @return  string         Image html when $echo is set to false.
	 */
	public function get_the_thumbnail( $echo = true, $attr = '' ) {

		$archive_size = get_option( 'reviewer_archive_image_size', array( 'width' => '', 'height' => '', 'crop' => '' ) );
		$image_size   = array( $archive_size['width'], $archive_size['height'] );
		$image        = get_the_post_thumbnail( $this->id, 'review-thumbnail', $attr );

		if ( $echo ) {
			echo $image;
		} else {
			return $image;
		}

	}


	/**
	 * Thumbnail in the proper size.
	 *
	 * Get the thumbnail in the proper size, or closest to it.
	 *
	 * @since 1.0.0
	 *
	 * @param   bool    $echo  Whether to output or return the image.
	 * @param   string  $attr  Attribute to be passed on to the thumbnail function.
	 * @return  string         Image html when $echo is set to false.
	 */
	public function get_the_featured( $echo = true, $attr = '' ) {

		$archive_size = get_option( 'reviewer_review_image_size', array( 'width' => '', 'height' => '', 'crop' => '' ) );
		$image_size   = array( $archive_size['width'], $archive_size['height'] );
		$image        = get_the_post_thumbnail( $this->id, 'review-single', $attr );

		if ( $echo ) {
			echo $image;
		} else {
			return $image;
		}

	}


	/**
	 * Get review categories.
	 *
	 * Get a list with the review categories.
	 *
	 * @since 1.0.0
	 *
	 * @param   array  $args  Arguments to pass to the object terms query.
	 * @return  array         List of the review category values.
	 */
	public function get_categories( $args = array() ) {
		return wp_get_object_terms( $this->id, 'review_category', $args );
	}


	/**
	 * Get review categories list.
	 *
	 * Get a HTML formatted list with the review categories with links.
	 *
	 * @since 1.0.0
	 *
	 * @param   string  $before  HTML to prepend before the list.
	 * @param   string  $sep     What to use as a separator.
	 * @param   string  $after   HTML to append after the list.
	 * @return  array            List of the review category values.
	 */
	public function get_category_list( $before = '', $sep = ', ', $after = '' ) {
		return get_the_term_list( $this->id, 'review_category', $before, $sep, $after );
	}


	/**
	 * Get review tags.
	 *
	 * Get a list with the review tags.
	 *
	 * @since 1.0.0
	 *
	 * @param   array  $args  Arguments to pass to the object terms query.
	 * @return  array         List of the review category values.
	 */
	public function get_tags( $args = array() ) {
		return wp_get_object_terms( $this->id, 'review_tag', $args );
	}


	/**
	 * Get review tags list.
	 *
	 * Get a HTML formatted list with the review tags with links.
	 *
	 * @since 1.0.0
	 *
	 * @param   string  $before  HTML to prepend before the list.
	 * @param   string  $sep     What to use as a separator.
	 * @param   string  $after   HTML to append after the list.
	 * @return  array            List of the review category values.
	 */
	public function get_tag_list( $before = '', $sep = ', ', $after = '' ) {
		return get_the_term_list( $this->id, 'review_tag', $before, $sep, $after );
	}


	/*******************************************************
	 * Author
	 ******************************************************/


	/**
	 * Get the author object.
	 *
	 * Get the WP_User object of the author.
	 *
	 * @since 1.0.0
	 *
	 * @return  \WP_User  Author object.
	 */
	public function get_author() {
		return new \WP_User( $this->post_author );
	}


	/**
	 * Get review author name.
	 *
	 * Get the display name of the review author.
	 *
	 * @since 1.0.0
	 *
	 * @return  string  Display name of the review author.
	 */
	public function get_author_name() {
		return $this->get_author()->display_name;
	}


}
