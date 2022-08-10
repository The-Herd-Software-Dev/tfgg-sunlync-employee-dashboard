<?php

/**
 * Plugin Name: TFGG Employee Dashboard
 * Description: Dashboard for SunLync stats
 * Version:     0.0.0.0
 * Author:      The Herd llc.
 */

    //2022-07-30 CB V1.0.0.0 - should only be set on the dev environment
    if($_SERVER['HTTP_HOST']=='localhost'){
        ini_set('display_errors', 1);
        ini_set('display_startup_errors', 1);
        error_reporting(E_ALL);
    }  

    //2022-07-30 CB V1.0.0.0 - if no session exists, start one
    if(session_status() !== PHP_SESSION_ACTIVE){ session_start(['read_and_close'=>true]);}

    $dir = plugin_dir_url(__FILE__);
    define("TFGG_EMP_DASH_PLUGIN_FILE", __FILE__);
    require_once('register-files.php');
    require_once('functions.php');
    
?>