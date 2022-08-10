<?php

    require_once('controllers/login.php');  


    function tfgg_ed_sunlync_emp_dashboard(){
        ob_start();
        tfgg_emp_dashboard();
        return ob_get_clean();
    }
    add_shortcode('cp_sunlync_emp_dashboard','tfgg_ed_sunlync_emp_dashboard');
?>