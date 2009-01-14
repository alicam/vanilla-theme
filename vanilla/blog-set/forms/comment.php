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

global $post, $user_ID, $user_identity, $comment_author, $comment_author_email, $comment_author_url, $tpl;

$req = get_option('require_name_email');

do_action('comment_form', $post->ID);

$tpl[] = array(
	"wpurl" => get_bloginfo('wpurl')
);

$tpl["comment_form"] = array(
	"tpl_file" => "comment.html",
	"open" => ($post->comment_status) ? 1 : 0,
	"logged_in" => ($user_ID) ? 1 : 0,
	"must_login" => (get_option('comment_registration') && !$user_ID) ? 1 : 0,
	"logged_in_message" => sprintf(__('You must be <a href="%s">logged in</a> to post a comment.', 'carrington'), get_bloginfo('wpurl').'/wp-login.php?redirect_to='.get_permalink()),
	"comment_label" => __('Post a comment', 'carrington'),
	"allowed_html_attribute" => sprintf(__('You can use: %s', 'carrington'), allowed_tags()),
	"allowed_html_message" => __('Some HTML is OK', 'carrington'),
	"logged_in_as" => sprintf(__('Logged in as <a href="%s">%s</a>.', 'carrington'), get_bloginfo('wpurl').'/wp-admin/profile.php', $user_identity),
	"log_out_attribute" => __('Log out of this account', 'carrington'),
	"log_out_message" => __('Logout &rarr;', 'carrington'),
	"author_label" => __('Name', 'carrington'),
	"author_value" => $comment_author,
	"required" => ($req) ? 1 : 0,
	"required_message" => __('(required)', 'carrington'),
	"email_label" => __('Email', 'carrington'),
	"email_value" => $comment_author_email,
	"required_email_message" => __('(required, but never shared)', 'carrington'),
	"email_message" => __('(never shared)', 'carrington'),
	"url_attribute" => __('Your website address', 'carrington'),
	"url_label" => __('Web', 'carrington'),
	"url_value" => $comment_author_url,
	"submit_text" => __('Post comment', 'carrington'),
	"trackback_message" => sprintf(__('or, reply to this post via <a rel="trackback" href="%s">trackback</a>.', 'carrington'), get_trackback_url()),
	"post_id" => $post->ID
);