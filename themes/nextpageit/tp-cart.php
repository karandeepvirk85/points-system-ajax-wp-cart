<?php 
/**
 * Template Name: Cart
 */
get_header();
    $intPointsCount         = Products_Controller::getCartTotalPoints();
    $intProductsCount       = Products_Controller::getCartTotalProducts();
    $args = array(
        'points-count' => $intPointsCount,
        'products-count' => $intProductsCount
    );
?>

<div class="page-container">
    <?php get_template_part('nextpage-templates/entry-header');?> 
    <?php get_template_part('nextpage-templates/content-page');?> 
    
    <!--Cart Back Buton To Products-->
    <div class="back-to-products">
        <a href="<?php echo home_url().'/shop';?>">
        <i class="fa fa-chevron-left" aria-hidden="true"></i> Back to Products </i>
        </a>
    </div>

    <?php 
        if($intProductsCount > 0){?>
            <!--Get Ajax Response-->
            <?php get_template_part('nextpage-templates/after-ajax-message');?>
            <!--Get Cart Spinner-->
            <?php get_template_part('nextpage-templates/checkout-spinner');?>
            <!--Get Cart Table-->
            <div class="cart-and-meta-container">
                <?php get_template_part('nextpage-templates/cart');?>
                <!--Cart Meta Information-->
                <?php get_template_part( 'nextpage-templates/cart','meta_information',$args);?>
                <!--Cart Proceed To checkout-->
                <div class="cart-checkout-container">
                    <button class="checkout-and-reedem">Reedem and Checkout <i class="fa fa-chevron-right" aria-hidden="true"></i></button>
                </div>
            </div>
        <?php } else{
            echo Theme_Controller::getShakeError(Theme_Controller::$constantCartEmpty);    
        }?>
</div>
<?php get_footer();?>