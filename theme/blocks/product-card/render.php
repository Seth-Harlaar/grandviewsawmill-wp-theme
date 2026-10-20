<?php
/**
 * Product card, based on the Demo's inventory cards.
 * WordPress runs this file each time the block appears. The Query Loop
 * around it passes in which product to show as $block->context['postId'].
 */

$post_id  = $block->context['postId'] ?? get_the_ID();
$rows  = gvs_product_prices( $post_id );
$only  = 1 === count( $rows ) ? $rows[0] : null; // Price and stock are only shown for a single price row.
$stock = $only ? gvs_format_stock( $only['quantity'] ) : '';
$title = html_entity_decode( wp_strip_all_tags( get_the_title( $post_id ) ), ENT_QUOTES, 'UTF-8' );
$short = gvs_truncate( $title ); // Long titles are cut, with the full title as a tooltip.
?>
<a href="<?php echo esc_url( get_permalink( $post_id ) ); ?>" <?php echo get_block_wrapper_attributes( array( 'class' => 'inv-card' ) ); ?>>
	<div class="inv-img ph" data-label="<?php echo esc_attr( $short ); ?>"></div>
	<div class="inv-body">
		<div class="inv-name"<?php echo $short !== $title ? ' title="' . esc_attr( $title ) . '"' : ''; ?>><?php echo esc_html( $short ); ?></div>
		<p class="inv-desc"><?php echo esc_html( wp_strip_all_tags( get_the_excerpt( $post_id ) ) ); ?></p>
		<div class="inv-foot">
			<div class="inv-price">
				<?php if ( count( $rows ) > 1 ) : ?>
					Price varies
				<?php elseif ( $only && '' !== $only['price'] ) : ?>
					<?php echo esc_html( gvs_format_price( $only['price'] ) ); ?>
					<?php if ( '' !== $only['unit'] ) : ?>
						<small>/ <?php echo esc_html( $only['unit'] ); ?></small>
					<?php endif; ?>
				<?php endif; ?>
			</div>
			<div class="inv-status<?php echo 'Sold out' === $stock ? ' sold' : ''; ?>"><?php echo esc_html( $stock ); ?></div>
		</div>
	</div>
</a>
