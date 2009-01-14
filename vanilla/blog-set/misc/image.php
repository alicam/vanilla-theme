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

global $vnl_width, $vnl_nesting, $vnl_template, $vnl_utility, $tpl, $post;
$vnl_width =  (isset($vnl_width))  ? $vnl_width  : vanilla_get_option("vnl_grid_width");
$vnl_nesting =  (isset($vnl_nesting))  ? $vnl_nesting  : vanilla_get_option("vnl_grid_nesting");
$vnl_template = (isset($vnl_template)) ? $vnl_template : vanilla_get_option("vnl_grid_template");
$vnl_utility =  (isset($vnl_utility))  ? $vnl_utility  : vanilla_get_option("vnl_utility_nesting");
// Page-specific overides
//$vnl_width = "yui-d3";
//$vnl_nesting = "yui-gf";
//$vnl_template = "yui-t2";
//$vnl_utility = "yui-bg";

function vanilla_adjacent_image_link($prev = true) {
	global $post;
	$post = get_post($post);
	$attachments = array_values(get_children( array('post_parent' => $post->post_parent, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => 'ASC', 'orderby' => 'menu_order ID') ));

	foreach ( $attachments as $k => $attachment )
		if ( $attachment->ID == $post->ID )
			break;

	$k = $prev ? $k - 1 : $k + 1;

	if ( isset($attachments[$k]) )
		return wp_get_attachment_link($attachments[$k]->ID, 'thumbnail', true);
}

// create a new PHPTAL template object 
$template = new PHPTAL(vanilla_get_template('misc/image.html') );
$template->cleanUpCache();

get_header();

$four04 = 0;

if (have_posts()) : while (have_posts()) : the_post();

	$parent_permalink = get_permalink($post->post_parent);
	$parent_title = get_the_title($post->post_parent);
	$prev_image_link = vanilla_adjacent_image_link(true);
	$next_image_link = vanilla_adjacent_image_link(false);
	$image_url = wp_get_attachment_url($post->ID);
	$image_link = wp_get_attachment_image( $post->ID, 'full aligncenter' );
	$image_title = get_the_title();
	$image_caption = ( !empty($post->post_excerpt) ) ? get_the_excerpt() : 0;
	$image_description = get_the_content();
	$imgmeta = wp_get_attachment_metadata( $post->ID );

endwhile; else:

	$four04 = 1;
	
endif;

$tpl["page"] = array(
	"width" => $vnl_width,
	"nesting" => $vnl_nesting,
	"template" => $vnl_template,
	"utility" => $vnl_utility,
	"body_class" => sandbox_body_class(false),
	"bd_class" => $vnl_width." ".$vnl_template,
	
	"parent_permalink" => $parent_permalink,
	"parent_title" => $parent_title,
	"prev_image_link" => $prev_image_link,
	"next_image_link" => $next_image_link,
	"image_url" => $image_url,
	"image_link" => $image_link,
	"image_title" => $image_title,
	"image_caption" => $image_caption,
	"image_description" => $image_description,
	
	"four04" => $four04,
	
	"exif_dimensions" => (0 != $imgmeta['width']) ? $imgmeta['width']." x ".$imgmeta['height'] : 0,
	"exif_aperture" => (0 != $imgmeta['image_meta']['aperture']) ? "f/" . $imgmeta['image_meta']['aperture'] : 0,
	"exif_camera" => (0 != $imgmeta['image_meta']['camera']) ? $imgmeta['image_meta']['camera'] : 0,
	"exif_date" => (0 != $imgmeta['image_meta']['created_timestamp']) ? date("d-m-Y H:i", $imgmeta['image_meta']['created_timestamp']) : 0,
	"exif_copyright" => (0 != $imgmeta['image_meta']['copyright']) ? $imgmeta['image_meta']['copyright'] : 0,
	"exif_focal_length" => (0 != $imgmeta['image_meta']['focal_length']) ? $imgmeta['image_meta']['focal_length']."mm" : 0,
	"exif_iso" => (0 != $imgmeta['image_meta']['iso']) ? $imgmeta['image_meta']['iso'] : 0,
	"exif_shutter_speed" => (0 != $imgmeta['image_meta']['shutter_speed']) ? number_format($imgmeta['image_meta']['shutter_speed'],2)." seconds" : 0
);

get_footer();

// Execute the PHPTAL template
vanilla_output_page($template);

?>



