<?php

class WordCamp_Coming_Soon_Page {
	protected $override_theme_template;
	const VERSION = '0.1';
	
	/**
	 * Constructor
	 */
	public function __construct() {
		add_action( 'init',               array( $this, 'init' ), 11 );                               // after WCCSP_Settings::init()
		add_action( 'wp_enqueue_scripts', array( $this, 'manage_plugin_theme_stylesheets' ), 99 );    // (hopefully) after all plugins/themes have enqueued their styles
		add_action( 'wp_head',            array( $this, 'render_dynamic_styles' ) );
		add_filter( 'template_include',   array( $this, 'override_theme_template' ) );
	}

	/**
	 * Initialize variables
	 */
	public function init() {
		$settings                      = $GLOBALS['WCCSP_Settings']->get_settings();
		$this->override_theme_template = 'on' == $settings['enabled'] && ! is_user_logged_in();
	}

	/**
	 * Ensure the template has a consistent base of CSS rules, regardless of the current theme or Custom CSS
	 * Dequeue irrelevant stylesheets and use TwentyThirteen as the base style
	 */
	public function manage_plugin_theme_stylesheets() {
		// todo maybe need to exempt jetpack styles also - $exempt_stylesheets = array(  );
		if ( $this->override_theme_template ) {
			foreach( $GLOBALS['wp_styles']->queue as $stylesheet ) {
				// todo removing fonts that we want - wp_dequeue_style( $stylesheet );
			}
		}

		$twenty_thirteen_stylesheet = '/twentythirteen/style.css';
		foreach( $GLOBALS['wp_theme_directories'] as $directory ) {
			if ( is_file( $directory . $twenty_thirteen_stylesheet ) ) {
				wp_register_style( 'twentythirteen-style-css', $directory . $twenty_thirteen_stylesheet );
				wp_register_style( 'twentythirteen-fonts-css', '//fonts.googleapis.com/css?family=Source+Sans+Pro%3A300%2C400%2C700%2C300italic%2C400italic%2C700italic%7CBitter%3A400%2C700&#038;subset=latin%2Clatin-ext' );
				
				// todo still isn't consistent between local and remote sandboxes
			}
		}
		
		wp_register_style(
			'wccsp-template',
			plugins_url( '/css/template-coming-soon.css', __DIR__ ),
			array( 'twentythirteen-style-css', 'twentythirteen-fonts-css' ),
			self::VERSION
		);
		
		wp_enqueue_style( 'wccsp-template' );
	}

	/**
	 * Render dynamic CSS styles
	 */
	public function render_dynamic_styles() {
		if ( ! $this->override_theme_template ) {
			return;
		}
		
		$settings = $GLOBALS['WCCSP_Settings']->get_settings();
		?>
		
		<style type="text/css">
			html, body {
				background-color: <?php echo esc_html( $settings['body_background_color'] ); ?>;
				color: <?php echo esc_html( $settings['text_color'] ); ?>;
			}

			#wccsp-container {
				background-color: <?php echo esc_html( $settings['container_background_color'] ); ?>;
			}
		</style>

		<?php
	}

	/**
	 * Load the Coming Soon template instead of a theme template
	 * 
	 * @param string $template
	 * @return string
	 */
	public function override_theme_template( $template ) {
		if ( $this->override_theme_template ) {
			$template = dirname( __DIR__ ) . '/views/template-coming-soon.php';
		}
		
		return $template;
	}

	/**
	 * Collect all of the variables the Coming Soon template will need
	 * Doing this here keeps the template less cluttered and more of a pure view
	 * 
	 * @return array
	 */
	function get_template_variables() {
		$variables = array(
			'image_url'              => $this->get_image_url(),
			'dates'                  => $this->get_dates(),
			'active_modules'         => Jetpack::$instance->get_active_modules(),
			'contact_form_shortcode' => $this->get_contact_form_shortcode(),
		);

		return $variables;
	}

	/**
	 * Retrieve the URL of the image displayed in the template
	 * 
	 * @return string|false
	 */
	public function get_image_url() {
		$settings = $GLOBALS['WCCSP_Settings']->get_settings();
		$image    = wp_get_attachment_image_src( $settings['image_id'], 'full' );
		
		return $image ? $image[0] : false;
	}

	/**
	 * Retrieve the dates of the WordCamp
	 * 
	 * @return string|false
	 */
	public function get_dates() {
		$dates = false;
		
		// todo - switch to blog, lookup based on url or blog id?
		
		return $dates;
	}

	/**
	 * Retrieve the contact form shortcode string
	 *
	 * We can't just create an arbitrary shortcode because of https://github.com/Automattic/jetpack/issues/102. Instead we have to use a form that's tied to a page.
	 * This is somewhat fragile, though. It should work in most cases because the first $page that contains [contact-form] will be the one we automatically create
	 * when the site is created, but if the organizers delete that and then add multiple forms, the wrong form could be displayed. The alternative approaches also
	 * have problems, though, and #102 should be fixed relatively soon, so hopefully this will be good enough until it can be refactored. 
	 * todo Refactor this once #102-jetpack is fixed.
	 *       
	 * @return string|false
	 */
	public function get_contact_form_shortcode() {
		$contact_form_shortcode = false;
		$shortcode_regex        = get_shortcode_regex();
		
		$all_pages = get_posts( array(
			'post_type'      => 'page',
			'post_status'    => 'any',
			'posts_per_page' => -1,
		) );
		
		foreach ( $all_pages as $page ) {
			preg_match_all( '/' . $shortcode_regex . '/s', $page->post_content, $matches, PREG_SET_ORDER );

			foreach ( $matches as $shortcode ) {
				if ( 'contact-form' === $shortcode[2] ) {
					global $post;
					$post = $page;
					setup_postdata( $post );
					
					ob_start();
					echo do_shortcode( $shortcode[0] );
					$contact_form_shortcode = ob_get_clean();
					
					wp_reset_postdata();
					break;
				}
			}
		}
		
		return $contact_form_shortcode;
	}
} // end WordCamp_Coming_Soon_Page
