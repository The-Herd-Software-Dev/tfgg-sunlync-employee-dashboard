<?php
    define('TFGG_EMP_DASH_STORE_SELECTION_SECTION','tfgg_emp_dash_store_selection_section');
    define('TFGG_EMP_DASH_SCP_STORE_SELECTION_OPTIONS','tfgg-emp-dash-store-selection');

    function tfgg_emp_dash_admin_store_selection_options(){
        add_settings_section(TFGG_EMP_DASH_STORE_SELECTION_SECTION, '', null, TFGG_EMP_DASH_SCP_STORE_SELECTION_OPTIONS);

        add_settings_field("tfgg_emp_dash_store_selection","Store Selection:","display_tfgg_emp_dash_store_selection", TFGG_EMP_DASH_SCP_STORE_SELECTION_OPTIONS, TFGG_EMP_DASH_STORE_SELECTION_SECTION);
        register_setting(TFGG_EMP_DASH_SCP_STORE_SELECTION_OPTIONS,TFGG_EMP_DASH_STORE_SELECTION_SECTION);
    }

    function tfgg_emp_dash_admin_stores_to_use(){
        tfgg_emp_dash_admin_menu_header();
        ?>

        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-11 col-md-8">
                    <div class="card tfgg-emp-dash-card">
                        <div class="card-header"><h5>Stores To Use</h5></div>
                        <div class="card-body"> 
                            <p class="card-text">Please select the stores you wish users to be able to select throughout this portal</p>
                            <p class="card-text">Checking the box will automatically set store for use / not for use</p>
                            <p class="card-text">If no stores are selected, all stores returned from the API will be used (barring stores containing "CLOSED" or "DELETED" in their description)</p>                                                                            
                            <hr/>
                            <?php
                                display_tfgg_emp_dash_store_selection();
                            ?>
                        </div>
                        <div class="card-footer">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <?php
    }

    function display_tfgg_emp_dash_store_selection(){
        $storeList = json_decode(tfgg_ed_api_get_unfiltered_stores());

        if(StrToUpper($storeList->results)==='SUCCESS'){
            $selectedStores = (array)get_option('tfgg_emp_dash_store_selection');
            $rowCounter = 1;//init the counter

            foreach($storeList->stores as $details){
                if($rowCounter==1){
                    //reset the row container
                    ?>
                    <div class="row">
                    <?php
                }

                if(in_array($details->store_id, $selectedStores)){ 
                    $isChecked = 'checked="checked"'; 
                    $titleBg='bg-success';
                }else{ 
                    $isChecked = ''; 
                    $titleBg='bg-light';
                }

                ?>
                    <div class="col">
                    <div class="card tfgg-emp-dash-card mb3 <?php echo $titleBg;?>" style="font-size:0.75rem">
                        <div class="card-header">
                            <input type="checkbox" 
                                class="tfgg_ed_foruse_store_selection" 
                                id="ed_store_<?php  echo $details->store_id;?>" 
                                value="<?php echo $details->store_id;?>"  
                                name="tfgg_emp_dash_store_selection[]" 
                                <?php echo $isChecked;?>/>
                            <?php echo $details->store_loc; ?>
                        </div>
                    </div>
                    </div>

                <?php

                if($rowCounter / 4==1){
                    //close the row container
                    ?>
                    </div><br/>
                    <?php
                    $rowCounter=1;
                }else{
                    $rowCounter++;
                }
            }

        }else{
        ?>
            <div class="notice notice-error">Unable to retrieve your store list, please ensure your API credentials are setup</div>
        <?php
        }
    }

?>