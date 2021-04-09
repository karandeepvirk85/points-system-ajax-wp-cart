<?php

Class Settings_Controller {

    public static $strSettingId = 'products_settings';
    public static $strSettingSlug = 'products_settings';
    public static $strSettingTitle = 'Shop Settings';
    public static $strSettingCapability = 'manage_options';

    public function __construct() {
        // Add the option to the uninstall list
        global $nm_uninstall;
        $nm_uninstall['options'][] = static::$strSettingId;

        // Add the admin menu options
        add_action('admin_menu', array($this, 'createSettingsPage'));
        add_action('init', array($this, 'updateAdminMessages'));
        add_filter('custom_menu_order', '__return_true');

    }

    /**
     * Add the settings page
     *
     */
    public function createSettingsPage() {
        global $menu;

        // Add the VP separator
        $menu[] = array('', 'read', 'separator-donation_settings', '', 'wp-menu-separator');

        // Add the setting page
        add_menu_page(
            static::$strSettingTitle,
            static::$strSettingTitle,
            static::$strSettingCapability,
            static::$strSettingSlug,
            array($this, 'createSettingsHtml'),
            null,
            85
        );
    }

    /**
    * Update Season Dates
    *
    */
    public static function updateAdminMessages(){
        if(isset($_GET['page']) && $_GET['page'] == 'products_settings'){
            isset($_POST['customer_email_message']) ? update_option('customer_email_message', $_POST['customer_email_message']) : ""; 
            isset($_POST['admin_notification_email']) ? update_option('admin_notification_email', $_POST['admin_notification_email']) : ""; 
        }
    }

    /**
     * Outputs the form HTML
     *
     */
    public static function createSettingsHtml(){
        if(isset($_GET['page']) && $_GET['page'] == 'products_settings'){
            $strAdminEmailContent = get_option('customer_email_message');
            $strAdminEmail = get_option('admin_notification_email');
            ?>
             <div class="admin-shop-settings">
                <form method="post">
                    <div class="input-section">
                        <h2>Notificaton Email</h2>
                        <input type="email" placeholder="Admin email for Notifications" value="<?php echo $strAdminEmail;?>" class="admin_notification_email" name="admin_notification_email">
                    </div>
                    <div class="input-section">
                        <h2>Order Email Message For Customer</h2>
                        <?php 
                            wp_editor(
                                htmlspecialchars_decode($strAdminEmailContent), 
                                'customer_email_message', 
                                array(
                                    "media_buttons" => false,
                                )
                            );
                        ?>
                    </div>
                    <button type="submit" class="button-product-settings button button-primary button-large">Save</button>                  
                </form>
            </div>
        <?php
            settings_fields(static::$strSettingId);
            do_settings_sections(static::$strSettingSlug);
        }
    }
}

// Create a new instance
$objSettings = new Settings_Controller();
