<?php

namespace App;

add_action( 'init',  __NAMESPACE__ . '\\create_my_post_types' );
function create_my_post_types() {
	$labels_faq = array(
		'name'               => _x( 'Preguntas Frecuentes', 'post type general name', 'wika' ),
		'singular_name'      => _x( 'Pregunta Frecuente', 'post type singular name', 'wika' ),
		'menu_name'          => _x( 'Preguntas Frecuentes', 'admin menu', 'wika' ),
		'name_admin_bar'     => _x( 'Pregunta Frecuente', 'Agregar Nuevo on admin bar', 'wika' ),
		'add_new'            => _x( 'Agregar Nueva', 'Pregunta Frecuente', 'wika' ),
		'add_new_item'       => __( 'Agregar Nueva Pregunta Frecuente', 'wika' ),
		'new_item'           => __( 'Nueva Pregunta Frecuente', 'wika' ),
		'edit_item'          => __( 'Editar Pregunta Frecuente', 'wika' ),
		'view_item'          => __( 'Ver Pregunta Frecuente', 'wika' ),
		'all_items'          => __( 'Todas', 'wika' ),
		'search_items'       => __( 'Buscar Preguntas Frecuentes', 'wika' ),
		'parent_item_colon'  => __( 'Pregunta Frecuente Padre:', 'wika' ),
		'not_found'          => __( 'Ninguna Pregunta Frecuente encontrada.', 'wika' ),
		'not_found_in_trash' => __( 'Ninguna Pregunta Frecuente encontrada en la Papelera.', 'wika' )
	);

	$args_faq = array(
		'labels'             => $labels_faq,
    'description'        => __( 'Preguntas Frecuentes de AGRO RURAL.', 'wika' ),
		'public'             => true,
		'publicly_queryable' => true,
		'show_ui'            => true,
		'show_in_menu'       => true,
		'query_var'          => true,
		'capability_type' => 'faq',
			'capabilities' => array(
				'publish_posts' => 'publish_faqs',
				'edit_posts' => 'edit_faqs',
				'edit_others_posts' => 'edit_others_faqs',
				'delete_posts' => 'delete_faqs',
				'delete_others_posts' => 'delete_others_faqs',
				'read_private_posts' => 'read_private_faqs',
				'edit_post' => 'edit_faq',
				'delete_post' => 'delete_faq',
				'read_post' => 'read_faq',
			),
		'has_archive'        => true,
		'hierarchical'       => true,
		'rewrite'            => array( 'slug' => 'faqs' ),
		'menu_position'      => 10,
		'menu_icon'			 => 'dashicons-megaphone',
		'supports'           => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt', 'comments' )
	);

	register_post_type('faq', $args_faq);
}

add_action( 'init',  __NAMESPACE__ . '\\create_my_taxonomies', 0 );
function create_my_taxonomies() {
	// Agregar Nuevo taxonomy, make it hierarchical (like categories)
	$labels_faq_sistema = array(
		'name'              => _x( 'Sistemas', 'taxonomy general name' ),
		'singular_name'     => _x( 'Sistema', 'taxonomy singular name' ),
		'search_items'      => __( 'Buscar Sistemas' ),
		'all_items'         => __( 'Todos los  Sistemas' ),
		'parent_item'       => __( 'Sistema Padre' ),
		'parent_item_colon' => __( 'Sistema Padre:' ),
		'edit_item'         => __( 'Editar Sistema' ),
		'update_item'       => __( 'Actualizar Sistema' ),
		'add_new_item'      => __( 'Agregar Nuevo Sistema' ),
		'new_item_name'     => __( 'Nombre de Nuevo Sistema' ),
		'menu_name'         => __( 'Sistemas' )
	);

	$args_faq_sistema = array(
		'public' 			=> true,
		'hierarchical'      => true,
		'labels'            => $labels_faq_sistema,
		'show_ui'           => true,
		'show_admin_column' => true,
    'query_var'         => true,
    'rewrite'            => array( 'slug' => 'faqs/sistemas' ),
		'capabilities'		=> array(
			'manage_terms' => 'manage_sistema',
			'edit_terms' => 'edit_sistema',
			'delete_terms' => 'delete_sistema',
			'assign_terms' => 'assign_sistema'
		)
	);

	register_taxonomy( 'sistema', 'faq', $args_faq_sistema );
}

add_filter( 'map_meta_cap',  __NAMESPACE__ . '\\faq_meta_cap', 10, 4 );

function faq_meta_cap( $caps, $cap, $user_id, $args ) {

	/* If editing, deleting, or reading a producto, get the post and post type object. */
	if ( 'edit_faq' == $cap || 'delete_faq' == $cap || 'read_faq' == $cap ) {
		$post = get_post( $args[0] );
		$post_type = get_post_type_object( $post->post_type );

		/* Set an empty array for the caps. */
		$caps = array();
	}

	/* If editing a producto, assign the required capability. */
	if ( 'edit_faq' == $cap ) {
		if ( $user_id == $post->post_author )
			$caps[] = $post_type->cap->edit_posts;
		else
			$caps[] = $post_type->cap->edit_others_posts;
	}

	/* If deleting a producto, assign the required capability. */
	elseif ( 'delete_faq' == $cap ) {
		if ( $user_id == $post->post_author )
			$caps[] = $post_type->cap->delete_posts;
		else
			$caps[] = $post_type->cap->delete_others_posts;
	}

	/* If reading a private producto, assign the required capability. */
	elseif ( 'read_faq' == $cap ) {

		if ( 'private' != $post->post_status )
			$caps[] = 'read';
		elseif ( $user_id == $post->post_author )
			$caps[] = 'read';
		else
			$caps[] = $post_type->cap->read_private_posts;
	}

	/* Return the capabilities required by the user. */
	return $caps;
}
