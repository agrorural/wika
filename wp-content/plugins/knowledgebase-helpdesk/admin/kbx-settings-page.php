<?php
/**
 * Renders the settings page.
 * Portions of this code have been inspired by Easy Digital Downloads, WordPress Settings Sandbox, etc.
 *
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}


/**
 * Render the settings page.
 *
 * @return void
 */
function kbx_options_page() 
{
	
	$active_tab = isset( $_GET['tab'] ) && array_key_exists( sanitize_key( wp_unslash( $_GET['tab'] ) ), kbx_get_settings_sections() ) ? sanitize_key( wp_unslash( $_GET['tab'] ) ) : 'general'; // Input var okay.

	ob_start();

	?>

	<div class="wrap">
		
		<h1><?php _e( 'Knowledgebase Settings', 'kbx-qc' ); // WPCS: XSS OK. ?></h1>

		<?php settings_errors(); ?>

		<div id="poststuff">
		<div id="post-body" class="metabox-holder columns-3">
		<div id="post-body-content">

			<h2 class="nav-tab-wrapper" style="padding:0">
				<?php
				foreach ( kbx_get_settings_sections() as $tab_id => $tab_name ) {

					$tab_url = esc_url( add_query_arg(
						array(
						'settings-updated' => false,
						'tab' => $tab_id,
						)
					) );

					$active = $active_tab === $tab_id ? ' nav-tab-active' : '';

					echo '<a href="' . esc_url( $tab_url ) . '" title="' . esc_attr( $tab_name ) . '" class="nav-tab ' . sanitize_html_class( $active ) . '">';
								echo esc_html( $tab_name );
					echo '</a>';

				}
				?>
			</h2>

			<div id="tab_container">
				<form method="post" action="options.php">
					<table class="form-table">
					<?php
						settings_fields( 'kbx_settings' );
						do_settings_fields( 'kbx_settings_' . $active_tab, 'kbx_settings_' . $active_tab );
					?>
					</table>
					<p>
					<?php
						// Default submit button.
						submit_button(
							__( 'Submit', 'kbx-qc' ),
							'primary',
							'submit',
							false
						);

						echo '&nbsp;&nbsp;';

						// Reset button.
						$confirm = esc_js( __( 'Do you really want to reset all these settings to their default values?', 'kbx-qc' ) );
						submit_button(
							__( 'Reset', 'kbx-qc' ),
							'secondary',
							'settings_reset',
							false,
							array(
								'onclick' => "return confirm('{$confirm}');",
							)
						);
					?>
					</p>
				</form>
			</div><!-- /#tab_container-->

		</div><!-- /#post-body-content -->

		<div id="postbox-container-1" class="postbox-container">

			<div id="side-sortables" class="meta-box-sortables ui-sortable">
				<?php //include_once( 'sidebar.php' ); ?>
			</div><!-- /#side-sortables -->

		</div><!-- /#postbox-container-1 -->
		</div><!-- /#post-body -->
		<br class="clear" />
		</div><!-- /#poststuff -->

	</div><!-- /.wrap -->

	<?php
	echo ob_get_clean(); // WPCS: XSS OK.
}

/**
 * Array containing the settings' sections.
 *
 * @return array Settings array
 */
function kbx_get_settings_sections() 
{
	$kbx_settings_sections = array(
		'general' => __( 'General', 'kbx-qc' ),
		'search' => __( 'Search', 'kbx-qc' ),
		'styles' => __( 'Styles', 'kbx-qc' ),
		'others' => __( 'Others', 'kbx-qc' ),
	);

	/**
	 * Filter the array containing the settings' sections.
	 *
	 * @param array $kbx_settings_sections Settings array
	 */
	return apply_filters( 'kbx_settings_sections', $kbx_settings_sections );

}


/**
 * Miscellaneous callback funcion
 *
 * @param array $args Arguments passed by the setting.
 * @return void
 */
function kbx_missing_callback( $args ) {
	printf( esc_html__( 'The callback function used for the <strong>%s</strong> setting is missing.', 'kbx-qc' ), esc_html( $args['id'] ) );
}


/**
 * Header Callback
 *
 * Renders the header.
 *
 * @param array $args Arguments passed by the setting.
 * @return void
 */
function kbx_header_callback( $args ) {

	/**
	 * After Settings Output filter
	 * @param string $html HTML string.
	 * @param array Arguments array.
	 */
	echo apply_filters( 'kbx_after_setting_output', '', $args ); // WPCS: XSS OK.
}


/**
 * Display text fields.
 *
 * @param array $args Array of arguments.
 * @return void
 */
function kbx_text_callback( $args ) {

	// First, we read the options collection.
	global $kbx_options;

	if ( isset( $kbx_options[ $args['id'] ] ) ) {
		$value = $kbx_options[ $args['id'] ];
	} else {
		$value = isset( $args['options'] ) ? $args['options'] : '';
	}

	$html = '<input type="text" id="kbx_settings[' . $args['id'] . ']" name="kbx_settings[' . $args['id'] . ']" value="' . esc_attr( stripslashes( $value ) ) . '" />';
	$html .= '<p class="description">' . $args['desc'] . '</p>';

	/** This filter has been defined in settings-page.php */
	echo apply_filters( 'kbx_after_setting_output', $html, $args ); // WPCS: XSS OK.
}


/**
 * Display textarea.
 *
 * @param array $args Array of arguments.
 * @return void
 */
function kbx_textarea_callback( $args ) {

	// First, we read the options collection.
	global $kbx_options;

	if ( isset( $kbx_options[ $args['id'] ] ) ) {
		$value = $kbx_options[ $args['id'] ];
	} else {
		$value = isset( $args['options'] ) ? $args['options'] : '';
	}

	$html = '<textarea class="large-text" cols="50" rows="5" id="kbx_settings[' . $args['id'] . ']" name="kbx_settings[' . $args['id'] . ']">' . esc_textarea( stripslashes( $value ) ) . '</textarea>';
	$html .= '<p class="description">' . $args['desc'] . '</p>';

	/** This filter has been defined in settings-page.php */
	echo apply_filters( 'kbx_after_setting_output', $html, $args ); // WPCS: XSS OK.
}


/**
 * Display checboxes.
 *
 * @param array $args Array of arguments.
 * @return void
 */
function kbx_checkbox_callback( $args ) {

	// First, we read the options collection.
	global $kbx_options;

	$checked = isset( $kbx_options[ $args['id'] ] ) ? checked( 1, $kbx_options[ $args['id'] ], false ) : '';

	$html = '<input type="checkbox" id="kbx_settings[' . $args['id'] . ']" name="kbx_settings[' . $args['id'] . ']" value="1" ' . $checked . '/>';
	$html .= '<p class="description">' . $args['desc'] . '</p>';

	/** This filter has been defined in settings-page.php */
	echo apply_filters( 'kbx_after_setting_output', $html, $args ); // WPCS: XSS OK.
}


/**
 * Multicheck Callback
 *
 * Renders multiple checkboxes.
 *
 * @param array $args Array of arguments.
 * @return void
 */
function kbx_multicheck_callback( $args ) {
	global $kbx_options;
	$html = '';

	if ( ! empty( $args['options'] ) ) {
		foreach ( $args['options'] as $key => $option ) {
			if ( isset( $kbx_options[ $args['id'] ][ $key ] ) ) {
				$enabled = $option;
			} else {
				$enabled = null;
			}

			$html .= '<input name="kbx_settings[' . $args['id'] . '][' . $key . ']" id="kbx_settings[' . $args['id'] . '][' . $key . ']" type="checkbox" value="' . $option . '" ' . checked( $option, $enabled, false ) . '/> <br />';

			$html .= '<label for="kbx_settings[' . $args['id'] . '][' . $key . ']">' . $option . '</label><br/>';
		}

		$html .= '<p class="description">' . $args['desc'] . '</p>';
	}

	/** This filter has been defined in settings-page.php */
	echo apply_filters( 'kbx_after_setting_output', $html, $args ); // WPCS: XSS OK.
}


/**
 * Radio Callback
 *
 * Renders radio boxes.
 *
 * @param array $args Array of arguments.
 * @return void
 */
function kbx_radio_callback( $args ) {
	global $kbx_options;
	$html = '';

	foreach ( $args['options'] as $key => $option ) {
		$checked = false;

		if ( isset( $kbx_options[ $args['id'] ] ) && $kbx_options[ $args['id'] ] === $key ) {
			$checked = true;
		} elseif ( isset( $args['options'] ) && $args['options'] === $key && ! isset( $kbx_options[ $args['id'] ] ) ) {
			$checked = true;
		}

		$html .= '<input name="kbx_settings[' . $args['id'] . ']"" id="kbx_settings[' . $args['id'] . '][' . $key . ']" type="radio" value="' . $key . '" ' . checked( true, $checked, false ) . '/> <br />';
		$html .= '<label for="kbx_settings[' . $args['id'] . '][' . $key . ']">' . $option . '</label><br/>';
	}

	$html .= '<p class="description">' . $args['desc'] . '</p>';

	/** This filter has been defined in settings-page.php */
	echo apply_filters( 'kbx_after_setting_output', $html, $args ); // WPCS: XSS OK.
}


/**
 * Number Callback
 *
 * Renders number fields.
 *
 * @param array $args Array of arguments.
 * @return void
 */
function kbx_number_callback( $args ) {
	global $kbx_options;

	if ( isset( $kbx_options[ $args['id'] ] ) ) {
		$value = $kbx_options[ $args['id'] ];
	} else {
		$value = isset( $args['options'] ) ? $args['options'] : '';
	}

	$max  = isset( $args['max'] ) ? $args['max'] : 999999;
	$min  = isset( $args['min'] ) ? $args['min'] : 0;
	$step = isset( $args['step'] ) ? $args['step'] : 1;

	$size = ( isset( $args['size'] ) && ! is_null( $args['size'] ) ) ? $args['size'] : 'regular';
	$html = '<input type="number" step="' . esc_attr( $step ) . '" max="' . esc_attr( $max ) . '" min="' . esc_attr( $min ) . '" class="' . $size . '-text" id="kbx_settings[' . $args['id'] . ']" name="kbx_settings[' . $args['id'] . ']" value="' . esc_attr( stripslashes( $value ) ) . '"/>';
	$html .= '<p class="description">' . $args['desc'] . '</p>';

	/** This filter has been defined in settings-page.php */
	echo apply_filters( 'kbx_after_setting_output', $html, $args ); // WPCS: XSS OK.
}


/**
 * Select Callback
 *
 * Renders select fields.
 *
 * @param array $args Array of arguments.
 * @return void
 */
function kbx_select_callback( $args ) {
	global $kbx_options;

	if ( isset( $kbx_options[ $args['id'] ] ) ) {
		$value = $kbx_options[ $args['id'] ];
	} else {
		$value = isset( $args['options'] ) ? $args['options'] : '';
	}

	if ( isset( $args['chosen'] ) ) {
		$chosen = 'class="kbx-chosen"';
	} else {
		$chosen = '';
	}

	$html = '<select id="kbx_settings[' . $args['id'] . ']" name="kbx_settings[' . $args['id'] . ']" ' . $chosen . ' />';

	foreach ( $args['options'] as $option => $name ) {
		$selected = selected( $option, $value, false );
		$html .= '<option value="' . $option . '" ' . $selected . '>' . $name . '</option>';
	}

	$html .= '</select>';
	$html .= '<p class="description">' . $args['desc'] . '</p>';

	/** This filter has been defined in settings-page.php */
	echo apply_filters( 'kbx_after_setting_output', $html, $args ); // WPCS: XSS OK.
}


