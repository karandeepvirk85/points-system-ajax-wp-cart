<?php
/**
 * The template for displaying all single posts
 *
 */

get_header();
?>
<div class="container page-container"> 
<?php 
/* Start the Loop */
while ( have_posts() ) :
	the_post();
	if($post->post_type == 'post'){?>
		<div class="row single-posts-row">
			<div class="col-md-9">
				<?php get_template_part('nextpage-templates/singlepost'); ?>	
			</div>
			<div class="col-md-3 sidbar-container">
				<?php get_template_part('nextpage-templates/nextpagesidebar'); ?>
			</div>
		</div>
	<?php } else{
		get_template_part( 'template-parts/content/content-single' );
	}
		
	if ( comments_open() || get_comments_number() ) {
		comments_template();
	}

	// the_post_navigation(
	// 	array(
	// 		'next_text' => '<p class="meta-nav">Next Post</p>',
	// 		'prev_text' => '<p class="meta-nav">Previous Post </p>',
	// 	)
	// );
	
endwhile; // End of the loop.
?>
</div>
<?php get_footer();?>
