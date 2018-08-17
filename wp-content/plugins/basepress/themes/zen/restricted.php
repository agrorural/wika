<?php
/*
 * This id the template for the restricted content page
 */

$is_single_product = basepress_is_single_product();

get_header( 'basepress' ); ?>

<!-- Main BasePress wrap -->
<div class="bpress-wrap">
	
	<!-- Product title -->
	<header class="bpress-main-header">
		<h2 class="bpress-product-title"><?php echo ( $is_single_product ? '' : $product->name ); ?></h2>
	</header>

	<!-- Add breadcrumbs -->
	<div class="bpress-crumbs-wrap">
		<?php basepress_breadcrumbs(); ?>
	</div>

	<div class="bpress-content-area bpress-float-left">
		
		<!-- Add searchbar -->
		<div class="bpress-card">
			<?php basepress_searchbar(); ?>
		</div>

		<!-- Add main content -->
		<main class="bpress-main" role="main">
			<article id="post-<?php the_ID(); ?>">
				<header class="bpress-post-header">
					<h1<?php echo $header_class; ?>>
						<?php if ( basepress_show_post_icon() ) { ?>
							<span aria-hidden="true" class="<?php echo basepress_post_icon( get_the_ID() ); ?>"></span>
						<?php } ?>
						<?php the_title(); ?>
					</h1>
				</header>

				<div class="bpress-card">

					<?php if ( basepress_show_restricted_teaser() ) { ?>
					<div class="bpress-card-body">
						<div class="article-teaser"><?php echo basepress_article_teaser(); ?></div>
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
				</div>
			</article>
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
