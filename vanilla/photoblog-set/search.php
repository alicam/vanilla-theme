<?php get_header(); rewind_posts(); ?>
<div class="search">
		
		<?php if (have_posts()) : ?>
		<h2>Search Results</h2>
				<div class="nav">
					<div class="prev"><?php next_posts_link('&laquo; Older Entries') ?></div>
					<div class="next"><?php previous_posts_link('Newer Entries &raquo;') ?></div>
				</div>
		<ul class="thumbnails">
				<?php while (have_posts()) : the_post();
				
				// loop start
				
				 ?>

							<li id="post-<?php the_ID(); ?>">
								<a href="<?php the_permalink() ?>" title="Link to <?php the_title_attribute(); ?>"><?php the_thumbnail(); ?></a>
							</li>

				<?php 
				
				// loop end
				
				endwhile; ?>
		</ul>
		<div class="nav">
			<div class="prev"><?php next_posts_link('&laquo; Older Entries') ?></div>
			<div class="next"><?php previous_posts_link('Newer Entries &raquo;') ?></div>
		</div>

	<?php else :
	
	// 404 start
	
	 ?>

		<h2>Not Found</h2>
		<?php include (TEMPLATEPATH . '/searchform.php'); ?>

	<?php 
	
	// 404 end
	
	endif; ?>
</div> <!-- .search -->

<?php get_footer(); ?>
