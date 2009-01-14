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

function vanilla_get_attachment_link($id = 0, $size = 'thumbnail', $permalink = false, $icon = false) {
	echo $id;
	return $id;
}
// Testing here... no other purpose for this!!
//add_filter( 'wp_get_attachment_link', 'vanilla_get_attachment_link', $id, $size, $permalink, $icon );

/**
 * OVERRIDES: gallery_shortcode()
 *
 * This implements the functionality of the Gallery Shortcode for displaying
 * WordPress images on a post.
 */
function vanilla_gallery($attr) {
	global $post, $tpl;
	
	// BUG: I'm doing something wrong, because $attr is not the array of attributes from gallery_shortcode function. Why not??

	// We're trusting author input, so let's at least make sure it looks like a valid orderby statement
	if ( isset( $attr['orderby'] ) ) {
		$attr['orderby'] = sanitize_sql_orderby( $attr['orderby'] );
		if ( !$attr['orderby'] )
			unset( $attr['orderby'] );
	}

	extract(shortcode_atts(array(
		'order'      => 'ASC',
		'orderby'    => 'menu_order ID',
		'id'         => $post->ID,
		'itemtag'    => 'dl',
		'icontag'    => 'dt',
		'captiontag' => 'dd',
		'columns'    => 3,
		'size'       => 'thumbnail',
	), $attr));

	$id = intval($id);
	$attachments = get_children( array('post_parent' => $id, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $order, 'orderby' => $orderby) );

	if ( empty($attachments) )
		return '';

	if ( is_feed() ) {
		$output = "\n";
		foreach ( $attachments as $id => $attachment )
			$output .= wp_get_attachment_link($id, $size, true) . "\n";
		return $output;
	}

	$images = array();
	
	foreach ( $attachments as $id => $attachment ) {
		$images[] = array(
			"link" => wp_get_attachment_link($id, $size, true),
			"caption" => ( $captiontag && trim($attachment->post_excerpt) ) ? $attachment->post_excerpt : 0
		);
	}

	// Prepare the template data
	$tpl["gallery"] = array(
		"tpl_file" => "shortcodes/gallery.html",
		"itemtag" => tag_escape($itemtag),
		"icontag" => tag_escape($icontag),
		"captiontag" => tag_escape($captiontag),
		"columns" => intval($columns),
		"itemwidth" => ($columns > 0) ? floor(100/$columns)-1 : 100,
		"images" => $images
	);
	
	// Execute the template
	return vanilla_shortcode("gallery");
}

add_filter('post_gallery', 'vanilla_gallery', 999);

?>