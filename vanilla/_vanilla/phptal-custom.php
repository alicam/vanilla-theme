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

// Load and execute a specific PHPTAL template for each templated shortcode
function vanilla_shortcode($shortcode){
	global $tpl_set, $tpl;
	
	$active_template = vanilla_get_template('shortcodes/' . $shortcode . ".html");
	
	if (!$active_template) return "";
	// No need to include the PHP tpl file here. Already loaded at init.
	
	$tpl_source = '<metal:block define-macro="'.$shortcode.'_shortcode">' . "\n" .
		"<!-- shortcode: ".$shortcode." -->\n" .
		'<span tal:condition="php:VANILLA_DEBUG" class="widget-debug">SHORTCODE: '.$shortcode.'</span>' . "\n" .
		'<span metal:use-macro="'.$active_template.'/loader" />' . "\n" .
		'<span metal:define-slot="'.$shortcode.'" />' . "\n" .
		'</metal:block><metal:block use-macro="'.$shortcode.'_shortcode" />'."\n";
		
	//return "<textarea style='width:500px; height:300px;'> $tpl_source </textarea>";
	
	// Load and fire the PHPTAL template!
	$template = new PHPTAL();
	$template->setSource($tpl_source, $tpl_set.$shortcode);
	$template->set('vanilla', $tpl);
	try { return $template->execute(); }
	catch (Exception $e){ return $e; }
}

// Using ugly filter to get a return, not echo.
$comments_number = "";
function redir_comments_number($out){
	global $comments_number;
	$comments_number = $out;
	return "";
}
add_filter('comments_number', 'redir_comments_number');

// no filter here so have to redo the function using return, not echo.
function vanilla_comments_popup_link( $zero = 'No Comments', $one = '1 Comment', $more = '% Comments', $css_class = '', $none = 'Comments Off' ) {
	global $id, $wpcommentspopupfile, $wpcommentsjavascript, $post, $comments_number;
	
	$output = false;

	if ( is_single() || is_page() )
		return $output;

	$number = get_comments_number( $id );

	if ( 0 == $number && 'closed' == $post->comment_status && 'closed' == $post->ping_status ) {
		return '<span' . ((!empty($css_class)) ? ' class="' . $css_class . '"' : '') . '>' . $none . '</span>';
	}

	if ( post_password_required() ) {
		return __('Enter your password to view comments');
	}

	$output .= '<a href="';
	if ( $wpcommentsjavascript ) {
		if ( empty( $wpcommentspopupfile ) )
			$home = get_option('home');
		else
			$home = get_option('siteurl');
		$output .= $home . '/' . $wpcommentspopupfile . '?comments_popup=' . $id;
		$output .= '" onclick="wpopen(this.href); return false"';
	} else { // if comments_popup_script() is not in the template, display simple comment link
		if ( 0 == $number )
			$output .= get_permalink() . '#respond';
		else
			$output .= get_comments_link();
		$output .= '"';
	}

	if ( !empty( $css_class ) ) {
		$output .= ' class="'.$css_class.'" ';
	}
	$title = attribute_escape( get_the_title() );

	$output .= apply_filters( 'comments_popup_link_attributes', '' );

	$output .= ' title="' . sprintf( __('Comment on %s'), $title ) . '">';
	comments_number( $zero, $one, $more, $number );
	$output .= $comments_number . '</a>';
	
	return $output;
}
?>