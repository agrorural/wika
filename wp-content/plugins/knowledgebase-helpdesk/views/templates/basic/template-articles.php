<section class="kbx-articles">
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

	<?php if( $query->have_posts() ) : ?>

	<ul class="articleList">
            
        <?php while( $query->have_posts() ) : $query->the_post() ?>
        <li>
        	<a href="<?php the_permalink(); ?>">
        		<i class="fa fa-file-text-o"></i>
        		<span>
        			<?php the_title(); ?>
        		</span>
        	</a>
        </li>
    	<?php endwhile;// wp_reset_postdata(); ?>
    
	</ul>

	<section class="pagination">

		<?php
			$args = array();
			echo paginate_links( $args );
       /* $big = 999999999; // need an unlikely integer
        echo paginate_links( array(
            'base' => str_replace( $big, '%#%', get_pagenum_link( $big ) ),
            'format' => '?paged=%#%',
            'current' => max( 1, get_query_var('paged') ),
            'total' => $query->max_num_pages
        ) );*/
		?>
    </section>

	<?php else : ?>

	<p>
		<?php _e('No articles found under this section.', 'kbx-qc'); ?>
	</p>

	<?php endif; ?>

</section>