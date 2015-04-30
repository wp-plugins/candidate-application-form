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
                jQuery(drag_element).find('.temp_cls3').attr('name', 'af_options[]'); //crinch
           
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
                  jQuery(this).find('.temp_cls3').attr('name', 'av_options[]'); //crinch
                  
                  
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
                  jQuery(this).find('.temp_cls3').attr('name', 'av_options[]');  //crinch
                  
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
      
//custom - crinch
      /*jQuery( "#add_new_button" ).dialog( "option", "buttons", [{ text: "Upload", click: function() 
    	  { jQuery("add_new_buttonadd_new_button" ). append('<form action="../test/test_upload.php" method="POST" name="getnamefile"><input type="file" id="uploadfile" name="uploadfile"><input type="submit" id="Submit" name= "Submit" value="Upload"></form>');}      
    	  }]);*/
//
   jQuery("#add_new_button").click( function() {
	   //ADDING NEW FIELDS
	   var showHideAddOptionLink = "style='display:none'";
	   var showHideUploadPDFField = "style='display:none'";
      jQuery("#dialog" ).dialog( "open" );
      //01april - CRINCH done
      jQuery("#dialog").append("<form id='form_dialog' method='post' enctype='multipart/form-data'><table class='wpaf_dialog_table'><tr><td width='22px'><span class='wpaf_tooltip trigger'  onmouseover = 'OpenDiv(\""+afTooltip.FIELD_TYPE_TOOLTIP+"\");'>?</span></td><td width='110px'><span class='dialog_flds'>Field Type:</span></td><td><select onchange='DeleteItemOnDropDownSelection(); return false;' id='new_field' name='new_field' style='width:180px;'><option value='Text'>Text</option><option value='Upload_CV'>Upload - CV</option><option value='Upload_Other'>Upload - Other</option><option value='LongText'>Long Text</option><option value='Numeric'>Numeric</option><option value='Email'>Email</option><option value='Dropdown'>Dropdown</option><option value='Checkbox'>Checkbox</option><option value='EditablePDF'>Editable PDF</option></select></td></tr><tr><td><span class='wpaf_tooltip trigger'  onmouseover = 'OpenDiv(\""+afTooltip.REQUIRED_TOOLTIP+"\");'>?</span></td><td><span class='dialog_flds'>Required:</span></td><td><input id='required_field' type='checkbox' name='required_field' value='1'></td></tr><tr><td><span class='wpaf_tooltip trigger'  onmouseover = 'OpenDiv(\""+afTooltip.TITLE_OF_FIELD_TOOLTIP+"\");'>?</span></td><td><span class='dialog_flds'>Title of field:</span></td><td><input type='text' name='field_title' id='field_title' /></td></tr><tr id='asifarif-0' class='checkme'><td colspan='3'></td></tr><tr "+showHideAddOptionLink+" id='linkToCreateDynamicOption'><td align='center' colspan='3'><button type='button' id='asif_arif' class='asif_arif' onclick = 'event_add_audience_custom(); return false;'><span class='ui-button-text trigger'>Add OPtion</span></button></td></tr><tr "+showHideUploadPDFField+" id='showHideUploadPDFFieldID'><td align='center' colspan='3'><input name='file' id='fileupload' type='file' size='15' multiple/><div id='showPDFfileOnly'style='color:red;font-size:10px;'>Only PDF file!</div><div ='response'></div></td></tr></table></form>");
      })

      
   jQuery("#wp_ai_flds").find('li').each(function(){     
     jQuery(this).click( function() {
        /* alert('in droppable - 3');*/
         jQuery(this).updateDialog(this);

      });
  }); 
   //CRINCH - custom code
  jQuery("#dialog").on( "dialogclose", function( event, ui ) {
     if(event.cancelable){
         jQuery("#dialog").html(''); 
     }else{           
    	 //custom, crinch
    	 var final_string = '';
    	 var new_field_type = jQuery('#new_field').val();
    	 
    	 if(new_field_type=='EditablePDF'){
    		 if(jQuery('#field_title').val()==''){
    			 alert('Please enter FIELD title');
    			 return false;
    		 }
    		 
//
    		 event.preventDefault;
    		 var fd = new FormData();
    		 var file = jQuery('#fileupload').prop('files')[0];
    		 if(!file){
    			 alert('Please try again and select PDF file!');
    			 return false;
    		 }
    		 if(file.name==''){
    			 alert('Please try again and select PDF file!');
    			 return false;
    		 }
    		 var ext = jQuery('#fileupload').val().split('.').pop().toLowerCase();
    		 if(ext!='pdf'){
    			 alert('Please try again and select PDF file only!');
    			 return false;
    		 }
    		 fd.append("file", file);
    		 fd.append("name", file.name);    		 
		    fd.append("caption", 'asifarif');  
		    fd.append('action', 'crinch_custom_file_upload');
		    var json_response_error = false;
		    var json_response_file_uploaded_path = '';
		    var break_whole_loop = false;
		    jQuery.ajax({
		        type: 'POST',
		        url: myCustomAjax.ajaxcustomurl,
		        data: fd,
		        contentType: false,
		        processData: false,
		        async: false,
		        success: function(response){
		        	//var break_whole_loop = false;
		        	var json = jQuery.parseJSON(response);
		        	jQuery(json).each(function(i,val){
		        		if(break_whole_loop){
		        			return false;
		        		}
		        		jQuery.each(val,function(k,v){
		        	          if(k=='error'){
		        	        	  break_whole_loop = true;
		        	        	  return false;
		        	          }
		        	          if(k=='filePath' && v!=''){
		        	        	  final_string = val[k];
		        	        	  break_whole_loop = true;
		        	        	  return false;
		        	          }		        	          
		        	});
		        	});
		        },
		    	error: function (returnval) {
		        alert('Sorry, there is REQUEST problem: '+returnval);
		        return false;
		    	}
		    });		    
		    if(final_string==''){
		    	alert('PDF file uploading problem!');
		    	return false;
		    }
    	 }
    	 //alert(final_string);
    	 
    	 if(new_field_type=='Dropdown'){
	    	 //var final_string = '';
	    	 var drop_down_default_selection_value = '';
	    	 
	    	 // get selected radrio button value
	    	 var selected_radio_button_val = jQuery("#form_dialog input[type='radio']:checked").val();
	    	 var ii = 1;    	 
	    	 // new field options value
	    	 jQuery('input[name^="fieldOptions"]').each(function() {
	    		    drop_down_default_selection_value = '';
	    		    //if(ii == selected_radio_button_val){
	    		    if(jQuery('#fieldOPtionDefaultRadioButton-' + this.id).is(":checked")){
	    		    	drop_down_default_selection_value = '[selected]';
	    		    	final_string = final_string + jQuery(this).val() + drop_down_default_selection_value + ',';
	    		    }else{
	    		    	final_string = final_string + jQuery(this).val() + ',';
	    		    }
	    		    drop_down_default_selection_value = '';
	    		    ii++;
	    		});
	    	 final_string = final_string.substring(0, final_string.length - 1);
    	 }

    	 if(new_field_type=='Checkbox'){
	    	 //var final_string = '';
	    	 var checkbox_checked_value = '';
	    	 
	    	 // get selected radrio button value
	    	 //var selected_checkbox_button_val = jQuery("#form_dialog input[type='checkbox']:checked").val();
	    	 var jj = 1;    	 
	    	 // new field options value
	    	 jQuery('input[name^="fieldOptions"]').each(function() {
	    		 checkbox_checked_value = '';
	    		    //if(jQuery('#fieldOPtionDefaultCheckBox-' + jj).is(":checked")){
	    		 if(jQuery('#fieldOPtionDefaultCheckBox-' + this.id).is(":checked")){
	    		    	checkbox_checked_value = '[checked]';
	    		    	final_string = final_string + jQuery(this).val() + checkbox_checked_value + ',';
	    		    }else{
	    		    	final_string = final_string + jQuery(this).val() + ',';
	    		    }
	    		    checkbox_checked_value = '';
	    		    jj++;
	    		});
			 final_string = final_string.substring(0, final_string.length - 1);
    	 }
    	 
  //new field options radio button value
    	 
        var new_field = jQuery('#new_field').val();
        var required_field = jQuery('#required_field:checked').val();
        var field_title = jQuery('#field_title').val();
        var compulsory = '';
        
        if(required_field == 1){
         compulsory += '<span style="color:red;">*</span>';
        }else{
           required_field = 0;
        }
   
      
      //jQuery("<li class='available_fields'><label>"+compulsory+" "+new_field+":"+field_title+" </label><input name='av_field[]' class='temp_cls1' type='hidden'  value='"+new_field+":"+required_field+"' /><input name='av_title[]' class='temp_cls2' type='hidden' value='"+field_title+"'></li>").insertAfter("#mCSB_1 .mCSB_container ul li:last-child");
        if(new_field=='Dropdown' || new_field=='Checkbox'){
        	jQuery("<li class='available_fields'><label>"+compulsory+" "+new_field+":"+field_title+" </label><input name='av_field[]' class='temp_cls1' type='hidden'  value='"+new_field+":"+required_field+"' /><input name='av_title[]' class='temp_cls2' type='hidden' value='"+field_title+"'><input name='av_options[]' class='temp_cls3' type='hidden' value='"+final_string+"'></li>").insertAfter("#mCSB_1 .mCSB_container ul li:last-child");
        }else{
        	jQuery("<li class='available_fields'><label>"+compulsory+" "+new_field+":"+field_title+" </label><input name='av_field[]' class='temp_cls1' type='hidden'  value='"+new_field+":"+required_field+"' /><input name='av_title[]' class='temp_cls2' type='hidden' value='"+field_title+"'><input name='av_options[]' class='temp_cls3' type='hidden' value='"+final_string+"'></li>").insertAfter("#mCSB_1 .mCSB_container ul li:last-child");
        }
          
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
                jQuery(drag_element).find('.temp_cls3').attr('name', 'af_options[]');  //crinch
           
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
                  jQuery(this).find('.temp_cls3').attr('name', 'av_options[]');  //crinch
                 
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
		 /***************EDIT FIELDS WITHOUT PAGE REFRESHING AND WHEN YOU CILCK OK ON EDIT DIALOG*********************************/	  
     if(event.cancelable){
         jQuery("#dialog").html(''); 
     }else{   
        var new_field = jQuery('#new_field').val();
        var required_field = jQuery('#required_field:checked').val();
        var field_title = jQuery('#field_title').val();
        var compulsory = '';
        
        
   	 //custom, crinch
   	 var final_string_edit = '';
   	 var new_field_type_edit = jQuery('#new_field').val();
   	 
   	var already_selected_file_path = jQuery('#alreadySelectedPdf').val();
   	if(already_selected_file_path!='' && new_field_type_edit!='EditablePDF'){
		 var fd_edit_only_delete_file = new FormData();
		 fd_edit_only_delete_file.append("name", 'nofile');
		 fd_edit_only_delete_file.append("delteOldFile", already_selected_file_path);
		 fd_edit_only_delete_file.append("caption", 'asifarif');  
		 fd_edit_only_delete_file.append('action', 'crinch_custom_file_upload');
		    jQuery.ajax({
		        type: 'POST',
		        url: myCustomAjax.ajaxcustomurl,
		        data: fd_edit_only_delete_file,
		        contentType: false,
		        processData: false,
		        async: false,
		        success: function(response_edit){
		        },
		    	error: function (returnval_edit) {
		    	}
		    });			 
   	}
  	 if(new_field_type_edit=='EditablePDF'){
  		var already_selected_file_path = jQuery('#alreadySelectedPdf').val();
  		
  		////////////////////////////////
		 event.preventDefault;
		 var fd_edit = new FormData();
		 var file_edit = jQuery('#fileupload').prop('files')[0];
		 if((!file_edit) && already_selected_file_path!=''){
			 final_string_edit = already_selected_file_path;
		 }else if(file_edit){
		 if(file_edit.name==''){
			 alert('Please try again and select PDF file!');
			 return false;
		 }
		 var ext_edit = jQuery('#fileupload').val().split('.').pop().toLowerCase();
		 if(ext_edit!='pdf'){
			 alert('Please try again and select PDF file only!');
			 return false;
		 }
		 fd_edit.append("file", file_edit);
		 fd_edit.append("name", file_edit.name);
		 fd_edit.append("delteOldFile", already_selected_file_path);
		 fd_edit.append("caption", 'asifarif');  
		 fd_edit.append('action', 'crinch_custom_file_upload');
	    var json_response_error_edit = false;
	    var json_response_file_uploaded_path_edit = '';
	    var break_whole_loop_edit = false;
	    jQuery.ajax({
	        type: 'POST',
	        url: myCustomAjax.ajaxcustomurl,
	        data: fd_edit,
	        contentType: false,
	        processData: false,
	        async: false,
	        success: function(response_edit){
	        	//var break_whole_loop = false;
	        	var json_edit = jQuery.parseJSON(response_edit);
	        	jQuery(json_edit).each(function(i_edit,val_edit){
	        		if(break_whole_loop_edit){
	        			return false;
	        		}
	        		jQuery.each(val_edit,function(k_edit,v_edit){
	        	          if(k_edit=='error'){
	        	        	  break_whole_loop_edit = true;
	        	        	  return false;
	        	          }
	        	          if(k_edit=='filePath' && v_edit!=''){
	        	        	  final_string_edit = val_edit[k_edit];
	        	        	  break_whole_loop_edit = true;
	        	        	  return false;
	        	          }		        	          
	        	});
	        	});
	        },
	    	error: function (returnval_edit) {
	        alert('Sorry, there is REQUEST problem: '+returnval_edit);
	        return false;
	    	}
	    });		    
	    if(final_string_edit==''){
	    	alert('PDF file uploading problem on EDIT!');
	    	return false;
	    }
		 }
  		////////////////////////////////
  	 }//new_field_type_edit=='EditablePDF'
   	 if(new_field_type_edit=='Dropdown'){
	    	 //var final_string = '';
	    	 var drop_down_default_selection_value_edit = '';
	    	 var selected_radio_button_val_edit = jQuery("#form_dialog input[type='radio']:checked").val();
	    	 var ii_edit = 1;    	 
	    	 jQuery('input[name^="fieldOptions"]').each(function() {
	    		    drop_down_default_selection_value_edit = '';
	    		    if(this.id == selected_radio_button_val_edit){
	    		    //if(jQuery('#fieldOPtionDefaultRadioButton-' + this.id).is(":checked")){
	    		    	drop_down_default_selection_value_edit = '[selected]';
	    		    	final_string_edit = final_string_edit + jQuery(this).val() + drop_down_default_selection_value_edit + ',';
	    		    }else{
	    		    	final_string_edit = final_string_edit + jQuery(this).val() + ',';
	    		    }
	    		    drop_down_default_selection_value_edit = '';
	    		    ii_edit++;
	    		});
	    	 final_string_edit = final_string_edit.substring(0, final_string_edit.length - 1);
   	 }
   	 if(new_field_type_edit=='Checkbox'){
	    	 var checkbox_checked_value_edit = '';
	    	 var jj_edit = 1;    	 
	    	 jQuery('input[name^="fieldOptions"]').each(function() {
	    		 checkbox_checked_value_edit = '';
	    		    //if(jQuery('#fieldOPtionDefaultCheckBox-' + jj_edit).is(":checked")){
	    		 if(jQuery('#fieldOPtionDefaultCheckBox-' + this.id).is(":checked")){
	    		    	checkbox_checked_value_edit = '[checked]';
	    		    	final_string_edit = final_string_edit + jQuery(this).val() + checkbox_checked_value_edit + ',';
	    		    }else{
	    		    	final_string_edit = final_string_edit + jQuery(this).val() + ',';
	    		    }
	    		    checkbox_checked_value_edit = '';
	    		    jj_edit++;
	    		});
			 final_string_edit = final_string_edit.substring(0, final_string_edit.length - 1);
   	 }
        
        
        
        if(required_field == 1){
         compulsory += '<span style="color:red;">*</span>';
        }else{
           required_field = 0;
        }
   //CRINCH - updated below line
      /*jQuery('#pointer').html("<label>"+compulsory+" "+new_field+":"+field_title+" </label><input name='av_field[]' class='temp_cls1' type='hidden'  value='"+new_field+":"+required_field+"' /><input name='av_title[]' class='temp_cls2' type='hidden' value='"+field_title+"'>");*/
      jQuery('#pointer').html("<label>"+compulsory+" "+new_field+":"+field_title+" </label><input name='av_field[]' class='temp_cls1' type='hidden'  value='"+new_field+":"+required_field+"' /><input name='av_title[]' class='temp_cls2' type='hidden' value='"+field_title+"'><input name='av_options[]' class='temp_cls3' type='hidden' value='"+final_string_edit+"'>");
	  
	  
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
                jQuery(drag_element).find('.temp_cls3').attr('name', 'af_options[]');  //crinch
           
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
                  jQuery(this).find('.temp_cls3').attr('name', 'av_options[]');
                  
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
	 /***************EDIT FIELDS WITHOUT PAGE REFRESHING*********************************/
	 	//CRINCH
	   var showHideAddOptionLink = "style='display:none'";
	   var showHideUploadPDFField = "style='display:none'";
	   var dynamicOptionsTrs = '';
	   //end CRINCH
        jQuery("#edit_dialog" ).dialog( "open" ); 
        var av_field = jQuery(evt).find('.temp_cls1').val();
        var av_title = jQuery(evt).find('.temp_cls2').val();
        var av_options = jQuery(evt).find('.temp_cls3').val();
        //important - CRINCH
        var exploded_field = av_field.split(":");
        //CRINCH
        //var text_selected = longtext_selected = upload_selected = numeric_selected =  upload_other_selected = email_selected = '' ;
        var text_selected = longtext_selected = upload_selected = numeric_selected =  upload_other_selected = email_selected = dropdown_selected = checkbox_selected = editablePDF_selected = '' ;  
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
         else if(sel_field == afAjax.FIELD_TYPE8){ //CRINCH
             dropdown_selected = "selected='selected'";
             showHideAddOptionLink = '';
             if(av_options!=''){
            	 dynamicOptionsTrs = makeDynamicOptions(sel_field, av_options);
            	 //makeDynamicOptions(sel_field, av_options);
             }
         }
         else if(sel_field == afAjax.FIELD_TYPE9){ //CRINCH
             checkbox_selected = "selected='selected'";
             dynamicOptionsTrs = makeDynamicOptions(sel_field, av_options);
             showHideAddOptionLink = '';
         }
         else if(sel_field == afAjax.FIELD_TYPE10){ //CRINCH
             editablePDF_selected = "selected='selected'";
				showHideAddOptionLink = "style='display:none'";
				dynamicOptionsTrs = makeDynamicOptions_runtime_editable(sel_field, av_options);
         }
         
         if(exploded_field[1] == "1"){
          required = "checked='checked'";
         }
        
         //var option_str = "<option value='Text' "+text_selected+">Text</option><option value='Upload_CV' "+upload_selected+">Upload - CV</option><option value='Upload_Other' "+upload_other_selected+">Upload - Other</option><option value='LongText' "+longtext_selected+">LongText</option><option value='Numeric' "+numeric_selected+">Numeric</option><option value='Email' "+email_selected+">Email</option>";
         //CRINCH - 01april - done
         var option_str = "<option value='Text' "+text_selected+">Text</option><option value='Upload_CV' "+upload_selected+">Upload - CV</option><option value='Upload_Other' "+upload_other_selected+">Upload - Other</option><option value='LongText' "+longtext_selected+">LongText</option><option value='Numeric' "+numeric_selected+">Numeric</option><option value='Email' "+email_selected+">Email</option><option value='Dropdown' "+dropdown_selected+">Dropdown</option><option value='Checkbox' "+checkbox_selected+">Checkbox</option><option value='EditablePDF' "+editablePDF_selected+">Editable PDF</option>";
         jQuery(evt).attr('id','pointer');      //set a id called pointer
        /* jQuery("#edit_dialog").append("<form id='form_dialog'><div>Field Type: <select id='new_field' name='new_field'>"+option_str+"</select></div><div>Required: <input id='required_field' type='checkbox' name='required_field' value='1' "+required+" ></div><div>Title of field: <input type='text' name='field_title' id='field_title' value='"+av_title+"' /></div></form>"); */
         
        //CRINCH 
        //jQuery("#edit_dialog").append("<form id='form_dialog'><table class='wpaf_dialog_table'><tr><td width='22px'><span class='wpaf_tooltip trigger'  onmouseover = 'OpenDiv(\""+afTooltip.FIELD_TYPE_TOOLTIP+"\");'>?</span></td><td width='110px'><span class='dialog_flds'>Field Type:</span></td><td><select id='new_field' name='new_field' style='width:180px;'>"+option_str+"</select></td></tr><tr><td><span class='wpaf_tooltip trigger'  onmouseover = 'OpenDiv(\""+afTooltip.REQUIRED_TOOLTIP+"\");'>?</span></td><td><span class='dialog_flds'>Required:</span></td><td><input id='required_field' type='checkbox' name='required_field' value='1' "+required+"></td></tr><tr><td><span class='wpaf_tooltip trigger'  onmouseover = 'OpenDiv(\""+afTooltip.TITLE_OF_FIELD_TOOLTIP+"\");'>?</span></td><td><span class='dialog_flds'>Title of field:</span></td><td><input type='text' name='field_title' id='field_title' value='"+av_title+"' /></td></tr></table></form>"); 
        //jQuery("#edit_dialog").append("<form id='form_dialog'><table class='wpaf_dialog_table'><tr><td width='22px'><span class='wpaf_tooltip trigger'  onmouseover = 'OpenDiv(\""+afTooltip.FIELD_TYPE_TOOLTIP+"\");'>?</span></td><td width='110px'><span class='dialog_flds'>Field Type:</span></td><td><select onchange='DeleteItemOnDropDownSelection(); return false;' id='new_field' name='new_field' style='width:180px;'>"+option_str+"</select></td></tr><tr><td><span class='wpaf_tooltip trigger'  onmouseover = 'OpenDiv(\""+afTooltip.REQUIRED_TOOLTIP+"\");'>?</span></td><td><span class='dialog_flds'>Required:</span></td><td><input id='required_field' type='checkbox' name='required_field' value='1' "+required+"></td></tr><tr><td><span class='wpaf_tooltip trigger'  onmouseover = 'OpenDiv(\""+afTooltip.TITLE_OF_FIELD_TOOLTIP+"\");'>?</span></td><td><span class='dialog_flds'>Title of field:</span></td><td><input type='text' name='field_title' id='field_title' value='"+av_title+"' /></td></tr><tr id='asifarif-0' class='checkme'><td colspan='3'></td></tr>"+dynamicOptionsTrs+"<tr "+showHideAddOptionLink+" id='linkToCreateDynamicOption'><td align='center' colspan='3'><button type='button' id='asif_arif' class='asif_arif' onclick = 'event_add_audience_custom(); return false;'><span class='ui-button-text trigger'>Add OPtion</span></button></td></tr><tr "+showHideUploadPDFField+" id='showHideUploadPDFFieldID'><td align='center' colspan='3'><input name='file' id='fileupload' type='file' size='15' multiple/><div id='showPDFfileOnly'style='color:red;font-size:10px;'>Only PDF filesq!</div><div ='response'></div></td></tr></table></form>");
         jQuery("#edit_dialog").append("<form id='form_dialog'><table class='wpaf_dialog_table'><tr><td width='22px'><span class='wpaf_tooltip trigger'  onmouseover = 'OpenDiv(\""+afTooltip.FIELD_TYPE_TOOLTIP+"\");'>?</span></td><td width='110px'><span class='dialog_flds'>Field Type:</span></td><td><select onchange='DeleteItemOnDropDownSelection(); return false;' id='new_field' name='new_field' style='width:180px;'>"+option_str+"</select></td></tr><tr><td><span class='wpaf_tooltip trigger'  onmouseover = 'OpenDiv(\""+afTooltip.REQUIRED_TOOLTIP+"\");'>?</span></td><td><span class='dialog_flds'>Required:</span></td><td><input id='required_field' type='checkbox' name='required_field' value='1' "+required+"></td></tr><tr><td><span class='wpaf_tooltip trigger'  onmouseover = 'OpenDiv(\""+afTooltip.TITLE_OF_FIELD_TOOLTIP+"\");'>?</span></td><td><span class='dialog_flds'>Title of field:</span></td><td><input type='text' name='field_title' id='field_title' value='"+av_title+"' /></td></tr><tr id='asifarif-0' class='checkme'><td colspan='3'></td></tr>"+dynamicOptionsTrs+"<tr "+showHideAddOptionLink+" id='linkToCreateDynamicOption'><td align='center' colspan='3'><button type='button' id='asif_arif' class='asif_arif' onclick = 'event_add_audience_custom(); return false;'><span class='ui-button-text trigger'>Add OPtion</span></button></td></tr></table></form>");
         
  };
  
 
	

  
});


//CRINCH
function makeDynamicOptions(field_type, field_option_str){
	
	var return_str = '';
	var input_old_field_option_str = field_option_str;
	var old_field_option_arr = input_old_field_option_str.split(",");
	for(var i = 1; i <= old_field_option_arr.length; i++){
		var maxId = i;
		var field_title_str = old_field_option_arr[i-1];
		var select_radio_button = '';
		if (field_title_str.match( /(^.*\[|\].*$)/g, '' )){ 
			var select_radio_button = 'checked';	
			var field_title_control_selection = field_title_str.split("[");
			field_title_str = field_title_control_selection[0];
		}	
		if(field_type=='CHECKBOX'){
			var dynamic_checkbox_radio_control = "<input class='inputclass' type='checkbox' name='fieldOPtionDefaultCheckBox[]' id='fieldOPtionDefaultCheckBox-"+maxId+"' "+select_radio_button+"  value='"+maxId+"'><span style='font-size:11px;'>Default Selected</span>";
		}else if(field_type=='DROPDOWN'){
				var dynamic_checkbox_radio_control = "<input class='inputclass' type='radio' name='fieldOPtionDefaultRadioButton[]' id='fieldOPtionDefaultRadioButton-"+maxId+"' "+select_radio_button+"  value='"+maxId+"'><span style='font-size:11px;'>Default Selection</span>";			
		}else{
			var dynamic_checkbox_radio_control = "&nbsp";
		}
		
		var return_str = return_str + "<tr class='checkme' id='asifarif-"+maxId+"'>"+
		"<td><span style='color:red;font-size:11px;cursor:pointer;' class='btnDelete' onclick='customDeleteRowFromAvailableFieldsOnUpdate("+maxId+"); return false;'>Delete</span></td>"+
		"<td colspan='2'>&nbsp; <span style='font-size:15px;'>Options "+maxId+"</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input name='fieldOptions[]' id='"+maxId+"' type='text' value='"+field_title_str+"'/>&nbsp;&nbsp;"+dynamic_checkbox_radio_control+"</td>"+
		"</tr>";
	}
	return return_str;
}
function customDeleteRowFromAvailableFieldsOnUpdate(id){
	DeleteCustomNew(id);
}
function makeDynamicOptionsNew(field_type, field_option_str){
	var return_str = '';
	var input_old_field_option_str = field_option_str;
	var old_field_option_arr = input_old_field_option_str.split(",");
		var maxId = 1;
	    //document.write("<br /> Element " + i + " = " + mySplitResult[i]);
		if(field_type=='CHECKBOX'){
			var dynamic_checkbox_radio_control = "<input class='inputclass' type='checkbox' name='fieldOPtionDefaultCheckBox[]' id='fieldOPtionDefaultCheckBox-"+maxId+"' ><span style='font-size:11px;'>Default Selected</span>";
		}else if(field_type=='DROPDOWN'){
				var dynamic_checkbox_radio_control = "<input class='inputclass' type='radio' name='fieldOPtionDefaultRadioButton[]' id='fieldOPtionDefaultRadioButton-"+maxId+"'  value='"+maxId+"'><span style='font-size:11px;'>Default Selection</span>";			
		}else{
			var dynamic_checkbox_radio_control = "&nbsp";
		}
		//var field_title_str = old_field_option_arr[i]; 
		var field_title_str = 'Option1';
		jQuery(".wpaf_dialog_table tbody tr.checkme:last").after(	
		"<tr class='checkme' id='asifarif-"+maxId+"'>"+
		"<td><span style='color:red;font-size:11px;cursor:pointer;' class='btnDelete'>Delete</span></td>"+
		"<td colspan='2'>&nbsp; <span style='font-size:15px;'>Options "+maxId+"</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input name='fieldOptions[]' type='text' value='"+field_title_str+"'/>&nbsp;&nbsp;"+dynamic_checkbox_radio_control+"</td>"+
		"</tr>");
		
	jQuery(".btnDelete").bind("click", Delete);
	}
	


function Delete(){ //crinch
	var par = jQuery(this).parent().parent(); //tr
	par.remove();
};

function DeleteCustom(id){ //crinch
	var par = jQuery('.wpaf_dialog_table tbody tr#'+id);
	par.remove();
};
function DeleteCustomNew(id){ //crinch
	var par = jQuery('.wpaf_dialog_table tbody tr#asifarif-'+id);
	par.remove();
};

function DeleteItemOnDropDownSelection(){ //crinch
	var getClass = 'checkme';
	var field_type_custom_value = jQuery('#new_field').val();
	if(field_type_custom_value=='Checkbox' || field_type_custom_value=='Dropdown'){
		jQuery('.wpaf_dialog_table tbody tr#linkToCreateDynamicOption').show();
	}else{
		jQuery('.wpaf_dialog_table tbody tr#linkToCreateDynamicOption').hide();
	}

	jQuery("."+getClass).each(function() {
		if(this.id=='asifarif-0'){
			return;
		}
		DeleteCustom(this.id);
		});
};

function event_add_audience_custom() {
	var maxId = 0;
	var getClass = 'checkme';
	jQuery("."+getClass).each(function() {
		   var id = jQuery(this).attr('id').split('-')[1];
		   if( id > maxId)
			  maxId = id;
		});
	maxId = parseInt(maxId)+1;
	
	var field_type_custom_value = jQuery('#new_field').val();
	
	if(field_type_custom_value=='Checkbox'){
		var dynamic_checkbox_radio_control = "<input class='inputclass' type='checkbox' name='fieldOPtionDefaultCheckBox[]' id='fieldOPtionDefaultCheckBox-"+maxId+"' ><span style='font-size:11px;'>Default Selected</span>";
	}else if(field_type_custom_value=='Dropdown'){
			var dynamic_checkbox_radio_control = "<input class='inputclass' type='radio' name='fieldOPtionDefaultRadioButton[]' id='fieldOPtionDefaultRadioButton-"+maxId+"'  value='"+maxId+"'><span style='font-size:11px;'>Default Selection</span>";			
	}else{
		var dynamic_checkbox_radio_control = "&nbsp";
		return false;
	}	
	if(field_type_custom_value=='Checkbox'){
		var dynamic_checkbox_radio_control = "<input class='inputclass' type='checkbox' name='fieldOPtionDefaultCheckBox[]' id='fieldOPtionDefaultCheckBox-"+maxId+"' ><span style='font-size:11px;'>Default Selected</span>";
	}else if(field_type_custom_value=='Dropdown'){
			var dynamic_checkbox_radio_control = "<input class='inputclass' type='radio' name='fieldOPtionDefaultRadioButton[]' id='fieldOPtionDefaultRadioButton-"+maxId+"'  value='"+maxId+"'><span style='font-size:11px;'>Default Selection</span>";			
	}else{
		var dynamic_checkbox_radio_control = "&nbsp";
		return false;
	}

	
	/*jQuery("."+getClass).each(function() {
		   var id = jQuery(this).attr('id').split('-')[1];
		   if( id > maxId)
			  maxId = id;
		});
	maxId = parseInt(maxId)+1;*/
	
	jQuery(".wpaf_dialog_table tbody tr.checkme:last").after(	
			"<tr class='checkme' id='asifarif-"+maxId+"'>"+
			"<td><span style='color:red;font-size:11px;cursor:pointer;' class='btnDelete'>Delete</span></td>"+
			"<td colspan='2'>&nbsp; <span style='font-size:15px;'>Options "+maxId+"</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input name='fieldOptions[]' id='"+maxId+"' type='text'/>&nbsp;&nbsp;"+dynamic_checkbox_radio_control+"</td>"+
			"</tr>");

	jQuery(".btnDelete").bind("click", Delete);
return false;
}
