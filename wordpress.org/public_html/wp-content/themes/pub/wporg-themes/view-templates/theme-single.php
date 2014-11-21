<script id="tmpl-theme-single" type="text/template">
	<div class="theme-backdrop"></div>
	<div class="theme-wrap">
		<div class="theme-header">
			<button class="left dashicons dashicons-no"><span class="screen-reader-text"><?php _e( 'Show previous theme' ); ?></span></button>
			<button class="right dashicons dashicons-no"><span class="screen-reader-text"><?php _e( 'Show next theme' ); ?></span></button>
			<button class="close dashicons dashicons-no"><span class="screen-reader-text"><?php _e( 'Close overlay' ); ?></span></button>
		</div>
		<div class="theme-about">
			<div class="theme-screenshots">
				<# if ( data.screenshot_url ) { #>
				<div class="screenshot"><img src="{{ data.screenshot_url }}" alt=""/></div>
				<# } else { #>
				<div class="screenshot blank"></div>
				<# } #>
			</div>

			<div class="theme-info">
				<h3 class="theme-name">
					{{{ data.name }}}<span class="theme-version"><?php printf( __( 'Version: %s' ), '{{{ data.version }}}' ); ?></span>
				</h3>
				<h4 class="theme-author"><?php printf( __( 'By %s' ), '{{{ data.author }}}' ); ?></h4>

				<p class="theme-description">{{{ data.description }}}</p>

				<div class="rating rating-{{ Math.round( data.rating / 10 ) * 10 }}">
					<span class="one"></span>
					<span class="two"></span>
					<span class="three"></span>
					<span class="four"></span>
					<span class="five"></span>

					<p class="votes"><?php printf( __( 'Based on %s ratings.' ), '{{{ data.num_ratings }}}' ); ?></p>
				</div>

				<div class="theme-stats">
					<div><strong><?php _e( 'Last updated:' ); ?></strong> {{ data.last_updated }}</div>
					<div><strong><?php _e( 'Downloads:' ); ?></strong> {{ data.downloaded }}</div>
					<div><a href="{{ data.homepage }}"><?php _e( 'Theme Homepage &raquo;' ); ?></a></div>
				</div>

				<# if ( data.tags ) { #>
				<p class="theme-tags">
					<span><?php _e( 'Tags:' ); ?></span>
					<# _.each( data.tags, function( tag ) { #>
						{{{ tag }}}
					<# }); #>
				</p>
				<# } #>
			</div>
		</div>

		<div class="theme-actions">
			<a href="" class="button button-primary"><?php _e( 'Download' ); ?></a>
			<a href="{{{ data.preview_url }}}" class="button button-secondary"><?php _e( 'Preview' ); ?></a>
		</div>
	</div>
</script>
