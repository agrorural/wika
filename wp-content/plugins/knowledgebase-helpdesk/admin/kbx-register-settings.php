<?php
/**
 * Register settings.
 *
 * Functions to register, read, write and update settings.
 * Portions of this code have been inspired by Easy Digital Downloads, WordPress Settings Sandbox, etc.
 *
 * @subpackage Admin/Register_Settings
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}


/**
 * Get an option
 *
 * Looks to see if the specified setting exists, returns default if not
 *
 * @param string $key Key of the option to fetch.
 * @param mixed  $default Default value to fetch if option is missing.
 * @return mixed
 */
function kbx_get_option( $key = '', $default = false ) {

	global $kbx_options;

	$value = ! empty( $kbx_options[ $key ] ) ? $kbx_options[ $key ] : $default;

	/**
	 * Filter the value for the option being fetched.
	 *
	 * @param mixed $value  Value of the option
	 * @param mixed $key  Name of the option
	 * @param mixed $default Default value
	 */
	$value = apply_filters( 'kbx_get_option', $value, $key, $default );

	/**
	 * Key specific filter for the value of the option being fetched.
	 *
	 * @param mixed $value  Value of the option
	 * @param mixed $key  Name of the option
	 * @param mixed $default Default value
	 */
	return apply_filters( 'kbx_get_option_' . $key, $value, $key, $default );
}


/**
 * Update an option
 *
 * Updates an kbx setting value in both the db and the global variable.
 * Warning: Passing in an empty, false or null string value will remove
 * the key from the kbx_options array.
 *
 * @param  string          $key   The Key to update.
 * @param  string|bool|int $value The value to set the key to.
 * @return boolean   True if updated, false if not.
 */
function kbx_update_option( $key = '', $value = false ) {

	// If no key, exit.
	if ( empty( $key ) ) {
		return false;
	}

	// If no value, delete.
	if ( empty( $value ) ) {
		$remove_option = kbx_delete_option( $key );
		return $remove_option;
	}

	// First let's grab the current settings.
	$options = get_option( 'kbx_settings' );

	/**
	 * Filters the value before it is updated
	 *
	 * @param  string|bool|int $value The value to set the key to
	 * @param  string          $key   The Key to update
	 */
	$value = apply_filters( 'kbx_update_option', $value, $key );

	// Next let's try to update the value.
	$options[ $key ] = $value;
	$did_update = update_option( 'kbx_settings', $options );

	// If it updated, let's update the global variable.
	if ( $did_update ) {
		global $kbx_options;
		$kbx_options[ $key ] = $value;
	}
	return $did_update;
}


/**
 * Remove an option
 *
 * Removes an kbx setting value in both the db and the global variable.
 *
 * @param  string $key The Key to update.
 * @return boolean   True if updated, false if not.
 */
function kbx_delete_option( $key = '' ) {

	// If no key, exit.
	if ( empty( $key ) ) {
		return false;
	}

	// First let's grab the current settings.
	$options = get_option( 'kbx_settings' );

	// Next let's try to update the value.
	if ( isset( $options[ $key ] ) ) {
		unset( $options[ $key ] );
	}

	$did_update = update_option( 'kbx_settings', $options );

	// If it updated, let's update the global variable.
	if ( $did_update ) {
		global $kbx_options;
		$kbx_options = $options;
	}
	return $did_update;
}


/**
 * Register settings function
 *
 * @return void
 */
function kbx_register_settings() {

	if ( false === get_option( 'kbx_settings' ) ) {
		add_option( 'kbx_settings', kbx_settings_defaults() );
	}

	foreach ( kbx_get_registered_settings() as $section => $settings ) {

		add_settings_section(
			'kbx_settings_' . $section, // ID used to identify this section and with which to register options, e.g. kbx_settings_general.
			__return_null(),	// No title, we will handle this via a separate function.
			'__return_false',	// No callback function needed. We'll process this separately.
			'kbx_settings_' . $section  // Page on which these options will be added.
		);

		foreach ( $settings as $setting ) {

			$args = wp_parse_args( $setting, array(
					'section' => $section,
					'id'      => null,
					'name'    => '',
					'desc'    => '',
					'type'    => null,
					'options' => '',
					'max'     => null,
					'min'     => null,
					'step'    => null,
			) );

			add_settings_field(
				'kbx_settings[' . $args['id'] . ']', // ID of the settings field. We save it within the kbx_settings array.
				$args['name'],	   // Label of the setting.
				function_exists( 'kbx_' . $args['type'] . '_callback' ) ? 'kbx_' . $args['type'] . '_callback' : 'kbx_missing_callback', // Function to handle the setting.
				'kbx_settings_' . $section,	// Page to display the setting. In our case it is the section as defined above.
				'kbx_settings_' . $section,	// Name of the section.
				$args
			);
		}
	}

	// Register the settings into the options table.
	register_setting( 'kbx_settings', 'kbx_settings', 'kbx_settings_sanitize' );
}
add_action( 'admin_init', 'kbx_register_settings' );


/**
 * Retrieve the array of plugin settings
 *
 * @since 1.2.0
 *
 * @return array Settings array
 */
function kbx_get_registered_settings() {

	$kbx_settings = array(
		/*** General settings ***/
		'general'             => apply_filters( 'kbx_settings_general',
			array(
				'kb_slug'           => array(
					'id'               => 'kb_slug',
					'name'             => esc_html__( 'Knowledgebase slug', 'kbx-qc' ),
					'desc'             => esc_html__( 'This will set the opening path of the URL of the knowledgebase and is set when registering the custom post type', 'kbx-qc' ),
					'type'             => 'text',
					'options'          => 'knowledgebase',
				),
				'category_slug'     => array(
					'id'               => 'category_slug',
					'name'             => esc_html__( 'Category slug', 'kbx-qc' ),
					'desc'             => esc_html__( 'Each category is a section of the knowledgebase. This setting is used when registering the custom category and forms a part of the URL when browsing category archives', 'knowledgebase' ),
					'type'             => 'text',
					'options'          => 'kb-sections',
				),
				'tag_slug'          => array(
					'id'               => 'tag_slug',
					'name'             => esc_html__( 'Tag slug', 'kbx-qc' ),
					'desc'             => esc_html__( 'Each article can have multiple tags. This setting is used when registering the custom tag and forms a part of the URL when browsing tag archives', 'kbx-qc' ),
					'type'             => 'text',
					'options'          => 'kb-tags',
				),
				'uninstall_header'  => array(
					'id'               => 'uninstall_header',
					'name'             => '<h3>' . esc_html__( 'Uninstall options', 'kbx-qc' ) . '</h3>',
					'desc'             => '',
					'type'             => 'header',
					'options'          => '',
				),
				'uninstall_options' => array(
					'id'               => 'uninstall_options',
					'name'             => esc_html__( 'Delete options on uninstall', 'kbx-qc' ),
					'desc'             => esc_html__( 'Check this box to delete the settings on this page when the plugin is deleted via the Plugins page in your WordPress Admin', 'kbx-qc' ),
					'type'             => 'checkbox',
					'options'          => true,
				),
				'uninstall_data'    => array(
					'id'               => 'uninstall_data',
					'name'             => esc_html__( 'Delete all knowledgebase posts on uninstall', 'kbx-qc' ),
					'desc'             => esc_html__( 'Check this box to delete all the posts, categories and tags created by the plugin. There is no way to restore the data if you choose this option', 'kbx-qc' ),
					'type'             => 'checkbox',
					'options'          => false,
				),
			)
		),
		/*** search settings ***/
		'search'              => apply_filters( 'kbx_settings_search',
			array(
				'search_mode'        => array(
					'id'               => 'search_mode',
					'name'             => esc_html__( 'Search Mode', 'kbx-qc' ),
					'desc'             => esc_html__( 'Mode of the database search.', 'kbx-qc' ),
					'type'             => 'select',
					'options'          => array(
						'fulltext-search' => 'Fulltext Search',
						'fulltext-search-bn' => 'Fulltext Search, Boolean Mode',
						'like-search' => 'Like Search',
					),
				),
				'enable_caching'    => array(
					'id'               => 'enable_caching',
					'name'             => esc_html__( 'Enable search caching.', 'kbx-qc' ),
					'desc'             => esc_html__( 'Check this box if you want to cache search terms and results for a certain period.', 'kbx-qc' ),
					'type'             => 'checkbox',
					'options'          => false,
				),
				'title_weight'     => array(
					'id'               => 'title_weight',
					'name'             => esc_html__( 'Title Weight', 'kbx-qc' ),
					'desc'             => esc_html__( 'Weight or priority value of the title field. Usefull for priority wise ranking. Example: 50' ),
					'type'             => 'text',
					'options'          => '50',
				),
				'content_weight'     => array(
					'id'               => 'content_weight',
					'name'             => esc_html__( 'Content Weight', 'kbx-qc' ),
					'desc'             => esc_html__( 'Weight or priority value of the content field. Example: 30' ),
					'type'             => 'text',
					'options'          => '30',
				),
				'search_orderby'        => array(
					'id'               => 'search_orderby',
					'name'             => esc_html__( 'Search Result Sorting', 'kbx-qc' ),
					'desc'             => esc_html__( 'Mode of the database result sorting.', 'kbx-qc' ),
					'type'             => 'select',
					'options'          => array(
						'by-score' => 'By Matching Score',
						'by-date' => 'By Articles Date',
					),
				),
			)
		),
		/*** Style settings ***/
		'styles'              => apply_filters( 'kbx_settings_styles',
			array(
				'custom_css'        => array(
					'id'               => 'custom_css',
					'name'             => esc_html__( 'Custom CSS', 'kbx-qc' ),
					'desc'             => esc_html__( 'Enter any custom valid CSS without any wrapping &lt;style&gt; tags', 'kbx-qc' ),
					'type'             => 'textarea',
					'options'          => '',
				),
			)
		),	
		/*** others settings ***/
		'others'              => apply_filters( 'kbx_settings_others',
			array(
				'enable_rtl'        => array(
					'id'               => 'enable_rtl',
					'name'             => esc_html__( 'Enable RTL Direction', 'kbx-qc' ),
					'desc'             => esc_html__( 'Enable RTL direction for question and answers.', 'kbx-qc' ),
					'type'             => 'checkbox',
					'options'          => false,
				),

				'enable_fes_widget'        => array(
					'id'               => 'enable_fes_widget',
					'name'             => esc_html__( 'Enable Floating Search Widget', 'kbx-qc' ),
					'desc'             => esc_html__( 'Enable Floating Search Widget for flexible searching in the frontend.', 'kbx-qc' ),
					'type'             => 'checkbox',
					'options'          => false,
				),

				/*'enable_question_widget'  => array(
					'id'               => 'enable_question_widget',
					'name'             => esc_html__( 'Enable Ask Question', 'kbx-qc' ),
					'desc'             => esc_html__( 'Enable question asking feature for visitors.', 'kbx-qc' ),
					'type'             => 'checkbox',
					'options'          => false,
				),*/
			)
		),
	);

	/**
	 * Filters the settings array
	 *
	 *
	 * @param array $kbx_setings Settings array
	 */
	return apply_filters( 'kbx_registered_settings', $kbx_settings );

}



/**
 * Default settings.
 *
 * @return array Default settings
 */
function kbx_settings_defaults() {

	$options = array();

	// Populate some default values.
	foreach ( kbx_get_registered_settings() as $tab => $settings ) {
		foreach ( $settings as $option ) {
			// When checkbox is set to true, set this to 1.
			if ( 'checkbox' === $option['type'] && ! empty( $option['options'] ) ) {
				$options[ $option['id'] ] = '1';
			}
			// If an option is set.
			if ( in_array( $option['type'], array( 'textarea', 'text', 'csv' ), true ) && ! empty( $option['options'] ) ) {
				$options[ $option['id'] ] = $option['options'];
			}
		}
	}

	/**
	 * Filters the default settings array.
	 *
	 * @param array $options Default settings.
	 */
	return apply_filters( 'kbx_settings_defaults', $options );
}


/**
 * Reset settings.
 *
 * @return void
 */
function kbx_settings_reset() {
	delete_option( 'kbx_settings' );
}

