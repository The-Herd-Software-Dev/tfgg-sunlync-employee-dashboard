<?php

    require_once('lib/sunlync-api.php');
    require_once('lib/employees.php');
    require_once('lib/employee-auth.php');
    require_once('lib/stores.php');
    require_once('lib/utils.php');

    if ( is_admin()  ) {    
        require_once('lib/auto-updater.php');
        new tfgg_emp_dash_updater( plugin_dir_path( __FILE__ ).'tfgg-employee-dashboard.php', 'The-Herd-Software-Dev', "tfgg-sunlync-employee-dashboard" );
    }

?>