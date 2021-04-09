<?php
/**
 * Category Templates
*/
get_header();

$paged = Theme_Controller::getPagedQuery();

$args = array(
	'paged' => $paged,
	'max_num_pages' => $wp_query->max_num_pages
);

?>
<div class="page-container">
    <header class="page-header alignwide">
        <?php the_archive_title( '<h1 class="page-title">', '</h1>' ); ?>
    </header>

    <div class="products-row">
        <?php if (have_posts()){
            while (have_posts() ) : the_post();
                get_template_part('nextpage-templates/products');
            endwhile;
            wp_reset_postdata(); 
        }
        ?>
    </div>

    <div class="row">
        <!-------INLUDE PAGINATION------->
        <div class="col-md-12 pagination-container">
            <?php get_template_part( 'nextpage-templates/nextpage','custom_pagination',$args); ?>
        </div>
    </div>
</div>
<?php get_footer(); ?>
