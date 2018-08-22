<?php

function kbx_post_like_action()
{

	//Get posted items
	$post_id = trim( sanitize_text_field( $_POST['post_id'] ) );
	$post_id = intval($post_id);

	//Check wpdb directly, for all matching meta items
	global $wpdb;

	//Defaults
	$votes = 0;

	$data['votes'] = 0;
	$data['vote_status'] = 'failed';

	$exists = in_array("$post_id", $_COOKIE['voted_articles']);

	//If li-id not exists in the cookie, then prceed to vote
	if( !$exists )
	{
		
		$votes = get_post_meta($post_id, 'kpm_upvotes', true);

		if( $votes == "" || $votes == null ){
			$votes = 0;
		}

		$vote_increment = $votes + 1;

		update_post_meta($post_id, 'kpm_upvotes', $vote_increment);

		setcookie("voted_articles[]",$post_id, time() + (86400 * 30), "/");

		$data['vote_status'] = 'success';
		$data['votes'] = $vote_increment;
	}

	$data['cookies'] = $_COOKIE['voted_articles'];

	echo json_encode($data);

	die(); // stop executing script
}

//Implementing the ajax action for frontend users
add_action( 'wp_ajax_kbx_post_like_action', 'kbx_post_like_action' );
add_action( 'wp_ajax_nopriv_kbx_post_like_action', 'kbx_post_like_action' );