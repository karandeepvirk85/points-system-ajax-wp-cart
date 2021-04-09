<?php

defined('ABSPATH') OR exit;

/**
 * Plugin Name: Next Page It
 * Plugin URI: https://nextpageit.com
 * Description: A plugin to manage Wordpress
 * Version: 1.3
 * Author: Next Page
 * Author URI: http://nextpageit.com
*/

define('Nextpage_VERSION', 1.3);
define('Nextpage_URL', get_bloginfo('url'));
define('Nextpage_REQUEST', $_SERVER['REQUEST_URI']);
define('Nextpage_ROOT', $_SERVER['DOCUMENT_ROOT']);
define('Nextpage_PLUGIN', plugin_dir_url(__FILE__));
define('Nextpage_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('Nextpage_USER_IV', 'JhP1RTrl3eJgIBu7fklVFw==');
define('Nextpage_IMAGES', Nextpage_PLUGIN . 'assets/images/');
define('Nextpage_CSS', Nextpage_PLUGIN . 'assets/css/');
define('Nextpage_JS', Nextpage_PLUGIN . 'assets/js/');
define('Nextpage_VENDOR', Nextpage_PLUGIN . 'assets/vendor/');
$uploads = wp_upload_dir();
define('Nextpage_UPLOADS', $uploads['baseurl']);
define('Nextpage_UPLOADS_ROOT', $uploads['basedir']);
include_once('cuztom.php');
include_once('functions.php');
add_action('admin_enqueue_scripts', 'Nextpage_admin_enqueues', 10);

$Nextpage_uninstall = array(
    'post_types' => array(),
    'taxonomies' => array(),
    'options' => array()
);

// Post types extra functionality will be extended in the models
$pattern = Nextpage_PLUGIN_DIR . 'models/*.php';
foreach (glob($pattern) as $filename) {
    include_once($filename);
}

$pattern = Nextpage_PLUGIN_DIR . 'controllers/*.php';
foreach (glob($pattern) as $filename) {
    include_once($filename);
}

/**
 * Plugin enqueue hooks to add the frontend CSS + scripts
 *
 */
function Nextpage_admin_enqueues() {
    wp_enqueue_style('custom-posts-admin', Nextpage_CSS . 'style-admin.css');
    wp_enqueue_script('custom-posts-script-admin', Nextpage_JS . 'scripts-admin.js', array('jquery'), Nextpage_VERSION);
}

/**
 * Plugin Activation / Deactivation / Uninstall Hooks
 *
 */

register_activation_hook(__FILE__, 'Nextpage_plugin_activate');
register_deactivation_hook (__FILE__, 'Nextpage_plugin_deactivate');
register_uninstall_hook(__FILE__, 'Nextpage_plugin_delete');
    
function Nextpage_plugin_activate() {
    flush_rewrite_rules();
}

function Nextpage_plugin_deactivate() {
    flush_rewrite_rules();
}
