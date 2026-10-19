<?php
/**
 * index.php is the fallback template: WordPress uses it for any page
 * that doesn't have a more specific template (single.php, page.php, etc.).
 */

get_header(); // Pulls in header.php
?>

<main>
	<?php if ( have_posts() ) : ?>
		<?php
		// "The Loop": WordPress has already queried the database for the
		// posts that belong on this URL; this walks through them.
		while ( have_posts() ) :
			the_post();
			?>
			<article <?php post_class(); ?>>
				<h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
				<p><small><?php echo get_the_date(); ?></small></p>
				<?php the_content(); ?>
			</article>
		<?php endwhile; ?>
	<?php else : ?>
		<p>Nothing here yet.</p>
	<?php endif; ?>
</main>

<?php
get_footer(); // Pulls in footer.php
