<?php
/**
 * Category card. Same look as the product card (it reuses that block's CSS).
 * The Terms Query around it passes in which category to show as
 * $block->context['termId'].
 */

$term = get_term( $block->context['termId'] ?? 0, $block->context['taxonomy'] ?? 'product_category' );
if ( ! $term || is_wp_error( $term ) ) {
	return;
}

$name  = html_entity_decode( $term->name, ENT_QUOTES, 'UTF-8' );
$short = gvs_truncate( $name ); // Long names are cut, with the full name as a tooltip.
$count = (int) $term->count;
$image = (int) get_term_meta( $term->term_id, 'image_id', true ); // Set on the category's edit screen.
?>
<a href="<?php echo esc_url( get_term_link( $term ) ); ?>" <?php echo get_block_wrapper_attributes( array( 'class' => 'inv-card' ) ); ?>>
	<?php if ( $image ) : ?>
		<div class="inv-img"><?php echo wp_get_attachment_image( $image, 'medium_large' ); ?></div>
	<?php else : ?>
		<div class="inv-img ph" data-label="<?php echo esc_attr( $short ); ?>"></div>
	<?php endif; ?>
	<div class="inv-body">
		<div class="inv-name"<?php echo $short !== $name ? ' title="' . esc_attr( $name ) . '"' : ''; ?>><?php echo esc_html( $short ); ?></div>
		<p class="inv-desc"><?php echo esc_html( wp_strip_all_tags( $term->description ) ); ?></p>
		<div class="inv-foot">
			<div class="inv-status<?php echo $count > 0 ? '' : ' sold'; ?>">
				<?php echo esc_html( $count > 0 ? sprintf( _n( '%d product', '%d products', $count ), $count ) : 'No products yet' ); ?>
			</div>
			<span class="inv-more">View Products →</span><?php // A span, not a link: the whole card is already the link. ?>
		</div>
	</div>
</a>
