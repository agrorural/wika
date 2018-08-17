<?php
/*
 *	This template lists all top sections with a boxed style
 *
 */

//Get the sections object
$sections = basepress_sections();

?>
<div class="bpress-grid">

<?php
//We can iterate through the sections
foreach ( $sections as $section ) :
?>

	<div class="bpress-col bpress-col-<?php basepress_section_cols(); ?>">
		<div class="bpress-section-boxed">
			<a href="<?php echo $section->permalink; ?>">

				<!-- Section Title icon -->
				<?php if ( $section->image['image_url'] ) { ?>
					<div class="bpress-section-image">
						<img src="<?php echo $section->image['image_url']; ?>" alt="<?php echo $section->name; ?>">
					</div>
				<?php } else { ?>
					<span aria-hidden="true" class="bpress-section-icon <?php echo $section->icon; ?>"></span>
				<?php } ?>

				<!-- Section Title -->
				<h2 class="bpress-section-title"><?php echo $section->name; ?></h2>

				<!-- Section Description -->
				<?php if ( $section->description ) { ?>
				<p><?php echo $section->description; ?></p>
				<?php } ?>

				<!-- Section View All -->
				<span class="bpress-viewall">
					<?php printf( _n( 'View %d article', 'View all %d articles', $section->posts_count, 'basepress' ), $section->posts_count ); ?>
				</span>

			</a>

		</div><!-- End Section -->
	</div><!-- End col -->

<?php endforeach; ?>

</div><!-- End grid -->
