<?php 
get_header();
$arrCart = Products_Controller::getCartFromSession();   
?>

<div class="page-container">
    <?php get_template_part('nextpage-templates/entry-header');?> 
    <?php get_template_part('nextpage-templates/content');?> 
    <?php if (Products_Controller::getCartTotalPoints()>0 AND Products_Controller::getCartTotalProducts()>0){?>
    <div class="checkout-user-details">
        <div class="spinner-container">
            <div class="account-spinner"><i class="fa-spin fa-3x fa fa-cog" aria-hidden="true"></i></div>
        </div>
        <div class="account-info-main">
            <h2>User Details</h2>
            <div class="account-info-container">
                <div class="account-info-container-inner">
                    <p>First Name: <span id="account_first_name"></span></p>
                    <p>Last Name: <span id="account_last_name"></span></p>
                    <p>User ID: <span id="account_response_id"></span></p>
                    <p>Password: <span id="account_password"></span></p>
                    <p>Email: <span id="account_email"></span></p>
                    <p>Phone Number: <span id="account_phone_number"></span></p>
                    <p>User Points: <span id="account_user_points"></span></p>
                </div>
                <div class="account-info-container-inner">
                    <p>Country: <span id="account_country"></span></p>
                    <p>State: <span id="account_state"></span></p>
                    <p>City: <span id="account_city"></span></p>
                    <p>Town: <span id="account_town"></span></p>
                    <p>PinCode: <span id="account_pinCode"></span></p>
                    <p>Address1: <span id="account_address1"></span></p>
                    <p>Address2: <span id="account_address2"></span></p>
                </div>
            </div>
        </div>
    </div>
    <p class="after-ajax-call-message"></p>
    <div class="checkout-bottom-part-animation">
    <div align="center" class="fond">
  <div class="contener_general">
      <div class="contener_mixte"><div class="ballcolor ball_1">&nbsp;</div></div>
      <div class="contener_mixte"><div class="ballcolor ball_2">&nbsp;</div></div>
      <div class="contener_mixte"><div class="ballcolor ball_3">&nbsp;</div></div>
      <div class="contener_mixte"><div class="ballcolor ball_4">&nbsp;</div></div>
  </div>
</div>
    </div>  
    <div class="cart-bottom-part">
        <div class="cart-details">
        <h2>Cart Summary</h2>
            <?php 
            if(!empty($arrCart)){
                foreach($arrCart as $key => $strValue){
                    ?>
                        <p><a href="<?php echo get_permalink($key);?>"><?php echo get_the_title($key);?></a> x <?php echo $strValue;?> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<strong><?php echo $strValue * Products_Controller::getPoints($key)?></strong> Points</p>
                <?php }
            }
            ?>
        </div>
        <div class="cart-summary">
            Total Products: <?php echo Products_Controller::getCartTotalProducts(); ?>
            Total Points: <?php echo Products_Controller::getCartTotalPoints();?>
        </div>
    </div>
    <div class="cart-checkout-container">
        <button class="checkout-and-reedem">Reedem and Checkout</button>
    </div>
    <?php } else{
       echo Theme_Controller::getShakeError('Your cart is empty. Please go back to products page to add some items in the cart.');
    }
    ?>
</div>
<?php get_footer(); ?>
<script>
 showAccountInfo: function (responseData) {
        $(".spinner-container").show();
        if ($("#account_first_name").length) {
          $("#account_first_name").html(responseData.firstName);
        }
        if ($("#account_last_name").length) {
          $("#account_last_name").html(responseData.lastName);
        }
        if ($("#account_response_id").length) {
          $("#account_response_id").html(responseData.id);
        }
        if ($("#account_password").length) {
          $("#account_password").html(responseData.password);
        }
        if ($("#account_email").length) {
          $("#account_email").html(responseData.email);
        }
        if ($("#account_phone_number").length) {
          $("#account_phone_number").html(responseData.pointBalance);
        }
        if ($("#account_user_points").length) {
          $("#account_user_points").html(responseData.pointBalance);
        }
        if ($("#account_country").length) {
          $("#account_country").html(responseData.country);
        }
        if ($("#account_state").length) {
          $("#account_state").html(responseData.state);
        }
        if ($("#account_city").length) {
          $("#account_city").html(responseData.city);
        }
        if ($("#account_town").length) {
          $("#account_town").html(responseData.town);
        }
        if ($("#account_pinCode").length) {
          $("#account_pinCode").html(responseData.pinCode);
        }
        if ($("#account_address1").length) {
          $("#account_address1").html(responseData.address1);
        }
        if ($("#account_address2").length) {
          $("#account_address2").html(responseData.address2);
        }
        if (this.elementShowUserInfo.length) {
          $(".account-info-container-inner").show();
          $(".account-info-container").css("display", "flex");
          $(".account-info-main").show();
        }
        $(".spinner-container").hide();
      } 
</script>