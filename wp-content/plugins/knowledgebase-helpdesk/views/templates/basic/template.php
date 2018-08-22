<!--Adding Template Specific Style -->

<div class="kb-front-main-div">
	
	<?php if( $show_search_form == 'true' ) : ?>

	<?php echo kbx_get_search_form(); ?>

	<?php endif; ?>

	<section id="contentArea" class="container-fluid">
		<div id="noResults" style="display:none;">
			No results found
		</div>
		
		<?php if( $show_section_box == 'true' ) : ?>

		<!-- Site has only one collection -->
		<section class="category-list">

			<?php
				$terms = get_terms( 'kbx_category', array(
				    'hide_empty' => true,
				) );
			?>

			<?php foreach( $terms as $term ) : ?>

			<a class="category" id="category-<?php $term->term_id; ?>" href="<?php echo get_term_link($term->term_id); ?>">
				<h3><?php echo $term->name; ?></h3>
				<p>
				</p>
				<p class="article-count">
					<span class="notranslate"><?php echo $term->count; ?></span> articles
				</p>
			</a>
			<!-- /category -->

			<?php endforeach; ?>

		</section>
		<!-- /category-list -->

		<?php endif; ?>

		<?php if( $show_article_title == 'true' ) : ?>
			
			<section class="kbx-article-ques">
				
			</section>

		<?php endif; ?>

	</section>
</div>