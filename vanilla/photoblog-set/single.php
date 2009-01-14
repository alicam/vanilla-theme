<?php get_header(); ?>


<?php if (have_posts()) : while (have_posts()) : the_post(); 

// loop start

?>

	<div class="image">
		<div class="nav prev"><?php next_post_link('%link') ?></div>
		<?php the_image(); ?>
		<div class="nav next"><?php previous_post_link('%link') ?></div>
	</div>
	
	<?php 
	
	// content start
	
	include 'post.php'; 
	
	// content end
	
	?>
	
<?php 

// loop end

endwhile; else :

// 404 start

 ?>
	<h2 class="center">Not Found</h2>
	<p class="center">Sorry, but you are looking for something that isn't here.</p>
	<?php include (TEMPLATEPATH . "/searchform.php"); ?>
<?php 

// 404 end

endif; ?>
<?php get_footer(); ?>
