<?php

/**
 * This is the class that adds and handles BasePress custom post type and taxonomies
 */
// Exit if called directly
if ( !defined( 'ABSPATH' ) ) {
    exit;
}

if ( !class_exists( 'Basepress_CPT' ) ) {
    class Basepress_CPT
    {
        /**
         * basepress_cpt constructor.
         *
         * @since 1.0.0
         */
        public function __construct()
        {
            global  $basepress_utils ;
            //Add rewrite rules to handle links properly
            add_filter( 'rewrite_rules_array', array( $this, 'rewrite_rules' ) );
            //Add the product and section name on the post permalink
            add_filter(
                'post_type_link',
                array( $this, 'post_permalinks' ),
                1,
                2
            );
            //Add the product name on the archive permalink
            add_filter(
                'term_link',
                array( $this, 'sections_permalink' ),
                99,
                3
            );
            //Add Product filter dropdown on post list table
            add_action( 'restrict_manage_posts', array( $this, 'restrict_by_product' ) );
            //Filter Articles by products
            add_filter( 'parse_query', array( $this, 'products_list_query' ) );
            //Add and manage Product and Section columns
            add_filter( 'manage_knowledgebase_posts_columns', array( $this, 'add_custom_columns' ) );
            add_action(
                'manage_posts_custom_column',
                array( $this, 'manage_custom_columns' ),
                10,
                2
            );
            //Add views, votes and score metafields default values on post save
            add_action(
                'wp_insert_post',
                array( $this, 'save_basepress_metas' ),
                10,
                3
            );
            //Add admin notice for articles with missing data
            add_action( 'admin_notices', array( $this, 'missing_data_notice' ) );
            $this->options = get_option( 'basepress_settings' );
            $this->kb_slug = $basepress_utils->get_kb_slug();
            $this->register_taxonomy();
            $this->register_post_type();
        }
        
        /**
         * Adds rewrite rules for Basepress post type
         * Called by flush_rewrite rules
         *
         * @since 1.0.0
         * @updated 1.4.0
         *
         * @param $rules
         * @return array
         */
        public function rewrite_rules( $rules )
        {
            global  $wp_rewrite, $basepress_utils ;
            $options = get_option( 'basepress_settings' );
            //If the entry page has not been set skip the rewrite rules
            if ( !isset( $options['entry_page'] ) ) {
                return $rules;
            }
            $kb_slug = $basepress_utils->get_kb_slug();
            /**
             * Filter the kb_slug before generating the rewrite rules
             * @since 1.5.0
             */
            $kb_slug = apply_filters( 'basepress_rewrite_rules_kb_slug', $kb_slug, $this->options['entry_page'] );
            $search_base = $wp_rewrite->search_base;
            $page_base = $wp_rewrite->pagination_base;
            $comment_page_base = $wp_rewrite->comments_pagination_base;
            $new_rules = array();
            $product = get_terms( array(
                'taxonomy'   => 'knowledgebase_cat',
                'parent'     => 0,
                'hide_empty' => false,
                'meta_key'   => 'basepress_position',
                'orderby'    => 'meta_value_num',
                'order'      => 'ASC',
                'number'     => 1,
            ) );
            $product_slug = '';
            if ( !empty($product) && !is_a( $product, 'WP_Error' ) ) {
                $product_slug = $product[0]->slug;
            }
            
            if ( isset( $options['single_product_mode'] ) ) {
                //entry page
                $new_rules[$kb_slug . '/?$'] = 'index.php?knowledgebase_cat=' . $product_slug;
                //Search
                $new_rules[$kb_slug . '/' . $search_base . '/(.+)/page/?([0-9]{1,})/?$'] = 'index.php?s=$matches[1]&knowledgebase_cat=' . $product_slug . '&paged=$matches[2]';
                $new_rules[$kb_slug . '/' . $search_base . '/(.+)/?$'] = 'index.php?s=$matches[1]&knowledgebase_cat=' . $product_slug;
                $new_rules[$kb_slug . '/' . $search_base . '/(.+)/?$'] = 'index.php?s=$matches[1]&knowledgebase_cat=' . $product_slug;
                //Paged archives
                $new_rules[$kb_slug . '/(.+)/' . $page_base . '/(.+)/?$'] = 'index.php?knowledgebase_cat=$matches[1]&paged=$matches[2]';
                //Paged posts
                $new_rules[$kb_slug . '/(.+)/(.+)/([0-9]+)?/?$'] = 'index.php?knowledgebase=$matches[2]&page=$matches[3]';
                //Paged Comments
                $new_rules[$kb_slug . '/(.+)/(.+)/' . $comment_page_base . '-([0-9]{1,})/?$'] = 'index.php?knowledgebase=$matches[2]&cpage=$matches[3]';
                //Article
                $new_rules[$kb_slug . '/(.+)/(.+)/?$'] = 'index.php?knowledgebase=$matches[2]';
                //Category
                $new_rules[$kb_slug . '/(.+)/?$'] = 'index.php?knowledgebase_cat=$matches[1]';
            } else {
                //Search
                $new_rules[$kb_slug . '/' . $search_base . '/(.+)/page/?([0-9]{1,})/?$'] = 'index.php?s=$matches[1]&post_type=knowledgebase&paged=$matches[2]';
                $new_rules[$kb_slug . '/' . $search_base . '/(.+)/?$'] = 'index.php?s=$matches[1]&post_type=knowledgebase';
                $new_rules[$kb_slug . '/(.+)/' . $search_base . '/(.+)/page/?([0-9]{1,})/?$'] = 'index.php?s=$matches[2]&knowledgebase_cat=$matches[1]&paged=$matches[3]';
                $new_rules[$kb_slug . '/(.+)/' . $search_base . '/(.+)/?$'] = 'index.php?s=$matches[2]&knowledgebase_cat=$matches[1]';
                $new_rules[$kb_slug . '/(.+)/' . $search_base . '/(.+)/?$'] = 'index.php?s=$matches[2]&knowledgebase_cat=$matches[1]';
                //Paged archives
                $new_rules[$kb_slug . '/(.+)/(.+)/' . $page_base . '/(.+)/?$'] = 'index.php?knowledgebase_cat=$matches[2]&paged=$matches[3]';
                //Paged posts
                $new_rules[$kb_slug . '/(.+)/(.+)/(.+)/([0-9]+)?/?$'] = 'index.php?knowledgebase=$matches[3]&page=$matches[4]';
                //Paged Comments
                $new_rules[$kb_slug . '/(.+)/(.+)/(.+)/' . $comment_page_base . '-([0-9]{1,})/?$'] = 'index.php?knowledgebase=$matches[3]&cpage=$matches[4]';
                //Article
                $new_rules[$kb_slug . '/(.+)/(.+)/(.+)/?$'] = 'index.php?knowledgebase=$matches[3]';
                //Category
                $new_rules[$kb_slug . '/(.+)/(.+)/?$'] = 'index.php?knowledgebase_cat=$matches[2]';
                //Product
                $new_rules[$kb_slug . '/(.+)/?$'] = 'index.php?knowledgebase_cat=$matches[1]';
            }
            
            //IMPORTANT: the new rules must be added at the top of the array to have higher priority
            return array_merge( $new_rules, $rules );
        }
        
        /**
         * Registers the taxonomy 'knowledgebase_cat'
         *
         * @since version 1.0.0
         */
        public function register_taxonomy()
        {
            register_taxonomy( 'knowledgebase_cat', 'knowledgebase', array(
                'labels'            => array(
                'name'          => __( 'Knowledge Base categories', 'basepress' ),
                'singular_name' => __( 'Knowledge Base category', 'basepress' ),
                'menu_name'     => __( 'Knowledge Base categories', 'basepress' ),
            ),
                'hierarchical'      => true,
                'query_var'         => true,
                'public'            => true,
                'show_ui'           => true,
                'show_admin_column' => true,
                'show_in_nav_menus' => true,
                'show_tagcloud'     => false,
                'meta_box_cb'       => false,
                'rewrite'           => array(
                'slug'       => $this->kb_slug . '%product%',
                'with_front' => false,
            ),
            ) );
        }
        
        /**
         * Registers Basepress post type
         *
         * @since version 1.0.0
         */
        public function register_post_type()
        {
            register_post_type( 'knowledgebase', array(
                'label'               => __( 'Knowledge Base', 'basepress' ),
                'labels'              => array(
                'name'          => __( 'Knowledge Base', 'basepress' ),
                'singular_name' => __( 'Knowledge Base Article', 'basepress' ),
                'all_items'     => __( 'All Articles', 'basepress' ),
            ),
                'description'         => __( 'These are the Knowledge base articles from BasePress.', 'basepress' ),
                'supports'            => array(
                'title',
                'editor',
                'author',
                'thumbnail',
                'excerpt',
                'trackbacks',
                'revisions',
                'comments'
            ),
                'taxonomies'          => array( 'knowledgebase_cat' ),
                'hierarchical'        => false,
                'query_var'           => true,
                'public'              => true,
                'show_ui'             => true,
                'show_in_menu'        => true,
                'show_in_nav_menus'   => true,
                'show_in_admin_bar'   => true,
                'menu_position'       => 25,
                'menu_icon'           => 'dashicons-book',
                'can_export'          => true,
                'has_archive'         => true,
                'exclude_from_search' => false,
                'publicly_queryable'  => true,
                'capability'          => 'edit_post',
                'rewrite'             => array(
                'slug'       => $this->kb_slug . '/%taxonomies%',
                'with_front' => false,
            ),
            ) );
        }
        
        /**
         * Adds the product and section name on the post permalink
         *
         * @since version 1.0.0
         *
         * @param $link
         * @param $post
         * @return mixed
         */
        public function post_permalinks( $link, $post )
        {
            //Return if this is not a basepress post
            if ( 'knowledgebase' != $post->post_type ) {
                return $link;
            }
            $terms = get_the_terms( $post->ID, 'knowledgebase_cat' );
            if ( $terms ) {
                //replace '%taxonomies%' with the appropriate product and sections names
                $link = str_replace( '%taxonomies%', $this->get_taxonomies( $terms ), $link );
            }
            /**
             * Filters the section permalink before returning it
             */
            $link = apply_filters( 'basepress_post_permalink', $link, $post );
            return $link;
        }
        
        /**
         * Gets the product and section for the post permalink
         * It iterates through the current post terms and sorts them as product/section
         *
         * @since version 1.0.0
         *
         * @param $terms
         * @return string
         */
        public function get_taxonomies( $terms )
        {
            global  $basepress_utils ;
            $options = $basepress_utils->get_options();
            $term_product = $this->get_product( $terms[0] );
            $section = $terms[0];
            $taxonomies = '';
            if ( isset( $section ) ) {
                
                if ( isset( $options['single_product_mode'] ) ) {
                    $taxonomies = $section->slug;
                } else {
                    $taxonomies = $term_product->slug . '/' . $section->slug;
                }
            
            }
            return $taxonomies;
        }
        
        /**
         * Adds the product name on the archive permalink
         *
         * @since version 1.0.0
         *
         * @param $termlink
         * @param $term
         * @param $taxonomy
         * @return mixed
         */
        public function sections_permalink( $termlink, $term, $taxonomy )
        {
            global  $basepress_utils ;
            //If this term is not a basepress product return the $termlink unchanged
            if ( 'knowledgebase_cat' != $term->taxonomy ) {
                return $termlink;
            }
            
            if ( 0 != $term->parent ) {
                
                if ( !isset( $this->options['single_product_mode'] ) ) {
                    //If this is not a parent term, we are on a section archive page. We need to retrieve the product
                    $sections = $basepress_utils->get_sections_tree( $term );
                    $product = '/' . $sections[0]->slug;
                } else {
                    $product = '';
                }
                
                //Replace the '/%product%' placeholder with the product name
                $termlink = str_replace( '%product%', $product, $termlink );
            } else {
                //If this term is the parent term, remove the '/%product%' placeholder from the link
                $termlink = str_replace( '%product%', '', $termlink );
            }
            
            /**
             * Filters the section permalink before returning it
             */
            $termlink = apply_filters( 'basepress_sections_permalink', $termlink, $term );
            return $termlink;
        }
        
        /**
         * Adds product filtering on post list table
         *
         * @since version 1.0.0
         */
        public function restrict_by_product()
        {
            global  $typenow ;
            $post_type = 'knowledgebase';
            $taxonomy = 'knowledgebase_cat';
            
            if ( $typenow == $post_type ) {
                $selected = ( isset( $_GET[$taxonomy] ) ? $_GET[$taxonomy] : '' );
                wp_dropdown_categories( array(
                    'show_option_all' => __( 'Show all products', 'basepress' ),
                    'taxonomy'        => $taxonomy,
                    'name'            => $taxonomy,
                    'orderby'         => 'name',
                    'selected'        => $selected,
                    'show_count'      => true,
                    'hide_empty'      => false,
                    'hierarchical'    => true,
                    'depth'           => 1,
                ) );
            }
        
        }
        
        /**
         * Filters articles by products on post list table
         *
         * @since version 1.0.0
         *
         * @param $query
         */
        public function products_list_query( $query )
        {
            global  $pagenow ;
            $post_type = 'knowledgebase';
            $taxonomy = 'knowledgebase_cat';
            $q_vars =& $query->query_vars;
            
            if ( 'edit.php' == $pagenow && isset( $q_vars['post_type'] ) && $q_vars['post_type'] == $post_type && isset( $q_vars[$taxonomy] ) && is_numeric( $q_vars[$taxonomy] ) && 0 != $q_vars[$taxonomy] ) {
                $term = get_term_by( 'id', $q_vars[$taxonomy], $taxonomy );
                $q_vars[$taxonomy] = $term->slug;
            }
        
        }
        
        /**
         * Adds the Product and Section columns
         *
         * @since version 1.0.0
         *
         * @param $columns
         * @return array
         */
        public function add_custom_columns( $columns )
        {
            unset( $columns['taxonomy-knowledgebase_cat'] );
            $first_columns = array_slice(
                $columns,
                0,
                3,
                true
            );
            $last_columns = array_slice(
                $columns,
                3,
                null,
                true
            );
            $new_columns = array();
            $new_columns['basepress-product'] = __( 'Product', 'basepress' );
            $new_columns['basepress-section'] = __( 'Section', 'basepress' );
            $columns = array_merge( $first_columns, $new_columns, $last_columns );
            return $columns;
        }
        
        /**
         * Generates the values for the Product and Section columns
         *
         * @since version 1.0.0
         *
         * @param $column
         * @param $post_id
         */
        public function manage_custom_columns( $column, $post_id )
        {
            switch ( $column ) {
                case 'basepress-product':
                    $terms = get_the_terms( $post_id, 'knowledgebase_cat' );
                    
                    if ( $terms ) {
                        $i = 0;
                        foreach ( $terms as $term ) {
                            $product = $this->get_product( $term );
                            $link = get_admin_url() . 'edit.php?post_type=knowledgebase&knowledgebase_cat=' . $product->slug;
                            if ( $i ) {
                                echo  ', ' ;
                            }
                            echo  '<a href="' . $link . '">' . $product->name . '</a>' ;
                            $i++;
                        }
                    }
                    
                    break;
                case 'basepress-section':
                    $sections = wp_get_post_terms( $post_id, 'knowledgebase_cat', array() );
                    if ( empty($sections) ) {
                        break;
                    }
                    $section_items = array();
                    foreach ( $sections as $section ) {
                        //Skip terms with parent 0 as they are products
                        if ( 0 == $section->parent ) {
                            continue;
                        }
                        $link = get_admin_url() . 'edit.php?post_type=knowledgebase&knowledgebase_cat=' . $section->slug;
                        $section_items[] = '<a href="' . $link . '">' . $section->name . '</a>';
                    }
                    $section_items = join( ', ', $section_items );
                    echo  $section_items ;
                    break;
            }
        }
        
        /**
         * Finds the product from the section
         *
         * @since version 1.0.0
         *
         * @param $term
         * @return array|null|WP_Error|WP_Term
         */
        private function get_product( $term )
        {
            
            if ( 0 != $term->parent ) {
                $parent_term = get_term( $term->parent, 'knowledgebase_cat' );
                
                if ( 0 != $parent_term->parent ) {
                    return $this->get_product( $parent_term );
                } else {
                    return $parent_term;
                }
            
            }
            
            return $term;
        }
        
        /**
         * Saves knowledge base post metas
         * This function gets called every time a post is created.
         * We can then save the default meta values for the Views, Votes and Scores and menu_order
         *
         * @since version 1.0.0
         * @updated 1.6.0
         *
         * @param $post_id
         * @param $post
         * @param $update
         */
        public function save_basepress_metas( $post_id, $post, $update )
        {
            global  $wpdb ;
            // If this isn't a knowledgebase post return.
            if ( 'knowledgebase' != $post->post_type ) {
                return;
            }
            
            if ( 'publish' == $post->post_status ) {
                $views = get_post_meta( $post_id, 'basepress_views', true );
                if ( !$views ) {
                    update_post_meta( $post_id, 'basepress_views', 0 );
                }
                //Set the menu order in case this is a new article
                
                if ( -1 == $post->menu_order ) {
                    $post_terms = get_the_terms( $post, 'knowledgebase_cat' );
                    
                    if ( !empty($post_terms) ) {
                        $section = $post_terms[0];
                        $menu_order = $wpdb->get_var( "\n\t\t\t\t\t\t\tSELECT MAX(menu_order)+1 AS menu_order FROM {$wpdb->posts} AS p\n\t\t\t\t\t\t\tLEFT JOIN {$wpdb->term_relationships} AS t ON (p.ID = t.object_id)\n\t\t\t\t\t\t\tWHERE t.term_taxonomy_id = {$section->term_id}\n\t\t\t\t\t\t\tAND p.post_status = 'publish'\n\t\t\t\t\t\t\t" );
                        $menu_order = ( $menu_order ? $menu_order : 1 );
                        $wpdb->query( "UPDATE {$wpdb->posts} AS p SET p.menu_order = {$menu_order} WHERE p.ID = {$post_id};" );
                    }
                
                }
            
            }
        
        }
        
        /**
         * Add admin notice if the articles has missing data like section and template
         *
         * @since 1.7.6
         *
         * @return mixed 
         */
        public function missing_data_notice()
        {
            global  $post ;
            $action = ( isset( $_GET['action'] ) ? $_GET['action'] : '' );
            
            if ( 'edit' == $action && $post && 'knowledgebase' == $post->post_type && 'auto-draft' != $post->post_status ) {
                $post_type = $post->post_type;
                
                if ( 'edit' == $action && 'knowledgebase' == $post_type ) {
                    $missing_options = array();
                    $post_terms = get_the_terms( $post->ID, 'knowledgebase_cat' )[0];
                    $post_meta = get_post_meta( $post->ID, 'basepress_template_name', true );
                    if ( empty($post_terms) ) {
                        $missing_options[] = __( 'Section', 'basepress' );
                    }
                    if ( empty($post_meta) ) {
                        $missing_options[] = __( 'Template', 'basepress' );
                    }
                    
                    if ( !empty($missing_options) ) {
                        $class = 'notice notice-error is-dismissible';
                        $message = __( 'This post was saved without the following data: ', 'basepress' );
                        $missing_options = implode( ', ', $missing_options );
                        printf(
                            '<div class="%1$s"><p>%2$s%3$s</p></div>',
                            esc_attr( $class ),
                            esc_html( $message ),
                            $missing_options
                        );
                    }
                
                }
            
            }
        
        }
    
    }
    //End Class
    new Basepress_CPT();
}
