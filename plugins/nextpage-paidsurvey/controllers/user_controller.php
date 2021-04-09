<?php
// SEE THE README FOR DOCUMENTATION
// Initialize the class objects, and add functionality

Class User_Controller {

    public static $objPostType;
    public static $strPostType = 'shop_user';
    public static $strPostTypePlural = 'shop_users';
    public static $arrPostTypeBaseLabels = array(
        'shop user', // lowercase
        'Shop User', // singular
        'Shop Users' // plural
    );

    public function __construct() {
        // static::createPostType();
        // static::createMetaBoxes();
        // add_action('wp_ajax_set_user_login_session', array(__CLASS__, 'setUserSession'));
        // add_action('wp_ajax_nopriv_set_user_login_session', array(__CLASS__, 'setUserSession'));
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
                'title',
                'thumbnail'
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
        
        $arrUsers = array(
            array(
                'name' => 'email',
                'label' => 'Email',
                'description' => 'User Email',
                'type' => 'text',
            ),
            array(
                'name' => 'password',
                'label' => 'User Password',
                'description' => 'User Password',
                'type' => 'text',
            ),
            array(
                'name' => 'phone',
                'label' => 'Phone',
                'description' => 'User Phone',
                'type' => 'text',
            ),
            array(
                'name' => 'points_balance',
                'label' => 'User Points',
                'description' => 'User Points Last Time API Hit',
                'type' => 'number',
            ),
        );

        // Create general info metabox
        static::$objPostType->add_meta_box(
            'meta_information',
            'User Details',
            $arrUsers,
            'normal',
            'default'
        );
    }
    
    /**
     * Set User In Wp Session 
     */
    public static function setUserSession(){
        // $arrUserData = Login_Controller::getDataFromToken();
        if(!empty($arrUserData)){
            $strFirstName   = (string) $arrUserData['first_name'];
            $strLastName    = (string) $arrUserData['last_name'];
            $strUserEmail   = $arrUserData['email'];
            $strPassword    = (string) $arrUserData['password'];
            $strPhone       = (string) $arrUserData['phone'];
            $intUserPoints  = (int) $arrUserData['points_balance'];
            $intUserId      = (int) $arrUserData['id'];
            $intFieldId     = (int) $arrUserData['file_id'];

            if(self::checkIfUserExists($strUserEmail)){
                $_SESSION['user'] = array(
                    'user_email' => $strUserEmail,
                    'user_points' => $intUserPoints,
                    'first_name' => $intUserPoints,
                );      
            }
            else{
                // Insert User Post
                $arrUser = array(
                    'post_type' => 'shop_user',
                    'post_title' => $strFirstName.' '.$strLastName,
                    'post_status' => 'publish',
                    'post_content'=> 'Wordpress User'
                );

                $intNewUserId = wp_insert_post($arrUser);
                if(!empty($intNewUserId)){
                    update_post_meta($intNewUserId,'_meta_information_email',$strUserEmail);
                    update_post_meta($intNewUserId,'_meta_information_password',$strPassword);
                    update_post_meta($intNewUserId,'_meta_information_phone',$strPhone);
                    update_post_meta($intNewUserId,'_meta_information_points_balance',$intUserPoints);
                    update_post_meta($intNewUserId,'_meta_information_ref_id',$intUserId);
                    update_post_meta($intNewUserId,'_meta_information_field_id',$intFieldId);
                }
            }
        }
        
    }

    /**
     * Check if User Exists
     */
    public static function checkIfUserExists($strUserEmail){
        $bolReturn = true;
        $args = array(
            'post_type' => 'shop_user',
            'meta_query' => array(
                array(
                    'key' => '_meta_information_email',
                    'value' => $strUserEmail,
                    'compare' => '='
                ),
            )
        );
        $arrPosts = get_posts($args);

        if(empty($arrPosts)){
            $bolReturn = false;
        }

        return $bolReturn;
    }
}

$objUserController = new User_Controller();
