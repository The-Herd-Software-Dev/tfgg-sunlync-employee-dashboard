/*global $*/
/*global jQuery*/
/*global localAccess*/

jQuery(function(){

    jQuery('.tfgg_ed_foruse_store_selection').on('click', function(){
        parentCard = jQuery(this).parent().parent();
        
        if(parentCard.hasClass('bg-light')){
            prevClass='bg-light';
        }else if(parentCard.hasClass('bg-success')){
            prevClass='bg-success';
        }
        parentCard.removeClass(prevClass);
        parentCard.addClass('bg-info');//mark as updating 
        checkbox = jQuery(this);
        
        var pathname = window.location.pathname;

        jQuery.post(localAccess.adminAjaxURL,{
            'action'    : 'tfgg_ed_modify_stores_for_use',
            'data'      : {storeid: jQuery(this).val(), 
                        modification:+jQuery(this).prop('checked')},
            'dataType'  : 'json',
            'pathname'  : pathname
        },function(data){            
            if(data==='true'){
                //successful update
                if(prevClass=='bg-success'){
                    parentCard.addClass('bg-light');  
                }else if(prevClass=='bg-light'){
                    parentCard.addClass('bg-success'); 
                }
                parentCard.removeClass('bg-info');
            }else{
                checkbox.prop('checked', !checkbox.is(':checked'));
                parentCard.removeClass('bg-info');
                parentCard.addClass(prevClass);
            }
        });
    });

});

function TestEmpDashAPICredentials(){
    jQuery("#tfgg-api-options-test-api-response").css('display','none');
    jQuery("#tfgg-api-options-test-api-response").removeClass('notice-error');
    jQuery("#tfgg-api-options-test-api-response").removeClass('notice-success');
    jQuery("#tfgg-api-test-response").text('');

    var pathname = window.location.pathname;
    
    jQuery.get(tfgg_emp_dash_scripts_common.ajaxurl,{
        'action'    : 'tfgg_ed_get_api_version',
        'dataType'  : 'json',
		'pathname'  : pathname
    },function(data){
        var obj = jQuery.parseJSON(data);
        
        if(obj["results"]=='success'){
            jQuery("#tfgg-api-options-test-api-response").addClass('notice-success');
            jQuery("#tfgg-api-test-response").text(obj['api_version']);
        }else{
            jQuery("#tfgg-api-options-test-api-response").addClass('notice-error');
            jQuery("#tfgg-api-test-response").text(obj['error_message']);
        }

        jQuery("#tfgg-api-options-test-api-response").css('display','block');
    });
}