<?php

add_action('wp_head', 'kbx_ajax_ajaxurl');

function kbx_ajax_ajaxurl() {

   echo '<script type="text/javascript">
           var ajaxurl = "' . admin_url('admin-ajax.php') . '";
         </script>';
}

add_action( 'wp_ajax_kbx_search_article', 'func_kbx_search_article' );
add_action( 'wp_ajax_nopriv_kbx_search_article', 'func_kbx_search_article' );

function func_kbx_search_article()
{
	
	global $wpdb;

	$data['status'] = 'false';

	$searchKey = trim( sanitize_text_field($_POST['post_key']) );

	$results = kbx_get_search_results( $searchKey );

	ob_clean();

	$list = "";

	
	if( count($results) > 0 )
	{
		$data['status'] = 'true';

		$list = "";

		foreach( $results as $article )
		{

			$list .= '<li class="">
						<a href="'.get_permalink($article->ID).'">
							'.get_the_title($article->ID).'
						</a>
					</li>';

		}

	}
	else
	{
		if( trim($list) == "" )
			{
				$list .= '<li class="">
								<a href="#">
									'.__('No result found.', 'kbx-qc').'
								</a>
							</li>';
			}
	}

	$data['list'] = $list;
	
	echo json_encode($data);
	
	die();
}

/*******************************
 * This function will return the 
 * results set
 *******************************/
function kbx_get_search_results( $search_key )
{
	global $wpdb, $kbx_options;

	$results = false;

	$searchMode = $kbx_options['search_mode'];
	$sortingMode = $kbx_options['search_orderby'];

	$use_fulltext_bn = false;
	$bydate = false;

	if( $searchMode == 'fulltext-search-bn' ){
		$use_fulltext_bn = true;
	}

	if( $sortingMode == 'by-date' || $searchMode == 'like-search' ){
		$bydate = true;
	}

	//$search_info[0] holds full search string
	//$search_info[1] holds keywords exists in the string

	$search_info = get_kbxsearch_terms( $search_key );

	//First check the transient, if matched with cached data
	// Get search transient
	$search_query_transient = 'kbx_' . preg_replace( '/[^A-Za-z0-9\-]/', '', str_replace( ' ', '', $search_key ) );

	/**
	 * Filter name of the search transient
	 *
	 * @param	string	$search_query_transient	Transient name
	 * @param	array	$search_query	Search query
	 */
	$search_query_transient = apply_filters( 'kbxsearch_transient_name', $search_query_transient, $search_key );
	$search_query_transient = substr( $search_query_transient, 0, 40 );	// Name of the transient limited to 40 chars

	$matches = get_transient( $search_query_transient );

	if ( $matches ) {

		if ( isset( $matches['search_query'] ) ) 
		{

			if ( $matches['search_query'] == $search_key ) 
			{
				$results = $matches[0];

				return $results;

			}
		}
	}

	if( ! $results )
	{
		$sql = kbxsearch_sql_prepare($search_info, $use_fulltext_bn, $bydate);

		$results = $wpdb->get_results( $sql );
	}

	if ( ! $results ) 
	{
		$sql = kbxsearch_sql_prepare( $search_info, 1, $bydate );

		$results = $wpdb->get_results( $sql );
	}

	// If no results are found then force LIKE mode
	if ( ! $results ) 
	{
		// strip out all the fancy characters that fulltext would use
		$search_key = addslashes_gpc( $search_key );
		$search_key = preg_replace( '/, +/', ' ', $search_key );
		$search_key = str_replace( ',', ' ', $search_key );
		$search_key = str_replace( '"', ' ', $search_key );
		$search_key = trim( $search_key );
		$search_words = explode( ' ', $search_key );

		$s_array[0] = $searchKey;	// Save original query at [0]
		$s_array[1] = $search_words;	// Save array of terms at [1]

		$search_info = $s_array;

		$sql = kbxsearch_sql_prepare( $search_info, 0, $bydate );

		$results = $wpdb->get_results( $sql );
	}

	/*Set Transient for Future Query Serving*/
	$matches[0] = $results;
	$matches['search_query'] = $search_key;

	if ( $kbx_options['enable_caching'] == 1 ) 
	{
		set_transient( $search_query_transient, $matches, 7200 );
	}

	return $results;
}
