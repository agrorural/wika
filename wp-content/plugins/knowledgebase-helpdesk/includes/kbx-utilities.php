<?php

/*******************************
 * Print pagination links
 *******************************/

function kbx_get_pagination_links($numpages = '', $pagerange = '', $paged = '')
{

    if (empty($pagerange)) {
        $pagerange = 2;
    }

    if (empty($paged)) {
        $paged = 1;
    }

    if ($numpages == '') 
    {
        global $wp_query;
        $numpages = $wp_query->max_num_pages;
        if (!$numpages) {
            $numpages = 1;
        }
    }

    $pagination_args = array(
        'base'               => str_replace('%_%', 1 == $paged ? '' : "?page=%#%", "?page=%#%"),
        'format'             => '?page=%#%',
        'total'              => $numpages,
        'current'            => $paged,
        'show_all'           => false,
        'end_size'           => 1,
        'mid_size'           => 2,
        'prev_next'          => true,
        'prev_text'          => __('&laquo;'),
        'next_text'          => __('&raquo;'),
        'type'               => 'plain',
        'add_args'           => false,
        'add_fragment'       => '',
        'before_page_number' => '',
        'after_page_number'  => ''
    );

    $paginate_links = paginate_links($pagination_args);

    return $paginate_links;

}

/*******************************
 * Generate and Show Glossary
 *******************************/
function get_glossary_links()
{
	ob_start();

	$glossary_array = array();
	$keys_array = array();

	//Query Parameters
	$kb_args = array(
		'post_type' => 'kbx_knowledgebase',
		'orderby' => 'title',
		'order' => 'ASC',
		'posts_per_page' => -1,
	);

	// The Query
	$query = new WP_Query( $kb_args );

	if( $query->have_posts() )
	{
		while( $query->have_posts() )
		{
			$query->the_post();

			$gMetaTerm = get_post_meta( get_the_ID(), 'kpm_gterm', true);

			if( $gMetaTerm != "" && !in_array($gMetaTerm, $glossary_array))
			{
				array_push($glossary_array, trim($gMetaTerm));
			}
    	}
    }

    wp_reset_postdata();

    if( count($glossary_array) > 0 )
    {
    	foreach ($glossary_array as $key => $value) {
    		$trimmedLetter = substr($value, 0, 1);
    		array_push($keys_array, strtolower($trimmedLetter));
    	}
    }
    else
    {
    	echo '<div class="kbx-not-found">No glossary terms was found in the database.</div>';
    }

    $sorted = sort($keys_array);

    $page_permalink = get_permalink();

    $selected = '';

    if( !isset($_GET['glossary']) || $_GET['glossary'] == ""){
		$selected = "g-selected";
	}

   	echo '<div class="glossary-letters">';

   	echo '<ul>';

   	echo '<li><a class="'.$selected.'" href="'.$page_permalink.'">'.__('All', 'kbx-qc').'</a></li>';

   	$class = "";
   	
   	foreach ($keys_array as $key => $value) 
   	{
   		if( isset($_GET['glossary']) && $_GET['glossary'] == $value){
   			$class = "g-selected";
   		}

   		echo '<li><a class="'.$class.'" href="'.$page_permalink.'?glossary='.$value.'">'.strtoupper($value).'</a></li>';

   		$class = "";
   	}

   	echo '</ul>';

   	echo '</div>';


	$content = ob_get_clean();

	return $content;
}

/*******************************
 * Filter meta key from the generated
 * query to compare wildcard, only the first character
 *******************************/
add_filter( 'posts_where', function ( $where, \WP_Query $q )
{ 
    // Check for our custom query var
    if ( true !== $q->get( 'wildcard_on_key' ) )
        return $where;

    // Lets filter the clause
    $where = str_replace( 'meta_value LIKE \'%', 'meta_value LIKE \'', $where );

    return $where;
}, 10, 2 );


/*******************************
 * Get search form
 *******************************/
function kbx_get_search_form()
{
	ob_start();

	?>

	<section id="docsSearch">
		<form action="<?php echo home_url('/'); ?>" method="GET" id="searchBar" autocomplete="off">
			<input type="hidden" name="s" id="s" value="" class="kbx-hidden-search">
			<input id="kbx-query" name="kbx-query" title="search-query" class="search-query" placeholder="Search the knowledge base" value="" type="text">
			<button type="submit">
				<i class="icon-search lp"></i>
				<span>Search</span>
			</button>
			<div id="serp-dd" style="display: none;">
				<ul class="result">
				</ul>
			</div>
		</form>
	</section>

	<?php

	$content = ob_get_clean();

	return $content;
}

/*******************************
 * Generate and Serve post after
 * block
 *******************************/

function kbx_after_single_content( $content ) {    
    
    global $post;

    $post_id = $post->ID;
    $post_type = $post->post_type;

    $total_like = get_post_meta($post_id, 'kpm_upvotes', true);
    $total_views = get_post_meta($post_id, 'kpm_views', true);

    if( $total_like == "" ){
      $total_like = 0;
    }

    if( $total_views == "" ){
      $total_views = 0;
    }

    $appended_contnet = "";

    if( is_single() && $post_type == 'kbx_knowledgebase' ) 
    {
        $appended_contnet .= '<div class="kbx-post-single-stats">';
        $appended_contnet .= '<div class="kbx-stats">';
        $appended_contnet .= '<a href="#" title="Like this Article" id="kbx-like-pid-'.$post_id.'" data-article-id="'.$post_id.'" class="kbx-vote-article kbx-like-btn"><div class="kbx-post-like kbx-inline">';
        $appended_contnet .= '<span class="kbx-like-icon kbx-icon">';
        $appended_contnet .= '<i class="fa fa-thumbs-up"></i>';
        $appended_contnet .= '</span>';
        $appended_contnet .= '<span class="kbx-like-counter kbx-counter">';
        $appended_contnet .= $total_like;
        $appended_contnet .= '</span>';
        $appended_contnet .= '</div></a>';
        $appended_contnet .= '<a href="#" title="Total Views"><div class="kbx-post-views kbx-inline" id="kbx-views-pid-'.$post_id.'">';
        $appended_contnet .= '<span class="kbx-view-icon kbx-icon">';
        $appended_contnet .= '<i class="fa fa-eye"></i>';
        $appended_contnet .= '</span>';
        $appended_contnet .= '<span class="kbx-view-counter kbx-counter">';
        $appended_contnet .= $total_views;
        $appended_contnet .= '</span>';
        $appended_contnet .= '</div></a>';
        $appended_contnet .= '</div>';
        $appended_contnet .= '</div>';
    }

    return $content . $appended_contnet;
}

add_filter( 'the_content', 'kbx_after_single_content' );


/*******************************
 * Update post views
 *******************************/
function kbx_wpb_set_post_views($postID) 
{
    
    $count_key = 'kpm_views';
    $count = get_post_meta($postID, $count_key, true);

    if($count == '')
    {
        $count = 0;
        delete_post_meta($postID, $count_key);
        add_post_meta($postID, $count_key, $count);
    }
    else
    {
        $count++;
        update_post_meta($postID, $count_key, $count);
    }
}

function kbx_wpb_track_post_views($post_id) 
{

    global $post;

    if( !is_single() || is_user_logged_in() ){
        return;
    }

    if( $post->post_type != 'kbx_knowledgebase' ){
        return;
    }

    if( !isset($_SESSION['kbx-post-views']) )
    {
        $_SESSION['kbx-post-views'] = array();
    }

    $current_ss_cokie = "";

    if( $post->post_type == 'kbx_knowledgebase' )
    {

      if( !in_array($post->ID, $_SESSION['kbx-post-views']) )
      {

        if ( !is_single() ) return;

        if ( empty ( $post_id) ) {
            $post_id = $post->ID;    
        }

        kbx_wpb_set_post_views($post_id);

        $_SESSION['kbx-post-views'][] = $post_id;

      }

    }

}

add_action( 'wp_head', 'kbx_wpb_track_post_views');

/*******************************
 * Widget Output Markup
 *******************************/
function kbx_get_widget_display( $sort_by = null, $limit )
{
    if( !isset($sort_by) || $sort_by == null )
    {
        $sort_by = 'date';
    }

    if( !isset($limit) || $limit == '' )
    {
        $limit = 5;
    }

    ob_start();

    $orderby = "";
    $order = "";
    $meta_key = "";

    if( $sort_by == 'date' ){
        $orderby = 'date';
        $order = "DESC";
    }

    if( $sort_by == 'popularity' ){
        $orderby  = array( 'meta_value_num' => 'DESC' );
        $meta_key = 'kpm_upvotes';
    }

    if( $sort_by == 'views' ){
        $orderby  = array( 'meta_value_num' => 'DESC' );
        $meta_key = 'kpm_views';
    }

    //Query Parameters
    $kb_args = array(
        'post_type' => 'kbx_knowledgebase',
        'orderby' => $orderby,
        'order' => $order,
        'posts_per_page' => $limit,
        'meta_key' => $meta_key,
    );

    $query = new WP_Query( $kb_args );

    ?>

    <ul class="kbx-widget kbx-widget-sortby-<?php echo $sort_by; ?> kbx-widget-articles">
            
        <?php while( $query->have_posts() ) : $query->the_post() ?>
        <li>
            <a href="<?php the_permalink(); ?>">
                <i class="fa fa-file-text-o"></i>
                <span>
                    <?php the_title(); ?>
                </span>
            </a>
        </li>
        <?php endwhile; wp_reset_postdata(); ?>
    
    </ul>

    <?php

    $content = ob_get_clean();

    return $content;
}

/*******************************
 * Custom Breadcrumb
 *******************************/
// Breadcrumbs
function kbx_custom_breadcrumbs() {
       
    // Settings
    $separator          = '&gt;';
    $breadcrums_id      = 'kbx-breadcrumbs';
    $breadcrums_class   = 'kbx-breadcrumbs';
    $home_title         = __('Home', 'kbx-qc');

    $output = "";

    // Get the query & post information
    global $post, $wp_query;
       
    // Do not display on the homepage
    if ( !is_front_page() ) {
       
        // Build the breadcrums
        $output .= '<ul id="' . $breadcrums_id . '" class="' . $breadcrums_class . '">';
           
        // Home page
        $output .= '<li class="item-home"><a class="bread-link bread-home" href="' . get_home_url() . '" title="' . $home_title . '">' . $home_title . '</a></li>';
        $output .= '<li class="separator separator-home"> ' . $separator . ' </li>';
           
        if ( is_archive() && !is_tax() && !is_category() && !is_tag() ) {
              
            $output .= '<li class="item-current item-archive"><strong class="bread-current bread-archive">' . post_type_archive_title($prefix, false) . '</strong></li>';
              
        }

        if ( is_single() ) {
              
            // If post is a custom post type
            $post_type = get_post_type();
              
            // If it is a custom post type display name and link
            if($post_type != 'post') {
                  
                $post_type_object = get_post_type_object($post_type);
                $post_type_archive = get_post_type_archive_link($post_type);
              
                $output .= '<li class="item-cat item-custom-post-type-' . $post_type . '"><a class="bread-cat bread-custom-post-type-' . $post_type . '" href="' . $post_type_archive . '" title="' . $post_type_object->labels->name . '">' . $post_type_object->labels->name . '</a></li>';
                $output .= '<li class="separator"> ' . $separator . ' </li>';
              
            }
       
            $output .= '</ul>';
        }
           
    }

    return $output;
       
}
/*
 * Handling Permalink
 */
if( !function_exists( 'kbx_permalink_handler' ) ) {
    function kbx_permalink_handler(){
        if(!get_option('kbx_parmalink_handled')){
            flush_rewrite_rules();
            update_option('kbx_parmalink_handled',true);
        }
    }
}
