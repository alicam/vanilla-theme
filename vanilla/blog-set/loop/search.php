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

$search_title = false;
$search_message = false;

if (have_posts()) {
	while (have_posts()) {
		the_post();
		vnl_include('excerpt');
	}
} else {

	$search_title = __('Nothing Found', 'thematic');
	$search_message = __('Sorry, but nothing matched your search criteria. Please try again with some different keywords.', 'thematic');
	
	vnl_include('search');
}

$tpl["loop"] = array(
	"tpl_file" => "search.html",
	"have_posts" => (have_posts()) ? 1 : 0,
	"search_title" => $search_title,
	"search_message" =>  $search_message
);

?>