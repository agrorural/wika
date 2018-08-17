<?php
/* Template Name: Full Width */

//Get product object
$product = basepress_product();
$is_single_product = basepress_is_single_product();

get_header( 'basepress' );
?>

	<!-- Main BasePress wrap -->
	<div class="bpress-wrap">

		<div class="bpress-page-header">
			<!-- Product title -->
			<header>
				<h1>Knowledge Base<br><?php echo ( $is_single_product ? '' : $product->name ); ?></h1>
			</header>

			<!-- Add searchbar -->
			<div class="bpress-searchbar-wrap">
				<?php basepress_searchbar(); ?>
			</div>
		</div>

		<!-- Add breadcrumbs -->
		<div class="bpress-crumbs-wrap">
			<?php basepress_breadcrumbs(); ?>
		</div>

		<div class="bpress-content-area bpress-full-width">

			<!-- Add main content -->
			<main class="bpress-main" role="main">

				<?php
				//Start the loop.
				while ( have_posts() ) : the_post();

					//Include the page content template using basepress internal function.
					basepress_get_template_part( 'post-content' );

					//Get Polls Items
					basepress_votes();

					//End of the loop.
				endwhile;

				?>

				<!-- Add previous and next articles navigation -->
				<?php basepress_get_template_part( 'adjacent-articles' ); ?>

			</main>

			<?php
			// If comments are open or we have at least one comment, load up the comment template.
			if ( comments_open() || get_comments_number() ) {
				comments_template();
			}
			?>

		</div><!-- content area -->

	</div><!-- .wrap -->

<?php get_footer( 'basepress' ); ?>
