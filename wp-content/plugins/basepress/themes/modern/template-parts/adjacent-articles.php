<?php
/*
 *	This is the template that renders the previous and next articles
 *
 */

if ( basepress_show_adjacent_articles() ) {

	//Get Previous and Next articles
	$prev_article = basepress_prev_article();
	$next_article = basepress_next_article();
	$show_icon = basepress_show_post_icon();
	$post_class = $show_icon ? ' show-icon' : '';
	$grid_align = $next_article && ! $prev_article ? ' bpress-align-right' : '';

	if ( $prev_article || $next_article ) { ?>

		<div class="bpress-grid<?php echo $grid_align; ?>">

			<?php
			if ( $prev_article ) {
				$prev_link = get_permalink( $prev_article->ID );
			?>
			<div class="bpress-col bpress-col-2">
				<div class="bpress-prev-post">
					<span class="bpress-adjacent-title"><?php echo basepress_prev_article_text(); ?></span>

					<div class="bpress-adjacent-post<?php echo $post_class; ?>">
						<?php if ( basepress_show_post_icon() ) { ?>
							<span class="bp-icon <?php echo $prev_article->icon; ?>"></span>
						<?php } ?>
						<h4>
							<a href="<?php echo $prev_link; ?>"><?php echo $prev_article->post_title; ?></a>
						</h4>
					</div>
				</div>
			</div>
			<?php } ?>




		<?php
		if ( $next_article ) {
			$next_link = get_permalink( $next_article->ID );
		?>
		<div class="bpress-col bpress-col-2">
			<div class="bpress-next-post">
				<span class="bpress-adjacent-title"><?php echo basepress_next_article_text(); ?></span>

				<div class="bpress-adjacent-post<?php echo $post_class; ?>">
					<?php if ( basepress_show_post_icon() ) { ?>
						<span class="bp-icon <?php echo $next_article->icon; ?>"></span>
					<?php } ?>
					<h4>
						<a href="<?php echo $next_link; ?>"><?php echo $next_article->post_title; ?></a>
					</h4>
				</div>
			</div>
		</div>
		<?php } ?>

	</div>
	<?php } ?>

<?php } ?>
