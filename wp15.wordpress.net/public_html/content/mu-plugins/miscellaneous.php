<?php

/*
Plugin Name: WP15 - Miscellaneous
Description: Miscellaneous functionality for WP15
Version:     0.1
Author:      WordPress Meta Team
Author URI:  https://make.wordpress.org/meta
*/

namespace WP15\Miscellaneous;
use DateTime;

defined( 'WPINC' ) or die();

add_filter( 'map_meta_cap',  __NAMESPACE__ . '\allow_css_editing', 10, 2   );
add_filter( 'tggr_end_date', __NAMESPACE__ . '\set_tagregator_cutoff_date' );
add_filter( 'wp15_update_pomo_files', __NAMESPACE__ . '\update_pomo_files' );

if ( ! wp_next_scheduled( 'wp15_update_pomo_files' ) ) {
	wp_schedule_event( time(), 'hourly', 'wp15_update_pomo_files' );
}


/**
 * Allow admins to use Additional CSS, despite `DISALLOW_UNFILTERED_HTML`.
 *
 * The admins on this site are trusted, so `DISALLOW_UNFILTERED_HTML` is mostly in place to enforce best practices,
 * -- like placing JavaScript in a plugin instead of `post_content` -- rather than to prevent malicious code. CSS
 * is an exception to that rule, though; it's perfectly acceptable to store minor tweaks in Additional CSS, that's
 * what it's for.
 *
 * @param array  $required_capabilities The primitive capabilities that are required to perform the requested meta
 *                                      capability.
 * @param string $requested_capability  The requested meta capability.
 *
 * @return array
 */
function allow_css_editing( $required_capabilities, $requested_capability ) {
	if ( 'edit_css' === $requested_capability ) {
		$required_capabilities = array( 'edit_theme_options' );
	}

	return $required_capabilities;
}

/**
 * Tell Tagregator when to stop fetching new items.
 *
 * The #wp15 hashtag will collect spam, etc, after the event is over, and we want to
 * avoid publishing those.
 *
 * @param DateTime|null $date
 *
 * @return DateTime
 */
function set_tagregator_cutoff_date( $date ) {
	// A few weeks after the event ends, so that wrap-up posts, etc are included.
	return new DateTime( 'June 15, 2018' );
}

/**
 * Update the PO/MO files for the wp15 text domain.
 */
function update_pomo_files() {
	$gp_api            = 'https://translate.wordpress.org';
	$gp_project        = 'meta/wp15';
	$localizations_dir = WP_CONTENT_DIR . '/languages/wp15';
	$set_response      = wp_remote_get( "$gp_api/api/projects/$gp_project" );
	$body              = json_decode( wp_remote_retrieve_body( $set_response ) );
	$translation_sets  = isset( $body->translation_sets ) ? $body->translation_sets : false;

	if ( ! $translation_sets ) {
		trigger_error( 'Translation sets missing from response body.' );
		return;
	}

	foreach ( $translation_sets as $set ) {
		if ( empty( $set->locale ) || empty( $set->wp_locale ) ) {
			continue;
		}

		$po_response = wp_remote_get( "$gp_api/projects/$gp_project/{$set->locale}/default/export-translations?filters[status]=current&format=po" );
		$mo_response = wp_remote_get( "$gp_api/projects/$gp_project/{$set->locale}/default/export-translations?filters[status]=current&format=mo" );
		$po_content  = wp_remote_retrieve_body( $po_response );
		$mo_content  = wp_remote_retrieve_body( $mo_response );

		if ( ! $po_content || ! $mo_content || false === strpos( $po_content, "Language: {$set->wp_locale}" ) ) {
			trigger_error( "Invalid PO/MO content for {$set->wp_locale}." );
			continue;
		}

		file_put_contents( "$localizations_dir/wp15-{$set->wp_locale}.po", $po_content );
		file_put_contents( "$localizations_dir/wp15-{$set->wp_locale}.mo", $mo_content );
	}
}
