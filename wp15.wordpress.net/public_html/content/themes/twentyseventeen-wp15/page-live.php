<?php get_header(); ?>

<div class="wrap">
	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">
			<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

				<header class="entry-header">
					<h1 class="entry-title">
						<?php

						// translators: The name of the page that displays the #wp15 social media posts in real time.
						echo esc_html_x( 'Live', 'verb', 'wp15' );

						?>
					</h1>
				</header>

				<div class="entry-content">
					<?php _e( 'Join the conversation by using #WP15 on your favorite social networks.', 'wp15' ); ?>

					<?php if ( current_user_can( 'manage_options' ) ) : ?>
						<p style="color:darkred">Don't forget to enable this for logged-out visitors once moderation is in place.</p>
					<?php echo do_shortcode( '[tagregator hashtag="#WP15"]' ); ?>
					<?php endif; ?>
				</div>

			</article>
		</main>
	</div>
</div>

<?php get_footer();
