<?php
/*
 * Plugin Name: Candidate Application Form
 * Plugin URI: http://responsecoordinator.com/?p=298
 * Description: Easily add a candidate application form to a job vacancy post, which allows the candidate to apply for the vacancy.
 * Version: 1.0
 * Author: <a href="http://flaxlandsconsulting.com">Flaxlands Consulting Ltd</a>
 */

$my_plugin = new Candidate_Application_Form();
class Candidate_Application_Form{
   
  	/* * * * * * * * * * * * * * * * * * * * * * *
	  ADMIN MENU:
	* * * * * * * * * * * * * * * * * * * * * * */

	function admin_menu() {
     add_options_page('Candidate Apply Form', 'Candidate Apply Form', 'manage_options', 'candidate-apply-form', array($this, 'admin_candidate_apply_form'));
	}    

		
   
   
 /* Name: apply_form_frontend_method
     Parameters:
     Use: To load the javascript files only on front end
   */   
   
   
   function apply_form_frontend_method(){
   
        wp_register_style('jquery-ui',  plugins_url('css/jquery-ui.css', __FILE__ ));                    
        wp_register_style('jquery.ui.theme',   plugins_url('js/themes/base/jquery.ui.theme.css', __FILE__ ));
        wp_register_style('af-style-ic', plugins_url('css/style-ic.css', __FILE__ ));
        wp_register_style('af-mediaform', plugins_url('css/mediaform.css', __FILE__ )); 
         
        

        wp_enqueue_style( 'jquery-ui' );      
        wp_enqueue_style( 'jquery.ui.theme' );      
        wp_enqueue_style( 'af-style-ic' );   
        wp_enqueue_style( 'af-mediaform' );  
     
        wp_register_script( "custom_plupload", plugins_url('js/custom_plupload.js', __FILE__ ), array('jquery') ); 
        wp_register_script( "apply_form", plugins_url('js/apply_form.js', __FILE__ ), array('custom_plupload') ); 
      
        wp_localize_script( 'apply_form', 'myAjax', array( 'ajaxurl' => admin_url('admin-ajax.php'), 'script_in_use' => SCRIPT_IN_USE));    
        wp_localize_script('custom_plupload', 'AF', array( 'DEBUG_MODE' => DEBUG_MODE )); 
       wp_localize_script('custom_plupload', 'myAjax', array( 'ajaxurl' => admin_url('admin-ajax.php'))); 
       
        wp_enqueue_script('jquery');
        wp_enqueue_script('jquery-ui-core'); 
        wp_enqueue_script('jquery-ui-draggable');  
        wp_enqueue_script('jquery-ui-dialog');            
        wp_enqueue_script('plupload-all');    
        wp_enqueue_script('custom_plupload');    
        wp_enqueue_script('apply_form');  
                  
   }
  
   
   
   /* Name: apply_form_admin_enqueue
     Parameters:
     Use: To load the javascript files for the admin page
   */ 
     function apply_form_admin_enqueue($hook) {
      add_action('admin_notices', array(&$this,'candidate_apply_form_admin_notice'));   
      if( 'settings_page_candidate-apply-form' != $hook )
        return;
       /*added in admin*/
        wp_register_style('jquery-ui',  plugins_url('css/jquery-ui.css', __FILE__ ));     
         
          wp_register_style('jquery.ui.theme', plugins_url('js/themes/base/jquery.ui.theme.css', __FILE__ ));  
        
        /*add style*/
        wp_register_style('af-style', plugins_url('css/admin/form-style.css', __FILE__ ));
         wp_register_style('af-custom-scrollbar', plugins_url('css/jquery.mCustomScrollbar.css', __FILE__ ));
          wp_register_style('af-bootstrap', plugins_url('css/bootstrap.css', __FILE__ ));  
        
	   
        wp_enqueue_style( 'jquery-ui' );    
        wp_enqueue_style( 'jquery.ui.theme' );  
        wp_enqueue_style( 'af-style' );
        wp_enqueue_style( 'af-custom-scrollbar' );
        wp_enqueue_style( 'af-bootstrap' );

        

        
        wp_register_script('af-scrollbar', plugins_url('js/jquery.mCustomScrollbar.concat.min.js', __FILE__ ), array('jquery') );
        wp_register_script( "af-functions", plugins_url('js/functions.js', __FILE__ ), array('jquery') );
        wp_register_script( "admin_apply_form", plugins_url('js/admin_apply_form.js', __FILE__ ), array('af-functions') ); 
    
        wp_localize_script( 'admin_apply_form', 'myCustomAjax', array( 'ajaxcustomurl' => admin_url('admin-ajax.php'), 'script_in_use_custom' => 'uploadfile.php'));
        
        wp_enqueue_script('jquery');
        wp_enqueue_script('jquery-ui-core'); 
        wp_enqueue_script('jquery-ui-draggable');  
        wp_enqueue_script('jquery-ui-droppable'); 
        wp_enqueue_script('jquery-ui-dialog');       
        wp_enqueue_script('jquery-ui-sortable');        
         
       /*end of new scripts*/ 
    
      /*localize field types*/
       //wp_localize_script( 'admin_apply_form', 'afAjax', array( 'FIELD_TYPE1' => FIELD_TYPE1,'FIELD_TYPE2' => FIELD_TYPE2,'FIELD_TYPE3' => FIELD_TYPE3,'FIELD_TYPE4' => FIELD_TYPE4,'FIELD_TYPE6' => FIELD_TYPE6,'FIELD_TYPE7' => FIELD_TYPE7));    
       //CRINCH
        wp_localize_script( 'admin_apply_form', 'afAjax', array( 'FIELD_TYPE1' => FIELD_TYPE1,'FIELD_TYPE2' => FIELD_TYPE2,'FIELD_TYPE3' => FIELD_TYPE3,'FIELD_TYPE4' => FIELD_TYPE4,'FIELD_TYPE6' => FIELD_TYPE6,'FIELD_TYPE7' => FIELD_TYPE7,'FIELD_TYPE8' => FIELD_TYPE8,'FIELD_TYPE9' => FIELD_TYPE9,'FIELD_TYPE10' => FIELD_TYPE10));
     
         wp_localize_script( 'admin_apply_form', 'afTooltip', array( 'FIELD_TYPE_TOOLTIP' => FIELD_TYPE_TOOLTIP,'REQUIRED_TOOLTIP' => REQUIRED_TOOLTIP,'TITLE_OF_FIELD_TOOLTIP' => TITLE_OF_FIELD_TOOLTIP));  
            
       wp_enqueue_script('af-scrollbar');    
       wp_enqueue_script('af-functions');  
       wp_enqueue_script('admin_apply_form');  

       wp_enqueue_script('file-upload-js');
       
        
     }
   
    
    
   /* Name: on_ic_apply
     Parameters:
     Use:  js/apply_form.js sends a post to ic_apply. This function handles the ajax request and loads the scripts from the api_scripts folder as determined in the $script_in_use variable.
   */ 
    function on_ic_apply(){
        /*check for validation errors if any */
     $form_params = $_POST;
     if(isset($_POST['validation_mode'])){ 
       $messages = array();
       $messages = $this->validation_errors($form_params);
      
        if(isset($messages['invalid']) && !empty($messages['invalid'])){
            $validation_msg = json_encode($messages);
            echo $validation_msg;
           die();
        
        }
        else if(isset($messages['file_response']) && !empty($messages['file_response']) ){
             unset($messages['invalid']);
            $validation_msg = json_encode($messages);
            echo $validation_msg;
           die();
        
        }
        echo 1;
        die();
      
      }
      
      if(isset($_POST['Confirm'])){
          unset($_POST['Confirm']);
      }
  
      
      /*end of validation code */
     
     
     $file_path = IC_ROOT_DIR."/api_scripts/".SCRIPT_IN_USE;
     /* Add the Values from the script config section parameter to the list */
    
      global $wpdb;    
      $id = $_POST['post_id']; 
      if(isset($id)){
              $wpaf_headers =  get_option('wpaf_headers');
              $wpaf_parameters =  get_option('wpaf_parameters');
              
              $decoded_headers =  json_decode($wpaf_headers);
              $decoded_parameters = json_decode($wpaf_parameters);
        
               /*Query post meta table */
              $querystr = "SELECT group_concat(wp_postmeta.meta_key  separator '-wp-ai-ex- ') as meta_key_group, group_concat(wp_postmeta.meta_value  separator '-wp-ai-ex- ') as meta_value_group FROM wp_postmeta WHERE wp_postmeta.post_id = $id order by meta_id asc"; 
                  
        
              $page_records = $wpdb->get_results($querystr, OBJECT);    /*get the meta list */
                  
                  
                 /*Query post table */
                 $wp_records = array();    
                 $querystr = "SELECT ".WPpostURL."
                                          FROM wp_posts
                                          WHERE wp_posts.post_status = 'publish' 
                                          AND wp_posts.post_type = 'post'
                                          AND wp_posts.post_date < NOW()
                                          AND  wp_posts.ID = $id
                                          ORDER BY wp_posts.post_date DESC";
                                           
                  
                  $post_records = $wpdb->get_results($querystr, OBJECT);     /*gets details about the post */
                  if(!empty($post_records)){
                        $result = $post_records['0'];
                        $wp_records["WPpostURL"] = $result->guid;    /*todo: list of parameters to fetch as hardcoded values*/
                                
                   }
                   
                   /*add current page url to the hardcoded paramer list */
                     if(isset($_POST['current_page_url'])){
                       $wp_records["Current Page URL"] = $_POST['current_page_url'];  
                      
                       if(SCRIPT_IN_USE == 'Apply_Form_email_script.php'){
                          $_POST['AdvertURL'] = $_POST['current_page_url'];
                       }                       
                       unset($_POST['current_page_url']); 
                      
                     
                     }
                   
                   /*end -current page url code */
            
                   $meta_key_str =  $page_records[0]->meta_key_group;
                   $meta_val_str =  $page_records[0]->meta_value_group;  
                   $meta_key_arr = array();
                   $meta_val_arr = array(); 
                   $meta_key_arr = explode("-wp-ai-ex- ",$meta_key_str);
                   $meta_val_arr = explode("-wp-ai-ex- ",$meta_val_str); 
                   $meta_arr =array();
                   if(isset($meta_key_arr)){
                     foreach($meta_key_arr as $mkey => $mval){
                        $meta_arr[$mval]  = $meta_val_arr[$mkey];
                     }
                   }
                     
                    if(isset($decoded_parameters)){     
                         foreach($decoded_parameters as $param_arr ){ 
                                $param_name_key = trim($param_arr->name);
                                $post_arr["$param_name_key"] =  trim($param_arr->value);
                                $param_value = trim($param_arr->value);
                                
                                
                                preg_match_all('/{.*?}/', $param_value, $matches);
                                $match_val = array_map('intval',$matches);
                                if($match_val[0] == 1){
                                    $parameter_rec =  str_replace("{","","$param_value");
                                     $parameter_rec =  str_replace("}","","$parameter_rec");                              
        
                                    if (array_key_exists("$parameter_rec", $meta_arr)) {
                                          $post_arr["$param_name_key"] =  $meta_arr[$parameter_rec];
                                    }
                                    else if(array_key_exists("$parameter_rec", $wp_records)) { /*Check for hardcoded array */
                                        $post_arr["$param_name_key"] =  $wp_records[$parameter_rec];      /*belongs to wp_post table for hardcoded values */
                                   }
                                  
                               
                                }else if(empty($param_name_key) && empty($param_name_value) ){
                                       unset($post_arr["$param_name_key"]);   /*unset the  empty values from the array*/
                                }
                                
                                
                            /*Merge tag match on name field*/  
                                preg_match_all('/{.*?}/', $param_name_key, $matches);
                                $match_val = array_map('intval',$matches);
                                if($match_val[0] == 1){
                                    $parameter_name_rec =  str_replace("{","","$param_name_key");
                                     $parameter_name_rec =  str_replace("}","","$parameter_name_rec");                              
        
                                    if(array_key_exists("$parameter_name_rec", $meta_arr)) {
                                             $new_key = $meta_arr["$parameter_name_rec"];
                                             $temp_parameter = $post_arr["$param_name_key"];
                                             unset($post_arr["$param_name_key"]);     
                                             $post_arr["$new_key"] = $temp_parameter;
                                    }
                                    else if(array_key_exists("$parameter_name_rec", $wp_records)) { /*Check for hardcoded array */
                                            $new_key = $wp_records[$parameter_name_rec];
                                            $temp_parameter = $post_arr["$param_name_key"];
                                            unset($post_arr["$param_name_key"]);   
                                            $post_arr["$new_key"] = $temp_parameter;
                                   }
                                }
                                /*End: Merge tag match on value field*/         
                           
                           
                              
                        }
                    }
            
        
                    
              
              $headers_array_new =array();  
         
              $final_post_arr = $post_arr;            /*combination*/
              $header_counter = 0;    /*used to obtain if content-type other than application/json is used to overide default content type*/
              if(isset($decoded_headers)){
                foreach($decoded_headers as $head_arr ){ 
                    $name_key = trim($head_arr->name);
                    $uc_name_key = strtoupper($name_key);
                    $uc_name_value = strtoupper($head_arr->value);
                    if(($uc_name_key == 'CONTENT-TYPE') && ($uc_name_value != 'APPLICATION/JSON') ){
                      $header_counter++;  
                    }
                    $headers_array_new["$name_key"] =  trim($head_arr->value);
                    
                    /*Code: Replace merge tag with the Database value*/
                               
                               $head_value  = trim($head_arr->value);
                                preg_match_all('/{.*?}/', $head_value, $matches);
                                $match_val = array_map('intval',$matches);
                                if($match_val[0] == 1){
                                    $header_rec =  str_replace("{","","$head_value");
                                    $header_rec =  str_replace("}","","$header_rec");                              
        
                                    if (array_key_exists("$header_rec", $meta_arr)) {
                                          $headers_array_new["$name_key"] =  $meta_arr[$header_rec];
                                    }
                                    else if(array_key_exists("$header_rec", $wp_records)) { /*Check for hardcodede array */
                                        $headers_array_new["$name_key"] =  $wp_records[$header_rec];      /*belongs to wp_post table for hardcoded values */
                                    }
                                     
        
                               
                                }else if(empty($name_key) && empty($head_value) ){
                                       unset($headers_array_new["$name_key"]);   /*unset the  empty values from the array*/
                                
                                }
                               
                               
                /*End Code: Replace merge tag with the Database value*/
                
                
                  /*Merge tag match on name field for headers*/  
                                preg_match_all('/{.*?}/', $name_key, $matches);
                                $match_val = array_map('intval',$matches);
                                if($match_val[0] == 1){
                                    $header_name_rec =  str_replace("{","","$name_key");
                                    $header_name_rec =  str_replace("}","","$header_name_rec");                              
        
                                    if(array_key_exists("$header_name_rec", $meta_arr)) {
                                             $new_key = $meta_arr["$header_name_rec"];
                                             $temp_header = $headers_array_new["$name_key"];
                                             unset($headers_array_new["$name_key"]); 
                                             $headers_array_new["$new_key"] = $temp_header;
                                    }
                                    else if(array_key_exists("$header_name_rec", $wp_records)) { /*Check for hardcoded array */
                                            $new_key = $wp_records[$header_name_rec];
                                            $temp_header = $headers_array_new["$name_key"];
                                            unset($headers_array_new["$name_key"]);   
                                            $headers_array_new["$new_key"] =  $temp_header;
                                   }
                                     
        
                               
                                }
                /*End: Merge tag match on value field*/   
                               
                }
            }
                    
            if($header_counter == 0){ /*If not set by user, content-type is set to default i.e application/json*/
                  $headers_array_new["Content-Type"] =  "application/json";
            }
            
            $headers = array();
            $headers = $headers_array_new;  
            $script_parameters = $final_post_arr;  
            
            $_POST['headers'] = $headers; 
            $_POST['script_parameters'] = $script_parameters; 
            $script_parameters = array();  
            $headers = array();   
   }
  /*End of code */                   
    $wpaf_setting =  get_option('wpaf_setting');
    $wpaf_messages =  get_option('wpaf_messages');      
    $decoded_setting =  json_decode($wpaf_setting);
    $decoded_messages =  json_decode($wpaf_messages);
    if(isset($decoded_setting->success_setting)){
       
       $success_setting = $decoded_setting->success_setting;
       $_POST['apply_success'] = $success_setting;
       if($success_setting == 1){
        $_POST['success_message'] = stripslashes(htmlspecialchars_decode($decoded_messages->success_msg,ENT_QUOTES));
       }
     }
     if(isset($decoded_setting->failure_setting)){
       $failure_setting = $decoded_setting->failure_setting;
        $_POST['apply_failure'] = $failure_setting;
       if($failure_setting == 1){
        $_POST['failure_message'] =   stripslashes(htmlspecialchars_decode($decoded_messages->failure_msg,ENT_QUOTES));
       }
     }
     
      
     if(isset($decoded_setting->destination_email)){
        $_POST['destination_email'] = $decoded_setting->destination_email;
     }
       
    /*End of Script Config code*/
    
    
     
    if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'){
       include("$file_path");
       die();
        
       
                       
   }
   else {  /* javascript is disabled, this feature is not being called for now*/
     include("$file_path");
      /*end of api post*/
      header("Location: ".$_SERVER["HTTP_REFERER"]);     
      die();  
   }


         /** Set the correct status (so that the correct splash message is shown */
        // $_POST['_wp_http_referer'] = add_query_arg('status', $status, $_POST['_wp_http_referer']);
 
         /** Redirect the user back to where they came from */
        //  wp_redirect($_POST['_wp_http_referer']);

    }
    
    function add_meta_tags(){
        echo '<meta charset="utf-8"> 
        <meta name = "viewport" content = "width=device-width, maximum-scale = 1, minimum-scale=1" /><!--[if lt IE 9]> 
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
<![endif]-->';
    }
    
                                                                

   /* Name: apply_form
     Parameters:
     Use: The Apply to Job Form
     shortcode:  [apply-form]
   */ 

   function apply_form(){  
   ob_start();
   add_action('wp_head', 'add_meta_tags');  ?>
   <!--[if lt IE 9]>
   <script type="text/javascript" src="<?php echo plugins_url('js/html5shiv.js', __FILE__ ); ?>"></script>
   <script type="text/javascript" src="<?php echo plugins_url('js/css3-mediaqueries.js', __FILE__ ); ?>"></script>
  <script type="text/javascript" src="<?php echo plugins_url('js/respond.js', __FILE__ ); ?>"></script>
  <![endif]-->       
   <?php    

  
      /* Start - Adding User defined style to the Apply to Job Form */
     
        $wpaf_popup_params =  get_option('wpaf_response_popup');
        $wpaf_apply_button =  get_option('wpaf_apply_button');
       
        if(!empty($wpaf_popup_params)){
           $decoded_popup_params =  json_decode($wpaf_popup_params);
           $popup_width = $decoded_popup_params->width;
           $popup_height = $decoded_popup_params->height;
           $popup_textcolour = $decoded_popup_params->colour;
           $popup_bgcolour = $decoded_popup_params->bgcolour;
           $popup_style = $decoded_popup_params->style;
        }
        
        if(!empty($wpaf_apply_button)){
           $decoded_apply_button =  json_decode($wpaf_apply_button);
           $apply_btn_width = $decoded_apply_button->width;
           $apply_btn_height = $decoded_apply_button->height;
           $apply_btn_margin = $decoded_apply_button->margin;
           $apply_btn_margin_left = $decoded_apply_button->margin_left;
           $apply_btn_margin_right = $decoded_apply_button->margin_right;
           $apply_btn_margin_top = $decoded_apply_button->margin_top;
           $apply_btn_margin_bottom = $decoded_apply_button->margin_bottom;
           $apply_btn_padding_left = $decoded_apply_button->padding_left;
           $apply_btn_padding_right = $decoded_apply_button->padding_right;
           $apply_btn_padding_top = $decoded_apply_button->padding_top;
           $apply_btn_padding_bottom = $decoded_apply_button->padding_bottom;
           $apply_btn_style = $decoded_apply_button->style;
           $apply_btn_float_val = $decoded_apply_button->float_val;
        }
        
        
 
 ?>  
 

   
   <style type="text/css">    
   .wpaf_dialog_setting{ 
   font-family: inherit !important;
   font-size: inherit !important; 
 /*  position: fixed !important;       
  top: 10% !important;                 */
   <?php if(!empty($popup_style)){ echo $popup_style; }  ?>
   }
   .wpaf_dialog_setting .ui-button{
     font-family: inherit !important;
     font-size: inherit !important;
   }
   /*  .wpaf_dialog_setting  .ui-dialog-titlebar{ } -- for title  */
 
 
   
   .wpaf_dialog_setting .dialog p, .wpaf_dialog_setting .dialog span{
      <?php  if(!empty($popup_textcolour)){   
              echo 'color:'.$popup_textcolour.' !important;'; 
              } ?>
       <?php  if(!empty($popup_bgcolour)){   
              echo 'background-color:'.$popup_bgcolour.' !important;'; 
              } ?>        
    }
    /*background - color */
    .dialog{
            <?php  if(!empty($popup_bgcolour)){   
              echo 'background-color:'.$popup_bgcolour.' !important;'; 
              } ?>    
    }

    .applybtn{
     <?php  if(!empty($apply_btn_width)){    
         echo 'width: '.$apply_btn_width.'px !important;';
      }if(!empty($apply_btn_height)){   
         echo 'height: '.$apply_btn_height.'px !important;'; 
      }
     if(!empty($apply_btn_margin_left)){   
         echo 'margin-left: '.$apply_btn_margin_left.'px;';
      }
      if(!empty($apply_btn_margin_right)){    
         echo 'margin-right: '.$apply_btn_margin_right.'px;'; 
      }
      if(!empty($apply_btn_margin_top)){    
        echo 'margin-top: '.$apply_btn_margin_top.'px;'; 
      }
      if(!empty($apply_btn_margin_bottom)){   
       echo 'margin-bottom: '.$apply_btn_margin_bottom.'px;';
       }
      if(!empty($apply_btn_padding_left)){   
         echo 'padding-left: '.$apply_btn_padding_left.'px !important;'; 
      }
      if(!empty($apply_btn_padding_right)){   
         echo 'padding-right: '.$apply_btn_padding_right.'px !important;'; 
      }
      if(!empty($apply_btn_padding_top)){    
         echo 'padding-top : '.$apply_btn_padding_top.'px !important;'; 
      }
      if(!empty($apply_btn_padding_bottom)){    
         echo 'padding-bottom : '.$apply_btn_padding_bottom.'px !important;'; 
      }
     
      if(!empty($apply_btn_float_val)){  
         echo 'float : '.$apply_btn_float_val.' !important;'; 
      } 
      if(!empty($apply_btn_style)){   
          echo $apply_btn_style; 
      }  ?>
   }
  
   
   

   

   
   </style>
  <?php  /* End -Adding User defined style to the Apply to Job Form */ ?> 
   
    <div class="form_id">
    <div class="container_form">
    <div class="msg_div"> 
        <?php /* $this->splash_message(); */ ?>
    </div>
    <form class="af_clss" enctype="multipart/form-data" method="post"> 
    <div class="wp_title"> Apply for this job:</div>
     <?php 
        $wpaf_field_title =  get_option('wpaf_field_title');
        if(!empty($wpaf_field_title)){
           $af_fields =  json_decode($wpaf_field_title);
        }
        
        if(isset($af_fields)){
//CRINCH
$ASIF_FIELD_MARK=0;
        foreach($af_fields as $fld ){ 
            $compulsory = '';
            $parameter = $fld->field;
            $param = explode(':',$parameter);  
            $title = $fld->title;
            //CRINCH - block with if condition
            $final_string_new = '';
            if (strpos($fld->title,'@@') !== false) {
            	$param_custom_new = explode('@@',$fld->title);
            	$fld->title = $param_custom_new[0];
            	$title = $param_custom_new[0];
            	$final_string_new = $param_custom_new[1];
            	$final_string_new = str_replace('{','',$final_string_new);
            	$final_string_new = str_replace('}','',$final_string_new);
            }            
            if(isset($param[0])){     
                 if($param[1] == 1){
                       $compulsory = '<span style="color:red;">*</span>';
                  } 
                  
                   
                  ?>
                      <div> <label class="wp_labeltxt "> <?php echo $title.$compulsory; ?>: </label> 
                      <?php 
                      if(strcasecmp($param[0], FIELD_TYPE10) == 0){    /*EditAble PDF */    
						$custom_plugin_name = plugin_basename( __FILE__ );
						$custom_plugin_name = str_replace("apply_form.php","",$custom_plugin_name);
						$custom_plugin_dir_path = $custom_plugin_name;
						$custom_plugin_name = str_replace("-","_",$custom_plugin_name);
						//print $final_string_new;
						$file_pdf_name = str_replace('uploadedpdffiles/','',$final_string_new);
						$file_pdf_name = str_replace('.pdf','',$file_pdf_name);
						//print $file_pdf_name;
						$title_for_field_id = str_replace(" ","_",$title);
						$title_for_PDF_Hidden_field_id = str_replace(" ","",$title);
						?>

                     <!--CRINCH 30March2015-->
                     <div class="openDialogForPDFMain">
                     <!--<input type="hidden" name="customPdfFilePath[]" id="<?php echo $title_for_field_id.'_customPdfFilePath'; ?>" value="<?php echo $upload_dir."wp-content/uploads/".$custom_plugin_name.$final_string_new;?>">-->
                     <input type="hidden" name="customPdfFilePath" id="<?php echo $title_for_field_id.'_customPdfFilePath'; ?>" value="<?php //echo 'wp-content/plugins/candidate-application-form/downloadpdffile.php?fileUrl='.$upload_dir."wp-content/uploads/".$custom_plugin_name."&fileName=".$final_string_new;?><?php echo bloginfo('url').'/wp-content/plugins/'.$custom_plugin_dir_path.'downloadpdffile.php?fileUrl='.$upload_dir."wp-content/uploads/".$custom_plugin_name."&fileName=".$final_string_new;?>">
                     <input type="hidden" name="customPdfFilePathh" id="<?php echo $title_for_field_id.'_customPdfFilePathh'; ?>" value="<?php echo $upload_dir."wp-content/uploads/".$custom_plugin_name.$final_string_new;?>">
                     <input type="button" class="openDialogForPDF" id="<?php echo $title_for_field_id; ?>" value="Click here to open fillable PDF">
                     <span style="font-size:12px;color:green;" id="<?php echo $title_for_field_id.'_mentiondfileuploaded'; ?>" class="remove_me"></span>
                     </div>
                     <input type="hidden" name="userPdfUploadFileNewName[]" id="<?php echo $title_for_field_id.'_userPdfUploadFileNewName'; ?>" value="<?php echo $custom_plugin_name.md5(time().$title_for_field_id.$ASIF_FIELD_MARK);?>">
                     <div><input type="hidden" id="<?php echo $title_for_PDF_Hidden_field_id;?>_uploadornot" name="<?php //echo $title_for_PDF_Hidden_field_id;?><?php echo $title_for_field_id;?>" value="" class="inputclass makemeempty"></div>                     
                     <!--END CRINCH 26March2015-->                     
                     
                 <?php } ?>
                      </div>
                 <?php 
                 if(strcasecmp($param[0], FIELD_TYPE1) == 0){    /*text input */ ?>    
                    <div> <input type="text" name="<?php echo $title; ?>" class="inputclass" />
                     </div>
                <?php  }else if(strcasecmp($param[0], FIELD_TYPE2) == 0){    /*upload input */ ?>   
                  <div>  
                         <div class="uploader_class" style="float:left;">
                         <div class="container_class"  style="margin:0px;display:inline-block;">
                          <a class ="multiple_select" title = "Select images" alt = "Select images" tabindex="5" href="javascript:;">Choose File</a>
                          <a style="display:none;" class ="multiple_upldprev" title = "Upload &amp; Preview" alt = "Upload &amp; Preview" tabindex="6" href="javascript:;">Upload CV File</a>
                        </div>
                        <span class="span_error_class error"></span>   
                        <input type="hidden" class="uploaded_file_path_class" name="file_upload_path[<?php echo $title; ?>]">
                        <input type="hidden" class="original_filename_class" name="filename_original[<?php echo $title; ?>]"> 
                        <input type="hidden" class="filename_type" name="filename_type[<?php echo $title; ?>]" value="1" /> 
                     </div>  
                  </div>
				<!-- START OF CRINCH CUSTOM BLOCK OF CODE -->                  
                 <?php } if(strcasecmp($param[0], FIELD_TYPE8) == 0){    /*DropDOwn */    
                 	$myCustomArray = explode(',', $final_string_new);
                 	?>
                      <div>
                     <select id='<?php echo $fld->title; ?>' name='<?php echo $fld->title; ?>' class="inputclass">						  
						  <?php
						    for($io=0; $io<count($myCustomArray); $io++){
								$selected = '';
								$output = $myCustomArray[$io];
								if (strpos($myCustomArray[$io],'[') !== false) {
									$selected = "selected='selected'";		
									$output = explode('[', $output);
									$output = $output[0];
								}
							?>
						      <option value="<?php echo $output; ?>" <?php echo $selected;?>><?php echo $output; ?></option>
						  <?php						  
						    } ?>                     
                     </select>
                     </div>
                 <?php } if(strcasecmp($param[0], FIELD_TYPE9) == 0){    /*CheckBox */    
                 	$myCustomArrayCheckBox = explode(',', $final_string_new);
                 	?>                     
						  <?php
						    for($io=0; $io<count($myCustomArrayCheckBox); $io++){
								$checked = '';
								$outputCheckbox = $myCustomArrayCheckBox[$io];
								if (strpos($myCustomArrayCheckBox[$io],'[') !== false) {
									$checked = "checked='checked'";		
									$outputCheckbox = explode('[', $outputCheckbox);
									$outputCheckbox = $outputCheckbox[0];
								}
							?>
						      <div style='font-size:12px;'>
						      <!--<input type="checkbox" name="<?php echo $outputCheckbox; ?>" value="<?php echo $outputCheckbox; ?>" <?php echo $checked; ?>>&nbsp; <?php echo $outputCheckbox; ?></div>-->
						      <input type="checkbox" name="inputtypecheckbox<?php echo $title; ?>[]" value="<?php echo $outputCheckbox; ?>" <?php echo $checked; ?>>&nbsp; <?php echo $outputCheckbox; ?></div>
						      <!-- END OF CRINCH CUSTOM BLOCK OF CODE -->                  						      
						  <?php						  
						    } ?>                                          
                     
                 <?php } if(strcasecmp($param[0], FIELD_TYPE3) == 0){    /*longtext */    ?>
                     <div> <textarea name="<?php echo $title; ?>" class="inputclass"></textarea> </div>
                 <?php } if(strcasecmp($param[0], FIELD_TYPE4) == 0){   /*numeric/integer */    ?>
                    <div> <input type="text" name="<?php echo $title; ?>" class="inputclass" /> </div>
                 
                <?php }
                else if(strcasecmp($param[0], FIELD_TYPE6) == 0){    /*upload other */ ?>   
                  <div>  
                         <div class="uploader_class" style="float:left;">
                         <div class="container_class"  style="margin:0px;display:inline-block;">
                          <a class ="multiple_select" title = "Select images" alt = "Select images" tabindex="5" href="javascript:;">Choose File</a>
                          <a style="display:none;" class ="multiple_upldprev" title = "Upload &amp; Preview" alt = "Upload &amp; Preview" tabindex="6" href="javascript:;">Upload Other File</a>
                        </div>
                        <span class="span_error_class error"></span>   
                        <input type="hidden" class="uploaded_file_path_class" name="file_upload_path[<?php echo $title; ?>]">
                        <input type="hidden" class="original_filename_class" name="filename_original[<?php echo $title; ?>]"> 
                         <input type="hidden" class="filename_type" name="filename_type[<?php echo $title; ?>]" value="2" /> 
                     </div>  
                  </div>
                 <?php }
                
                 if(strcasecmp($param[0], FIELD_TYPE7) == 0){    /*email input */ ?>    
                    <div> <input type="text" name="<?php echo $title; ?>" class="inputclass" />
                     </div>
                     <div class="clear h20"></div>
                    <div> <label class="wp_labeltxt ">Confirm <?php echo $title.$compulsory; ?>: </label> </div>
                     <div> <input type="text" name="Confirm[<?php echo $title; ?>]" class="inputclass" />
                     </div>
                <?php  }  
                 
                 
                 
                 
                
                     ?>  
                                          
                  <div class="clear h20"></div>
                <?php 
                       }
                       $ASIF_FIELD_MARK = $ASIF_FIELD_MARK + 1;
               }
            } 
    ?>
    
    
    <div class="wp_applybtn">
    <?php 
          $http_str =  ((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == "on") ? "https" : "http");
          $Path=$_SERVER['REQUEST_URI'];
          $URI= $http_str.'://'.$_SERVER['SERVER_NAME'].$Path;
        
    ?>
      <input type="hidden" value="<?php echo $URI; ?>" name="current_page_url" /> 
      <input type="hidden" value="0" id="total_file_size" /> 
      <?php    
           $post_id = get_the_ID();  
          $nonce = wp_create_nonce("submit_ic-application_nonce");
          $link = admin_url("admin-ajax.php?action=submit_ic-application&post_id=$post_id&nonce=".$nonce);
          echo '<input  name="submit_save" type="button" class="applybtn btn apply_form" value="Apply"   data-nonce="' . $nonce . '" data-post_id="' . $post_id . '"  />';       ?>
        
    </div>
    <div class="dialog" title="Basic dialog">
     <?php if(DEBUG_MODE == 'on'){  ?>
       <span class="dialogcls">Please wait while sending details to server..</span>
     <?php }else if(DEBUG_MODE == 'off'){ 
        
        /*fetch wait msg from setting page */
        $wpaf_setting =  get_option('wpaf_setting');
        
        $wpaf_messages =  get_option('wpaf_messages'); 
        
        $decoded_setting =  json_decode($wpaf_setting);
        $decoded_messages =  json_decode($wpaf_messages); 
        
                      
        if(isset($decoded_setting->wait_setting) && $decoded_setting->wait_setting == 1){
          $wait_msg = $decoded_messages->wait_msg;
        }  
        if(isset($wait_msg)){  ?>
             <span class="dialogcls"><?php echo $wait_msg ?></span>
        
     <?php   }else{    ?>
         <span class="dialogcls">Please wait..</span>
     
     <?php
              }
      } ?>
   </div>
 
</form>
</div>
<!--CRINCH 26March2015-->
   <div id="dialogCrinch" title="Basic dialog Crinch">
   </div>
<!--END CRINCH 26March2015-->   
</div>

<!--End of responsive form design-->   
<script type="text/javascript">
        jQuery(document).ready(function(){


    		/*CRINCH 26March2015*/
     	   jQuery(".openDialogForPDF").click( function() {
         	   var use_id = this.id;
         	   var mystring = use_id;
         	   mystringg = mystring.split('_').join('');
     		   showHideUploadPDFInputField = "style='display:none'";
     		   var custom_pdf_file_path = jQuery('#'+use_id+'_customPdfFilePath').val();
     		  var custom_pdf_file_path_new = jQuery('#'+use_id+'_customPdfFilePathh').val();
     		   var custom_pdf_file_new_name = jQuery('#'+use_id+'_userPdfUploadFileNewName').val();
     		   var current_file_path_change_to_new_id = use_id+'_userPdfUploadFileNewName';
     		   custom_pdf_file_path = custom_pdf_file_path.split('|');
     		   custom_pdf_file_path = custom_pdf_file_path[0];
     		   var custom_pdf_file_name = jQuery('#'+use_id+'_customPdfFilePath').val();
     		   var pdf_text_message = "Please download the PDF by clicking the 'Download' link below. Once downloaded, open the file and fill in the appropriate details. Now all the details have been filled in, save your file with a new name then click 'Upload' to re-upload the PDF document.";
     	      jQuery("#dialogCrinch" ).dialog( "open"  ); 
     	      jQuery("#dialogCrinch").append("<form id='form_dialog_pdf' method='post' enctype='multipart/form-data'><table class='wpaf_dialog_table'><tr><td><p style='font-size:12px;color:green;'>"+pdf_text_message+"<br /><a id='download_pdf_link' style='font-size:12px;color:red;' onclick = 'show_dialog_pdf_input_field();' href='"+custom_pdf_file_path+"' download='"+custom_pdf_file_name+"'><input type='button' value='Download PDF'></a></p></td></tr><tr id='pdf_upload_dialog_row'><td align='center'><input name='file' id='dialog_fileupload' type='file' size='15' multiple/><div class='spinner' style='float:left;font-size:14px;color:green;display:none;'>Uploading....</div><div id='showPDFfileOnly'style='color:red;font-size:10px;'>Only PDF files!</div><input type='hidden' name='new_pdf_file_name' id='new_pdf_file_name' value='"+custom_pdf_file_new_name+"'></td></tr></table><input type='hidden' id='PDFUPLOADEDORNOT' value='"+mystringg+"_uploadornot'><input type='hidden' id='PDFUPLOADEDORNOT_NEW' value='"+custom_pdf_file_path_new+"'><input type='hidden' id='PDFUPLOADEDORNOT_PROGRESS' value='"+mystring+"'><input type='hidden' id='Chambeli' value='"+current_file_path_change_to_new_id+"'></form>");
     	   });
  
     	   /*END CRINCH 26March2015*/
        
			<!--CRINCH 26March2015-->
          jQuery("#dialogCrinch").dialog({
            autoOpen: false,
            title: "Download and Upload PDF",
            modal: true,
            minHeight: 300,
            minWidth: 500, 
            buttons: {
              Ok: function() {
            	  jQuery("div.spinner").show('fast'); 
                 //var dialog_pdf_upload_file_name = jQuery('#userPdfUploadFileNewName').val();
                 var chambeli_val = jQuery('#Chambeli').val();
                 var dialog_pdf_upload_file_name = jQuery('#new_pdf_file_name').val();
                 if(dialog_pdf_upload_file_name==''){
	    			 alert('Please refresh your page and Try Again!');
	    			 return false;
                 }
	      		 var fd_dialog_pdf_upload = new FormData();
	      		var final_string_dialogPDF = '';
	    		 var file_dialog_pdf_upload = jQuery('#dialog_fileupload').prop('files')[0];
	    		 if(!file_dialog_pdf_upload){
	    			 alert('Please try again and select PDF file!');
	    			 return false;
	    		 }
	    		 if(file_dialog_pdf_upload.name==''){
	    			 alert('Please try again and select PDF file!');
	    			 return false;
	    		 }
	    		 var ext = jQuery('#dialog_fileupload').val().split('.').pop().toLowerCase();
	    		 if(ext!='pdf'){
	    			 alert('Please try again and select PDF file only!');
	    			 return false;
	    		 }
	    		 fd_dialog_pdf_upload.append("file", file_dialog_pdf_upload);
	    		 fd_dialog_pdf_upload.append("name", file_dialog_pdf_upload.name);
	    		 fd_dialog_pdf_upload.append("newname", dialog_pdf_upload_file_name);    		 
	    		 fd_dialog_pdf_upload.append("caption", 'asifarif');  
	    		 fd_dialog_pdf_upload.append('action', 'crinch_custom_file_upload_dialog');
	    		 var break_whole_loop_dialogPDF = false;
	 		    jQuery.ajax({
			        type: 'POST',
			        url: myAjax.ajaxurl,
			        data: fd_dialog_pdf_upload,
			        contentType: false,
			        processData: false,
			        async: false,
			        beforeSend: function() {
			        	jQuery('div.spinner').show('fast');
			        },
			        complete: function(){
			        	var delay1 = 2000;
						setTimeout(function() {
							jQuery('div.spinner').html('Uploaded!');
						}, delay1);
			        },
			        success: function(dialogPDFUploadResponse){
						//CRINCH -
						var pdf_text_field_upload_or_not_id =  jQuery('#PDFUPLOADEDORNOT').val();
						putPathOfPDFFile = jQuery('#PDFUPLOADEDORNOT_NEW').val();
						fileUploadProgress = jQuery('#PDFUPLOADEDORNOT_PROGRESS').val();
						
						//new block
						var delay = 3000;
						setTimeout(function() {
							jQuery('div.spinner').hide('slow');
							jQuery("#dialogCrinch").dialog('close');
							jQuery('#form_dialog_pdf').remove();
							jQuery("#dialogCrinch").html('');
						}, delay);
						jQuery('div.spinner').html('Uploading...');
						setTimeout(function() {	
						jQuery('#'+fileUploadProgress+'_mentiondfileuploaded').html("Thanks, File Uploaded!");
						}, delay);					
						//new block
						
			        	var json = jQuery.parseJSON(dialogPDFUploadResponse);
			        	jQuery(json).each(function(i,val){
			        		if(break_whole_loop_dialogPDF){
			        			return false;
			        		}
			        		jQuery.each(val,function(k,v){
			        	          if(k=='error'){
			        	        	  break_whole_loop_dialogPDF = true;
			        	        	  return false;
			        	          }
			        	          if(k=='filePath' && v!=''){
			        	        	  stringToRemove = putPathOfPDFFile.split('/').pop();
			        	        	  putPathOfPDFFile2 = putPathOfPDFFile.replace(stringToRemove,v);
			        	        	  jQuery('#'+pdf_text_field_upload_or_not_id).val("FILEUPLOADED|"+putPathOfPDFFile2);			        	        	  
			        	        	  jQuery('#'+chambeli_val).val(v);
			        	        	  final_string_dialogPDF = val[k];
			        	        	  break_whole_loop_dialogPDF = true;
			        	        	  return false;
			        	          }		        	          
			        	});
			        	});
			        },
			    	error: function (returnval) {
			        alert('Sorry, there is REQUEST problem: '+returnval);
			        return false;
			    	}
			    });	//end of ajax request
			    if(final_string_dialogPDF==''){
			    	alert('PDF file uploading problem!');
			    	return false;
			    }			    	    		 	    		                
               //jQuery(this).dialog("close");
               //jQuery('#form_dialog_pdf').remove();
              }
            },
           close: function( event, ui ) {
  		         jQuery("#dialogCrinch").html(''); 
           }       
        
        });

          <!--END CRINCH 26March2015-->

            
           jQuery(".dialog").dialog({ autoOpen: false,
              title: "Application Status",
              modal: true,
              minHeight: 49,
              minWidth: 500, 
              <?php 
              if(!empty($popup_width)){  ?>
              width: <?php echo $popup_width; ?>,
              <?php }if(!empty($popup_height)){  ?>
              height: <?php echo $popup_height; ?>,    <?php } ?>
              dialogClass: "wpaf_dialog_setting",            
              buttons: {
                Ok: function() {
                  jQuery( this ).dialog( "close" );
                  jQuery('.dialog p').remove();   
                  jQuery(".af_clss")[0].reset();
		  jQuery(".makemeempty").val("");  //CRINCH - april 30
		  jQuery("span.remove_me").html("");  //CRINCH - april 30                
                  jQuery(".span_error_class").each(function() {  
                     var elm_id = jQuery(this).attr("id"); 
                     jQuery("#"+elm_id).empty();  
                  });
                  jQuery(".wp_af_validation_error").empty();  
                }
            } });       
           });      
    </script>
  <?php 
  return ob_get_clean();         
      }
    
 /* Name: admin_candidate_apply_form
   Parameters:
   Use: The Settings page in admin to control the creation of apply form on the front end 
 */     
 function admin_candidate_apply_form(){   
   global $wpdb;
?>

<div id="f_container">
<form id="admin_ic_apply" action="admin-post.php" method="post">

<div class="ftitle">Candidate Application Form</div>
<div class="msg_div"> 
    <?php $this->splash_message(); ?>
</div>
<div id="wpaf_error"></div>

<?php

    $wpaf_setting =  get_option('wpaf_setting');
    $decoded_setting =  json_decode($wpaf_setting);

    if(isset($decoded_setting->activation_key)){
      $activation_key = $decoded_setting->activation_key;
    }
    if(isset($decoded_setting->activation_email)){
      $activation_email_address = $decoded_setting->activation_email;
    }
    if(isset($decoded_setting->destination_email)){
      $destination_email_address = $decoded_setting->destination_email;
    }
 
 /*Some Difficult Logical Arithmetic*/
   $SD54SS = md5($activation_email_address);
   $CC12NM = "8f213a31b3d5f921fb6ff6c0333af826";
   $RT99IO = $SD54SS.$CC12NM;
   $GG13DS = md5($RT99IO);
   $AS33ER = md5($SD54SS.$CC12NM);    
   $disabled = '';
   $matchODLE = 0;
   if($GG13DS == $activation_key){
     $matchODLE = 1;
   }
   else{
     $matchODLE = 0;
     $disabled = "disabled = 'disabled'";
   }  
?>

<div id= "wp_fieldcontainer">
<div class="wp_asub_title"><span class="wpaf_tooltip trigger"  onmouseover = "OpenDiv('<?php echo APPLY_FORM_SHORTCODE_TOOLTIP; ?>');">?</span>  
<span class="wp_swtbtn"><label>Apply Form shortcode </label></span> 
<span class="wp_swtbtn"><label> [apply-form] </label> </span> 
</div>
<?php /*Activation key box */ ?>
<div id="wpaf_activation_maincontainer">
<div class="wp_asub_title">
<label> Activation Settings </label>
</div>
<div class="h10 clear"></div> 
<div id="wpaf_activation_subcontainer">
<table width=" ">
   <tbody>

    <tr> 
    <td width=" "><span class="wpaf_tooltip trigger"  onmouseover = "OpenDiv('<?php echo UPGRADE_ACTIVATION_KEY_TOOLTIP; ?>');">&#63;</span><label class="wp_formlabel">Upgrade Activation Key:</label> </td>
    <td width=" "> 
    <input type="text" class="wp_textfield_script" name="setting[activation_key]" placeholder=""  value="<?php  if(isset($activation_key)){ echo $activation_key; } ?>" /> 
    </td>
    </tr>
    <tr> 
    <td width=" "><span class="wpaf_tooltip trigger"  onmouseover = "OpenDiv('<?php echo ACTIVATION_EMAIL_TOOLTIP; ?>');">?</span><label class="wp_formlabel">Activation Email:</label> </td>
    <td width=" "> 
    <input type="text" class="wp_textfield_script" id="activation_email" name="setting[activation_email]" placeholder=""  value="<?php  if(isset($activation_email_address)){ echo $activation_email_address; } ?>" />
    </td>
    </tr> 
   <tr> 
    <td width=" "><span class="wpaf_tooltip trigger"  onmouseover = "OpenDiv('<?php echo DESTINATION_EMAIL_TOOLTIP; ?>');">?</span><label class="wp_formlabel">Destination Email:</label> </td>
    <td width=" "> 
    
    <input type="text" class="wp_textfield_script" id="destination_email" name="setting[destination_email]" placeholder=""  value="<?php  if(isset($destination_email_address)){ echo $destination_email_address; } ?>" /> 
    </td>
    </tr> 
        
 </tbody>
</table>
</div> 
</div>
<div class="h20 clear"></div> 
<div class="h20 clear"></div> 

<?php /*End: Activation key */ ?> 

<?php
     if(APPLY_FORM_EDITION != 'free'){   
      $wpaf_new_fields =  get_option('wpaf_available_field_title');
     }
      $wpaf_fields =  get_option('wpaf_field_title');
      if(!empty($wpaf_new_fields)){
         $decoded_new_fields =  json_decode($wpaf_new_fields);
      }
      if(!empty($wpaf_fields)){
         $decoded_form_fields =  json_decode($wpaf_fields);
      }
?>

<div id="avail_fields">
<label class="wp_a_fields">
<span class="wpaf_tooltip trigger"  onmouseover = "OpenDiv('<?php echo AVAILABLE_FIELDS_TOOLTIP; ?>');">?</span>Available Fields </label>     
<div class="h10 clear"></div> 

<div id="wp_ai_flds" class="section_border"> 
<ul><li style="display:none;" class=""></li>   
<?php
if(isset($decoded_new_fields)){
foreach($decoded_new_fields as $new_field ){  

    $compulsory = '';
    $parameter = $new_field->field;
    $param = explode(':',$parameter);
    //CRINCH
    $final_string_new = '';
    if (strpos($new_field->title,'@@') !== false) {
		$param_custom_new = explode('@@',$new_field->title);
		$new_field->title = $param_custom_new[0];
		$final_string_new = $param_custom_new[1];
		$final_string_new = str_replace('{','',$final_string_new);
		$final_string_new = str_replace('}','',$final_string_new);
    }
    //END CRICNH
    
    if($param[1] == 1){
         $compulsory = '<span style="color:red;">*</span>';
        }
?>
	<!-- edit this below line - crinch -->
    <!--<li class='available_fields'><label><?php echo $compulsory." ".$param[0].":".$new_field->title; ?></label><input name='av_field[]' class='temp_cls1' type='hidden'  value='<?php echo $param[0].":".$param[1]; ?>' /><input name='av_title[]' class='temp_cls2' type='hidden' value='<?php echo $new_field->title; ?>'></li>  -->
    <li class='available_fields'><label><?php echo $compulsory." ".$param[0].":".$new_field->title; ?></label><input name='av_field[]' class='temp_cls1' type='hidden'  value='<?php echo $param[0].":".$param[1]; ?>' /><input name='av_title[]' class='temp_cls2' type='hidden' value='<?php echo $new_field->title; ?>'><input name='av_options[]' class='temp_cls3' type='hidden' value='<?php echo $final_string_new; ?>'></li>
 <?php
   }
 }  
 
 ?>    
</ul> 


<input  id="add_new_button" type="button"  class="ui-button wp_apply_button" value="Add New Field" name="submit_field" <?php if(APPLY_FORM_EDITION == 'free'){ echo "disabled = 'disabled'"; } ?> />
 
<?php
    if(APPLY_FORM_EDITION == 'free'){    
       echo "<br /><br />Upgrade to the professional edition of Apply Form to use this feature.";
    }

?>


</div> 
</div>


<div id="apply_fields">
<label class="wp_apply_fields"><span class="wpaf_tooltip trigger"  onmouseover = "OpenDiv('<?php echo APPLY_FORM_FIELDS_TOOLTIP; ?>');">?</span>Apply Form Fields</label>
<div class="h10 clear"></div> 
<div id="wp_apply_flds" class="section_border" >     
<ul>  <li style="display:none;" class=""> </li> 
<?php
if(isset($decoded_form_fields)){
foreach($decoded_form_fields as $af_field ){  

    $compulsory = '';
    $parameter = $af_field->field;
    $param = explode(':',$parameter);
    //CRINCH
    $final_string = '';
    if (strpos($af_field->title,'@@') !== false) {
		$param_custom = explode('@@',$af_field->title);
		$af_field->title = $param_custom[0];
		$final_string = $param_custom[1];
		$final_string = str_replace('{','',$final_string);
		$final_string = str_replace('}','',$final_string);
    }
    //END CRICNH
    if($param[1] == 1){
         $compulsory = '<span style="color:red;">*</span>';
     }
?>
	<!-- edit this below line - crinch -->
   <!--<li class='available_fields ui-draggable'><label><?php echo $compulsory." ".$param[0].":".$af_field->title; ?></label><input name='af_field[]' class='temp_cls1' type='hidden'  value='<?php echo $param[0].":".$param[1]; ?>' /><input name='af_title[]' class='temp_cls2' type='hidden' value='<?php echo $af_field->title; ?>'></li>  -->
   <li class='available_fields ui-draggable'><label><?php echo $compulsory." ".$param[0].":".$af_field->title; ?></label><input name='af_field[]' class='temp_cls1' type='hidden'  value='<?php echo $param[0].":".$param[1]; ?>' /><input name='af_title[]' class='temp_cls2' type='hidden' value='<?php echo $af_field->title; ?>'><input name='af_options[]' class='temp_cls3' type='hidden' value='<?php echo $final_string; ?>'></li>
<?php }
  }
?>   
</ul> 
</div> 
</div>



<!-- Add here -->
</div>

<div class="wp_asub_title">
<label class="wp_af_lbl_cls"><span class="wpaf_tooltip trigger"  onmouseover = "OpenDiv('<?php echo SCRIPT_CONFIG_TOOLTIP; ?>');">?</span>Script Config</label>
</div>
  <?php  
      
      /*$wpaf_setting =  get_option('wpaf_setting');     */
      $wpaf_headers =  get_option('wpaf_headers');
      $wpaf_parameters =  get_option('wpaf_parameters');
      
      $decoded_setting =  json_decode($wpaf_setting);
   
      $decoded_headers =  json_decode($wpaf_headers);
      $decoded_parameters = json_decode($wpaf_parameters);
      
      if(APPLY_FORM_EDITION == 'free'){ 
        $scriptname = 'Apply_Form_email_script.php';
        $readonly = 'readOnly';
      }else{  
        if(isset($decoded_setting->scriptname)){
           $scriptname = $decoded_setting->scriptname;
        }
      }
      

      
      
      
?>  
<div id="intervw_form">

<table width=" ">    
<tbody>
<tr> 
<td width=" "><label class="wp_formlabel"><span class="wpaf_tooltip trigger"  onmouseover = "OpenDiv('<?php echo SCRIPT_NAME_TOOLTIP; ?>');">?</span>Script Name:</label> </td>
<td width=" "> 

<input type="text" class="wp_textfield_script" name="setting[scriptname]" placeholder=""  value="<?php  if(isset($scriptname)){ echo $scriptname; } ?>" <?php if(isset($readonly)){ echo $readonly;  } ?> /> 

</td>
</tr>
<tr> 
<td width="" height=" "><label><span class="wpaf_tooltip trigger"  onmouseover = "OpenDiv('<?php echo HEADER_VARIABLES_TOOLTIP; ?>');">?</span>Header Variables:</label> </td>
<td width="">

<div id="wp_adheaderintvw">
<?php if(!empty($decoded_headers)){ 
         $i = 0;
         foreach($decoded_headers as $head_arr ){  ?>
         <p>
           <input class="header_cls" type="text"  name="header[<?php echo $i; ?>][name]" placeholder="name" value="<?php echo $head_arr->name;  ?>" />
<input type="text" name="header[<?php echo $i; ?>][value]" value="<?php echo $head_arr->value;  ?>" placeholder="value" /> 
  <span  id="remScnt_<?php echo $i; ?>" class="remintv" style="cursor:pointer " onclick="removeFn(this.id);"> </span>
   </p>

            
      <?php   $i++;   
         }
       
       } ?>


</div>

<input  id="button_head" type="button" value="Add Another Header" name="submit_header"  onClick="" <?php if(APPLY_FORM_EDITION == 'free'){ echo "disabled = 'disabled'"; } ?>  /> 

</td>
</tr>
<tr> 
<td width=""><label style="width:176px;"><span class="wpaf_tooltip trigger"  onmouseover = "OpenDiv('<?php echo PARAMETER_VALUES_TOOLTIP; ?>');">?</span>Parameter Variables:</label></td>
<td width=""> 
<div id="wp_dropdivint"> 
<div class="wp_dropinputint">
 
 <?php if(!empty($decoded_parameters)){
   $j = 0;
    foreach($decoded_parameters as $param_arr ){ 
 ?>
      <p>  
          <input class="parameter_cls" type="text" name="parameter[<?php echo $j; ?>][name]" placeholder="name" value="<?php echo $param_arr->name; ?>" />  
          <input type="text" name="parameter[<?php echo $j; ?>][value]" value="<?php echo $param_arr->value; ?>" placeholder="value" /> 
          <span id="parameter_<?php echo $j; ?>" class="wp_reminv" style="cursor:pointer " onclick="remFn(this.id);"> </span>
     </p>
  <?php
   $j++;
   }
 }
 ?>

</div>
 
 </div> <input type="button" value="Add Another Parameter" id= "wp_addparameter"name="submit_header"  onClick=""  <?php if(APPLY_FORM_EDITION == 'free'){ echo "disabled = 'disabled'"; } ?>   /> 

</td>
</tr>
</tbody>
</table> 

<div id ="wp_intpara_container">
<label class="intclass"><span class="wpaf_tooltip trigger"  onmouseover = "OpenDiv('<?php echo AVAILABLE_VARIABLES_TOOLTIP; ?>');">?</span>Available Variables</label>
<?php
   /*obtain list of dynamic varibles */
  $querystr = "select post_id from wp_pmxi_posts limit 1";
  $pmxi_post = $wpdb->get_results($querystr, OBJECT);
  if(!empty($pmxi_post)){
       $post_arr = $pmxi_post[0];
       $post_id  = $post_arr->post_id;
       $querystr = "SELECT wp_postmeta.meta_key
                      FROM wp_postmeta
                      WHERE wp_postmeta.post_id = $post_id";                    
                      

       $post_meta = $wpdb->get_results($querystr, OBJECT);
  
  }
  



?>
<div id="wp_ai_parvariable"> 
<ul> 
    <li class=" wp_addinputint">PostId from Wordpress Post created In Import<span style="display:none;">WPpostURL</span></li>
    <li class=" wp_addinputint">Custom Field: Current Page URL</li>  
    <?php if(isset($post_meta)){
             foreach($post_meta as $result) {   ?>
             <li class=" wp_addinputint">Custom Field: <?php echo $result->meta_key; ?></li> 
    <?php
             }
          }
   ?>

</ul> 
</div>
</div> 


</div>
<?php /* Start: Apply Button */ ?>
<div class="h10 clear"></div> 

<div id="apply_btnoption">
<div class="wp_asub_title">
<label><span class="wpaf_tooltip trigger"  onmouseover = "OpenDiv('<?php echo APPLY_BUTTON_APPEARANCE_TOOLTIP; ?>');">?</span>Apply Button Appearance</label>
<?php

 if(APPLY_FORM_EDITION != 'free'){  
      $wpaf_apply_button =  get_option('wpaf_apply_button');
      $wpaf_response_popup =  get_option('wpaf_response_popup');
      $decoded_apply_button =  json_decode($wpaf_apply_button);
      $decoded_response_popup =  json_decode($wpaf_response_popup);
      
      
      /*filter out the values for single and double quote */
       if(isset($decoded_apply_button->style)){
        $applybtn_style = $this->filter_output($decoded_apply_button->style);
       }
       
       if(isset($decoded_apply_button->width)){
        $applybtn_width = $this->filter_output($decoded_apply_button->width);
       }
       if(isset($decoded_apply_button->height)){
        $applybtn_height = $this->filter_output($decoded_apply_button->height);
       }
       
       if(isset($decoded_apply_button->position)){
        $applybtn_position = $this->filter_output($decoded_apply_button->position);
       }
       
       if(isset($decoded_apply_button->margin_left)){
        $applybtn_margin_left = $this->filter_output($decoded_apply_button->margin_left);
       }
        if(isset($decoded_apply_button->margin_right)){
        $applybtn_margin_right = $this->filter_output($decoded_apply_button->margin_right);
       }
        if(isset($decoded_apply_button->margin_top)){
        $applybtn_margin_top = $this->filter_output($decoded_apply_button->margin_top);
       }
        if(isset($decoded_apply_button->margin_bottom)){
        $applybtn_margin_bottom = $this->filter_output($decoded_apply_button->margin_bottom);
       }
       
       if(isset($decoded_apply_button->padding_right)){
        $applybtn_padding_right = $this->filter_output($decoded_apply_button->padding_right);
       }
       if(isset($decoded_apply_button->padding_left)){
        $applybtn_padding_left = $this->filter_output($decoded_apply_button->padding_left);
       }
         if(isset($decoded_apply_button->padding_top)){
        $applybtn_padding_top = $this->filter_output($decoded_apply_button->padding_top);
       }
         if(isset($decoded_apply_button->padding_bottom)){
        $applybtn_padding_bottom = $this->filter_output($decoded_apply_button->padding_bottom);
       }
        if(isset($decoded_apply_button->float_val)){
        $applybtn_float_val = $this->filter_output($decoded_apply_button->float_val);
       }
    }

     ?>       
      
</div>
<div class="h10 clear"></div> 

<div id="apply_form_appearance">
<table>  
  <tr>
    <td><label>Width:</label></td>
    <td><input type="text" class="wp_textfield_input_medium" name="apply_button[width]" placeholder="100" onkeypress="javascript: return isNumberKey(event);" value="<?php  if(isset($applybtn_width)){ echo $applybtn_width; } ?>"   <?php echo $disabled; ?> />&nbsp;px
    </td>       
    <td><label style="">Height:</label></td>
    <td><input type="text" class="wp_textfield_input_medium" name="apply_button[height]" placeholder="30" onkeypress="javascript: return isNumberKey(event);" value="<?php  if(isset($applybtn_height)){ echo $applybtn_height; } ?>" <?php echo $disabled; ?> />&nbsp;px
    
    </td>
  </tr>

  <tr>
    <td><label class="wp_afadmin_labelwidth">Margin-Left:</label></td>
    <td><input type="text" class=" wp_textfield_input_medium" name="apply_button[margin_left]" placeholder="0" onkeypress="javascript: return isNumberKey(event);" value="<?php  if(isset($applybtn_margin_left)){ echo $applybtn_margin_left; } ?>" <?php echo $disabled; ?> />&nbsp;px</td>
    <td><label>Margin-Right:</label></td>
    <td><input type="text" class=" wp_textfield_input_medium" name="apply_button[margin_right]" placeholder="0" onkeypress="javascript: return isNumberKey(event);" value="<?php  if(isset($applybtn_margin_right)){ echo $applybtn_margin_right; } ?>" <?php echo $disabled; ?> />&nbsp;px</td>

  </tr>
    <tr>
    <td><label>Margin-Top:</label></td>
    <td><input type="text" class=" wp_textfield_input_medium" name="apply_button[margin_top]" placeholder="0" onkeypress="javascript: return isNumberKey(event);" value="<?php  if(isset($applybtn_margin_top)){ echo $applybtn_margin_top; } ?>" <?php echo $disabled; ?> />&nbsp;px</td>
    <td><label>Margin-Bottom:</label></td>
    <td><input type="text" class=" wp_textfield_input_medium" name="apply_button[margin_bottom]" placeholder="0" onkeypress="javascript: return isNumberKey(event);" value="<?php  if(isset($applybtn_margin_bottom)){ echo $applybtn_margin_bottom; } ?>" <?php echo $disabled; ?> />&nbsp;px</td>

  </tr>
  
  <tr>
    <td><label>Padding-Left:</label></td>
    <td> <input type="text" class="wp_textfield_input_medium" name="apply_button[padding_left]" placeholder="0" onkeypress="javascript: return isNumberKey(event);" value="<?php  if(isset($applybtn_padding_left)){ echo $applybtn_padding_left; } ?>" <?php echo $disabled; ?> />&nbsp;px</td>
    <td><label>Padding-Right:</label></td>
    <td> <input type="text" class="wp_textfield_input_medium" name="apply_button[padding_right]" placeholder="0" onkeypress="javascript: return isNumberKey(event);" value="<?php  if(isset($applybtn_padding_right)){ echo $applybtn_padding_right; } ?>" <?php echo $disabled; ?> />&nbsp;px</td>
  </tr>
  
  <tr>
    <td><label>Padding-Top:</label></td>
    <td> <input type="text" class="wp_textfield_input_medium" name="apply_button[padding_top]" placeholder="0" onkeypress="javascript: return isNumberKey(event);" value="<?php  if(isset($applybtn_padding_top)){ echo $applybtn_padding_top; } ?>" <?php echo $disabled; ?> />&nbsp;px</td>
    <td><label>Padding-Bottom:</label></td>
    <td> <input type="text" class="wp_textfield_input_medium" name="apply_button[padding_bottom]" placeholder="0" onkeypress="javascript: return isNumberKey(event);" value="<?php  if(isset($applybtn_padding_bottom)){ echo $applybtn_padding_bottom; } ?>" <?php echo $disabled; ?> />&nbsp;px</td>
  </tr>
  <tr>
    <td><label>Float:</label> </td>    
    <td colspan="3">
    <?php
     $selected_left = '';
     $selected_right = '';
    if(isset($applybtn_float_val) && ($applybtn_float_val == 'left')){
          $selected_left ="selected='selected'";
    }
    else if(isset($applybtn_float_val) && ($applybtn_float_val == 'right')){
         $selected_right ="selected='selected'";
    }
    ?>
     <select name="apply_button[float_val]"  id="select" class="wp_selectclass" <?php echo $disabled; ?> >
        <option></option>
        <option value="left" <?php echo $selected_left; ?>>Left</option>
        <option value="right" <?php echo $selected_right; ?>>Right</option>
    </select> </td>  
  </tr>
  <tr>
    <td scope="col"><label>Style:</label></td>
    <td scope="col" colspan="3"> <input type="text" class="wp_textfield_input" name="apply_button[style]" placeholder="border-width: 1px !important; border-radius: 5px !important;" value="<?php  if(isset($applybtn_style)){ echo $applybtn_style; } ?>" <?php echo $disabled; ?> /> </td>
  </tr>
 </table>
</div>
</div> 







<?php /* End: Apply Button */ ?>
<div class="h10 clear"></div> 

<div id="response_popup">
<div class="wp_asub_title"><span class="wpaf_tooltip trigger"  onmouseover = "OpenDiv('<?php echo RESPONSE_POPUP_APPEARANCE_TOOLTIP; ?>');">?</span><label>Response Pop-Up Appearance</label>
<?php 
 if(APPLY_FORM_EDITION != 'free'){    
       if(isset($decoded_response_popup->width)){
        $response_popup_width = $this->filter_output($decoded_response_popup->width);
       }
       if(isset($decoded_response_popup->height)){
        $response_popup_height = $this->filter_output($decoded_response_popup->height);
       }
        if(isset($decoded_response_popup->colour)){
        $response_popup_colour = $this->filter_output($decoded_response_popup->colour);
       }
       if(isset($decoded_response_popup->bgcolour)){
        $response_popup_bgcolour = $this->filter_output($decoded_response_popup->bgcolour);
       }
        if(isset($decoded_response_popup->style)){
        $response_popup_style = $this->filter_output($decoded_response_popup->style);
       }
        if(isset($decoded_response_popup->position)){
        $response_popup_position = $this->filter_output($decoded_response_popup->position);
       }
      
  }     
  

 ?>
 
</div>
<div class="h10 clear"></div> 
<div id="response_popup_appearance">
<table width=" ">
  <tr>
  <td><label>Width:</label></td>
  <td><input type="text" class="wp_textfield_input_medium" name="response_popup[width]" placeholder="300" onkeypress="javascript: return isNumberKey(event);" value="<?php  if(isset($response_popup_width)){ echo $response_popup_width; } ?>" <?php echo $disabled; ?> />&nbsp;px </td>
 
  <td> 
  <label style="margin-right:-15px;">Height:</label></td> <td><input type="text" class="wp_textfield_input_medium" name="response_popup[height]" placeholder="300" onkeypress="javascript: return isNumberKey(event);" value="<?php  if(isset($response_popup_height)){ echo $response_popup_height; } ?>" <?php echo $disabled; ?> />&nbsp;px
  </td>
  </tr>
  <tr>
   <td><label>Text Colour:</label></td>
   <td colspan="3"> <input type="text" class="wp_textfield_input_mid" name="response_popup[colour]" placeholder="#000000" value="<?php  if(isset($response_popup_colour)){ echo $response_popup_colour; } ?>" <?php echo $disabled; ?> /></td>
  </tr>
    <tr>
   <td><label>Background:</label></td>
   <td colspan="3"> <input type="text" class="wp_textfield_input_mid" name="response_popup[bgcolour]" placeholder="#000000" value="<?php  if(isset($response_popup_bgcolour)){ echo $response_popup_bgcolour; } ?>" <?php echo $disabled; ?> /></td>
  </tr>
  
  <tr>
    <td><label>Style:</label></td>
    <td colspan="3"> <input type="text" class="wp_textfield_input" name="response_popup[style]" placeholder="border-width: 1px; border-radius: 5px;" value="<?php  if(isset($response_popup_style)){ echo $response_popup_style; } ?>" <?php echo $disabled; ?> /></td>
  </tr>
  
</table>
</div>

</div> 
<?php /*Default Messages  */ ?>
<!--Message-->
<div class="h10 clear"></div> 
<div class="response_msg">
<div class="wp_asub_title"><span class="wpaf_tooltip trigger"  onmouseover = "OpenDiv('<?php echo MESSAGES_TOOLTIP; ?>');">?</span><label>Messages</label>
<?php 
      /*settings*/ 
      if(isset($decoded_setting->wait_setting)){
        $wait_setting = $decoded_setting->wait_setting;
       }
        if(isset($decoded_setting->success_setting)){
        $success_setting = $decoded_setting->success_setting;
       }
       if(isset($decoded_setting->failure_setting)){
        $failure_setting = $decoded_setting->failure_setting;
       }


     /*messages */
      $wpaf_messages =  get_option('wpaf_messages');
      $decoded_message =  json_decode($wpaf_messages);
      
       if(isset($decoded_message->wait_msg)){
        $wait_msg = $this->filter_output($decoded_message->wait_msg);
       }
        if(isset($decoded_message->success_msg)){
        $success_msg = $this->filter_output($decoded_message->success_msg);
       }
       if(isset($decoded_message->failure_msg)){
        $failure_msg = $this->filter_output($decoded_message->failure_msg);
       }

 ?>
 </div>
 <div class="h10 clear"></div> 
 <div id="message_box"> 
 <table >

  <tr>
  <td scope="col"><label>Wait:</label></td> 
  <td scope="col"> <input class="wp_afadmin_radio" type="radio" id="wait_setting_1" name="setting[wait_setting]" value="1" <?php  if(isset($wait_setting) && ($wait_setting == 1)  ){ echo "checked"; } ?> />On
  <input class="wp_afadmin_radio" type="radio" id="wait_setting_2" name="setting[wait_setting]" value="0" <?php if(isset($wait_setting) && ($wait_setting == 0) ){ echo "checked"; }else if(!isset($wait_setting)){ echo "checked";   } ?> />Off
  </td>
  </tr>
    <tr>
  <td scope="col"></td>
  <td  class="wp_afadmin_msgtable" scope="col"> <input type="text" id="wait_setting_msg" class="wp_textfield_input" name="messages[wait_msg]" placeholder="Please wait.." value="<?php  if(isset($wait_msg)){ echo $wait_msg; } ?>" /> 
   <!-- <span id="wait_error" style="color:red;"></span>      -->
  </td>
  </tr>

  <tr>
  <td scope="col" class="wp_afadmin_tdwidth"><label>Success:</label></td>
  <td scope="col"> <input class="wp_afadmin_radio" type="radio" id="success_setting_1"  name="setting[success_setting]" value="1" <?php  if(isset($success_setting)  && ($success_setting == 1)){ echo "checked='true'"; } ?> />On
  <input class="wp_afadmin_radio" type="radio" id="success_setting_2" name="setting[success_setting]" value="0" <?php if(isset($success_setting) && ($success_setting == 0)){ echo "checked='true'"; }else if(!isset($success_setting)){ echo "checked";   } ?> />Off
  </td>
  </tr>
    <tr>
  <td scope="col"></td>
  <td class="wp_afadmin_msgtable" scope="col"> <input type="text"  class="wp_textfield_input" id="success_setting_msg"  name="messages[success_msg]" placeholder="The application process has completed successfully" value="<?php  if(isset($success_msg)){ echo $success_msg; } ?>" /> 
   <span id="success_error" style="color:red;"></span>   </td>
  </tr>
  
  
   <tr>
  <td scope="col"><label>Failure:</label></td>
  <td scope="col"> <input class="wp_afadmin_radio" type="radio" id="failure_setting_1" name="setting[failure_setting]" value="1" <?php  if(isset($failure_setting) && ($failure_setting == 1)){ echo "checked='true'"; } ?> />On
  <input class="wp_afadmin_radio" type="radio" name="setting[failure_setting]" id="failure_setting_2" value="0" <?php if(isset($failure_setting) && ($failure_setting == 0) ){ echo "checked='true'"; }else if(!isset($failure_setting)){ echo "checked";   } ?> />Off
  </td>
  </tr>
  <tr>
  <td scope="col"></td>
  <td class="wp_afadmin_msgtable" scope="col"> <input type="text" class="wp_textfield_input" id="failure_setting_msg" name="messages[failure_msg]" placeholder="There was a failure during the application" value="<?php  if(isset($failure_msg)){ echo $failure_msg; } ?>" /> 
     <span id="failure_error" style="color:red;"></span>
  </td>
  </tr>
</table>
</div>
 
</div> 

<!--end of message -->
<?php /* End  - Default Messages */ ?>

<div class="wp_intsvbtn">
  <?php wp_nonce_field('nonce-to-check'); ?>   
  <input type="hidden" id="save_form" name="action" value="save_af-plugin" />
   <input name="submit_save" type="button"  class="wp_intbutton" value="Save" onclick="saveForm();" />
  
 </div> 
   
  
 </form>
   <div id="dialog" title="Basic dialog">
   </div>
  
  <div id="edit_dialog" title="Basic dialog">
   </div>
   <!-- start: Copy link div -->       
   <div id = "div_eLink" class = "redClass" style = "min-width: 510px; max-width:600px;">
      <span id = "spn_eLink"></span>
   </div>
    <!-- end: Copy link div --> 
   
</div>    
  
<script type="text/javascript">  
	jQuery(document).ready( function() {
			jQuery(window).load(function(){  
      <?php  if(APPLY_FORM_EDITION != 'free'){ ?>     
				jQuery("#wp_ai_flds").mCustomScrollbar({
					scrollButtons:{
						enable:true
					},
             
				});   
				//ajax demo fn
				jQuery("a[rel='load-content']").click(function(e){
					e.preventDefault();
					var $this=jQuery(this),
						url=$this.attr("href");
					$this.addClass("loading");
					jQuery.get(url,function(data){
						$this.removeClass("loading");
						jQuery("#wp_ai_flds .mCSB_container").html(data); //load new content inside .mCSB_container
						jQuery("#wp_ai_flds").mCustomScrollbar("update"); //update scrollbar according to newly loaded content
						jQuery("#wp_ai_flds").mCustomScrollbar("scrollTo","top",{scrollInertia:200}); //scroll to top
					});
				});
				jQuery("a[rel='append-content']").click(function(e){
					e.preventDefault();
					var $this=jQuery(this),
						url=$this.attr("href");
					$this.addClass("loading");
					jQuery.get(url,function(data){
						$this.removeClass("loading");
						jQuery("#wp_ai_flds .mCSB_container").append(data); //append new content inside .mCSB_container
						jQuery("#wp_ai_flds").mCustomScrollbar("update"); //update scrollbar according to newly appended content
						jQuery("#wp_ai_flds").mCustomScrollbar("scrollTo","h2:last",{scrollInertia:2500,scrollEasing:"easeInOutQuad"}); //scroll to appended content
					});
				});
        
      <?php } ?>  
        
        
				jQuery("#wp_apply_flds").mCustomScrollbar({
					scrollButtons:{
						enable:true
					}
				});
				//ajax demo fn
				jQuery("a[rel='load-content']").click(function(e){
					e.preventDefault();
					var $this=jQuery(this),
						url=$this.attr("href");
					$this.addClass("loading");
					jQuery.get(url,function(data){
						$this.removeClass("loading");
						jQuery("#wp_apply_flds .mCSB_container").html(data); //load new content inside .mCSB_container
						jQuery("#wp_apply_flds").mCustomScrollbar("update"); //update scrollbar according to newly loaded content
						jQuery("#wp_apply_flds").mCustomScrollbar("scrollTo","top",{scrollInertia:200}); //scroll to top
					});
				});
				jQuery("a[rel='append-content']").click(function(e){
					e.preventDefault();
					var $this=jQuery(this),
						url=$this.attr("href");
					$this.addClass("loading");
					jQuery.get(url,function(data){
						$this.removeClass("loading");
						jQuery("#wp_apply_flds .mCSB_container").append(data); //append new content inside .mCSB_container
						jQuery("#wp_apply_flds").mCustomScrollbar("update"); //update scrollbar according to newly appended content
					jQuery("#wp_apply_flds").mCustomScrollbar("scrollTo","h2:last",{scrollInertia:2500,scrollEasing:"easeInOutQuad"}); //scroll to appended content
					});
				});  
			});
		});
    
	</script>
  <!--script for headers and parameters-->
<?php if(APPLY_FORM_EDITION != 'free'){  ?>    
  <script type="text/javascript">
    function removeFn(id){
         var elm = "#"+id;
         jQuery(elm).parent().remove();
    	  
    }

jQuery(document).ready(function(){
 var scntDivheader = jQuery('#wp_adheaderintvw');
  var i = jQuery('#wp_adheaderintvw p').size();
  jQuery('#button_head').click(function() {
          jQuery('<p class="selected"><input class="header_cls" type="text" name="header['+i+'][name]" value="" placeholder="name" />  <input type="text"  name="header['+i+'][value]" value="" placeholder="value" /><span class="remintv" id="remScnt_'+i+'" style="cursor:pointer " onclick="removeFn(this.id);" ></span></p>').appendTo(scntDivheader);
          i++;
          
  });  
        

  /*Add parameter*/
   var scntDiv = jQuery('.wp_dropinputint');
   var j = jQuery('.wp_dropinputint p').size();    
        jQuery('#wp_addparameter').click(function() {
        jQuery('<p class="selected"><input type="text" class="parameter_cls" name="parameter['+j+'][name]" value="" placeholder="name" />  <input type="text"   name="parameter['+j+'][value]" value="" placeholder="value" /> <span id="parameter_'+j+'" class="wp_reminv" style="cursor:pointer " onclick="remFn(this.id);"></span></p>').appendTo(scntDiv);
        j++;
               
        });
  
  
  
  
   var scntPara = jQuery('.wp_dropinputint');
      jQuery( "#wp_dropdivint" ).sortable({
      revert: true
    });

  jQuery( ".wp_addinputint" ).draggable({
      connectToSortable:  "#wp_dropdivint",    
      connectToSortable:  "#wp_adheaderintvw",      
      helper: "clone",
      revert: "invalid",
     

    });
 

  jQuery("#wp_dropdivint, #wp_adheaderintvw").droppable({
       accept: ".wp_addinputint",
        drop: function( event, ui ) {   
      if(jQuery(this).attr("id") == 'wp_dropdivint'){  
            var li_text = jQuery(ui.draggable).text();
            var li_arr = new Array();
            li_arr = li_text.split(":");
             if(li_arr[0] == "Custom Field"){ 
                var formatted_li =  jQuery.trim(li_arr[1]);
                var parameter_val_field = '{'+formatted_li+'}';
                		  jQuery(".wp_dropinputint").append("<p class='selected'><input type='text' class='parameter_cls' name='parameter["+j+"][name]' value='"+li_text+"' placeholder='name' /> <input type='text'  name='parameter["+j+"][value]' value='"+parameter_val_field+"' placeholder='value' /> <span  id='parameter_"+j+"' class='wp_reminv' style='cursor:pointer ' onclick='remFn(this.id);'></span></p>");
                    j++;
            }else{
            var para_val = jQuery.trim(jQuery(ui.draggable).children('span').text());
            var formatted_para_val = '{'+para_val+'}';
            var para_name = jQuery.trim(jQuery(ui.draggable).clone()    
                              .children() 
                              .remove()  
                              .end()  
                              .text()); 
                              
                    jQuery(".wp_dropinputint").append("<p class='selected'><input type='text' class='parameter_cls' name='parameter["+j+"][name]' value='"+para_name+"' placeholder='name' /> <input type='text'  name='parameter["+j+"][value]' value='"+formatted_para_val+"' placeholder='value' /> <span  id='parameter_"+j+"' class='wp_reminv' style='cursor:pointer ' onclick='remFn(this.id);'></span></p>");   
                    j++;        
            }    
                
        
    }else if(jQuery(this).attr("id") == 'wp_adheaderintvw'){ 
            var li_text = jQuery(ui.draggable).text();
            var li_arr = new Array();
            li_arr = li_text.split(":");
          
            if((li_arr[0] == "Custom Field") || (li_arr[0] == "Form")){ 
                var formatted_li =  jQuery.trim(li_arr[1]);
                var header_val_field = '{'+formatted_li+'}';
                 
               		  jQuery("#wp_adheaderintvw").append("<p class='selected'><input type='text' class='header_cls' name='header["+i+"][name]' value='"+li_text+"' placeholder='name' /> <input type='text'  name='header["+i+"][value]' value='"+header_val_field+"' placeholder='value' /> <span  id='remScnt_"+i+"' class='remintv' style='cursor:pointer ' onclick='removeFn(this.id);'></span></p>");
                    i++;
                 
            }else{
            var header_val = jQuery.trim(jQuery(ui.draggable).children('span').text());
            var formatted_header_val = '{'+header_val+'}';
            var header_name = jQuery.trim(jQuery(ui.draggable).clone()    
                              .children() 
                              .remove()  
                              .end()  
                              .text()); 
                              
           jQuery("#wp_adheaderintvw").append("<p class='selected'><input type='text' class='header_cls' name='header["+i+"][name]' value='"+header_name+"' placeholder='name' /> <input type='text'  name='header["+i+"][value]' value='"+formatted_header_val+"' placeholder='value' /> <span  id='remScnt_"+i+"' class='remintv' style='cursor:pointer ' onclick='removeFn(this.id);'></span></p>");  
           i++;        
            
            }    
     
       }
           
    
      },
  
  }); 
  
  }); 
  
  
  function remFn(id){
    var elm = "#"+id;
    jQuery(elm).parent().remove();
	  
}

  
  </script>
<?php } ?>

<script type="text/javascript">
jQuery(document).ready(function(){
   	jQuery(window).load(function(){ 
			jQuery("#wp_ai_parvariable").mCustomScrollbar({
					scrollButtons:{
						enable:true
					}
				});   
 <?php if(APPLY_FORM_EDITION != 'free'){  ?>  
 
				//ajax demo fn
				jQuery("a[rel='load-content']").click(function(e){
					e.preventDefault();
					var $this=jQuery(this),
						url=$this.attr("href");
					$this.addClass("loading");
					jQuery.get(url,function(data){
						$this.removeClass("loading");
						jQuery("#wp_ai_parvariable .mCSB_container").html(data); //load new content inside .mCSB_container
						jQuery("#wp_ai_parvariable").mCustomScrollbar("update"); //update scrollbar according to newly loaded content
						jQuery("#wp_ai_parvariable").mCustomScrollbar("scrollTo","top",{scrollInertia:200}); //scroll to top
					});
				});
				jQuery("a[rel='append-content']").click(function(e){
					e.preventDefault();
					var $this=jQuery(this),
						url=$this.attr("href");
					$this.addClass("loading");
					jQuery.get(url,function(data){
						$this.removeClass("loading");
						jQuery("#wp_ai_parvariable .mCSB_container").append(data); //append new content inside .mCSB_container
						jQuery("#wp_ai_parvariable").mCustomScrollbar("update"); //update scrollbar according to newly appended content
						jQuery("#wp_ai_parvariable").mCustomScrollbar("scrollTo","h2:last",{scrollInertia:2500,scrollEasing:"easeInOutQuad"}); //scroll to appended content
					});
				});
      <?php } ?> 
        
			});
  });   
<?php if(APPLY_FORM_EDITION != 'free'){  ?> 
  

  function checkHeaderDuplicates(){
   var headObj = new Array();
   var results = new Array();
   var sorted_arr = new Array();
   var cnter = 0;
  jQuery("input[class=header_cls]").each(function() {
    var hValue = jQuery(this).val();
    headObj[cnter] = hValue;
    cnter++;
});


var sorted_arr = headObj.sort();
for (var headI=0; headI<headObj.length - 1; headI++) {
  if (sorted_arr[headI + 1] == sorted_arr[headI]) {
        results.push(sorted_arr[headI]);
    }
    
    
}
if(results.length > 0){
     return 1;
}

return 0;

}
    
   function checkParameterDuplicates(){
    var paramObj = new Array();
    var resultsParam = new Array();
    var sorted_arr_param = new Array();
    var cnter = 0;
    jQuery("input[class=parameter_cls]").each(function() {
       var pValue = jQuery(this).val();
       paramObj[cnter] = pValue;
       cnter++;
    
   });

var sorted_arr_param = paramObj.sort();
for(var paramI=0; paramI<paramObj.length - 1; paramI++) {
  if(sorted_arr_param[paramI + 1] == sorted_arr_param[paramI]) {
        resultsParam.push(sorted_arr_param[paramI]);
    }
    
    
}
if(resultsParam.length > 0){
     return 1;
}

return 0;

    }   
    

<?php } ?>   


	</script>
  <!--end script for headers and parameters -->



<script type="text/javascript">
  
  <?php if(APPLY_FORM_EDITION != 'free'){  ?>     
jQuery(document).ready(function(){
  
          jQuery("#dialog").dialog({
            autoOpen: false,
            title: "Add new field",
            modal: true,
            minHeight: 300,
            minWidth: 500, 
            buttons: {
              Ok: function() {
               jQuery(this).dialog("close");
               jQuery('#form_dialog').remove();
                /*Initialize find on the new elements: code same as find li code on load*/
          
                 jQuery("#wp_ai_flds").find('li').each(function(){   
                      jQuery(this).unbind( "click" );  
                  }); 
                
                 jQuery("#wp_ai_flds").find('li').each(function(){   
                          jQuery(this).click( function() {
							//CRINCH
							var showHideUploadPDFField_runtime = "style='display:none'";
						   var showHideAddOptionLink_runtime = "style='display:none'";
						   var dynamicOptionsTrs_runtime = '';
						   //end CRINCH
						  
                                jQuery("#edit_dialog" ).dialog( "open" ); 
                                 
                                 var av_field = jQuery(this).find('.temp_cls1').val();
                                 var av_title = jQuery(this).find('.temp_cls2').val();
								 //CRINCH
								 var av_options = jQuery(this).find('.temp_cls3').val();
								 
								 
								 
								 var av_title = jQuery(this).find('.temp_cls2').val();  
                                 var exploded_field = av_field.split(":");
                                  
								  //CRINCH - BELOW LINE UPDATE
                                 //var text_selected = longtext_selected = upload_selected = numeric_selected = '';  
								 var text_selected = longtext_selected = upload_selected = numeric_selected = dropdown_selected = checkbox_selected = editablePDF_selected = '';  
								 
                                 var required = ''; 
                                 if(exploded_field[0] == "Text"){
                                   text_selected = "selected='selected'";
                                   showHideAddOptionLink_runtime = "style='display:none'";
       							   showHideUploadPDFField_runtime = "style='display:none'";                                   
                                 }
                                 else if(exploded_field[0] == "Upload"){
                                   upload_selected = "selected='selected'";
                                   showHideAddOptionLink_runtime = "style='display:none'";
                                   showHideUploadPDFField_runtime = "style='display:none'"; 
                                 }
                                 else if(exploded_field[0] == "Long Text"){
                                  longtext_selected = "selected='selected'";
                                  showHideAddOptionLink_runtime = "style='display:none'";
                                  showHideUploadPDFField_runtime = "style='display:none'";
                                 }
                                 else if(exploded_field[0] == "Numeric"){
                                  numeric_selected = "selected='selected'";
                                  showHideAddOptionLink_runtime = "style='display:none'";
                                  showHideUploadPDFField_runtime = "style='display:none'";
                                 }
                                 else if(exploded_field[0] == "Dropdown"){  //CRINCH
									dropdown_selected = "selected='selected'";
									//showHideAddOptionLink_runtime = "style='display:none'";
									showHideUploadPDFField_runtime = "style='display:none'";
										showHideAddOptionLink_runtime = '';
										if(av_options!=''){
										dynamicOptionsTrs_runtime = makeDynamicOptions_runtime(exploded_field[0], av_options);
									}
								  
                                 }
                                 else if(exploded_field[0] == "Checkbox"){  //CRINCH
								 //showHideAddOptionLink_runtime = "style='display:none'";
								 showHideUploadPDFField_runtime = "style='display:none'";
                                  checkbox_selected = "selected='selected'";
										showHideAddOptionLink_runtime = '';
										dynamicOptionsTrs_runtime = makeDynamicOptions_runtime(exploded_field[0], av_options);
								  
                                 }
                                 else if(exploded_field[0] == "EditablePDF"){  //CRINCH
                                  	editablePDF_selected = "selected='selected'";
									showHideAddOptionLink_runtime = "style='display:none'";
									showHideUploadPDFField_runtime = "";
									dynamicOptionsTrs_runtime = makeDynamicOptions_runtime_editable(exploded_field[0], av_options);                                  
                                 }
                                 
                                 if(exploded_field[1] == "1"){
                                  required = "checked='checked'";
                                 }
								
								//CRINCH - BELOW LINE UDPATED	                                
                                /*var option_str = "<option value='Text' "+text_selected+">Text</option><option value='Upload' "+upload_selected+">Upload</option><option value='LongText' "+longtext_selected+">Long Text</option><option value='Numeric' "+numeric_selected+">Numeric</option>";*/
								
								//01april - CRINCH -done
                                var option_str = "<option value='Text' "+text_selected+">Text</option><option value='Upload' "+upload_selected+">Upload</option><option value='LongText' "+longtext_selected+">Long Text</option><option value='Numeric' "+numeric_selected+">Numeric</option><option value='Dropdown' "+dropdown_selected+">Dropdown</option><option value='Checkbox' "+checkbox_selected+">Checkbox</option><option value='EditablePDF' "+editablePDF_selected+">Editable PDF</option>";
								
								
								
								
                                 jQuery(this).attr('id','pointer');      //set a id called pointer
								 
								 //CRINCH - BELOW LINE UPDATED
                                 /*jQuery("#edit_dialog").append("<form id='form_dialog'><table class='wpaf_dialog_table'><tr><td width='22px'><span class='wpaf_tooltip trigger'  onmouseover = 'OpenDiv(\""+afTooltip.FIELD_TYPE_TOOLTIP+"\");'>?</span></td><td width='110px'><span class='dialog_flds'>Field Type:</span></td><td><select id='new_field' name='new_field' style='width:180px;'>"+option_str+"</select></td></tr><tr><td><span class='wpaf_tooltip trigger'  onmouseover = 'OpenDiv(\""+afTooltip.REQUIRED_TOOLTIP+"\");'>?</span></td><td><span class='dialog_flds'>Required:</span></td><td><input id='required_field' type='checkbox' name='required_field' value='1' "+required+"></td></tr><tr><td><span class='wpaf_tooltip trigger'  onmouseover = 'OpenDiv(\""+afTooltip.TITLE_OF_FIELD_TOOLTIP+"\");'>?</span></td><td><span class='dialog_flds'>Title of field:</span></td><td><input type='text' name='field_title' id='field_title' value='"+av_title+"' /></td></tr></table></form>"); */
                                 //jQuery("#edit_dialog").append("<form id='form_dialog'><table class='wpaf_dialog_table'><tr><td width='22px'><span class='wpaf_tooltip trigger'  onmouseover = 'OpenDiv(\""+afTooltip.FIELD_TYPE_TOOLTIP+"\");'>?</span></td><td width='110px'><span class='dialog_flds'>Field Type:</span></td><td><select onchange='DeleteItemOnDropDownSelection(); return false;' name='new_field' id='new_field' style='width:180px;'>"+option_str+"</select></td></tr><tr><td><span class='wpaf_tooltip trigger'  onmouseover = 'OpenDiv(\""+afTooltip.REQUIRED_TOOLTIP+"\");'>?</span></td><td><span class='dialog_flds'>Required:</span></td><td><input id='required_field' type='checkbox' name='required_field' value='1' "+required+"></td></tr><tr><td><span class='wpaf_tooltip trigger'  onmouseover = 'OpenDiv(\""+afTooltip.TITLE_OF_FIELD_TOOLTIP+"\");'>?</span></td><td><span class='dialog_flds'>Title of field:</span></td><td><input type='text' name='field_title' id='field_title' value='"+av_title+"' /></td></tr><tr id='asifarif-0' class='checkme'><td colspan='3'></td></tr>"+dynamicOptionsTrs_runtime+"<tr "+showHideAddOptionLink_runtime+" id='linkToCreateDynamicOption'><td align='center' colspan='3'><button type='button' id='asif_arif' class='asif_arif' onclick = 'event_add_audience_custom(); return false;'><span class='ui-button-text trigger'>Add OPtion</span></button></td></tr><tr "+showHideUploadPDFField_runtime+" id='showHideUploadPDFFieldID'><td align='center' colspan='3'><input name='file' id='fileupload' type='file' size='15' multiple/><div id='showPDFfileOnly'style='color:red;font-size:10px;'>Only PDF files!</div><div ='response'></div></td></tr></table></form>"); 
                                 jQuery("#edit_dialog").append("<form id='form_dialog'><table class='wpaf_dialog_table'><tr><td width='22px'><span class='wpaf_tooltip trigger'  onmouseover = 'OpenDiv(\""+afTooltip.FIELD_TYPE_TOOLTIP+"\");'>?</span></td><td width='110px'><span class='dialog_flds'>Field Type:</span></td><td><select onchange='DeleteItemOnDropDownSelection(); return false;' name='new_field' id='new_field' style='width:180px;'>"+option_str+"</select></td></tr><tr><td><span class='wpaf_tooltip trigger'  onmouseover = 'OpenDiv(\""+afTooltip.REQUIRED_TOOLTIP+"\");'>?</span></td><td><span class='dialog_flds'>Required:</span></td><td><input id='required_field' type='checkbox' name='required_field' value='1' "+required+"></td></tr><tr><td><span class='wpaf_tooltip trigger'  onmouseover = 'OpenDiv(\""+afTooltip.TITLE_OF_FIELD_TOOLTIP+"\");'>?</span></td><td><span class='dialog_flds'>Title of field:</span></td><td><input type='text' name='field_title' id='field_title' value='"+av_title+"' /></td></tr><tr id='asifarif-0' class='checkme'><td colspan='3'></td></tr>"+dynamicOptionsTrs_runtime+"<tr "+showHideAddOptionLink_runtime+" id='linkToCreateDynamicOption'><td align='center' colspan='3'><button type='button' id='asif_arif' class='asif_arif' onclick = 'event_add_audience_custom(); return false;'><span class='ui-button-text trigger'>Add OPtion</span></button></td></tr></table></form>");
								 
                               
                                 });
           }); 
             
             /*end of initialization */     
               
               
               
              }
            },
           close: function( event, ui ) {}       
        
        });
        /*initialize edit dialog*/
         jQuery("#edit_dialog").dialog({
            autoOpen: false,
            title: "Edit field",
            modal: true,
            minHeight: 300,
            minWidth: 500, 
            buttons: {
              Ok: function() {
               jQuery(this).dialog("close");
               jQuery('#form_dialog').remove();
              }
            },
          
              close: function( event, ui ) {
            jQuery('#form_dialog').remove();
           } 
        
        });
        
        
      });
    
    <?php } ?>
    /* tooltip functions -- start */
     function OpenDiv(val){
       document.getElementById('div_eLink').style.display = 'block';      
       document.getElementById('spn_eLink').innerHTML = val;
   
      jQuery(document).ready(function(){
        var moveLeft = 5;
        var moveDown = -8;
         jQuery('.trigger').hover(function(e) {
           jQuery('#div_eLink').show();
         });
        jQuery('.trigger').mousemove(function(e) {  
          jQuery("#div_eLink").css('margin-left', '54px');
          jQuery("#div_eLink").css('top', e.pageY + moveDown);
        
        });
        jQuery('.trigger').mouseout(function(e) {  
           hideDiv();    
        });
       }); 
    }   
   /* Close  div  */
    function hideDiv(){
      document.getElementById('div_eLink').style.display = 'none';
    }
                 
    
   /* tooltip functions -- end */ 
   
   
//CRINCH



function DeleteCustom(id){ //crinch
	var par = jQuery('.wpaf_dialog_table tbody tr#'+id);
	par.remove();
};

function DeleteItemOnDropDownSelection(){ //crinch
	var getClass = 'checkme';
	var field_type_custom_value = jQuery('#new_field').val();
	if(field_type_custom_value=='Checkbox' || field_type_custom_value=='Dropdown'){
		jQuery('.wpaf_dialog_table tbody tr#linkToCreateDynamicOption').show();
		jQuery('.wpaf_dialog_table tbody tr#showHideUploadPDFFieldID').hide();
	}else if(field_type_custom_value=='EditablePDF'){
		jQuery('.wpaf_dialog_table tbody tr#linkToCreateDynamicOption').hide();
		jQuery('.wpaf_dialog_table tbody tr#showHideUploadPDFFieldID').show();			
	}else{
		jQuery('.wpaf_dialog_table tbody tr#linkToCreateDynamicOption').hide();
		jQuery('.wpaf_dialog_table tbody tr#asifarif-1').hide();
		jQuery('.wpaf_dialog_table tbody tr#showHideUploadPDFFieldID').hide();
	}

	jQuery("."+getClass).each(function() {
		if(this.id=='asifarif-0'){
			return;
		}
		DeleteCustom(this.id);
		/*if(field_type_custom_value=='Checkbox' || field_type_custom_value=='Dropdown'){
			jQuery('.wpaf_dialog_table tbody tr#'+this.id).show();
		}else{
			jQuery('.wpaf_dialog_table tbody tr#'+this.id).hide();
			
		}*/
	});
}
function makeDynamicOptions_runtime_editable(field_type, field_option_str){
	var plugin_dir_link = field_option_str;
	plugin_dir_link = plugin_dir_link.replace("uploadedpdffiles/", ""); 
	var href_file_link = "<span style='color:red;font-size:11px;'>"+plugin_dir_link+"</span>";
	var return_str_runtime = "<tr id='showHideUploadPDFFieldID'><td>"+href_file_link+"</td><td align='center' colspan='2'><input name='file' id='fileupload' type='file' size='15' multiple/><input type='hidden' name='alreadySelectedPdf' id='alreadySelectedPdf' value='"+field_option_str+"'><div id='showPDFfileOnly'style='color:red;font-size:10px;'>Only PDF files!</div></td></tr>";
	return return_str_runtime;
}
function makeDynamicOptions_runtime(field_type, field_option_str){
	var return_str_runtime = '';
	var input_old_field_option_str_runtime = field_option_str;
	var old_field_option_arr_runtime = input_old_field_option_str_runtime.split(",");
	for(var i_runtime = 1; i_runtime <= old_field_option_arr_runtime.length; i_runtime++){
		var maxId_runtime = i_runtime;
		var field_title_str_runtime = old_field_option_arr_runtime[i_runtime-1];
		var select_radio_button_runtime = '';
		if (field_title_str_runtime.match( /(^.*\[|\].*$)/g, '' )){ 
			var select_radio_button_runtime = 'checked';	
			var field_title_control_selection_runtime = field_title_str_runtime.split("[");
			field_title_str_runtime = field_title_control_selection_runtime[0];
		}	
		if(field_type=='Checkbox'){
			var dynamic_checkbox_radio_control_runtime = "<input class='inputclass' type='checkbox' name='fieldOPtionDefaultCheckBox[]' id='fieldOPtionDefaultCheckBox-"+maxId_runtime+"' "+select_radio_button_runtime+" value='"+maxId_runtime+"'><span style='font-size:11px;'>Default Selected</span>";
		}else if(field_type=='Dropdown'){
				var dynamic_checkbox_radio_control_runtime = "<input class='inputclass' type='radio' name='fieldOPtionDefaultRadioButton[]' id='fieldOPtionDefaultRadioButton-"+maxId_runtime+"' "+select_radio_button_runtime+"  value='"+maxId_runtime+"'><span style='font-size:11px;'>Default Selection</span>";			
		}else{
			var dynamic_checkbox_radio_control_runtime = "&nbsp";
		}
		var return_str_runtime = return_str_runtime + "<tr class='checkme' id='asifarif-"+maxId_runtime+"'>"+
		"<td><span style='color:red;font-size:11px;cursor:pointer;' class='btnDelete' onclick='customDeleteRowFromAvailableFieldsOnUpdate_runtime("+maxId_runtime+"); return false;'>Delete</span></td>"+
		"<td colspan='2'>&nbsp; <span style='font-size:15px;'>Options "+maxId_runtime+"</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input name='fieldOptions[]' id='"+maxId_runtime+"' type='text' value='"+field_title_str_runtime+"'/>&nbsp;&nbsp;"+dynamic_checkbox_radio_control_runtime+"</td>"+
		"</tr>";
	}
	return return_str_runtime;
}
    
function customDeleteRowFromAvailableFieldsOnUpdate_runtime(id){
	DeleteCustom_runtime(id);
}
function DeleteCustom_runtime(id){ //crinch
	var par = jQuery('.wpaf_dialog_table tbody tr#asifarif-'+id);
	par.remove();
};

    
    </script>
    
    



    <?php            
                
      }

      
      
     /**
     * Action and changes and save them if required
     */
    function save_apply_form(){ 

       /** Checks user permisisons */
        if(!current_user_can('manage_options')) :
            $warning = new Invalid_Action('permission');
            wp_die($warning, 'Cheatin&#8217; uh?', array('back_link' => true));
        endif;

        /** Validate the securiy nonce (I.e. make sure the user is coming here from a valid location) */
        check_admin_referer('nonce-to-check'); // This should match the value of 'wp_nonce_field()'
 
        /** Do your stuff here and then set the necessary status...*/
      
//         /*Save the posted information */
         $settings = array();
         $wpaf_setting =  get_option('wpaf_setting');
         
         
         if(isset($_POST['setting'])){
               if(APPLY_FORM_EDITION == 'free'){
                   $_POST['setting']['scriptname'] = "Apply_Form_email_script.php";
               }
               $settings = $_POST['setting']; 
               $jsonSetting = json_encode($settings);
         }
         
         if(isset($_POST['messages'])){
            $message_values = $_POST['messages']; 
            foreach($message_values as $key => $value){
                $message_values[$key] = htmlspecialchars($value,ENT_QUOTES);
           //      $message_values[$key] =  htmlentities($value,ENT_QUOTES);
            }
            $jsonMessages = json_encode($message_values);    
            unset($_POST['messages']);
          }  
         
         
         if(APPLY_FORM_EDITION != 'free'){ 
                 $wpaf_apply_button =  get_option('wpaf_apply_button');  
                 $wpaf_response_popup =  get_option('wpaf_response_popup');
                 $wpaf_headers = get_option('wpaf_headers');
                 $wpaf_parameters =  get_option('wpaf_parameters');
                 $wpaf_field_title =  get_option('wpaf_field_title');
                 $wpaf_available_field_title =  get_option('wpaf_available_field_title'); 
                 $wpaf_messages =  get_option('wpaf_messages'); 
                       
                 $apply_button = array();
                 $response_popup = array();
                 $jsonFormField = array();
                 $jsonAvailableField = array();
                  
               
                  if(isset($_POST['header'])){
                       $jsonHeader = json_encode($_POST['header']);     
                       unset($_POST['header']);
                  }
                  if(isset($_POST['parameter'])){
                      $jsonParameter = json_encode($_POST['parameter']);
                      unset($_POST['parameter']);
                  }
                  if(isset($_POST['apply_button'])){
                     /* $apply_button = array_walk($_POST['apply_button'], '_clean');    */
                       $apply_button = $_POST['apply_button']; 
                       foreach($apply_button as $key => $value){
                         $apply_button[$key] = htmlspecialchars($value,ENT_QUOTES);
                       }
                       $jsonApplyButton = json_encode($apply_button);
                  }
                  
                  if(isset($_POST['response_popup'])){
                        $response_popup =   $_POST['response_popup'];
                         foreach($response_popup as $key => $value){
                           $response_popup[$key] = htmlspecialchars($value,ENT_QUOTES);
                         }
                       $jsonPopupResponse = json_encode($response_popup);
                  }
                  
                   if(isset($_POST['af_field'])){
//crinch, important break ponit
                       $af_fields = $_POST['af_field'];
                       $af_titles = $_POST['af_title'];
                       //CRINCH,Added this new below line
                       $af_options = $_POST['af_options'];
                       // 
                       $form_fields = array();
                       $j = 0;
                       foreach($af_fields as $key => $value){
                         $form_fields[$j]['field'] = $value;
                         //CRINCH
                         //$form_fields[$j]['title'] = $af_titles[$key];
                         if($af_options[$key]!=''){
	                         $form_fields[$j]['title'] = $af_titles[$key].'@@{'.$af_options[$key].'}';
                         }else{
                         	$form_fields[$j]['title'] = $af_titles[$key];
                         }
                         $j++;
                       }
                                   
                   
                       $jsonFormField = json_encode($form_fields);    
                       unset($_POST['af_field']);
                       unset($_POST['af_title']);
                       //CRINCH
                       unset($_POST['af_options']);
                  }
        
                  
                  /*add new */
                  if(isset($_POST['av_field'])){
                       $av_fields = $_POST['av_field'];
                       $av_titles = $_POST['av_title']; 
                       //CRINCH,Added this new below line
                       $av_options = $_POST['av_options'];
                       //
                                   
                       $available_fields = array();
                       $j = 0;
                       foreach($av_fields as $key => $value){
                         $available_fields[$j]['field'] = $value;
                         //CRINCH
                         //$available_fields[$j]['title'] = $av_titles[$key];
                         if($av_options[$key]!=''){
                         	$available_fields[$j]['title'] = $av_titles[$key].'@@{'.$av_options[$key].'}';
                         }else{
                         	$available_fields[$j]['title'] = $av_titles[$key];
                         }                          
                         $j++;
                       }
                   
                       $jsonAvailableField = json_encode($available_fields);    
                       unset($_POST['av_field']);
                       unset($_POST['av_title']);
                       //CRINCH
                       unset($_POST['av_options']);
                  
                  }
                  
                    
                 if($wpaf_response_popup === false){  
                     add_option('wpaf_response_popup',$jsonPopupResponse);
                 }else{  
                     update_option('wpaf_response_popup',$jsonPopupResponse);
                  } 
                  
                 if($wpaf_apply_button === false){  
                     add_option('wpaf_apply_button',$jsonApplyButton);
                 }else{  
                     update_option('wpaf_apply_button',$jsonApplyButton);
                 }
                  
                 if($wpaf_headers === false){  
                     add_option('wpaf_headers',$jsonHeader);
                 }else{  
                     update_option('wpaf_headers',$jsonHeader);
                 } 
                 
                 if($wpaf_parameters === false){  
                     add_option('wpaf_parameters',$jsonParameter);
                 }else{  
                     update_option('wpaf_parameters',$jsonParameter);
                 } 
                 
                 if($wpaf_field_title === false){ 
                     add_option('wpaf_field_title',$jsonFormField);
                 }else{  
                     update_option('wpaf_field_title',$jsonFormField);
                 } 
                 
                 if($wpaf_available_field_title === false){ 
                     add_option('wpaf_available_field_title',$jsonAvailableField);
                 }else{  
                     update_option('wpaf_available_field_title',$jsonAvailableField);
                 } 
                 
        } 
         
         
         if($wpaf_messages === false){  
             add_option('wpaf_messages',$jsonMessages);
         }else{  
             update_option('wpaf_messages',$jsonMessages);
              
          } 
          
          if($wpaf_setting === false){   
             add_option('wpaf_setting',$jsonSetting);
           }else{ 
            
               update_option('wpaf_setting',$jsonSetting);
           } 
          
         $status = 1;
        /*End of Save code */

        /** Set the correct status (so that the correct splash message is shown */
     $_POST['_wp_http_referer'] = add_query_arg('status', $status, $_POST['_wp_http_referer']);

        /** Redirect the user back to where they came from */
      wp_redirect($_POST['_wp_http_referer']);
  

 }
     /* Name: __construct
     Parameters:
     Use: To declare which function is called on a given action, this is the first function that gets called
   */          
    function __construct(){ 
    	define('IC_ROOT_DIR', str_replace('\\', '/', dirname(__FILE__)));
      $upload_dir = wp_upload_dir();
      $upload_loc = $upload_dir['basedir']."/candidate_application_form";
     
		   if (is_dir($upload_loc)){ 
        $uploads_base_dir = $upload_dir['basedir'];
	      define('UPLOADS_CANDIDATE_APPLICATION_FORM',str_replace('\\', '/', $uploads_base_dir));
       }  
      
      define("WPpostURL","guid");
      
  
      /*todo : Query needs to written here to fetch debug mode value from database */
   //  define('DEBUG_MODE','on');
      define('DEBUG_MODE','off');
      
      /*Error messages */
      define("REQUIRED_ERROR_MSG","Please fill the required field.");
      //CRINCH
      define("REQUIRED_ERROR_MSG_PDF","Please Download and Upload required PDF Document.");
      
      define("INVALID_EMAIL_MSG","Invalid Email address.");
      define("EMAIL_DO_NOT_MATCH_MSG","Email ids do not match");   
      define("INVALID_NUMERIC_MSG","Invalid number.");   
      
      /*Tooltips*/
      define("APPLY_FORM_SHORTCODE_TOOLTIP",'This is the apply form shortcode name\/value. You can place this shortcode on any WordPress page, post or widget. The apply form will auto-populate into that area when a user navigates to the page or post which contains the shortcode.');    
      define("UPGRADE_ACTIVATION_KEY_TOOLTIP",'Enter your &ldquo;Upgrade Activation Key&ldquo; value to upgrade from the free version to the commercial version (see www.responsecoordinator.com/Candidate-Apply-Form.html for feature differences)');   
       define("ACTIVATION_EMAIL_TOOLTIP",'Enter the Activation email address you used to request an Activation Key from Flaxlands Consulting Ltd');  
       define("DESTINATION_EMAIL_TOOLTIP",'Enter the email destination the plugin should use to send completed candidate applications.'); 
       define("AVAILABLE_FIELDS_TOOLTIP",'Use the &ldquo;Available Fields&ldquo; section to create and add new fields which then become available to add to the Apply Form.');  
       define("APPLY_FORM_FIELDS_TOOLTIP",'Configure the list of fields on the Apply Form by dragging fields from the Available Fields Section into this section.');  
       define("SCRIPT_CONFIG_TOOLTIP",'This section allows you to configure the transmission method and parameters used to send the candidate application data. For example you can use this section to configure an interface between the Apply Form and your Applicant Tracking Software.');  
       define("SCRIPT_NAME_TOOLTIP",'The variables configured in this section are passed to one of a number of possible communication programs used to send data to different Applicant Tracking Systems (ATS). This field contains the name of the script. Where there is not already a script for your (ATS) please contact www.flaxlandsconsulting.com.');  
       define("HEADER_VARIABLES_TOOLTIP",'Configure the header variables you will need to send within an HTTP request to the API of your Applicant Tracking System.');  
       define("PARAMETER_VALUES_TOOLTIP",'Configure the content variables you will need to send within an HTTP request to the API of your Applicant Tracking System. ');  
       define("AVAILABLE_VARIABLES_TOOLTIP",'The Available Variables section displays a list of variables where values are derived from the custom fields on the WordPress posts database. There are also a few other static variables which can be used to configure the HTTP requests made to API&rsquo;s.');  
       define("APPLY_BUTTON_APPEARANCE_TOOLTIP",'In this section you can configure styling options for the Apply button on the apply form.');  
        define("RESPONSE_POPUP_APPEARANCE_TOOLTIP",'In this section you can configure styling options for the response pop-up window that is displayed when the Candidate clicks the Apply Button.');  
       define("MESSAGES_TOOLTIP",'The &ldquo;Messages&ldquo; section allows you to configure the messages that are shown to the candidate in the Response Pop-up window during the application process.');  
       define("FIELD_TYPE_TOOLTIP",'Choose the data type for the new Apply Form Field.');  
       define("REQUIRED_TOOLTIP",'Tick this box if you need the field to always contain data before allowing the form to be submitted.');  
       define("TITLE_OF_FIELD_TOOLTIP",'Type the full label name of the new field. It is normal for the label to describe the value you want the candidate to input.'); 
 
 
      
     /*Check edition */
     $wpaf_setting =  get_option('wpaf_setting');
     $decoded_setting =  json_decode($wpaf_setting);
     if(isset($decoded_setting->activation_key)){
      $activation_key = $decoded_setting->activation_key;
     }
     if(isset($decoded_setting->activation_email)){
        $activation_email_address = $decoded_setting->activation_email;
     }
     
     /*Some Difficut Logical Arithmetic*/
     $SD54SS = md5($activation_email_address);
     $CC12NM = "8f213a31b3d5f921fb6ff6c0333af826";
     $RT99IO = $SD54SS.$CC12NM;
     $GG13DS = md5($RT99IO);
     $AS33ER = md5($SD54SS.$CC12NM); 
     if($GG13DS != $activation_key){ 
        define('APPLY_FORM_EDITION', 'free'); 
        register_activation_hook( IC_ROOT_DIR.'/apply_form.php', array(&$this, 'af_insert_wp_options_table') );
     }
     register_activation_hook( IC_ROOT_DIR.'/apply_form.php', array(&$this, 'candidate_create_upload_folder') );   
     
     /*end of check for edition*/ 
   
      $scriptname = "";  
      if(isset($decoded_setting->scriptname)){
        $scriptname = $decoded_setting->scriptname;
        
      }
     define('SCRIPT_IN_USE',$scriptname);
     
     /*constants for field types */
     define('FIELD_TYPE1',"TEXT");
     define('FIELD_TYPE2',"UPLOAD_CV");
     define('FIELD_TYPE3',"LONGTEXT");
     define('FIELD_TYPE4',"NUMERIC");
     define('FIELD_TYPE6',"UPLOAD_OTHER");      
     define('FIELD_TYPE7',"EMAIL");
     define('FIELD_TYPE8',"DROPDOWN");
     define('FIELD_TYPE9',"CHECKBOX");
     define('FIELD_TYPE10',"EDITABLEPDF");
     
     add_filter('plugin_row_meta', array(&$this,'set_plugin_meta'),10,2);
   
     
     add_action("wp_ajax_submit_ic-application",array(&$this,'on_ic_apply'));
     add_action("wp_ajax_nopriv_submit_ic-application", array(&$this,'on_ic_apply'));
     
     //CRINCH - for ajax file upload
     add_action("wp_ajax_crinch_custom_file_upload",array(&$this,'on_crinch_file_upload'));
     add_action("wp_ajax_nopriv_crinch_custom_file_upload",array(&$this,'on_crinch_file_upload'));

     //CRINCH - for ajax DIALOG PDF file upload - FRONT END
     add_action("wp_ajax_crinch_custom_file_upload_dialog",array(&$this,'on_crinch_file_upload_dialog'));
     add_action("wp_ajax_nopriv_crinch_custom_file_upload_dialog",array(&$this,'on_crinch_file_upload_dialog'));
      
     /*file upload*/
     add_action("wp_ajax_candidate_file_upload",array(&$this,'on_file_upload'));
     add_action("wp_ajax_nopriv_candidate_file_upload", array(&$this,'on_file_upload'));
     
     /*add_action( 'init', array(&$this,'my_script_enqueuer'));  */
     add_action( 'wp_enqueue_scripts', array(&$this,'apply_form_frontend_method') ); /*only for frontend */
       
     add_filter('widget_text', 'do_shortcode'); /* this code activates the plugin in the widget area*/
     add_shortcode('apply-form', array(&$this, 'apply_form'), 10, 1);
   
   /*actions for admin form */

   	add_action('admin_menu', array($this, 'admin_menu')); // Will add the settings menu.
    add_action('admin_post_save_af-plugin', array(&$this, 'save_apply_form')); 
   /* if(APPLY_FORM_EDITION != 'free'){   */ 
       add_action( 'admin_enqueue_scripts', array($this,'apply_form_admin_enqueue')); 
    /* }  */
   
  
  
  
   }
 
 
 
 
 
 
 
     /**
     * Write a custom message at the top of an admin options page (if necessary)
     */
    private function splash_message(){

        /** Check that there is a status for a splash message to be displayed */
        if(!$_REQUEST['status']) :
            return false;
        endif;

        /** Work out the class of the splash message */
        $message_classes[1] = 'updated';
       $message_classes[99] = 'error';
       $message_class = $message_classes[$_REQUEST['status']];

        $this->set_splash_messages();
        $message = $this->messages_splash[$_REQUEST['status']];

        /** Display the message splash */
        echo '<div id="message" class="'.$message_class.' below-h2">';
        echo '<p>'.$message.'</p>';
        echo '</div>';

    }
    
     /**
     * Set the splash messages available for this plugin
     */
    private function set_splash_messages(){

        $this->messages_splash = array(
            0  => '', // Unused. Messages start at index 1.
            1  => __('Data saved successfully'),
            99 => __('An unknown error occured, please try again.')
        );

    }
    
    /* Name: filter_output
     Parameters: $value
     Use: replaces double quote with single quote
   */ 

    
    public function filter_output($value) {
      $value = htmlspecialchars_decode($value,ENT_QUOTES);
      $value =str_replace('\"','\'',$value);   
      $value =str_replace("\'","'",$value);  
      return $value;  
    }
    
    /* Name: validation_errors
     Parameters: $form_vars
     Use: validates the form fields on the apply form
   */ 
    
    public function validation_errors($form_vars){
      $message = array();
      $message['invalid'] = array();
      $wpaf_field_title =  get_option('wpaf_field_title');
      if(!empty($wpaf_field_title)){
           $af_fields =  json_decode($wpaf_field_title);
      }
                  
      if(isset($af_fields)){ 
        foreach($af_fields as $fld ){ 
            $compulsory = '';
            $parameter = $fld->field;
           
            $param = explode(':',$parameter);  
            $title = $fld->title;
            /*replace space with underscore*/
            if(isset($param[0])){

				//CRINCH
				if (strpos($fld->title,'@@') !== false) {
					$param_custom_new = explode('@@',$fld->title);
					$fld->title = $param_custom_new[0];
					$title = $param_custom_new[0];
					//$title = str_replace(" ","",$title);
					$title = str_replace(" ","_",$title);
				}  
				   
                    if(($param[1] == 1) && isset($form_vars[$title]) && empty($form_vars[$title]) ){
                       //$message['invalid'][$title] = REQUIRED_ERROR_MSG;   // CRINCH - COMMENTED
                       
						//CRINCH
						if($param[0]=='EditablePDF'){
							$message['invalid'][$title] = REQUIRED_ERROR_MSG_PDF;
						}else{
							$message['invalid'][$title] = REQUIRED_ERROR_MSG;
						}
                      
                       /*Check validation for confirm email if type is email */   
                        if(($param[0] == 'Email') && isset($form_vars['Confirm'])){ 
                           foreach($form_vars['Confirm'] as $confirm_key => $confirm_value){    
                              if($confirm_key == $title){
                                       if(($param[1] == 1) && empty($confirm_value)){
                                          $message['invalid']["Confirm[$confirm_key]"] = REQUIRED_ERROR_MSG;
                                       }
                                    }
                      
                      
                                 }
                       }
                       
                    
                    
                    }    
                    else if(($param[0] == 'Email')   ){ /*Check if email address is valid*/
                        if(('' != $form_vars[$title]) && ! $this->wpaf_is_email( $form_vars[$title] ) ){
                            $message['invalid'][$title] = INVALID_EMAIL_MSG;
                         }
                         if(isset($form_vars['Confirm'])){   /*check the confirm email*/
                              /*check values are same in both the text boxes*/
                              foreach($form_vars['Confirm'] as $confirm_key => $confirm_value){         
                                 if($confirm_key == $title){
                                      /*check for required field in confirm email*/
                                    if($confirm_key == $title){
                                       if(($param[1] == 1) && empty($confirm_value)){
                                          $message['invalid']["Confirm[$confirm_key]"] = REQUIRED_ERROR_MSG;
                                       }
                                       else if($confirm_value != $form_vars[$title]){
                                          $message['invalid']["Confirm[$confirm_key]"] = EMAIL_DO_NOT_MATCH_MSG;
                                       }
                                   }
                      
                                  }
                      
                      
                                 }
                         
                         
                         
                         }
                                        
                    }
                    
                   /*for file uploads*/
                    if(($param[1] == 1) && ($param[0] == 'Upload_CV' || $param[0] == 'Upload_Other' ) && isset($form_vars['file_upload_path']) ){
                    
                   
                           foreach($form_vars['file_upload_path'] as $file_key => $file_value){ 
                         
                                 if($title == $file_key){  
                                   if(empty($file_value)){
                                      $message['file_response']["file_upload_path[$file_key]"] = REQUIRED_ERROR_MSG;
                                   }
                                 }  
                           }
                 
                    
                    }   
                   
                   
                   /*end of file upload check*/ 
                   
                   /*check for numeric fields*/
                   if(($param[0] == 'Numeric')   ){
                       if(('' != $form_vars[$title]) && ! $this->wpaf_is_numeric( $form_vars[$title] ) ){
                            $message['invalid'][$title] = INVALID_NUMERIC_MSG;
                         }
                   } 
                    
                    
                    
                    
                
           }  
           
      
                 
        }
        
      }
      
      return $message; 
    
    }
    
    /* Name: wpaf_is_email
     Parameters: $email
     Use: validates email field
   */ 
    
    public function wpaf_is_email( $email ){
      	$result = is_email( $email );
        return $result;
    }
    
   /* Name: wpaf_is_numeric
     Parameters: $number
     Use: validates numeric field
   */ 
    
    public function wpaf_is_numeric( $number ){
      	$result = is_numeric( $number );
        return $result;
    }
    
    /* Name: af_insert_wp_options_table
     Parameters: 
     Use: inserts records while activating the plugin
   */ 
    
    public function af_insert_wp_options_table(){
       $form_fields = array();    
       $setting = array(); 
       $form_fields[0]['field'] = "Text:1";
       $form_fields[0]['title'] = "FirstName";
       $form_fields[1]['field'] = "Text:1";
       $form_fields[1]['title'] = "LastName";
       $form_fields[2]['field'] = "Text:1";
       $form_fields[2]['title'] = "Landline";
       $form_fields[3]['field'] = "Text:1";
       $form_fields[3]['title'] = "Mobile";
       $form_fields[4]['field'] = "Email:1";
       $form_fields[4]['title'] = "EmailAddress";
       $form_fields[5]['field'] = "LongText:0";
       $form_fields[5]['title'] = "AdditionalInfo";
       $form_fields[6]['field'] = "Upload_CV:1";
       $form_fields[6]['title'] = "CV Upload";
       
       $jsonFormField = array();
       $jsonFormField = json_encode($form_fields);   
       
       $wpaf_field_title =  get_option('wpaf_field_title');
    
       if($wpaf_field_title === false){ 
           add_option('wpaf_field_title',$jsonFormField);
       }else{  
           update_option('wpaf_field_title',$jsonFormField);
       }  
       
       $setting['scriptname'] = "Apply_Form_email_script.php";
       $wpaf_setting =  get_option('wpaf_setting');
       $jsonSetting = array();
       $jsonSetting = json_encode($setting);   
       if($wpaf_setting === false){ 
           add_option('wpaf_setting',$jsonSetting);
       }else{  
           update_option('wpaf_setting',$jsonSetting);
       } 
      
    }
    
    
    /* Name: candidate_create_upload_folder
     Parameters: 
     Use: create candidate_application_form folder in wp-content/uploads directory
   */ 
    
    public function candidate_create_upload_folder(){
       $upload_dir = wp_upload_dir();
       $upload_loc = $upload_dir['basedir']."/candidate_application_form";
       if(!is_dir($upload_loc))
       {  
           wp_mkdir_p($upload_loc);   
       }
    
    }
    
    /* Name: set_plugin_meta
     Parameters:  $links, $file
     Use: add link for license value in the installed plugin page
   */ 
    
    public function set_plugin_meta($links, $file) {
    $plugin = plugin_basename(__FILE__);
    if ($file == $plugin) {
       if(APPLY_FORM_EDITION != 'free'){   
          return array_merge($links,array( sprintf( 'FULL', $plugin, __('myplugin') ) ));
       }else{
          return array_merge($links,array( sprintf( 'GPLv2 (or later)', $plugin, __('myplugin') ) ));
       }
    }
    return $links;
    }
    
    /* Name: candidate_apply_form_admin_notice
     Parameters:  
     Use: checks if the plugin is activated correctly
   */ 
    
    
    public function candidate_apply_form_admin_notice(){
     $uploads = wp_upload_dir();
	   if ( ! @is_dir($uploads['basedir'] . '/candidate_application_form') or ! @is_writable($uploads['basedir'] . '/candidate_application_form')) {
	      echo '<div class="error"><p>For Candidate_Application_Form Plugin to function properly, uploads/candidate_application_form  folder is required and must be writable. Please deactivate the plugin, set proper permissions to the uploads folder and activate the plugin again.</p></div>';
    }
   
   }
   
  /* Name: on_file_upload
     Parameters:
     Use: used to upload files to candidate_application_form.
   */ 
    function on_file_upload(){
      $file_path = IC_ROOT_DIR."/upload.php";
      $upload_dir = wp_upload_dir();
      $base_dir = $upload_dir['basedir'];
      define('UPLOAD_BASE_DIR',$base_dir);
      include("$file_path");
      die();

   } 
   
   //CRINCH
   function on_crinch_file_upload(){
	$file_path = IC_ROOT_DIR."/uploadfile.php";
	$upload_dir = wp_upload_dir();
	$base_dir = $upload_dir['basedir'];
	define( 'CRINCH_PDF_UPLOAD_DIR', plugin_dir_path(__FILE__) );
	define('UPLOAD_BASE_DIR',$base_dir);
	include("$file_path");
	die();   	
   }
   
   //CRINCH
   function on_crinch_file_upload_dialog(){
   	//$file_path = IC_ROOT_DIR."/uploaddialogPDFfile.php";
   	$file_path = IC_ROOT_DIR."/uploadfile.php";
   	$upload_dir = wp_upload_dir();
   	$base_dir = $upload_dir['basedir'];
   	define( 'CRINCH_PDF_UPLOAD_DIR', plugin_dir_path(__FILE__) );
   	define('UPLOAD_BASE_DIR',$base_dir);
   	include("$file_path");
   	die();
   }
    
  
}
?>
