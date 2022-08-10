<?php

    function display_tfgg_emp_dash_late_store_clockins(){


        (isset($_GET['late_clockin_date'])) ? $selectedDate = new DateTime($_GET['late_clockin_date']) : $selectedDate = new DateTime(); 
        $selectedDate->setTime(0,0,0,0);//make sure there is no time

        $lateClockIns = tfgg_emp_dash_store_clockins($selectedDate, tfgg_emp_dash_employee_storecodes_for_api());

    ?>
        <script>
            jQuery( function() {
                var now = new Date();
                jQuery( "#tfgg_ed_store_clock_in_date" ).datepicker({
                    changeMonth: true,
                    changeYear: true,
                    yearRange: "-1:+0",
                    maxDate: now,
                    showMonthAfterYear:true,
                    dateFormat: 'dd-mm-yy',
                    regional: 'en-GB',
                    defaultDate: new Date("<?php echo $selectedDate->format('Y').'-'.($selectedDate->format('n')-1).','.$selectedDate->format('d');?>"),
                    onSelect: function(date){                    
                        var formatted = date.split('-');
                        var selected = formatted[2]+'-'+formatted[1]+'-'+formatted[0];
                        var url = window.location.href.split('?')[0];
                        url+='?tfgg_ed_pg=lateClockIn&late_clockin_date='+selected;
                        window.location.replace(url);
                    }
                });
            });
        </script>
        <div class="row gutters">
            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                <h6 class="mb-3 tfgg-text-color-orange">Late store clock-ins</h6>
            </div>
        </div>    
        <div class="row gutters">
            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                <div class="form-check form-check-inline">     
                    <label for="tfgg_ed_store_clock_in_date"><?php _e('Viewing date'); ?>&nbsp;</label>                   
                    <input name="tfgg_ed_store_clock_in_date" id="tfgg_ed_store_clock_in_date" type="text" readonly="true" value="<?php echo $selectedDate->format('d-m-Y');?>"/>                        
                </div>
            </div>
        </div>
        <div class="row">&nbsp;</div>
    <?php
        $count=1;
        $needToCloseCardDeck=false;
        
        foreach($lateClockIns as $storeClockIn){
            
            if($storeClockIn->earliest_clockin<>'23:59:59'){
                
                $openTime = new DateTime($storeClockIn->open_time);
                $closeTime = new DateTime($storeClockIn->close_time);
                $earliest = new DateTime($storeClockIn->earliest_clockin);

                $secondsLate = $earliest->getTimestamp() - $openTime->getTimestamp();
                
                if(($storeClockIn->open==1)&&($secondsLate>0)){
                    //only show stores that were open for the date selected
                    //only show clock-ins that are over 60 seconds late
                    if($count==1){
                        //open the card-deck
                        $needToCloseCardDeck=true;
                    ?>
                        <div class="card-deck">
                    <?php
                    }
                    $earliestEmp = '';
                    //find the employee who clocked-in first
                    foreach($storeClockIn->clock_in_list as $clockIns){
                        if($clockIns->clock_in == $storeClockIn->earliest_clockin){
                            $earliestEmp = $clockIns->emp_first.' '.$clockIns->emp_last;
                        }
                    }

                    ?>

                        <div class="col-lg-3 col-md-6 col-sm-12" id="<?php echo $storeClockIn->storecode; ?>">
                            <div class="card bg-light mb-4">
                                <div class="card-header text-danger"><?php echo $storeClockIn->location; ?></div>
                                <div class="card-body">
                                    <p>Opening Time: <?php echo $openTime->format('h:i a'); ?><br/>
                                    Closing Time: <?php echo $closeTime->format('h:i a'); ?></p>
                                    <p>Earliest Clock In: <?php echo $earliest->format('h:i:s a'); ?><br/>
                                    Performed By: <?php echo $earliestEmp;?></p>
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

                }
            }
        }
        if($needToCloseCardDeck){
            ?>
            </div>
            <?php
        }

    }
?>