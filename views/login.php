<?php

    function display_tfgg_emp_dash_login_form(){
    ?>
    <div class="container py-5 h-100">
        <div class="row d-flex justify-content-center align-items-center h-50">
            <div class="col-12 col-md-8 col-lg-6 col-xl-5">
                <div class="card shadow-2-strong" style="border-radius: 1rem;">
                    <h5 class="card-header text-center">
                        Employee Sign In
                    </h5>
                    <div class="card-body p-5 text-center tfgg_ed_loginform">
                        <?php	
                            //tfgg_ed_display_all_notice_data_as_alerts();
                        ?>                   
                        <form id="tfgg_ed_loginform" autocomplete="off" method="" action="" onsubmit="tfggEmpDashEmployeeLogin()">
                            <div class="form-outline mb-4">
                            <input type="text" id="tfgg-ed-login-user" name="tfgg-ed-login-user" class="form-control form-control-lg tfgg-formcontrol" required/>
                            <label class="form-label" for="tfgg-ed-login-user">SunLync Username</label>
                            </div>

                            <div class="form-outline mb-4">
                            <input type="password" id="tfgg-ed-login-pass" name="tfgg-ed-login-pass" class="form-control form-control-lg tfgg-formcontrol" required/>
                            <label class="form-label" for="tfgg-ed-login-pass">Password</label>
                            </div>

                            <input type="hidden" name="tfgg-ed-login-nonce" id="tfgg-ed-login-nonce" value="<?php echo wp_create_nonce('tfgg-ed-login-nonce'); ?>"/>
                            <input type="text" name="tfgg-ed-login-user-password-reenter" id="tfgg-ed-login-user-password-reenter" style="display:none !important" tabindex="-1" autocomplete="off"/>
                            <button class="btn btn-primary btn-lg btn-block tfgg-ed-button" type="submit">Login</button>
                        </form>                
                    </div>
                    <div class="card-body p-5 text-center tfgg-ed-busy">
                        <img class="card-img" src="<?php echo plugin_dir_url( __FILE__ ).'../images/loading.gif'; ?>" alt="Card image"/>
                        <div class="card-img-overlay"></div>
                    </div>
                    <div class="card-body p-5 text-center tfgg-ed-login-result" style="display:none">
                        <div class="alert alert-warning" id="tfgg-ed-login-response-alert">
                            Login failed
                        </div>
                        <button class="btn btn-primary btn-lg btn-block tfgg-ed-button" type="button" id="tfgg_ed_retry_login"onclick="tfggEmpDashRetryLogin()">Try Again</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php
    }

?>