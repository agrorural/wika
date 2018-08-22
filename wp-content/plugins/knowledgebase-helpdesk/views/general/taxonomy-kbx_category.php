<?php
/**
 * The template for displaying taxonomy archives
 *
 * Used to display custom taxonomy archives if no archive template is found in the theme folder.
 *
 */

global $wp_query;
$term = $wp_query->get_queried_object();

/* This plugin uses the Archive file of TwentyFifteen theme as an example */
get_header();

// Hide the first level header when displaying the category archives.
$custom_css = '
	.wzkb-section-name-level-1 {
		display: none;
	}
';
wp_add_inline_style( 'wzkb_styles', $custom_css );


?>

<div class="kbx-outer-wrapper">

	<section id="primary" class="content-area">

		<?php if ( have_posts() ) : ?>

			<header class="page-header">
				<h2 class="page-title"><?php echo esc_html( $term->name ); ?></h2>
			</header><!-- .page-header -->

			<?php

			echo do_shortcode( "[kbx-knowledgebase-articles section='{$term->term_id}']" );

			// If no content, include the "No posts found" template.
		else :

			_e('No articles found under this section.', 'kbx-qc');

		endif;
		?><!-- .site-main -->

	</section><!-- .content-area -->
	
</div>

<?php get_footer();


