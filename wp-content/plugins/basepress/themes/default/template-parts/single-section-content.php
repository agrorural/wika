<?php
/*
 *	This template displays a single section content
 *
 */

//Get the sections object
$sections = basepress_sections();

//We can iterate through the sections
foreach ( $sections as $section ) :
?>
	
		<div class="bpress-section">

			<?php if ( $section->posts_count ) : ?>
			<!-- Section Title -->
			<h1 class="bpress-section-title">
				<!-- Section icon -->
				<?php if ( basepress_show_section_icon() ) { ?>
					<span aria-hidden="true" class="bpress-section-icon <?php echo $section->icon; ?>"></span>
				<?php } ?>
				
				<!-- Section permalink -->
				<a href="<?php echo $section->permalink; ?>"><?php echo $section->name; ?></a>
			</h1>
			<?php endif; ?>

			<!-- Post list -->
			<ul class="bpress-section-list">
				<?php
				foreach ( $section->posts as $article ) :
					$show_post_icon = basepress_show_post_icon();
					$post_class = $show_post_icon ? ' show-icon' : '';
					?>
					<li class="bpress-post-link single-section<?php echo $post_class; ?>">

						<!-- Post permalink -->
						<a href="<?php echo get_the_permalink( $article->ID ); ?>">

							<!-- Post icon -->
							<?php if ( basepress_show_post_icon() ) { ?>
							<span aria-hidden="true" class="bpress-section-icon <?php echo $article->icon; ?>"></span>
							<?php } ?>

							<?php echo $article->post_title; ?>
						</a>
					</li>
				<?php endforeach; ?>
			</ul>

			<!-- Pagination -->
			<nav class="bpress-pagination">
				<?php basepress_pagination(); ?>
			</nav>

		</div><!-- End section -->
	
<?php endforeach; ?>
