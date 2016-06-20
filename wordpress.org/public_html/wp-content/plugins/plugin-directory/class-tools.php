<?php
namespace WordPressdotorg\Plugin_Directory;
use WP_User;

/**
 * Various functions used by other processes, will make sense to move to specific classes.
 *
 * @package WordPressdotorg\Plugin_Directory
 */
class Tools {

	/**
	 * Retrieve the average color of a specified image.
	 *
	 * This currently relies upon the Jetpack libraries.
	 *
	 * @static
	 *
	 * @param $file_location string URL or filepath of image.
	 * @return string|bool Average color as a hex value, False on failure.
	 */
	public static function get_image_average_color( $file_location ) {
		if ( ! class_exists( 'Tonesque' ) && function_exists( 'jetpack_require_lib' ) ) {
			jetpack_require_lib( 'tonesque' );
		}

		if ( ! class_exists( 'Tonesque' ) ) {
			return false;
		}

		$tonesque = new \Tonesque( $file_location );

		return $tonesque->color();
	}

	/**
	 * Returns the two latest reviews of a specific plugin.
	 *
	 * @static
	 * @global \wpdb $wpdb WordPress database abstraction object.
	 *
	 * @todo Populate with review title/content.
	 *
	 * @param string $plugin_slug The plugin slug.
	 * @return array|false
	 */
	public static function get_plugin_reviews( $plugin_slug ) {
		if ( false === ( $reviews = wp_cache_get( "{$plugin_slug}_reviews", 'wporg-plugins' ) ) ) {
			global $wpdb;

			$reviews = $wpdb->get_results( $wpdb->prepare( "
			SELECT posts.post_text AS post_content, topics.topic_title AS post_title, ratings.rating AS post_rating, ratings.user_id AS post_author
			FROM ratings
				INNER JOIN minibb_topics AS topics ON ( ratings.review_id = topics.topic_id )
				INNER JOIN minibb_posts AS posts ON ( ratings.review_id = posts.topic_id )
			WHERE
				ratings.object_type = 'plugin' AND
				ratings.object_slug = %s AND
				posts.post_position = 1
			ORDER BY ratings.review_id DESC LIMIT 2", $plugin_slug ) );

			wp_cache_set( "{$plugin_slug}_reviews", $reviews, 'wporg-plugins', HOUR_IN_SECONDS );
		}

		return $reviews;
	}

	/**
	 * Retrieve a list of users who have commit to a specific plugin.
	 *
	 * @static
	 * @global \wpdb $wpdb WordPress database abstraction object.
	 *
	 * @param string $plugin_slug The plugin slug.
	 * @return array The list of user_login's which have commit.
	 */
	public static function get_plugin_committers( $plugin_slug ) {
		if ( false === ( $committers = wp_cache_get( "{$plugin_slug}_committer", 'wporg-plugins' ) ) ) {
			global $wpdb;

			$committers = $wpdb->get_col( $wpdb->prepare( 'SELECT user FROM `' . PLUGINS_TABLE_PREFIX . 'svn_access' . '` WHERE path = %s', "/{$plugin_slug}" ) );

			wp_cache_set( "{$plugin_slug}_committer", $committers, 'wporg-plugins' );
		}

		return $committers;
	}

	/**
	 * Retrieve a list of plugins a specific user has commit to.
	 *
	 * @static
	 * @global \wpdb $wpdb WordPress database abstraction object.
	 *
	 * @param int|\WP_User $user The user.
	 * @return array The list of plugins the user has commit to.
	 */
	public static function get_users_write_access_plugins( $user ) {
		if ( ! $user instanceof \WP_User ) {
			$user = new \WP_User( $user );
		}

		if ( ! $user->exists() ) {
			return false;
		}

		if ( false === ( $plugins = wp_cache_get( "{$user->user_login}_committer", 'wporg-plugins' ) ) ) {
			global $wpdb;

			$plugins = $wpdb->get_col( $wpdb->prepare( 'SELECT path FROM `' . PLUGINS_TABLE_PREFIX . 'svn_access' . '` WHERE user = %s', $user->user_login ) );
			$plugins = array_map( function ( $plugin ) {
				return trim( $plugin, '/' );
			}, $plugins );

			wp_cache_set( "{$user->user_login}_committer", $plugins, 'wporg-plugins' );
		}

		return $plugins;
	}

	/**
	 * Grant a user RW access to a plugin.
	 *
	 * @static
	 * @global \wpdb $wpdb WordPress database abstraction object.
	 *
	 * @param string          $plugin_slug The plugin slug.
	 * @param string|\WP_User $user        The user to grant access to.
	 * @return bool
	 */
	public static function grant_plugin_committer( $plugin_slug, $user ) {
		global $wpdb;

		if ( ! $user instanceof \WP_User ) {
			$user = new \WP_User( $user );
		}

		if ( ! $user->exists() || ! Plugin_Directory::get_plugin_post( $plugin_slug ) ) {
			return false;
		}

		$existing_committers = self::get_plugin_committers( $plugin_slug );
		if ( in_array( $user->user_login, $existing_committers, true ) ) {

			// User already has write access.
			return true;
		}

		wp_cache_delete( "{$plugin_slug}_committer", 'wporg-plugins' );
		wp_cache_delete( "{$user->user_login}_committer", 'wporg-plugins' );

		return (bool) $wpdb->insert( PLUGINS_TABLE_PREFIX . 'svn_access', array(
			'path'   => "/{$plugin_slug}",
			'user'   => $user->user_login,
			'access' => 'rw',
		) );
	}

	/**
	 * Revoke a users RW access to a plugin.
	 *
	 * @static
	 * @global \wpdb $wpdb WordPress database abstraction object.
	 *
	 * @param string          $plugin_slug The plugin slug.
	 * @param string|\WP_User $user        The user to revoke access of.
	 * @return bool
	 */
	public static function revoke_plugin_committer( $plugin_slug, $user ) {
		global $wpdb;

		if ( ! $user instanceof \WP_User ) {
			$user = new \WP_User( $user );
		}

		if ( ! $user->exists() || ! Plugin_Directory::get_plugin_post( $plugin_slug ) ) {
			return false;
		}

		wp_cache_delete( "{$plugin_slug}_committer", 'wporg-plugins' );
		wp_cache_delete( "{$user->user_login}_committer", 'wporg-plugins' );

		return $wpdb->delete( PLUGINS_TABLE_PREFIX . 'svn_access', array(
			'path' => "/{$plugin_slug}",
			'user' => $user->user_login,
		) );
	}

	/**
	 * Subscribe/Unsubscribe a user to a plugins commits.
	 *
	 * Plugin Committers are automatically subscribed to plugin commit
	 * emails and cannot unsubscribe.
	 *
	 * @static
	 *
	 * @param string      $plugin_slug The plugin to subscribe to.
	 * @param int|WP_User $user        Optional. The user to subscribe. Default current user.
	 * @param bool        $subscribe   Optional. Whether to subscribe (true) or unsubscribe (false).
	 *                                 Default: true.
	 * @return bool Whether the user is subscribed.
	 */
	public static function subscribe_to_plugin_commits( $plugin_slug, $user = 0, $subscribe = true ) {
		$post = Plugin_Directory::get_plugin_post( $plugin_slug );
		if ( ! $post ) {
			return false;
		}

		$user = new WP_User( $user ?: get_current_user_id() );
		if ( ! $user->exists() ) {
			return false;
		}

		$users = get_post_meta( $post->ID, '_commit_subscribed', true ) ?: array();

		if ( $subscribe ) {
			$users[] = $user->ID;
			$users = array_unique( $users );
		} else {
			if ( false !== ( $pos = array_search( $user->ID, $users, true ) ) ) {
				unset( $users[ $pos ] );
			}
		}

		update_post_meta( $post->ID, '_commit_subscribed', $users );

		return self::subscribed_to_plugin_commits( $plugin_slug, $user->ID );
	}

	/**
	 * Determine if a user is subscribed to a plugins commits.
	 *
	 * Plugin Committers are automatically subscribed to commits, and this
	 * function does not respect that status.
	 *
	 * @static
	 *
	 * @param string      $plugin_slug The plugin to subscribe to.
	 * @param int|WP_User $user        Optional. The user to check. Default current user.
	 * @return bool Whether the specified user is subscribed to commits.
	 */
	public static function subscribed_to_plugin_commits( $plugin_slug, $user = 0 ) {
		$post = Plugin_Directory::get_plugin_post( $plugin_slug );
		if ( ! $post ) {
			return false;
		}

		$user = new WP_User( $user ?: get_current_user_id() );
		if ( ! $user->exists() ) {
			return false;
		}

		$users = get_post_meta( $post->ID, '_commit_subscribed', true ) ?: array();

		return in_array( $user->ID, $users, true );
	}
}
