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

// Default YUI Grid settings
global $vnl_width, $vnl_nesting, $vnl_template, $vnl_utility, $tpl;
$vnl_width = get_option("vnl_grid_width");
$vnl_nesting = get_option("vnl_grid_nesting");
$vnl_template = get_option("vnl_grid_template");
$vnl_utility = get_option("vnl_utility_nesting");

$language_attributes = "";
function redir_language_attributes($out){
	global $language_attributes;
	$language_attributes = $out;
	return "";
}
add_filter('language_attributes', 'redir_language_attributes');
language_attributes();

global $tpl_set;

$ie_cond_stylesheet = "<!--[if lt IE 8]>\n" .
	'<link rel="stylesheet" href="'.get_bloginfo('template_url').'/'.$tpl_set.'ie.css" type="text/css" media="screen" charset="utf-8" />' . "\n" .
	"<![endif]-->\n";

$tpl["header"] = array(
	"tpl_file" => "header-default.html",
	"language_attributes" => "", //$language_attributes,
	"content_type" => get_bloginfo('html_type')."; charset=".get_bloginfo('charset'),
	"stylesheet_url" => str_replace("style.css", "", get_bloginfo('stylesheet_url')).$tpl_set.'style.css',
	"template_url" => get_bloginfo('template_url').'/'.$tpl_set,
	"ie_cond_stylesheet" => $ie_cond_stylesheet
);

?>