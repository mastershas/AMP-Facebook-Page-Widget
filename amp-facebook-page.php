<?php
/**
 * @wordpress-plugin
 * Plugin Name:       AMP Facebook Page Widget
 * Plugin URI:        examframe.in/
 * Description:       A Plugin To Add Facebook Page Widget (AMP/NON-AMP Pages)
 * Version:           1.0.0
 * Author:            Shashi Kumar
 * Author URI:        examframe.in/contact-us
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       amp-facebook-page
 * Domain Path:       /languages
 */
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

define( 'text_domain' , 'amp_facebook_page');

// Declare a new widget Class

class AMP_Facebook_Page extends WP_Widget {
    // Set up widget Name
    public function __construct() {
        $args = array (
            'classname' => 'amp_facebook_page',
            'description' => 'A widget to show facebook page plugin on AMP/Non-AMP pages'
        );
        parent::__construct('amp_facebook_page', 'AMP Facebook Page', $args);
         
    }

    // Widget output 
    public function widget($args, $instance) {
            echo $args['before_widget'];
            if ( ! empty( $instance['title'] ) ) {
                echo $args['before_title'] . apply_filters( 'widget_title', $instance['title'] ) . $args['after_title'];
            }
            if($this->is_amp()) {
                // Amp Facebook Config
                ?>
                <script async custom-element="amp-facebook-page" src="https://cdn.ampproject.org/v0/amp-facebook-page-0.1.js"></script>
                <amp-facebook-page width="340" 
                    height="220"
                    layout="responsive"
                    data-hide-cover="false"
                    data-show-facepile="true"
                    data-href="<?php echo $instance['fb_url'] ;?>">
                </amp-facebook-page>
                <?php
            } else {
                // facebook Page Non Amp
                ?>
                <div id="fb-root"></div>
                <script async defer crossorigin="anonymous" src="https://connect.facebook.net/en_GB/sdk.js#xfbml=1&version=v3.2&appId=2091867207691402&autoLogAppEvents=1"></script>
                <div class="fb-page" 
                    data-href="<?php echo $instance['fb_url'] ;?>"
                    data-width="600" 
                    data-hide-cover="false"
                    data-show-facepile="true">
                </div>
                <?php
            }
            echo $args['after_widget'];
    }

    // Form output in wp-admin
    public function form($instance) {
        // Title Input Form
        $title = ! empty( $instance['title'] ) ? $instance['title'] : '';
		?>
		<p>
		<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_attr_e( 'Title:', 'text_domain' ); ?></label><br>
		<input class="title" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" placeholder="Facebook Page">
		</p>
        <?php 
        // Facebbok Page Username
        $fb_url = ! empty( $instance['fb_url'] ) ? $instance['fb_url'] : '';
		?>
		<p>
		<label for="<?php echo esc_attr( $this->get_field_id( 'fb_url' ) ); ?>"><?php esc_attr_e( 'Facebook Page URL:', 'text_domain' ); ?></label> <br>
		<input class="fb_url" id="<?php echo esc_attr( $this->get_field_id( 'fb_url' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'fb_url' ) ); ?>" type="text" value="<?php echo esc_attr( $fb_url ); ?>" placeholder="https://www.facebook.com/examframe">
		</p>
        <?php

    }

    // Processing widget form
    public function update ($new_instance, $old_instance) {
        $instance = array();
        $instance['title'] = ( ! empty( $new_instance['title'] ) ) ? sanitize_text_field( $new_instance['title'] ) : 'Facebook Page';
        $instance['fb_url'] = ( ! empty( $new_instance['fb_url'] ) ) ? sanitize_text_field( $new_instance['fb_url'] ) : '';

		return $instance;
    }

    // Function to check amp pages
    public function is_amp() {
        return function_exists( 'is_amp_endpoint' ) && is_amp_endpoint();
    }

}

// Widget Initialize
add_action('widgets_init', function(){
    register_widget('AMP_Facebook_Page');
});
