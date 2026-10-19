<?php
/**
 * Price, unit and stock for the product being viewed.
 */

$post_id  = $block->context['postId'] ?? get_the_ID();
$price    = get_post_meta( $post_id, 'price', true );
$quantity = (int) get_post_meta( $post_id, 'quantity', true );
$unit     = get_post_meta( $post_id, 'unit', true );
?>
<div <?php echo get_block_wrapper_attributes( array( 'class' => 'product-meta' ) ); ?>>
	<?php if ( '' !== $price ) : ?>
		<div class="product-price">
			<?php echo esc_html( gvs_format_price( $price ) ); ?>
			<?php if ( $unit ) : ?>
				<small>/ <?php echo esc_html( $unit ); ?></small>
			<?php endif; ?>
		</div>
	<?php endif; ?>
	<div class="product-stock<?php echo $quantity > 0 ? '' : ' sold'; ?>">
		<?php echo $quantity > 0 ? esc_html( "$quantity in stock" ) : 'Sold out'; ?>
	</div>
</div>
