<?php

    /**
     * simple helper function to return the currently logged in employee name
     * @return string the employee name
     */
    function tfgg_emp_dash_employee_name(){
        return $_SESSION['sunlync_employee']['first_name'].' '.$_SESSION['sunlync_employee']['last_name'];
    }

    /**
     * Used to retrieve the list of stores the employee is affiliated with in SunLync
     * @param string $employee the unique SunLync identifier of the employee
     * @param string|optional $allowedStores a string used to filter the store list returned from the API
     * @return array|object the list of storecodes the employee is affiliated with
     */
    function tfgg_emp_dash_employee_stores($employee, $allowedStores){
        if($employee == ''){
            return '';
        }

        $url = tfgg_ed_get_api_url();
        $url.='TSunLyncAPI/CIPAdminEmployeeStores/sEmpNo';

        $url=str_replace('sEmpNo',$employee,$url);

        try{
            $data = tfgg_ed_execute_api_request('GET',$url,'');            
        }catch(Exception $e){
            $result["results"]="error";
            $result["error_message"]=$e->getMessage(); 
            exit(json_encode($result));
        }

        $result = array();
        
        foreach($data as $storeDetails){
            if(property_exists($storeDetails,'storecode')){
                if($allowedStores!=''){
                    if(str_contains($allowedStores,$storeDetails->storecode)){
                        array_push($result,$storeDetails);
                    }
                }else{
                    //not configured, use them all
                    array_push($result,$storeDetails);
                }
            }
        }

        return $result;

    }

    /**
     * returns a list of the current employees storecodes for the api
     * 
     * @return string comma delimeted list of storecodes
     */
    function tfgg_emp_dash_employee_storecodes_for_api(){

        if(isset($_SESSION['sunlync_employee']['storelist'])){
            $storecodes = '';
            foreach($_SESSION['sunlync_employee']['storelist'] as $storeDetails){
                $storecodes.='"'.$storeDetails->storecode.'",';
            }

            return substr($storecodes,0,-1); //remove the last comma

        }else{
            return '';
        }

    }
    
?>