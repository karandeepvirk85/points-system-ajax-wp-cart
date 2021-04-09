<?php
// SEE THE README FOR DOCUMENTATION
// Initialize the class objects, and add functionality

Class Order_Controller {

    public static $objPostType;
    public static $strPostType = 'shop_order';
    public static $strPostTypePlural = 'shop_orders';
    public static $arrPostTypeBaseLabels = array(
        'orders', // lowercase
        'Orders', // singular
        'Orders'  // plural
    );

    public function __construct() {
        static::createPostType();
        static::createMetaBoxes();
        add_filter('manage_shop_order_posts_columns', array(__CLASS__,'orderColumns'));
        add_action('manage_shop_order_posts_custom_column' ,array(__CLASS__,'orderColumnsContent'), 10, 2);
        add_action('wp_ajax_check_out_user', array(__CLASS__, 'checkOutUser'));
	    add_action('wp_ajax_nopriv_check_out_user', array(__CLASS__, 'checkOutUser'));	
    }

    /**
     * Registers the post type
     *
     */
    public static function createPostType() {
        // Arguments for the post type
        $arrPostTypeArgs = array(
            'public' => true,
            'has_archive' => true,
            'menu_position' => 34,
            'menu_icon' => 'dashicons-cart',
            'supports' => array(
                'title',
            ),
        );

        // Get the base labels
        $arrBaseLabels = static::$arrPostTypeBaseLabels;

        // Create the wordpress labels
        $arrPostLabels = array(
            'name'                  => sprintf( _x( '%s', 'taxonomy general name', 'cuztom' ), $arrBaseLabels[2] ),
            'singular_name'         => sprintf( _x( '%s', 'taxonomy singular name', 'cuztom' ), $arrBaseLabels[1] ),
            'search_items'          => sprintf( __( 'Search %s', 'cuztom' ), $arrBaseLabels[2] ),
            'all_items'             => sprintf( __( 'All %s', 'cuztom' ), $arrBaseLabels[2] ),
            'parent_item'           => sprintf( __( 'Parent %s', 'cuztom' ), $arrBaseLabels[1] ),
            'parent_item_colon'     => sprintf( __( 'Parent %s:', 'cuztom' ), $arrBaseLabels[1] ),
            'edit_item'             => sprintf( __( 'Edit %s', 'cuztom' ), $arrBaseLabels[1] ),
            'update_item'           => sprintf( __( 'Update %s', 'cuztom' ), $arrBaseLabels[1] ),
            'add_new_item'          => sprintf( __( 'Add New %s', 'cuztom' ), $arrBaseLabels[1] ),
            'new_item_name'         => sprintf( __( 'New %s Name', 'cuztom' ), $arrBaseLabels[1] ),
            'menu_name'             => sprintf( __( '%s', 'cuztom' ), $arrBaseLabels[2] )
        );

        // Post type object is created here
        static::$objPostType = new Cuztom_Post_Type(static::$strPostType, $arrPostTypeArgs, $arrPostLabels);
    }

    /**
     * Setup the Metaboxes
     *
     */
    public static function createMetaBoxes() {
        
        $arrMetaOrder = array(
            array(
                'name' =>'car_info',
                'label'=>'Cart Info',
                'description' => 'Cart Information',
                'type' => 'readonly',
                'options' => array(
                    'type' => 'raw_output',
                    'output' => ''.self::getCartInformation().'',
                )
            ),
            array(
                'name' =>'cart_order_products',
                'label'=>'Total Products',
                'description' => 'Total Number of Products',
                'type' => 'readonly',
                'options' => array(
                    'type' => 'raw_output',
                    'output' => ''.self::getOrderProducts().'',
                )
            ),
            array(
                'name' =>'cart_order_points',
                'label'=>'Total Points',
                'description' => 'Total Number of Points',
                'type' => 'readonly',
                'options' => array(
                    'type' => 'raw_output',
                    'output' => ''.self::getOrderPoints().'',
                )
            ),
            array(
                'name' =>'cart_order_name',
                'label'=>'Order By',
                'description' => 'Name of the user',
                'type' => 'readonly',
                'options' => array(
                    'type' => 'raw_output',
                    'output' => ''.self::getOrderUserName().'',
                )
            ),
            array(
                'name' =>'cart_order_email',
                'label'=>'Order Email',
                'description' => 'Email of the user',
                'type' => 'readonly',
                'options' => array(
                    'type' => 'raw_output',
                    'output' => ''.self::getOrderUserEmail().'',
                )
            ),
            array(
                'name' =>'order_status',
                'label'=>'Order Status',
                'description' => 'Order status for admin reference.',
                'type' => 'select',
                'options'=> array(
                    'pending' => 'Pending',
                    'completed' => 'Completed',
                    'failed' => 'Failed',
                )
            ),
        );
        // Create general info metabox
        static::$objPostType->add_meta_box(
            'meta_information',
            'Order Details',
            $arrMetaOrder,
            'normal',
            'default'
        );
    }

    /**
     * Set Order Columns 
     */
    public static function orderColumns($arrColumns){
        unset($arrColumns['date']);
        $arrColumns['total_products'] = __('No. of Products', 'your_text_domain');
        $arrColumns['total_points'] = __('No. of Points', 'your_text_domain');
        $arrColumns['order_name'] = __('Order By', 'your_text_domain');
        $arrColumns['order_status'] = __('Order Status', 'your_text_domain');
        $arrColumns['order_date'] = __('Order Date', 'your_text_domain');
        
        return $arrColumns;
    }

    /**
     * Set Order Columns Content 
     */
    public static function orderColumnsContent($arrColumns, $intPostId){        
        
        if($arrColumns == 'total_products'){
            $intMeta = get_post_meta($intPostId,'_meta_order_total_products',true);
            echo $intMeta;
        }

        if($arrColumns == 'total_points'){
            $intMeta = get_post_meta($intPostId,'_meta_order_total_points',true);
            echo $intMeta;
        }

        if($arrColumns == 'order_date'){
            $intMeta = get_the_time('d F Y',$intPostId);
            echo $intMeta;
        }

        if($arrColumns == 'order_name'){
            $strMeta = get_post_meta($intPostId,'_meta_order_order_name',true);
            echo $strMeta;
        }

        if($arrColumns == 'order_status'){
            $strMeta = get_post_meta($intPostId,'_meta_information_order_status',true);
            echo '<span id="order-'.$strMeta.'">'.$strMeta.'</span>';
        }
    }

    /**
     * Get User email 
     */
    public static function getOrderUserName(){
        if(isset($_GET['post'])){
            $intOrderId = (int) $_GET['post'];
            $strMeta = get_post_meta($intOrderId,'_meta_order_order_name',true);
            return $strMeta;
        }
    }

    /**
     * Get Order Points 
     */
    public static function getOrderPoints(){
        if(isset($_GET['post'])){
            $intOrderId = (int) $_GET['post'];
            $intMeta = (int) get_post_meta($intOrderId,'_meta_order_total_points',true);
            return $intMeta;
        }
    }

    /**
     * Get Order Products 
     */
    public static function getOrderProducts(){
        if(isset($_GET['post'])){
            $intOrderId = (int) $_GET['post'];
            $intMeta = (int) get_post_meta($intOrderId,'_meta_order_total_products',true);
            return $intMeta;
        }
    }

    /**
     * Get User Name 
     */
    public static function getOrderUserEmail(){
        if(isset($_GET['post'])){
            $intOrderId = (int) $_GET['post'];
            $strMeta = get_post_meta($intOrderId,'_meta_order_order_email',true);
            return $strMeta;
        }
    }

    /**
     * Check Out User 
     */
    public static function checkOutUser(){
        
        $arrReturn  = array(
            'order_id' => '',
            'user_name' => '',
            'points' => '',
            'products' => '',
            'email' => '',
            'success' => true,
            'message' => '',
            'updated_points' => '',
            'token' => ''
        );

        $firstName      = isset($_GET['firstName'])     ? $_GET['firstName'] : "";
        $lastName       = isset($_GET['lastName'])      ? $_GET['lastName'] : "";
        $userPoints     = isset($_GET['userPoints'])    ? (int) $_GET['userPoints'] : "";
        $userEmail      = isset($_GET['userEmail'])     ? $_GET['userEmail'] : "";
        $strToken       = isset($_GET['strToken'])      ? $_GET['strToken'] : "";
        $strFullName    = $firstName.' '.$lastName;
        $arrCart                = Products_Controller::getCartFromSession();
        $intTotalPoints         = (int) Products_Controller::getCartTotalPoints();
        $intTotalProducts       = (int) Products_Controller::getCartTotalProducts();
        // We dont have user email or first name throw not logged in Error
        if(empty($userEmail) || empty($strFullName) || empty($strToken)){
            $arrReturn['success'] = false;
            $arrReturn['message'] = Theme_Controller::getShakeError('Sorry! You are not logged In');
        } 
        else{
            // 
            // If we have email and Name and Token but Products or Points are less than zero or equal to zero throw error
            if($intTotalPoints<=0 || $intTotalProducts<=0){
                $arrReturn['success'] = false;
                $arrReturn['message'] = Theme_Controller::getShakeError('Sorry! Your cart is empty. You cannot checkout without having products in your cart.');
            }else{
                // if user has less points throw error  
                if($userPoints<$intTotalPoints){
                    $arrReturn['success'] = false;
                    $arrReturn['message'] = Theme_Controller::getShakeError('Sorry! You do not have enough points to complete this order');
                }else{
                    // Insert Order Post if everything is good
                    $arrOrder = array(
                        'post_type' => 'shop_order',
                        'post_status' => 'publish',
                        'post_content'=> 'Wordpress Order'
                    );
                    $intOrderId = wp_insert_post($arrOrder);

                    // Update Order Post
                    if($intOrderId>0){
                        wp_update_post(
                            array(
                                'post_title' => 'Order# '.$intOrderId,
                                'ID' => $intOrderId
                            )
                        );
                
                        // Update Order Meta
                        update_post_meta($intOrderId,'_meta_order_information', $arrCart);
                        update_post_meta($intOrderId,'_meta_order_total_points', $intTotalPoints);
                        update_post_meta($intOrderId,'_meta_order_total_products', $intTotalProducts);
                        update_post_meta($intOrderId,'_meta_information_order_status','pending');
                        update_post_meta($intOrderId,'_meta_order_order_email',$userEmail);
                        update_post_meta($intOrderId,'_meta_order_order_name', $firstName.' '.$lastName);
                        $intUpdatedPoints = $userPoints-$intTotalPoints;
                        self::updateInventory();
                        self::sendEmailToAdmin($intOrderId);
                        self::sendEMailToUser($intOrderId, $userEmail);
                        $strMessage = Theme_Controller::getShakeSuccess('Thank You <strong>'.$strFullName.'</strong> for shopping with us. An email has been sent to <strong>'.$userEmail.'</strong> with all the order details. Your order ID is <strong>#'.$intOrderId.'</strong>');
                        $arrReturn = array(
                            'order_id' => $intOrderId,
                            'user_name' => $strFullName,
                            'points' => $intTotalPoints,
                            'products' => $intTotalProducts,
                            'email' => $userEmail,
                            'success' => true,
                            'updated_points' => $intUpdatedPoints,
                            'message'=> $strMessage,
                            'token' => $strToken
                        );
                        self::destroyCart();
                    }
                }
            }                
        }
        echo json_encode($arrReturn);
        die;
    }

    /**
     * Update Inventory After Order 
     */
    public static function updateInventory(){
        $arrCart = Products_Controller::getCartFromSession();
        if(!empty($arrCart)){
            foreach($arrCart as $key => $strValue){
                $intAvailableProducts   = (int) Products_Controller::getAvailableProducts($key);
                $intUpdatedProducts     = $intAvailableProducts - $strValue;
                update_post_meta($key ,'_meta_information_points_qty', (int) $intUpdatedProducts);
            }
        }
    }

    /**
     * This function empty the user cart after saving order post type 
     */
    public static function destroyCart(){
        $_SESSION['cart'] = array();
    }

    /**
     * Send Email to Admin 
     */
    public static function sendEmailToAdmin($intOrderId){
        $strNotificationEmail = trim(get_option('admin_notification_email'));        
        $strSubject = "A new order have been placed with order ID ".$intOrderId;
        $strMessage = "";
        $strMessage .= "<h2>Order Details</h2>";
        $strMessage .= self::getCartInformation();
        $headers    = "MIME-Version: 1.0" . "\r\n";
        $headers    .= "Content-type:text/html;charset=UTF-8" . "\r\n";
        $headers    .= 'From: <notify@notification.com>' . "\r\n";
        mail($strNotificationEmail, $strSubject, $strMessage, $headers);
    }
    
    /**
     * Send Email to User 
     */
    public static function sendEmailToUser($intOrderId, $strEmail){
        $strEmailContent = get_option('customer_email_message');
        $strEmail = trim(get_option('admin_notification_email'));        
        $strSubject = "Thank for shopping with us ".$intOrderId;
        $strMessage = "";
        $strMessage .= $strEmailContent;
        $strMessage .= "<h2>Order Details</h2>";
        $strMessage .= self::getCartInformation();
        $headers    = "MIME-Version: 1.0" . "\r\n";
        $headers    .= "Content-type:text/html;charset=UTF-8" . "\r\n";
        $headers    .= 'From: <no-reply@notification.com>' . "\r\n";
        mail($strEmail, $strSubject, $strMessage, $headers);
    }

    /**
     * Get Cart Information
     */
    public static function getCartInformation(){
        $strHtml = '';

        if(isset($_GET['post'])){
            $intOrderId = (int)$_GET['post'];
            $arrCart = get_post_meta($intOrderId,'_meta_order_information',true);
        }

        $strHtml .= '<table id="cart-table" class="table table-stripped">';
        $strHtml .= '<thead>';
        $strHtml .= '<tr>';
        $strHtml .= '<th>Image</th>';
        $strHtml .= '<th>Product</th>';
        $strHtml .= '<th>Quantity</th>';
        $strHtml .= '<th>Points</th>';
        $strHtml .= '</tr>';
        $strHtml .= '</thead>';
        $strHtml .= '<tbody>'; 
        if(!empty($arrCart)){
            foreach($arrCart as $key => $strValue){
                $strCartString = '';
                $strCartString .= '<tr>';
                $strCartString .= '<td><img class="order-image" src="'.getPostImage($key,'thumbnail').'"></td>';
                $strCartString .= '<td><a href="'.admin_url($key).'post.php?post='.$key.'&action=edit">'.get_the_title($key).'</a></td>';
                $strCartString .= '<td>'.$strValue.'</td>';
                $strCartString .= '<td>'.$strValue * get_post_meta($key,'_meta_information_points_price',true).'</td>';
                $strCartString .= '</tr>';
                $strHtml .= $strCartString;
            }
        }
        $strHtml .= '</tbody>';
        $strHtml .=  '</table>';
        return $strHtml;
    }
}

$objMemberController = new Order_Controller();
?>