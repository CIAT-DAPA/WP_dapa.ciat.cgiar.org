<?php
$prinz_ignorePosts = array();

//add posts to array to ignore them from future query_posts
function prinz_ignorePost($id) {
	global $prinzIgnorePosts;
	$prinzIgnorePosts[] = $id;
}

//clear "ignore list"
function prinz_resetIgnorePost() {
	global $prinzIgnorePosts;
	$prinzIgnorePosts = array();
}

//remove ignored posts from query_post
function prinz_postStrip($where) {

	global $prinzIgnorePosts, $wpdb;
	
	if(count($prinzIgnorePosts) > 0) {
		$where .= " AND $wpdb->posts.ID not in(" . implode(",", $prinzIgnorePosts) . ") ";
	}
	
	return $where;
	
}

add_filter("posts_where", "prinz_postStrip");

?>