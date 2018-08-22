<?php

//Register Meta Box
function kbx_register_meta_box() 
{
    add_meta_box( 'kbx-knowledgebase-meta', esc_html__( 'Custom Fields', 'kbx-qc' ), 'kbx_meta_box_callback', 'kbx_knowledgebase', 'advanced', 'high' );
}

add_action( 'add_meta_boxes', 'kbx_register_meta_box');
 
//Add field
function kbx_meta_box_callback( $object ) {
 
    $kpm_upvotes = get_post_meta( $object->ID, 'kpm_upvotes', true );
    $kpm_ranking = get_post_meta( $object->ID, 'kpm_ranking', true );
    $kpm_views = get_post_meta( $object->ID, 'kpm_views', true );
    $kpm_gterm = get_post_meta( $object->ID, 'kpm_gterm', true );

    $outline = '<div><label for="kpm_upvotes" style="width:150px; display:inline-block;">'. esc_html__('Upvote Count', 'kbx-qc') .'</label>';
    
    $outline .= '<input type="text" name="kpm_upvotes" id="kpm_upvotes" class="kpm_upvotes" value="'. esc_attr($kpm_upvotes) .'" style="width:300px;"/></div>';

    $outline .= '<div><label for="kpm_ranking" style="width:150px; display:inline-block;">'. esc_html__('Ranking', 'kbx-qc') .'</label>';
    
    $outline .= '<input type="text" name="kpm_ranking" id="kpm_ranking" class="kpm_ranking" value="'. esc_attr($kpm_ranking) .'" style="width:300px;"/></div>';

    $outline .= '<div><label for="kpm_views" style="width:150px; display:inline-block;">'. esc_html__('Views', 'kbx-qc') .'</label>';
    
    $outline .= '<input type="text" name="kpm_views" id="kpm_views" class="kpm_views" value="'. esc_attr($kpm_views) .'" style="width:300px;"/></div>';

    $outline .= '<div><label for="kpm_gterm" style="width:150px; display:inline-block;">'. esc_html__('Glossary Term', 'kbx-qc') .'</label>';
    
    $outline .= '<input type="text" name="kpm_gterm" id="kpm_gterm" class="kpm_gterm" value="'. esc_attr($kpm_gterm) .'" style="width:300px;"/></div>';
 
    echo $outline;
}

function kbx_save_custom_meta_box($post_id, $post, $update)
{

    if(!current_user_can("edit_post", $post_id))
        return $post_id;

    if(defined("DOING_AUTOSAVE") && DOING_AUTOSAVE)
        return $post_id;

    $slug = "kbx_knowledgebase";

    if($slug != $post->post_type)
        return $post_id;

    $upvotes = "";
    $ranking = "";
    $views = "";
    $gterm = "";

    if(isset($_POST["kpm_upvotes"]))
    {
        $upvotes = sanitize_text_field( $_POST["kpm_upvotes"] );
    }

    update_post_meta($post_id, "kpm_upvotes", $upvotes);

    if(isset($_POST["kpm_ranking"]))
    {
        $ranking = sanitize_text_field( $_POST["kpm_ranking"] );
    }

    update_post_meta($post_id, "kpm_ranking", $ranking);

    if(isset($_POST["kpm_views"]))
    {
        $views = sanitize_text_field( $_POST["kpm_views"] );
    }

    update_post_meta($post_id, "kpm_views", $views);

    if(isset($_POST["kpm_gterm"]))
    {
        $gterm = sanitize_text_field( $_POST["kpm_gterm"] );
    }

    update_post_meta($post_id, "kpm_gterm", $gterm);

    //Set the detault section as Uncategorized for unassigned section.
    if ( 'publish' === $post->post_status ) {
        $defualt_term = get_term_by('slug', 'uncategorized', 'kbx_category');
        $defualt_term_id = $defualt_term->term_id;
        $all_terms = get_the_terms($post_id, 'kbx_category');
        if (empty($all_terms)) {
            wp_set_object_terms($post_id, $defualt_term_id, 'kbx_category');
        }
    }
}

add_action("save_post", "kbx_save_custom_meta_box", 10, 3);