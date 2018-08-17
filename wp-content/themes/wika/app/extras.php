<?php

/**
 * Crea shortcode [image]
 */
function wika_image_shortcode($atts) {
  $fileName = \App\asset_path('images/' .  $atts['src']);
  
  return '<img src="' . $fileName . '" />';
}

add_shortcode('image', __NAMESPACE__ . '\\wika_image_shortcode');
add_filter('widget_text', 'do_shortcode');

/**
 * Ajax para Onmisearch
 */
add_action('wp_ajax_ajax_omni_search', __NAMESPACE__ . '\\ajax_omni_search_callback');
add_action('wp_ajax_nopriv_ajax_omni_search', __NAMESPACE__ . '\\ajax_omni_search_callback');
function ajax_omni_search_callback(){
    header('Content-Type:application/json');
    
    class objectToSend
    {
        var $action;
        var $postType;
        var $txtKeyword;
        var $optPerPage;
        var $max_num_pages; 
        var $response;
        var $bError;
        var $vMensaje;
        var $paged;
        var $searchLink;

        function __construct($action, $postType, $txtKeyword, $optPerPage, $max_num_pages, $response, $bError, $vMensaje, $paged, $searchLink) {
           $this->action = $action; 
           $this->postType = $postType; 
           $this->txtKeyword = $txtKeyword;
           $this->optPerPage = $optPerPage;
           $this->max_num_pages = $max_num_pages;
           $this->response = $response;
           $this->bError = $bError;
           $this->vMensaje = $vMensaje;
           $this->paged = $paged;
           $this->searchLink = $searchLink;
          }
    }
    
    $objectToSend = new objectToSend(
      'ajax_omni_search',
      explode(',', (isset($_GET['postType'])?sanitize_text_field( $_GET['postType'] ) : '')),
      isset($_GET['txtKeyword'])?sanitize_text_field( $_GET['txtKeyword'] ):'', 
      isset($_GET['optPerPage'])?intval( sanitize_text_field( $_GET['optPerPage'] ) ) : 20, 
      isset($_GET['max_num_pages'])?intval( sanitize_text_field( $_GET['max_num_pages'] ) ) : 0, 
      array(),
      false,
      '',
      isset($_GET['paged'])?intval( sanitize_text_field( $_GET['paged'] ) ) : 1,
      ''
    );
    
    $args = array(
        "post_type" => $objectToSend->postType,
        "posts_per_page" => $objectToSend->optPerPage,
        "s" => $objectToSend->txtKeyword,
        'paged' => $objectToSend->paged,
        'post_status'=> 'publish'
    );

    $objectToSend->searchLink = get_search_link($objectToSend->txtKeyword);

    $the_omni_search_query = new WP_Query( $args );
    // The Loop
    if ( $the_omni_search_query->have_posts() ) {
        while ( $the_omni_search_query->have_posts() ) {
            $the_omni_search_query->the_post();
            
            $objectToSend->response[] = array(
                "id"              =>  get_the_ID(),
                "title"           => get_the_title(),
                "slug"            =>  get_post_field( 'post_name', get_post() ),
                "permalink"       => get_permalink(),
                // "excerpt"         => get_the_excerpt(),
                "date"            => get_the_date(),
                "post_class" =>  join( ' ', get_post_class( get_the_ID() ) ),
                "post_type" => get_post_type(),
                "html"            => ''
            );
        }
        $objectToSend->bError = false;
        $objectToSend->vMensaje = $the_omni_search_query;
        $objectToSend->max_num_pages = $the_omni_search_query->max_num_pages;
        echo json_encode($objectToSend);
        /* Restore original Post Data */
        wp_reset_postdata();
    } else {
        $objectToSend->bError = true;
        $objectToSend->vMensaje = 'No se encontraron resultados';
        //$objectToSend->vMensaje = $the_omni_search_query;
        echo json_encode($objectToSend);
        wp_reset_postdata();
    }
    //var_dump($args);
    //echo json_encode($objectToSend);
    wp_die();
}

function add_login_logout_register_menu( $items, $args ) {
 if ( $args->theme_location != 'primary_navigation' ) {
 return $items;
 }
 
 if ( is_user_logged_in() ) {
 $items .= '<li class="withIcon rightIcon"><a href="' . wp_logout_url() . '">' . __( 'Salir' ) . '<i class="fas fa-sign-out-alt"></i></a></li>';
 } else {
 $items .= '<li class="withIcon rightIcon"><a href="' . wp_login_url() . '">' . __( 'Entrar' ) . '<i class="fas fa-sign-in-alt"></i></a></li>';
 }
 
 return $items;
}
 
add_filter( 'wp_nav_menu_items', __NAMESPACE__ . '\\add_login_logout_register_menu', 199, 2 );