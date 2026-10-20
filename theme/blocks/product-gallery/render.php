<?php
/**
 * Product images on the detail page, as a carousel: swipe or use the
 * arrows, dots underneath show which image you're on. No looping.
 * view.js wires up the arrows and dots. One image = no controls.
 */

$post_id = $block->context['postId'] ?? get_the_ID();
$images  = gvs_product_images( $post_id );

if ( ! $images ) : ?>
	<div <?php echo get_block_wrapper_attributes( array( 'class' => 'product-image ph' ) ); ?> data-label="Product photo"></div>
	<?php
	return;
endif;
?>
<div <?php echo get_block_wrapper_attributes( array( 'class' => 'product-gallery' ) ); ?>>
	<div class="pg-stage">
		<div class="pg-track">
			<?php foreach ( $images as $i => $id ) : ?>
				<div class="pg-slide product-image">
					<?php echo wp_get_attachment_image( $id, 'large', false, array( 'loading' => $i ? 'lazy' : 'eager' ) ); ?>
				</div>
			<?php endforeach; ?>
		</div>

		<?php if ( count( $images ) > 1 ) : ?>
			<button type="button" class="pg-arrow pg-prev" aria-label="Previous image" disabled>←</button>
			<button type="button" class="pg-arrow pg-next" aria-label="Next image">→</button>
		<?php endif; ?>
	</div>

	<?php if ( count( $images ) > 1 ) : ?>
		<div class="pg-dots">
			<?php foreach ( $images as $i => $id ) : ?>
				<button type="button" class="pg-dot<?php echo $i ? '' : ' is-active'; ?>" aria-label="<?php echo esc_attr( 'Image ' . ( $i + 1 ) ); ?>"></button>
			<?php endforeach; ?>
		</div>
	<?php endif; ?>
</div>
