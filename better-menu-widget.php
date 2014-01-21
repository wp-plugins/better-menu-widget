<?php
/*
 * Plugin Name:  Better Menu Widget
 * Plugin URI:  http://www.chaostoclarity.com/wordpress-plugins/better-menu-widget/
 * Description:  Better Menu Widget makes it easy to customize your menu widgets by adding css styles and a heading link.
 * Version:      1.2
 * Author:       Tracey Holinka
 * Author URI:   http://TraceyHolinka.com
 * Author Email: tracey@chaostoclarity.com
 * License:      GPLv2
 * 
 *  Copyright 2010-2014 Tracey Holinka (tracey@chaostoclarity.com)
 * 
 *  This program is free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License, version 2, as 
 *  published by the Free Software Foundation.
 *  
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *  
 *  You should have received a copy of the GNU General Public License
 *  along with this program; if not, write to the Free Software
 *  Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 *  
*/
 
if (!class_exists("Better_Menu_Widget")) {

add_action('widgets_init', 'load_better_menu_widget');

	function load_better_menu_widget() {
		register_widget( 'Better_Menu_Widget' );
	}

	class Better_Menu_Widget extends WP_Widget {

		function __construct() {

		load_plugin_textdomain( 'better-menu-widget', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
		
			$widget_ops = array( 'classname' => 'better-menu-widget', 'description' => __('Add one of your custom menus as a widget.', 'better-menu-widget') );
			$control_ops = array( 'width' => 300, 'height' => 350, 'id_base' => 'better-menu-widget' );
			$this->WP_Widget( 'better-menu-widget', __('Better Menu', 'better-menu-widget'), $widget_ops, $control_ops );	
		}

		function widget($args, $instance) {
			
			$nav_menu = wp_get_nav_menu_object( $instance['nav_menu'] ); /* Get menu */
	
			if ( !$nav_menu )
				return;
	
			$instance['title'] = apply_filters('widget_title', $instance['title'], $instance, $this->id_base);
	
			echo $args['before_widget'];
	
			if ( !empty($instance['title']) && !empty($instance['title_url']) )
				echo $args['before_title'] . '<a href="' . $instance['title_url'] . '">' . $instance['title'] . '</a>' . $args['after_title'];
	
			if ( !empty($instance['title']) && empty($instance['title_url']) )
				echo $args['before_title'] . $instance['title'] . $args['after_title'];
	
			wp_nav_menu( array( 'fallback_cb' => '', 'menu' => $nav_menu, 'menu_class' => $instance['menu_class'], 'container' => false ) );
	
			echo $args['after_widget'];
		}

		// widget admin
			
		function update( $new_instance, $old_instance ) {
			$instance['title'] = strip_tags( stripslashes($new_instance['title']) );
			$instance['nav_menu'] = (int) $new_instance['nav_menu'];
			$instance['title_url'] = $new_instance['title_url'];
			$instance['menu_class'] = $new_instance['menu_class'];
			return $instance;
		}
	
		function form( $instance ) {
			$title = isset( $instance['title'] ) ? $instance['title'] : '';
			$nav_menu = isset( $instance['nav_menu'] ) ? $instance['nav_menu'] : '';
			$title_url = isset( $instance['title_url'] ) ? $instance['title_url'] : '';
			$menu_class = isset( $instance['menu_class'] ) ? $instance['menu_class'] : 'sub-menu';
	
			// Get menus
			$menus = get_terms( 'nav_menu', array( 'hide_empty' => false ) );
	
			// If no menus exists, direct the user to create some.
			if ( !$menus ) {
				echo '<p>'. sprintf( __('No menus have been created yet. <a href="%s">Create some</a>.', 'better-menu-widget'), admin_url('nav-menus.php') ) .'</p>';
				return;
			}
			?>
			<p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:', 'better-menu-widget') ?></label><input type="text" class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" value="<?php echo $title; ?>" /></p>
			<p><label for="<?php echo $this->get_field_id('title_url'); ?>"><?php _e('Title URL:', 'better-menu-widget') ?></label><input type="text" class="widefat" id="<?php echo $this->get_field_id('title_url'); ?>" name="<?php echo $this->get_field_name('title_url'); ?>" value="<?php echo $title_url; ?>" /></p>
			<p><label for="<?php echo $this->get_field_id('nav_menu'); ?>"><?php _e('Select Menu:', 'better-menu-widget'); ?></label>
				<select id="<?php echo $this->get_field_id('nav_menu'); ?>" name="<?php echo $this->get_field_name('nav_menu'); ?>">
			<?php 
				foreach ( $menus as $menu ) {
					$selected = $nav_menu == $menu->term_id ? ' selected="selected"' : '';
					echo '<option'. $selected .' value="'. $menu->term_id .'">'. $menu->name .'</option>';
				}
			?>
				</select></p>
			<p><label for="<?php echo $this->get_field_id('menu_class'); ?>"><?php _e('Menu Class:', 'better-menu-widget') ?></label><input type="text" class="widefat" id="<?php echo $this->get_field_id('menu_class'); ?>" name="<?php echo $this->get_field_name('menu_class'); ?>" value="<?php echo $menu_class; ?>" />
				<small><?php _e( 'CSS class to use for the ul menu element.', 'better-menu-widget' ); ?></small></p>
				<p class="credits"><small><?php _e('Developed by', 'better-menu-widget'); ?> <a href="http://traceyholinka.com/" rel="nofollow">Tracey Holinka</a></small></p>
			<?php
		}
	}// end class
	
} // end if
?>