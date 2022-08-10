<?php
    /*
    * used to control the admin menu provided
    */

    function tfgg_sunlync_emp_dashboard(){
        tfgg_emp_dash_admin_api_options();
        tfgg_emp_dash_admin_store_selection_options();
    }
    add_action("admin_init", "tfgg_sunlync_emp_dashboard");

    //include the admin-menu files
    require_once('admin-menu/am-api.php');
    require_once('admin-menu/am-store-selection.php');

    function tfgg_ed_enqueue_admin_styles_scripts(){
        $jsv = date("ymd-Gis", filemtime( plugin_dir_path( __FILE__ ) . 'admin-menu/js/admin-scripts.js' ));
        wp_enqueue_script( 'tfgg-ed-api-admin-scripts', plugin_dir_url(__FILE__).'admin-menu/js/admin-scripts.js', array( 'jquery','jquery-ui-dialog'),$jsv,true );
        wp_localize_script('tfgg-ed-api-admin-scripts', 'tfgg_emp_dash_scripts_common', array(
            'ajaxurl' => admin_url( 'admin-ajax.php' )
        ));
        
        wp_enqueue_style( 'tfgg-ed-admin-styles', plugins_url( 'admin-menu/css/layout.css', __FILE__ ) );
    }
    add_action( 'admin_enqueue_scripts', 'tfgg_ed_enqueue_admin_styles_scripts' );

    //load the sidebar menu
    add_action('admin_menu','tfgg_sunlync_emp_dash_admin_menu_option');
    add_action('admin_menu', 'tfgg_sunlync_emp_dash_admin_submenu_stores_to_use');

    function tfgg_sunlync_emp_dash_admin_menu_option(){
        add_menu_page('Emp Dashboard API Connection',
        'SL Emp Dash',
        'manage_options',
        'tfgg-sunlync-emp-dash-admin-menu',
        'tfgg_sunlync_emp_dash_page',
        'dashicons-dashboard',
        6);
    }

    function tfgg_sunlync_emp_dash_admin_submenu_stores_to_use(){
        add_submenu_page(
        'tfgg-sunlync-emp-dash-admin-menu',
        'Stores to Use',
        'Stores To Use',
        'manage_options',
        'tfgg-emp-dash-admin-stores-to-use',
        'tfgg_emp_dash_admin_stores_to_use'
        );
    }

    //this header should be added to all pages
    function tfgg_emp_dash_admin_menu_header(){
        ?>
        <div class="container-fluid">
            <h2>TFGG Sunlync Employee Dashboard</h2>
            <?php if( isset($_GET['settings-updated']) ) { ?>
                <div id="message" class="updated">
                    <p><strong><?php _e('Settings saved.') ?></strong></p>
                </div>
            <?php }?> 
        </div>
        <?php
    }

?>