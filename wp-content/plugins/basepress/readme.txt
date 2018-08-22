=== BasePress Knowledge Base===
Contributors: 8bitsinarow, freemius
Donate link: http://8bitsinarow.com
Tags: knowledge base, help desk,documentation,faq
Requires at least: 4.5
Tested up to: 4.9
Stable tag: 1.8.8
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Build a Single or Multi product knowledge base with ease.
Let users find the information they need and reduce the cost of customer support.

== Description ==
BasePress allows you to build as many knowledge bases as you need to document your products or services.
Reduces your customer support cost and helps your customers find the answer they need 24/7.
It is designed to be easy to use thanks to its clean administration tools that integrates seamlessly in WordPress admin area.

What makes BasePress the right tool for your business?

1. Ready to use in less then 5 minutes.
1. Create as many knowledge bases as you need.
1. Keep your content organized in a logical way dividing it by product or service.
1. Serve targeted answer to your customers saving them time too.
1. Adapts to all devices. Your customers can consult it form any device including tablets and phones.
1. Keep the look of your website professional.


BasePress creates an entry page for your customers where they can choose the product or service they need help with.
They will be taken to the right knowledge base for their needs and all the articles, searches and suggestions will be fully relevant to what they are looking for.

This is a Lite version of our Premium plugin we wanted to share with the WordPress community.
It has all the features you need to create your fully functional knowledge base and nothing less.

MAIN FEATURES

1. Build a single or multi product knowledge base
1. A dedicated page for users to choose the product
1. Unlimited sections hierarchy
1. List and boxed sections styles
1. Image and description for each product
1. Image, icon and description for each section
1. Icon selector for each articles
1. Drag and drop reorder for products and sections
1. Search bar with live results
1. Shortcode to add the search bar anywhere in your website
1. Related articles widget
1. Sections widget
1. Products widget
1. Easy to use admin screens
1. Translatable via .pot files
1. Easy customization

If you need some extra features for you and your customers consider upgrading and get access to these extra benefits:

PREMIUM FEATURES

1. Improved search bar results based on user votes and visits as well
1. Articles voting
1. Popular articles widget based on votes and/or visits
1. Automatic Table of Contents (in article and/or widget)
1. Drag and drop articles reorder
1. Next and Previous articles navigation
1. Advanced Content Restriction by user role
1. Knowledge base Insights
1. Multisite support
1. WPML support
1. Shortcode editor to add dynamic lists of articles outside of the knowledge base


== Installation ==

1. Upload the plugin files to the `/wp-content/plugins/plugin-name` directory, or install the plugin through the WordPress plugins screen directly.
1. Activate the plugin through the 'Plugins' screen in WordPress.
1. Create a page and add the shortcode [basepress] to that page.
1. Go to BasePress->Settings->General and select the page you just created on 'Knowledge Base page'.
1. Select 'Single product mode' if you want a knowledge base for a single product skipping the product selection page.
1. Start creating your first product and sections.
1. Add your articles.

== Frequently Asked Questions ==

= I don't need multiple KBs can I still use it as standard knowledge base? =

Yes. You just need to enable the 'Single product mode' in the settings and it would work as a standard KB.
You still need to create a product under Knowledge Base->Products before adding sections and articles.

= Can I customize the look of the knowledge base? =

Yes of course. There a few ways to do it depending on your needs.

1. Simple modifications are possible via css on your theme stylesheet.
1. You can customize the look by creating a child theme of one of the default themes that comes with the plugin.
In the plugin directory there is a folder called 'themes'. Inside there is a folder for each theme. You can override any of those files by creating a folder in your theme called 'basepress', place inside a new folder with the name of the theme you want to modify and override any of the files from there respecting the directory names.
This works as a wooCommerce theme would.
1. Build your own theme from scratch. To do this create a folder with the name of your theme and place inside of it all the necessary files. Follow the file structure of the default theme present on BasePress.
Remember to place the 'Theme Name: Your theme name' at the beginning of the css file or BasePress won't reconize it.
Once the theme is ready it will be listed in the settings page.

= I get 404 pages in the knowledge base =

A 404 page will appear if any of the following points is true:

1. You haven't selected the main page containing the shortcode in the General settings.
1. You haven't created any product yet. Even if you use it as 'single product mode' you still need to create a product to function. Only the name is necessary.
1. You haven't created any section yet.
1. You haven't selected any product or section for the articles.
1. You have changed the slug of the page with the shortcode. In this case just go to WP Settings->Permalinks and just click 'Save Changes'.

= My knowledge base page is empty =

For the knowledge base to appear you needs at least a Product a Section and an article ready.
Products or sections without any article are not displayed by default.

= Can I upgrade to the Premium version and keep my content? =

Yes. You can use the free version and build your knowledge base as long as you like. If you then decide to upgrade to get all Premium features you can do it directly from within the plugin.
All your content will remain intact. New features might not be anabled after upgrade. Just go to BasePress->Settings and activate the one you need.

= Is this a limited version? =

No. You get a fully functional plugin with no restrictions. You can create unlimited KBs, prodcuts, sections and articles.

= Can I use BasePress in my language? =
BasePress is fully translatable via .pot files that you can find inside the plugin folder under languages.
If you need a multilingual knowledge base than consider upgrading to our Premium version for WPML compatibility.

= Can I get support for this plugin? =
We only give support for any bug fix you may find in the free version.
Customers of our Premium Version get full support for a year from purchase.

== Screenshots ==

1. BasePress Comes with 3 default themes
2. Easy to use admin tools
3. Search bar with Live Results

== Changelog ==

= 1.8.8 =
* Fixed post order page not showing all articles (Premium Only)
* Small improvement on admin css

= 1.8.7 =
* Fixed bug for searches when the KB is in sub-page

= 1.8.6 =
* Improved search results to move exact matches on top

= 1.8.5 =
* Removed page builders' shortcodes from search snippets

= 1.8.4 =
* Renamed some element's css classes on admin side to avoid naming conflict with other plugins

= 1.8.3 =
* Improved menu handling so KB menu item has class "current_menu_item" when visiting any KB page

= 1.8.2 =
* Updated Freemius SDK

= 1.8.1 =
* Fixed case insensitive search for non English languages

= 1.8.0 =
* Added knowledge base Insights (Premium Only)
* Fixed products template translation

= 1.7.12 =
* Minor code optimization for post views counter

= 1.7.11 =
* Fixed PHP error when section image is missing.
* Fixed search feature not finding articles in sub-sections

= 1.7.10.1 =
* Fixed PHP error when product image is missing.

= 1.7.10 =
* Improved update and upgrade process
* Improved articles voting system for better articles suggestions (Premium Only)

= 1.7.9 =
* Improved permalink structure if KB is in a sub-page

= 1.7.8 =
* Added an Icons Manager to have full control on the icons used in the knowledge base
* Updated included themes to work with the new Icon Manager
* Updated Freemius SDK

= 1.7.7 =
* Fixed page title tag for single product mode always showing the same title name

= 1.7.6 =
* Automated product selection on all admin screen if 'Single product mode' is enabled
* Improved page title tag for single product mode

= 1.7.5 =
* Fixed Search and Articles navigation not working on some installations
* Updated Freemius SDK

= 1.7.4 =
* Added possibility to disable the Table of Contents on individual articles (Premium Only)

= 1.7.3 =
* Fixed bug that stopped highliting of found terms on search bar results
* Fixed post counter not working on some WordPress installation

= 1.7.2 =
* Improved compatibility with Yoast SEO which would make Table of Contents links not work correctly (Premium Only)

= 1.7.1 =
* Fixed bug that made sections with not articles but only sub-sections disappear

= 1.7.0 =
* First release
