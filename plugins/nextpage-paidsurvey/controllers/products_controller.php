<?php
// SEE THE README FOR DOCUMENTATION
// Initialize the class objects, and add functionality

Class Products_Controller{

    public static $objPostType;
    public static $strPostType = 'shop_product';
    public static $strPostTypePlural = 'shop_products';
    public static $arrPostTypeBaseLabels = array(
        'product', // lowercase
        'Product', // singular
        'Products' // plural
    );

    // Register Taxonomies
    public static $arrEventTaxonomies = array(
        'product_category'=>'Product Category',
    );

    public function __construct() {
        static::createPostType();
        static::createMetaBoxes();
        static::createTaxonomies();
        add_action('wp_ajax_add-product-to-cart', array(__CLASS__, 'AddToCart'));
        add_action('wp_ajax_nopriv_add-product-to-cart', array(__CLASS__, 'AddToCart'));
        add_action('wp_ajax_remove_item_from_cart', array(__CLASS__,'removeItemFromCart'));
        add_action('wp_ajax_nopriv_remove_item_from_cart', array(__CLASS__,'removeItemFromCart'));	
        add_filter('manage_shop_product_posts_columns', array(__CLASS__,'productColumns'));
        add_action('manage_shop_product_posts_custom_column' ,array(__CLASS__,'productColumnsContent'), 10, 2);
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
            'menu_icon' => 'dashicons-image-filter',
            'supports' => array(
                'thumbnail',
                'title',
                'editor'
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
        
        $arrMetaMembers = array(
            array(
                'name' => 'points_price',
                'label' => 'Number of Points',
                'description' => 'Price of product in points',
                'type' => 'number',
            ),
            array(
                'name' => 'points_qty',
                'label' => 'Quantity',
                'description' => 'Quantity of products',
                'type' => 'number',
            ),
            array(
                'name' => 'short_description',
                'label' => 'Short Description',
                'description' => 'Short Description of the product',
                'type' => 'textarea',
            ),
        );
        // Create general info metabox
        static::$objPostType->add_meta_box(
            'meta_information',
            'Products Details',
            $arrMetaMembers,
            'normal',
            'default'
        );
    }

     /**
    *Create the taxonomies
    *
    */
    public static function createTaxonomies(){

        $arrTaxonomies = static::$arrEventTaxonomies;
        $strTaxonomy = '';
        foreach ($arrTaxonomies as $strTaxonomy => $objTaxonomy) {
            // Arguments for the taxonomy
            $arrTaxArgs = array(
                'public' => true,
                'show_ui' => true,
                'show_admin_column' => true,
                'hierarchical' => true,
                'query_var' => true
            );
            // Create the base labels
            $arrBaseLabels = array(
                $objTaxonomy,   // lowercase
                $objTaxonomy,   // singular
                $objTaxonomy // Plural
            );
            $arrTaxLabels = array(
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
            // Add the tax to the post type
            $objTaxonomyCategory = register_cuztom_taxonomy($strTaxonomy, static::$strPostType, $arrTaxArgs, $arrTaxLabels);
        }
    }

    /**
     * Function to return product points from database
     * @param Post ID
     */
    public static function getPoints($intPostId){
        $intReturn = 0;
        if(empty($intPostId)){
            return;
        }
        $intReturn = get_post_meta($intPostId,'_meta_information_points_price',true);
        return $intReturn;
    }

    /**
     * Set Product Columns 
     */
    public static function productColumns($arrColumns){
        unset($arrColumns['date']);
        $arrColumns['number_of_points'] = __('No. of Points', 'your_text_domain');
        $arrColumns['quantity'] = __('Quantity', 'your_text_domain');
        $arrColumns['order_date'] = __('Published Date', 'your_text_domain');
        
        return $arrColumns;
    }

    /**
     * Set Order Columns Content 
     */
    public static function productColumnsContent($arrColumns, $intPostId){        
        
        if($arrColumns == 'number_of_points'){
            $intMeta = get_post_meta($intPostId,'_meta_information_points_price',true);
            echo $intMeta;
        }

        if($arrColumns == 'quantity'){
            $intMeta = get_post_meta($intPostId,'_meta_information_points_qty',true);
            echo $intMeta;
        }

        if($arrColumns == 'order_date'){
            $intMeta = get_the_time('d F Y (H:i A)',$intPostId);
            echo $intMeta;
        }
    }


    /**
     * Function to return available qty from database
     * @param Post ID
     */
    public static function getAvailableProducts($intPostId){
        $intReturn = 0;
        if(empty($intPostId)){
            return;
        }
        $intReturn = (int) get_post_meta($intPostId,'_meta_information_points_qty',true);
        return $intReturn;
    }

    /**
     * Function to return short description
     * @param Post ID
     */
    public static function getShortDescription($intPostId){
        $strReturn = '';
        if(empty($intPostId)){
            return;
        }
        $strReturn = (string) trim(get_post_meta($intPostId,'_meta_information_short_description',true));
        return $strReturn;
    }

    /**
     * Function get parameters from AJAX
     * @param Post ID and Quantity
     */
    public static function AddToCart(){
        $arrReturn = array(
            'error' => false,
            'errors_messages' => array(),
            'error_string' => '',
            'success_message' => '',
            'product_id' => '',
            'quantity' => ''
        );

        $strHtmlErrors = '';

        if(isset($_GET['post_id']) AND isset($_GET['quantity'])) {
            $intProductId   = (int) $_GET['post_id'];
            $intQuantity    = (int) $_GET['quantity'];
            $intAvailableProducts = (int) self::getAvailableProducts($intProductId);
            if($intProductId<=0){
                $arrReturn['error'] = true;
                $arrReturn['errors_messages'][] = Theme_Controller::getShakeError('Post id is missing');
            }
            if($intQuantity<=0){
                $arrReturn['error'] = true;
                $arrReturn['errors_messages'][] = Theme_Controller::getShakeError('Quantity must be greater then zero');
            }
            // $intProductsAlreadyInCart   = (int) self::getProductQuantityFromSession($intProductId); 
            // $intNewQuantity             = (int) $intProductsAlreadyInCart+$intQuantity;
            if($intQuantity > $intAvailableProducts){
                $arrReturn['error'] = true;
                $arrReturn['errors_messages'][] = Theme_Controller::getShakeError('Sorry! Only '.$intAvailableProducts.'<strong> '.get_the_title($intProductId).'</strong>  are available to buy.');
            }
            if($arrReturn['error'] === false){
                if($intProductId>0 AND $intQuantity>0){
                    $intUserId = get_current_user_id();
                    $arrCallBack = self::addProductToCart($intProductId,$intQuantity);
                    $arrReturn['product_id'] = $arrCallBack['product_id']; 
                    $arrReturn['quantity'] =  $arrCallBack['quantity'];
                    $arrReturn['success_message'] = $arrCallBack['success_message'];
                    $arrReturn['product_points'] = $arrCallBack['product_points'];
                    $arrReturn['total_products'] = $arrCallBack['total_products'];
                    $arrReturn['total_points'] = $arrCallBack['total_points'];
                }
            }
            if(!empty($arrReturn['errors_messages'])){
                $strHtmlErrors .= implode('<br>',$arrReturn['errors_messages']);
                $arrReturn['error_string'] = $strHtmlErrors;
            }
        }
        echo json_encode($arrReturn);
        die;
    }

    /**
     * 
     * @param Product ID, Quantity
     * This Function fills session
     */
    public static function addProductToCart($intProductId, $intQuantity){
        $arrReturn = array(
            'product_id' => '',
            'quantity' => '',
            'success_message'
        );

        if((array_key_exists($intProductId,$_SESSION['cart']['products']))) {
            // $intOldQuantity = $_SESSION['cart']['products'][$intProductId];
            // $intTotalQuantity = $intOldQuantity+$intQuantity;
            $_SESSION['cart']['products'][$intProductId] = $intQuantity;
            $arrReturn['success_message'] = Theme_Controller::getShakeSuccess(' Your cart has been <strong> Updated </strong>. Now you have '.$intQuantity.' total items in your cart.');
        }else{
            $_SESSION['cart']['products'][$intProductId] = $intQuantity;
            $arrReturn['success_message'] =  Theme_Controller::getShakeSuccess($intQuantity.' item has been added to the cart.');
        }

        $arrReturn['product_id'] = $intProductId;
        $arrReturn['quantity'] = $intQuantity;
        $arrReturn['product_points'] = $intQuantity * self::getPoints($intProductId);
        $arrReturn['total_products'] = self::getCartTotalProducts();
        $arrReturn['total_points'] = self::getCartTotalPoints();
        return $arrReturn;
    }
    /**
     * Get Cart From Session
     */
    public static function getCartFromSession(){
        if(!empty($_SESSION['cart'])){
            return $_SESSION['cart']['products'];
        }
    }

    /**
     * Function to get total products in the session by product id 
     */

    public static function getProductQuantityFromSession($intProductId){
        $intReturn = 0;
        $arrCart = self::getCartFromSession();
        if(!empty($arrCart)){
            $intReturn = $_SESSION['cart']['products'][$intProductId];
        }
        return $intReturn;
    }
    /**
     * Get Cart Total
     */
    public static function getCartTotalPoints(){
        $intReturn = 0;
        $arrCart = self::getCartFromSession();
        if(!empty($arrCart)){
            foreach($arrCart as $key => $strValue){
                $intReturn += $strValue*self::getPoints($key);
            }
        }
        return $intReturn;
    }

    /**
     * Get Cart Total Products
     */
    public static function getCartTotalProducts(){
        $intReturn = 0;
        $arrCart = self::getCartFromSession();
        if(!empty($arrCart)){
            foreach($arrCart as $key => $strValue){
                $intReturn += $strValue;
            }
        }
        return $intReturn;
    }

    /**
     * Remove Item From Cart
     */
    public static function removeItemFromCart(){
        $arrReturn = array(
            'product_id' => '',
            'success' => false,
            'total_products' =>'',
            'total_points' => '',
            'message' => ''
        );

        if (isset($_GET['product_id'])){
            $intProductId = $_GET['product_id'];
            $arrCart = self::getCartFromSession();
            if(!empty($arrCart)){
                foreach($arrCart as $key => $strValue){
                    if($key == $intProductId){
                        unset($_SESSION['cart']['products'][$key]);
                        $arrReturn = array(
                            'product_id' => $intProductId,
                            'success' => true,
                        );
                    }
                }
            }   
        }
        $arrReturn['total_products'] = self::getCartTotalProducts();
        $arrReturn['total_points'] = self::getCartTotalPoints();
        $arrReturn['message'] = Theme_Controller::getShakeNotice('<strong>'.get_the_title($intProductId).'</strong> has been removed from your cart');
        echo json_encode($arrReturn);
        die;
    }
}

$objMemberController = new Products_Controller();