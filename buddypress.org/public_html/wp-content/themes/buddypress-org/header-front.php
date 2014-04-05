<?php if ( 'buddypress.org' === parse_url( get_home_url(), PHP_URL_HOST ) && is_front_page() ) : ?>

	<div id="headline"><div id="headline-inner">
		<h2 class="graphic home"><?php bloginfo( 'description' ); ?></h2>
		<p>BuddyPress is a powerful (yet elegant) suite of social networking components, made the WordPress way, to help you build a robust WordPress.org powered community.</p>
		<div>
			<a href="<?php bloginfo( 'url' ); ?>/download/" id="big-demo-button" class="button">Download BuddyPress &rarr;</a>
			<img src="<?php echo get_stylesheet_directory_uri(); ?>/images/screenshots.png?v=2" alt="Screenshots">
		</div>
	</div></div>
	<hr class="hidden" />

	<div id="showcase"><div id="showcase-inner">
		<div class="feature">
			<h3><?php _e( 'Profiles', 'bborg' ); ?></h3>
			<p><a href="//buddypress.org/about/profiles/"><img src="<?php echo get_stylesheet_directory_uri(); ?>/images/feature_profiles.gif" alt="" width="78" height="58"></a>Custom profile fields.<br /> Visibility levels.<br /> Common field types.</p>
		</div>
		<div class="feature">
			<h3><?php _e( 'Settings', 'bborg' ); ?></h3>
			<p><a href="//buddypress.org/about/settings/"><img src="<?php echo get_stylesheet_directory_uri(); ?>/images/feature_settings.gif" alt="" width="78" height="58"></a>Manage account settings.<br /> Email notifications.<br /> Email and Password.</p>
		</div>
		<div class="feature">
			<h3><?php _e( 'Groups', 'bborg' ); ?></h3>
			<p><a href="//buddypress.org/about/groups/"><img src="<?php echo get_stylesheet_directory_uri(); ?>/images/feature_groups.gif" alt="" width="78" height="58"></a>Extensible user groups. Allow your users to create micro-communities.</p>
		</div>
		<div class="feature" style="margin:0;">
			<h3><?php _e( 'Activity Streams', 'bborg' ); ?></h3>
			<p><a href="//buddypress.org/about/activity/"><img src="<?php echo get_stylesheet_directory_uri(); ?>/images/feature_activity.gif" alt="" width="78" height="58"></a>For members and groups. Sitewide directory and single threads.</p>
		</div>
		<div class="feature">
			<h3><?php _e( 'Notifications', 'bborg' ); ?></h3>
			<p><a href="//buddypress.org/about/notifications/"><img src="<?php echo get_stylesheet_directory_uri(); ?>/images/feature_notifications.gif" alt="" width="78" height="58"></a>Get notified.<br /> Smart read/unread.<br /> Fully integrated.</p>
		</div>
		<div class="feature">
			<h3><?php _e( 'Friendships', 'bborg' ); ?></h3>
			<p><a href="//buddypress.org/about/friends/"><img src="<?php echo get_stylesheet_directory_uri(); ?>/images/feature_friends.gif" alt="" width="78" height="58"></a>Friendship connections.<br /> It's always about.<br /> whe you know!</p>
		</div>
		<div class="feature">
			<h3><?php _e( 'Private Messaging', 'bborg' ); ?></h3>
			<p><a href="//buddypress.org/about/private-messaging/"><img src="<?php echo get_stylesheet_directory_uri(); ?>/images/feature_pms.gif" alt="" width="78" height="58"></a>Private conversations, with several members at one time.</p>
		</div>
		<div class="feature" style="margin:0;">
			<h3><?php _e( '...and more!', 'bborg' ); ?></h3>
			<p><a href="//buddypress.org/about/more/"><img src="<?php echo get_stylesheet_directory_uri(); ?>/images/feature_more.gif" alt="" width="78" height="58"></a>Extend BuddyPress with hundreds of third party components.</p>
		</div>
	</div></div>

<?php endif;
