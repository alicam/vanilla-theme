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

global $vnl_width, $vnl_nesting, $vnl_template, $vnl_utility, $tpl;
$vnl_width =  (isset($vnl_width))  ? $vnl_width  : vanilla_get_option("vnl_grid_width");
$vnl_nesting =  (isset($vnl_nesting))  ? $vnl_nesting  : vanilla_get_option("vnl_grid_nesting");
$vnl_template = (isset($vnl_template)) ? $vnl_template : vanilla_get_option("vnl_grid_template");
$vnl_utility =  (isset($vnl_utility))  ? $vnl_utility  : vanilla_get_option("vnl_utility_nesting");
// Page-specific overides
//$vnl_width = "yui-d3";
//$vnl_nesting = "yui-gd";
//$vnl_template = "yui-t2";
//$vnl_utility = "yui-bg";

// create a new PHPTAL template object 
$template = new PHPTAL( vanilla_get_template('posts/home.html') );
$template->cleanUpCache();

$tpl["page"] = array(
	"width" => $vnl_width,
	"nesting" => $vnl_nesting,
	"template" => $vnl_template,
	"utility" => $vnl_utility,
	"body_class" => sandbox_body_class(false),
	"bd_class" => $vnl_width." ".$vnl_template
);

get_header();

vnl_include('loop');
vnl_include('nav-posts');

get_footer();

// Execute the PHPTAL template
vanilla_output_page($template);

?>