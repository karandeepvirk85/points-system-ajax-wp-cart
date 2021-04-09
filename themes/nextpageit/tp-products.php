<?php 
/**
 * Template Name: Products
 * 
 */

get_header();
if(class_exists('Theme_Controller')){
    // Get Paged Query
    $paged = Theme_Controller::getPagedQuery();
    // Get Posts Query
    $arrProducts = Theme_Controller::getAllPosts($paged,'shop_product',12);
    // Set Args for Pagination
    $args = Theme_Controller::getArgsForPagination($paged, $arrProducts->max_num_pages);
}
?>

<div class="page-container">
    <?php get_template_part('nextpage-templates/entry-header');?> 
    <?php get_template_part('nextpage-templates/content-page');?> 
    <div class="sort-products">
        <form method="get" id="sort-products">
            <select name="sort_products_by">
                <option selected value="">Default Sorting</option>
                <option <?php if($_GET['sort_products_by'] == 'points') {echo 'selected';}?> value = 'points'>Sort By Points</option>
                <option <?php if($_GET['sort_products_by'] == 'name') {echo 'selected';}?> value = 'name'>Sort By Name</option>
                <option <?php if($_GET['sort_products_by'] == 'available') {echo 'selected';}?> value = 'available'>Sort By Availability</option>
            </select>
        </form>
    </div>
    <div class="products-row">
        <?php if ($arrProducts->have_posts()){
            while ($arrProducts->have_posts() ) : $arrProducts->the_post();
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

<?php get_footer();?>