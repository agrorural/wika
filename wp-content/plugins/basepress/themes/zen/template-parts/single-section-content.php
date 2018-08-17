<?php
/*
 *	This is the template which lists all sections for a product
 *
 */

//Get the sections object
$sections = basepress_sections();
$show_icon = basepress_show_section_icon();
$header_class = $show_icon ? ' class="show-icon"' : '';

//We can iterate through the sections
foreach ( $sections as $section ) : ?>

		<!-- Section Title -->
	<header class="bpress-post-header">
		<h1<?php echo $header_class; ?>>
			<!-- Section icon -->
			<?php if ( basepress_show_section_icon() ) { ?>
				<span aria-hidden="true" class="bpress-section-icon <?php echo $section->icon; ?>"></span>
			<?php } ?>

			<!-- Section Title -->
			<?php echo $section->name; ?>
		</h1>
	</header>


	<!-- Post list -->
	<?php if ( $section->posts_count ) : ?>
		<div class="bpress-card">
			<ul class="bpress-card-body">

		<?php
		foreach ( $section->posts as $article ) :
			$show_post_icon = basepress_show_post_icon();
			$post_class = $show_post_icon ? ' show-icon' : '';
		?>
				<li class="bpress-post-link single-section<?php echo $post_class; ?>">

					<!-- Post icon -->
					<?php if ( basepress_show_post_icon() ) { ?>
						<span aria-hidden="true" class="bp-icon <?php echo $article->icon; ?>"></span>
					<?php } ?>

					<!-- Post permalink -->
					<a href="<?php echo get_the_permalink( $article->ID ); ?>">
						<?php echo $article->post_title; ?>
					</a>
				</li>
		<?php endforeach; ?>
			</ul>
		</div>
	<?php endif; ?>

<?php endforeach; ?>
