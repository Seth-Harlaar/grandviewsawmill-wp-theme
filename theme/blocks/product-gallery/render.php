<?php
/**
 * Product images on the detail page. For now just the first image
 * (or the placeholder); the carousel comes later.
 */

$post_id = $block->context['postId'] ?? get_the_ID();
$images  = gvs_product_images( $post_id );
?>
<?php if ( $images ) : ?>
	<div <?php echo get_block_wrapper_attributes( array( 'class' => 'product-image' ) ); ?>><?php echo wp_get_attachment_image( $images[0], 'large' ); ?></div>
<?php else : ?>
	<div <?php echo get_block_wrapper_attributes( array( 'class' => 'product-image ph' ) ); ?> data-label="Product photo"></div>
<?php endif; ?>
