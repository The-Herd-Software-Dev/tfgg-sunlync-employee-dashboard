<?php
    /*
        misc. util functions used through out
    */

    /**
     * Used to hash a plain string to the one way hased value sunlync stores
     * 
     * @param string $plaintext the plaintext string to hash
     * @return string the hashed string
     */
    function tfgg_emp_dash_hash_password($plaintext){
        return StrToUpper(MD5(StrToUpper(trim($plaintext))));
    }

    /**
     * function to write to a debug log
     * 
     * @param string $message the message to be written to the log
     */
    function tfgg_ed_log_me($message) {
        if ( WP_DEBUG === true ) {
            $default = ini_get('error_log');
            ini_set( 'error_log', WP_CONTENT_DIR . '/debug.log' );
            if ( is_array($message) ){
                //$message = implode('","', $message);
                //error_log($message);
                error_log( print_r($message, true) );
            }elseif ( is_object($message) ) {
                error_log( print_r($message, true) );
            } else {
                error_log( $message );
            }
            ini_set('error_log',$default);
        }
    }

    /**
     * Helper function to remove stores that have CLOSED or DELETED in their location fields
     * @param array $storeList
     * 
     * @return array
     */
    function tfgg_ed_remove_closed_stores($storesList){
        $filteredStores = array();

        foreach($storesList as $details){
            if(
                (!str_contains(strtoupper($details->store_loc), 'CLOSED')) && 
                (!str_contains(strtoupper($details->store_loc), 'DELETED'))
            ){
                array_push($filteredStores, $details);
            }
        }

        return $filteredStores;
    }

    /**
     * Helper function to remove unwanted slashes in a string
     * @param string $str the string to be modified
     * 
     * @return string the modified string
     */
    function tfgg_ed_remove_slashes($str){
        $str = str_replace('/','',$str);
        $str = stripslashes($str);
        return $str;
    }

    /**
     * Helper function to left pad identifiers to match the SunLync 0000000000 format
     * @param string $s the string to left padded with 0s
     * 
     * @return string
     */
    function tfgg_ed_pad_str_to_sunlync($s){
        return str_pad($s,10,'0',STR_PAD_LEFT);
    }

    /**
     * usort function to order stores by location name asc
     * 
     * @param object $a store A
     * @param object $b store B
     * 
     * @return int the result of comparing A to B
     */
    function tfgg_ed_sort_store_by_name($a,$b){
        return strcmp($a->store_loc, $b->store_loc);
    }

    /**
     * helper function to format sunlync ids into a list that can passed to the SunLync API
     * 
     * @param array $idList the list of ids to be formatted to the api query
     * 
     * @return string
     */
    function tfgg_ed_format_ids_for_api($idList){
        if($idList<>''){
            $idSelected = join('","',$idList);   
            return '"'.$idSelected.'"';    
        }else{
            return '';
        }
    }

?>