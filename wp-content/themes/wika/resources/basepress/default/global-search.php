<?php
/*
 *	This is the archive page for global search results.
 */


//Get the products details
$show_sidebar = is_active_sidebar( 'basepress-sidebar' );
$content_classes = $show_sidebar ? ' show-sidebar' : '';

//Get active theme header
get_header( 'basepress' );
?>

	<div class="bpress-wrap">
		<!-- Add breadcrumbs -->
		<div class="bpress-crumbs-wrap">
			<?php basepress_breadcrumbs(); ?>
		</div>

		<div class="bpress-content-area bpress-float-left<?php echo $content_classes; ?>">

			<!-- Add searchbar -->
			<div class="bpress-searchbar-wrap">
				<?php basepress_searchbar(); ?>
			</div>

			<main class="bpress-main" role="main">

				<?php if ( have_posts() ) { ?>

					<h1><?php echo basepress_search_page_title() . ' ' . basepress_search_term(); ?></h1>
					<ul class="bpress-post-list">

						<?php
						while ( have_posts() ) {
							the_post();
							$show_post_icon = basepress_show_post_icon();
							$post_class = $show_post_icon ? ' show-icon' : '';
							?>

							<li class="bpress-post-link search<?php echo $post_class; ?>">

								<!-- Post permalink -->
								<a href="<?php the_permalink(); ?>">
									<h3>
										<!-- Post icon -->
										<?php if ( basepress_show_post_icon() ) { ?>
											<span aria-hidden="true" class="<?php echo basepress_post_icon( get_the_ID() ); ?>"></span>
										<?php } ?>

										<?php the_title(); ?>
									</h3>
									<p class="bpress-search-excerpt"><?php basepress_search_post_snippet(); ?></p>
								</a>

							</li>

						<?php	} //End while ?>

					</ul>
				<?php } else {
					echo '<h3>' . basepress_search_page_no_results_title() . '</h3>';
				}
				?>

			</main>

			<!-- Pagination -->
			<nav class="bpress-pagination">
				<?php basepress_pagination(); ?>
			</nav>

		</div><!-- content area -->

		<!-- BasePress Sidebar -->
		<?php if ( $show_sidebar ) : ?>
			<aside class="bpress-sidebar bpress-float-left widget-area" role="complementary">
				<?php dynamic_sidebar( 'basepress-sidebar' ); ?>
			</aside>
		<?php endif; ?>


	</div><!-- wrap -->
<?php get_footer( 'basepress' ); ?>

<!-- <script>
alert("Hola mundo");
</script> -->
