<?php
/**
 * Back link. On the categories list page it goes to the home page.
 * On a product page it goes to the product's category (the first one,
 * if it has several). Everywhere else, or if the product has no category,
 * it goes to the list of all categories.
 */

$url  = home_url( '/categories/' );
$text = 'Return to All Product Categories';

if ( is_page( 'categories' ) ) {
	$url  = home_url( '/' );
	$text = 'Home Page';
} elseif ( is_singular( 'product' ) ) {
	$terms = get_the_terms( get_the_ID(), 'product_category' );
	if ( $terms && ! is_wp_error( $terms ) ) {
		$url  = get_term_link( $terms[0] );
		$text = 'Back to ' . html_entity_decode( $terms[0]->name, ENT_QUOTES, 'UTF-8' );
	}
}
?>
<a href="<?php echo esc_url( $url ); ?>" <?php echo get_block_wrapper_attributes( array( 'class' => 'back-link' ) ); ?>>← <?php echo esc_html( $text ); ?></a>
