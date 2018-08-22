<?php
/*
 *	This is the template that renders the previous and next articles
 *
 */

if ( basepress_show_adjacent_articles() ) {

	//Get Previous and Next articles
	$prev_article = basepress_prev_article();
	$next_article = basepress_next_article();

	if ( $prev_article || $next_article ) {

		echo '<div class="bpress-grid">';

		echo '<div class="bpress-col bpress-col-2">';
		echo '<div class="bpress-prev-post">';
		if ( $prev_article ) {
			$prev_link = get_permalink( $prev_article->ID );
			echo '<a href="' . $prev_link . '">';
			echo '<span class="bpress-adjacent-title">' . basepress_prev_article_text() . '</span>';
			echo '<h4>';
			if ( basepress_show_post_icon() ) {
				echo '<span class="bp-icon ' . $prev_article->icon . '"></span>';
			}
			echo $prev_article->post_title . '</h4>';
			echo '</a>';
		}
		echo '</div>';
		echo '</div>';

		echo '<div class="bpress-col bpress-col-2">';
		echo '<div class="bpress-next-post">';
		if ( $next_article ) {
			$next_link = get_permalink( $next_article->ID );
			echo '<a href="' . $next_link . '">';
			echo '<span class="bpress-adjacent-title">' . basepress_next_article_text() . '</span>';
			echo '<h4>';
			echo $next_article->post_title;
			if ( basepress_show_post_icon() ) {
				echo '<span class="bp-icon ' . $next_article->icon . '"></span>';
			}
			echo '</h4>';
			echo '</a>';
		}
		echo '</div>';
		echo '</div>';

		echo '</div>';
	}
}
