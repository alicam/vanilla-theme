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

function widget_site_credits() {
	global $tpl;
	$tpl["widget"]["site_credits"] = array(
		"generator_link" => __('Proudly powered by <a href="http://wordpress.org/" title="WordPress" rel="generator">WordPress</a></span> and <span id="theme-link"><a href="http://carringtontheme.com" title="Carrington theme for WordPress">Carrington</a></span>.', 'carrington'),
		"developer_link" => sprintf(__('<a href="http://crowdfavorite.com" title="Custom WordPress development, design and backup services." rel="developer designer">%s</a>', 'carrington'), 'Carrington Theme by Crowd Favorite')
	);
}

function widget_site_credits_control() {}

//vanilla_register_widget(function name, description);
vanilla_register_widget('widget_site_credits', 'Site Credits (footer)');

?>