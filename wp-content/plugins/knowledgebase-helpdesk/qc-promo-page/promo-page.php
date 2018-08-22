<?php

/**
 * Register a custom promo menu page.
 */
function qcpromo_kbxhd_add_promo_menu_page_qcopd(){

	$menu_slug = 'edit.php?post_type=kbx_knowledgebase';
	
	add_submenu_page(
        $menu_slug,
        __( 'More WordPress Goodies for You!', 'quantumcloud' ),
        __( 'More', 'quantumcloud' ),
        'manage_options',
        'kbx-qcpro-promo-page',
        'qcpromo_kbxhd_add_promo_page_callaback_qcopd'
    );
	
}

add_action( 'admin_menu', 'qcpromo_kbxhd_add_promo_menu_page_qcopd' );
 
/**
 * Display promo page content
 */
function qcpromo_kbxhd_add_promo_page_callaback_qcopd()
{
    //Include Part File
	require_once('main-part-file.php');  
}