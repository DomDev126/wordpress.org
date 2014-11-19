<?php

global $pagetitle;
$pagetitle = get_bloginfo( 'name', 'display' ) . wp_title( '&laquo;', false, 'left' );

$GLOBALS['wporg_global_header_options'] = array(
	'rosetta_site' => get_bloginfo( 'name', 'display' ),
	'search' => false,
	'menu' => wp_nav_menu( array( 'theme_location' => 'rosetta_main', 'container' => false, 'echo' => false, 'menu_id' => 'wporg-header-menu' ) ),
);

wp_enqueue_style( 'rosetta', get_stylesheet_uri(), array(), 16, 'screen' ); 

if ( is_rtl() ) {
	wp_enqueue_style( 'rosetta-rtl', get_locale_stylesheet_uri(), array( 'rosetta' ), 2, 'screen' );
}

if ( is_locale_css() ) {
	wp_enqueue_style( 'rosetta-locale', get_locale_css_url(), array(), 1 );
}

require WPORGPATH . 'header.php';

