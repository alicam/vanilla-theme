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

global $comment, $tpl;

add_filter('get_comment_author_link', 'cfct_hcard_comment_author_link');

$author_link = sprintf(__('%s <span class="said">said</span>', 'carrington'), get_comment_author_link());

remove_filter('get_comment_author_link', 'cfct_hcard_comment_author_link');

$comment_date = sprintf(
	__('<span class="on">on</span> <abbr class="published" title="%s"><a title="Permanent link to this comment" rel="bookmark" href="%s#comment-%s">%s</a></abbr>'
	, 'carrington'
	)
	, get_comment_date('Y-m-d\TH:i:sO')
	, get_permalink()
	, get_comment_ID()
	, get_comment_date()
);

$tpl["comment"] = array(
	"tpl_file" => "comment-default.html"
);

$tpl["comments_list"][] = array(
	"id" => get_comment_ID(),
	"class" => cfct_comment_class(false),
	"approved" => ($comment->comment_approved) ? 1 : 0,
	"message" => __('Your comment is awaiting moderation.', 'carrington'),
	"avatar" => get_avatar($comment, 36),
	"author_link" => $author_link,
	"date" => $comment_date,
	"author_url" => get_comment_author_url(),
	"text" => get_comment_text(),
	"edit_link" => get_edit_comment_link($comment->comment_ID),
	"edit_attribute" => __('Edit comment'),
	"edit_message" => __('Edit')
);

?>