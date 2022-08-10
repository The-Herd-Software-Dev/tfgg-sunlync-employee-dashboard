<?php
    /**
     * Returns a list of unfiltered stores from the SunLync API
     * Stores are sorted alphabetically desc
     * 
     */
    function tfgg_ed_api_get_unfiltered_stores(){

        $url= tfgg_ed_get_api_url().'TSunLyncAPI/CIPGetStoreDemoApptInfo/sStoreCode/nInAppts';
    
        $url=str_replace('sStoreCode','',$url);
        $url=str_replace('nInAppts','',$url);

        try{
            $data = tfgg_ed_execute_api_request('GET', $url, '');
        }catch(Exception $e){
            $result["results"]="error";
            $result["error_message"]=$e->getMessage(); 
            return json_encode($result);
        }

        if((property_exists($data[0],'ERROR'))||(property_exists($data[0],'WARNING'))){
            if(property_exists($data[0],'ERROR')){
                $result=array("results"=>"FAIL",
                    "response"=>$data[0]->ERROR);
            }else{
                $result=array("results"=>"FAIL",
                    "response"=>$data[0]->WARNING);
            }
            
            return json_encode($result);
        }else{

            $result["results"]="success";
            $stores=array_slice($data,1,-1);
            $stores=tfgg_ed_remove_closed_stores($stores);
            usort($stores,"tfgg_ed_sort_store_by_name");

            $result["stores"]=$stores;
            return json_encode($result);

        }

        /*
        example return object
        { 
            ["store_id"]=> string(10) "0000000042" 
            ["store_loc"]=> string(6) "Barnet" 
            ["address1"]=> string(15) "166 High Street" 
            ["address2"]=> string(0) "" 
            ["city"]=> string(6) "Barnet" 
            ["state"]=> string(5) "Herts" 
            ["zip"]=> string(6) "EN55XP" 
            ["phone"]=> string(0) "" 
            ["ext"]=> string(0) "" 
            ["email"]=> string(0) "" 
            ["fax"]=> string(0) "" 
            ["apptlength"]=> string(2) "15" 
            ["allowappts"]=> string(1) "1" 
            ["apptstarttime"]=> string(8) "09:00:00" 
            ["apptendtime"]=> string(8) "21:00:00" 
            ["allowwalkintans"]=> string(1) "0" 
            ["ApptLync"]=> string(1) "1" }

        */
    }

    /**
     * Function used to update the stores for use through out the dashboard
     * Uses the post array to receive data
     * 
     * @return boolean
     */
    function tfgg_ed_modify_stores_for_use(){

        $currentList = (array)get_option('tfgg_emp_dash_store_selection');
        $mod = $_POST['data']['modification'];
        $storecode = $_POST['data']['storeid'];

        if($mod==='1'){
            //adding the item
            if(!in_array($storecode, $currentList)){
                array_push($currentList, $storecode);
            }
        }else{
            //removing the item
            //wrapped in array_values to keep it non-associative
            //$currentList = array_values(array_filter($currentList, fn($m) => $m != $serviceId));
            if (($key = array_search($storecode, $currentList)) !== false) {
                unset($currentList[$key]);
            }
        }

        exit(json_encode(update_option('tfgg_emp_dash_store_selection', $currentList)));

    }
    add_action( 'wp_ajax_tfgg_ed_modify_stores_for_use', 'tfgg_ed_modify_stores_for_use' );

    /**
     * Used to return a list of 'allowed' stores from the SunLync API
     * 
     */
    function tfgg_ed_get_usable_stores(){
        $currentList = (array)get_option('tfgg_emp_dash_store_selection');//passed to the sunlync api

        $url= tfgg_ed_get_api_url().'TSunLyncAPI/CIPGetStoreDemoApptInfo/sStoreCode/nInAppts';
        $url=str_replace('sStoreCode',tfgg_ed_format_ids_for_api($currentList),$url);

        try{
            $data = tfgg_ed_execute_api_request('GET', $url, '');
        }catch(Exception $e){
            $result["results"]="error";
            $result["error_message"]=$e->getMessage(); 
            return json_encode($result);
        }

        if((property_exists($data[0],'ERROR'))||(property_exists($data[0],'WARNING'))){
            if(property_exists($data[0],'ERROR')){
                $result=array("results"=>"FAIL",
                    "response"=>$data[0]->ERROR);
            }else{
                $result=array("results"=>"FAIL",
                    "response"=>$data[0]->WARNING);
            }
            
            return json_encode($result);
        }else{

            $result["results"]="success";
            $stores=array_slice($data,1,-1);
            $stores=tfgg_ed_remove_closed_stores($stores);
            usort($stores,"tfgg_ed_sort_store_by_name");

            $result["stores"]=$stores;
            return json_encode($result);

        }
    }

    function tfgg_emp_dash_store_clockins($date, $storelist){
        $url = tfgg_ed_get_api_url();
        $url.='TSunLyncAPI/TFGG_GetStoresClockIns/sStoreList/sDate';
        $searchDate = $date->format('Y-m-d');

        $url=str_replace('sStoreList',$storelist,$url);
        $url=str_replace('sDate',$searchDate,$url);

        try{
            $data = tfgg_emp_dash_get_store_clockins($url);
        }catch(Exception $e){
            $result["results"]="error";
            $result["error_message"]=$e->getMessage(); 
            return json_encode($result);
        }

        return $data;

    }

    /**
     * There is an issue with the way the standard call to tfgg_ed_execute_api_request is removing
     * unneeded data in the return string that is invalidating the response from TFGG_GetStoresClockIns
     * 
     * This is a special GET request JUST for the purposes of calling TFGG_GetStoresClockIns
     * 
     * @param string $url the full URL to process the get request against
     * 
     * @return array
     */
    function tfgg_emp_dash_get_store_clockins($url){

        $url.='/'.get_option('tfgg_scp_api_mrkt'); 

        $ch = curl_init($url);
        //set all common options first
        curl_setopt($ch, CURLOPT_USERPWD,get_option('tfgg_scp_api_user').":".get_option('tfgg_scp_api_pass'));                                                                                                                  
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json'
        ));

        $result = curl_exec($ch);
        curl_close($ch);
		
		if(($result===FALSE)||($result=='')){
			throw new Exception("ERROR: Invalid URL");
			exit;
        }

        $data=str_replace('{"result":[','',$result);
        $data=str_replace(']]}',']',$data);
        $data=json_decode($data);
        $data=array_slice($data,1,-1);
        
        return $data;
    }
?>