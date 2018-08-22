<?php
/**
 * Plugin Name: Knowledgebase Helpdesk
 * Plugin URI: https://www.quantumcloud.com
 * Description: Advanced knowledgebase plugin with helpdesk, glossary and FAQ all in one.
 * Version: 1.5.0
 * Requires at least: 4.0
 * Tested up to: 4.9
 * Author: QuantumCloud
 * Author URI: http://www.quantumcloud.com
 * Text Domain: kbx-qc
 * License: GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 * Domain Path: /languages
 */

// If this file is called directly, then abort execution.
if ( ! defined( 'WPINC' ) ) {
	die( "Aren't you supposed to come here via Administrator Access?" );
}


/**
 * Holds the URL for Knowledgebase-X
 *
 * @since	1.0
 *
 * @var string
 */

$kbx_url = plugins_url() . '/' . plugin_basename( dirname( __FILE__ ) );

//Custom Constants

if ( ! defined( 'KBX_URL' ) ) {
	define('KBX_URL', plugin_dir_url(__FILE__));
}

if ( ! defined( 'KBX_ASSETS_URL' ) ) {
	define('KBX_ASSETS_URL', KBX_URL . "/assets");
}

if ( ! defined( 'KBX_DIR' ) ) {
	define('KBX_DIR', dirname(__FILE__));
}

if ( ! defined( 'KBX_PLUGIN_FILE' ) ) {
	define( 'KBX_PLUGIN_FILE', __FILE__ );
}

/**
 * 
 * Declare $kbx_options global so that it can be accessed in every function
 *
 */

global $kbx_options;

$kbx_options = kbx_get_settings();

/**
 * 
 * Function to load translation files.
 *
 */

function kbx_lang_init() {
	load_plugin_textdomain( 'kbx-qc', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
}

add_action( 'plugins_loaded', 'kbx_lang_init' );

/**
 * Get Settings.
 *
 * Retrieves all plugin settings
 * 
 * @return array wzkb settings
 */
function kbx_get_settings() {

	$settings = get_option( 'kbx_settings' );

	/**
	 * Settings array
	 *
	 * Retrieves all plugin settings
	 * 
	 * @param array $settings Settings array
	 */
	return apply_filters( 'wzkb_get_settings', $settings );
}


/*
 * ----------------------------------------------------------------------------*
 * Include files
 *----------------------------------------------------------------------------
 */

require_once( KBX_DIR . '/admin/kbx-register-settings.php' );
require_once( KBX_DIR . '/includes/kbx-activate-deactivate.php' );
require_once( KBX_DIR . '/includes/kbx-post-type.php' );
require_once( KBX_DIR . '/includes/kbx-add-post-meta.php' );
require_once( KBX_DIR . '/includes/kbx-shortcode.php' );
require_once( KBX_DIR . '/includes/kbx-ajax-search-question.php' );
require_once( KBX_DIR . '/includes/kbx-load-assets.php' );
require_once( KBX_DIR . '/includes/kbx-template-handling.php' );
require_once( KBX_DIR . '/includes/kbx-query.php' );
require_once( KBX_DIR . '/includes/kbx-utilities.php' );
require_once( KBX_DIR . '/includes/kbx-ajax-like-article.php' );
require_once( KBX_DIR . '/includes/kbx-widgets.php' );
require_once( KBX_DIR . '/includes/kbx-floating-search-widget.php' );
//require_once( KBX_DIR . '/includes/kbx-ask-question-widget.php' );

require_once( 'kbx-help.php' );

//Support Page - Updated On - 06-01-2017
require_once('qc-support-promo-page/class-qc-support-promo-page.php');

/*
 ************************************************
 * Dashboard and Administrative Functionality
 ************************************************
 */
if ( is_admin() || ( defined( 'WP_CLI' ) && WP_CLI ) ) {

	/**
	 *  Load the admin pages if we're in the Admin.
	 */
	require_once( KBX_DIR . '/admin/kbx-admin.php' );
	require_once( KBX_DIR . '/admin/kbx-settings-page.php' );
	require_once( KBX_DIR . '/admin/kbx-save-settings.php' );

} // End admin.inc

/*******************************
 * Filter title to add Breadcrumb
 *******************************/
function kbx_modify_archive_query( $query ) 
{
	
	if( is_post_type_archive('kbx_knowledgebase') || is_tax( 'kbx_category' ) || is_tax( 'kbx_tag' ))
	{
		if( isset($_GET['sort']) &&  $_GET['sort'] != "" )
		{
			
			$sortBy = sanitize_text_field( $_GET['sort'] );

			if( isset($sortBy) && $sortBy == 'name' ){
				$orderby = 'title';
				$order   = 'ASC';
				$query->query_vars['order'] = $order;
			}

			if( isset($sortBy) && $sortBy == 'popularity' ){
				$orderby  = array( 'meta_value_num' => 'DESC' );
				$meta_key = 'kpm_upvotes';
				$query->query_vars['meta_key'] = $meta_key;
			}

			if( isset($sortBy) && $sortBy == 'views' ){
				$orderby  = array( 'meta_value_num' => 'DESC' );
				$meta_key = 'kpm_views';
				$query->query_vars['meta_key'] = $meta_key;
			}

			$query->query_vars['orderby'] = $orderby;
		}
		
	}

	return $query;
}

add_action( 'pre_get_posts', 'kbx_modify_archive_query' );

/**
 * Submenu filter function. Tested with Wordpress 4.7.3
 * Sort and order submenu positions to match your custom order.
 */

function kbx_order_index_menu_page( $menu_ord ) 
{

  global $submenu;

  // Enable the next line to see a specific menu and it's order positions
  //echo '<pre>'; print_r( $submenu['edit.php?post_type=kbx_knowledgebase'] ); echo '</pre>'; exit();

  $arr = array();

  $arr[] = $submenu['edit.php?post_type=kbx_knowledgebase'][5];
  $arr[] = $submenu['edit.php?post_type=kbx_knowledgebase'][10];
  $arr[] = $submenu['edit.php?post_type=kbx_knowledgebase'][15];
  $arr[] = $submenu['edit.php?post_type=kbx_knowledgebase'][16];
  $arr[] = $submenu['edit.php?post_type=kbx_knowledgebase'][17];
  $arr[] = $submenu['edit.php?post_type=kbx_knowledgebase'][19];
  $arr[] = $submenu['edit.php?post_type=kbx_knowledgebase'][18];
  
  
  if( isset($submenu['edit.php?post_type=kbx_knowledgebase'][300]) ){
    $arr[] = $submenu['edit.php?post_type=kbx_knowledgebase'][300];
  }

  $submenu['edit.php?post_type=kbx_knowledgebase'] = $arr;

  return $menu_ord;

}

// add the filter to wordpress
add_filter( 'custom_menu_order', 'kbx_order_index_menu_page' );



$instance_kbx0601a = new QcSupportAndPromoPage('kbx-support');

if( is_admin() )
{
	$instance_kbx0601a->plugin_menu_slug = "edit.php?post_type=kbx_knowledgebase"; //Edit Value
	$instance_kbx0601a->plugin_name = "Knowledgebase Helpdesk - Free Version"; //Edit Value
	$instance_kbx0601a->show_promo_page();
}