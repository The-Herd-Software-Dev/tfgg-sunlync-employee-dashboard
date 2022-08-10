<?php

    require_once(dirname(__DIR__, 1).'/views/login.php');
    require_once(dirname(__DIR__, 1).'/views/admin-login-warning.php');    
    require_once(dirname(__DIR__, 1).'/views/dashboard.php');

    function tfgg_emp_dashboard(){
        if ( !is_user_logged_in() ) {
            display_tfgg_emp_dash_login_form_warning();
            return;
        }

        if(!tfgg_emp_dash_logged_in_check()){            
            display_tfgg_emp_dash_login_form();
            return;
        }

        display_tfgg_emp_dashboard();
    }

?>