<?php
/*
 *	This is BasePress archive page for global search results.
 */

//Get Post meta icons
$post_meta_icons = basepress_get_post_meta_icons();
$post_views_icon = isset( $post_meta_icons[0] ) ? $post_meta_icons[0] : '';
$post_post_like_icon = isset( $post_meta_icons[1] ) ? $post_meta_icons[1] : '';
$post_post_dislike_icon = isset( $post_meta_icons[2] ) ? $post_meta_icons[2] : '';
$post_post_date_icon = isset( $post_meta_icons[3] ) ? $post_meta_icons[3] : '';

//Get active theme header
get_header( 'basepress' );
?>

	<div class="bpress-wrap">

		<!-- Add breadcrumbs -->
		<div class="bpress-crumbs-wrap">
			<?php basepress_breadcrumbs(); ?>
		</div>

		<div class="bpress-content-area bpress-float-left">

			<!-- Add searchbar -->
			<div class="bpress-card">
				<?php basepress_searchbar(); ?>
			</div>

			<main class="bpress-main" role="main">

				<header class="bpress-post-header">
					<h1><?php echo basepress_search_page_title() . ' ' . basepress_search_term(); ?></h1>
				</header>

				<div class="bpress-card">
					<?php if ( have_posts() ) { ?>

						<ul class="bpress-card-body">

							<?php
							while ( have_posts() ) {
								the_post();
								$show_post_icon = basepress_show_post_icon();
								$post_class = $show_post_icon ? ' show-icon' : '';
								?>

								<li class="bpress-post-link search">
									<h3>
										<!-- Post icon -->
										<?php if ( basepress_show_post_icon() ) { ?>
											<span aria-hidden="true" class="bp-icon <?php echo basepress_post_icon( get_the_ID() ); ?>"></span>
										<?php } ?>

										<!-- Post permalink -->
										<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
									</h3>
									<!-- Post excerpt -->
									<p class="bpress-search-excerpt"><?php basepress_search_post_snippet(); ?></p>

									<!-- Post Section -->
									<?php	$post_section = get_the_terms( get_the_ID(), 'knowledgebase_cat' )[0]; ?>
									<a href="<?php echo get_term_link( $post_section ); ?>" class="bpress-search-section"><?php echo $post_section->name; ?></a>

									<!-- Post Meta -->
									<div class="bpress-post-meta">
										<?php $post_metas = basepress_get_post_meta( get_the_ID() ); ?>

										<span class="bpress-post-views"><span class="<?php echo $post_views_icon; ?>"></span><?php echo $post_metas['views']; ?></span>
										<?php if ( isset( $post_metas['votes'] ) ) { ?>
											<span class="bpress-post-likes"><span class="<?php echo $post_post_like_icon; ?>"></span><?php echo $post_metas['votes']['like']; ?></span>
											<span class="bpress-post-dislikes"><span class="<?php echo $post_post_dislike_icon; ?>"></span><?php echo $post_metas['votes']['dislike']; ?></span>
										<?php } ?>
										<span class="bpress-post-date"><span class="<?php echo $post_post_date_icon; ?>"></span><?php echo get_the_modified_date(); ?></span>
									</div>

								</li>

							<?php	} //End while ?>

						</ul>
					<?php
					} else {
						echo '<h3>' . basepress_search_page_no_results_title() . '</h3>';
					}
					?>

				</div>
			</main>

			<!-- Pagination -->
			<nav class="bpress-pagination">
				<?php basepress_pagination(); ?>
			</nav>

		</div><!-- content area -->

		<!-- BasePress Sidebar -->
		<?php if ( is_active_sidebar( 'basepress-sidebar' ) ) : ?>
			<aside class="bpress-sidebar bpress-float-left widget-area" role="complementary">
				<?php dynamic_sidebar( 'basepress-sidebar' ); ?>
			</aside>
		<?php endif; ?>


	</div><!-- wrap -->
<?php get_footer( 'basepress' ); ?>
