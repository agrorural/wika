<?php
/**
 * The template for displaying post type archives
 *
 */

global $wp_query;

get_header();

?>

<div class="kbx-outer-wrapper">

	<section class="kbx-articles">

		<?php 
		
		if( have_posts() ) : while(have_posts()) : the_post();
			
		?>
		
		<div class="single-kbx-post-outer">
			
			<header class="page-header">
				<h2 class="page-title">
					<?php echo esc_html( get_the_title() ); ?>
				</h2>
			</header>

			<div class="clear"></div>

			<div class="kbx-article-body">
					
				<?php the_content(); ?>
			
			</div>
		
		</div>

		<?php endwhile; else : ?>

		<p>
			<?php _e('No article found according to the provided URL.', 'kbx-qc'); ?>
		</p>

		<?php endif; ?>

	</section>

</div>

<?php get_footer();


