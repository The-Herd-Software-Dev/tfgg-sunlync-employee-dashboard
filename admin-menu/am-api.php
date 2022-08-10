<?php

    define('TFGG_EMP_DASH_API_SECTION','tfgg_emp_dash_section');
    define('TFGG_EMP_DASH_API_OPTIONS','tfgg-emp-dash-options');

    function tfgg_emp_dash_admin_api_options(){
        add_settings_section(TFGG_EMP_DASH_API_SECTION, '', null, TFGG_EMP_DASH_API_OPTIONS);

        add_settings_field("tfgg_emp_dash_api_protocol", "Protocol:", "display_tfgg_emp_dash_api_protocol", TFGG_EMP_DASH_API_OPTIONS, TFGG_EMP_DASH_API_SECTION);
        register_setting(TFGG_EMP_DASH_API_SECTION, "tfgg_emp_dash_api_protocol");
        
        add_settings_field("tfgg_emp_dash_api_url", "URL:", "display_tfgg_emp_dash_api_url", TFGG_EMP_DASH_API_OPTIONS, TFGG_EMP_DASH_API_SECTION);
        register_setting(TFGG_EMP_DASH_API_SECTION, "tfgg_emp_dash_api_url");
        
        add_settings_field("tfgg_emp_dash_api_port", "Port:", "display_tfgg_emp_dash_api_port", TFGG_EMP_DASH_API_OPTIONS, TFGG_EMP_DASH_API_SECTION);
        register_setting(TFGG_EMP_DASH_API_SECTION, "tfgg_emp_dash_api_port");
        
        add_settings_field("tfgg_emp_dash_api_mrkt", "Market:", "display_tfgg_emp_dash_api_market", TFGG_EMP_DASH_API_OPTIONS, TFGG_EMP_DASH_API_SECTION);
        register_setting(TFGG_EMP_DASH_API_SECTION, "tfgg_emp_dash_api_mrkt");
        
        add_settings_field("tfgg_emp_dash_api_user", "Username:", "display_tfgg_emp_dash_api_user", TFGG_EMP_DASH_API_OPTIONS, TFGG_EMP_DASH_API_SECTION);
        register_setting(TFGG_EMP_DASH_API_SECTION, "tfgg_emp_dash_api_user");
        
        add_settings_field("tfgg_emp_dash_api_pass", "Password:", "display_tfgg_emp_dash_api_pass", TFGG_EMP_DASH_API_OPTIONS, TFGG_EMP_DASH_API_SECTION);
        register_setting(TFGG_EMP_DASH_API_SECTION, "tfgg_emp_dash_api_pass");   
    }

    function tfgg_sunlync_emp_dash_page(){
        tfgg_emp_dash_admin_menu_header();
        ?>

        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-11 col-md-8">
                    <div class="card tfgg-emp-dash-card">
                        <div class="card-header"><h5>SunLync API Connection</h5></div>
                        <div class="card-body">
                            <p class="card-text">Please enter the URL and Credentials for the SunLync API connection</p>
                            <form method="post" action="options.php">
                                <?php
                                settings_fields(TFGG_EMP_DASH_API_SECTION);
                                do_settings_sections(TFGG_EMP_DASH_API_OPTIONS);
                                ?>
                                <div class="form-row">
                                    <div class="form-group col-md-6">
                                        <div class="form-group col-12">
                                            <button type="submit" class="btn btn-primary"><?php echo __('Save Settings');?></button>
                                        </div>
                                    </div>
                                </div>
                            </form>                                                   
                        </div>
                        <div class="card-footer">
                            <?php
                            if((get_option('tfgg_emp_dash_api_url','')!='')&&(get_option('tfgg_emp_dash_api_port','')!='')
                            &&(get_option('tfgg_emp_dash_api_user','')!='')&&(get_option('tfgg_emp_dash_api_pass','')!='')){
                            ?>
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <div class="form-group col-12">
                                        <button type="submit" onclick="TestEmpDashAPICredentials()" class="btn btn-primary"><?php echo __('Test Credentials');?></button>
                                    </div>
                                </div>
                                <div class="form-group col-md-6">
                                    <div id="tfgg-api-options-test-api-response" class="notice is-dismissible" style="display:none">
                                        <p>API Responded With: <strong><span id="tfgg-api-test-response"></span></strong></p>
                                    </div>  
                                </div>
                            </div>
                            <?php
                            }
                            ?> 
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <?php
    }

    function display_tfgg_emp_dash_api_protocol(){
        $selected = get_option('tfgg_emp_dash_api_protocol','http');
        ?>
        <select name="tfgg_emp_dash_api_protocol" style="width: 60%">
            <option value="http" <?php echo ($selected=='http'?'selected':''); ?>>HTTP</option>
            <option value="https" <?php echo ($selected=='https'?'selected':''); ?>>HTTPS</option>
        </select>
        <?php
    }

    function display_tfgg_emp_dash_api_url(){
        ?>
        <input type="text" name="tfgg_emp_dash_api_url" value="<?php echo get_option('tfgg_emp_dash_api_url'); ?>" style="width: 60%" />
        <?php
    }

    function display_tfgg_emp_dash_api_port(){
        ?>
        <input type="number" name="tfgg_emp_dash_api_port" value="<?php echo get_option('tfgg_emp_dash_api_port'); ?>" style="width: 30%" />
        <?php
    }

    function display_tfgg_emp_dash_api_market(){
        ?>
        <input type="text" name="tfgg_emp_dash_api_mrkt" value="<?php echo get_option('tfgg_emp_dash_api_mrkt'); ?>" style="width: 60%" />
        <?php
    }

    function display_tfgg_emp_dash_api_user(){
        ?>
        <input type="text" name="tfgg_emp_dash_api_user" value="<?php echo get_option('tfgg_emp_dash_api_user'); ?>" style="width: 60%" />
        <?php
    }

    function display_tfgg_emp_dash_api_pass(){
        ?>
        <input type="password" name="tfgg_emp_dash_api_pass" value="<?php echo get_option('tfgg_emp_dash_api_pass'); ?>" style="width: 60%" />
        <?php
    }

?>