<?php

function theme_styles() {

  wp_enqueue_style( 'canvas', get_template_directory_uri() . '/css/style.css');
  wp_enqueue_style( 'theme', get_template_directory_uri() . '/style.css');

}
add_action( 'wp_enqueue_scripts', 'theme_styles' );

function theme_js() {

  wp_enqueue_script( 'canvas_jquery', get_template_directory_uri() . '/js/jquery.js', array('jquery'), '', true );
  wp_enqueue_script( 'canvas_plugins', get_template_directory_uri() . '/js/plugins.js', '', '', true );
  wp_enqueue_script( 'canvas_functions', get_template_directory_uri() . '/js/functions.js', '', '', true );
}
add_action( 'wp_enqueue_scripts', 'theme_js' );

//Navigation Menus

add_filter( 'show_admin_bar', '__return_false' );

add_theme_support( 'menus' );

function register_theme_menus() {
  register_nav_menus(
    array(
      'primary'  => __( 'Primary' )
    )
  );

}
add_action( 'init', 'register_theme_menus' );

// class Microdot_Walker_Nav_Menu extends Walker_Nav_Menu {
//     public function start_lvl( &$output, $depth = 0, $args = array() ) {
//         $output .= '<div class="mega-menu-content style-2 clearfix"><ul class="mega-menu-column col-md-6">';
//     }
//
//     public function end_lvl( &$output, $depth = 0, $args = array() ) {
//         $output .= '</ul></div>';
//     }
//
//     public function start_el( &$output, $item, $depth = 0, $args = array(), $id = 0 ) {
//         $classes = array();
//         if( !empty( $item->classes ) ) {
//             $classes = (array) $item->classes;
//         }
//
//         $active_class = '';
//         if( in_array('current-menu-item', $classes) ) {
//             $active_class = ' class="active"';
//         } else if( in_array('current-menu-parent', $classes) ) {
//             $active_class = ' class="active-parent"';
//         } else if( in_array('current-menu-ancestor', $classes) ) {
//             $active_class = ' class="active-ancestor"';
//         }
//
//         $url = '';
//         if( !empty( $item->url ) ) {
//             $url = $item->url;
//         }
//
//         $output .= '<li class="mega-menu" '. $active_class . '><a href="' . $url . '"class="sf-with-ul">' . $item->title . '</a>';
//     }
//
//     public function end_el( &$output, $item, $depth = 0, $args = array() ) {
//         $output .= '</li>';
//     }
// }

?>
