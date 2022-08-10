<?php

    add_action( 'admin_enqueue_scripts', 'load_tfgg_emp_dash_scripts_common' );
    add_action( 'wp_enqueue_scripts', 'load_tfgg_emp_dash_scripts_common' );
    add_action( 'wp_enqueue_scripts', 'load_tfgg_emp_dash_css' );

    function load_tfgg_emp_dash_scripts_common(){       
        
        wp_enqueue_style('bootstrap4', 'https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css');
        wp_register_script( 'boot-script', 'https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js', array( 'jquery' ) );
        wp_enqueue_script( 'boot-script' );
        
        wp_register_style( 'jquery-style', 'https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/themes/smoothness/jquery-ui.css', true);
        wp_enqueue_style( 'jquery-style' );
        wp_register_script( 'jquery-ui-datepicker', 'https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/jquery-ui.min.js', array( 'jquery' ) );
        wp_enqueue_script( 'jquery-ui-datepicker' );

        $jsv = date("ymd-Gis", filemtime( plugin_dir_path( __FILE__ ) . 'js/tfgg-emp-dash-scripts.js' ));
        wp_enqueue_script( 'tfgg-emp-dash-scripts', plugin_dir_url(__FILE__).'js/tfgg-emp-dash-scripts.js', array( 'jquery'),$jsv,false );

        wp_localize_script('tfgg-emp-dash-scripts', 'tfgg_emp_dash_scripts_common', array(
            'ajaxurl' => admin_url( 'admin-ajax.php' )
        ));
    }

    function load_tfgg_emp_dash_css(){
        wp_enqueue_style('tfgg-emp-dash',plugins_url( 'css/tfgg_emp_dash.css', __FILE__ ), array('bootstrap4')); 
     }

    require_once('admin-menu.php');
    require_once('shortcodes.php');

?>