<?php
/**
 * Save settings.
 *
 * Functions to register, read, write and update settings.
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}


/**
 * Sanitize the form data being submitted.
 * 
 * @param  array $input Input unclean array.
 * @return array Sanitized array
 */
function kbx_settings_sanitize( $input = array() ) {

	// First, we read the options collection.
	global $kbx_options;

	// This should be set if a form is submitted, so let's save it in the $referrer variable.
	if ( empty( $_POST['_wp_http_referer'] ) ) {
		return $input;
	}

	parse_str( sanitize_text_field( wp_unslash( $_POST['_wp_http_referer'] ) ), $referrer ); // Input var okay.

	// Get the various settings we've registered.
	$settings = kbx_get_registered_settings();

	// Check if we need to set to defaults.
	$reset = isset( $_POST['settings_reset'] );

	if ( $reset ) {
		kbx_settings_reset();
		$kbx_options = get_option( 'kbx_settings' );

		add_settings_error( 'kbx-notices', '', __( 'Settings have been reset to their default values. Reload this page to view the updated settings', 'kbx-qc' ), 'error' );

		// Re-register post type and flush the rewrite rules.
		kbx_register_post_type();
		flush_rewrite_rules();

		return $kbx_options;
	}

	// Get the tab. This is also our settings section.
	$tab = isset( $referrer['tab'] ) ? $referrer['tab'] : 'general';

	$input = $input ? $input : array();

	/**
	 * Filter the settings for the tab. e.g. kbx_settings_general_sanitize.
	 *
	 * @param  array $input Input unclean array
	 */
	$input = apply_filters( 'kbx_settings_' . $tab . '_sanitize', $input );

	// Loop through each setting being saved and pass it through a sanitization filter.
	foreach ( $input as $key => $value ) {

		// Get the setting type (checkbox, select, etc).
		$type = isset( $settings[ $tab ][ $key ]['type'] ) ? $settings[ $tab ][ $key ]['type'] : false;

		if ( $type ) {

			/**
			 * Field type specific filter.
			 * 
			 * @param  array $value Setting value.
			 * @paaram array $key Setting key.
			 */
			$input[ $key ] = apply_filters( 'kbx_settings_sanitize_' . $type, $value, $key );
		}

		/**
		 * Field type general filter.
		 *
		 * @paaram array $key Setting key.
		 */
		$input[ $key ] = apply_filters( 'kbx_settings_sanitize', $input[ $key ], $key );
	}

	// Loop through the whitelist and unset any that are empty for the tab being saved.
	if ( ! empty( $settings[ $tab ] ) ) {
		foreach ( $settings[ $tab ] as $key => $value ) {
			if ( empty( $input[ $key ] ) && ! empty( $kbx_options[ $key ] ) ) {
				unset( $kbx_options[ $key ] );
			}
		}
	}

	// Merge our new settings with the existing. Force (array) in case it is empty.
	$kbx_options = array_merge( (array) $kbx_options, $input );

	add_settings_error( 'kbx-notices', '', __( 'Settings updated.', 'kbx-qc' ), 'updated' );

	// Re-register post type and flush the rewrite rules.
	kbx_register_post_type();
	flush_rewrite_rules();

	return $kbx_options;

}


/**
 * Sanitize text fields
 *
 * @param  array $input The field value.
 * @return string  $input  Sanitizied value
 */
function kbx_sanitize_text_field( $input ) {
	return sanitize_text_field( $input );
}

add_filter( 'kbx_settings_sanitize_text', 'kbx_sanitize_text_field' );


/**
 * Sanitize CSV fields
 *
 * @param  array $input The field value.
 * @return string  $input  Sanitizied value
 */
function kbx_sanitize_csv_field( $input ) {

	return implode( ',', array_map( 'trim', explode( ',', sanitize_text_field( wp_unslash( $input ) ) ) ) );
}

add_filter( 'kbx_settings_sanitize_csv', 'kbx_sanitize_csv_field' );


