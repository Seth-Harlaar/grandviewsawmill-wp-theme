<?php
/**
 * Product card, based on the Demo's inventory cards.
 * WordPress runs this file each time the block appears. The Query Loop
 * around it passes in which product to show as $block->context['postId'].
 */

$post_id  = $block->context['postId'] ?? get_the_ID();
$price    = get_post_meta( $post_id, 'price', true );
$quantity = (int) get_post_meta( $post_id, 'quantity', true );
$unit     = get_post_meta( $post_id, 'unit', true );
$title    = html_entity_decode( wp_strip_all_tags( get_the_title( $post_id ) ), ENT_QUOTES, 'UTF-8' );

// Long titles are cut to this many characters, with the full title as a tooltip.
$max_title = 40;
$short     = mb_strlen( $title ) > $max_title ? rtrim( mb_substr( $title, 0, $max_title - 1 ) ) . '…' : $title;
?>
<a href="<?php echo esc_url( get_permalink( $post_id ) ); ?>" <?php echo get_block_wrapper_attributes( array( 'class' => 'inv-card' ) ); ?>>
	<div class="inv-img ph" data-label="<?php echo esc_attr( $short ); ?>"></div>
	<div class="inv-body">
		<div class="inv-name"<?php echo $short !== $title ? ' title="' . esc_attr( $title ) . '"' : ''; ?>><?php echo esc_html( $short ); ?></div>
		<p class="inv-desc"><?php echo esc_html( wp_strip_all_tags( get_the_excerpt( $post_id ) ) ); ?></p>
		<div class="inv-foot">
			<div class="inv-price">
				<?php if ( '' !== $price ) : ?>
					<?php echo esc_html( gvs_format_price( $price ) ); ?>
					<?php if ( $unit ) : ?>
						<small>/ <?php echo esc_html( $unit ); ?></small>
					<?php endif; ?>
				<?php endif; ?>
			</div>
			<div class="inv-status<?php echo $quantity > 0 ? '' : ' sold'; ?>">
				<?php echo $quantity > 0 ? esc_html( "$quantity in stock" ) : 'Sold out'; ?>
			</div>
		</div>
	</div>
</a>
