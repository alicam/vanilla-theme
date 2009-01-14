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

// Load widget PHP files from the widgets directory
function vanilla_load_widgets() {
	global $tpl_set;
	$files = cfct_files(CFCT_PATH.$tpl_set.'widgets');
	foreach ($files as $file) {
		include(CFCT_PATH.$tpl_set.'widgets/'.$file);
	}
}

function vanilla_widget_block_wrapper($block){
	// called from within a dynamic PHPTAL macro (below) to stop it outputting a '1' to screen.
	if (!dynamic_sidebar($block)) {
		// do nothing;
	}
}

function vanilla_widget_template_markup($block=null) {
	global $wp_registered_sidebars, $wp_registered_widgets;
	
	$tpl_source = "";

	$block = sanitize_title($block);
	foreach ( (array) $wp_registered_sidebars as $key => $value ) {
		if ( sanitize_title($value['name']) == $block ) {
			$block = $key;
			break;
		}
	}

	$sidebars_widgets = wp_get_sidebars_widgets();

	if ( empty($wp_registered_sidebars[$block]) || !array_key_exists($block, $sidebars_widgets) || !is_array($sidebars_widgets[$block]) || empty($sidebars_widgets[$block]) )
		return "";

	$sidebar = $wp_registered_sidebars[$block];

	foreach ( (array) $sidebars_widgets[$block] as $id ) {
		$params = array_merge(
			array( array_merge( $sidebar, array('widget_id' => $id, 'widget_name' => $wp_registered_widgets[$id]['name']) ) ),
			(array) $wp_registered_widgets[$id]['params']
		);

		$params = apply_filters( 'dynamic_sidebar_params', $params );
		$callback = $wp_registered_widgets[$id]['callback'];
		$widget_name = str_replace("widget_", "", strtolower($callback));
		$active_template = vanilla_get_template('widgets/' . str_replace("_", "-", $widget_name) . ".html");
		
		if (!$active_template) return "";
		
		//echo $widget_name . " " . $widget_filename;

		if ( is_callable($callback) ) {
			call_user_func_array($callback, $params);
			
			$tpl_source .= '<span metal:use-macro="'.$active_template.'/loader" />' . "\n" .
					'<span tal:condition="php:VANILLA_DEBUG" class="widget-debug">WIDGET: '.$widget_name.'</span>' . "\n" .
					'<span metal:define-slot="'.$widget_name.'" />' . "\n";	
		}
	}
	return $tpl_source;
}

function vanilla_widget_block($block=null){
	$block = sanitize_title_with_dashes(strtolower($block));
	
	// Apply action
	do_action('vanilla_widget_' . str_replace('-', '_', $block) . '_before');
	
	if ( function_exists('dynamic_sidebar') && is_sidebar_active($block) ) {
		
		$tpl_source = '<metal:block define-macro="'.str_replace("-", "_", $block).'">' . "\n" .
				"<!-- widget block: ".$block." -->\n" .
				'<span tal:condition="php:VANILLA_DEBUG" class="widget-debug">'.$block.'</span>' . "\n";
		$tpl_source .= vanilla_widget_template_markup($block);
		$tpl_source .= '${php:vanilla_widget_block_wrapper(\''.$block.'\')}' . "\n" .
				'</metal:block><metal:block use-macro="'.str_replace("-", "_", $block).'" />'."\n";
		
		//echo $tpl_source;
		
		echo "\t\t<div id=\"" . $block . "\" class=\"block\">\n";
		
		// Load and fire the PHPTAL template!
		$$block = new PHPTAL();
		global $tpl_set;
		$$block->setSource($tpl_source, $tpl_set.$block);
		vanilla_output_page($$block);
		
		echo "</div>\n";
	}
	
	// Apply action
	do_action('vanilla_widget_' . str_replace('-', '_', $block) . '_after');
}

// Make widget registration really easy
function vanilla_register_widget($classname=null, $description=null){
	if(!isset($classname)) return false;
	
	$widget = str_replace("widget_", "", $classname);
	$description = (!isset($description) || $description == "") ? $widget : $description;
	
	// clean up the description a tad
	$description = ucwords(str_replace("_", " ", $description));
	
	wp_register_sidebar_widget( $widget, $description, $classname, 
		array(
			'classname'    =>  $classname,
			'description'  =>  $description
		)
	);
	wp_register_widget_control( $widget, $description, $classname.'_control' );
}

// Widget: Global_Nav
function widget_globalnav($args) {
	extract($args);
	$options = get_option('widget_globalnav');
	$title = empty($options['title']) ? __( 'Main Menu', 'sandbox' ) : $options['title'];
	$params = empty($options['params']) ? __( 'Parameters', 'sandbox' ) : $options['params'];
 
	// Produces a list of pages in the header without whitespace -- er, I mean negative space.
	echo '<div id="menu"><ul>'."\n";
	$menu = wp_list_pages('title_li=&sort_column=menu_order&echo=0&'.$params) .
		'<li class="rss"><a href="' . get_bloginfo('rss2_url') . '">Subscribe</a></li>' .
		'<li class="delicious"><a href="#" onclick="delicious_bookmark();" title="Click here to bookmark this page on del.icio.us...">Bookmark</a></li>' .
		'<li class="technorati"><a href="http://technorati.com/faves?add=' . get_option('home') . '">Favorite</a></li>';
	echo str_replace(array("\r", "\n", "\t"), '', $menu);
	echo "</ul></div>\n";
}

// Widget: Global_Nav Control
function widget_globalnav_control() {
	$options = $newoptions = get_option('widget_globalnav');
	if ( $_POST['globalnav-submit'] ) {
		$newoptions['title'] = strip_tags( stripslashes( $_POST['globalnav-title'] ) );
		$newoptions['params'] = strip_tags( stripslashes( $_POST['globalnav-params'] ) );
	}
	if ( $options != $newoptions ) {
		$options = $newoptions;
		update_option( 'widget_globalnav', $options );
	}
	$title = attribute_escape( $options['title'] );
	$params = attribute_escape( $options['params'] );
?>
			<p><label for="globalnav-title"><?php _e( 'Title:', 'sandbox' ) ?> <input class="widefat" id="globalnav-title" name="globalnav-title" type="text" value="<?php echo $title; ?>" /></label></p>
			<p><label for="globalnav-params"><?php _e( 'Parameters:', 'sandbox' ) ?> <input class="widefat" id="globalnav-params" name="globalnav-params" type="text" value="<?php echo $params; ?>" /></label></p>
			<input type="hidden" id="globalnav-submit" name="globalnav-submit" value="1" />
<?php
}

// Widget: Search; to match the Sandbox style and replace Widget plugin default
function widget_sandbox_search($args) {
	extract($args);
	$options = get_option('widget_sandbox_search');
	$title = empty($options['title']) ? __( 'Search', 'sandbox' ) : $options['title'];
	$button = empty($options['button']) ? __( 'Find', 'sandbox' ) : $options['button'];
?>
			<?php echo $before_widget ?>
				<?php echo $before_title ?><label for="s"><?php echo $title ?></label><?php echo $after_title ?>
				<form id="searchform" method="get" action="<?php bloginfo('home') ?>">
					<div>
						<input id="s" class="text-input" name="s" type="text" value="<?php the_search_query() ?>" size="10" tabindex="1" accesskey="S" />
						<input id="searchsubmit" class="submit-button" name="searchsubmit" type="submit" value="<?php echo $button ?>" tabindex="2" />
					</div>
				</form>
			<?php echo $after_widget ?>
<?php
}

// Widget: Search; element controls for customizing text within Widget plugin
function widget_sandbox_search_control() {
	$options = $newoptions = get_option('widget_sandbox_search');
	if ( $_POST['search-submit'] ) {
		$newoptions['title'] = strip_tags( stripslashes( $_POST['search-title'] ) );
		$newoptions['button'] = strip_tags( stripslashes( $_POST['search-button'] ) );
	}
	if ( $options != $newoptions ) {
		$options = $newoptions;
		update_option( 'widget_sandbox_search', $options );
	}
	$title = attribute_escape( $options['title'] );
	$button = attribute_escape( $options['button'] );
?>
			<p><label for="search-title"><?php _e( 'Title:', 'sandbox' ) ?> <input class="widefat" id="search-title" name="search-title" type="text" value="<?php echo $title; ?>" /></label></p>
			<p><label for="search-button"><?php _e( 'Button Text:', 'sandbox' ) ?> <input class="widefat" id="search-button" name="search-button" type="text" value="<?php echo $button; ?>" /></label></p>
			<input type="hidden" id="search-submit" name="search-submit" value="1" />
<?php
}

// Widget: Meta; to match the Sandbox style and replace Widget plugin default
function widget_sandbox_meta($args) {
	extract($args);
	if ( empty($title) )
		$title = __('Meta', 'sandbox');
?>
			<?php echo $before_widget; ?>
				<?php echo $before_title . $title . $after_title; ?>
				<ul>
					<?php wp_register() ?>
					<li><?php wp_loginout() ?></li>
					<?php wp_meta() ?>
				</ul>
			<?php echo $after_widget; ?>
<?php
}

// Widget: Navigation_top
function widget_navigation_top($args) {
	if (is_single() || is_page()) return;
?>
			<div id="nav-above" class="navigation">
                <?php if(function_exists('wp_pagenavi')) { wp_pagenavi(); }
                elseif(function_exists('wp_page_numbers')) { wp_page_numbers(); }
                else { ?>  
				<div class="nav-previous"><?php next_posts_link(__('<span class="meta-nav">&laquo;</span> Older posts', 'thematic')) ?></div>
				<div class="nav-next"><?php previous_posts_link(__('Newer posts <span class="meta-nav">&raquo;</span>', 'thematic')) ?></div>
				<?php } ?>
			</div>
<?php
}

// Widget: Navigation_top; element controls for customizing text within Widget plugin
function widget_navigation_top_control() {
	$options = $newoptions = get_option('widget_navigation_top');
	if ( $_POST['navigation-top-submit'] ) {
		$newoptions['title'] = strip_tags( stripslashes( $_POST['navigation-top-title'] ) );
	}
	if ( $options != $newoptions ) {
		$options = $newoptions;
		update_option( 'widget_navigation_top', $options );
	}
	$title = attribute_escape( $options['title'] );
?>
			<p><label for="navigation-top-title"><?php _e( 'Title:', 'sandbox' ) ?> <input class="widefat" id="navigation-top-title" name="navigation-top-title" type="text" value="<?php echo $title; ?>" /></label></p>
			<input type="hidden" id="navigation-top-submit" name="navigation-top-submit" value="1" />
<?php
}

// Widget: Navigation_bottom
function widget_navigation_bottom($args) {
	if (is_single() || is_page()) return;
?>
			<div id="nav-below" class="navigation">
                <?php if(function_exists('wp_pagenavi')) { wp_pagenavi(); }
                elseif(function_exists('wp_page_numbers')) { wp_page_numbers(); }
                else { ?>  
				<div class="nav-previous"><?php next_posts_link(__('<span class="meta-nav">&laquo;</span> Older posts', 'thematic')) ?></div>
				<div class="nav-next"><?php previous_posts_link(__('Newer posts <span class="meta-nav">&raquo;</span>', 'thematic')) ?></div>
				<?php } ?>
			</div>
<?php
}

// Widget: Navigation_bottom; element controls for customizing text within Widget plugin
function widget_navigation_bottom_control() {
	$options = $newoptions = get_option('widget_navigation_bottom');
	if ( $_POST['navigation-bottom-submit'] ) {
		$newoptions['title'] = strip_tags( stripslashes( $_POST['navigation-bottom-title'] ) );
	}
	if ( $options != $newoptions ) {
		$options = $newoptions;
		update_option( 'widget_navigation_bottom', $options );
	}
	$title = attribute_escape( $options['title'] );
?>
			<p><label for="navigation-bottom-title"><?php _e( 'Title:', 'sandbox' ) ?> <input class="widefat" id="navigation-bottom-title" name="navigation-bottom-title" type="text" value="<?php echo $title; ?>" /></label></p>
			<input type="hidden" id="navigation-bottom-submit" name="navigation-bottom-submit" value="1" />
<?php
}

// Widget: RSS links; to match the Sandbox style
function widget_sandbox_rsslinks($args) {
	extract($args);
	$options = get_option('widget_sandbox_rsslinks');
	$title = empty($options['title']) ? __('RSS Links', 'sandbox') : $options['title'];
?>
		<?php echo $before_widget; ?>
			<?php echo $before_title . $title . $after_title; ?>
			<ul>
				<li><a href="<?php bloginfo('rss2_url') ?>" title="<?php echo wp_specialchars(get_bloginfo('name'), 1) ?> <?php _e('Posts RSS feed', 'sandbox'); ?>" rel="alternate" type="application/rss+xml"><?php _e('All posts', 'sandbox') ?></a></li>
				<li><a href="<?php bloginfo('comments_rss2_url') ?>" title="<?php echo wp_specialchars(bloginfo('name'), 1) ?> <?php _e('Comments RSS feed', 'sandbox'); ?>" rel="alternate" type="application/rss+xml"><?php _e('All comments', 'sandbox') ?></a></li>
			</ul>
		<?php echo $after_widget; ?>
<?php
}

// Widget: RSS links; element controls for customizing text within Widget plugin
function widget_sandbox_rsslinks_control() {
	$options = $newoptions = get_option('widget_sandbox_rsslinks');
	if ( $_POST["rsslinks-submit"] ) {
		$newoptions['title'] = strip_tags(stripslashes($_POST["rsslinks-title"]));
	}
	if ( $options != $newoptions ) {
		$options = $newoptions;
		update_option('widget_sandbox_rsslinks', $options);
	}
	$title = htmlspecialchars($options['title'], ENT_QUOTES);
?>
			<p><label for="rsslinks-title"><?php _e('Title:'); ?> <input style="width: 250px;" id="rsslinks-title" name="rsslinks-title" type="text" value="<?php echo $title; ?>" /></label></p>
			<input type="hidden" id="rsslinks-submit" name="rsslinks-submit" value="1" />
<?php
}

// Widgets plugin: intializes the plugin after the widgets above have passed snuff
function vanilla_widgets_init() {
	if ( !function_exists('register_sidebars') )
		return;
		
	$widget_blocks = array('Header Nav','Header','Main Menu','Sub Menu','Breadcrumbs','Content Top','Content Middle','Content Bottom','Primary Sidebar','Secondary Sidebar','Utility 1','Utility 2','Utility 3','Utility 4','Footer Nav','Footer','Front Page 1', 'Front Page 2');
	
	foreach ($widget_blocks as $block) {
		register_sidebar( array(
			'name' => $block,
			'id' => sanitize_title_with_dashes(strtolower($block)),
			'before_widget' => '<div id="%1$s" class="widget %2$s">'."\n",
			'after_widget' => "</div>\n",
			'before_title' => '<h3 class="widgettitle">'."\n",
			'after_title' => "</h3>\n"
		));
	};
	
	// Load widgets from the /widgets directory.
	vanilla_load_widgets();

	// Finished intializing Widgets plugin, now let's load the Sandbox default widgets; first, Sandbox search widget
	$widget_ops = array(
		'classname'    =>  'widget_search',
		'description'  =>  __( "A search form for your blog (Sandbox)", "sandbox" )
	);
	wp_register_sidebar_widget( 'search', __( 'Search', 'sandbox' ), 'widget_sandbox_search', $widget_ops );
	unregister_widget_control('search');
	wp_register_widget_control( 'search', __( 'Search', 'sandbox' ), 'widget_sandbox_search_control' );

	// Sandbox Meta widget
	$widget_ops = array(
		'classname'    =>  'widget_meta',
		'description'  =>  __( "Log in/out and administration links (Sandbox)", "sandbox" )
	);
	wp_register_sidebar_widget( 'meta', __( 'Meta', 'sandbox' ), 'widget_sandbox_meta', $widget_ops );
	unregister_widget_control('meta');
	wp_register_widget_control( 'meta', __('Meta'), 'wp_widget_meta_control' );
	
	// Navigation Top widget
	$widget_ops = array(
		'classname'    =>  'widget_navigation_top',
		'description'  =>  __( "Top Prev/Next paging links (Vanilla)", "sandbox" )
	);
	wp_register_sidebar_widget( 'navigation_top', __( 'Top Prev/Next', 'sandbox' ), 'widget_navigation_top', $widget_ops );
	wp_register_widget_control( 'navigation_top', __( 'Top Prev/Next', 'sandbox' ), 'widget_navigation_top_control' );
	
	// Navigation Bottom widget
	$widget_ops = array(
		'classname'    =>  'widget_navigation_bottom',
		'description'  =>  __( "Bottom Prev/Next paging links (Vanilla)", "sandbox" )
	);
	wp_register_sidebar_widget( 'navigation_bottom', __( 'Bottom Prev/Next', 'sandbox' ), 'widget_navigation_bottom', $widget_ops );
	wp_register_widget_control( 'navigation_bottom', __( 'Bottom Prev/Next', 'sandbox' ), 'widget_navigation_bottom_control' );

	//Sandbox RSS Links widget
	$widget_ops = array(
		'classname'    =>  'widget_rss_links',
		'description'  =>  __( "RSS links for both posts and comments <small>(Sandbox)</small>", "sandbox" )
	);
	wp_register_sidebar_widget( 'rss_links', __( 'RSS Links', 'sandbox' ), 'widget_sandbox_rsslinks', $widget_ops );
	wp_register_widget_control( 'rss_links', __( 'RSS Links', 'sandbox' ), 'widget_sandbox_rsslinks_control' );
	
	//Global Nav widget
	$widget_ops = array(
		'classname'    =>  'widget_globalnav',
		'description'  =>  __( "Global Navigation Menu <small>(Vanilla)</small>", "sandbox" )
	);
	wp_register_sidebar_widget( 'globalnav', __( 'Global Navigation', 'sandbox' ), 'widget_globalnav', $widget_ops );
	wp_register_widget_control( 'globalnav', __( 'Global Navigation', 'sandbox' ), 'widget_globalnav_control' );
}


// Runs our code at the end to check that everything needed has loaded
add_action( 'init', 'vanilla_widgets_init' );

// Adds filters so that things run smoothly
add_filter( 'archive_meta', 'wptexturize' );
add_filter( 'archive_meta', 'convert_smilies' );
add_filter( 'archive_meta', 'convert_chars' );
add_filter( 'archive_meta', 'wpautop' );

?>