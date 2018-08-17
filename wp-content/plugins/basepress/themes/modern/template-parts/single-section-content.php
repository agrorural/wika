<?php
/*
 *	This template displays a single section content
 *
 */

//Get the sections object
$sections = basepress_sections();

//We can iterate through the sections
foreach ( $sections as $section ) : ?>
	
		<div class="bpress-single-section">

			<!-- Section Title -->
			<?php
				$show_icon = basepress_show_section_icon();
				$section_class = $show_icon ? ' show-icon' : '';
			?>
			<div class="bpress-heading<?php echo $section_class; ?>">
				<!-- Section icon -->
				<?php if ( $show_icon ) { ?>
					<span aria-hidden="true" class="bpress-heading-icon <?php echo $section->icon; ?> colored"></span>
				<?php } ?>

				<h1><?php echo $section->name; ?></h1>
			</div>

			<!-- Post list -->
			<ul class="bpress-section-list">
				<?php
				foreach ( $section->posts as $article ) :
					$show_post_icon = basepress_show_post_icon();
					$post_class = $show_post_icon ? ' show-icon' : '';
				?>
				<li class="bpress-post-link single-section">

					<div class="bpress-heading<?php echo $post_class; ?>">
						<!-- Post icon -->
						<?php if ( $show_post_icon ) { ?>
							<span aria-hidden="true" class="bpress-heading-icon <?php echo $article->icon; ?>"></span>
						<?php } ?>

						<h3>
							<!-- Post permalink -->
							<a href="<?php echo get_the_permalink( $article->ID ); ?>"><?php echo $article->post_title; ?></a>
						</h3>
					</div>
				</li>
				<?php endforeach; ?>
			</ul>

			<!-- Pagination -->
			<nav class="bpress-pagination">
				<?php basepress_pagination(); ?>
			</nav>

		</div><!-- End section -->
	
<?php endforeach; ?>
