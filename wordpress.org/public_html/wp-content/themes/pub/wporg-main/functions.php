<?php
/**
 * WordPress.org functions and definitions.
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package WordPressdotorg\Theme\Main
 */

namespace WordPressdotorg\MainTheme;

/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 */
function setup() {
	// This theme uses wp_nav_menu() in one location.
	register_nav_menus( array(
		'rosetta_main' => esc_html__( 'Rosetta', 'wporg' ),
	) );
}
add_action( 'after_setup_theme', __NAMESPACE__ . '\setup' );

/**
 * Registers theme-specific widgets.
 */
function widgets() {
	include_once get_stylesheet_directory() . '/widgets/class-wporg-widget-download.php';

	register_widget( __NAMESPACE__ . '\WPORG_Widget_Download' );
	register_widget( 'WP_Widget_Links' );

	add_filter( 'widget_links_args', function( $args ) {
		$args['categorize'] = 0;
		$args['title_li']   = __( 'Resources', 'wporg' );

		return $args;
	} );

	add_filter( 'widget_categories_args', function( $args ) {
		$args['number']  = 10;
		$args['orderby'] = 'count';
		$args['order']   = 'DESC';

		return $args;
	} );

	add_filter( 'widget_archives_args', function( $args ) {
		$args['limit'] = 12;

		return $args;
	} );

	register_sidebar( [
		'id'            => 'sidebar-1',
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget'  => '</div>',
		'before_title'  => '<h4>',
		'after_title'   => '</h4>',
	] );
}
add_action( 'widgets_init', __NAMESPACE__ . '\widgets' );

/**
 * Extend the default WordPress body classes.
 *
 * Adds classes to make it easier to target specific pages.
 *
 * @param array $classes Body classes.
 * @return array
 */
function body_class( $classes ) {
	if ( is_page() ) {
		$classes[] = 'page-' . get_query_var( 'pagename' );
	}

	return array_unique( $classes );
}
add_filter( 'body_class', __NAMESPACE__ . '\body_class' );

/**
 * Custom template tags.
 */
require_once get_stylesheet_directory() . '/inc/template-tags.php';
