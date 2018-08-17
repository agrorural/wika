<?php

namespace App;

add_action( 'init',  __NAMESPACE__ . '\\create_my_post_types' );
function create_my_post_types() {
	$labels_producto = array(
		'name'               => _x( 'Productos', 'post type general name', 'incl' ),
		'singular_name'      => _x( 'Producto', 'post type singular name', 'incl' ),
		'menu_name'          => _x( 'Productos', 'admin menu', 'incl' ),
		'name_admin_bar'     => _x( 'Producto', 'Agregar Nuevo on admin bar', 'incl' ),
		'add_new'            => _x( 'Agregar Nuevo', 'Producto', 'incl' ),
		'add_new_item'       => __( 'Agregar Nuevo Producto', 'incl' ),
		'new_item'           => __( 'Nuevo Producto', 'incl' ),
		'edit_item'          => __( 'Editar Producto', 'incl' ),
		'view_item'          => __( 'Ver Producto', 'incl' ),
		'all_items'          => __( 'Todos', 'incl' ),
		'search_items'       => __( 'Buscar Productos', 'incl' ),
		'parent_item_colon'  => __( 'Producto Padre:', 'incl' ),
		'not_found'          => __( 'Ningún Producto encontrado.', 'incl' ),
		'not_found_in_trash' => __( 'Ningún Producto encontrado en la Papelera.', 'incl' )
	);

	$args_producto = array(
		'labels'             => $labels_producto,
    'description'        => __( 'Productos de AGRO RURAL.', 'incl' ),
		'public'             => true,
		'publicly_queryable' => true,
		'show_ui'            => true,
		'show_in_menu'       => true,
		'query_var'          => true,
		'capability_type' => 'producto',
			'capabilities' => array(
				'publish_posts' => 'publish_productos',
				'edit_posts' => 'edit_productos',
				'edit_others_posts' => 'edit_others_productos',
				'delete_posts' => 'delete_productos',
				'delete_others_posts' => 'delete_others_productos',
				'read_private_posts' => 'read_private_productos',
				'edit_post' => 'edit_producto',
				'delete_post' => 'delete_producto',
				'read_post' => 'read_producto',
			),
		'has_archive'        => true,
		'hierarchical'       => true,
		'rewrite'            => array( 'slug' => 'productos' ),
		'menu_position'      => 10,
		'menu_icon'			 => 'dashicons-cart',
		'supports'           => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt', 'comments' )
	);

	register_post_type('producto', $args_producto);
}

add_action( 'init',  __NAMESPACE__ . '\\create_my_taxonomies', 0 );
function create_my_taxonomies() {
	// Agregar Nuevo taxonomy, make it hierarchical (like categories)
	$labels_producto_productor = array(
		'name'              => _x( 'Productores', 'taxonomy general name' ),
		'singular_name'     => _x( 'Productor', 'taxonomy singular name' ),
		'search_items'      => __( 'Buscar productores' ),
		'all_items'         => __( 'Todos los  productores' ),
		'parent_item'       => __( 'Productor Padre' ),
		'parent_item_colon' => __( 'Productor Padre:' ),
		'edit_item'         => __( 'Editar productor' ),
		'update_item'       => __( 'Actualizar productor' ),
		'add_new_item'      => __( 'Agregar Nuevo productor' ),
		'new_item_name'     => __( 'Nombre de Nuevo productor' ),
		'menu_name'         => __( 'Productores' )
	);

	$args_producto_productor = array(
		'public' 			=> true,
		'hierarchical'      => false,
		'labels'            => $labels_producto_productor,
		'show_ui'           => true,
		'show_admin_column' => true,
		'query_var'         => true,
		'capabilities'		=> array(
			'manage_terms' => 'manage_productor',
			'edit_terms' => 'edit_productor',
			'delete_terms' => 'delete_productor',
			'assign_terms' => 'assign_productor'
		)
	);

	register_taxonomy( 'productor', 'producto', $args_producto_productor );
}

add_filter( 'map_meta_cap',  __NAMESPACE__ . '\\producto_meta_cap', 10, 4 );

function producto_meta_cap( $caps, $cap, $user_id, $args ) {

	/* If editing, deleting, or reading a producto, get the post and post type object. */
	if ( 'edit_producto' == $cap || 'delete_producto' == $cap || 'read_producto' == $cap ) {
		$post = get_post( $args[0] );
		$post_type = get_post_type_object( $post->post_type );

		/* Set an empty array for the caps. */
		$caps = array();
	}

	/* If editing a producto, assign the required capability. */
	if ( 'edit_producto' == $cap ) {
		if ( $user_id == $post->post_author )
			$caps[] = $post_type->cap->edit_posts;
		else
			$caps[] = $post_type->cap->edit_others_posts;
	}

	/* If deleting a producto, assign the required capability. */
	elseif ( 'delete_producto' == $cap ) {
		if ( $user_id == $post->post_author )
			$caps[] = $post_type->cap->delete_posts;
		else
			$caps[] = $post_type->cap->delete_others_posts;
	}

	/* If reading a private producto, assign the required capability. */
	elseif ( 'read_producto' == $cap ) {

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
