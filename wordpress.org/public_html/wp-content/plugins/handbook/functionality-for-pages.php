<?php
/**
 * Plugin Name: Handbook Functionality for Pages
 * Description: Adds handbook-like table of contents to all Pages for a site. Covers Table of Contents and the "watch this page" widget.
 * Author: Nacin
 */

require_once dirname( __FILE__ ) . '/inc/table-of-contents.php';
require_once dirname( __FILE__ ) . '/inc/email-post-changes.php';

new WPorg_Handbook_TOC( array( 'page' ) );

add_action( 'widgets_init', 'wporg_handbook_functionality_for_pages_widget', 12 );
function wporg_handbook_functionality_for_pages_widget() {
	require_once dirname( __FILE__ ) . '/inc/widgets.php';
	register_widget( 'WPorg_Handbook_Widget_for_Pages' );
}

add_filter( 'wporg_email_changes_for_post_types', 'wporg_email_changes_for_pages' );
function wporg_email_changes_for_pages( $post_types ) {
	if ( ! in_array( 'page', $post_types ) )
		$post_types[] = 'page';
	return $post_types;
}

