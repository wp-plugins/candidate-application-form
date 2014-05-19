/*File: custom_plupload.js
  Use: to upload files 
*/
  var admin_ajax_url = myAjax.ajaxurl;
  var Uploadsarr = {};
  var upload_var; 

jQuery(document).ready( function() {
 var m = 1;
 jQuery(".uploader_class").each(function() { 
     var upload_var = "uploader_"+m;   
     var container_elm = 'container_'+m;
     var pickfile_elm = 'pickfiles_'+m;
     var multiple_upldprev_elm = 'uploadfiles_'+m;
     var span_error_elm = 'file_select_err_'+m;
     var uploaded_file_path_elm = 'uploaded_file_path_'+m; 
     var original_filename_elm = 'original_filename_'+m;
     
     jQuery(this).attr("id",upload_var); 
     jQuery(this).find(".container_class").attr("id",container_elm); 
     jQuery(this).find(".multiple_select").attr("id",pickfile_elm); 
     jQuery(this).find(".multiple_upldprev").attr("id",multiple_upldprev_elm); 
     jQuery(this).find(".span_error_class").attr("id",span_error_elm); 
     jQuery(this).find(".uploaded_file_path_class").attr("id",uploaded_file_path_elm);
     jQuery(this).find(".original_filename_class").attr("id",original_filename_elm); 
     
            	 Uploadsarr[upload_var] = new plupload.Uploader({
            		runtimes      : 'gears,html5,flash,silverlight,browserplus,html4',
            		browse_button : pickfile_elm,
                urlstream_upload : 'true', //used to maintain session in IE+Flash Combo..
            		container     : container_elm,
            	 	url           : admin_ajax_url,
                max_file_size : '2 mb',
            		filters : [
            			{title : "Doc files", extensions : "doc,docx"},
                 	{title : "Pdf files", extensions : "pdf"},
                  {title : "Text files", extensions : "txt"},
                  ],
                multipart_params : {
                      "action" : "candidate_file_upload"
                  }
                               
            	});
              
              
            	Uploadsarr[upload_var].init(); 
              
    m++;

 });
  
 
 
    var multiple_upldprev = jQuery(".multiple_upldprev");
    var uploadfiles = new Array();
    var j = 0;
    var k = j+1;
    multiple_upldprev.each(function() {     // perform function for each element
       var element = jQuery(this);         // get jquery object for the current element
       var id = element.attr("id");   // get the id
       uploadfiles[j] = '#'+id;
       j++;
    });
    var uploadfiles_str = uploadfiles.join();
    
    
    
  	jQuery(uploadfiles_str).click(function(e) { 
        var id = jQuery(this).attr("id"); 
        var elm_arr = new Array();
        elm_arr = id.split("_"); 
       	Uploadsarr["uploader_"+elm_arr[1]].start();
    		e.preventDefault();   
    	});
    
    
   jQuery.each( Uploadsarr, function( key, value ) {
    	
      Uploadsarr[key].bind('FilesAdded', function(up, files) {
        while (up.files.length > 1){
          up.removeFile(up.files[0]);
        }
    
    		jQuery.each(files, function(i, file) {
          var filename = file.name;
          if(filename.length>50){
            filename = filename.substr(0,50)+"...";
          } 
          var elm_arr = new Array();
          elm_arr = key.split("_"); 
         
         jQuery("#file_select_err_"+elm_arr[1]).html(filename); 
          jQuery("#file_select_err_"+elm_arr[1]+" :has('.wpaf_error_detected')").removeClass("wpaf_error_detected");    /*remove error class */ 
         
          /*Trigger to upload the file on choose file*/
          /*$("#uploadfiles_"+elm_arr[1]).trigger("click");    */
                
      
      }); 
     	up.refresh(); // Reposition Flash/Silverlight 
    	});
    
    
    
     	Uploadsarr[key].bind('Error', function(up, err) {    
             var elm_arr = new Array();
            elm_arr = key.split("_"); 
            jQuery("#file_select_err_"+elm_arr[1]).html('Invalid file');
            /*  $("#file_select_err_"+elm_arr[1]+" :not(:has('.wpaf_error_detected'))").addClass("wpaf_error_detected"); */
            jQuery("#file_select_err_"+elm_arr[1]).addClass("wpaf_error_detected");
               /*add error class */ 
          	up.refresh(); // Reposition Flash/Silverlight
      	});
        
       /*to return filename after upload*/
       	Uploadsarr[key].bind('FileUploaded', function(up, file, info) {
            var elm_arr = new Array();
            elm_arr = key.split("_"); 
            var obj = JSON.parse(info.response); 
            jQuery("#uploaded_file_path_"+elm_arr[1]).val(obj.filePath);
            jQuery("#original_filename_"+elm_arr[1]).val(obj.original_filename);
            var filesize = parseFloat(jQuery("#total_file_size").val()) + parseFloat(file.size);
            jQuery("#total_file_size").val(filesize);
               
          	up.refresh(); 
        }); 
        
        
        
        
     });
 
});   