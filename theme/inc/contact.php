<?php
/**
 * Contact form submissions. Each one is saved as a "Message" post, which
 * shows up under Messages in the admin. Not public on the site.
 */

function gvs_contact_services() {
	return array( 'Purchase Lumber', 'Custom milling', 'Just curious', 'Other' );
}

add_action( 'init', function () {
	register_post_type( 'message', array(
		'label'     => 'Messages',
		'public'    => false,
		'show_ui'   => true, // Show in the admin even though it's not public.
		'menu_icon' => 'dashicons-email',
		'supports'  => array( 'title', 'editor' ),
	) );
} );

// The form posts to admin-post.php with action=gvs_contact. "nopriv" is for
// visitors who aren't logged in; the other is for logged-in users.
add_action( 'admin_post_nopriv_gvs_contact', 'gvs_handle_contact' );
add_action( 'admin_post_gvs_contact', 'gvs_handle_contact' );

function gvs_handle_contact() {
	$back = home_url( '/' );

	if ( ! isset( $_POST['gvs_contact_nonce'] ) || ! wp_verify_nonce( $_POST['gvs_contact_nonce'], 'gvs_contact' ) ) {
		gvs_contact_reply( false, add_query_arg( 'contact_error', 1, $back ) );
	}

	$name     = sanitize_text_field( wp_unslash( $_POST['name'] ?? '' ) );
	$email    = sanitize_email( wp_unslash( $_POST['email'] ?? '' ) );
	$phone    = sanitize_text_field( wp_unslash( $_POST['phone'] ?? '' ) );
	$message  = sanitize_textarea_field( wp_unslash( $_POST['message'] ?? '' ) );
	$services = array_intersect( (array) ( $_POST['services'] ?? array() ), gvs_contact_services() );

	if ( '' === $name || ! is_email( $email ) || mb_strlen( $message ) < 12 ) {
		gvs_contact_reply( false, add_query_arg( 'contact_error', 1, $back ) );
	}

	$post_id = wp_insert_post( array(
		'post_type'    => 'message',
		'post_status'  => 'private',
		'post_title'   => $name,
		'post_content' => $message,
	) );
	update_post_meta( $post_id, 'email', $email );
	update_post_meta( $post_id, 'phone', $phone );
	update_post_meta( $post_id, 'services', implode( ', ', $services ) );

	gvs_contact_reply( true, add_query_arg( 'sent', 1, $back ) );
}

// Sent from the block's view.js (ajax=1): reply with JSON so the page stays put.
// Otherwise (JavaScript off): redirect back to the contact section.
function gvs_contact_reply( $ok, $url ) {
	if ( ! empty( $_POST['ajax'] ) ) {
		$ok ? wp_send_json_success() : wp_send_json_error();
	}
	wp_safe_redirect( $url . '#contact' );
	exit;
}

// Read-only box on the message's admin screen showing the contact details.
add_action( 'add_meta_boxes', function () {
	add_meta_box( 'gvs_message_details', 'Contact Details', function ( $post ) {
		foreach ( array( 'email' => 'Email', 'phone' => 'Phone', 'services' => 'Needs' ) as $key => $label ) {
			printf( '<p><strong>%s:</strong> %s</p>', esc_html( $label ), esc_html( get_post_meta( $post->ID, $key, true ) ) );
		}
	}, 'message', 'side' );
} );
