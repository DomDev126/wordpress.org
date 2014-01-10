<?php
/* Plugin Name: Trac Notifications
 * Description: For make.wordpress.org/core only, at the moment. Adds notifications endpoints for Trac, as well as notification management.
 * Author: Nacin
 * Version: 1.0
 */

class wporg_trac_notifications {

	protected $trac_subdomain;

	function __construct() {
		$this->set_trac( 'core' );
		add_filter( 'allowed_http_origins', array( $this, 'filter_allowed_http_origins' ) );
		add_action( 'template_redirect', array( $this, 'action_template_redirect' ) );
		add_shortcode( 'trac-notifications', array( $this, 'notification_settings_page' ) );
	}

	function set_trac( $trac ) {
		$this->trac_subdomain = $trac;
		if ( function_exists( 'add_db_table' ) ) {
			$tables = array( 'ticket', '_ticket_subs', '_notifications', 'ticket_change', 'component', 'milestone' );
			foreach ( $tables as $table ) {
				add_db_table( 'trac_' . $trac, $table );
			}
			$this->trac = $GLOBALS['wpdb'];
		}
	}

	function trac_url() {
		return 'https://' . $this->trac_subdomain . '.trac.wordpress.org';
	}

	function filter_allowed_http_origins( $origins ) {
		$origins[] = $this->trac_url();
		return $origins;
	}

	function ticket_link( $ticket ) {
		$status_res = $ticket->status;
		if ( $ticket->resolution ) {
			$status_res .= ': ' . $ticket->resolution;
		}
		return sprintf( '<a href="%s" class="%s ticket" title="%s">#%s</a>',
			$this->trac_url() . '/ticket/' . $ticket->id,
			$ticket->status,
			esc_attr( sprintf( "%s: %s (%s)", $ticket->type, $ticket->summary, $status_res ) ),
			$ticket->id );
	}

	function action_template_redirect() {
		if ( isset( $_POST['trac-ticket-sub'] ) ) {
			$this->trac_notifications_box_actions();
			exit;
		} elseif ( isset( $_POST['trac-ticket-subs'] ) ) {
			$this->trac_notifications_query_tickets();
			exit;
		} elseif ( isset( $_GET['trac-notifications'] ) ) {
			$this->trac_notifications_box_render();
			exit;
		}
	}

	function get_trac_ticket( $ticket_id ) {
		return $this->trac->get_row( $this->trac->prepare( "SELECT * FROM ticket WHERE id = %d", $ticket_id ) );
	}

	function get_trac_ticket_participants( $ticket_id ) {
		return $this->trac->get_col( $this->trac->prepare( "SELECT DISTINCT author FROM ticket_change WHERE ticket = %d", $ticket_id ) );
	}

	function get_trac_ticket_stars( $ticket_id ) {
		return $this->trac->get_col( $this->trac->prepare( "SELECT username FROM _ticket_subs WHERE ticket = %s AND status = 1", $ticket_id ) );
	}

	function get_trac_components() {
		return $this->trac->get_col( "SELECT name FROM component ORDER BY name ASC" );
	}

	function get_trac_milestones() {
		// Only shoe 3.8+, when this feature was launched.
		return $this->trac->get_results( "SELECT name, completed FROM milestone
			WHERE name NOT IN ('WordPress.org', '3.5.3', '3.6.2', '3.7.2') AND (completed = 0 OR completed >= 1386864000000000)
			ORDER BY (completed = 0) DESC, name DESC", OBJECT_K );
	}

	function get_trac_notifications_for_user( $username ) {
		$rows = $this->trac->get_results( $this->trac->prepare( "SELECT type, value FROM _notifications WHERE username = %s ORDER BY type ASC, value ASC", $username ) );
		$notifications = array( 'component' => array(), 'milestone' => array() );

		foreach ( $rows as $row ) {
			$notifications[ $row->type ][ $row->value ] = true;
		}
		return $notifications;
	}

	function get_trac_ticket_subscription_status_for_user( $ticket_id, $username ) {
		$status = $this->trac->get_var( $this->trac->prepare( "SELECT status FROM _ticket_subs WHERE username = %s AND ticket = %s", $username, $ticket_id ) );
		if ( null !== $status ) {
			$status = (int) $status;
		}
		return $status;
	}

	function get_trac_ticket_subscriptions_for_user( $username ) {
		return $this->trac->get_col( $this->trac->prepare( "SELECT ticket FROM _ticket_subs WHERE username = %s AND status = 1", $username ) );
	}

	function trac_notifications_box_actions() {
		send_origin_headers();

		if ( ! is_user_logged_in() ) {
			wp_send_json_error();
		}

		$username = wp_get_current_user()->user_login;

		$ticket_id = absint( $_POST['trac-ticket-sub'] );
		if ( ! $ticket_id ) {
			wp_send_json_error();
		}

		$ticket = $this->get_trac_ticket( $ticket_id );
		if ( ! $ticket ) {
			wp_send_json_error();
		}

		$action = $_POST['action'];
		if ( ! $action ) {
			wp_send_json_error();
		}

		switch ( $action ) {
			case 'subscribe' :
			case 'block' :
				$status = $action === 'subscribe' ? 1 : 0;
				$this->trac->delete( '_ticket_subs', array( 'username' => $username, 'ticket' => $ticket_id ) );
				$result = $this->trac->insert( '_ticket_subs', array( 'username' => $username, 'ticket' => $ticket_id, 'status' => $status ) );
				break;

			case 'unsubscribe' :
			case 'unblock' :
				$status = $action === 'unsubscribe' ? 1 : 0;
				$result = $this->trac->delete( '_ticket_subs', array( 'username' => $username, 'ticket' => $ticket_id, 'status' => $status ) );
				break;
		}

		if ( $result ) {
			wp_send_json_success();
		}

		wp_send_json_error();
	}

	function trac_notifications_query_tickets() {
		send_origin_headers();

		if ( ! is_user_logged_in() ) {
			exit;
		}
		$username = wp_get_current_user()->user_login;

		$queried_tickets = (array) $_POST['tickets'];
		if ( count( $queried_tickets ) > 100 ) {
			wp_send_json_error();
		}

		$subscribed_tickets = $this->get_trac_ticket_subscriptions_for_user( $username );
		if ( ! is_array( $subscribed_tickets ) ) {
			wp_send_json_error();
		}
		$tickets = array_intersect( $queried_tickets, $subscribed_tickets );
		$tickets = array_map( 'intval', array_values( $tickets ) );
		wp_send_json_success( array( 'tickets' => $tickets ) );
	}

	function trac_notifications_box_render() {
		send_origin_headers();

		if ( ! is_user_logged_in() ) {
			exit;
		}
		$username = wp_get_current_user()->user_login;

		$ticket_id = absint( $_GET['trac-notifications'] );
		if ( ! $ticket_id ) {
			exit;
		}
		$ticket = $this->get_trac_ticket( $ticket_id );
		if ( ! $ticket ) {
			exit;
		}

		$notifications = $this->get_trac_notifications_for_user( $username );

		$ticket_sub = $this->get_trac_ticket_subscription_status_for_user( $ticket_id, $username );

		$stars = $this->get_trac_ticket_stars( $ticket_id );
		$star_count = count( $stars );

		$reasons = array();

		if ( $username == $ticket->reporter ) {
			$reasons['reporter'] = 'you reported this ticket';
		}
		if ( $username == $ticket->owner ) {
			$reasons['owner'] = 'you own this ticket';
		}
		if ( ! empty( $notifications['component'][ $ticket->component ] ) ) {
			$reasons['component'] = sprintf( 'you subscribe to the %s component', $ticket->component );
		}
		if ( ! empty( $notifiations['milestone'][ $ticket->milestone ] ) ) {
			$reasons['milestone'] = sprintf( 'you subscribe to the %s milestone', $ticket->milestone );
		}

		if ( 1 === $ticket_sub ) {
			$class = 'subscribed';
		} else {
			if ( null === $ticket_sub && $reasons ) {
				$class = 'block';
			} elseif ( 0 === $ticket_sub ) {
				$class = 'blocked';
			} else {
				$class = '';
			}
		}
		if ( $reasons ) {
			$class .= ' receiving';
		}

		if ( $star_count === 0 || $star_count === 1 ) {
			$class .= ' count-' . $star_count;
		}
		ob_start();
		?>
	<div id="notifications" class="<?php echo $class; ?>">
		<fieldset>
			<legend>Notifications</legend>
				<p class="star-this-ticket">
					<a href="#" class="button button-large watching-ticket"><span class="dashicons dashicons-star-filled"></span> Watching ticket</a>
					<a href="#" class="button button-large watch-this-ticket"><span class="dashicons dashicons-star-empty"></span> Watch this ticket</a>
					<span class="num-stars"><span class="count"><?php echo $star_count; ?></span> <span class="count-1">star</span> <span class="count-many">stars</span></span>
					<div class="star-list">
				<?php
					foreach ( $stars as $follower ) :
						if ( $username === $follower ) {
							continue;
						}
						$follower = esc_attr( $follower );
					?>
						<a title="<?php echo $follower; ?>" href="//profiles.wordpress.org/<?php echo $follower; ?>">
							<img width="36" height="36" src="//wordpress.org/grav-redirect.php?user=<?php echo $follower; ?>&amp;s=36" />
						</a>
					<?php endforeach; ?>
					<a title="you" class="star-you" href="//profiles.wordpress.org/<?php echo esc_attr( $username ); ?>">
						<img width="36" height="36" src="//wordpress.org/grav-redirect.php?user=<?php echo esc_attr( $username ); ?>&amp;s=36" />
					</a>
					</div>
				</p>
				<p class="receiving-notifications">You are receiving notifications.</p>
			<?php if ( $reasons ) : ?>
				<p class="receiving-notifications-because">You are receiving notifications because <?php echo current( $reasons ); ?>. <a href="#" class="button button-small block-notifications">Block notifications</a></p>
			<?php endif ?>
				<p class="not-receiving-notifications">You do not receive notifications because you have blocked this ticket. <a href="#" class="button button-small unblock-notifications">Unblock</a></p>
		</fieldset>
	</div>
	<?php
		$this->ticket_notes( $ticket, $username );
		wp_send_json_success( array( 'notifications-box' => ob_get_clean() ) );
		exit;
	}

	function ticket_notes( $ticket, $username ) {
		if ( $username != $ticket->reporter ) {
			$previous_tickets = $this->trac->get_results( $this->trac->prepare( "SELECT id, summary, type, status, resolution
				FROM ticket WHERE reporter = %s AND id <= %d LIMIT 5", $ticket->reporter, $ticket->id ) );

			if ( count( $previous_tickets ) < 5 ) {
				$dashicon = '<span class="dashicons dashicons-welcome-learn-more"></span> ';
				$dashicon .= '<img width="36" height="36" src="//wordpress.org/grav-redirect.php?user=' . esc_attr( $ticket->reporter ) . '&amp;s=36" /> ';
			}

			if ( 1 == count( $previous_tickets ) ) {
				$previous_comments = $this->trac->get_var( $this->trac->prepare( "SELECT ticket FROM ticket_change
					WHERE field = 'comment' AND author = %s AND ticket <> %d LIMIT 1", $ticket->reporter, $ticket->id ) );

				echo '<p class="ticket-note note-new-reporter">' . $dashicon . '<strong>Make sure ' . $ticket->reporter . ' receives a warm welcome</strong> &mdash; ';

				if ( $previous_comments ) {
					echo 'they&#8217;ve commented before, but it&#8127;s their first bug report!</p>';
				} else {
					echo 'it&#8127;s their first bug report!</p>';
				}

			} elseif ( count( $previous_tickets ) < 5 ) {
				$mapping = array( 2 => 'second', 3 => 'third', 4 => 'fourth' );

				echo '<p class="ticket-note note-new-reporter">' . $dashicon .  '<strong>This is only ' . $ticket->reporter . '&#8217;s ' . $mapping[ count( $previous_tickets ) ] . ' ticket!</strong> Previously:';

				foreach ( $previous_tickets as $t ) {
					if ( $t->id != $ticket->id ) {
						echo ' ' . $this->ticket_link( $t );
					}
				}
				echo '.';
			}
		}
	}

	function notification_settings_page() {
		if ( ! is_user_logged_in() ) {
			return 'Please log in to save your notification preferences.';
		}

		ob_start();
		$components = $this->get_trac_components();
		$milestones = $this->get_trac_milestones();

		$username = wp_get_current_user()->user_login;
		$notifications = $this->get_trac_notifications_for_user( $username );

		if ( $_POST && isset( $_POST['trac-nonce'] ) ) {
			check_admin_referer( 'save-trac-notifications', 'trac-nonce' );

			foreach ( array( 'milestone', 'component' ) as $type ) {
				foreach ( $_POST[ $type ] as $value => $on ) {
					if ( empty( $notifications[ $type ][ $value ] ) ) {
						$this->trac->insert( '_notifications', compact( 'username', 'type', 'value' ) );
						$notifications[ $type ][ $value ] = true;
					}
				}

				foreach ( $notifications[ $type ] as $value => $on ) {
					if ( empty( $_POST[ $type ][ $value ] ) ) {
						$this->trac->delete( '_notifications', compact( 'username', 'type', 'value' ) );
						unset( $notifications[ $type ][ $value ] );
					}
				}
			}
		}
		?>

		<style>
		#components, #milestones, p.save-changes {
			clear: both;
		}
		#milestones, p.save-changes {
			padding-top: 1em;
		}
		#components li,
		#milestones li {
			float: left;
			width: 25%;
			list-style: none;
		}	
		.completed-milestone {
			display: none !important;
		}
		.completed-milestone.checked,
		#milestones.show-completed-milestones .completed-milestone {
			display: list-item !important;
		}
		</style>
		<script>
		jQuery(document).ready( function($) {
			$('#show-completed').on('click', 'a', function() {
				$('#show-completed').hide();
				$('#milestones').addClass( 'show-completed-milestones' );
				return false;
			});
			$('p.select-all').on('click', 'a', function() {
				$('#components').find('input[type=checkbox]').prop('checked', $(this).data('action') === 'select-all');
				return false;
			});
		});
		</script>
		<?php
		echo '<form method="post" action="">';
		wp_nonce_field( 'save-trac-notifications', 'trac-nonce', false );
		echo '<div id="components">';
		echo '<h3>Components</h3>';
		echo '<p class="select-all"><a href="#" data-action="select-all">select all</a> &bull; <a href="#" data-action="clear-all">clear all</a></p>';
		echo '<ul>';
		foreach ( $components as $component ) {
			$checked = checked( ! empty( $notifications['component'][ $component ] ), true, false );
			echo '<li><label><input type="checkbox" ' . $checked . 'name="component[' . esc_attr( $component ) . ']" /> ' . $component . '</label></li>';
		}
		echo '</ul>';
		echo '</div>';
		echo '<div id="milestones">';
		echo '<h3>Milestones</h3>';
		echo '<ul>';
		foreach ( $milestones as $milestone ) {
			$checked = checked( ! empty( $notifications['milestone'][ $milestone->name ] ), true, false );
			$class = '';
			if ( ! empty( $milestone->completed ) ) {
				$class = 'completed-milestone';
				if ( $checked ) {
					$class .= ' checked';
				}
				$class = ' class="' . $class . '"';
			}
			echo  '<li' . $class . '><label><input type="checkbox" ' . $checked . 'name="milestone[' . esc_attr( $milestone->name ) . ']" /> ' . $milestone->name . '</label></li>';
		}
		echo '<li id="show-completed"><a href="#">Show recently completed&hellip;</a></li>';
		echo '</ul>';
		echo '</div>';
		echo '<p class="save-changes"><input type="submit" value="Save Changes" /></p>';
		echo '</form>';
		return ob_get_clean();
	}
}
$wporg_trac_notifications = new wporg_trac_notifications;

