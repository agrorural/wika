<?php
/*
 *	This is BasePress archive page for all the sections in a product.
 */


//Get the products details
$product = basepress_product();
$is_single_product = basepress_is_single_product();

//Get active theme header
get_header( 'basepress' );
?>

<!-- Main BasePress wrap -->
<div class="bpress-wrap">

	<!-- Product title -->
	<header>
		<h1 class="bpress-product-title"><?php echo ( $is_single_product ? '' : $product->name ); ?></h1>
	</header>

	<!-- Add breadcrumbs -->
	<div class="bpress-crumbs-wrap">
		<?php basepress_breadcrumbs(); ?>
	</div>

	<div class="bpress-content-area bpress-float-left">
		
		<!-- Add search-bar -->
		<div class="bpress-card">
			<?php basepress_searchbar(); ?>
		</div>
		
		<!-- Add main content -->
		<main role="main">
			<?php basepress_get_template_part( 'sections-content-boxed' ); ?>
		</main>
	</div><!-- content area -->
	
	<!-- Sidebar -->
	<?php if ( is_active_sidebar( 'basepress-sidebar' ) ) : ?>
	<aside class="bpress-sidebar bpress-float-left" role="complementary">
		<?php dynamic_sidebar( 'basepress-sidebar' ); ?>
	</aside>
	<?php endif; ?>

</div><!-- wrap -->
<?php get_footer( 'basepress' ); ?>
