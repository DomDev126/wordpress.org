<?php
/**
 * Template for displaying a release archive page.
 *
 * @package WordPressdotorg\MainTheme
 */

namespace WordPressdotorg\MainTheme;

$releases = $GLOBALS['rosetta']->rosetta->get_releases_breakdown();

the_post();
get_header();
?>

	<article id="post-<?php the_ID(); ?>" <?php post_class( 'col-12' ); ?> role="main">
		<header class="entry-header">
			<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
		</header><!-- .entry-header -->

		<div class="entry-content">
			<?php
			if ( ! empty( $releases ) ) :
				if ( isset( $releases['latest'] ) ) :
					?>
					<h3 id="latest"><?php _esc_html_e( 'Latest release', 'wporg' ); ?></h3>
					<table class="releases latest">
						<?php release_row( $releases['latest'] ); ?>
					</table>
					<?php
				endif;

				if ( ! empty( $releases['branches'] ) ) :
					echo '<a name="older" id="older"></a>';

					foreach ( $releases['branches'] as $branch => $branch_release ) :
						?>
						<h3>
							<?php
							printf(
								/* translators: Version number. */
								esc_html__( '%s Branch', 'wporg' ),
								esc_html( $branch )
							);
							?>
						</h3>
						<table class="releases">
							<?php
							foreach ( $branch_release as $release ) :
								release_row( $release );
							endforeach;
							?>
						</table>
						<?php
					endforeach;
				endif; // Any branches.

				if ( ! empty( $releases['betas'] ) ) :
					?>
					<h3 id="betas"><?php _esc_html_e( 'Beta &amp; RC releases', 'wporg' ); ?></h3>
					<table id="beta" class="releases">
						<?php
						foreach ( $releases['betas'] as $release ) :
							release_row( $release );
						endforeach;
						?>
					</table>

					<?php
				endif; // Any betas.
			else : // No releases.
				echo '<p>' . esc_html__( 'There are no releases, yet.', 'wporg' ) . '</p>';
			endif; // if releases.
			?>
		</div><!-- .entry-content -->

		<?php
		edit_post_link(
			sprintf(
				/* translators: %s: Name of current post */
				esc_html__( 'Edit %s', 'wporg' ),
				the_title( '<span class="screen-reader-text">"', '"</span>', false )
			),
			'<footer class="entry-footer"><span class="edit-link">',
			'</span></footer><!-- .entry-footer -->'
		);
		?>
	</article><!-- #post-## -->

<?php
get_footer();
