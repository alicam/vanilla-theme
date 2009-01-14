<?php
/*
	Copyright (c) 2008, Australis Media Pty Ltd. All rights reserved.
	
	Australis Media Pty Ltd has made the contents of this file
	available under a CC-GNU-GPL license:
	
	 http://creativecommons.org/licenses/GPL/2.0/
	
	 A copy of the full license can be found as part of this
	 distribution in the file LICENSE.TXT
	
	You may use the Vanilla theme software in accordance with the
	terms of that license. You agree that you are solely responsible
	for your use of the Vanilla theme software and you represent and 
	warrant to Australis Media Pty Ltd that your use of the Vanilla
	theme software will comply with the CC-GNU-GPL.
*/

if (__FILE__ == $_SERVER['SCRIPT_FILENAME']) { die(); }
if (CFCT_DEBUG) { cfct_banner(__FILE__); }

global $post, $wp_query, $comments, $comment, $tpl;
if (empty($post->post_password) || $_COOKIE['wp-postpass_' . COOKIEHASH] == $post->post_password) {
	$comments = $wp_query->comments;
	$comment_count = count($comments);
	$comment_count == 1 ? $comment_title = __('One Response', 'carrington') : $comment_title = sprintf(__('%d Responses', 'carrington'), $comment_count);
}

$comments_desc = sprintf(__('Stay in touch with the conversation, subscribe to the <a class="feed" title="RSS feed for comments on this post" rel="alternate" href="%s"><acronym title="Really Simple Syndication">RSS</acronym> feed for comments on this post</a>.', 'carrington'), get_bloginfo('rss2_url'));

$tpl["comments"] = array(
	"tpl_file" => "comments-default.html",
	"comments_title" => $comment_title,
	"comments_description" => $comments_desc,
	"pings_title" => __('Continuing the Discussion', 'carrington')
);

if ($comments) {
	$comment_count = 0;
	$ping_count = 0;
	foreach ($comments as $comment) {
		if (get_comment_type() == 'comment') {
			$comment_count++;
		}
		else {
			$ping_count++;
		}
	}
	if ($comment_count) {
		cfct_template_file('comments', 'comments-loop');
	}
	if ($ping_count) {
		cfct_template_file('comments', 'pings-loop');
	}
}

vnl_include('comment-form');

?>