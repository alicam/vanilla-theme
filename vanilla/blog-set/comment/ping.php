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

add_filter('get_comment_author_link', 'cfct_hcard_ping_author_link');

$author_link = sprintf(__('<cite class="vcard author entry-title">%s <span class="linked-to-this-post">linked to this post</span></cite>', 'carrington'), get_comment_author_link());

remove_filter('get_comment_author_link', 'cfct_hcard_ping_author_link');

global $comment, $tpl;

$tpl["ping"] = array(
	"tpl_file" => "ping.html"
);

$tpl["pings_list"][] = array(
	"id" => get_comment_ID(),
	"class" => cfct_comment_class(false),
	"author_link" => $author_link,
	"on" => __('on'),
	"date_attribute" => get_comment_date('Y-m-d\TH:i:sO'),
	"date" => get_comment_date(),
	"author_url" => get_comment_author_url(),
	"text" => get_comment_text(),
	"edit_link" => get_edit_comment_link($comment->comment_ID),
	"edit_attribute" => __('Edit comment'),
	"edit_message" => __('Edit')
);