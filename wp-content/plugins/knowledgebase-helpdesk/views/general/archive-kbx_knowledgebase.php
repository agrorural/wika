<?php
/**
 * The template for displaying post type archives
 *
 */

global $wp_query;

get_header();

?>

<div class="kbx-outer-wrapper">

	<section class="kbx-articles">

		<?php 
			$page_title = __('Knowledebase Articles', 'kbx-qc');

			if( is_tax( 'kbx_category' ) )
			{

				$page_title = single_term_title('Knowledebase Section: ', false);;
			}

			if( is_tax( 'kbx_tag' ) )
			{

				$page_title = single_term_title('Knowledebase Tag: ', false);;
			}
		?>

		<header class="page-header">
			<h2 class="page-title">
				<?php echo esc_html( $page_title ); ?>
			</h2>
		</header>

		<div class="clear"></div>

		<?php if( have_posts() ) : ?>

		<div id="categoryHead">
			<div class="sort">
				<form action="" method="GET">
					
					<select name="sort" id="sortBy" title="<?php _e('Sort By', 'kbx-qc'); ?>" onchange="this.form.submit();">
							<option value="" selected="selected"><?php _e('Sort by Default', 'kbx-qc'); ?></option>
							<option value="name"><?php _e('Sort A-Z', 'kbx-qc'); ?></option>
							<option value="popularity"><?php _e('Sort by Popularity', 'kbx-qc'); ?></option>
							<option value="views"><?php _e('Sort by Views', 'kbx-qc'); ?></option>
					</select>
							
				</form>
			</div>
		</div>

		<div class="clear"></div>

		<ul class="articleList">
				
			<?php while( have_posts() ) : the_post() ?>
			<li>
				<a href="<?php the_permalink(); ?>">
					<i class="fa fa-file-text-o"></i>
					<span>
						<?php the_title(); ?>
					</span>
				</a>
			</li>
			<?php endwhile; ?>
		
		</ul>

		<section class="kbx-pagination">
			
			<?php 
				echo paginate_links(); 
			?>

		</section>

		<?php else : ?>

		<p>
			<?php _e('No articles found under this section.', 'kbx-qc'); ?>
		</p>

		<?php endif; ?>

	</section>

</div>

<?php get_footer();


