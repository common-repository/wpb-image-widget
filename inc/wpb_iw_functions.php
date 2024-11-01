<?php

/*
    WPB Image Widget
    By WPBean
*/

if ( ! defined( 'ABSPATH' ) ) exit;


/**
 * Adding Styles
 */

if( !function_exists('wpb_iw_adding_style') ):
	function wpb_iw_adding_style() {
		wp_enqueue_style('wpb_iw_main', plugins_url('../assets/css/main.css', __FILE__),'','1.0', false);
	}
	add_action( 'wp_enqueue_scripts', 'wpb_iw_adding_style',11 );
endif;