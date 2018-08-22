<?php

/**
 * Proper way to enqueue scripts and styles
 */
function kbx_plugin_scripts() {

    wp_enqueue_script( 'jquery' );
    wp_enqueue_script( 'kbx-jquery-modal-js', KBX_ASSETS_URL . '/js/jquery.modal.min.js', array('jquery'));
    wp_enqueue_script( 'kbx-script-js', KBX_ASSETS_URL . '/js/script.js', array('jquery'));
    wp_enqueue_script( 'kbx-general-js', KBX_ASSETS_URL . '/js/general.js', array('jquery'));

    wp_enqueue_style( 'kbx-fontawesome-css', KBX_ASSETS_URL . '/css/font-awesome.min.css');
    wp_enqueue_style( 'kbx-jquery.modal.min-css', KBX_ASSETS_URL . '/css/jquery.modal.min.css');
    wp_enqueue_style( 'kbx-style-css', KBX_ASSETS_URL . '/css/style.css');
    wp_enqueue_style( 'kbx-general-css', KBX_ASSETS_URL . '/css/general.css');

}

add_action( 'wp_enqueue_scripts', 'kbx_plugin_scripts' );

/*Custom CSS*/
if( isset($kbx_options['custom_css']) && trim($kbx_options['custom_css']) != "" )
{
	if( !is_admin() ){
		add_action('wp_head', 'kbx_add_custom_provided_css');
	}
}

function kbx_add_custom_provided_css()
{
 
 global $kbx_options;
 
 ?>
 <style>
 <?php echo $kbx_options['custom_css']; ?>
 </style>
 <?php 
}