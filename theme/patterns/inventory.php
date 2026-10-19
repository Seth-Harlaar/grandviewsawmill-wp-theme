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

<!-- wp:terms-query {"termQuery":{"perPage":3,"taxonomy":"product_category","order":"asc","orderBy":"name","include":[],"hideEmpty":false,"showNested":false,"inherit":false}} -->
<div class="wp-block-terms-query">
<!-- wp:term-template {"className":"inv-grid"} -->
<!-- wp:grandview/category-card /-->
<!-- /wp:term-template -->
</div>
<!-- /wp:terms-query -->

<!-- wp:buttons {"className":"inventory-more"} -->
<div class="wp-block-buttons inventory-more">
<!-- wp:button {"className":"btn-arrow"} -->
<div class="wp-block-button btn-arrow"><a class="wp-block-button__link wp-element-button" href="<?php echo esc_url( home_url( '/categories/' ) ); ?>">View All Inventory</a></div>
<!-- /wp:button -->
</div>
<!-- /wp:buttons -->
</div>
<!-- /wp:group -->
</section>
<!-- /wp:group -->
