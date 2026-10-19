<!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<?php wp_head(); // Required: WordPress and plugins inject CSS/JS here. ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<header class="site-header">
	<p class="site-title">
		<a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php bloginfo( 'name' ); // Set in Settings -> General ?></a>
	</p>
	<nav>
		<?php wp_nav_menu( array( 'theme_location' => 'primary', 'fallback_cb' => false ) ); ?>
	</nav>
</header>
