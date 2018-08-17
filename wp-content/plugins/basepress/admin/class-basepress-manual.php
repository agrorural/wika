<?php

// Exit if called directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Basepress_Manual' ) ) {

	class Basepress_Manual {

		public function __construct() {
			add_action( 'admin_menu', array( $this, 'add_manual_page' ), 20 );

			add_action( 'admin_menu', array( $this, 'add_welcome_page' ) );

			add_action( 'admin_head', array( $this, 'remove_welcome_page_menu' ) );
		}


		public function add_manual_page() {
			add_submenu_page( 'basepress', 'BasePress ' . __( 'Manual', 'basepress' ), __( 'Manual', 'basepress' ), 'manage_options', 'basepress_manual', array( $this, 'display_manual_screen' ) );
		}


		public function display_manual_screen() {
			?>
			<div class="wrap" style="max-width:70em;margin:0 auto">
				<div style="margin:1em 0 0;padding:30px;background-color:#fff;box-shadow:0 1px 1px 0 rgba(0,0,0,.1);">
					<h1>BasePress <?php echo __( 'Manual', 'basepress' ); ?></h1>
					<p>Please follow the quick instruction below to get startet using BasePress and build your knowledge base.<br>
					For further help please visit our <a href="http://8bitsinarow.com" target="_blank">website</a> under Knowledge Base->BasePress.</p>
					<h2>Set up the knowledge base entry page</h2>
					<p>Before starting to use the Knowledge base you need to create a new standard page set the title to 'Knowledge Base' (or any other name you want the knowledge base to be called), 
					insert the shortcode [basepress] and publish the page.<br>
					This page will list the various products with their images and description. You can add any further content you need to introduce your knowledge bases.
					Just place the shortcode wherever you want the list of your products to appear in the page.<br>
					If you intend to create a single knowledge base you just need to insert the shortcode as this page will not be displayed.
					After you have created the entry page you have to add it to your menu of choice so your visitors can find it. Go to Appearance->Menus and add the page to your menu.<br>
					Once the page and menu link are ready head to the settings page under BasePress->Settings.
					In the General settings select the page you have just created from the select menu called 'Knowledge base page'.
					</p>

					<h2>Choose Single or Multi-product knowledge base</h2>
					<p>BasePress was designed to work as a standard knowledge base or a multi-product one.<br>
					If you have a single product or service and you only need one knowledge base go to BasePress->Settings and under the General options select 'Single product mode'.<br>
					When used in 'Single product mode' BasePress will skip the entry page when people go to the knowledge base and the permalinks and breadcrumbs will not show the product name.<br>
					If you have more products or services and you want to create a different knowledge base for each of them leave the 'Single product mode' option unselected.<br>
					</p>

					<h2>Choose the theme you want to use</h2>
					<p>BasePress comes with some premade themes. You can choose the one you want to use under BasePress->Settings->Aspect along with some general aspect options.</p>
					<p>If you didn't do it already save your settings.</p>

					<h2>Start creating your content</h2>
					<p>At this point you can start creating your content and build your knowledge base(s).<br>
					You should start by creating your first product under Knowledge Base->Products. If you have selected 'Single product mode' and intend to have a single knowledge base you need to create a product anyway and you just need to give it a name. You can pass from single product mode to multi product at any time.<br>
					Once you have your first product ready go to Knowledge Base->Sections. Select the product you have created in the select menu on the top of the screen and create your section(s).<br>
					Now that you have created your first product and section you can head to the knowledge base articles and create your content. Before publishing your articles remember to select the product and section to article belongs to.</p>
					<p>You can now visit the front end of your website and click on the 'Knowledge base' link on your menu and you should see your content appear.<br>
					Please remember that you need at least a product, a section and an article for the content to appear or you will just see an empty page.</p>
				</div>
			</div>
			<?php
		}


		public function add_welcome_page() {
			add_dashboard_page(
				'Welcome To BasePress',
				'Welcome To BasePress',
				'read',
				'basepress-welcome-screen',
				array( $this, 'welcome_screen_content' )
			);
		}


		public function welcome_screen_content() {
			?>
			<div class="wrap" style="max-width:70em;margin:0 auto">
				<div style="margin:1em 0 0;padding:30px;background-color:#fff;box-shadow:0 1px 1px 0 rgba(0,0,0,.1);">
				<table>
					<tr style="vertical-align:top;">
						<td><img src="<?php echo BASEPRESS_URI . 'assets/img/logo.png'; ?>" style="margin-right:20px;"></td>
						<td><h1 style="font-size: 3em; margin: 0;padding:0;">Welcome to BasePress</h1>
						<p>If this is the first time you are using BasePress please follow the small manual below to get started. You can find it anytime under BasePress->Manual.</p>
						</td>
					</tr>
				</table>
				</div>
			</div>
			<?php
			$this->display_manual_screen();
		}


		public function remove_welcome_page_menu() {
			remove_submenu_page( 'index.php', 'basepress-welcome-screen' );
		}
	}

	new Basepress_Manual;
}