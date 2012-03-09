<?php
/*
Plugin Name: Safe
Plugin URI: http://wp.tutsplus.com

Description: The easiest, most effective way to secure your WordPress site from attackers.
Author: Fouad Matin
Version: 1.0
Author URI: http://wp.tutsplus.com/author/fouad
*/
if ( ! defined('WP_CONTENT_URL')) {
    define( 'WP_CONTENT_URL', get_option( 'siteurl' ) . '/wp-content' );
}
if ( ! defined('WP_CONTENT_DIR')) {
    define( 'WP_CONTENT_DIR', ABSPATH . 'wp-content' );
}
if ( ! defined('WP_PLUGIN_URL')) {
    define( 'WP_PLUGIN_URL', WP_CONTENT_URL. '/plugins' );
}
if ( ! defined('WP_PLUGIN_DIR')) {
    define( 'WP_PLUGIN_DIR', WP_CONTENT_DIR . '/plugins' );
}

require_once(WP_PLUGIN_DIR . "/safe/inc/functions.php");

add_filter('admin_footer_text', 'remove_footer_admin');
add_action('admin_enqueue_scripts','safe_styles');
add_action('admin_menu', 'add_menu_bpg');
remove_action('wp_head', 'wp_generator');

function remove_footer_admin () {
    echo "Thank you for creating with <a href='http://wordpress.org'>WordPress</a>. Also, thank you for using the Safe security plugin by <a href='http://fouadmat.in'>Fouad Matin</a>.";
} 

function safe_styles(){
    wp_enqueue_style('safe_style', plugin_dir_url(__FILE__) . 'css/safe.css');
}

function add_menu_bpg()
{
    if (!current_user_can('administrator')){return false;}

    if (function_exists('add_menu_page'))
    {
        add_menu_page('Safe', 'Safe', 'edit_pages', __FILE__, 'safe_main', WP_PLUGIN_URL.'/safe/img/safe.png');
    }
}

function safe_meta_box()
{
?>
    <div id="safe-basic-checks" class="safe-inside">
        <div class="safe-basic-checks-section"><?php safe_check_version();?></div>

        <div class="safe-basic-checks-section"><?php safe_check_table_prefix();?></div>

        <div class="safe-basic-checks-section"><?php safe_version_removal();?></div>

        <div class="safe-basic-checks-section"><?php safe_errorsoff();?></div>

        <div class="safe-basic-checks-section"><?php safe_wpConfigCheckPermissions('/wp-config.php');?></div>
<?php
    global $wpdb;

    echo '<div class="pass">WP ID META tag removed form WordPress core</div>';

    echo '<div class="safe-basic-checks-section">';
        $name = $wpdb->get_var("SELECT user_login FROM $wpdb->users WHERE user_login='admin'");
        if ($name == "admin") {
                echo '<span class="fail">"admin" user exists.</span>';
        }
        else { echo '<span class="pass">No user "admin".</span>'; }
    echo '</div>';

    echo '<div class="safe-basic-checks-section">';
        if (file_exists('.htaccess')) {
            echo '<span class="pass">.htaccess file found in wp-admin/</span>';
        }
        else { echo '<span class="fail">The file .htaccess does not exist in the wp-admin section.</span>'; }
    echo '</div>';

?>
    </div>
<?php
}

function safe_meta_box2()
{
?>
    <ul id="safe-information-scan-list">
            <?php safe_get_serverinfo(); ?>
    </ul>
<?php
}

function safe_main() 
{

            add_meta_box("safe_box_1", 'Basic Checks', "safe_meta_box", "box1");
            add_meta_box("safe_box_2", 'System Information', "safe_meta_box2", "box2");

        echo '  
            <div class="metabox-holder">
                <div style="float:left; width:48%;" class="inner-sidebar1">';
         
                    do_meta_boxes('box1','advanced','');      

        echo '      
                </div>
                <div style="float:right;width:48%;" class="inner-sidebar1">';
                    do_meta_boxes('box2','advanced','');  
        echo '  
                </div>
                        
                <div style="clear:both"></div>
            </div>';

}
?>