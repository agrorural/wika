<div id="kbx-glossary-head">
    <div class="kbx-glossary-keys">
    	<?php echo get_glossary_links(); ?>
	</div>
</div>

<div class="clear"></div>

<section class="kbx-articles">

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
    	<?php endwhile; ?>
    
	</ul>

	<section class="kbx-pagination">
        
		<?php 
			echo kbx_get_pagination_links($query->max_num_pages, "", $paged); 
		?>

    </section>

	<?php else : ?>

	<p>
		<?php _e('No articles found under this section.', 'kbx-qc'); ?>
	</p>

	<?php endif; ?>

</section>