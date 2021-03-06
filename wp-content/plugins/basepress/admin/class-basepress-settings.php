<?php

/**
 * This is the class that handles BasePress Settings in the admin area
 */

// Exit if called directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


if ( ! class_exists( 'BasePress_Settings' ) ) {

	class BasePress_Settings {
		private $options = '';

		/**
		* Admin_Settings constructor
		*
		* @since 1.0.0
		*/
		function __construct() {
			add_action( 'admin_menu', array( $this, 'add_admin_settings_page' ), 10 );

			//Initialize options variables;
			add_action( 'init', array( $this, 'load_options' ), 10 );
		}


		/**
		*  Loads and caches plugin options
		*
		*  @since 1.5.0
		*/
		public function load_options() {
			$options = get_option( 'basepress_settings' );
			if ( ! $options && is_multisite() ) {
				$options = get_site_option( 'basepress_settings' );
			}
			$this->options = $options;
		}



		/**
		* Adds BasePress settings page on admin menu
		*
		* @since 1.0.0
		*/
		public function add_admin_settings_page() {
			//Check that the user has the required capability
			if ( current_user_can( 'manage_options' ) ) {
				//Add top level menu and 'Settings' submenu on admin screen
				add_menu_page( 'BasePress ' . __( 'Settings', 'basepress' ), 'BasePress', 'manage_options', 'basepress', '', 'none' );
				add_submenu_page( 'basepress', 'BasePress ' . __( 'Settings', 'basepress' ), __( 'Settings', 'basepress' ), 'manage_options', 'basepress', array( $this, 'display_screen' ) );

				//Initialize the administration settings with WP settings API
				add_action( 'admin_init', array( $this, 'settings_init' ) );
			}
		}


		/**
		* Declares all settings sections and fields and fetches all options
		*
		* @since 1.0.0
		*/
		public function settings_init() {

			//Check if there is a transient set during options sanitation to remind us that the knowledge base options have been changed
			//If it exist we flush the rewrite rules and delete the transient
			if( delete_transient( 'basepress_flush_rules' ) ){
				add_action( 'shutdown', function(){
					flush_rewrite_rules();
				});
			}

			//Register plugin settings
			register_setting( 'basepress_settings', 'basepress_settings', array( $this, 'basepress_settings_validate' ) );

			//Add general settings
			add_settings_section( 'basepress_general_settings', __( 'General', 'basepress' ), '', 'basepress' );

			//Add theme settings
			add_settings_section( 'basepress_theme_settings', __( 'Aspect', 'basepress' ), '', 'basepress' );

			//Add search settings
			add_settings_section( 'basepress_search_settings', __( 'Search', 'basepress' ), '', 'basepress' );

			//Add article comments settings
			add_settings_section( 'basepress_comments_settings', __( 'Comments', 'basepress' ), '', 'basepress' );

			/**
			 * Action to add extra settings sections
			 */
			 do_action( 'basepress_settings_sections' );

			//Add settings fields for GENERAL settings
			add_settings_field( 'entry_page', __( 'Knowledge Base page', 'basepress' ), array( $this, 'entry_page_render' ), 'basepress', 'basepress_general_settings' );
			add_settings_field( 'kb_name', __( 'Breadcrumbs name', 'basepress' ), array( $this, 'kb_name_render' ), 'basepress', 'basepress_general_settings' );
			add_settings_field( 'post_count_ip_exclude', __( 'Exclude IPs from post view counter', 'basepress' ), array( $this, 'post_count_ip_exclude_render' ), 'basepress', 'basepress_general_settings' );
			add_settings_field( 'single_product_mode', __( 'Single product mode', 'basepress' ), array( $this, 'single_product_mode_render' ), 'basepress', 'basepress_general_settings' );
			if ( ! is_multisite() ) {
				add_settings_field( 'remove_all_unistall', __( 'Remove all content on unistall', 'basepress' ), array( $this, 'remove_all_unistall_render' ), 'basepress', 'basepress_general_settings' );
			}

			//Add settings fields for THEME settings
			add_settings_field( 'theme_style', __( 'Theme', 'basepress' ), array( $this, 'theme_style_render' ), 'basepress', 'basepress_theme_settings' );
			add_settings_field( 'products_cols', __( 'Products Columns', 'basepress' ), array( $this, 'products_columns_render' ), 'basepress', 'basepress_theme_settings' );
			add_settings_field( 'sections_cols', __( 'Sections Columns', 'basepress' ), array( $this, 'sections_columns_render' ), 'basepress', 'basepress_theme_settings' );
			add_settings_field( 'sections_post_limit', __( 'Limit Post Count on multi sections page', 'basepress' ), array( $this, 'sections_post_limit_render' ), 'basepress', 'basepress_theme_settings' );
			add_settings_field( 'section_post_limit', __( 'Limit Post Count on single section page', 'basepress' ), array( $this, 'section_post_limit_render' ), 'basepress', 'basepress_theme_settings' );
			add_settings_field( 'show_section_icon', __( 'Show Section Icons', 'basepress' ), array( $this, 'show_section_icon_render' ), 'basepress', 'basepress_theme_settings' );
			add_settings_field( 'show_post_icon', __( 'Show Post Icons', 'basepress' ), array( $this, 'show_post_icon_render' ), 'basepress', 'basepress_theme_settings' );
			add_settings_field( 'show_section_post_count', __( 'Show post count on Sections', 'basepress' ), array( $this, 'show_section_post_count_render' ), 'basepress', 'basepress_theme_settings' );

			//Add settings fields for SEARCH settings
			add_settings_field( 'show_search_suggest', __( 'Use smart search suggestions', 'basepress' ), array( $this, 'show_search_suggest_render' ), 'basepress', 'basepress_search_settings' );
			add_settings_field( 'min_search_suggest_screen', __( 'Disable on touch devises smaller than', 'basepress' ), array( $this, 'min_search_suggest_screen_render' ), 'basepress', 'basepress_search_settings' ); //Since version 1.2.0
			add_settings_field( 'search_suggest_count', __( 'Limit smart search suggestions', 'basepress' ), array( $this, 'search_suggest_count_render' ), 'basepress', 'basepress_search_settings' );
			add_settings_field( 'search_field_placeholder', __( 'Search field placeholder', 'basepress' ), array( $this, 'search_field_placeholder_render' ), 'basepress', 'basepress_search_settings' );
			add_settings_field( 'search_submit_text', __( 'Submit button text', 'basepress' ), array( $this, 'search_submit_text_render' ), 'basepress', 'basepress_search_settings' );
			add_settings_field( 'show_search_submit', __( 'Show search submit button', 'basepress' ), array( $this, 'show_search_submit_render' ), 'basepress', 'basepress_search_settings' );
			add_settings_field( 'search_page_title', __( 'Search result page title', 'basepress' ), array( $this, 'search_page_title_render' ), 'basepress', 'basepress_search_settings' );
			add_settings_field( 'search_page_no_results_title', __( "'No search result found' page title", 'basepress' ), array( $this, 'search_page_no_results_title_render' ), 'basepress', 'basepress_search_settings' );
			add_settings_field( 'search_suggest_more_text', __( 'Show all results text', 'basepress' ), array( $this, 'search_suggest_more_text_render' ), 'basepress', 'basepress_search_settings' );
			add_settings_field( 'searchbar_style', __( 'Load css on shortcode', 'basepress' ), array( $this, 'searchbar_style_render' ), 'basepress', 'basepress_search_settings' );

			//Add settings fields for COMMENTS
			add_settings_field( 'enable_comments', __( 'Enable Comments', 'basepress' ), array( $this, 'enable_comments_render' ), 'basepress', 'basepress_comments_settings' );
			add_settings_field( 'use_default_comments_template', __( 'Use main theme template', 'basepress' ), array( $this, 'use_default_comments_template_render' ), 'basepress', 'basepress_comments_settings' );

			/**
			 * Action to add extra features settings fileds
			 */
			do_action( 'basepress_settings_fields' );

		}


		/*
		* General settings fields
		*/

		public function entry_page_render() {
			$options = $this->options;

			$ID = isset( $options['entry_page'] ) ? $options['entry_page'] : '';
			$ID = apply_filters( 'basepress_entry_page', $ID );

			$pages = get_pages(
				array(
					'sort_order'   => 'asc',
					'sort_column'  => 'post_title',
					'hierarchical' => 1,
					'child_of'     => 0,
					'parent'       => -1,
					'post_type'    => 'page',
					'post_status'  => 'publish',
				)
			);

			echo '<select name="basepress_settings[entry_page]">';
			echo '<option ' . selected( '', $ID, false ) . ' disabled>' . __( 'Select page', 'basepress' ) . '</option>';
			foreach ( $pages as $page ) {
				$selected = selected( $page->ID, $ID, false );
				echo '<option value="' . $page->ID . '"' . $selected . '>' . $page->post_title . '</option>';
			}
			echo '</select>';
			echo '<p class="description">' . __( 'Select the page containing the knowledge base shortcode.', 'basepress' ) . '</p>';
		}

		public function kb_name_render() {
			$options = $this->options;

			$name = isset( $options['kb_name'] ) ? $options['kb_name'] : '';
			echo '<input type="text" name="basepress_settings[kb_name]" value="' . $name . '">';
			echo '<p class="description">' . __( 'This is the name used in the breadcrumbs for the knowledge base entry page.', 'basepress' ) . '</p>';
		}

		public function post_count_ip_exclude_render() {
			$options = $this->options;

			$excludes = isset( $options['post_count_ip_exclude'] ) ? $options['post_count_ip_exclude'] : '';
			echo '<textarea name="basepress_settings[post_count_ip_exclude]" rows="3" cols="50" style="resize:none;">' . $excludes . '</textarea>';
			echo '<p class="description">' . __( 'Add multiple IP addresses separated by a space.', 'basepress' ) . '</p>';
		}

		public function single_product_mode_render() {
			$options = $this->options;

			$value = isset( $options['single_product_mode'] ) ? 1 : 0;
			echo '<input type="checkbox" name="basepress_settings[single_product_mode]" value="1"' . checked( $value, 1, false ) . '>';
		}

		public function remove_all_unistall_render() {
			$options = $this->options;

			$value = isset( $options['remove_all_unistall'] ) ? 1 : 0;
			echo '<input type="checkbox" name="basepress_settings[remove_all_unistall]" value="1"' . checked( $value, 1, false ) . '>';
		}


		/*
		*	Theme settings fields
		*/

		public function theme_style_render() {
			$unique_themes = array();
			$base_theme_dir = get_stylesheet_directory() . '/basepress/';
			$plugin_theme_dir = BASEPRESS_DIR . 'themes/';

			$options = $this->options;

			$set_theme = isset( $options['theme_style'] ) ? $options['theme_style'] : 0;

			$base_themes = array();

			if ( file_exists( $base_theme_dir ) ) {
				$base_themes = glob( $base_theme_dir . '*' );
			}
			$plugin_themes = glob( $plugin_theme_dir . '*' );
			$themes = array_merge( $plugin_themes, $base_themes );

			echo '<select name="basepress_settings[theme_style]">';
			echo '<option ' . ( 0 == $set_theme ? 'selected' : '' ) . ' disabled>' . __( 'Select Theme', 'basepress' ) . '</option>';

			foreach ( $themes as $theme ) {
				$theme_dir = basename( $theme );

				if ( ! in_array( $theme_dir, $unique_themes ) ) {
					$theme_css = file_get_contents( $theme . '/css/style.css', false, null, 0, 200 );
					preg_match( '/Theme Name:\s*(.+)/i', $theme_css, $theme_name );
					$selected = selected( $theme_dir, $set_theme, false );

					echo '<option value="' . $theme_dir . '"' . $selected . '>' . $theme_name[1] . '</option>';
					$unique_themes[] = $theme_dir;
				}
			}
			echo '</select>';
		}

		public function products_columns_render() {
			$options = $this->options;

			$value = isset( $options['products_cols'] ) ? $options['products_cols'] : '';
			echo '<input type="number" name="basepress_settings[products_cols]" value="' . $value . '" min="1" max="4">';
		}

		public function sections_columns_render() {
			$options = $this->options;

			$value = isset( $options['sections_cols'] ) ? $options['sections_cols'] : '';
			echo '<input type="number" name="basepress_settings[sections_cols]" value="' . $value . '" min="1" max="4">';
		}

		public function sections_post_limit_render() {
			$options = $this->options;

			$value = isset( $options['sections_post_limit'] ) ? $options['sections_post_limit'] : '';
			echo '<input type="number" name="basepress_settings[sections_post_limit]" value="' . $value . '" min="0" max="99">';
		}

		public function section_post_limit_render() {
			$options = $this->options;

			$value = isset( $options['section_post_limit'] ) ? $options['section_post_limit'] : '';
			echo '<input type="number" name="basepress_settings[section_post_limit]" value="' . $value . '" min="0" max="99">';
		}

		public function show_section_icon_render() {
			$options = $this->options;

			$value = isset( $options['show_section_icon'] ) ? 1 : 0;
			echo '<input type="checkbox" name="basepress_settings[show_section_icon]" value="1"' . checked( $value, 1, false ) . '>';
		}

		public function show_post_icon_render() {
			$options = $this->options;

			$value = isset( $options['show_post_icon'] ) ? 1 : 0;
			echo '<input type="checkbox" name="basepress_settings[show_post_icon]" value="1"' . checked( $value, 1, false ) . '>';
		}

		public function show_section_post_count_render() {
			$options = $this->options;

			$value = isset( $options['show_section_post_count'] ) ? 1 : 0;
			echo '<input type="checkbox" name="basepress_settings[show_section_post_count]" value="1"' . checked( $value, 1, false ) . '>';
		}


		/*
		*	Search settings fields
		*/

		public function show_search_suggest_render() {
			$options = $this->options;

			$value = isset( $options['show_search_suggest'] ) ? 1 : 0;
			echo '<input type="checkbox" name="basepress_settings[show_search_suggest]" value="1"' . checked( $value, 1, false ) . '>';
		}


		/**
		* @since version 1.2.0
		*/
		public function min_search_suggest_screen_render() {
			$options = $this->options;

			$value = isset( $options['min_search_suggest_screen'] ) ? $options['min_search_suggest_screen'] : '';
			echo '<input type="number" name="basepress_settings[min_search_suggest_screen]" value="' . $value . '" min="1">';
			echo '<p class="description">' . __( 'Insert the minimum screen <u>height</u> in px.', 'basepress' ) . '</p>';
		}

		public function search_suggest_count_render() {
			$options = $this->options;

			$value = isset( $options['search_suggest_count'] ) ? $options['search_suggest_count'] : '';
			echo '<input type="number" name="basepress_settings[search_suggest_count]" value="' . $value . '" min="1" max="10">';
		}

		public function show_search_submit_render() {
			$options = $this->options;

			$value = isset( $options['show_search_submit'] ) ? 1 : 0;
			echo '<input type="checkbox" name="basepress_settings[show_search_submit]" value="1"' . checked( $value, 1, false ) . '>';
		}

		public function search_submit_text_render() {
			$options = $this->options;

			$value = isset( $options['search_submit_text'] ) ? $options['search_submit_text'] : '';
			echo '<input type="text" name="basepress_settings[search_submit_text]" value="' . $value . '">';
		}

		public function search_field_placeholder_render() {
			$options = $this->options;

			$value = isset( $options['search_field_placeholder'] ) ? $options['search_field_placeholder'] : '';
			echo '<input type="text" name="basepress_settings[search_field_placeholder]" value="' . $value . '">';
		}

		/**
		* @since 1.2.0
		*/
		public function search_page_title_render() {
			$options = $this->options;

			$value = isset( $options['search_page_title'] ) ? $options['search_page_title'] : '';
			echo '<input type="text" name="basepress_settings[search_page_title]" value="' . $value . '">';
			echo '<p class="description">' . __( 'This is the title for the search page. Use %number% to include the number of found posts in the text.', 'basepress' ) . '</p>';
		}

		/**
		* @since 1.2.1
		*/
		public function search_page_no_results_title_render() {
			$options = $this->options;

			$value = isset( $options['search_page_no_results_title'] ) ? $options['search_page_no_results_title'] : '';
			echo '<input type="text" name="basepress_settings[search_page_no_results_title]" value="' . $value . '">';
			echo '<p class="description">' . __( 'This is the title for the search page when no results are found.', 'basepress' ) . '</p>';
		}


		/**
		* @since 1.2.0
		*/
		public function search_suggest_more_text_render() {
			$options = $this->options;

			$value = isset( $options['search_suggest_more_text'] ) ? $options['search_suggest_more_text'] : '';
			echo '<input type="text" name="basepress_settings[search_suggest_more_text]" value="' . $value . '">';
			echo '<p class="description">' . __( 'This text appears at the bottom of the search bar suggestions.', 'basepress' ) . '</p>';
		}

		/**
		* Since 1.4.0
		*/
		public  function searchbar_style_render() {
			$options = $this->options;

			$value = isset( $options['searchbar_style'] ) ? 1 : 0;
			echo '<input type="checkbox" name="basepress_settings[searchbar_style]" value="1"' . checked( $value, 1, false ) . '>';
		}


		/*
		* Comments fields
		*/

		public function enable_comments_render() {
			$options = $this->options;

			$value = isset( $options['enable_comments'] ) ? 1 : 0;
			echo '<input type="checkbox" name="basepress_settings[enable_comments]" value="1"' . checked( $value, 1, false ) . '>';
		}

		public function use_default_comments_template_render() {
			$options = $this->options;

			$value = isset( $options['use_default_comments_template'] ) ? 1 : 0;
			echo '<input type="checkbox" name="basepress_settings[use_default_comments_template]" value="1"' . checked( $value, 1, false ) . '>';
		}




		/**
		* This function validates all options fields before saving them on DB
		*
		* @since 1.0.0
		* @updated 1.7.6, 1.8.0
		*
		* @param $input
		* @return mixed
		*/
		public function basepress_settings_validate( $input ) {
			//If in Single Product mode maybe create a new product
			if( isset( $input['single_product_mode'] ) ){
				$this->create_default_product();
			}

			//Get new entry page and filter it if necessary
			$new_entry_page = isset( $input['entry_page'] ) ? $input['entry_page'] : 0;
			$new_entry_page = apply_filters( 'basepress_settings_entry_page_save', $new_entry_page );

			//Update new entry page
			$input['entry_page'] = $new_entry_page;

			//Set a transient to flush rewrite rules on reload
			set_transient( 'basepress_flush_rules', 1 );

			do_action( 'basepress_after_settings_validate', $input );

			return $input;
		}



		/**
		 * Creates a default product if none exists
		 * 
		 * @since 1.7.6
		 *
		 * @return mixed
		 */
		private function create_default_product(){
			$products_terms = get_terms(
				'knowledgebase_cat',
				array(
					'taxonomy'	=> 'knowledgebase_cat',
					'parent'		=> 0,
					'fields'    => 'ids'
				)
			);

			$products_terms = apply_filters( 'basepress_settings_product_terms', $products_terms );

			if( empty( $products_terms ) ){
				$default_term = wp_insert_term( 'Default', 'knowledgebase_cat', array( 'description' => 'Default Product' ) );
				if( $default_term && ! is_wp_error( $default_term ) ){
					//Add product image
					update_term_meta(
						$default_term['term_id'],
						'image',
						array(
							'image_url'		=> '',
							'image_width'	=> '',
							'image_height'	=> ''
						)
					);

					//Add product visibility
					update_term_meta(
						$default_term['term_id'],
						'visibility',
						1
					);

					//Add product position
					update_term_meta(
						$default_term['term_id'],
						'basepress_position',
						0
					);

					//Add product sections style
					update_term_meta(
						$default_term['term_id'],
						'sections_style',
						array(
							'sections'		=> 'boxed',
							'sub_sections'	=> 'list'
						)
					);
				}
			}
		}



		/**
		* Displays the settings page in the admin area
		*
		* @since 1.0.0
		*/
		public function display_screen() {
			if ( ! current_user_can( 'manage_options' ) ) {
				wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
			}

			echo '<div class="wrap">';
			echo '<h1>BasePress ' . __( 'Settings', 'basepress' ) . '</h1>';

			echo '<form method="post" action="options.php">';

			settings_fields( 'basepress_settings' );

			echo '<div class="basepress-tabs">';
			// We use a custom function to render the sections
			$this->do_settings_sections( 'basepress' );
			echo '</div>';

			submit_button( __( 'Save Settings', 'basepress' ) );
			echo '</form>';
			echo '</div>';
		}


		/**
		* Custom function based on WP do_settings_page function
		*
		* @since 1.0.0
		*
		* @param $page
		*/
		private function do_settings_sections( $page ) {
			global $wp_settings_sections, $wp_settings_fields;

			if ( ! isset( $wp_settings_sections[ $page ] ) ) {
				return;
			}

			settings_errors();
			
			foreach ( (array) $wp_settings_sections[ $page ] as $section ) {

				$checked = 'basepress_general_settings' == $section['id'] ? ' checked="checked" ' : '';
				echo '<div class="basepress-tab">';
				echo '<input name="css-tabs" id="' . $section['id'] . '"' . $checked . 'class="basepress-tab-switch" type="radio">';
				echo '<label for="' . $section['id'] . '" class="basepress-tab-label">' . $section['title'] . '</label>';

				if ( $section['callback'] ) {
					call_user_func( $section['callback'], $section );
				}

				if ( ! isset( $wp_settings_fields )
					|| ! isset( $wp_settings_fields[ $page ] )
					|| ! isset( $wp_settings_fields[ $page ][ $section['id'] ] ) ) {
					continue;
				}
				echo '<div class="basepress-tab-content">';
				echo '<h2 class="settings-title">' . $section['title'] . '</h2>';
				echo '<table class="form-table">';
				do_settings_fields( $page, $section['id'] );
				echo '</table>';
				echo '</div>';
				echo '</div>';
			}
		}
	}

	new BasePress_Settings;
}
