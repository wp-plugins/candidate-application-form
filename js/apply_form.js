/*File: apply_script.js
  Use:  On click of the apply button on the Apply to Job form a ajax request is triggered to send a post to the on_ic_apply function in apply_form.php. The action in the data parameters determine the function which will be called.
*/  


jQuery(document).ready( function() {
  
   var form_count = 1;
   jQuery(".af_clss").each(function() { 
       var dialog_elm = "dialog_"+form_count;      /*dialog id*/
       var applybtn_elm = "afbtn_"+form_count;     /*apply form button */
       var form_elm = 'afform_'+form_count;         /*form id */
             
       jQuery(this).find(".dialog").attr("id",dialog_elm);  
       jQuery(this).find(".applybtn").attr("id",applybtn_elm);    
       jQuery(this).attr("id",form_elm);  
       form_count++;
   });
   jQuery(".apply_form").click( function() {       
   
     /*Check the validation code*/  
     var this_elm = jQuery(this);  
     var apply_btn_id = jQuery(this_elm).attr('id');
     var btn_arr = new Array();
     btn_arr = apply_btn_id.split("_");
     var form_dialog = "#dialog_"+btn_arr[1]; 
     var form_id = "#afform_"+btn_arr[1]; 
     jQuery(form_id).find(".wp_af_validation_error").remove(); 
     post_id = jQuery(this_elm).attr("data-post_id");
     nonce = jQuery(this_elm).attr("data-nonce"); 
     var form_data1 = jQuery(form_id).serialize();      
     /*   jQuery(form_dialog).dialog("open");   */  
         

    jQuery.ajax({
       type : "post",
       dataType : "json",
       url :  myAjax.ajaxurl,
       data : form_data1+'&validation_mode=1&action=submit_ic-application&post_id='+post_id+'&nonce='+nonce,
       async: false,  
       success: function(response) {
            
        if(response['invalid']){
                  jQuery.each(response.invalid, function(i, n) {
                    jQuery(form_id).find("input[name='"+i+"']").parent().append('<span class="wp_af_validation_error">'+n+'</span>');
                      	});
                   /*also check for file response */
                     if(response['file_response']){
                        jQuery.each(response.file_response, function(i, n) {
                            /*for file upload files are not uploaded so we need an additional check */
                            if(i.substring(0,16) == "file_upload_path"){  
                               var container_id = jQuery(form_id).find("input[name='"+i+"']").parent().attr("id");
                               var container_arr = new Array();
                               container_arr = container_id.split("_");
                               var invalid_file_value = jQuery("#file_select_err_"+container_arr[1]).html();   
                               if(invalid_file_value == 'Invalid file' || invalid_file_value == ''){
                                  jQuery(form_id).find("input[name='"+i+"']").parent().parent().append('<span class="wp_af_validation_error">'+n+'</span>');
                                  return false;
                               }
                                 
                            }
      
                       });
                     } 
                   
                   /*end of file response check */     
                        
                        
                        
        
        }else{ 
          var err_count = 0;
          if(response['file_response']){
          
                  jQuery.each(response.file_response, function(i, n) {
                      /*for file upload files are not uploaded so we need an additional check */
                      if(i.substring(0,16) == "file_upload_path"){  
                         var container_id = jQuery(form_id).find("input[name='"+i+"']").parent().attr("id");
                         var container_arr = new Array();
                         container_arr = container_id.split("_");
                         var invalid_file_value = jQuery("#file_select_err_"+container_arr[1]).html();   
                         if(invalid_file_value == ''){
                            jQuery(form_id).find("input[name='"+i+"']").parent().parent().append('<span class="wp_af_validation_error">'+n+'</span>');
                            err_count++;
                        
                         }
                         else if(invalid_file_value == 'Invalid file'){
                            jQuery(form_id).find("input[name='"+i+"']").parent().parent().append('<span class="wp_af_validation_error">'+n+'</span>');
                          err_count++; 
                         }
                           
                      }

                 });
                 
              
                 
         }
         if(err_count > 0){
             return false;
         }                  
     
            
     
     /*Commented the dialog */     
    jQuery(form_dialog).dialog("open");
    
    var multiple_upldprev_elm = jQuery(".multiple_upldprev");
          jQuery(form_id).find(".multiple_upldprev").each(function() {     // perform function for each element
          var element = jQuery(this);         // get jquery object for the current element
          var id = element.attr("id");   // get the id
          jQuery("#"+id).trigger("click");  
      });
      
                 

     
   setTimeout(function() {
        // Do something after 5 seconds
       
         /*To check and update dialog box if file upload is complete */ 
         var upload_count = 0;
         jQuery(form_id).find(".uploaded_file_path_class").each(function() { 
           /* console.log('up = '+jQuery.trim(jQuery(this).val())); */
         var condition_counter = 0;
          if(jQuery.trim(jQuery(this).val()) == 'undefined'){
               condition_counter++;
          }
          if(jQuery.trim(jQuery(this).val()) == ''){
               condition_counter++;
          }
          
          if(condition_counter == 0){
               upload_count++
           }
         });
         
         var added_file_count = 0;
          jQuery(form_id).find(".span_error_class").each(function() {
              /*        console.log('add = '+jQuery.trim(jQuery(this).html()));    */
          var condition_counter = 0;
          if(jQuery.trim(jQuery(this).html()) == 'undefined'){
               condition_counter++;
          }
          if(jQuery.trim(jQuery(this).html()) == ''){
               condition_counter++;
          }
          if(jQuery.trim(jQuery(this).html()) == 'Invalid file'){
               condition_counter++;
          }
          
          
          if(condition_counter == 0){
               added_file_count++
           }
    
         });
     
   /*   console.log('upload_count'+upload_count);
     console.log('added_file_count'+added_file_count);  */
     
         if((upload_count >0) && (upload_count ==  added_file_count)){    
            if(AF.DEBUG_MODE == 'on'){ 
              jQuery(form_dialog).append('<p class="dialog_elements">File upload complete </p>');
            }   
         }else if(added_file_count != 0){
           if(AF.DEBUG_MODE == 'on'){ 
              jQuery(form_dialog).append('<p class="dialog_elements">File upload process incomplete </p>').dialog(); 
           }
         } 
         if(myAjax.script_in_use == 'ic_api_script.php'){
             var total_size = jQuery("#total_file_size").val();
             var converted_value = bytesToSize(total_size);
             var time_reqd;
             var timeout_val; 
            
             if(total_size >=  0 && total_size <= 2097152){    /* 0 - 2 mb */
                    timeout_val = 180;
             }else if(total_size >= 2097152 && total_size <= 4194304){   /* 2 - 4 mb */
                    timeout_val = 240;
             }else if(total_size >= 4194304 && total_size <= 6291456){  /* 4 - 6 mb */
                    timeout_val = 300;
             } 
             
             time_reqd = 90 + parseInt(timeout_val);
             var message_val = '<p class="dialog_elements">Please wait while we transfer '+converted_value+' of files. This may take up to '+time_reqd+' seconds depending on internet speeds</p>';
       
             if(AF.DEBUG_MODE == 'on'){ 
                jQuery(form_dialog).append(message_val).dialog(); 
             }
             else{
                jQuery(form_dialog).html(message_val).dialog(); 
             }
        }     
          
      /*End of file upload checking code*/ 
        
        /*fetch the form data to submit the details to the api scripts*/ 
         var form_data = jQuery(form_id).serialize();   
         
        setTimeout(function() {
         jQuery.ajax({
             type : "post",
             dataType : "json",
             url :  myAjax.ajaxurl,
             data : form_data+'&action=submit_ic-application&post_id='+post_id+'&nonce='+nonce,
             async: false,  
             success: function(response) {
      
               /*handles debug messages*/
                 if(response['debug']){
                     var x = 0;
                      for(var x in response['debug']){
                         jQuery(form_dialog).append('<p class="dialog_elements">'+response['debug'][x]+'</p>');  
                         x++   
                      }
                 
                 }
                 else if(response['response']){    /*handles response messages or when debug mode is off*/
                      var x = 0;
                      for(var x in response['response']){
                        if(x == 0){
                            jQuery(form_dialog).html('<p class="dialog_elements">'+response['response'][x]+'</p>').dialog();   
                        }else{
                          jQuery(form_dialog).append('<p class="dialog_elements">'+response['response'][x]+'</p>');  
                         }
                         
                         x++   
                      }
                 
                }  
                
                /*Reset the form */
                
                 
            }       
      
          });
        }, 2000);
      
    }, 5000);
    
     }
    
    }//end of success    
    
    }); /*end of when   */
    
     
   });
   
   
   /*on dialog close*/
     jQuery(".dialog").on( "dialogclose", function( event, ui ) {
       if(event.cancelable){  /*if the X button is clicked */
          jQuery('.dialog p').remove();  
          jQuery(".af_clss")[0].reset();
          jQuery(".span_error_class").each(function() {  
                     var elm_id = jQuery(this).attr("id"); 
                     jQuery("#"+elm_id).empty();  
          });
          jQuery(".wp_af_validation_error").empty();  
       }
     });

})

 function bytesToSize(bytes) {
    var sizes = ['Bytes', 'KB', 'MB', 'GB', 'TB'];
    if (bytes == 0) return 'n/a';
    var i = parseInt(Math.floor(Math.log(bytes) / Math.log(1024)));
    if (i == 0) return bytes + ' ' + sizes[i];
    return (bytes / Math.pow(1024, i)).toFixed(1) + ' ' + sizes[i];
};
