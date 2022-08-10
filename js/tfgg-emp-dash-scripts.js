/*global $*/
/*global jQuery*/
/*global tfgg_emp_dash_scripts_common*/

jQuery(function(){

});

function tfggEmpDashEmployeeLogin(){
    event.preventDefault();
    jQuery('.tfgg-ed-busy').toggle();
    jQuery('.tfgg_ed_loginform').toggle();
    jQuery('.tfgg-ed-login-result').hide(); 
    var thisAlert = jQuery('#tfgg-ed-login-response-alert');
    thisAlert.removeClass('alert-danger alert-warning alert-success');

    var userpass = jQuery('#tfgg-ed-login-pass').val();
    jQuery('#tfgg-ed-login-pass').val('');

    var pathname = window.location.pathname;

    jQuery.post(tfgg_emp_dash_scripts_common.ajaxurl,{
        'action'    : 'tfgg_emp_dash_process_login_form_request',
        'data'      : {
            username: jQuery('#tfgg-ed-login-user').val(), 
            password: userpass,
            wpnonce:  jQuery('#tfgg-ed-login-nonce').val(),
                },
        'dataType'  : 'json',
        'pathname'  : pathname
    },function(data){      
        //console.log(data);
        var obj = jQuery.parseJSON(data);

        if(obj.result == true){
            thisAlert.addClass('alert-success');
            thisAlert.html('Login Success - Redirecting to dashboard');
            jQuery('#tfgg_ed_retry_login').hide();
            setTimeout(function(){
                location.href =  window.location.href;
            }, 2000);
        }else{            
            thisAlert.addClass('alert-danger');
            thisAlert.html('Login Failed - please try again');  
        }
        jQuery('.tfgg-ed-login-result').show();
        jQuery('.tfgg-ed-busy').toggle();
    });
    
}

function tfggEmpDashRetryLogin(){
    jQuery('.tfgg-ed-busy').hide(); 
    jQuery('.tfgg-ed-login-result').hide(); 
    jQuery('.tfgg_ed_loginform').show();
}

function tfggEmpDashEndEmployeeSessions(){
    event.preventDefault();
    jQuery.get(tfgg_emp_dash_scripts_common.ajaxurl,{
        'action'    : 'tfgg_emp_dash_logout',
        'dataType'  : 'json',
		'pathname'  : window.location.pathname
    },function(data){
        var obj = jQuery.parseJSON(data);
        window.location.replace(obj["logout"]);
    });
}

function tfggDashboardRefresh(){
    window.location.reload();
}

function tfggDashboardNavCurrentClockIns(){
    var url = window.location.href.split('?')[0];
    window.location.replace(url);
}

function tfggDashboardNavLateClockIns(){
    var url = window.location.href.split('?')[0];
    url+='/?tfgg_ed_pg=lateClockIn';

    window.location.replace(url);
}