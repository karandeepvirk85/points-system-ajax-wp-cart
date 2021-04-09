<?php
/**
 * Template Name: Home
 */
get_header();
if(class_exists('Theme_Controller')){
    // Get Paged Query
    $paged = Theme_Controller::getPagedQuery();
    // Get Posts Query
    $allPostsWPQuery = Theme_Controller::getAllPosts($paged,'post',8);
    // Set Args for Pagination
    $args = Theme_Controller::getArgsForPagination($paged,$allPostsWPQuery->max_num_pages);
}
?>

<div class="page-container">
    <?php get_template_part('nextpage-templates/entry-header');?> 
    <?php get_template_part('nextpage-templates/content-page');?> 
    
    <div class="row">
        <div class="col-md-9">
            <div class="row posts-row">
                <?php if ($allPostsWPQuery->have_posts()){
                    $intCount = 0;
                    while ($allPostsWPQuery->have_posts() ) : $allPostsWPQuery->the_post();
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

<?php get_footer();?>