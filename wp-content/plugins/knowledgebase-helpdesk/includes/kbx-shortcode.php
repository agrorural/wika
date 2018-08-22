<?php

/*******************************
 * Shortcode for Displaying 
 * Main Knowledgebase panel
 *******************************/
add_shortcode('kbx-knowledgebase', 'kbx_show_search_panel_sc');

function kbx_show_search_panel_sc( $atts = array() )
{
	ob_start();

    kbx_display_search_panel( $atts );

    $content = ob_get_clean();

    return $content;
}

function kbx_display_search_panel( $atts = array() )
{

	//Defaults & Set Parameters
	extract( shortcode_atts(
		array(
			'orderby' => 'date',
			'order' => 'DESC',
			'limit' => 10,
			'section' => '',
			'template' => '',
			'hide_empty_section' => false,
			'show_section_box' => true,
			'show_article_title' => '',
			'show_search_form' => true,
		), $atts
	));

    if ( $template == "" )
    {
        $template = "basic";
    }

    require ( KBX_DIR . "/views/templates/$template/template.php" );

}

/*******************************
 * Shortcode for Displaying 
 * Knowledgebase Articles
 *******************************/
add_shortcode('kbx-knowledgebase-articles', 'kbx_show_kbarticle_panel_sc');

function kbx_show_kbarticle_panel_sc( $atts = array() )
{
	ob_start();

    kbx_display_kbarticle_panel( $atts );

    $content = ob_get_clean();

    return $content;
}

function kbx_display_kbarticle_panel( $atts = array() )
{

	//Defaults & Set Parameters
	extract( shortcode_atts(
		array(
			'orderby' => 'date',
			'order' => 'DESC',
			'limit' => 10,
			'section' => '',
			'template' => '',
			'meta_key' => '',
		), $atts
	));

	if( isset($_GET['sort']) && $_GET['sort'] == 'name' ){
		$orderby = 'title';
		$order   = 'ASC';
	}

	if( isset($_GET['sort']) && $_GET['sort'] == 'popularity' ){
		$orderby  = array( 'meta_value_num' => 'DESC' );
		$meta_key = 'kpm_upvotes';
	}

	if( isset($_GET['sort']) && $_GET['sort'] == 'views' ){
		$orderby  = array( 'meta_value_num' => 'DESC' );
		$meta_key = 'kpm_views';
	}

	//Query Parameters
	$kb_args = array(
		'post_type' => 'kbx_knowledgebase',
		'orderby' => $orderby,
		'order' => $order,
		'posts_per_page' => $limit,
		'meta_key' => $meta_key,
        'paged' => get_query_var('paged'),
	);
	
	if( $section != "" )
	{
		$taxArray = array(
			array(
				'taxonomy' => 'kbx_category',
				'field'    => 'term_id',
				'terms'    => $section,
			),
		);
		
		$kb_args = array_merge($kb_args, array( 'tax_query' => $taxArray ));
		
	}
   /*  echo "<pre>";
	 print_r($kb_args);
     echo "</pre>";*/
	// The Query
	$query = new WP_Query( $kb_args );

    if ( $template == "" )
    {
        $template = "basic";
    }

    require ( KBX_DIR . "/views/templates/$template/template-articles.php" );

}

/*******************************
 * Glossary
 *******************************/
add_shortcode('kbx-knowledgebase-glossary', 'kbx_show_glossary_sc');

function kbx_show_glossary_sc( $atts = array() )
{
	ob_start();

    kbx_display_glossary( $atts );

    $content = ob_get_clean();

    return $content;
}


function kbx_display_glossary( $atts = array() )
{

	//Defaults & Set Parameters
	extract( shortcode_atts(
		array(
			'orderby' => 'title',
			'order' => 'ASC',
			'limit' => -1,
			'template' => '',
		), $atts
	));

	$paged = ( get_query_var('page') ) ? get_query_var('page') : 1;

	//Query Parameters
	$kb_args = array(
		'post_type' => 'kbx_knowledgebase',
		'orderby' => $orderby,
		'order' => $order,
		'posts_per_page' => 10,
		'paged' => $paged,
	);

	//check if glossary term is set
	if( isset($_GET['glossary']) && $_GET['glossary'] != '' )
	{
		

		$enableCustomWildCard = array('wildcard_on_key' => true);

		$kb_args = array_merge($kb_args, $enableCustomWildCard);
		
		$glossaryTerm = trim( sanitize_text_field( $_GET['glossary'] ) );

		$metaArray = array(
			array(
				'key' 		=> 'kpm_gterm',
				'value'    	=> $glossaryTerm,
				'compare'   => 'LIKE',
			),
		);
		
		$kb_args = array_merge($kb_args, array( 'meta_query' => $metaArray ));
	}

	// The Query
	$query = new WP_Query( $kb_args );

    if ( $template == "" )
    {
        $template = "basic";
    }

    require ( KBX_DIR . "/views/templates/$template/template-glossary.php" );

}