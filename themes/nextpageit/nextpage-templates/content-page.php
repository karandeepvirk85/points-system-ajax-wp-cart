<?php
/**
 * Page Content
 */
?>
<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<?php if (!is_front_page()){?>
		<header class="entry-header alignwide">
			<?php get_template_part( 'template-parts/header/entry-header' ); ?>
		</header>
	<?php }?>

	<div class="entry-content">
		<?php
		the_content();
		wp_link_pages(
			array(
				'before'   => '<nav class="page-links" aria-label="' . esc_attr__( 'Page', 'twentytwentyone' ) . '">',
				'after'    => '</nav>',
				'pagelink' => esc_html__( 'Page %', 'twentytwentyone' ),
			)
		);
		?>
	</div>
</article>