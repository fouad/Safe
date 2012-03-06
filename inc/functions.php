<?php

function safe_get_serverinfo() {
        global $wpdb;
        $sqlversion = $wpdb->get_var("SELECT VERSION() AS version");
        $mysqlinfo = $wpdb->get_results("SHOW VARIABLES LIKE 'sql_mode'");
        if (is_array($mysqlinfo)) 
            $sql_mode = $mysqlinfo[0]->Value;
        if (empty($sql_mode)) 
            $sql_mode = __('Not set');
        $sm = ini_get('safe_mode');
        if (strcasecmp('On', $sm) == 0) { 
            $safe_mode = __('On'); 
        }
        else { 
            $safe_mode = __('Off'); 
        }
        if(ini_get('allow_url_fopen')) 
            $allow_url_fopen = __('On');
        else 
            $allow_url_fopen = __('Off');
        if(ini_get('upload_max_filesize')) 
            $upload_max = ini_get('upload_max_filesize');
        else 
            $upload_max = __('N/A');
        if(ini_get('post_max_size')) 
            $post_max = ini_get('post_max_size');
        else 
            $post_max = __('N/A');
        if(ini_get('max_execution_time')) 
            $max_execute = ini_get('max_execution_time');
        else 
            $max_execute = __('N/A');
        if(ini_get('memory_limit')) 
            $memory_limit = ini_get('memory_limit');
        else 
            $memory_limit = __('N/A');
        if (function_exists('memory_get_usage')) 
            $memory_usage = round(memory_get_usage() / 1024 / 1024, 2) . __(' MByte');
        else 
            $memory_usage = __('N/A');
        if (is_callable('exif_read_data')) 
            $exif = __('Yes'). " ( V" . substr(phpversion('exif'),0,4) . ")" ;
        else 
            $exif = __('No');
        if (is_callable('iptcparse')) 
            $iptc = __('Yes');
        else 
            $iptc = __('No');
        if (is_callable('xml_parser_create')) 
            $xml = __('Yes');
        else 
            $xml = __('No');

?>
        <li><?php _e('Operating System'); ?> : <strong><?php echo PHP_OS; ?></strong></li>
        <li><?php _e('Server'); ?> : <strong><?php echo $_SERVER["SERVER_SOFTWARE"]; ?></strong></li>
        <li><?php _e('Memory usage'); ?> : <strong><?php echo $memory_usage; ?></strong></li>
        <li><?php _e('MYSQL Version'); ?> : <strong><?php echo $sqlversion; ?></strong></li>
        <li><?php _e('SQL Mode'); ?> : <strong><?php echo $sql_mode; ?></strong></li>
        <li><?php _e('PHP Version'); ?> : <strong><?php echo PHP_VERSION; ?></strong></li>
        <li><?php _e('PHP Safe Mode'); ?> : <strong><?php echo $safe_mode; ?></strong></li>
        <li><?php _e('PHP Allow URL fopen'); ?> : <strong><?php echo $allow_url_fopen; ?></strong></li>
        <li><?php _e('PHP Memory Limit'); ?> : <strong><?php echo $memory_limit; ?></strong></li>
        <li><?php _e('PHP Max Upload Size'); ?> : <strong><?php echo $upload_max; ?></strong></li>
        <li><?php _e('PHP Max Post Size'); ?> : <strong><?php echo $post_max; ?></strong></li>
        <li><?php _e('PHP Max Script Execute Time'); ?> : <strong><?php echo $max_execute; ?>s</strong></li>
        <li><?php _e('PHP Exif support'); ?> : <strong><?php echo $exif; ?></strong></li>
        <li><?php _e('PHP IPTC support'); ?> : <strong><?php echo $iptc; ?></strong></li>
        <li><?php _e('PHP XML support'); ?> : <strong><?php echo $xml; ?></strong>  </li>
<?php
}

function safe_check_table_prefix(){
    if($GLOBALS['table_prefix']=='wp_'){
        echo '<span class="fail">Your table prefix should not be <em>wp_</em>.</span><br />';
    }
    else { echo '<span class="pass">Your table prefix is not <i>wp_</i>.</span><br />'; }
}

function safe_errorsoff(){
    echo '<span class="pass">WordPress DB Errors turned off.</span><br />';
}


function safe_version_removal(){
    global $wp_version;
    echo '<span class="pass">Your WordPress version is successfully hidden.</span><br />';
}

function safe_check_version()
{
    $c = get_site_transient( 'update_core' );
    if ( is_object($c))
    {
        if (empty($c->updates))
        {
            echo '<span class="pass">'.__('You have the latest version of Wordpress.').'</span>';
            return;
        }

        if (!empty($c->updates[0]))
        {
            $c = $c->updates[0];

            if ( !isset($c->response) || 'latest' == $c->response ) {
                echo '<span class="pass">'.__('You have the latest version of Wordpress.').'</span>';
                return;
            }

            if ('upgrade' == $c->response)
            {
                $lv = $c->current;
                $m = '<span class="fail">'.sprintf('Wordpress <strong>(%s)</strong> is available. You should upgrade to the latest version.', $lv).'</span>';
                echo __($m);
                return;
            }
        }
    }

    echo '<span class="fail">'.__('An error has occurred while trying to retrieve the status of your Wordpress version.').'</span>';
}
function safe_wpConfigCheckPermissions($wpConfigFilePath)
{
    if (!is_writable($wpConfigFilePath)) { 
        echo '<span class="pass">'.__('Your wp config file is not a threat.').'</span>';
        return false; 
    }

    if (!function_exists('file') || !function_exists('file_get_contents') || !function_exists('file_put_contents'))
    {
        echo '<span class="pass">'.__('Your wp config file is not a threat.').'</span>';
        return false;
    }
    else {
        echo '<span class="fail">'.__('Your wp config file can be compromised by hackers, fix the permissions.').'</span>';
        return true;
    }
}

?>