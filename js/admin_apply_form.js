/*File: admin_apply_form.js
  Use:  On click of the apply button on the Apply to Job form a ajax request is triggered to send a post to the on_ic_apply function in apply_form.php. The action in the data parameters determine the function which will be called.
*/  


jQuery(document).ready( function() {
   jQuery(".available_fields").draggable({
      connectToSortable:  "#wp_apply_flds,#wp_ai_flds",    
      helper: "clone",
      revert: "valid",
       opacity: 0.35,  
       zIndex: 100000,
       appendTo: "body" 
     });
     
     jQuery("#wp_apply_flds").droppable({
           accept: ".available_fields",
            drop: function( event, ui ) {  
                 var drag_element = (ui.draggable).clone();
                 
                jQuery(drag_element).find('.temp_cls1').attr('name', 'af_field[]');
                jQuery(drag_element).find('.temp_cls2').attr('name', 'af_title[]');
           
                jQuery(drag_element).insertAfter("#wp_apply_flds #mCSB_2 .mCSB_container ul li:last-child");  
            
                jQuery("#wp_apply_flds").mCustomScrollbar("update");
                jQuery(ui.helper).remove(); //destroy clone
                jQuery(ui.draggable).remove(); //remove from list    
              
                jQuery(".available_fields").draggable({
                connectToSortable:  "#wp_ai_flds",    
                helper: "clone",
                revert: "valid",
                 opacity: 0.35,  
                 zIndex: 100000,
                 appendTo: "body" 
               }); 
               
                 jQuery("#wp_ai_flds").droppable({
           accept: ".available_fields",
            drop: function( event, ui ) {  
                  jQuery((ui.draggable).clone()).insertAfter("#wp_ai_flds #mCSB_1 .mCSB_container ul li:last-child");
                  
                  /*jQuery(this).find('.post_fields').remove();  */
                  jQuery(this).find('.temp_cls1').attr('name', 'av_field[]');
                  jQuery(this).find('.temp_cls2').attr('name', 'av_title[]');
                  
                  /*Initialize find on the new elements: code same as find li code on load dragged after being dropped once*/
          
                   jQuery("#wp_ai_flds").find('li').each(function(){   
                        jQuery(this).unbind( "click" );  
                    }); 
                  
                   jQuery("#wp_ai_flds").find('li').each(function(){   
                            jQuery(this).click( function() {
                            /*   alert('in droppable - 1');   */
                                  jQuery(this).updateDialog(this);
                                 
                                   });
             }); 
             
             /*end of initialization */    
                  
                  
                  
                  
                  
                  
                  jQuery("#wp_ai_flds").mCustomScrollbar("update");
                  jQuery(ui.helper).remove(); //destroy clone
                  jQuery(ui.draggable).remove(); //remove from list
                  jQuery(".available_fields").draggable({
                connectToSortable:  "#wp_ai_flds",    
                helper: "clone",
                revert: "valid",
                 opacity: 0.35,  
                 zIndex: 100000,
                 appendTo: "body" 
               }); 
            
             
             }});
                

                    
              },
      
      });
      
      /*new droppable*/
      jQuery("#wp_ai_flds").droppable({
           accept: ".available_fields",
            drop: function( event, ui ) {  
                  jQuery((ui.draggable).clone()).insertAfter("#wp_ai_flds #mCSB_1 .mCSB_container ul li:last-child");
                  
                  /*jQuery(this).find('.post_fields').remove();  */
                  jQuery(this).find('.temp_cls1').attr('name', 'av_field[]');
                  jQuery(this).find('.temp_cls2').attr('name', 'av_title[]');
                  
         /*Initialize find on the new elements: code same as find li code on load*/
          
       jQuery("#wp_ai_flds").find('li').each(function(){   
            jQuery(this).unbind( "click" );  
        }); 
      
       jQuery("#wp_ai_flds").find('li').each(function(){   
                jQuery(this).click( function() {
                        /* alert('in droppable - 2');*/
                         jQuery(this).updateDialog(this);                     
                       });
 }); 
             
             /*end of initialization */    
                  
                  
                  
                  
                  jQuery("#wp_ai_flds").mCustomScrollbar("update");
                  jQuery(ui.helper).remove(); //destroy clone
                  jQuery(ui.draggable).remove(); //remove from list
 
                  
                  
                  
             jQuery(".available_fields").draggable({
                connectToSortable:  "#wp_ai_flds",    
                helper: "clone",
                revert: "valid",
                 opacity: 0.35,  
                 zIndex: 100000,
                 appendTo: "body" 
               }); 
            
             
      }});
  /*end droppable*/
      

   jQuery("#add_new_button").click( function() {
      jQuery("#dialog" ).dialog( "open" ); 
      jQuery("#dialog").append("<form id='form_dialog'><table class='wpaf_dialog_table'><tr><td width='22px'><span class='wpaf_tooltip trigger'  onmouseover = 'OpenDiv(\""+afTooltip.FIELD_TYPE_TOOLTIP+"\");'>?</span></td><td width='110px'><span class='dialog_flds'>Field Type:</span></td><td><select id='new_field' name='new_field' style='width:180px;'><option value='Text'>Text</option><option value='Upload_CV'>Upload - CV</option><option value='Upload_Other'>Upload - Other</option><option value='LongText'>Long Text</option><option value='Numeric'>Numeric</option><option value='Email'>Email</option></select></td></tr><tr><td><span class='wpaf_tooltip trigger'  onmouseover = 'OpenDiv(\""+afTooltip.REQUIRED_TOOLTIP+"\");'>?</span></td><td><span class='dialog_flds'>Required:</span></td><td><input id='required_field' type='checkbox' name='required_field' value='1'></td></tr><tr><td><span class='wpaf_tooltip trigger'  onmouseover = 'OpenDiv(\""+afTooltip.TITLE_OF_FIELD_TOOLTIP+"\");'>?</span></td><td><span class='dialog_flds'>Title of field:</span></td><td><input type='text' name='field_title' id='field_title' /></td></tr></table></form>");
      })
 
   jQuery("#wp_ai_flds").find('li').each(function(){     
     jQuery(this).click( function() {
        /* alert('in droppable - 3');*/
         jQuery(this).updateDialog(this);

      });
  }); 
   

  jQuery("#dialog").on( "dialogclose", function( event, ui ) {
     if(event.cancelable){
         jQuery("#dialog").html(''); 
     }else{           
        var new_field = jQuery('#new_field').val();
        var required_field = jQuery('#required_field:checked').val();
        var field_title = jQuery('#field_title').val();
        var compulsory = '';
        
        if(required_field == 1){
         compulsory += '<span style="color:red;">*</span>';
        }else{
           required_field = 0;
        }
   
      
      jQuery("<li class='available_fields'><label>"+compulsory+" "+new_field+":"+field_title+" </label><input name='av_field[]' class='temp_cls1' type='hidden'  value='"+new_field+":"+required_field+"' /><input name='av_title[]' class='temp_cls2' type='hidden' value='"+field_title+"'></li>").insertAfter("#mCSB_1 .mCSB_container ul li:last-child");   
          
         jQuery("#wp_ai_flds").mCustomScrollbar("update"); 
         
         
     /*Add drag and drop feature to the Available parameter */
     jQuery(".available_fields").draggable({
      connectToSortable:  "#wp_apply_flds",    
      helper: "clone",
      revert: "valid",
       opacity: 0.35,  
       zIndex: 100000,
       appendTo: "body" 
     });
     
     
     /////////Droppable /////
            jQuery("#wp_apply_flds").droppable({
           accept: ".available_fields",
            drop: function( event, ui ) {  
                 var drag_element = (ui.draggable).clone();
                 
                jQuery(drag_element).find('.temp_cls1').attr('name', 'af_field[]');
                jQuery(drag_element).find('.temp_cls2').attr('name', 'af_title[]');
           
                jQuery(drag_element).insertAfter("#wp_apply_flds #mCSB_2 .mCSB_container ul li:last-child");  
            
                jQuery("#wp_apply_flds").mCustomScrollbar("update");
                jQuery(ui.helper).remove(); //destroy clone
                jQuery(ui.draggable).remove(); //remove from list    
              
                jQuery(".available_fields").draggable({
                connectToSortable:  "#wp_ai_flds",    
                helper: "clone",
                revert: "valid",
                 opacity: 0.35,  
                 zIndex: 100000,
                 appendTo: "body" 
               }); 
               
                 jQuery("#wp_ai_flds").droppable({
           accept: ".available_fields",
            drop: function( event, ui ) {  
                  jQuery((ui.draggable).clone()).insertAfter("#wp_ai_flds #mCSB_1 .mCSB_container ul li:last-child");
                  
                  /*jQuery(this).find('.post_fields').remove();  */
                  jQuery(this).find('.temp_cls1').attr('name', 'av_field[]');
                  jQuery(this).find('.temp_cls2').attr('name', 'av_title[]');
                 
                  /*Initialize find on the new elements: code same as find li code on load***/
          
                   jQuery("#wp_ai_flds").find('li').each(function(){   
                          jQuery(this).unbind( "click" );  
                      }); 
                    
                     jQuery("#wp_ai_flds").find('li').each(function(){   
                              jQuery(this).click( function() {
                               /*   alert('in droppable - 4');*/
                                  jQuery(this).updateDialog(this);
                                  
                               });
               }); 
             
             /*end of initialization */    
                 
                  
                  jQuery("#wp_ai_flds").mCustomScrollbar("update");
                  jQuery(ui.helper).remove(); //destroy clone
                  jQuery(ui.draggable).remove(); //remove from list
                  jQuery(".available_fields").draggable({
                connectToSortable:  "#wp_ai_flds",    
                helper: "clone",
                revert: "valid",
                 opacity: 0.35,  
                 zIndex: 100000,
                 appendTo: "body" 
               }); 
            
             
             }});
                

                    
              },
      
      }); 
   }         

  });
  

   
  jQuery("#edit_dialog").on( "dialogclose", function( event, ui ) {
     if(event.cancelable){
         jQuery("#dialog").html(''); 
     }else{   
        var new_field = jQuery('#new_field').val();
        var required_field = jQuery('#required_field:checked').val();
        var field_title = jQuery('#field_title').val();
        var compulsory = '';
        
        if(required_field == 1){
         compulsory += '<span style="color:red;">*</span>';
        }else{
           required_field = 0;
        }
   
      jQuery('#pointer').html("<label>"+compulsory+" "+new_field+":"+field_title+" </label><input name='av_field[]' class='temp_cls1' type='hidden'  value='"+new_field+":"+required_field+"' /><input name='av_title[]' class='temp_cls2' type='hidden' value='"+field_title+"'>");
      jQuery('#pointer').removeAttr('id'); 
       

          
         jQuery("#wp_ai_flds").mCustomScrollbar("update"); 
         
         
     /*Add drag and drop feature to the Available parameter */
     jQuery(".available_fields").draggable({
      connectToSortable:  "#wp_apply_flds",    
      helper: "clone",
      revert: "valid",
       opacity: 0.35,  
       zIndex: 100000,
       appendTo: "body" 
     });
     
     
     /////////Droppable /////
     jQuery("#wp_apply_flds").droppable({
           accept: ".available_fields",
            drop: function( event, ui ) {  
                 var drag_element = (ui.draggable).clone();
                 
                jQuery(drag_element).find('.temp_cls1').attr('name', 'af_field[]');
                jQuery(drag_element).find('.temp_cls2').attr('name', 'af_title[]');
           
                jQuery(drag_element).insertAfter("#wp_apply_flds #mCSB_2 .mCSB_container ul li:last-child");  
            
                jQuery("#wp_apply_flds").mCustomScrollbar("update");
                jQuery(ui.helper).remove(); //destroy clone
                jQuery(ui.draggable).remove(); //remove from list    
              
                jQuery(".available_fields").draggable({
                connectToSortable:  "#wp_ai_flds",    
                helper: "clone",
                revert: "valid",
                 opacity: 0.35,  
                 zIndex: 100000,
                 appendTo: "body" 
               }); 
               
                 jQuery("#wp_ai_flds").droppable({
           accept: ".available_fields",
            drop: function( event, ui ) {  
                  jQuery((ui.draggable).clone()).insertAfter("#wp_ai_flds #mCSB_1 .mCSB_container ul li:last-child");
                   jQuery(this).find('.temp_cls1').attr('name', 'av_field[]');
                  jQuery(this).find('.temp_cls2').attr('name', 'av_title[]');
                  
                  /*Initialize find on the new elements: code same as find li code on load*/
          
       jQuery("#wp_ai_flds").find('li').each(function(){   
            jQuery(this).unbind( "click" );  
        }); 
      
       jQuery("#wp_ai_flds").find('li').each(function(){   
                jQuery(this).click( function() {
                    /*    alert('in droppable - 5');*/
                        jQuery(this).updateDialog(this);
                     
                       });
      }); 
             
      /*end of initialization */    
                   
                jQuery("#wp_ai_flds").mCustomScrollbar("update");
                jQuery(ui.helper).remove(); //destroy clone
                jQuery(ui.draggable).remove(); //remove from list
                jQuery(".available_fields").draggable({
              connectToSortable:  "#wp_ai_flds",    
              helper: "clone",
              revert: "valid",
               opacity: 0.35,  
               zIndex: 100000,
               appendTo: "body" 
             }); 
        
              
             }});
  
                    
              },
              
           
              
              
              
              
      
      }); 
    }
  });
  
  /*function used to update the dialog box parameters*/
 jQuery.fn.updateDialog = function(evt){
        jQuery("#edit_dialog" ).dialog( "open" ); 
        var av_field = jQuery(evt).find('.temp_cls1').val();
        var av_title = jQuery(evt).find('.temp_cls2').val();
        var exploded_field = av_field.split(":");
        var text_selected = longtext_selected = upload_selected = numeric_selected =  upload_other_selected = email_selected = '' ;  
        var required = ''; 

        var sel_field = exploded_field[0].toUpperCase();
        if(sel_field == afAjax.FIELD_TYPE1){
           text_selected = "selected='selected'";
         }
         else if(sel_field == afAjax.FIELD_TYPE2){
           upload_selected = "selected='selected'"; 
         }
         else if(sel_field == afAjax.FIELD_TYPE3){
          longtext_selected = "selected='selected'";
         }
         else if(sel_field == afAjax.FIELD_TYPE4){
          numeric_selected = "selected='selected'";
         }
         else if(sel_field == afAjax.FIELD_TYPE6){
          upload_other_selected = "selected='selected'";
         }
         else if(sel_field == afAjax.FIELD_TYPE7){
          email_selected = "selected='selected'";
         }
         
         if(exploded_field[1] == "1"){
          required = "checked='checked'";
         }
        
         var option_str = "<option value='Text' "+text_selected+">Text</option><option value='Upload_CV' "+upload_selected+">Upload - CV</option><option value='Upload_Other' "+upload_other_selected+">Upload - Other</option><option value='LongText' "+longtext_selected+">LongText</option><option value='Numeric' "+numeric_selected+">Numeric</option><option value='Email' "+email_selected+">Email</option>";
         jQuery(evt).attr('id','pointer');      //set a id called pointer
        /* jQuery("#edit_dialog").append("<form id='form_dialog'><div>Field Type: <select id='new_field' name='new_field'>"+option_str+"</select></div><div>Required: <input id='required_field' type='checkbox' name='required_field' value='1' "+required+" ></div><div>Title of field: <input type='text' name='field_title' id='field_title' value='"+av_title+"' /></div></form>"); */
         
         
        jQuery("#edit_dialog").append("<form id='form_dialog'><table class='wpaf_dialog_table'><tr><td width='22px'><span class='wpaf_tooltip trigger'  onmouseover = 'OpenDiv(\""+afTooltip.FIELD_TYPE_TOOLTIP+"\");'>?</span></td><td width='110px'><span class='dialog_flds'>Field Type:</span></td><td><select id='new_field' name='new_field' style='width:180px;'>"+option_str+"</select></td></tr><tr><td><span class='wpaf_tooltip trigger'  onmouseover = 'OpenDiv(\""+afTooltip.REQUIRED_TOOLTIP+"\");'>?</span></td><td><span class='dialog_flds'>Required:</span></td><td><input id='required_field' type='checkbox' name='required_field' value='1' "+required+"></td></tr><tr><td><span class='wpaf_tooltip trigger'  onmouseover = 'OpenDiv(\""+afTooltip.TITLE_OF_FIELD_TOOLTIP+"\");'>?</span></td><td><span class='dialog_flds'>Title of field:</span></td><td><input type='text' name='field_title' id='field_title' value='"+av_title+"' /></td></tr></table></form>"); 
         
  };
  
  
  
})