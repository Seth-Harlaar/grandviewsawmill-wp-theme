<?php
/**
 * Title: Header
 * Slug: grandview/header
 * Inserter: no
 *
 * A pattern (instead of plain HTML in parts/header.html) so it can use PHP
 * for the logo path and home links.
 */
?>
<!-- wp:group {"className":"nav"} -->
<div class="wp-block-group nav">
<!-- wp:group {"className":"nav-inner"} -->
<div class="wp-block-group nav-inner">
<!-- wp:html -->
<a class="brand" href="<?php echo esc_url( home_url( '/' ) ); ?>">
	<img class="brand-img" src="<?php echo esc_url( get_theme_file_uri( 'assets/images/logo-mark.png' ) ); ?>" alt="">
	<span class="brand-word">Grandview <small>Sawmill</small></span>
</a>
<!-- /wp:html -->

<!-- wp:navigation {"overlayMenu":"never","className":"nav-links"} -->
<!-- wp:navigation-link {"label":"About","url":"<?php echo esc_url( home_url( '/#about' ) ); ?>","kind":"custom"} /-->
<!-- wp:navigation-link {"label":"Services","url":"<?php echo esc_url( home_url( '/#services' ) ); ?>","kind":"custom"} /-->
<!-- wp:navigation-submenu {"label":"Products","url":"<?php echo esc_url( home_url( '/categories/' ) ); ?>","kind":"custom"} -->
<?php
// One dropdown link per product category, looked up on each page load.
$gvs_categories = get_terms( array( 'taxonomy' => 'product_category', 'hide_empty' => false ) );
foreach ( is_wp_error( $gvs_categories ) ? array() : $gvs_categories as $gvs_category ) {
	$attrs = array(
		'label' => $gvs_category->name,
		'url'   => get_term_link( $gvs_category ),
		'kind'  => 'custom',
	);
	echo '<!-- wp:navigation-link ' . wp_json_encode( $attrs ) . " /-->\n";
}
?>
<!-- /wp:navigation-submenu -->
<!-- /wp:navigation -->

<!-- wp:group {"className":"nav-cta"} -->
<div class="wp-block-group nav-cta">
<!-- wp:paragraph {"className":"nav-phone"} -->
<p class="nav-phone">(519) 362-6733</p>
<!-- /wp:paragraph -->

<!-- wp:buttons -->
<div class="wp-block-buttons">
<!-- wp:button -->
<div class="wp-block-button"><a class="wp-block-button__link wp-element-button" href="<?php echo esc_url( home_url( '/#contact' ) ); ?>">Get a Quote</a></div>
<!-- /wp:button -->
</div>
<!-- /wp:buttons -->
</div>
<!-- /wp:group -->
</div>
<!-- /wp:group -->
</div>
<!-- /wp:group -->
