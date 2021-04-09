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
<div class="page-container container">
    <header class="page-header alignwide">
        <?php the_archive_title( '<h1 class="page-title">', '</h1>' ); ?>
    </header>

    <div class="row">
        <div class="col-md-9">
            <div class="row posts-row">
                <?php if (have_posts()){
                    $intCount = 0;
                    while (have_posts() ) : the_post();
                        $intCount++;
                        get_template_part( 'nextpage-templates/allposts' );
                        echo ($intCount % 2 == 0) ? '</div><div class="row posts-row">' : "";
                     endwhile;
                     wp_reset_postdata(); 
                 } else { ?>
                    <div class="col-md-12">
                        <p><?php _e( 'There no posts to display.' ); ?></p>
                    </div>
                <?php } ?>
            </div>
        </div>
        
        <!-------INLUDE SIDE BAR------->
        <div class="col-md-3 sidbar-container">    
            <?php  get_template_part('nextpage-templates/nextpagesidebar');?>
        </div>    
    </div>

    <!-------INLUDE PAGINATION------->
    <div class="row">    
        <div class="col-md-12 pagination-container">
            <?php get_template_part( 'nextpage-templates/nextpage','custom_pagination',$args); ?>
        </div>
    </div>

</div>
<?php get_footer(); ?>
