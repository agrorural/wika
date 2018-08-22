<?php
/*
 *	This is the archive page for the top sections with a list style.
 */


//Get the products details
$product = basepress_product();
$show_sidebar = is_active_sidebar( 'basepress-sidebar' );
$content_classes = $show_sidebar ? ' show-sidebar' : '';

//Get active theme header
get_header( 'basepress' );
?>

<!-- Main BasePress wrap -->
<div class="bpress-wrap">
	
	<div class="bpress-content-area bpress-float-left<?php echo $content_classes; ?>">
		
    <!-- Product title -->
    <?php /*
		<header>
			<h1 class="bpress-product-title"><?php echo $product->name; ?></h1>
		</header>
    */ ?>
    
    <!-- Add breadcrumbs -->
    <?php /*
		<div class="bpress-crumbs-wrap">
			<?php basepress_breadcrumbs(); ?>
    </div>
    */ ?>
		
    <!-- Add searchbar -->
    <?php /*
		<div class="bpress-searchbar-wrap">	
			<?php basepress_searchbar(); ?>
    </div>
    */?>
		
		<!-- Add main content -->
		<main class="bpress-main" role="main">
			<?php basepress_get_template_part( 'sections-content' ); ?>
		</main>
	</div><!-- content area -->
	
	<!-- Sidebar -->
	<?php if ( $show_sidebar ) : ?>
	<aside class="bpress-sidebar bpress-float-left" role="complementary">
		<?php dynamic_sidebar( 'basepress-sidebar' ); ?>
	</aside>
	<?php endif; ?>
	
	
</div><!-- wrap -->
<?php get_footer( 'basepress' ); ?>

<!-- <script>
alert("Hola mundo");
</script> -->