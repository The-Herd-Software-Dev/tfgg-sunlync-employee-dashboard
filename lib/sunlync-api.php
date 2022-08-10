<?php
    /*
        everything required to communicate with the SunLync API
    */

    /**
     * Helper function to return the constructed API url
     * 
     * @return string $url - the SunLync API URL
     */
    function tfgg_ed_get_api_url(){
        //we are not adding the class (TSunlyncAPI) in this call since there are now multiple (TSunLyncAPI / TCart etc)
        return get_option('tfgg_emp_dash_api_protocol').'://'.get_option('tfgg_scp_api_url').':'.get_option('tfgg_scp_api_port').'/datasnap/rest/';    
    }

    /**
     * Helper function used to execute a request against the SunLync API
     * @param string $method the HTTP method to be used, typically either GET or POST
     * @param string $url the url for the SunLync API
     * @param mixed $data any data to be sent to the API as part of the URL, or, the POST body
     * 
     * @return array array or object
     */
    function tfgg_ed_execute_api_request($method, $url, $data){
        if(!strpos($url,'GenericGetAPIVersion')){
            $url.='/'.get_option('tfgg_scp_api_mrkt'); 
        }
        
        $ch = curl_init($url);
        //set all common options first
        curl_setopt($ch, CURLOPT_USERPWD,get_option('tfgg_scp_api_user').":".get_option('tfgg_scp_api_pass'));                                                                                                                  
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        
        switch (StrToUpper($method)){
            case "GET":
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
                curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                    'Content-Type: application/json'
                ));
                break;
            case "POST":
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");                                                                    
                curl_setopt($ch, CURLOPT_POSTFIELDS, $data); 
                curl_setopt($ch, CURLOPT_HTTPHEADER, array(                                                                          
                    'Content-Type: application/json',                                                                                
                    'Content-Length: ' . strlen($data))                                                                       
                ); 
                break; 
            case "DELETE":
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE"); 
                curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                    'Content-Type: application/json'
                ));
                break;
            case "PUT":
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT"); 
                curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                    'Content-Type: application/json'
                ));
                break;                
        }//switch
        
        $result = curl_exec($ch);
        curl_close($ch);
		
		if(($result===FALSE)||($result=='')){
			throw new Exception("ERROR: Invalid URL");
			exit;
        }

        //strip data from non-cart items
        if(strpos($url,'TSunLyncAPI')){
            $result=str_replace('{"result":[','',$result);
            $result=str_replace(']}','',$result);   
        }
        
        return json_decode($result);
    }

    /**
     * Used to test the SunLync API connection by returning the API Version
     * 
     * @return {json}
     */
    function tfgg_ed_get_api_version(){
        $url = tfgg_ed_get_api_url();
        $url.='TSunLyncAPI/GenericGetAPIVersion';
        //echo $url;
        
        try{
            $data = tfgg_ed_execute_api_request('GET',$url,'');
            
        }catch(Exception $e){
            $result["results"]="error";
            $result["error_message"]=$e->getMessage(); 
            exit(json_encode($result));
        }
        $result=array();
        $result["results"]="success";
        $result["api_version"]=$data[0]->result;
        exit(json_encode($result));
        
    }
    add_action( 'wp_ajax_tfgg_ed_get_api_version', 'tfgg_ed_get_api_version' );
?>