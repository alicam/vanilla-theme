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

global $tpl;

$tpl["excerpt"] = array(
	"tpl_file" => "search.html"
);

$author = sprintf(__('<span class="by">By</span> %s', 'carrington'), '<a class="url fn" href="'.get_author_link(false, get_the_author_ID(), $authordata->user_nicename).'" title="View all posts by ' . attribute_escape($authordata->display_name) . '">'.get_the_author().'</a>');

$tpl["entries"][] = array(
	"id" => get_the_ID(),
	"class" => sandbox_post_class(false),
	"permalink" => get_permalink(),
	"author" => $author,
	"title_attribute" => the_title_attribute('echo=0'),
	"title" => the_title("", "", false),
	"date_time" => get_the_time('Y-m-d\TH:i:sO'),
	"date" => get_the_time('F j, Y'),
	"content" => get_the_excerpt()
);
?>