<?php
/**
 *
 * Plugin Name: Mi Ruta Fácil - Menu
 * Description: Plugin personalizado para mirutafacil.com
 * Version: 1.0
 * Author: mirutafacil
 * Author URI: http://mirutafacil.com/
 * License: Private
 * Text Domain: mirutafacil
 */

if ( ! defined( 'ABSPATH' ) ) {
  die();
}

define( 'MIRUTAFACIL_PATH', plugin_dir_path( __FILE__ ) );

function print_menu_shortcode($atts, $content = null) {

  wp_enqueue_style( 'mirutafacil-menu', plugins_url( "css/menu.css", __FILE__ ), array() );

  extract(shortcode_atts(array( 'name' => null, 'class' => null ), $atts));

  return wp_nav_menu( array( 'menu' => $name, 'menu_class' => $class, 'container_class' => 'mrfcl', 'echo' => false ) );
}

add_shortcode('menu', 'print_menu_shortcode');
