<?php

    require_once('current-store-clockins.php');
    require_once('late-store-clockins.php');

    function display_tfgg_emp_dashboard(){
        date_default_timezone_set('Europe/London');
    ?>

    <div class="card" style="margin-bottom: 10px">
        <div class="card-header">
            <div class="btn-group" role="group" aria-label="Basic example">
                <button type="button" 
                    class="btn btn-sm btn-secondary tfgg-ed-button <?php if(!isset($_GET['tfgg_ed_pg'])){echo 'tfgg-ed-button-active';}?>"
                    onclick="tfggDashboardNavCurrentClockIns()">Current Clock-ins</button>
                <button type="button" 
                    class="btn btn-sm btn-secondary tfgg-ed-button <?php if(isset($_GET['tfgg_ed_pg']) && ($_GET['tfgg_ed_pg']=='lateClockIn')){echo 'tfgg-ed-button-active';}?>"
                    onclick="tfggDashboardNavLateClockIns()">Late Clock-ins</button>
            </div>
            <button class="btn btn-primary float-right btn-sm tfgg-ed-button" onclick="tfggDashboardRefresh();">Refresh</button>
        </div>
        <div class="card-body">
    <?php    
        if(isset($_GET['tfgg_ed_pg'])){

            switch($_GET['tfgg_ed_pg']){
                case 'lateClockIn':
                    display_tfgg_emp_dash_late_store_clockins();
                    break;
                default:
                    display_tfgg_emp_dash_current_store_clockins();
            }

        }else{
            display_tfgg_emp_dash_current_store_clockins();
        }
    ?>

        </div>
        <div class="card-footer" style="font-size:80%">
            <span>Logged in as: <?php echo tfgg_emp_dash_employee_name(); ?></span>
        </div>
    </div>

    <?php
    }

?>