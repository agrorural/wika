<?php
/*
 * This template lists all top sections with a list style
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
		<div class="bpress-section fix-height">

			<!-- Section Title -->
			<?php
			$show_icon = basepress_show_section_icon();
			$section_class = $show_icon ? ' show-icon' : '';
			?>
			<div class="bpress-heading<?php echo $section_class; ?>">
				<?php if ( $show_icon ) { ?>
					<span aria-hidden="true" class="bpress-heading-icon <?php echo $section->icon; ?> colored"></span>
				<?php } ?>
				<h2>
					<a href="<?php echo $section->permalink; ?>"><?php echo $section->name; ?></a>
				</h2>
			</div>

			<?php if ( basepress_show_section_post_count() ) { ?>
				<span class="bpress-post-count"><?php echo $section->posts_count; ?></span>
			<?php } ?>

			<!-- Post list -->
			<ul class="bpress-section-list">
				<?php
				foreach ( $section->posts as $post ) :
					$show_post_icon = basepress_show_post_icon();
					$post_class = $show_post_icon ? ' show-icon' : '';
				?>

				<li class="bpress-post-link">

					<div class="bpress-heading<?php echo $post_class; ?>">
						<!-- Post icon -->
						<?php if ( $show_post_icon ) { ?>
						<span aria-hidden="true" class="bpress-heading-icon <?php echo $post->icon; ?>"></span>
						<?php } ?>

						<!-- Post permalink -->
						<a href="<?php echo get_the_permalink( $post->ID ); ?>">
							<?php echo $post->post_title; ?>
						</a>
					</div>
				</li>

				<?php endforeach; ?>
			</ul>

			<!-- Section View All -->
			<a href="<?php echo $section->permalink; ?>" class="bpress-viewall"><?php printf( _n( 'View %d article', 'View all %d articles', $section->posts_count, 'basepress' ), $section->posts_count ); ?></a>

		</div>
	</div>

<?php endforeach; ?>

</div><!-- End grid -->
