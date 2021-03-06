<?php
/**
 * This is the class contains core functions used by all other classes
 */

// Exit if called directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Basepress_Utils' ) ) {

	class Basepress_Utils {

		private $kb_slug = null;
		private $product = null;
		private $sections = null;
		private $single_section = null;
		private $is_sections_page = false;
		private $is_single_section_page = false;
		private $options = '';
		private $icons_options = false;
		public	$icons = '';
		public  $icons_form = '';


		/**
		 * basepress_utils constructor.
		 *
		 * @since 1.0.0
		 *
		 * @updated 1.7.8
		 */
		public function __construct() {

			//Add knowledge base custom template
			add_filter( 'template_include', array( $this, 'basepress_page_template' ), 99 );

			//Initialize options variables;
			$this->load_options();
			add_action( 'init', array( $this, 'load_options' ), 10 );

			//Change WP query for sections
			add_action( 'pre_get_posts', array( $this, 'sections_query' ) );

			//Change knowledge base page to product category page if in single product mode
			add_filter( 'request', array( $this, 'change_request' ), 10, 1 );

			//Load icons
			add_action( 'init', array( $this, 'load_icons' ));

			//Enable comments for knowledge base
			add_filter( 'comments_open', array( $this, 'enable_comments' ) );

			//Redirect comments template for knowledge base
			add_filter( 'comments_template', array( $this, 'comments_template' ), 90 );

			//Include theme functions.php if it exists
			add_action( 'setup_theme', array( $this, 'load_theme_functions' ) );

			//Load theme settings in admin
			if ( is_admin() ) {
				$this->load_theme_settings();
			}

			//Enqueue public scripts and styles
			add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_public_scripts' ), 10 );

			add_filter( 'document_title_parts', array( $this, 'set_title' ), 10 );

			//Make KB menu item active when visiting any KB page
			add_filter( 'nav_menu_css_class', array( $this, 'set_menu_item_active') , 10, 2 );
		}


		/**
		 * set_title
		 * @since 1.7.6
		 * @updated 1.7.7
		 *
		 * @param mixed $parts
		 * @return mixed 
		 */
		public function set_title( $parts ){
			if( isset( $this->options['single_product_mode'] ) && $this->is_sections_page ){
				$options = $this->get_options();
				$entry_page = $options['entry_page'];
				$title = $entry_page ? get_the_title( $options['entry_page'] ) : '';
				
				$parts['title'] = $title;
			}
			return $parts;
		}


		/**
		 * Loads and caches plugin options
		 *
		 * @since 1.5.0
		 */
		public function load_options() {
			$options = get_option( 'basepress_settings' );
			if ( ! $options && is_multisite() ) {
				$options = get_site_option( 'basepress_settings' );
			}
			$this->options = $options;
		}



		/**
		 * Loads the active theme functions.php if it exists
		 *
		 * @since 1.3.0
		 */
		public function load_theme_functions() {
			//Include theme functions.php if it exists
			$theme_functions = $this->get_theme_file_path( 'functions.php' );
			if ( $theme_functions ) {
				include_once( $theme_functions );
			}
		}


		/**
		 * Loads the theme settings page on admin screen
		 *
		 * @since 1.3.1
		 */
		private function load_theme_settings() {
			//Include theme theme-settings.php if it exists
			$theme_settings = $this->get_theme_file_path( 'settings/theme-settings.php' );
			if ( $theme_settings ) {
				include_once( $theme_settings );
			}
		}


		/**
		 * Enqueue scripts for front end
		 *
		 * @since 1.0.0
		 *
		 * @updated 1.7.8, 1.7.10, 1.7.12
		 */
		public function enqueue_public_scripts() {

			global $basepress, $post, $wp_query;

			$options = $this->get_options();
			$entry_page = isset( $options['entry_page'] ) ? $options['entry_page'] : '';
			/**
			 * This filter allows to modify the entry page ID
			 */
			$entry_page = apply_filters( 'basepress_entry_page', $entry_page );

			//Retrieve the active theme css and icons URLs.
			/**
			 * This filter allows to change the css file name
			 */
			$stylesheet = apply_filters( 'basepress_theme_style', 'style.css' );

			$theme_css = $this->get_theme_file_uri( 'css/' . $stylesheet );

			$theme_icons = $this->get_icons_uri();

			$theme_path = $this->get_theme_file_path( 'css/' . $stylesheet );
			$theme_ver = filemtime( $theme_path );

			//Always register scripts and styles as they might be needed for shortcodes as well
			wp_register_script( 'basepress-js', plugins_url( 'public/js/basepress.js', __DIR__ ), array( 'jquery' ), $basepress->ver );

			wp_register_style( 'basepress-styles', $theme_css, array(), $theme_ver );
			wp_register_style( 'basepress-icons', $theme_icons, array(), $basepress->ver );

			//Enqueue scripts and styles inside the knowledge base pages
			if ( 'knowledgebase' == get_post_type()
				|| ( isset($wp_query->query_vars['post_type']) && 'knowledgebase' == $wp_query->query_vars['post_type'] )
				|| is_tax( 'knowledgebase_cat' )
				|| is_page( $entry_page ) ) {

				$kb_post_id = is_singular('knowledgebase') ? $kb_post_id = $post->ID : '';
				$product = $this->get_product();

				//Enqueue scripts
				wp_enqueue_script( 'basepress-js' );
				wp_localize_script( 'basepress-js', 'basepress_vars',
					array(
						'ajax_url'   => admin_url( 'admin-ajax.php' ),
						'postID'     => $kb_post_id,
						'productID'  => $product->id,
					)
				);

				//Enqueue the theme styles
				wp_enqueue_style( 'basepress-styles', $theme_css );

				if( ! isset( $this->icons_options['form']['skip-enqueue'] ) ){
					wp_enqueue_style( 'basepress-icons', $theme_icons );
				}

			}
		}


		/**
		 * Get the icon URI for enqueueing
		 *
		 * @since 1.7.8
		 *
		 * @return bool|string
		 */
		public function get_icons_uri(){
			$icons_options = $this->icons_options;
			$css_uri = $icons_options && !apply_filters( 'basepress_legacy_icons', false ) ? $icons_options['form']['css-uri'] : '';
			$is_default_font = empty( $css_uri ) ? true : false;
			$is_cdn = filter_var( $css_uri, FILTER_VALIDATE_URL ) !== false;

			If( $is_cdn && !apply_filters( 'basepress_legacy_icons', false )){
				$theme_icons = $css_uri;
			}
			else{
				$theme_icons = $is_default_font ? $this->get_default_icons_css_uri() : get_template_directory_uri() . '/' . $css_uri;
			}

			return $theme_icons;
		}


		/**
		 * Loads xml icons file from active theme
		 *
		 * @since 1.0.0
		 *
		 * @updated 1.7.8
		 */
		public function load_icons(){

			$icons_options = $this->get_icons_options();

			if( $icons_options && !apply_filters( 'basepress_legacy_icons', false )){
				$this->icons = json_decode( json_encode( $icons_options['icons-sets'] ));
				$this->icons_form = $icons_options['form'];
			}
			else{
				$icons = $this->get_default_icons_xml_path();
				if( $icons ){
					$this->icons = simplexml_load_file( $icons );
				}
				else{
					$this->icons = basepress_icons_manager::get_empty_icons_data();
				}
				$this->icons_form = array( 'css-uri' => '', 'extra-classes' => '' );
			}
		}


		/**
		 * Returns the icons options from DB
		 *
		 * @since 1.7.8
		 *
		 * @return bool
		 */
		public function get_icons_options(){
			if( ! $this->icons_options ){
				$icons_options = get_option( 'basepress_icons_sets' );
				$this->icons_options = $icons_options;
			}
			return $this->icons_options;
		}


		/**
		 * Returns the icons css uri
		 *
		 * @since 1.7.8
		 *
		 * @return string
		 */
		public function get_default_icons_css_uri(){
			if( apply_filters( 'basepress_legacy_icons', false ) ){
				$css_uri = $this->get_theme_file_uri( 'css/icons.css' );
			}
			else{
				$css_uri = BASEPRESS_URI . 'icons/icons.css';
			}
			return $css_uri;
		}


		/**
		 * Returns the icons css path
		 *
		 * @since 1.7.8
		 *
		 * @return string
		 */
		public function get_default_icons_css_path(){
			if( apply_filters( 'basepress_legacy_icons', false ) ){
				$css_path = $this->get_theme_file_path( 'css/icons.css' );
			}
			else{
				$css_path = BASEPRESS_DIR . 'icons/icons.css';
			}
			return $css_path;
		}



		/**
		 * Returns the icons xml path
		 *
		 * @since 1.7.8
		 *
		 * @return string
		 */
		public function get_default_icons_xml_path(){
			if( apply_filters( 'basepress_legacy_icons', false ) ){
				$xml_path = $this->get_theme_file_path( 'icons/icons.xml' );
				$xml_path = $xml_path ? $xml_path : BASEPRESS_DIR . 'icons/fonts/icons.xml';
			}
			else{
				$xml_path = BASEPRESS_DIR . 'icons/fonts/icons.xml';
			}
			return $xml_path;
		}


		/**
		 * Gets the KB slug including parents pages if exists
		 *
		 * @since 1.7.9
		 *
		 * @return string
		 */
		public function get_kb_slug(){

			if( $this->kb_slug ){
				return $this->kb_slug;
			}

			$entry_page = isset( $this->options['entry_page'] ) ? $this->options['entry_page'] : 0;

			/**
			 * Filters the entry page ID before use
			 */
			$entry_page = apply_filters( 'basepress_entry_page', $entry_page );

			$parents = get_ancestors( $entry_page, 'page' );
			$kb_slug = get_post_field( 'post_name', $entry_page );

			foreach( $parents as $parent ){
				$parent_slug = get_post_field( 'post_name', $parent );
				$kb_slug = $parent_slug . '/' . $kb_slug;
			}
			$this->kb_slug = $kb_slug;
			return $kb_slug;
		}


		/**
		 * Changes knowledgebase entry page to product category page if we are in single product mode.
		 *
		 * @since 1.0.0
		 *
		 * @param $value
		 * @return mixed
		 */
		public function change_request( $value ) {
			$options = $this->get_options();
			if ( ! isset( $options['single_product_mode'] ) ) {
				return $value;
			}

			if ( isset( $value['page_id'] ) && $value['page_id'] == $options['entry_page'] ) {

				$product = get_terms(
					array(
						'taxonomy'      => 'knowledgebase_cat',
						'parent'        => 0,
						'hide_empty'    => false,
						'meta_key'      => 'basepress_position',
						'orderby'       => 'meta_value_num',
						'order'         => 'ASC',
						'number'        => 1,
					)
				);

				unset( $value['page_id'] );
				$value['knowledgebase_cat'] = $product[0]->slug;
			}
			return $value;
		}



		/**
		 * Limit the number of posts in the main query for archive pages.
		 *
		 * @since 1.0.0
		 * @updated 1.6.0
		 *
		 * @param $query
		 */
		public function sections_query( $query ) {
			if ( is_admin() ) {
				return;
			}

			//Check if we are on a basepress archive page
			if ( isset( $query->query['knowledgebase_cat'] )
				|| ( isset( $query->query['post_type'] ) && 'knowledgebase' == $query->query['post_type'] )
				&& $query->is_search ) {

				$posts_per_page = isset( $this->options['section_post_limit'] ) ? $this->options['section_post_limit'] : -1;

				//If this is a search result page
				if ( isset( $query->query['s'] ) ) {

					$query->set( 'post_type', 'knowledgebase' );
					$query->set( 'posts_per_page', $posts_per_page );

					return;
				}

				$queried_object = get_queried_object();

				//If we have no queried object return. This can happen if articles have not product or sections.
				if ( ! $queried_object ) {
					return;
				}

				$this->is_sections_page = 0 == $queried_object->parent ? true : false;
				$this->is_single_section_page = ! $this->is_sections_page;

				if ( $this->is_single_section_page ) {
					$query->set( 'posts_per_page', $posts_per_page );
					$query->set( 'orderby', 'menu_order date' );
					$query->set( 'order', 'ASC' );

					$query->set(
						'tax_query',
						array(
							array(
								'taxonomy'          => 'knowledgebase_cat',
								'field'             => 'slug',
								'terms'             => $query->query_vars['knowledgebase_cat'],
								'include_children'  => false,
							),
						)
					);
					/**
					 * Action filter to modify the query for a single section
					 */
					do_action( 'basepress_single_section_query', $query );

				} else {
					//If this is a multi section page we don't need posts from the main query and we reduce it to only 1
					//since the main query cannot be eliminated completely without side effects
					$query->set( 'posts_per_page', 1 );
				}
			}
		}



		/**
		 * Get list of all products with their data
		 *
		 * @since 1.0.0
		 * @updated 1.7.10.1
		 *
		 * @return array
		 */
		public function get_products() {

			$products = array();
			$products_terms = get_terms(
				'knowledgebase_cat',
				array(
					'hide_empty' => true,
					'parent'     => 0,
					'meta_key'   => 'basepress_position',
					'orderby'    => 'meta_value_num',
					'order'      => 'ASC',
				)
			);

			foreach ( $products_terms as $product ) {

				//If the product has visibility off skip it
				$visibility = get_term_meta( $product->term_id, 'visibility', true );
				if ( ! $visibility ) {
					continue;
				}

				//Get product image
				$image = get_term_meta( $product->term_id, 'image', true );

				$image_url = isset( $image['image_url'] ) ? $image['image_url'] : '';
				$image_width = isset( $image['image_width'] ) ? $image['image_width'] : '';
				$image_height = isset( $image['image_height'] ) ? $image['image_height'] : '';

				//Get product permalink
				$permalink = get_term_link( $product->term_id, 'knowledgebase_cat' );

				$products[] = (object) array(
					'id'             => $product->term_id,
					'name'           => $product->name,
					'slug'           => $product->slug,
					'permalink'      => $permalink,
					'description'    => $product->description,
					'image'          => (object) array(
						'url'        => set_url_scheme( $image_url ),
						'width'      => $image_width,
						'height'     => $image_height,
					),
				);
			}
			return $products;
		}



		/**
		 * This function collects and returns all data for the current product
		 *
		 * @since 1.0.0
		 *
		 * @return null|object|void
		 */
		public function get_product() {
			global $wp_query;

			// If we already created the product we return the cached copy
			if ( $this->product ) {
				return $this->product;
			}

			$queried_object = get_queried_object();

			$term = null;

			if ( is_a( $queried_object, 'WP_Term' ) ) {
				$term = $this->get_top_term( $queried_object );
			}

			if ( is_a( $queried_object, 'WP_Post' ) ) {
				$terms = wp_get_post_terms( get_the_ID(), 'knowledgebase_cat' );

				if ( $terms ) {
					$term = $this->get_top_term( $terms[0] );
				}
			}

			if ( is_search() && isset( $wp_query->query['knowledgebase_cat'] ) ) {
				$term = get_terms(array(
					'taxonomy' => 'knowledgebase_cat',
					'slug'     => $wp_query->query['knowledgebase_cat'],
					'number'   => 1,
				));
				$term = isset( $term[0] ) ? $term[0] : '';
			}

			if ( ! $term ) {
				$product = (object) array(
					'id'          => '',
					'name'        => '',
					'slug'        => '',
					'permalink'   => '',
					'description' => '',
					'image'       => (object) array(
						'url'    => '',
						'width'  => '',
						'height' => '',
					),
				);
				return $product;
			}

			//We also need to get the image for the product
			$image = get_term_meta( $term->term_id, 'image', true );
			$image = $image ? $image : array(
				'image_url' => '',
				'image_width' => '',
				'image_height' => '',
			);

			//Once we have all data we can join it in a single object
			$product = (object) array(
				'id'          => $term->term_id,
				'name'        => $term->name,
				'slug'        => $term->slug,
				'permalink'   => get_term_link( $term->term_id, 'knowledgebase_cat' ),
				'description' => $term->description,
				'image'       => (object) array(
					'url'    => set_url_scheme( $image['image_url'] ),
					'width'  => $image['image_width'],
					'height' => $image['image_height'],
				),
			);

			$this->product = $product;
			return $product;
		}


		/**
		 * Returns the active product on single product mode
		 *
		 * @since 1.7.6
		 * 
		 * @return mixed
		 */
		public function get_active_product_id(){
			
			$active_product_id = false;

			if( isset( $this->options['single_product_mode'] ) ){
				$active_product = get_terms(
					array(
						'taxonomy'   => 'knowledgebase_cat',
						'parent'     => 0,
						'hide_empty' => false,
						'meta_key'   => 'basepress_position',
						'orderby'    => 'meta_value',
						'order'      => 'ASC',
						'number'     => 1,
						'fields'     => 'ids',
					)
				);
				if( $active_product && ! is_wp_error( $active_product )){
					$active_product_id = $active_product[0];
				}
			}
			
			return $active_product_id;
		}


		/**
		 * Recursive function to find the top level term which is the Product
		 *
		 * @since 1.0.0
		 * @updated 1.7.6
		 *
		 * @param $term
		 * @return mixed
		 */
		public function get_top_term( $term ) {
			if( $term->parent == 0 ) return $term;
			
			while( $term->parent != 0 ){
				$term = get_term( $term->parent, 'knowledgebase_cat' );
			}
			
			return $term;
		}




		/**
		 * This function collects and returns all sections for a product including the posts for each section.
		 * The posts collected will be no more than the limit set on the option screen.
		 *
		 * @since 1.0.0
		 *
		 * @return array|null
		 */
		public function get_sections() {

			//Get the queried object
			$queried_object = get_queried_object();

			if ( $this->is_sections_page ) {
				if ( $this->sections ) {
					return $this->sections;
				}
				return $this->get_multi_sections( $queried_object );
			} else {
				if ( $this->single_section ) {
					return $this->sections;
				}
				return $this->get_single_section( $queried_object );
			}
		}



		/**
		 * Builds the sections for a multi sections view.
		 *
		 * @since 1.0.0
		 *
		 * @updated 1.7.8, 1.7.11
		 *
		 * @param $term
		 * @return array
		 */
		public function get_multi_sections( $term ) {

			$sections = array();
			$options = get_option( 'basepress_settings' );

			//Get the section(s)
			$terms = get_terms( array(
				'taxonomy'   => 'knowledgebase_cat',
				'hide_empty' => true,
				'parent'     => $term->term_id,
				'meta_key'   => 'basepress_position',
				'orderby'    => 'meta_value_num',
				'order'      => 'ASC',
			));

			//We can now iterate over each section and load its posts
			foreach ( $terms as $section ) {
				$args = array(
					'post_type'      => 'knowledgebase',
					'posts_per_page' => $options['sections_post_limit'],
					'orderby'        => 'menu_order date',
					'order'          => 'ASC',
					'tax_query'      => array(
						array(
							'taxonomy' => 'knowledgebase_cat',
							'field'    => 'term_id',
							'terms'    => $section->term_id,
						),
					),
				);

				/**
				 * Filter to modify the query for multi sections
				 */
				$args = apply_filters( 'basepress_multi_section_query', $args );

				$section_posts = new WP_Query( $args );

				if ( $section_posts->have_posts() ) {
					$meta = get_term_meta( $section->term_id );

					$icon = isset( $meta['icon'] ) && $meta['icon'][0] ? $meta['icon'][0] : $this->icons->sections->default;
					$image = isset( $meta['image'][0] ) ? $meta['image'][0] : '';
					$image = unserialize( $image );
					$image['image_url'] = isset( $image['image_url'] ) ? set_url_scheme( $image['image_url'] ) : '';

					$sections[ $section->slug ] = (object) array(
						'id'          => $section->term_id,
						'name'        => $section->name,
						'slug'        => $section->slug,
						'permalink'   => get_term_link( $section->term_id, 'knowledgebase_cat' ),
						'description' => $section->description,
						'icon'        => $icon,
						'image'       => $image,
						'posts_count' => $section_posts->found_posts,
						'posts'       => array(),
					);

					while ( $section_posts->have_posts() ) {
						$section_posts->the_post();
						//Get the post object
						$the_post = get_post();
						//Add the icon to the post object
						$post_icon = get_post_meta( get_the_ID(), 'basepress_post_icon', true );
						$the_post->icon = $post_icon ? $post_icon : $this->icons->post->default;
						//Add the post object to the section
						$sections[ $section->slug ]->posts[] = $the_post;
					}
				}
				wp_reset_postdata();
			}

			$this->sections = $sections;
			return $sections;
		}



		/**
		 * Builds the section for a single section view
		 *
		 * @since 1.0.0
		 *
		 * @updated 1.7.8, 1.7.11
		 */
		public function get_single_section( $term ) {

			//If the term is a product return. This can happen if articles have no section set.
			if ( 0 == $term->parent ) {
				return array();
			}

			$meta = get_term_meta( $term->term_id );
			$icon = isset( $meta['icon'][0] )  && $meta['icon'][0] ? $meta['icon'][0] : $this->icons->sections->default;
			$image = isset( $meta['image'][0] ) ? $meta['image'][0] : '';
			$image = unserialize( $image );
			$image['image_url'] = isset( $image['image_url'] ) ? set_url_scheme( $image['image_url'] ) : '';

			$section[ $term->slug ] = (object) array(
				'id'           => $term->term_id,
				'name'         => $term->name,
				'slug'         => $term->slug,
				'permalink'    => get_term_link( $term->term_id, 'knowledgebase_cat' ),
				'description'  => $term->description,
				'icon'         => $icon,
				'image'        => $image,
				'posts_count'  => $term->count,
				'posts'        => array(),
				'sub_sections' => $this->get_multi_sections( $term ),
			);

			if ( have_posts() ) {
				while ( have_posts() ) {
					the_post();
					//Get the post object
					$the_post = get_post();

					//Add the icon to the post object
					$post_icon = get_post_meta( get_the_ID(), 'basepress_post_icon', true );
					$the_post->icon = $post_icon ? $post_icon : $this->icons->post->default;

					//Add the post object to the section
					$section[ $term->slug ]->posts[] = $the_post;
				}
				wp_reset_postdata();
			}
			$this->sections = $section[ $term->slug ]->sub_sections;
			$this->single_section = $section;

			return $section;
		}





		/**
		 * Generates the breadcrumbs
		 *
		 * @since 1.0.0
		 *
		 * @updated 1.7.8
		 */
		public function get_breadcrumbs(){

			//if the breadcrumbs are used outside of the knowledge base return;
			if( !is_tax( 'knowledgebase_cat' ) && !is_singular( 'knowledgebase' ) && !is_search()) return;

			$entry_page = isset( $this->options['entry_page'] ) ?	get_page_link( $this->options['entry_page'] ) : '';
			$kb_name = isset( $this->options['kb_name'] ) ? sanitize_text_field( $this->options['kb_name'] ) : 'Knowledge Base';
			$sections = '';
			$separator = isset( $this->icons->breadcrumbs->icon ) ? $this->icons->breadcrumbs->icon : '';
			if( is_array( $separator ) ){
				$separator = ! empty( $separator ) ? $separator[0] : '';
			}

			echo '<ul class="bpress-crumbs">';

			echo '<li><a href="' .$entry_page. '">' . $kb_name . '</a></li>';

			if( is_tax() ){
				$term = get_queried_object();
				$sections = $this->get_sections_tree( $term );

				foreach( $sections as $section ){
					$section_link = get_term_link( $section->term_id, 'knowledgebase_cat' );

					//If single product mode is used the skip product
					if( $section->parent == 0 && isset( $this->options['single_product_mode'] ) )
						continue;

					echo '<li><span class="bpress-breadcrumb-arrow ' . $separator . '"></span><a href="' . $section_link . '">' . $section->name . '</a></li>';

				}
			}

			if( is_single() ){
				$terms = get_the_terms( get_the_ID(), 'knowledgebase_cat' );

				if( $terms ){
					foreach( $terms as $term ){
						if( $term->parent !== 0 ){
							$sections = $this->get_sections_tree( $term );
						}
					}

					foreach( $sections as $section ){
						$section_link = get_term_link( $section->term_id, 'knowledgebase_cat' );
						//If single product mode is on use the product as entry page
						if( $section->parent == 0 && isset( $this->options['single_product_mode'] ) )
							continue;

						echo '<li><span class="bpress-breadcrumb-arrow ' . $separator . '"></span><a href="' . $section_link . '">' . $section->name . '</a></li>';
					}
				}
				echo '<li><span class="bpress-breadcrumb-arrow ' . $separator . '"></span><a href="' . get_the_permalink() . '">' . get_the_title() . '</a></li>';
			}

			echo '</ul>';
		}



		/**
		 * Generates an array of sections from product to subsections
		 *
		 * @since 1.0.0
		 */
		public function get_sections_tree( $term ) {
			$tree = array();
			$tree[] = $term;

			while ( 0 != $term->parent ) {
				$term = get_term( $term->parent, 'knowledgebase_cat' );
				$tree[] = $term;
			}
			return array_reverse( $tree );
		}





		/**
		 * Replaces theme template with BasePress custom template
		 * This is a router function which will determine the correct template
		 *
		 * @since 1.0.0
		 * @updated 1.4.0
		 *
		 * @param $template
		 * @return bool|mixed
		 */
		public function basepress_page_template( $template ) {
			global $post;

			if ( ! is_tax( 'knowledgebase_cat' ) && ! is_singular( 'knowledgebase' ) && ! is_post_type_archive( 'knowledgebase' ) ) {
				return $template;
			}

			$product = $this->get_product();

			//Set the template if it is an archive page
			if ( is_archive() && ! is_search() ) {

				$sections_style = get_term_meta( $product->id, 'sections_style', true );

				if ( $this->is_sections_page ) {
					//This is the template to list sections and sub sections
					if ( 'list' == $sections_style['sections'] ) {
						$template_name = 'sections';
					} else {
						$template_name = 'sections-boxed';
					}
				} else {
					//This is the template to list all articles in a section
					$template_name = 'single-section';
				}
			} elseif ( is_single() ) {
				//Set the template if is a post page
				//Get template name from post meta field
				//If it was not set we will use the full width template
				$template_name = get_post_meta( $post->ID, 'basepress_template_name', true ) ? : 'full-width';

			} elseif ( is_search() ) {
				//Set the template if is a search result page
				if ( $product->id ) {
					$template_name = 'search';
				} else {
					$template_name = 'global-search';
				}
			}

			/**
			 * Filter to modify the template file
			 */
			$template_name = apply_filters( 'basepress_page_template', $template_name );

			//If the template file exists we can load it
			$basepress_template = $this->get_theme_file_path( $template_name . '.php' );

			if ( $basepress_template ) {
				return $basepress_template;
			} else {
				return $template;
			}
		}



		/**
		 * Enables comment on articles
		 *
		 * @since 1.3.0
		 *
		 * @param $open
		 * @return bool
		 */
		public function enable_comments( $open ) {
			if ( ! ( is_singular( 'knowledgebase' ) || is_page( 'knowledgebase' )) ) {
				return $open;
			}

			if ( isset( $this->options['enable_comments'] ) ) {
				return $open;
			}
			return false;
		}



		/**
		 * Replaces theme template with BasePress custom template for comments
		 *
		 * @since 1.3.0
		 *
		 * @param $theme_template
		 * @return bool|mixed|void
		 */
		public function comments_template( $theme_template ) {

			if ( ! ( is_singular( 'knowledgebase' ) || is_page( 'knowledgebase' )) ) {
				return $theme_template;
			}

			if ( isset( $this->options['use_default_comments_template'] ) ) {
				return $theme_template;
			}

			$template = $this->get_theme_file_path( 'comments.php' );
			if ( $template ) {
				$theme_template = $template;
			}
			return $theme_template;
		}

		/*
		 *
		 * Utility functions
		 *
		 */


		/**
		 * Get Basepress template part
		 *
		 * @since 1.0.0
		 *
		 * @param $slug
		 * @param null $name
		 * @return bool
		 */
		public function get_template_part( $slug, $name = null ) {
			//Check if there is a slug for the template
			if ( $slug ) {

				$templates = array();
				$name = (string) $name;
				if ( '' !== $name ) {
					$templates[] = "{$slug}-{$name}.php";
				}

				$templates[] = "{$slug}.php";

				foreach ( $templates as $template ) {
					$template_part = $this->get_theme_file_path( 'template-parts/' . $template );
					if ( $template_part ) {
							require $template_part;
							break;
					}
				}
			}
		}


		/**
		 * Finds the path for the file
		 * It looks first in the main theme directory and then in the plugin theme directory
		 *
		 * @since 1.3.0
		 *
		 * @param $file_name
		 * @return bool|mixed
		 */
		public function get_theme_file_path( $file_name ) {
			$options = $this->options;
			$theme_name = isset( $options['theme_style'] ) ? $options['theme_style'] : '';
			$theme_file_paths = array();

			//Collect the possible file path for the files
			//First we check if it exists in the main theme directory then in the plugin themes directory
			$theme_file_paths [] = get_stylesheet_directory() . '/basepress/' . $theme_name . '/' . $file_name;
			$theme_file_paths [] = BASEPRESS_DIR . 'themes/' . $theme_name . '/' . $file_name;

			foreach ( $theme_file_paths as $theme_file_path ) {
				if ( file_exists( $theme_file_path ) ) {
					return $theme_file_path;
				}
			}

			return false;
		}


		/**
		 * Finds the url for the file
		 * It looks first in the main theme directory and then in the plugin theme directory
		 *
		 * @since 1.3.0
		 *
		 * @param $file_name
		 * @return bool
		 */
		public function get_theme_file_uri( $file_name ) {
			$options = $this->options;
			$theme_name = isset( $options['theme_style'] ) ? $options['theme_style'] : '';
			$theme_file_paths = array();

			//Collect the possible file path for the files
			//First we check if it exists in the main theme directory then in the plugin themes directory
			$theme_file_paths [] = array( get_stylesheet_directory() . '/basepress/' . $theme_name . '/' . $file_name, get_stylesheet_directory_uri() . '/basepress/' . $theme_name . '/' . $file_name );
			$theme_file_paths [] = array( BASEPRESS_DIR . 'themes/' . $theme_name . '/' . $file_name, BASEPRESS_URI . 'themes/' . $theme_name . '/' . $file_name );

			foreach ( $theme_file_paths as $theme_file_path ) {
				if ( file_exists( $theme_file_path[0] ) ) {
					return $theme_file_path[1];
				}
			}

			return false;
		}

		/**
		 * Returns BasePress options array
		 *
		 * @since 1.0.0
		 */
		public function get_options() {
			return $this->options;
		}


		/**
		 * Make KB menu item active when visiting any KB page
		 *
		 * @since 1.8.3
		 *
		 * @param $classes
		 * @param $item
		 * @return array
		 */
		public function set_menu_item_active( $classes, $item ){
			global $wp_query;

			//Get Knowledge base main page ID
			$options = $this->get_options();
			$entry_page = isset( $options['entry_page'] ) ? $options['entry_page'] : '';
			$entry_page = apply_filters( 'basepress_entry_page', $entry_page );

			//If we are in any KB page
			if ( get_post_type() == 'knowledgebase'
				|| ( isset($wp_query->query_vars['post_type']) && 'knowledgebase' == $wp_query->query_vars['post_type'] )
				|| is_tax( 'knowledgebase_cat' )
				|| is_page( $entry_page ) )
			{
				//Make KB menu active
				if( $item->object_id == $entry_page ){
					$classes[] = 'current-menu-item';
				}
				else{
					//And make sure no other menu item is active
					$classes = array_diff( $classes, array( 'current_page_parent' ) );
				}
			}

			// Return the corrected set of classes to be added to the menu item
			return $classes;
		}

	}


	global $basepress_utils;
	$basepress_utils = new Basepress_Utils;
}