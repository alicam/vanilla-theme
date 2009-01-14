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

// Alister - added to handle Thematic-naming-convention of options/variables - perhaps not needed? A better way?
global $options, $tpl;
foreach ($options as $value) {
	$$value['id'] = (get_settings( $value['id'] ) === FALSE) ? $value['std'] : get_settings( $value['id'] );
}

$tpl["loop"] = array(
	"tpl_file" => "loop-default.html"
);

if (have_posts()) {
	
	while (have_posts()) {
		the_post();
		vnl_include('content');
	}
}

?>