<?php 
/**
 * Custom Search Form
 * 
 */
?>
<form role="search" method="get" action="<?php echo home_url( '/' ); ?>">
    <div>
        <input class="search-box" type="text" value="" name="s" placeholder="Search" />
    </div>
    <div class = "cart-icon-container">
		<a href="<?php echo home_url().'/cart';?>"><img src="<?php echo get_template_directory_uri();?>/images/cart-icon.svg" alt="cart Icon"></a>
	</div>
</form>