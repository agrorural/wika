<?php

/**
 * Replace the archive temlate for the knowledgebase. Functions archive_template.
 *
 * @param  string $template Default Archive Template location.
 * @return string Modified Archive Template location
 */

function kbx_load_archive_template( $template ) {

	$template_name = '';
	$located = '';

	if ( is_post_type_archive( 'kbx_knowledgebase' ) ) 
	{
		$template_name = 'archive-kbx_knowledgebase.php';
	}
	
	if ( is_single() && 'kbx_knowledgebase' == get_post_type() ) 
	{
		$template_name = 'single-kbx_knowledgebase.php';
	}

	if ( is_tax( 'kbx_tag' ) ) 
	{
		$template_name = 'archive-kbx_knowledgebase.php';
	}

	if ( is_search() && isset($_GET['kbx-query']) && $_GET['kbx-query'] != "") 
	{
		$template_name = 'search-kbx_knowledgebase.php';
	}

	if ( is_tax( 'kbx_category' ) && ! is_search() ) 
	{
		$template_name = 'taxonomy-kbx_category.php';
	}

	/*if ( '' !== $template_name && '' === locate_template( array( $template_name ) ) ) 
	{
		$template = KBX_DIR . '/views/general/' . $template_name;
	}*/
	
	/*Template Lookup - Child Theme First, Then Parent Theme, Then This Plugin*/
	
	if ( '' !== $template_name && '' === locate_template( array( $template_name ) ) ) 
	{
		// Trim off any slashes from the template name
		$template_name = ltrim( $template_name, '/' );

		// Check child theme first
		if ( file_exists( trailingslashit( get_stylesheet_directory() ) . 'kbhd/' . $template_name ) ) {
			$template = trailingslashit( get_stylesheet_directory() ) . 'kbhd/' . $template_name;

		// Check parent theme next
		} elseif ( file_exists( trailingslashit( get_template_directory() ) . 'kbhd/' . $template_name ) ) {
			$template = trailingslashit( get_template_directory() ) . 'kbhd/' . $template_name;

		// Check theme compatibility last
		} elseif ( file_exists( KBX_DIR . '/views/general/' . $template_name ) ) {
			$template = KBX_DIR . '/views/general/' . $template_name;
		}
	
	}

	return $template;

}

add_filter( 'template_include', 'kbx_load_archive_template' );


/**
 * For knowledgebase search results, set posts_per_page 10.
 *
 * @since 1.1.0
 *
 * @param  object $query The search query object.
 * @return object $query Updated search query object
 */
function wzkb_posts_per_search_page( $query ) {

	if ( ! is_admin() && is_search() && isset( $query->query_vars['post_type'] ) && $query->query_vars['post_type'] === 'wz_knowledgebase' ) {
		$query->query_vars['posts_per_page'] = 10;
	}

	return $query;
}
add_filter( 'pre_get_posts', 'wzkb_posts_per_search_page' );