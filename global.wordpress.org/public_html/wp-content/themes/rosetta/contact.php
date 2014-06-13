<?php
/*
Template Name: Contact Page
*/


function rosetta_set_sender( &$phpmailer ) {
	$phpmailer->Sender = $_POST['your_email'];
}

?>
<?php get_header(); ?>
<div class="wrapper">
	<div class="section">
    <?php if (have_posts()) : while (have_posts()) : the_post(); ?>

<?php
if ($_POST['submit']) {

// Check values
$error = $your_name = $blog_name = $your_email = $blog_url = $message = false;
if ('' == $_POST['your_name']) {
	$your_name = true;
	$error = true;
}

if (!validate_email($_POST['your_email'])) {
	$your_email = true;
	$error = true;
}

if ('' == $_POST['message']) {
	$blog_description = true;
	$error = true;
}

if ('' == $_POST['subject']) {
	$subject = true;
	$error = true;
}

if ($error) {
?>
<h3 id="return"><?php _e('Error', 'rosetta'); ?></h3>
<p class="error"><?php _e('There seems to have been a problem with the information you entered. Please fix the field indicated and resubmit.', 'rosetta'); ?></p>
<form id="contactme" method="post" action="/contact/#return">
  <table id="form">
<?php if ($your_name) { ?>
    <tr class="error">
		<td class="label"><label for="your_name"><?php _e('Your Name:', 'rosetta'); ?> </label></td>
		<td><span><input name="your_name" type="text" id="your_name" value="<?php echo esc_attr( $_POST['your_name'], 1 ); ?>" /></span>
		<?php _e('Let us know your name.', 'rosetta'); ?></td>
    </tr>
<?php } else { ?>
    <tr>
	<td class="label"><label for="your_name"><?php _e('Your Name:', 'rosetta'); ?></label></td>
		<td><span><input name="your_name" type="text" id="your_name" value="<?php echo esc_attr( $_POST['your_name'], 1 ); ?>" /></span></td>
    </tr>
<?php } ?>

<?php if ($your_email) { ?>
    <tr class="error">
	<td class="label"><label for="your_email"><?php _e('Your Email:', 'rosetta'); ?></label></td>
		<td><span><input name="your_email" type="text" id="your_email" value="<?php echo esc_attr( $_POST['your_email'], 1 ); ?>" /></span>
<?php _e('Your email address did not appear to be valid. Please check it.', 'rosetta'); ?></td>
    </tr>
<?php } else { ?>
    <tr>
		<td class="label"><label for="your_email"><?php _e('Your Email:', 'rosetta'); ?></label></td>
		<td><span><input name="your_email" type="text" id="your_email" value="<?php echo esc_attr( $_POST['your_email'], 1 ); ?>" /></span></td>
    </tr>
<?php } ?>

    <tr>
	<td class="label"><label for="blog_url"><?php _e('URI of your blog:', 'rosetta'); ?></label></td>
		<td><span><input name="blog_url" type="text" id="blog_url" value="<?php echo esc_attr( $_POST['blog_url'], 1 ); ?>" /></span></td>
    </tr>
<?php if ($subject) { ?>
    <tr class="error">
		<td class="label"><label for="subject"><?php _e('What&rsquo;s this about?', 'rosetta'); ?></label></td>
		<td><span><input name="subject" type="text" id="subject" value="<?php echo esc_attr( $_POST['subject'], 1 ); ?>" /></span> <?php _e('Write something!', 'rosetta'); ?></td>
    </tr>
<?php } else { ?>
    <tr>
		<td class="label"><label for="subject"><?php _e('What&rsquo;s this about?', 'rosetta'); ?></label></td>
		<td><span><input name="subject" type="text" id="subject" value="<?php echo esc_attr( $_POST['subject'], 1 ); ?>" /></span></td>
    </tr>
<?php } ?>

<?php if ($blog_description) { ?>
    <tr class="error">
	<td class="label"><label for="message"><?php _e('Your Message:', 'rosetta'); ?></label></td>
		<td><span class="message"><textarea name="message" id="message"><?php echo esc_textarea( $_POST['message'], 1 ); ?></textarea></span>
<?php _e('Say something!', 'rosetta'); ?></td>
    </tr>
<?php } else { ?>
    <tr>
	<td class="label"><label for="message"><?php _e('Your Message:', 'rosetta'); ?></label></td>
		<td><span class="message"><textarea name="message" id="message"><?php echo esc_textarea( $_POST['message'], 1 ); ?></textarea></span></td>
    </tr>
<?php } ?>
	<tr class="submit">
		<td class="label"></td>
		<td><input type="submit" name="submit" value="<?php esc_attr_e('Submit Form Again', 'rosetta'); ?>" /></td>
	</tr>
  </table>
</form>

<?php } else { // If all the info is good

// Akismet checking
	$comment['user_ip']    = preg_replace( '/[^0-9., ]/', '', $_SERVER['REMOTE_ADDR'] );
	$comment['user_agent'] = $_SERVER['HTTP_USER_AGENT'];
	$comment['referrer']   = $_SERVER['HTTP_REFERER'];
	$comment['blog']       = home_url();
	$comment['comment_type'] = 'contact_form';
	$comment['comment_author'] = '';
	$comment['comment_author_email'] = $_POST['your_email'];
	$comment['comment_author_url'] = $_POST['blog_url'];
	$comment['comment_content'] = stripslashes($_POST['message']);
	$query_string = '';
	foreach ( $comment as $key => $data )
		$query_string .= $key . '=' . urlencode( stripslashes($data) ) . '&';
	$response = akismet_http_post($query_string, $akismet_api_host, '/1.1/comment-check', $akismet_api_port);
	if ( 'true' == $response[1] )
		die();
// end Akismet checking



//sanitization
	$message_data = array();
	$message_data['ip'] = preg_replace( '/[^0-9., ]/', '', $_SERVER['REMOTE_ADDR'] );
	$message_data['name'] = sanitize_text_field( $_POST['your_name'] );
	$message_data['email'] = sanitize_email( $_POST['your_email'] );
	$message_data['blog_url'] = esc_url_raw( $_POST['blog_url'] );
	$message_data['subject'] = sanitize_text_field( $_POST['subject'] );
	$message_data['message'] = wp_kses( stripslashes( $_POST['message'] ), array() );

// Let's send an email
$message = $message_data['message'] . '
--
Name: ' . $message_data['name'] . '
Email: ' . $message_data['email'] . '
Blog URI: ' . $message_data['blog_url'] . '
IP Address: ' . $message_data['ip'] . '
Browser: ' . sanitize_text_field( $_SERVER['HTTP_USER_AGENT'] ) . '
Sent From: ' . esc_url_raw( $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'] );

$headers = array();
$headers[] = 'From: ' . $message_data['name'] . ' <' . $message_data['email'].'>';
$headers[] = 'Return-Path: '. $message_data['email'];

add_action('phpmailer_init', 'rosetta_set_sender');
wp_mail(get_option('admin_email'), '[wordpress.org] ' . $message_data['subject'], $message, implode("\r\n", $headers));
remove_action('phpmailer_init', 'rosetta_set_sender');
?>
<div id="return">
<h3><?php _e('Submitted!', 'rosetta'); ?></h3>
<p><strong><?php _e('Thank you!', 'rosetta'); ?></strong></p>
</div>
<?php } ?>
<?php } else { ?>

<h3><?php _e('Contact', 'rosetta'); ?></h3>

<?php if ( "The contents of this page are filled automatically, you don't need to spend time here. Just change the title." == get_the_content() ) : ?>
	<p><?php _e('You can contact translators and this site administrators via this form:', 'rosetta'); ?></p>
	<p><?php /* translators: feel free to add links to places, where one can get support in your language. */ _e('<strong>Please, do not post support requests here!</strong> They will probably be ignored.', 'rosetta');?></p>
<?php else: ?>
	<?php the_content(); ?>
<?php endif; ?>

<form id="contactme" method="post" action="/contact/#return">
  <table id="form">
    <tr>
	<td class="label"><label for="your_name"><?php _e('Your Name:', 'rosetta'); ?></label> <?php _e('(required)', 'rosetta'); ?></td>
		<td><span><input name="your_name" type="text" id="your_name" /></span></td>
    </tr>
    <tr>
	<td class="label"><label for="your_email"><?php _e('Your Email:', 'rosetta'); ?></label> <?php _e('(required)', 'rosetta'); ?></td>
		<td><span><input name="your_email" type="text" id="your_email" /></span></td>
    </tr>
    <tr>
	<td class="label"><label for="blog_url"><?php _e('URI of your blog:', 'rosetta'); ?></label></td>
		<td><span><input name="blog_url" type="text" id="blog_url" /></span></td>
    </tr>
    <tr>
	<td class="label"><label for="subject"><?php _e('What&rsquo;s this about?', 'rosetta'); ?></label> <?php _e('(required)', 'rosetta'); ?></td>
		<td><span><input name="subject" type="text" id="subject" /></span></td>
    </tr>
    <tr>
	<td class="label"><label for="message"><?php _e('Tell us something:', 'rosetta'); ?></label> <?php _e('(required)', 'rosetta'); ?></td>
		<td><span class="message"><textarea name="message" id="message"></textarea></span></td>
    </tr>
	<tr class="submit">
		<td class="label"></td>
		<td><input type="submit" name="submit" value="<?php esc_attr_e('Submit Contact Form', 'rosetta'); ?>" /></td>
	</tr>
  </table>
</form>
<?php } ?>

			</div>
		</div>
	  <?php endwhile; endif; ?>
	</div>

	</div><!-- .section  -->
</div><!-- .wrapper -->
<script type="text/javascript">
var your_name = document.getElementById('your_name');
if (your_name) your_name.focus();
</script>
<?php get_footer(); ?>
