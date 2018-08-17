<?php
/*
 *	This is the archive page for the top sections with a list style.
 */


//Get the products details
$product = basepress_product();
$show_sidebar = is_active_sidebar( 'basepress-sidebar' );
$content_classes = $show_sidebar ? ' show-sidebar' : '';
$is_single_product = basepress_is_single_product();

//Get active theme header
get_header( 'basepress' );
?>

<!-- Main BasePress wrap -->
<div class="bpress-wrap">

	<div class="bpress-page-header">
		<div class="bpress-content-wrap">
			<!-- Product title -->
			<header>
				<h2>Knowledge Base<br>
				<?php echo ( $is_single_product ? '' : $product->name ); ?>
				</h2>
				<p><?php echo $product->description; ?></p>
			</header>

			<!-- Add searchbar -->
			<div class="bpress-searchbar-wrap">
				<?php basepress_searchbar(); ?>
			</div>
		</div>
	</div>

	<!-- Add breadcrumbs -->
	<div class="bpress-crumbs-wrap">
		<div class="bpress-content-wrap">
			<?php basepress_breadcrumbs(); ?>
		</div>
	</div>

	<div class="bpress-content-wrap">
		<div class="bpress-content-area bpress-float-left<?php echo $content_classes; ?>">

			<!-- Add main content -->
			<main class="bpress-main" role="main">
				<?php basepress_get_template_part( 'sections-content' ); ?>
			</main>
		</div><!-- content area -->

		<!-- Sidebar -->
		<?php if ( $show_sidebar ) : ?>
		<aside class="bpress-sidebar bpress-float-left" role="complementary">
			<div class="hide-scrollbars">
			<?php dynamic_sidebar( 'basepress-sidebar' ); ?>
			</div>
		</aside>
		<?php endif; ?>

	</div>
</div><!-- wrap -->
<?php get_footer( 'basepress' ); ?>
