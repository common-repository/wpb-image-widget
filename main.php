<?php 
/**
Plugin Name: WPB Image Widget
Plugin URI: http://wpbean.com/wpb-image-widget
Description: Showing responsive image in widget area.
Author: wpbean
Version: 1.0.1
Author URI: https://wpbean.com/contact-us/
text-domain: wpb_iw
*/


if ( ! defined( 'ABSPATH' ) ) exit;


/**
 * Define TextDomain
 */

if ( !defined( 'WPB_IW_TEXTDOMAIN' ) ) {
	define( 'WPB_IW_TEXTDOMAIN','wpb_fp' );
}


/**
 * Internationalization
 */

if( !function_exists('wpb_iw_internationalization') ):
	function wpb_iw_internationalization() {
		load_plugin_textdomain( WPB_IW_TEXTDOMAIN, false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
	}
endif;
add_action( 'init', 'wpb_iw_internationalization' );


/**
 * Requred files
 */

require_once dirname( __FILE__ ) . '/inc/wpb_iw_shortcode.php';
require_once dirname( __FILE__ ) . '/inc/wpb_iw_functions.php';
require_once dirname( __FILE__ ) . '/admin/wpb_iw_widget.php';