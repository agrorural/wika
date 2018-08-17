<?php
/*
 *	This template lists all sections for a product
 * 	or sub-sections for a section
 *
 */

//Get the sections object
$sections = basepress_sections();
$show_icon = basepress_show_section_icon();
$section_class = $show_icon ? ' show-icon' : '';
?>

<div class="bpress-grid">

<?php
//We can iterate through the sections
foreach ( $sections as $section ) :
?>

	<div class="bpress-section bpress-col bpress-col-<?php basepress_section_cols(); ?>">

		<div class="bpress-card bpress-section fix-height">

			<!-- Section Title -->
			<h2 class="bpress-card-header<?php echo $section_class; ?>">
				<?php if ( $show_icon ) { ?>
				<span aria-hidden="true" class="bp-icon <?php echo $section->icon; ?>"></span>
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
			<ul class="bpress-section-list bpress-card-body">
				<?php
				foreach ( $section->posts as $article ) :
					$show_post_icon = basepress_show_post_icon();
					$post_class = $show_post_icon ? ' show-icon' : '';
				?>
			
				<li class="bpress-post-link<?php echo $post_class; ?>">

					<!-- Post icon -->
					<?php if ( $show_post_icon ) { ?>
								<span aria-hidden="true" class="bp-icon <?php echo $article->icon; ?>"></span>
					<?php } ?>

					<!-- Post permalink -->
					<a href="<?php echo get_the_permalink( $article->ID ); ?>">

					<!-- Post title -->
					<?php echo $article->post_title; ?>
					</a>
				</li>

				<?php endforeach; ?>
			</ul>

			<!-- Section View All -->

				<a href="<?php echo $section->permalink; ?>">
					<span class="bpress-card-footer">
					<?php printf( _n( 'View %d article', 'View all %d articles', $section->posts_count, 'basepress' ), $section->posts_count ); ?>
					</span>
				</a>


		</div>
	</div><!-- End section -->

<?php endforeach; ?>

</div><!-- End grid -->
