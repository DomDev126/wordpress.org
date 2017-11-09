<?php
/**
 * Custom template tags
 *
 * @package WordPressdotorg\MainTheme
 */

namespace WordPressdotorg\MainTheme;

/**
 * Displays a table row with release information.
 *
 * @param array  $release
 * @param string $alt_class
 * @param string $first_of_branch_class
 * @param bool   $reset
 */
function release_row( $release, $alt_class = '', $first_of_branch_class = '', $reset = false ) {
	static $even = true;
	static $last_branch = '';

	if ( $reset ) {
		$even        = true;
		$last_branch = '';

		return;
	}

	$classes = array();
	if ( ! $even && $alt_class ) {
		$classes[] = $alt_class;
	}

	$even = ! $even;

	if ( $release['branch'] != $last_branch && $first_of_branch_class ) {
		$classes[] = $first_of_branch_class;
	}

	$last_branch = $release['branch'];
	?>

	<tr class="<?php echo esc_attr( implode( ' ', $classes ) ); ?>">
		<td><?php echo esc_html( $release['version'] ); ?></td>
		<td><?php echo esc_html( date_i18n( get_option( 'date_format' ), $release['builton'] ) ); ?></td>
		<td><a href="<?php echo esc_url( $release['zip_url'] ); ?>">zip</a>
			<small>(<a href="<?php echo esc_url( $release['zip_url'] . '.md5' ); ?>">md5</a>)</small>
		</td>
		<td><a href="<?php echo esc_url( $release['targz_url'] ); ?>">tar.gz</a>
			<small>(<a href="<?php echo esc_url( $release['targz_url'] . '.md5' ); ?>">md5</a>)</small>
		</td>
	</tr>
	<?php
}
