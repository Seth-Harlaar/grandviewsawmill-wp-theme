<?php
/**
 * Title: Inventory
 * Slug: grandview/inventory
 * Inserter: no
 */
?>
<!-- wp:group {"tagName":"section","className":"section section-kraft inventory","anchor":"inventory"} -->
<section id="inventory" class="wp-block-group section section-kraft inventory">
<!-- wp:group {"className":"shell"} -->
<div class="wp-block-group shell">
<!-- wp:group {"className":"section-hd"} -->
<div class="wp-block-group section-hd">
<!-- wp:paragraph {"className":"label label-accent"} -->
<p class="label label-accent">Available Now</p>
<!-- /wp:paragraph -->

<!-- wp:heading -->
<h2 class="wp-block-heading">Current inventory.</h2>
<!-- /wp:heading -->

<!-- wp:paragraph {"className":"section-intro"} -->
<p class="section-intro">Standing stock, updated weekly. All slabs are rough-sawn unless noted. Surfacing and finishing available on request; any slab can be held with a deposit.</p>
<!-- /wp:paragraph -->
</div>
<!-- /wp:group -->

<!-- wp:query {"queryId":2,"query":{"perPage":3,"postType":"product","order":"desc","orderBy":"date","inherit":false}} -->
<div class="wp-block-query">
<!-- wp:post-template {"className":"inv-grid"} -->
<!-- wp:grandview/product-card /-->
<!-- /wp:post-template -->

<!-- wp:query-no-results -->
<!-- wp:paragraph -->
<p>No products yet.</p>
<!-- /wp:paragraph -->
<!-- /wp:query-no-results -->
</div>
<!-- /wp:query -->

<!-- wp:buttons {"className":"inventory-more"} -->
<div class="wp-block-buttons inventory-more">
<!-- wp:button {"className":"btn-arrow"} -->
<div class="wp-block-button btn-arrow"><a class="wp-block-button__link wp-element-button" href="<?php echo esc_url( get_post_type_archive_link( 'product' ) ); ?>">View All Inventory</a></div>
<!-- /wp:button -->
</div>
<!-- /wp:buttons -->
</div>
<!-- /wp:group -->
</section>
<!-- /wp:group -->
