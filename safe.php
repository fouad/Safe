<?php
/*
Plugin Name: Safe
Plugin URI: http://www.tutsplus.com

Description: Your very own butler
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

require_once(WP_PLUGIN_DIR . "/alfred/libs/functions.php");
// require_once(WP_PLUGIN_DIR . "/safe/libs/safe.php");


//## this is the container for header scripts
add_action('admin_head', 'mrt_hd');
// # $rev #2 {c}
add_action('admin_init', 'wps_admin_init_load_resources');

//before sending headers
add_action("init",'mrt_wpdberrors',1);

//after executing a query
add_action("parse_query",'mrt_wpdberrors',1);

//## add the sidebar menu
add_action('admin_menu', 'add_men_bpg');

// add_action("init", 'mrt_remove_wp_version',1);   //comment out this line to make ddsitemapgen work

//before rendering each admin init
add_action('admin_init','safe_admin_init');
add_filter('admin_footer_text', 'remove_footer_admin');

function remove_footer_admin () {
    echo "Thank you for creating with <a href='http://wordpress.org'>WordPress</a>. Also, thank you for using the safe security plugin by <a href='http://fouadmat.in'>Fouad Matin</a>.";
} 

function safe_admin_init(){
    wp_enqueue_style('safe_style', plugin_dir_url(__FILE__) . 'css/safe.css');
}

remove_action('wp_head', 'wp_generator');
function add_men_bpg()
{
    if (!current_user_can('administrator')){return false;}

    if (function_exists('add_menu_page'))
    {
        add_menu_page('Safe', 'Safe', 'edit_pages', __FILE__, 'mrt_opt_mng_pg', WP_PLUGIN_URL.'/safe/img/safe.png');
    }
}
// add_filter( 'plugin_action_links_'.plugin_basename(__FILE__), 'wpss_admin_plugin_actions', -10);

function safe_meta_box()
{
?>
    <div id="safe-initial-scan" class="safe-inside">
        <div class="safe-initial-scan-section"><?php mrt_check_version();?></div>

        <div class="safe-initial-scan-section"><?php mrt_check_table_prefix();?></div>

        <div class="safe-initial-scan-section"><?php mrt_version_removal();?></div>

        <div class="safe-initial-scan-section"><?php mrt_errorsoff();?></div>
<?php
    global $wpdb;

    echo '<div class="scanpass">WP ID META tag removed form WordPress core</div>';

    echo '<div class="safe-initial-scan-section">';
        $name = $wpdb->get_var("SELECT user_login FROM $wpdb->users WHERE user_login='admin'");
        if ($name == "admin") {
                echo '<font color="red">"admin" user exists.</font>';
        }
        else { echo '<span class="scanpass">No user "admin".</span>'; }
    echo '</div>';

    echo '<div class="safe-initial-scan-section">';
        if (file_exists('.htaccess')) {
            echo '<span class="scanpass">.htaccess file found in wp-admin/</span>';
        }
        else { echo '<span style="color:#f00;">
            The file .htaccess does not exist in the wp-admin section.
            Read more why you should have a .htaccess file in  the WP-admin area
            <a href="http://www.websitedefender.com/wordpress-security/htaccess-files-wordpress-security/"
            title="Why you should have a .htaccess file in  the WP-admin area" target="_blank">here</a>.
            </span>'; }
    echo '</div>';

?>

        <div class="mrt_wpss_note">
            <em>**WP Security Scan plugin <strong>must</strong> remain active for security features to persist**</em>
        </div>
    </div>
    sadf
<?php
}
function safe_meta_box2()
{
?>
    <ul id="safe-information-scan-list">
            <?php mrt_get_serverinfo(); ?>
    </ul>
<?php
}
function safe_render_main()
{
  echo 'tits';
}
function mrt_opt_mng_pg() {

            add_meta_box("wpss_mrt_1", 'Initial Scan', "safe_meta_box", "wpss");

            add_meta_box("wpss_mrt_3", 'About Website Defender', "safe_render_main", "wpss_safe");
            add_meta_box("wpss_mrt_2", 'System Information Scan', "safe_meta_box2", "wpss2");

echo '  
            <div class="metabox-holder">
                <div style="float:left; width:48%;" class="inner-sidebar1">';
         
                    do_meta_boxes('wpss','advanced','');    
                    do_meta_boxes('wpss_safe','advanced','');   

echo '      
                </div>
                <div style="float:right;width:48%;" class="inner-sidebar1">';
                    do_meta_boxes('wpss2','advanced','');  
echo '  
                </div>
                        
                <div style="clear:both"></div>
            </div>';

    }
?>