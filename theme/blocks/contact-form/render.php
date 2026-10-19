<?php
/**
 * Contact form. Posts to admin-post.php, which runs gvs_handle_contact()
 * in inc/contact.php. view.js sends it without a page reload; without
 * JavaScript the visitor is sent back here with ?sent=1.
 */

if ( isset( $_GET['sent'] ) ) : ?>
	<div <?php echo get_block_wrapper_attributes( array( 'class' => 'form-success' ) ); ?>>
		<div class="label">Message received</div>
		Thanks, we'll get back to you as soon as we can.
	</div>
	<?php
	return;
endif;
?>
<form <?php echo get_block_wrapper_attributes( array( 'class' => 'contact-form' ) ); ?> method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
	<input type="hidden" name="action" value="gvs_contact">
	<?php wp_nonce_field( 'gvs_contact', 'gvs_contact_nonce' ); ?>

	<?php if ( isset( $_GET['contact_error'] ) ) : ?>
		<div class="form-err">Please fill in your name, a valid email and a few words about what you need.</div>
	<?php endif; ?>

	<div class="form-row split">
		<div class="form-row">
			<label for="gvs-name">Your Name<span class="req">*</span></label>
			<input id="gvs-name" name="name" required placeholder="Jane Doe">
		</div>
		<div class="form-row">
			<label for="gvs-phone">Phone</label>
			<input id="gvs-phone" name="phone" type="tel" placeholder="(519) 000-0000">
		</div>
	</div>

	<div class="form-row">
		<label for="gvs-email">Email<span class="req">*</span></label>
		<input id="gvs-email" name="email" type="email" required placeholder="you@somewhere.com">
	</div>

	<div class="form-row choose-svc">
		<label>What do you need? (pick any)</label>
		<div class="choose-svc-grid">
			<?php foreach ( gvs_contact_services() as $gvs_service ) : ?>
				<label class="svc-option">
					<input type="checkbox" name="services[]" value="<?php echo esc_attr( $gvs_service ); ?>">
					<span><?php echo esc_html( $gvs_service ); ?></span>
				</label>
			<?php endforeach; ?>
		</div>
	</div>

	<div class="form-row">
		<label for="gvs-message">Details<span class="req">*</span></label>
		<textarea id="gvs-message" name="message" required minlength="12" placeholder="e.g. One white oak log, ~24&quot; diameter, 10 ft long. Looking for 8/4 slabs for a tabletop."></textarea>
	</div>

	<button type="submit" class="wp-element-button contact-submit">Send Message</button>
</form>
