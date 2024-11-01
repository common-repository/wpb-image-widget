<?php

/*
    WPB Image Widget
    By WPBean
*/

if ( ! defined( 'ABSPATH' ) ) exit;


/**
 * Adding The Image Widget
 */


if( !class_exists('wpb_iw_Widget') ):
	class wpb_iw_Widget extends WP_Widget {

		/**
		 * Register widget with WordPress.
		 */

		function __construct() {
			parent::__construct(
				'wpb_iw_widget',
				__( 'WPB Image Widget',  WPB_IW_TEXTDOMAIN ),
				array( 'description' => __( 'Showing image in widget.',  WPB_IW_TEXTDOMAIN ), )
			);

			add_action('admin_enqueue_scripts', array($this, 'upload_scripts'));
		}

		/**
		 * Front-end display of widget.
		 *
		 * @see WP_Widget::widget()
		 *
		 * @param array $args     Widget arguments.
		 * @param array $instance Saved values from database.
		 */
		public function widget( $args, $instance ) {

			extract( $args );
			extract( $instance );

			echo $before_widget;

			if ( ! empty( $title ) ) {
				echo $before_title . apply_filters( 'widget_title', $title ). $after_title;
			}


			echo do_shortcode( '[wpb-image-widget x_class="'.$x_class.'" src="'.$src.'" alt="'.$alt.'" size="'.$size.'" align="'.$align.'" linking="'.$linking.'" custom_link="'.$custom_link.'" window="'.$window.'" theme="'.$theme.'" x_text="'.$x_text.'"]' );

			echo $after_widget;
		}

		/**
		 * Back-end widget form.
		 *
		 * @see WP_Widget::form()
		 *
		 * @param array $instance Previously saved values from database.
		 */
		public function form( $instance ) {
			extract( $instance );

			$x_class = ( !isset( $x_class ) ? '' : $x_class );
			$src = ( !isset( $src ) ? '' : $src );
			$alt = ( !isset( $alt ) ? '' : $alt );
			$size = ( !isset( $size ) ? 'medium' : $size );
			$align = ( !isset( $align ) ? 'left' : $align );
			$linking = ( !isset( $linking ) ? 'no' : $linking );
			$custom_link = ( !isset( $custom_link ) ? '' : $custom_link );
			$window = ( !isset( $window ) ? 'same' : $window );
			$theme = ( !isset( $theme ) ? 'hover' : $theme );
			$x_text = ( !isset( $x_text ) ? '' : $x_text );

			?>

			<p>
				<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:', WPB_IW_TEXTDOMAIN ); ?></label> 
				<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php if( isset( $title ) ) echo esc_attr( $title ); ?>">
			</p>

			<p>
            	<label style="display: block;" for="<?php echo $this->get_field_name( 'src' ); ?>"><?php _e( 'Image:' ); ?></label>
	            <input style="display: none;" name="<?php echo $this->get_field_name( 'src' ); ?>" id="<?php echo $this->get_field_id( 'src' ); ?>" class="widefat wpb_iw_uploaded_image" type="number" size="36"  value="<?php if( isset( $src ) ) echo esc_attr( $src ); ?>" />
	            <?php 
		            if( isset( $src ) ){
		            	$show_img = wp_get_attachment_image_src( $src, 'medium');
						$show_img = $show_img[0];
						?>
						<img class="wpb_iw_show_image" id="<?php echo $this->get_field_id( 'src' ); ?>_wpb_iw_show_image" src="<?php echo $show_img; ?>">
						<?php
		            } 
	            ?>
	            <input class="wpb_iw_upload_image button button-primary" onclick="WpbImageWidget.processing( '<?php echo $this->id; ?>', '<?php echo $this->get_field_id( 'src' ); ?>' ); return false;" type="button" value="Upload Image" />
	        </p>

			<p>
				<label for="<?php echo $this->get_field_id( 'alt' ); ?>"><?php _e( 'Image alt text:', WPB_IW_TEXTDOMAIN ); ?></label> 
				<input class="widefat" id="<?php echo $this->get_field_id( 'alt' ); ?>" name="<?php echo $this->get_field_name( 'alt' ); ?>" type="text" value="<?php if( isset( $alt ) ) echo esc_attr( $alt ); ?>">
			</p>

			<p>
				<label for="<?php echo $this->get_field_id( 'x_text' ); ?>"><?php _e( 'Text bellow the image:', WPB_IW_TEXTDOMAIN ); ?></label> 
				<input class="widefat" id="<?php echo $this->get_field_id( 'x_text' ); ?>" name="<?php echo $this->get_field_name( 'x_text' ); ?>" type="text" value="<?php if( isset( $x_text ) ) echo esc_attr( $x_text ); ?>">
			</p>

			<p>
				<label for="<?php echo $this->get_field_id( 'size' ); ?>"><?php _e( 'Size:', WPB_IW_TEXTDOMAIN ); ?></label> 
				<select class="widefat" id="<?php echo $this->get_field_id( 'size' ); ?>" name="<?php echo $this->get_field_name( 'size' ); ?>">
					<?php echo $this->wpb_iw_image_sizes_in_select($size); ?>
				</select>
			</p>

			<p>
				<label for="<?php echo $this->get_field_id( 'align' ); ?>"><?php _e( 'Align:', WPB_IW_TEXTDOMAIN ); ?></label> 
				<select class="widefat" id="<?php echo $this->get_field_id( 'align' ); ?>" name="<?php echo $this->get_field_name( 'align' ); ?>">
					<option value="left" <?php if( $align && $align == 'left' ) echo 'selected';?>><?php _e( 'Left', WPB_IW_TEXTDOMAIN ); ?></option>
					<option value="right" <?php if( $align && $align == 'right' ) echo 'selected';?>><?php _e( 'Right', WPB_IW_TEXTDOMAIN ); ?></option>
					<option value="center" <?php if( $align && $align == 'center' ) echo 'selected';?>><?php _e( 'Center', WPB_IW_TEXTDOMAIN ); ?></option>
				</select>
			</p>

			<p class="wpb_iw_linking_type_<?php echo $this->get_field_id( 'linking' ); ?>">
				<label for="<?php echo $this->get_field_id( 'linking' ); ?>"><?php _e( 'Linking:', WPB_IW_TEXTDOMAIN ); ?></label> 
				<select class="widefat" id="<?php echo $this->get_field_id( 'linking' ); ?>" name="<?php echo $this->get_field_name( 'linking' ); ?>">
					<option value="no" <?php if( $linking && $linking == 'no' ) echo 'selected';?>><?php _e( 'No', WPB_IW_TEXTDOMAIN ); ?></option>
					<option value="image" <?php if( $linking && $linking == 'image' ) echo 'selected';?>><?php _e( 'Large Image', WPB_IW_TEXTDOMAIN ); ?></option>
					<option value="custom" <?php if( $linking && $linking == 'custom' ) echo 'selected';?>><?php _e( 'Custom Link', WPB_IW_TEXTDOMAIN ); ?></option>
				</select>
			</p>

			<p class="wpb_iw_custom_link_<?php echo $this->get_field_id( 'custom_link' ); ?>">
				<label for="<?php echo $this->get_field_id( 'custom_link' ); ?>"><?php _e( 'Custom Link:', WPB_IW_TEXTDOMAIN ); ?></label> 
				<input class="widefat" id="<?php echo $this->get_field_id( 'custom_link' ); ?>" name="<?php echo $this->get_field_name( 'custom_link' ); ?>" type="text" value="<?php if( isset( $custom_link ) ) echo esc_attr( $custom_link ); ?>">
			</p>

			<p>
				<label for="<?php echo $this->get_field_id( 'window' ); ?>"><?php _e( 'Link Window:', WPB_IW_TEXTDOMAIN ); ?></label> 
				<select class="widefat" id="<?php echo $this->get_field_id( 'window' ); ?>" name="<?php echo $this->get_field_name( 'window' ); ?>">
					<option value="same" <?php if( $window && $window == 'same' ) echo 'selected';?>><?php _e( 'Same Window', WPB_IW_TEXTDOMAIN ); ?></option>
					<option value="new" <?php if( $window && $window == 'new' ) echo 'selected';?>><?php _e( 'New Window', WPB_IW_TEXTDOMAIN ); ?></option>
				</select>
			</p>

			<p>
				<label for="<?php echo $this->get_field_id( 'theme' ); ?>"><?php _e( 'Theme:', WPB_IW_TEXTDOMAIN ); ?></label> 
				<select class="widefat" id="<?php echo $this->get_field_id( 'theme' ); ?>" name="<?php echo $this->get_field_name( 'theme' ); ?>">
					<option value="hover" <?php if( $theme && $theme == 'hover' ) echo 'selected';?>><?php _e( 'Hover', WPB_IW_TEXTDOMAIN ); ?></option>
					<option value="default" <?php if( $theme && $theme == 'default' ) echo 'selected';?>><?php _e( 'Default', WPB_IW_TEXTDOMAIN ); ?></option>
				</select>
			</p>

			<p>
				<label for="<?php echo $this->get_field_id( 'x_class' ); ?>"><?php _e( 'Extra Class:', WPB_IW_TEXTDOMAIN ); ?></label> 
				<input class="widefat" id="<?php echo $this->get_field_id( 'x_class' ); ?>" name="<?php echo $this->get_field_name( 'x_class' ); ?>" type="text" value="<?php if( isset( $x_class ) ) echo esc_attr( $x_class ); ?>">
			</p>

			<?php 
		}

		/**
		 * Sanitize widget form values as they are saved.
		 *
		 * @see WP_Widget::update()
		 *
		 * @param array $new_instance Values just sent to be saved.
		 * @param array $old_instance Previously saved values from database.
		 *
		 * @return array Updated safe values to be saved.
		 */
		public function update( $new_instance, $old_instance ) {
			$instance = array();
			$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
			$instance['x_class'] = ( ! empty( $new_instance['x_class'] ) ) ? strip_tags( $new_instance['x_class'] ) : '';
			$instance['src'] = ( ! empty( $new_instance['src'] ) ) ? strip_tags( $new_instance['src'] ) : '';
			$instance['alt'] = ( ! empty( $new_instance['alt'] ) ) ? strip_tags( $new_instance['alt'] ) : '';
			$instance['size'] = ( ! empty( $new_instance['size'] ) ) ? strip_tags( $new_instance['size'] ) : '';
			$instance['align'] = ( ! empty( $new_instance['align'] ) ) ? strip_tags( $new_instance['align'] ) : '';
			$instance['linking'] = ( ! empty( $new_instance['linking'] ) ) ? strip_tags( $new_instance['linking'] ) : '';
			$instance['custom_link'] = ( ! empty( $new_instance['custom_link'] ) ) ? strip_tags( $new_instance['custom_link'] ) : '';
			$instance['window'] = ( ! empty( $new_instance['window'] ) ) ? strip_tags( $new_instance['window'] ) : '';
			$instance['theme'] = ( ! empty( $new_instance['theme'] ) ) ? strip_tags( $new_instance['theme'] ) : '';
			$instance['x_text'] = ( ! empty( $new_instance['x_text'] ) ) ? strip_tags( $new_instance['x_text'] ) : '';

			return $instance;
		}


		/**
		 * Upload the Javascripts for the media uploader
		 */

	    public function upload_scripts() {
	    	wp_enqueue_script('jquery');
	    	wp_enqueue_media();
	        wp_enqueue_script('upload_media_widget', plugin_dir_url(__FILE__) . 'assets/js/upload-media.js', array('jquery'));
	        wp_enqueue_style('wpb_iw_admin', plugins_url('assets/css/main.css', __FILE__),'','1.0', false);
	    }


	    /**
	     * Getting all defined image sizes and preparing for select box
	     */
	    
	    protected function wpb_iw_image_sizes_in_select( $saved_size ){
	    	$all_sizes = get_intermediate_image_sizes();
	    	$output = '';
	    	foreach ( $all_sizes as $size ) {
	    		$output .= '<option value="'.$size.'" '. ( $saved_size === $size ? 'selected' : '' ) .'>'.$size.'</option>';
	    	}
	    	return $output;
	    }


	} // class wpb_iw_Widget
endif;


/**
 * Registering the Widget
 */

add_action( 'widgets_init', 'register_wpb_iw_widget' );

if( !function_exists('register_wpb_iw_widget') ):
	function register_wpb_iw_widget() {
	    register_widget( 'wpb_iw_Widget' );
	}
endif;