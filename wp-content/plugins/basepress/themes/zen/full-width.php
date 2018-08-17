<?php
/* Template Name: Full Width */

//Get product object
$product = basepress_product();
$is_single_product = basepress_is_single_product();

get_header( 'basepress' );
?>

<!-- Main BasePress wrap -->
<div class="bpress-wrap">

	<!-- Product title -->
	<header class="bpress-main-header">
		<h2 class="bpress-product-title"><?php echo ( $is_single_product ? '' : $product->name ); ?></h2>
	</header>

	<!-- Add breadcrumbs -->
	<div class="bpress-crumbs-wrap">
		<?php basepress_breadcrumbs(); ?>
	</div>

	<div class="bpress-content-area bpress-full-width">
		
		<!-- Add searchbar -->
		<div class="bpress-card">
			<?php basepress_searchbar(); ?>
		</div>
		
		<!-- Add main content -->
		<main class="bpress-main" role="main">
			
			<?php
			// Start the loop.
			while ( have_posts() ) : the_post();

				//Include the page content template using basepress internal function.
				basepress_get_template_part( 'post-content' );

			// End of the loop.
			endwhile;

			?>

		</main>

		<?php
		// If comments are open or we have at least one comment, load up the comment template.
		if ( comments_open() || get_comments_number() ) {
			comments_template();
		}
		?>
	</div><!-- content area -->
	
</div><!-- wrap -->

<?php get_footer( 'basepress' ); ?>
