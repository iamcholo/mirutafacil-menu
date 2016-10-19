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

wp_enqueue_style( 'font-awesome-four-css', plugins_url( "css/font-awesome.min.css", __FILE__ ), array() );

wp_register_style( 'mirutafacil-menu', plugins_url( "css/menu.css", __FILE__ ), array() );

/**
 * Menu - Shortcode
 *
 * To be use:
 *      [menu name="-your menu name-" class="-your color-"]
 *
 * @link http://stephanieleary.com/2010/07/call-a-navigation-menu-using-a-shortcode/#comment-328537
 */
function print_menu_shortcode( $atts, $content = null ) {

	wp_enqueue_style( 'font-awesome-four-css' );
	wp_enqueue_style( 'mirutafacil-menu' );

	extract( shortcode_atts( array( 'name' => null, 'class' => null ), $atts ) );

	// $container_class = 'mrfcl' . ' ' . $class;
	$container_class = isset( $class ) ? "mrfcl {$class}" : "mrfcl";

	return wp_nav_menu( array(
		'menu'            => $name,
		// 'menu_class'      => $class,
		'container_class' => $container_class,
		'echo'            => false
	) );
}

/**
 * Inspect nav menus
 */
function mirutafacil_nav_menu_css_class( $classes ) {
	if ( is_array( $classes ) ) {
		$tmp_classes = preg_grep( '/^(fa)(-\S+)?$/i', $classes );
		if ( ! empty( $tmp_classes ) ) {
			$classes = array_values( array_diff( $classes, $tmp_classes ) );
		}
	}

	return $classes;
}


/**
 * Filter for modifying Awesome Menu items
 */
function mirutafacil_child_menu_replace_item( $item_output, $classes ) {
	$spacer = ' ';

	if ( ! in_array( 'fa', $classes ) ) {
		array_unshift( $classes, 'fa' );
	}

	$before = true;
	if ( in_array( 'fa-after', $classes ) ) {
		$classes = array_values( array_diff( $classes, array( 'fa-after' ) ) );
		$before  = false;
	}

	$icon = '<i class="' . implode( ' ', $classes ) . '"></i>';

	preg_match( '/(<a.+>)(.+)(<\/a>)/i', $item_output, $matches );
	if ( 4 === count( $matches ) ) {
		$item_output = $matches[1];
		if ( $before ) {
			$item_output .= $icon . '<span class="fontawesome-text">' . $spacer . $matches[2] . '</span>';
		} else {
			$item_output .= '<span class="fontawesome-text">' . $matches[2] . $spacer . '</span>' . $icon;
		}
		$item_output .= $matches[3];
	}

	return $item_output;
}


/*
 * Render FontAwesome menus items
 *
 * @link https://developer.wordpress.org/reference/hooks/walker_nav_menu_start_el/
 */
function mirutafacil_walker_nav_menu_start_el( $item_output, $item, $depth, $args ) {
	if ( is_array( $item->classes ) ) {
		$classes = preg_grep( '/^(fa)(-\S+)?$/i', $item->classes );
		if ( ! empty( $classes ) ) {
			$item_output = mirutafacil_child_menu_replace_item( $item_output, $classes );
		}
	}

	return $item_output;
}


add_shortcode( 'menu', 'print_menu_shortcode' );

add_filter( 'nav_menu_css_class', 'mirutafacil_nav_menu_css_class' );

add_filter( 'walker_nav_menu_start_el', 'mirutafacil_walker_nav_menu_start_el', 10, 4 );


