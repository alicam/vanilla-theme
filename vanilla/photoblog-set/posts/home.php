<?php

// This file is part of the Carrington Theme for WordPress
// http://carringtontheme.com
//
// Copyright (c) 2008 Crowd Favorite, Ltd. All rights reserved.
// http://crowdfavorite.com
//
// Released under the GPL license
// http://www.opensource.org/licenses/gpl-license.php
//
// **********************************************************************
// This program is distributed in the hope that it will be useful, but
// WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. 
// **********************************************************************

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

cfct_loop();
cfct_misc('nav-posts');

get_footer();

// Execute the PHPTAL template
vanilla_output_page($template);

?>



<?php if (have_posts()) : 

// have posts

while (have_posts()) : the_post(); 

// LOOP START

$wp_query->is_single = 1;

if(is_home()) $wp_query->is_single = 0;

?>
	
	<div class="image">
		<div class="nav prev"><?php next_post_link('%link','&lsaquo;') ?></div>
		<?php the_image(); ?>
		<div class="nav next"><?php if(is_home()) $wp_query->is_single = 1; previous_post_link('%link','&rsaquo;'); if(is_home()) $wp_query->is_single = 0; ?></div>
	</div>
	
<?php
	
include 'post.php';

// LOOP END

endwhile; 

// end have posts

// 404 start

else : ?>

	<h2>Not Found</h2>
	<p>Sorry, but you are looking for something that isn't here.</p>
	<?php include (TEMPLATEPATH . "/searchform.php"); ?>

<?php 

// 404 end

endif; ?>

<?php 

// single post next/prev navigation

$wp_query->is_single = 1; ?>

<div class="navigation">
	<div class="prev"><?php next_post_link('%link', '&lsaquo' ) ?></div>
	<div class="next"><?php previous_post_link('%link','&rsaquo;') ?></div>
</div>








