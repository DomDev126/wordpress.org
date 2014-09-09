<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the #content div and all content after
 *
 * @package wpmobileapps
 */
?>

	</div><!-- #content -->


	<footer id="colophon" class="site-footer" role="contentinfo">

        <div class="button-download-container">
                <h3><?php _e( 'Download the WordPress app:', 'wpmobileapps' ); ?></h3>
	            <?php echo do_shortcode( '[wpmobileapps_download_button platform="ios" link="http://itunes.apple.com/us/app/wordpress/id335703880?mt=8"]iOS[/wpmobileapps_download_button]' ); ?>
	            <?php echo do_shortcode( '[wpmobileapps_download_button platform="android" link="http://play.google.com/store/apps/details?id=org.wordpress.android"]ANDROID[/wpmobileapps_download_button]' ); ?>
        </div>

		<div class="footer-area">
		<div class="site-info clear">
			<?php
				if ( has_nav_menu( 'social' ) ) {
					wp_nav_menu( array(
						'theme_location'  => 'social',
						'container_class' => 'menu-social',
						'menu_class'      => 'clear',
						'link_before'     => '<span class="menu-social-text">',
						'link_after'      => '</span>',
						'depth'           => 1,
					) );
				}
			?>

			<p class="wporg-attribution"><a href="http://wordpress.org">WordPress.org</a></p>

		</div><!-- .site-info -->
		</div><!-- .footer-area -->
	</footer><!-- #colophon -->
</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>
