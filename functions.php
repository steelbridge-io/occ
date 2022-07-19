<?php
/**
 * Betheme Child Theme
 *
 * @package Betheme Child Theme
 * @author Muffin group
 * @link https://muffingroup.com
 */

/**
 * Child Theme constants
 * You can change below constants
 */

// white label

define('WHITE_LABEL', false);

/**
 * Disable JetPack Upsells
 */
 
add_filter( 'jetpack_just_in_time_msgs', '_return_false' );

/**
 * Enqueue Styles
 */

function mfnch_enqueue_styles()
{
	// enqueue the parent stylesheet
	// however we do not need this if it is empty
	// wp_enqueue_style('parent-style', get_template_directory_uri() .'/style.css');

	// enqueue the parent RTL stylesheet

	wp_enqueue_style('custom', get_stylesheet_directory_uri() . '/assets/scss/custom.css');

	if (is_rtl()) {
		wp_enqueue_style('mfn-rtl', get_template_directory_uri() . '/rtl.css');
	}

	// enqueue bootstrap css

	wp_enqueue_style('bootstrap', 'https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css', array(), '5.2.0', 'all');

	// enqueue the child stylesheet

	wp_dequeue_style('style');
	wp_enqueue_style('style', get_stylesheet_directory_uri() .'/style.css');
}

	// enqueue scripts

	wp_enqueue_script('bootstrap-js', 'https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js', array(), '5.2.0', true );

add_action('wp_enqueue_scripts', 'mfnch_enqueue_styles', 101);

/**
 * Load Textdomain
 */

function mfnch_textdomain()
{
	load_child_theme_textdomain('betheme', get_stylesheet_directory() . '/languages');
	load_child_theme_textdomain('mfn-opts', get_stylesheet_directory() . '/languages');
}
add_action('after_setup_theme', 'mfnch_textdomain');

/*
 * Current User link shortcode
 * Use like so: [current_user_link] This is displayed only if current user is logged in.
 */

add_shortcode( 'current_user_link', 'wppbc_current_user_link' );
function wppbc_current_user_link( $atts, $content ) {
	global $current_user; wp_get_current_user();
	if ( is_user_logged_in() ) {
		//$id = get_current_user_id();
		$user = $current_user->user_login;
		// make sure to change the URL to represent your setup.
		return "<a href='https://opencurtains.parsonshosting.dev/members/{$user}'>View Your User Page Here</a>";
	}

	return ;
}

/**
 * Get a link to send PM to the given User.
 *
 * @param int $user_id user id.
 *
 * @return string
 */
function buddydev_get_send_private_message_to_user_url( $user_id ) {
	return wp_nonce_url( bp_loggedin_user_domain() . bp_get_messages_slug() . '/compose/?r=' . bp_core_get_username( $user_id ) );
}

/**
 * Shortcode [bp-pm-button username=optional_some_user_name]
 *
 * @param array $atts shortcode attributes.
 * @param string $content content.
 *
 * @return string
 */
function buddydev_private_message_button_shortcode( $atts, $content = '' ) {
	// User is not logged in.
	if ( ! is_user_logged_in() ) {
		return '';
	}

	$atts = shortcode_atts( array(
	 'user_id'   => '',
	 'username'  => '',
	 'label'     => 'Send Private Message',
	), $atts );

	$user_id = absint( $atts['user_id'] );
	$user_login = $atts['username'];

	// if the username is given, override the user id.
	if ( $user_login ) {
		$user = get_user_by( 'login', $user_login );
		if ( ! $user ) {
			return '';
		}
		$user_id = $user->ID;
	}

	if ( ! $user_id ) {
		if ( ! in_the_loop() ) {
			return '';
		}

		$user_id = get_the_author_meta('ID' );
	}
	// do not show the PM button for the user, if it is aimed at them.
	if ( bp_loggedin_user_id() === $user_id ) {
		return '';
	}

	// if we are here, generate the button.
	$button = sprintf('<a href="%1$s">%2$s</a>', buddydev_get_send_private_message_to_user_url( $user_id ), $atts['label'] );

	return $button . $content;
}

add_shortcode( 'bp-pm-button', 'buddydev_private_message_button_shortcode' );
