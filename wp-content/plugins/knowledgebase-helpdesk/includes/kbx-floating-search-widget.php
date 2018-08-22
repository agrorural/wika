<?php

global $kbx_options;

if( !is_admin() ){

	if( isset($kbx_options['enable_fes_widget']) && $kbx_options['enable_fes_widget'] == '1' )
	{
		add_action('wp_footer', 'kbx_show_floating_search_widget');
	}

}

function kbx_show_floating_search_widget()
{
	?>
	<div class="kbx-fes-widget-wrapper">
		<div class="kbx-fes-widget-main">
			<div class="beacon-wrapper">
				<form class="search-form" id="ajax-fes-search-form">
					<div>
						<input class="kbx-fes-search-form-input" placeholder="<?php _e('Search the knowledge base', 'kbx-qc') ?>" autocomplete="off" offset="18" type="text">
					</div>
					<a href="#" class="kbx-fes-search-form-submit">
						<?php _e('Submit', 'kbx-qc') ?>
					</a>
					<div class="search-spinner hidden">
						<div class="double-bounce1">
						</div>
						<div class="double-bounce2">
						</div>
					</div>
				</form>
				<div class="kbx-fes-content kbx-fes-results" style="max-height:300px;">
					<div class="kbx-fes-search-results">
						<ul class="kbx-fes-search-results-ul"></ul>
					</div>
					<p class="search-empty hidden">
						<span><?php _e('No results found for ', 'kbx-qc') ?></span>
						"<span class="fes-search-terms"></span>"
					</p>
					<p class="kbx-fes-alert">
						<span>
							<?php _e('Type your search string. Minimum 4 characters are required.', 'kbx-qc') ?>
						</span>
					</p>
				</div>
				<div class="fes-search-actions">
					<div class="pc-close-me">
						<a href="#" class="close-it">
							<?php _e('close me', 'kbx-qc') ?>
						</a>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="kbx-widget-trigger-wrapper">
		<button class="kbx-fes-trigger widget-trigger widget-trigger-search">Help</button>
	</div>

	<?php
}