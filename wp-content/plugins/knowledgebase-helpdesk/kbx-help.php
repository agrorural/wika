<?php

/**
 * Register a custom help menu page.
 */
function kbxhd_add_help_menu_page(){

	$menu_slug = 'edit.php?post_type=kbx_knowledgebase';
	
	add_submenu_page(
        $menu_slug,
        __( 'Knowledgebase Help', 'quantumcloud' ),
        __( 'Help', 'quantumcloud' ),
        'manage_options',
        'kbx-help-kbhd-page',
        'kbxhd_add_help_page_callaback_func'
    );
	
}

add_action( 'admin_menu', 'kbxhd_add_help_menu_page' );
 
/**
 * Display help page content
 */
function kbxhd_add_help_page_callaback_func()
{
    
    ?>
	<div class="wrap">
		<div id="icon-tools" class="icon32"></div>
        <h2>Knowledgebase - Help</h2>
        <div>
        	<p>
        		You need to use shortcodes to display article search page or glossary page. Copy the below shortcodes as per your requirement and put in your post or page.
        	</p>
        	<p>
        		<strong><u>Shortcodes:</u></strong>
        	</p>
        	<p>
        		[kbx-knowledgebase] for article search page with category tiles.
        	</p>
        	<p>
        		[kbx-knowledgebase-glossary] for glossary page.
        	</p>
        </div>
        <div>
        	<p>
        		<strong><u>Settings:</u></strong>
        	</p>
        	<p>
        		Settings options are self explanatory. These can be found under "Knowledgebase --> Settings".
        	</p>
        </div>
        <div>
        	<p>
        		<strong><u>Widgets:</u></strong>
        	</p>
        	<p>
        		Widgets can be found under "Appearence --> Widgets".
        	</p>
        	<p>
        		There are two avilable widgets. These are:
        		<ol>
        			<li>Knowledgebase Articles [Sorting Options: Date, Popularity, Views]</li>
        			<li>Knowledgebase Tag Cloud</li>
        		</ol>
        	</p>
        </div>
        <div>
        	<p>
        		<strong><u>Floating Search Widget:</u></strong>
        	</p>
        	<p>
        		You can enable a user friendly floating search widget sitewide. To enable this option go to "Settings --> Others" section.
        	</p>
        	<p>
        		Then enable "Enable Floating Search Widget" this option by clicking on checkbox. Finally save the settings.
        	</p>
        </div>
    </div>
    <?php 
}
