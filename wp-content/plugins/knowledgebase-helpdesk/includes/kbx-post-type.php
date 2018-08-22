<?php
/**
 * 
 * Knowledgebase Custom Post Type.
 * 
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}


/**
 * Register Knowledgebase Post Type.
 */
function kbx_register_post_type() {

	$slug = kbx_get_option( 'kb_slug', 'knowledgebase' );

	$archives = defined( 'KBX_DISABLE_ARCHIVE' ) && KBX_DISABLE_ARCHIVE ? false : $slug;
	$rewrite  = defined( 'KBX_DISABLE_REWRITE' ) && KBX_DISABLE_REWRITE ? false : array( 'slug' => $slug, 'with_front' => false );

	$ptlabels = array(
		'name'               => _x( 'Knowledgebase', 'Post Type General Name', 'kbx-qc' ),
		'singular_name'      => _x( 'Knowledgebase', 'Post Type Singular Name', 'kbx-qc' ),
		'menu_name'          => __( 'Knowledgebase', 'kbx-qc' ),
		'name_admin_bar'     => __( 'Knowledgebase Article', 'kbx-qc' ),
		'parent_item_colon'  => __( 'Parent Article', 'kbx-qc' ),
		'all_items'          => __( 'All Articles', 'kbx-qc' ),
		'add_new_item'       => __( 'Add New Article', 'kbx-qc' ),
		'add_new'            => __( 'Add New Article', 'kbx-qc' ),
		'new_item'           => __( 'New Article', 'kbx-qc' ),
		'edit_item'          => __( 'Edit Article', 'kbx-qc' ),
		'update_item'        => __( 'Update Article', 'kbx-qc' ),
		'view_item'          => __( 'View Article', 'kbx-qc' ),
		'search_items'       => __( 'Search Article', 'kbx-qc' ),
		'not_found'          => __( 'Not found', 'kbx-qc' ),
		'not_found_in_trash' => __( 'Not found in Trash', 'kbx-qc' ),
	);

	/**
	 * Filter the labels of the post type.
	 *
	 * @param array $ptlabels Post type lables
	 */
	$ptlabels = apply_filters( 'kbx_post_type_labels', $ptlabels );

	$ptargs = array(
		'label'              => __( 'kbx_knowledgebase', 'kbx-qc' ),
		'description'        => __( 'Knowledgebase', 'kbx-qc' ),
		'labels'             => $ptlabels,
		//'supports'           => array( 'title', 'editor', 'thumbnail', 'excerpt', 'revisions', 'comments' ),
		'supports'           => array( 'title', 'editor', 'thumbnail', 'excerpt', 'revisions' ),
		'taxonomies'         => array( 'kbx_category', 'kbx_tag' ),
		'public'             => true,
		'hierarchical'       => true,
		'menu_position'      => 5,
		'menu_icon'          => 'dashicons-book-alt',
		'map_meta_cap'       => true,
		'has_archive'        => $archives,
		'rewrite'            => $rewrite,
	);

	/**
	 * Filter the arguments passed to register the post type.
	 *
	 * @param array $ptargs Post type arguments
	 */
	$ptargs = apply_filters( 'kbx_post_type_args', $ptargs );

	register_post_type( 'kbx_knowledgebase', $ptargs );
    kbx_permalink_handler();

}

add_action( 'init', 'kbx_register_post_type' );


/**
 * 
 * Register Knowledgebase Custom Taxonomies.
 *
 */
function kbx_register_taxonomies() {

	$catslug = kbx_get_option( 'category_slug', 'kb-sections' );
	$tagslug = kbx_get_option( 'tag_slug', 'kb-tags' );

	$args = array(
		'hierarchical'      => true,
		'show_admin_column' => true,
		'show_tagcloud'     => false,
		'rewrite'           => array( 'slug' => $catslug, 'with_front' => true, 'hierarchical' => true ),
	);

	// Now register categories for the Knowledgebase.
	$catlabels = array(
		'name'                       => _x( 'Sections', 'Taxonomy General Name', 'kbx-qc' ),
		'singular_name'              => _x( 'Section', 'Taxonomy Singular Name', 'kbx-qc' ),
		'menu_name'                  => __( 'Sections', 'kbx-qc' ),
		'all_items'                  => __( 'All Sections', 'kbx-qc' ),
		'parent_item'                => __( 'Parent Section', 'kbx-qc' ),
		'parent_item_colon'          => __( 'Parent Section:', 'kbx-qc' ),
		'new_item_name'              => __( 'New Section Name', 'kbx-qc' ),
		'add_new_item'               => __( 'Add New Section', 'kbx-qc' ),
		'edit_item'                  => __( 'Edit Section', 'kbx-qc' ),
		'update_item'                => __( 'Update Section', 'kbx-qc' ),
		'view_item'                  => __( 'View Section', 'kbx-qc' ),
		'separate_items_with_commas' => __( 'Separate sections with commas', 'kbx-qc' ),
		'add_or_remove_items'        => __( 'Add or remove sections', 'kbx-qc' ),
		'choose_from_most_used'      => __( 'Choose from the most used', 'kbx-qc' ),
		'popular_items'              => __( 'Popular Sections', 'kbx-qc' ),
		'search_items'               => __( 'Search Sections', 'kbx-qc' ),
		'not_found'                  => __( 'Not Found', 'kbx-qc' ),
		'no_terms'                   => __( 'No sections', 'kbx-qc' ),
		'items_list'                 => __( 'Sections list', 'kbx-qc' ),
		'items_list_navigation'      => __( 'Sections list navigation', 'kbx-qc' ),
	);

	/**
	 * Filter the labels of the custom categories.
	 *
	 * @param array $catlabels Category labels
	 */
	$args['labels'] = apply_filters( 'kbx_cat_labels', $catlabels );

	register_taxonomy(
		'kbx_category',
		array( 'kbx_knowledgebase' ),
		/**
		 * Filter the arguments of the custom categories.
		 *
		 * @param array $catlabels Category labels
		 */
		apply_filters( 'kbx_cat_args', $args )
	);

	// Now register tags for the Knowledgebase.
	$taglabels = array(
		'name'          => _x( 'Tags', 'Taxonomy General Name', 'kbx-qc' ),
		'singular_name' => _x( 'Tag', 'Taxonomy Singular Name', 'kbx-qc' ),
		'menu_name'     => __( 'Tags', 'kbx-qc' ),
	);

	/**
	 * Filter the labels of the custom tags.
	 *
	 * @param array $taglabels Tags labels
	 */
	$args['labels'] = apply_filters( 'kbx_tag_labels', $taglabels );

	$args['hierarchical']    = false;
	$args['show_tagcloud']   = true;
	$args['rewrite']['slug'] = $tagslug;

	register_taxonomy(
		'kbx_tag',
		array( 'kbx_knowledgebase' ),
		/**
		 * Filter the arguments of the custom tags.
		 *
		 * @since 1.2.0
		 *
		 * @param array $args Tag arguments
		 */
		apply_filters( 'kbx_tag_args', $args )
	);
	//Insert the default kbx-section as uncategories section.
    wp_insert_term(
        'Uncategorized', // the term
        'kbx_category', // the taxonomy
        array(
                'description'=> 'Defualt section for articles.',
                'slug' => 'uncategorized'
              )
        );
    kbx_permalink_handler();

}

add_action( 'init', 'kbx_register_taxonomies' );

