<?php
/**
 * functions.php runs on every page load. It just loads the files in inc/,
 * one file per feature.
 */

require_once get_template_directory() . '/inc/assets.php';
require_once get_template_directory() . '/inc/blocks.php';
require_once get_template_directory() . '/inc/product.php';
require_once get_template_directory() . '/inc/product-images.php';
require_once get_template_directory() . '/inc/category-image.php';
require_once get_template_directory() . '/inc/contact.php';
