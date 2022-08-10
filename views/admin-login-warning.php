<?php

    /**
     * Simple login for to warn users who are not presently logged in as wp admin
     * that they cannot view this form
     */
    function display_tfgg_emp_dash_login_form_warning(){
    ?>
        <div class="container py-5 h-100 tfgg_ed_loginform">
        <div class="row d-flex justify-content-center align-items-center h-50">
        <div class="col-12 col-md-8 col-lg-6 col-xl-5">
            <div class="card shadow-2-strong" style="border-radius: 1rem;">
                <div class="card-body p-5 text-center">
                    <div class="alert alert-warning">
                        Admin level access required to view this page
                    </div>
                </div>
            </div>
        </div>
        </div>
        </div>
    <?php
    }

?>