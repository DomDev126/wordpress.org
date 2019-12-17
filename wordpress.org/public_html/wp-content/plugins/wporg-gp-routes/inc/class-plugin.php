<?php

namespace WordPressdotorg\GlotPress\Routes;

use GP;
use GP_Locales;
use WP_CLI;

class Plugin {

	/**
	 * @var Plugin The singleton instance.
	 */
	private static $instance;

	/**
	 * Returns always the same instance of this plugin.
	 *
	 * @return Plugin
	 */
	public static function get_instance() {
		if ( ! ( self::$instance instanceof Plugin ) ) {
			self::$instance = new Plugin();
		}
		return self::$instance;
	}

	/**
	 * Instantiates a new Plugin object.
	 */
	private function __construct() {
		add_action( 'plugins_loaded', [ $this, 'plugins_loaded' ] );
	}

	/**
	 * Initializes the plugin.
	 */
	public function plugins_loaded() {
		if ( file_exists( WPORGPATH . 'wp-content/plugins/plugin-directory/class-template.php' ) ) {
			include_once WPORGPATH . 'wp-content/plugins/plugin-directory/class-template.php';
		}

		add_action( 'template_redirect', [ $this, 'register_routes' ], 5 );
		add_filter( 'gp_locale_glossary_path_prefix', [ $this, 'set_locale_glossary_path_prefix' ] );

		add_filter( 'cron_schedules', [ $this, 'register_cron_schedules' ] );
		add_action( 'init', [ $this, 'register_cron_events' ] );
		add_action( 'init', [ $this, 'respect_robots_txt' ], 9 );
		add_action( 'wporg_translate_update_existing_locales_cache', [ $this, 'update_existing_locales_cache' ] );
		add_action( 'wporg_translate_update_translation_status_cache', [ $this, 'update_translation_status_cache' ] );
		add_action( 'wporg_translate_update_contributors_count_cache', [ $this, 'update_contributors_count_cache' ] );
	}

	/**
	 * The GlotPress match-all route is prepended early before a lot of rules, including the robots.txt rules.
	 *
	 * gp_rewrite_rules() is called from gp_init() on init at priority 10, we need to insert before that.
	 */
	function respect_robots_txt() {
		// ?robots=1 is here to trigger `is_robots()`, which prevents canonical.
		// ?gp_route=robots.txt is here, as GlotPress ultimately is the router for the request.
		add_rewrite_rule( '^robots\.txt$', 'index.php?robots=1&gp_route=robots.txt', 'top' );
	}

	/**
	 * Changes the path prefix for a locale glossary to '/locale'.
	 *
	 * @return string '/locale'
	 */
	public function set_locale_glossary_path_prefix() {
		return '/locale';
	}

	/**
	 * Registers custom routes and removes default routes.
	 *
	 * Removes:
	 *  - API: /languages/$locale
	 *  - /languages/$locale
	 *  - /languages/$locale
	 *  - /languages/$locale/$path
	 *  - (/languages)/$locale/$dir/glossary
	 *  - (/languages)/$locale/$dir/glossary/-new
	 *  - (/languages)/$locale/$dir/glossary/-delete
	 *  - (/languages)/$locale/$dir/glossary/-export
	 *  - (/languages)/$locale/$dir/glossary/-import
	 *  - /profile/$path
	 *  - /projects/wp-plugins/?
	 *  - /projects/wp-themes/?
	 *
	 * Adds:
	 *  - /
	 *  - /robots.txt
	 *  - /locale/$locale
	 *  - /locale/$locale/$path
	 *  - /locale/$locale/$path/$path
	 *  - /locale/$locale/$path/$path/$path
	 *  - (/locale)/$locale/$dir/glossary
	 *  - (/locale)/$locale/$dir/glossary/-new
	 *  - (/locale)/$locale/$dir/glossary/-delete
	 *  - (/locale)/$locale/$dir/glossary/-export
	 *  - (/locale)/$locale/$dir/glossary/-import
	 *  - /locale/$locale/$dir/stats(?:/(plugins|themes))?s
	 *  - /stats/?
	 *  - /projects/wp-plugins/$project
	 *  - /projects/wp-plugins/$project/contributors
	 *  - /projects/wp-plugins/$project/language-packs
	 *  - /projects/wp-themes/$project
	 *  - /projects/wp-themes/$project/contributors
	 *  - /projects/wp-themes/$project/language-packs
	 */
	public function register_routes() {
		$request_uri = GP::$router->request_uri();
		$path = '(.+?)';
		$dir = '([^_/][^/]*)';
		$project = '([^/]*)/?';

		$locale = '(' . implode( '|', array_map( function( $locale ) { return $locale->slug; }, GP_Locales::locales() ) ) . ')';
		$sets   = '(default|formal|informal|ao90|valencia)';

		if ( gp_startswith( $request_uri, '/' . GP::$router->api_prefix . '/' ) ) { // API requests.
			// Delete default routes.
			GP::$router->remove( "/languages/$locale" );
			GP::$router->remove( '/profile' );
		} else {
			// Delete default routes.
			GP::$router->remove( "/languages/$locale" );
			GP::$router->remove( "/languages/$locale/$path" );
			GP::$router->remove( "(/languages)/$locale/$dir/glossary" );
			GP::$router->remove( "(/languages)/$locale/$dir/glossary", 'post' );
			GP::$router->remove( "(/languages)/$locale/$dir/glossary/-new", 'post' );
			GP::$router->remove( "(/languages)/$locale/$dir/glossary/-delete", 'post' );
			GP::$router->remove( "(/languages)/$locale/$dir/glossary/-export" );
			GP::$router->remove( "(/languages)/$locale/$dir/glossary/-import" );
			GP::$router->remove( "(/languages)/$locale/$dir/glossary/-import", 'post' );

			GP::$router->remove( '/profile' );
			GP::$router->remove( "/profile/$path" );

			// Redirect routes.
			GP::$router->prepend( '/languages', array( __NAMESPACE__ . '\Routes\Redirector', 'redirect_languages' ) );
			GP::$router->prepend( "/languages/$path", array( __NAMESPACE__ . '\Routes\Redirector', 'redirect_languages' ) );
			GP::$router->prepend( '/projects/wp-plugins/?', array( __NAMESPACE__ . '\Routes\Redirector', 'redirect_index' ) );
			GP::$router->prepend( '/projects/wp-themes/?', array( __NAMESPACE__ . '\Routes\Redirector', 'redirect_index' ) );
			GP::$router->prepend( "/projects/wp/$path/$locale/$dir/glossary", array( __NAMESPACE__ . '\Routes\Redirector', 'redirect_old_glossary' ) );

			// Register custom routes.
			GP::$router->prepend( '/', array( __NAMESPACE__ . '\Routes\Index', 'get_locales' ) );
			GP::$router->prepend( "/locale/$locale", array( __NAMESPACE__ . '\Routes\Locale', 'get_locale_projects' ) );
			GP::$router->prepend( "/locale/$locale/$sets", array( __NAMESPACE__ . '\Routes\Locale', 'get_locale_projects' ) );
			GP::$router->prepend( "/locale/$locale/$sets/$path", array( __NAMESPACE__ . '\Routes\Locale', 'get_locale_projects' ) );
			GP::$router->prepend( "/locale/$locale/$sets/$path/$path", array( __NAMESPACE__ . '\Routes\Locale', 'get_locale_project' ) );
			GP::$router->prepend( "(/locale)/$locale/$sets/glossary", array( 'GP_Route_Glossary_Entry', 'glossary_entries_get' ) );
			GP::$router->prepend( "(/locale)/$locale/$sets/glossary", array( 'GP_Route_Glossary_Entry', 'glossary_entries_post' ), 'post' );
			GP::$router->prepend( "(/locale)/$locale/$sets/glossary/-new", array( 'GP_Route_Glossary_Entry', 'glossary_entry_add_post' ), 'post' );
			GP::$router->prepend( "(/locale)/$locale/$sets/glossary/-delete", array( 'GP_Route_Glossary_Entry', 'glossary_entry_delete_post' ), 'post' );
			GP::$router->prepend( "(/locale)/$locale/$sets/glossary/-export", array( 'GP_Route_Glossary_Entry', 'export_glossary_entries_get' ) );
			GP::$router->prepend( "(/locale)/$locale/$sets/glossary/-import", array( 'GP_Route_Glossary_Entry', 'import_glossary_entries_get' ) );
			GP::$router->prepend( "(/locale)/$locale/$sets/glossary/-import", array( 'GP_Route_Glossary_Entry', 'import_glossary_entries_post' ), 'post' );
			GP::$router->prepend( "/locale/$locale/$sets/stats(?:/(plugins|themes))?", array( __NAMESPACE__ . '\Routes\Stats', 'get_stats_plugin_theme_overview' ) );
			GP::$router->prepend( '/stats', array( __NAMESPACE__ . '\Routes\Stats', 'get_stats_overview' ) );
			GP::$router->prepend( '/consistency', array( __NAMESPACE__ . '\Routes\Consistency', 'get_search_form' ) );

			// Project routes.
			GP::$router->prepend( "/projects/wp-plugins/$project", array( __NAMESPACE__ . '\Routes\WP_Plugins', 'get_plugin_projects' ) );
			GP::$router->prepend( "/projects/wp-plugins/$project/contributors", array( __NAMESPACE__ . '\Routes\WP_Plugins', 'get_plugin_contributors' ) );
			GP::$router->prepend( "/projects/wp-plugins/$project/language-packs", array( __NAMESPACE__ . '\Routes\WP_Plugins', 'get_plugin_language_packs' ) );
			GP::$router->prepend( "/projects/wp-themes/$project", array( __NAMESPACE__ . '\Routes\WP_Themes', 'get_theme_projects' ) );
			GP::$router->prepend( "/projects/wp-themes/$project/contributors", array( __NAMESPACE__ . '\Routes\WP_Themes', 'get_theme_contributors' ) );
			GP::$router->prepend( "/projects/wp-themes/$project/language-packs", array( __NAMESPACE__ . '\Routes\WP_Themes', 'get_theme_language_packs' ) );

			// Add Robots.txt support
			GP::$router->prepend( '/robots\.txt', 'do_robots' );

			if ( defined( 'TRANSLATE_MAINTENANCE_ACTIVE' ) ) {
				GP::$router->prepend( '.*', array( __NAMESPACE__ . '\Routes\Maintenance', 'show_maintenance_message' ) );
			}
		}
	}

	/**
	 * Filters the non-default cron schedules.
	 *
	 * @param array $schedules An array of non-default cron schedules.
	 * @return array  An array of non-default cron schedules.
	 */
	public function register_cron_schedules( $schedules ) {
		$schedules['15_minutes'] = array(
			'interval' => 15 * MINUTE_IN_SECONDS,
			'display'  => 'Every 15 minutes',
		);

		return $schedules;
	}

	/**
	 * Registers CLI commands if WP-CLI is loaded.
	 */
	public function register_cron_events() {
		if ( ! wp_next_scheduled( 'wporg_translate_update_existing_locales_cache' ) ) {
			wp_schedule_event( time(), '15_minutes', 'wporg_translate_update_existing_locales_cache' );
		}

		if ( ! wp_next_scheduled( 'wporg_translate_update_translation_status_cache' ) ) {
			wp_schedule_event( time() + 3 * MINUTE_IN_SECONDS, '15_minutes', 'wporg_translate_update_translation_status_cache' );
		}

		if ( ! wp_next_scheduled( 'wporg_translate_update_contributors_count_cache' ) ) {
			wp_schedule_event( time() + 6 * MINUTE_IN_SECONDS, '15_minutes', 'wporg_translate_update_contributors_count_cache' );
		}
	}

	/**
	 * Calculates the translation status of the WordPress project per locale.
	 */
	public function update_translation_status_cache() {
		global $wpdb;

		if ( ! isset( $wpdb->project_translation_status ) ) {
			return;
		}

		$translation_status = $wpdb->get_results( $wpdb->prepare(
			"SELECT `locale`, `all` AS `all_count`, `waiting` AS `waiting_count`, `current` AS `current_count`, `fuzzy` AS `fuzzy_count`
			FROM {$wpdb->project_translation_status}
			WHERE `project_id` = %d AND `locale_slug` = %s",
			2, // 2 = wp/dev
			'default'
		), OBJECT_K );

		if ( ! $translation_status ) {
			return;
		}

		wp_cache_set( 'translation-status', $translation_status, 'wporg-translate' );
	}

	/**
	 * Updates contributors count per locale.
	 */
	public function update_contributors_count_cache() {
		global $wpdb;

		if ( ! isset( $wpdb->user_translations_count ) ) {
			return;
		}

		$locales   = $this->get_existing_locales();
		$db_counts = $wpdb->get_results(
			"SELECT `locale`, COUNT( DISTINCT user_id ) as `count` FROM {$wpdb->user_translations_count} WHERE `accepted` > 0 GROUP BY `locale`",
			OBJECT_K
		);

		if ( ! $db_counts || ! $locales ) {
			return;
		}

		$counts = array();
		foreach ( $locales as $locale ) {
			if ( isset( $db_counts[ $locale ] ) ) {
				$counts[ $locale ] = (int) $db_counts[ $locale ]->count;
			} else {
				$counts[ $locale ] = 0;
			}
		}

		wp_cache_set( 'contributors-count', $counts, 'wporg-translate' );
	}

	/**
	 * Retrieves existing locales of the main wp/dev project.
	 *
	 * Much faster alternative to `GP::$translation_set->existing_locales();` since it avoids
	 * the expensive DISTINCT.
	 *
	 * @return array List of GlotPress locales.
	 */
	private function get_existing_locales() {
		global $wpdb;

		return $wpdb->get_col(
			$wpdb->prepare(
				"SELECT locale FROM {$wpdb->gp_translation_sets} WHERE `project_id` = %d and slug = %s",
				2, // 2 = wp/dev
				'default'
			)
		);
	}

	/**
	 * Updates cache for existing locales.
	 */
	public function update_existing_locales_cache() {
		$existing_locales = $this->get_existing_locales();
		if ( ! $existing_locales ) {
			return;
		}

		wp_cache_set( 'existing-locales', $existing_locales, 'wporg-translate' );
	}
}
