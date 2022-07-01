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
