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

global $tpl, $authordata;

$author_title = $author_name = false;
$author_desc = "";

if (have_posts()) {
	while (have_posts()) {
		the_post();

		$title = sprintf(__('Posts by: <a href="%s">%s</a>', 'carrington'), get_author_posts_url($authordata->ID), get_author_name($authordata->ID)); 

		if (!empty($authordata->ID)) {

			$author_name = sprintf(__('About %s', 'carrington'), get_author_name($authordata->ID));
			$author_desc = apply_filters('the_content', get_the_author_description()); 

		}
		break;
	}
}

$tpl["loop"] = array(
	"tpl_file" => "author.html",
	"author_title" => $title,
	"author_name" => $author_name,
	"author_description" => $author_desc
);

rewind_posts();

if (have_posts()) {
	
	while (have_posts()) {
		the_post();
		vnl_include('excerpt');
	}
}
?>