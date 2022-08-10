<?php

    function display_tfgg_emp_dash_current_store_clockins(){
        $currentDate = new DateTime();
        $currentClockIns = tfgg_emp_dash_store_clockins($currentDate, tfgg_emp_dash_employee_storecodes_for_api());
    ?>

        <div class="row gutters">
            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                <h6 class="mb-3 tfgg-text-color-orange">Stores not currently clocked in: <?php echo $currentDate->format('d/m/Y H:i a'); ?></h6>
            </div>
        </div>      

    <?php
        $count=1;
        $openClockedIn=0;
        $storecount=0;
        $stores_notopen=0;
        $notOpenToday=0;
        $lastWeek = new DateTime();
        $lastWeek->modify('-1 week');
        $needToCloseCardDeck=false;

        foreach($currentClockIns as $storeClockIn){
            if(($storeClockIn->earliest_clockin=='23:59:59')&&($storeClockIn->open==1)){
                //only show currently open stores with no clockin 
                $openTime = new DateTime($storeClockIn->open_time);
                $closeTime = new DateTime($storeClockIn->close_time);
                if($openTime->format('h:i:s') < $currentDate->format('h:i:s')){
                    //store should be open
                    $storecount++;
                    $earliest = new DateTime($storeClockIn->earliest_clockin);
                    $warningText = 'No clock in recorded';//$default
                    $bgColor = 'bg-danger';

                    if($count==1){
                        //open the card-deck
                        $needToCloseCardDeck=true;
                    ?>
                        <div class="card-deck">
                    <?php
                    }

                    if($earliest<$openTime){
                        $bgColor = 'bg-success'; //clocked in early
                    }

                    if(sizeof($storeClockIn->prev_clock_in_list)>0){
                        //check if the current clock-in is there from within the past 7 days
                        foreach($storeClockIn->prev_clock_in_list as $clockIns){
                            $prevClockIn = new DateTime($clockIns->clock_in_date);
                            
                            if($prevClockIn < $lastWeek){
                                break;//over 7 days ago, no use to us
                            }else{
                                $bgColor = 'bg-warning'; 
                                $warningText = 'Clock in exists from '.$prevClockIn->format('d/m/Y');
                            }
                        }
                    }
                    ?>

                    <div class="col-lg-3 col-md-6 col-sm-12" id="<?php echo $storeClockIn->storecode; ?>">
                        <div class="card bg-light mb-4">
                            <div class="card-header <?php echo $bgColor; ?> text-white"><?php echo $storeClockIn->location; ?></div>
                            <div class="card-body">
                                <p>Opening Time: <?php echo $openTime->format('h:i a'); ?><br/>
                                Closing Time: <?php echo $closeTime->format('h:i a'); ?></p>
                                <p><?php echo $warningText;?></p>
                            </div>
                            <div class="card-footer text-right">
                                
                            </div>
                        </div>   
                    </div>

                    <?php

                    if($count==4){
                        //close the card-deck
                        $needToCloseCardDeck=false;
                    ?>
                        </div>
                    <?php
                        $count=1;
                    }else{
                        $count++;
                    }


                }else{
                    $stores_notopen++;
                }
            }else{
                if($storeClockIn->open==0){
                    $notOpenToday++;
                }else{                    
                    $openClockedIn++;
                }
            }
        }
        if($needToCloseCardDeck){
            ?>
            </div>
            <?php
        }
        ?>
        <div class="card-deck">
            <div class="col-lg-3 col-md-6 col-sm-12">
                <div class="card bg-light mb-4">
                    <div class="card-header">Total Store Count</div>
                    <div class="card-body">
                        <p>Total Stores: <?php echo sizeof($currentClockIns); ?></p>
                        <p>Open and not clocked in: <?php echo $storecount;?></p>
                        <p>Open and clocked in: <?php echo $openClockedIn;?></p>
                        <p>Not yet open: <?php echo $stores_notopen;?></p>
                        <p>Not open for business: <?php echo $notOpenToday;?></p>
                    </div>
                    <div class="card-footer text-right">
                        
                    </div>
                </div>   
            </div>
        </div>
        <?php
    }

?>