<?php
/*
 * This id the template for the restricted content page
 */

$is_single_product = basepress_is_single_product();

get_header( 'basepress' ); ?>

<!-- Main BasePress wrap -->
<div class="bpress-wrap">
	
	<div class="bpress-page-header">
		<div class="bpress-content-wrap">
			<!-- Product title -->
			<header>
				<h2>Knowledge Base<br><?php echo ( $is_single_product ? '' : $product->name ); ?></h2>
			</header>

			<!-- Add searchbar -->
			<div class="bpress-searchbar-wrap">
				<?php basepress_searchbar(); ?>
			</div>
		</div>
	</div>
		
	<!-- Add breadcrumbs -->
	<div class="bpress-crumbs-wrap">
		<div class="bpress-content-wrap">
			<?php basepress_breadcrumbs(); ?>
		</div>
	</div>

	<div class="bpress-content-wrap">
		<div class="bpress-content-area bpress-float-left">

		<!-- Add main content -->
		<main class="bpress-main" role="main">
			<header class="bpress-post-header">
				<h1><?php the_title(); ?></h1>

				<div class="bpress-post-meta">
					<?php $post_metas = basepress_get_post_meta( get_the_ID() ); ?>

					<span class="bpress-post-views"><span class="bp-eye"></span><?php echo $post_metas['views']; ?></span>
					<span class="bpress-post-likes"><span class="bp-thumbs-up"></span><?php echo $post_metas['votes']['like']; ?></span>
					<span class="bpress-post-dislikes"><span class="bp-thumbs-down"></span><?php echo $post_metas['votes']['dislike']; ?></span>
					<span class="bpress-post-date"><span class="bp-clock"></span><?php echo get_the_modified_date(); ?></span>
				</div>
			</header>

			<?php if ( basepress_show_restricted_teaser() ) { ?>
			<div class="article-teaser">
			<?php echo basepress_article_teaser(); ?>
			</div>
			<?php } ?>

			<div class="bpress-restricted-notice"><?php echo basepress_restricted_notice(); ?></div>

			<?php if ( ! is_user_logged_in() && basepress_show_restricted_login() ) { ?>
			<div class="bpress-login">
			<?php
			$args = array(
				'echo'           => true,
				'remember'       => true,
				'redirect'       => ( is_ssl() ? 'https://' : 'http://' ) . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'],
				'form_id'        => 'bpress-loginform',
				'id_username'    => 'user_login',
				'id_password'    => 'user_pass',
				'id_remember'    => 'rememberme',
				'id_submit'      => 'bpress-restricted-login-submit',
				'label_username' => __( 'Username' ),
				'label_password' => __( 'Password' ),
				'label_remember' => __( 'Remember Me' ),
				'label_log_in'   => __( 'Log In' ),
				'value_username' => '',
				'value_remember' => false,
			);
			wp_login_form( $args );
			?>
			</div>
			<?php } ?>
		</main>

	</div><!-- content area -->

	<!-- Sidebar -->
	<?php if ( is_active_sidebar( 'basepress-sidebar' ) ) : ?>
	<aside class="bpress-sidebar bpress-float-left" role="complementary">
		<?php dynamic_sidebar( 'basepress-sidebar' ); ?>
	</aside>
	<?php endif; ?>
	
</div><!-- .wrap -->

<?php get_footer( 'basepress' ); ?>
