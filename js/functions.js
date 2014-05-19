function validateEmail(sEmail){var filter=/^([\w-\.]+)@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)|(([\w-]+\.)+))([a-zA-Z]{2,4}|[0-9]{1,3})(\]?)$/;if(filter.test(sEmail))return true;else return false;}

function saveForm(){
        var field_blank = 0;
        jQuery('#wait_setting_msg').removeClass('wpaf_highlight_error');
        jQuery('#success_setting_msg').removeClass('wpaf_highlight_error');
        jQuery('#failure_setting_msg').removeClass('wpaf_highlight_error');
        jQuery('#activation_email').removeClass('wpaf_highlight_error');
        jQuery('#destination_email').removeClass('wpaf_highlight_error');
         jQuery('#wpaf_error').removeClass('wpaf_error');
      
        var activation_email = jQuery('#activation_email').val();
        var destination_email = jQuery('#destination_email').val(); 
        
        var is_activation_email_valid = validateEmail(activation_email);
        var is_destination_email_valid = validateEmail(destination_email);
       
        if(jQuery.trim(activation_email) != ''){
          if(!is_activation_email_valid){
             jQuery('#activation_email').addClass('wpaf_highlight_error');
             jQuery('#wpaf_error').addClass('wpaf_error');
             jQuery('#wpaf_error').html('Enter valid email address');
             jQuery(window).scrollTop(0); 
             return false;
          }
        }
       if(jQuery.trim(destination_email) != ''){
         if(!is_destination_email_valid){
               jQuery('#destination_email').addClass('wpaf_highlight_error');
               jQuery('#wpaf_error').addClass('wpaf_error');
               jQuery('#wpaf_error').html('Enter valid email address');
               jQuery(window).scrollTop(0); 
               return false;
          }
       }   
      
      
        if((jQuery('#wait_setting_msg').val() == '') && jQuery('#wait_setting_1').is(':checked') == true ){
          field_blank = 1;
          jQuery('#wait_setting_msg').addClass('wpaf_highlight_error');
        }
        if((jQuery('#success_setting_msg').val() == '') && jQuery('#success_setting_1').is(':checked') == true ){
            field_blank = 1;
            jQuery('#success_setting_msg').addClass('wpaf_highlight_error');
        }
        if((jQuery('#failure_setting_msg').val() == '') && jQuery('#failure_setting_1').is(':checked') == true ){
           field_blank = 1;
           jQuery('#failure_setting_msg').addClass('wpaf_highlight_error');
      
        }

        if(field_blank == 1){
            jQuery('#wpaf_error').addClass('wpaf_error');
            jQuery('#wpaf_error').html('Either turn off the setting or enter a message');
            jQuery(window).scrollTop(0); 
            return false;
        }
       
  
        
        jQuery('#admin_ic_apply').submit();
}

function isNumberKey(evt)
{
   var charCode = (evt.which) ? evt.which : evt.keyCode;

   if(charCode!=8 || charCode!=32){
     if (charCode > 31 && (charCode < 48 || charCode > 57))
        return false;
   }
   return true;
}