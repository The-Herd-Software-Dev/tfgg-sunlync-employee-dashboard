<?php

    /**
     * Helper function to determine the current session user was
     * authorized to use the dashboard as a SunLync Employee
     */
    function tfgg_emp_dash_logged_in_check(){

        if(!isset($_SESSION['sunlync_employee'])){
            return false;
        }else{
            return true;
        }

    }

    /**
     * Set the current employee session credentials
     * @param object $employee the employee information returned after a successful login
     * 
     * @return void
     */
    function tfgg_emp_dash_set_employee($employee){
        if(session_status() !== PHP_SESSION_ACTIVE){ session_start();}
        $_SESSION['sunlync_employee']['employee_number'] = $employee->emp_no;
        $_SESSION['sunlync_employee']['first_name'] = $employee->firstname;
        $_SESSION['sunlync_employee']['last_name'] = $employee->lastname;
        session_write_close();
    }

    function tfgg_emp_dash_unset_employee(){
        if(session_status() !== PHP_SESSION_ACTIVE){ session_start();}
        unset($_SESSION['sunlync_employee']);
        session_write_close();
    }


    /**
     * Log the current SunLync Employee out of the dashboard
     */
    function tfgg_emp_dash_logout(){        
        tfgg_emp_dash_unset_employee();
        $result["logout"]=site_url();//possible configurable option
        exit(json_encode($result));
    }
    add_action( 'wp_ajax_tfgg_emp_dash_logout', 'tfgg_emp_dash_logout' );
    
    /**
     * Function to process the contents of the login request
     * 
     */
    function tfgg_emp_dash_process_login_form_request(){
        
        $result = array();

        if(!wp_verify_nonce($_POST['data']['wpnonce'],'tfgg-ed-login-nonce')){
            $result['result'] = false;
            exit(json_encode($result));
        }

        $loginResult = tfgg_emp_dash_validate_login($_POST['data']['username'], $_POST['data']['password']);
        
        if($loginResult['results'] == 'success'){
            $result['result'] = true;
        }else{
            $result['result'] = false;  
            tfgg_ed_log_me($loginResult);
        }

        exit(json_encode($result));

    }
    add_action( 'wp_ajax_tfgg_emp_dash_process_login_form_request', 'tfgg_emp_dash_process_login_form_request' );

    /**
     * function to send the employee username and password to the sunlync api
     * for validation
     * 
     * @param string $username the employee username as stored in SunLync
     * @param string $password the current employee password
     * 
     * @return array an array containing the result and some supporting information
     */
    function tfgg_emp_dash_validate_login($username, $password){

        $url = tfgg_ed_get_api_url();
        $url.='TSunLyncAPI/CIPValidateSecurity//sLoginID/sLoginPass//';

        $url=str_replace('sLoginID',tfgg_emp_dash_hash_password($username),$url);
        $url=str_replace('sLoginPass',tfgg_emp_dash_hash_password($password),$url);

        try{
            $data = tfgg_ed_execute_api_request('GET', $url, '');
        }catch(Exception $e){
            $result["results"]="error";
            $result["response"]=$e->getMessage(); 
            return $result;
        }

        if(is_array($data[0])){
            if((isset($data[0]['ERROR']))||(isset($data[0]['WARNING']))){
                if(isset($data[0]['ERROR'])){
                    $result=array("results"=>"FAIL",
                        "response"=>$data[0]['ERROR']);
                }else{
                    $result=array("results"=>"FAIL",
                        "response"=>$data[0]['WARNING']);
                }
                
                return $result;
            }else{

                $result["results"]="success";
    
            }
        }elseif(is_object($data[0])){
            if((property_exists($data[0],'ERROR'))||(property_exists($data[0],'WARNING'))){
                if(property_exists($data[0],'ERROR')){
                    $result=array("results"=>"FAIL",
                        "response"=>$data[0]->ERROR);
                }else{
                    $result=array("results"=>"FAIL",
                        "response"=>$data[0]->WARNING);
                }

            }else{

                $result["results"]="success";
    
            }
        }
               
        tfgg_emp_dash_set_employee($data[0]);

        $allowedStores = implode(',', (array)get_option('tfgg_emp_dash_store_selection'));
        $employeeStores = tfgg_emp_dash_employee_stores($_SESSION['sunlync_employee']['employee_number'], $allowedStores);

        if(session_status() !== PHP_SESSION_ACTIVE){ session_start();}
        $_SESSION['sunlync_employee']['storelist'] = $employeeStores;
        session_write_close();

        return $result;
        
    }

    /**
     * used to add the Log Out link to the secondary-menuy once the employee is logged in
     */
    function tfgg_emp_dash_add_logout_employee_link($items, $args){
        //add a logout link to the small menu bar (secondary-menu) at the top of the screen
        if(tfgg_emp_dash_logged_in_check() && $args->theme_location=='secondary-menu'){
          $items .='<li><a href="" onclick="tfggEmpDashEndEmployeeSessions();">Log Out</a></li>';  
        }
        return $items;
    }
    add_filter('wp_nav_menu_items', 'tfgg_emp_dash_add_logout_employee_link', 10, 2 );

?>