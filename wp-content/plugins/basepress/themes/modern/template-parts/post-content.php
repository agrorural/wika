<?php
/*
 * The template part for displaying content
 */

//Get Post meta icons
$post_meta_icons = basepress_get_post_meta_icons();
$post_views_icon = isset( $post_meta_icons[0] ) ? $post_meta_icons[0] : '';
$post_post_like_icon = isset( $post_meta_icons[1] ) ? $post_meta_icons[1] : '';
$post_post_dislike_icon = isset( $post_meta_icons[2] ) ? $post_meta_icons[2] : '';
$post_post_date_icon = isset( $post_meta_icons[3] ) ? $post_meta_icons[3] : '';
?>

<article id="post-<?php the_ID(); ?>">
	<header class="bpress-post-header">
		<h1><?php the_title(); ?></h1>


		<div class="bpress-post-meta">
			<?php $post_metas = basepress_get_post_meta( get_the_ID() ); ?>

			<span class="bpress-post-views"><span class="<?php echo $post_views_icon; ?>"></span><?php echo $post_metas['views']; ?></span>
			<?php if ( isset( $post_metas['votes'] ) ) { ?>
			<span class="bpress-post-likes"><span class="<?php echo $post_post_like_icon; ?>"></span><?php echo $post_metas['votes']['like']; ?></span>
			<span class="bpress-post-dislikes"><span class="<?php echo $post_post_dislike_icon; ?>"></span><?php echo $post_metas['votes']['dislike']; ?></span>
			<?php } ?>
			<span class="bpress-post-date"><span class="<?php echo $post_post_date_icon; ?>"></span><?php echo get_the_modified_date(); ?></span>
		</div>
	</header>

	<?php
	//Add the table of content
	basepress_get_template_part( 'table-of-content' );
	?>

	<?php the_content(); ?>


	<!-- Pagination -->
	<nav class="bpress-pagination">
		<?php basepress_post_pagination(); ?>
	</nav>

</article>
