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

function vanilla_get_option($name) {
	$defaults = array(
		'vnl_tpl_set' => array('blog', 'Blog (default)')
		, 'vnl_grid_width' => array('yui-d1', '750 pixels, centered')
		, 'vnl_custom_width' => 1000
		, 'vnl_grid_template' => array('yui-t7', 'none')
		, 'vnl_grid_nesting' => array('yui-g', 'none')
		, 'vnl_utility_nesting' => array('yui-ga', 'none')
		, 'vnl_insert_position' => 2
		, 'vnl_authorinfo' => false
		, 'vnl_footertext' => '<span id="generator-link">You are enjoying the taste by <span id="designer-link"><a href="http://www.alistercameron.com/vanilla-theme-for-wordpress" title="Vanilla Theme" rel="designer">Vanilla flavored</a> <a href="http://WordPress.org/" title="WordPress" rel="generator">WordPress</a></span></span><span class="meta-sep">.</span>'
	);
	$value = get_option($name);
	if ($value == '' && isset($defaults[$name])) {
		$value = $defaults[$name];
		$value = (is_array($value)) ? $value[0] : $value;
	}
	return $value;
}

function vnl_include($template) {
	switch ($template) {
	
		// page template
		case 'page':
			$file = cfct_default_file('pages');
			cfct_template_file('pages', $file);
			break;
		
		// content template
		case 'content':
		case 'excerpt':
			$file = cfct_choose_content_template($template);
			cfct_template_file($template, $file);
			break;
		
		// comment template
		case 'comment':
			$file = cfct_choose_comment_template($template);
			cfct_template_file($template, $file);
			break;
		
		// forms template
		case 'comment-form':
		case 'search':
			cfct_template_file('forms', str_replace('-form','' , $template));
			break;
			
		// misc template
		case 'banner':
		case 'image':
		case 'nav-posts':
			cfct_template_file('misc', $template);
			break;
			
		// error template
		case '404':
		case 'exit':
			cfct_template_file('error', $template);
			break;
		
		// general template (everything else!)
		default:
			$file = cfct_choose_general_template($template);
			cfct_template_file($template, $file);
	}
}

// Check whether a child theme template file exists, otherwise return the vanilla file.
function vanilla_get_template($path) {
	global $tpl_set;
	$child_template = STYLESHEETPATH.'/'.$tpl_set.$path;
	$parent_template = CFCT_PATH.$tpl_set.$path;
	return ( file_exists($child_template) ) ? $child_template : ( file_exists($parent_template) ) ? $parent_template : false;
}

// These are all the templates we *always* need (e.g. TAL shortcode templates)
function vanilla_load_tpl_includes() {
	global $tpl_set;

	// list of directories containing pairs of PHP/HTML template files. Include the PHP files now for later use...
	$dirs = array(
		"shortcodes"
		// can add more later as they are "thought up"!
	);
	foreach ($dirs as $dir) {
		// load template item from either parent or child theme
		$files = cfct_files(CFCT_PATH.$tpl_set.$dir);
		foreach ($files as $file) {
			include_once(vanilla_get_template($dir.'/'.$file));
		}
	}
}
add_action('init', 'vanilla_load_tpl_includes');

function vanilla_output_page($template) {
	global $tpl;
	
	$template->set('vanilla', $tpl);
	if (!VANILLA_DEBUG) $template->setPostFilter(new Minify_HTML());
	
	try { echo $template->execute(); }
	catch (Exception $e){ echo $e; }
}

function vanilla_add_debug_css(){
	if (!VANILLA_DEBUG) return;
?>
	<style type="text/css">
	/* Vanilla debugging CSS - set the constant in functions.php to 'false' to remove. */
	.debug, .grid-debug, .widget-debug, .doc-debug { display: block; text-align: left; border: 1px solid #090; background: #cfc; color: #060; padding: 0.2em 0.5em; filter: alpha(opacity=50);-moz-opacity: 0.50; opacity: 0.50; }
	.grid-debug, .doc-debug { color: #900; background: #fcc; border-color: #900; }
	.widget-debug { color: #009; background: #ccf; border-color: #009; }
	</style>
<?php
}
add_action('wp_head', 'vanilla_add_debug_css');
?>