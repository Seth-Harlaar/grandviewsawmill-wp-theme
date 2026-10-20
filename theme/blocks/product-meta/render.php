<?php
/**
 * Price table for the product being viewed: one row per price, cheapest first.
 */

$post_id = $block->context['postId'] ?? get_the_ID();
$rows    = gvs_product_prices( $post_id );

if ( ! $rows ) {
	return;
}
?>
<table <?php echo get_block_wrapper_attributes( array( 'class' => 'product-prices' ) ); ?>>
	<tbody>
		<?php foreach ( $rows as $row ) :
			$stock = gvs_format_stock( $row['quantity'] );
			?>
			<tr>
				<td class="pp-item">
					<?php echo esc_html( $row['title'] ); ?>
					<?php if ( '' !== $row['desc'] ) : ?>
						<small><?php echo esc_html( $row['desc'] ); ?></small>
					<?php endif; ?>
				</td>
				<td class="pp-price">
					<?php if ( '' !== $row['price'] ) : ?>
						<?php echo esc_html( gvs_format_price( $row['price'] ) ); ?>
						<?php if ( '' !== $row['unit'] ) : ?>
							<small>/ <?php echo esc_html( $row['unit'] ); ?></small>
						<?php endif; ?>
					<?php endif; ?>
				</td>
				<td class="pp-stock<?php echo 'Sold out' === $stock ? ' sold' : ''; ?>"><?php echo esc_html( $stock ); ?></td>
			</tr>
		<?php endforeach; ?>
	</tbody>
</table>
