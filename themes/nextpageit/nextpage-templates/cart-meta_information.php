<?php 
/**
 * Cart Meta
 */
?>
<div class="cart-meta-container">
    <div class="cart-meta-info">
        <!--Total Products-->
        <p>
            <strong>Total Products: </strong>
            <span id="cart-meta-products">
                <?php echo $args['products-count'];?>
            </span>
        </p>
        
        <!--Total Points-->        
        <p>
            <strong>Total Points: </strong>
            <span id="cart-meta-points">
                <?php echo $args['points-count'];?>
            </span>
        </p>
    </div>
</div>