<?php
/*
 * This is BasePress template to show the list of products
 * It is called from the shortcode
 */


//Get the product objects
$products = basepress_products();

?>
<div class="bpress-wrap">
	<div class="bpress-grid">
	<?php foreach ( $products as $product ) : ?>
		<div class="bpress-col bpress-col-<?php basepress_product_cols(); ?>">
			<div class="bpress-card bpress-product fix-height">
				<a class="bpress-product-link" href="<?php echo $product->permalink; ?>">
					<img class="bpress-card-top-image" src="<?php echo $product->image->url; ?>">
					<h3 class="bpress-card-title"><?php echo $product->name; ?></h3>
					<?php if ( '' != $product->description ) : ?>
						<div class="bpress-card-body">
						<p><?php echo $product->description; ?></p>
						</div>
					<?php endif; ?>
					<span class="bpress-card-footer"><?php _e( 'Choose Product', 'basepress' ); ?></span>
				</a>
			</div>
		</div>
	<?php endforeach; ?>
	</div>
</div>
