<?php
/*
 * This template shows the list of products
 * It is called from the shortcode
 */


//Get the product objects
$products = basepress_products();

?>
<div class="bpress-wrap">
	<div class="bpress-grid">
	<?php foreach ( $products as $product ) : ?>
		<div class="bpress-col bpress-col-<?php basepress_product_cols(); ?>">
			<div class="bpress-product">
				<a class="bpress-product-link" href="<?php echo $product->permalink; ?>">
					<img class="bpress-product-image" src="<?php echo $product->image->url; ?>">
					<h3 class="bpress-product-title"><?php echo $product->name; ?></h3>
					<?php if ( $product->description != '' ) : ?>
						<p class="bpress-product-description"><?php echo $product->description; ?></p>
					<?php endif; ?>
					<button class="bpress-btn bpress-btn-product"><?php _e( 'Choose Product', 'basepress' ); ?></button>
				</a>
			</div>
		</div>
	<?php endforeach; ?>
	</div>
</div>
