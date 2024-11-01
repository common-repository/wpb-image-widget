<?php

/*
    WPB Image Widget
    By WPBean
*/

if ( ! defined( 'ABSPATH' ) ) exit;



/**
 * Image Widget Shortcode
 */

add_shortcode( 'wpb-image-widget', 'wpb_iw_shortcode' );

if( !function_exists('wpb_iw_shortcode') ):
	function wpb_iw_shortcode($atts){
		extract(shortcode_atts(array(
			'x_class' 		=> '',
			'src' 	  		=> '',
			'alt' 	  		=> '',
			'x_text' 		=> '',
			'size' 	  		=> 'medium',
			'align'   		=> 'left',
			'linking'   	=> 'no',
			'window'   		=> 'same',
			'custom_link'   => '',
			'theme'   		=> 'hover',
			'width'   		=> '',
			'height'  		=> '',
		), $atts));

		if( $linking === 'image' && $src != '' ){
			$link_href = wp_get_attachment_url( $src );
		}elseif( $linking === 'custom' && $custom_link != ''){
			$link_href = $custom_link;
		}else{
			$link_href = '';
		}

		if( $src == '' ){
			$src = 'http://placehold.it/300?text=Placeholder';
		}else {
			$src = wp_get_attachment_image_src( $src, $size);
			$src = $src[0];
		}

		if( $alt ){
			$alt = ' alt="'.$alt.'"';
		}

		if( $window === 'new' ){
			$window = ' target="_blank"';
		}else{
			$window = '';
		}

		ob_start();
		?>

		<div class="wpb-iw-area wpb-iw-<?php echo $theme; ?><?php echo ( $x_class ? ' '.$x_class : '' )?> wpb-iw-align-<?php echo ( $align ? ''.$align : '' )?>">
			<figure>
				<?php if( $link_href != '' ):?>
					<a class="wpb_iw_linking" href="<?php echo $link_href; ?>"<?php echo $window ?>>
				<?php endif; ?>

					<img src="<?php echo $src; ?>"<?php echo $alt; ?>>

				<?php if( $link_href != '' ):?>
					</a>
				<?php endif; ?>

				<?php if( $x_text != '' ): ?>
					<figcaption>
						<h3><?php echo $x_text; ?></h3>
					</figcaption>
				<?php endif; ?>
			</figure>
		</div>

		<?php
		return ob_get_clean();
	}
endif;