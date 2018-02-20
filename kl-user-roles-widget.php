<?php
/*
Plugin Name: KL User Roles Widget
Plugin URI: https://github.com/educate-sysadmin/kl-user-roles-widget
Description: Wordpress plugin to display widget with user role(s)
Version: 0.2
Author: b.cunningham@ucl.ac.uk
Author URI: https://educate.london
License: GPL2
*/
class KL_User_Roles_Widget extends WP_Widget {
/* thanks http://www.wpexplorer.com/create-widget-plugin-wordpress/ */

	// Main constructor
	public function __construct() {
            parent::__construct(
		        'kl_user_roles_widget',
		        __( 'KL User Roles Widget', 'text_domain' ),
		        array(
			        'customize_selective_refresh' => true,
		        )
	        );
	}

	// The widget form (for the backend )
	public function form( $instance ) {	
            // Set widget defaults
	        $defaults = array(
		        'title'    => 'User roles',
	        );
            // Parse current settings with defaults
	        extract( wp_parse_args( ( array ) $instance, $defaults ) ); ?>

            <?php // Widget Title ?>
	        <p>
		        <label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php _e( 'Widget Title', 'text_domain' ); ?></label>
		        <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
	        </p>
<?php }

	// Update widget settings
	public function update( $new_instance, $old_instance ) {
        try {
	        $instance = $old_instance;
	        $instance['title'] = isset( $new_instance['title'] ) ? wp_strip_all_tags( $new_instance['title'] ) : '';
	        return $instance;
        } catch (Exception $e) {
        }
	}

	// Display the widget
	public function widget( $args, $instance ) {
        try {
            extract( $args );

	        // Check the widget options
	        $title = isset( $instance['title'] ) ? apply_filters( 'widget_title', $instance['title'] ) : '';

	        // WordPress core before_widget hook (always include )
	        echo $before_widget;

            // Display the widget
            echo '<div class="widget-text wp_widget_plugin_box">'; 

		        // Display widget title if defined
		        if ( $title ) {
			        echo $before_title . $title . $after_title;
		        }

		        echo '<div id = "kl_user_roles_widget" class="kl_user_roles_widget">';
		        echo '<ul class="class="kl_user_roles_widget_list">';
		        if (!is_user_logged_in()) {
                    echo '<li class="kl_user_roles_widget_item">'.'Visitor'.'</li>';
		        } else {
                    $user = wp_get_current_user();
                    foreach ($user->roles as $role) {
                        echo '<li class="kl_user_roles_widget_item">'.$role.'</li>';
                    }
                }
		        echo '</ul>';
		        echo '</div>';

	        echo '</div>';

	        // WordPress core after_widget hook (always include )
	        echo $after_widget;
        } catch (Exception $e) {            
        }
	}
}

// Register the widget
function register_kl_user_roles_widget() {
	register_widget( 'KL_User_Roles_Widget');
}

add_action( 'widgets_init', 'register_kl_user_roles_widget' );
