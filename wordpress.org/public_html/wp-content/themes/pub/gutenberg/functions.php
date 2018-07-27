<?php
/**
 * gutenbergtheme functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package Gutenbergtheme
 */

if ( ! defined( 'WPORGPATH' ) ) {
	define( 'WPORGPATH', get_theme_file_path( '/inc/' ) );
}


add_action( 'template_redirect', function() {
	if ( ! is_page( 'test' ) ) {
		return;
	}

	function frontenberg_edit_pages( $allcaps ) {
		$allcaps['edit_pages']           = true;
		$allcaps['edit_others_pages']    = true;
		$allcaps['edit_published_pages'] = true;

		return $allcaps;
	}
	add_filter( 'user_has_cap', 'frontenberg_edit_pages' );

	show_admin_bar( true );

	add_action( 'wp_enqueue_scripts', function() {
		wp_enqueue_script( 'postbox', admin_url( 'js/postbox.min.js' ),array( 'jquery-ui-sortable' ), false, 1 );
		wp_enqueue_style( 'dashicons' );
		wp_enqueue_style( 'common' );
		wp_enqueue_style( 'forms' );
		wp_enqueue_style( 'dashboard' );
		wp_enqueue_style( 'media' );
		wp_enqueue_style( 'admin-menu' );
		wp_enqueue_style( 'admin-bar' );
		wp_enqueue_style( 'nav-menus' );
		wp_enqueue_style( 'l10n' );
		wp_enqueue_style( 'buttons' );
		wp_add_inline_script( 'wp-api-fetch',
			sprintf(
				'wp.apiFetch.use( function( options, next ) {
					if ( options.method !== "GET" ) {
						return Promise.resolve( options.data ); //not sure what this actually should be, etc.
					}

					return next( options );
				} );'
			),
			'after'
		);
	} );
	add_action( 'wp_enqueue_scripts', 'gutenberg_editor_scripts_and_styles' );

	if ( ! is_user_logged_in() ) {
		add_filter( 'wp_insert_post_empty_content', '__return_true', PHP_INT_MAX -1, 2 );
		add_filter( 'pre_insert_term', function( $t ) {return ''; });
	}


	// Disable use XML-RPC
	add_filter( 'xmlrpc_enabled', '__return_false' );

	// Disable X-Pingback to header
	function disable_x_pingback( $headers ) {
		unset( $headers['X-Pingback'] );

		return $headers;
	}
	add_filter( 'wp_headers', 'disable_x_pingback' );

	/**
	 * Ajax handler for querying attachments.
	 *
	 * @since 3.5.0
	 */
	function frontenberg_wp_ajax_nopriv_query_attachments() {
		$query = isset( $_REQUEST['query'] ) ? (array) $_REQUEST['query'] : array();
		$keys = array(
			's', 'order', 'orderby', 'posts_per_page', 'paged', 'post_mime_type',
			'post_parent', 'post__in', 'post__not_in', 'year', 'monthnum'
		);
		foreach ( get_taxonomies_for_attachments( 'objects' ) as $t ) {
			if ( $t->query_var && isset( $query[ $t->query_var ] ) ) {
				$keys[] = $t->query_var;
			}
		}

		$query = array_intersect_key( $query, array_flip( $keys ) );
		$query['post_type'] = 'attachment';
		if ( MEDIA_TRASH
			&& ! empty( $_REQUEST['query']['post_status'] )
			&& 'trash' === $_REQUEST['query']['post_status'] ) {
			$query['post_status'] = 'trash';
		} else {
			$query['post_status'] = 'inherit';
		}

		// Filter query clauses to include filenames.
		if ( isset( $query['s'] ) ) {
			add_filter( 'posts_clauses', '_filter_query_attachment_filenames' );
		}

		/**
		 * Filters the arguments passed to WP_Query during an Ajax
		 * call for querying attachments.
		 *
		 * @since 3.7.0
		 *
		 * @see WP_Query::parse_query()
		 *
		 * @param array $query An array of query variables.
		 */
		$query = apply_filters( 'ajax_query_attachments_args', $query );
		$query = new WP_Query( $query );

		$posts = array_map( 'wp_prepare_attachment_for_js', $query->posts );
		$posts = array_filter( $posts );

		wp_send_json_success( $posts );
	}
	add_action( 'wp_ajax_nopriv_query-attachments', 'frontenberg_wp_ajax_nopriv_query_attachments' );
});



if ( ! function_exists( 'gutenbergtheme_setup' ) ) :
	/**
	 * Sets up theme defaults and registers support for various WordPress features.
	 *
	 * Note that this function is hooked into the after_setup_theme hook, which
	 * runs before the init hook. The init hook is too late for some features, such
	 * as indicating support for post thumbnails.
	 */
	function gutenbergtheme_setup() {
		/*
		 * Make theme available for translation.
		 * Translations can be filed in the /languages/ directory.
		 * If you're building a theme based on gutenbergtheme, use a find and replace
		 * to change 'gutenbergtheme' to the name of your theme in all the template files.
		 */
		load_theme_textdomain( 'gutenbergtheme', get_template_directory() . '/languages' );

		// Add default posts and comments RSS feed links to head.
		add_theme_support( 'automatic-feed-links' );

		/*
		 * Let WordPress manage the document title.
		 * By adding theme support, we declare that this theme does not use a
		 * hard-coded <title> tag in the document head, and expect WordPress to
		 * provide it for us.
		 */
		add_theme_support( 'title-tag' );

		/*
		 * Enable support for Post Thumbnails on posts and pages.
		 *
		 * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
		 */
		add_theme_support( 'post-thumbnails' );

		// This theme uses wp_nav_menu() in one location.
		register_nav_menus( array(
			'menu-1' => esc_html__( 'Primary', 'gutenbergtheme' ),
		) );

		/*
		 * Switch default core markup for search form, comment form, and comments
		 * to output valid HTML5.
		 */
		add_theme_support( 'html5', array(
			'search-form',
			'comment-form',
			'comment-list',
			'gallery',
			'caption',
		) );

		add_theme_support( 'align-wide' );

		add_theme_support( 'editor-color-palette', [
			[
				'name'  => esc_html__( 'Dark Blue', 'gutenbergtheme' ),
				'slug' => 'dark-blue',
				'color' => '#0073aa',
			],
			[

				'name'  => esc_html__( 'Light Blue', 'gutenbergtheme' ),
				'slug' => 'light-blue',
				'color' => '#229fd8',
			],
			[

				'name'  => esc_html__( 'Dark Gray', 'gutenbergtheme' ),
				'slug' => 'dark-gray',
				'color' => '#444',
			],
			[

				'name'  => esc_html__( 'Light Gray', 'gutenbergtheme' ),
				'slug' => 'light-gray',
				'color' => '#eee',
			],
		] );
	}
endif;
add_action( 'after_setup_theme', 'gutenbergtheme_setup' );

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function gutenbergtheme_content_width() {
	$GLOBALS['content_width'] = apply_filters( 'gutenbergtheme_content_width', 640 );
}
add_action( 'after_setup_theme', 'gutenbergtheme_content_width', 0 );

/**
 * Register Google Fonts
 */
function gutenbergtheme_fonts_url() {
    $fonts_url = '';

    /* Translators: If there are characters in your language that are not
	 * supported by Karla, translate this to 'off'. Do not translate
	 * into your own language.
	 */
	$notoserif = esc_html_x( 'on', 'Noto Serif font: on or off', 'gutenbergtheme' );

	if ( 'off' !== $notoserif ) {
		$font_families = array();
		$font_families[] = 'Noto Serif:400,400italic,700,700italic';

		$query_args = array(
			'family' => urlencode( implode( '|', $font_families ) ),
			'subset' => urlencode( 'latin,latin-ext' ),
		);

		$fonts_url = add_query_arg( $query_args, 'https://fonts.googleapis.com/css' );
	}

	return $fonts_url;

}

/**
 * Enqueue scripts and styles.
 */
function gutenbergtheme_scripts() {
	wp_enqueue_style( 'gutenbergtheme-style', get_stylesheet_uri() );

	wp_enqueue_style( 'gutenbergthemeblocks-style', get_template_directory_uri() . '/blocks.css');

	wp_enqueue_style( 'gutenbergtheme-prism', gutenbergtheme_fonts_url(), array(), null );

	wp_enqueue_script( 'gutenbergtheme-navigation', get_template_directory_uri() . '/js/navigation.js', array(), '20151215', true );

	wp_enqueue_script( 'gutenbergtheme-prism', get_template_directory_uri() . '/js/prism.js', array(), null );

	wp_enqueue_script( 'gutenbergtheme-handbook', get_template_directory_uri() . '/js/handbook.js', array( 'jquery' ), null );

	wp_enqueue_script( 'gutenbergtheme-skip-link-focus-fix', get_template_directory_uri() . '/js/skip-link-focus-fix.js', array(), '20151215', true );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'gutenbergtheme_scripts' );

function gutenbergtheme_adjacent_post_order( $order_by, $post, $order ) {
	if ( 'handbook' !== $post->post_type ) {
		return $order_by;
	}

	return "ORDER BY p.menu_order $order LIMIT 1";
}
add_filter( 'get_previous_post_sort', 'gutenbergtheme_adjacent_post_order', 10, 3 );
add_filter( 'get_next_post_sort', 'gutenbergtheme_adjacent_post_order', 10, 3 );

function gutenbergtheme_previous_post_where( $where, $in_same_term, $excluded_term, $taxonomy, $post ) {
	if ( 'handbook' !== $post->post_type ) {
		return $order_by;
	}

	return "WHERE p.post_type='handbook' AND p.post_status='publish' AND p.menu_order < {$post->menu_order}";
}
add_filter( 'get_previous_post_where', 'gutenbergtheme_previous_post_where', 10, 5 );

function gutenbergtheme_next_post_where( $where, $in_same_term, $excluded_term, $taxonomy, $post ) {
	if ( 'handbook' !== $post->post_type ) {
		return $order_by;
	}

	return "WHERE p.post_type='handbook' AND p.post_status='publish' AND p.menu_order > {$post->menu_order}";
}
add_filter( 'get_next_post_where', 'gutenbergtheme_next_post_where', 10, 5 );

/**
 * Implement the Custom Header feature.
 */
require get_template_directory() . '/inc/custom-header.php';

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Functions which enhance the theme by hooking into WordPress.
 */
require get_template_directory() . '/inc/template-functions.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';

/**
 * Load Jetpack compatibility file.
 */
if ( defined( 'JETPACK__VERSION' ) ) {
	require get_template_directory() . '/inc/jetpack.php';
}

/**
 * Gutenberg documentation
 */
require __DIR__ . '/inc/docs-importer.php';
require __DIR__ . '/inc/class-gutenberg-handbook.php';

add_action( 'load-post.php', array( 'Import_Gutendocs', 'action_load_post_php' ) );
add_action( 'edit_form_after_title', array( 'Import_Gutendocs', 'action_edit_form_after_title' ) );
add_action( 'save_post', array( 'Import_Gutendocs', 'action_save_post' ) );
add_filter( 'cron_schedules', array( 'Import_Gutendocs', 'filter_cron_schedules' ) );
add_action( 'init', array( 'Import_Gutendocs', 'action_init' ) );
add_action( 'wporg_gutenberg_manifest_import', array( 'Import_Gutendocs', 'action_wporg_gutenberg_manifest_import' ) );
add_action( 'wporg_gutenberg_markdown_import', array( 'Import_Gutendocs', 'action_wporg_gutenberg_markdown_import' ) );

add_filter( 'the_title', array( 'Gutenberg_Handbook', 'filter_the_title_edit_link' ), 10, 2 );
add_filter( 'get_edit_post_link', array( 'Gutenberg_Handbook', 'redirect_edit_link_to_github' ), 10, 3 );
add_filter( 'o2_filter_post_actions', array( 'Gutenberg_Handbook', 'redirect_o2_edit_link_to_github' ), 11, 2 );

add_filter( 'handbook_display_toc', '__return_false' );
