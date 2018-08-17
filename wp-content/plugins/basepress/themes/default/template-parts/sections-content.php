<?php
/*
*	This template lists all top sections with a list style
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

	<div class="bpress-section bpress-col bpress-col-<?php basepress_section_cols(); ?>">
	
	<!-- Section Title -->
	<?php
		$show_icon = basepress_show_section_icon();
		$section_class = $show_icon ? ' show-icon' : '';
	?>
	<h2 class="bpress-section-title<?php echo $section_class; ?>">
		<?php if ( $show_icon ) { ?>
		<span aria-hidden="true" class="bpress-section-icon <?php echo $section->icon; ?>"></span>
		<?php } ?>

		<a href="<?php echo $section->permalink; ?>">
			<?php echo $section->name; ?>
			<!-- Posts count -->
			<?php if ( basepress_show_section_post_count() ) { ?>
				<span class="bpress-post-count">(<?php echo $section->posts_count; ?>)</span>
			<?php } ?>
		</a>
	</h2>

	
	<!-- Post list -->
	<ul class="bpress-section-list">
		<?php
		foreach ( $section->posts as $article ) :

			$show_post_icon = basepress_show_post_icon();
			$post_class = $show_post_icon ? ' show-icon' : '';
			?>

			<li class="bpress-post-link<?php echo $post_class; ?>">
			
				<!-- Post permlnk -->
				<a href="<?php echo get_the_permalink( $article->ID ); ?>">

					<!-- Post icon -->
					<?php if ( $show_post_icon ) { ?>
					<span aria-hidden="true" class="<?php echo $article->icon; ?>"></span>
					<?php } ?>

					<!-- Post title -->
					<?php echo $article->post_title; ?>
				</a>
			</li>

		<?php endforeach; ?>
	</ul>

	<!-- Section View All -->
	<?php if ( $section->posts_count > count( $section->posts ) ) { ?>
		<br>
		<a href="<?php echo $section->permalink; ?>" class="bpress-viewall">
			<?php printf( _n( 'View %d article', 'View all %d articles', $section->posts_count, 'basepress' ), $section->posts_count ); ?>
		</a>
	<?php } ?>

	</div><!-- End section -->

<?php endforeach; ?>

</div><!-- End grid -->
