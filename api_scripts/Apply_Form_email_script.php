<?php
/* File: Apply_Form_email_script.php
   Use:  Code to send an email 
*/    
   // global $wpdb;   
   
    $response = array();
    $response['debug'][] = "Running Apply_Form_email_script";
    
    
    $to = $_POST['destination_email']; /* email is sent to */
    $from = get_option('admin_email'); /* gets admin email  of the blog is sent from */
    
  
    $first_name = '';
    $last_name = '';
    $title = '';
    
    $attachments = array();
    $post_headers = array(); 
    
     /*If debugging is turned off and Messages are set in the admin then retreive it from post and set it in a variable and then unset the value*/
   $apply_success = 1;
   $apply_failure = 1;
   
   if(isset($_POST['apply_success'])){
        $apply_success = $_POST['apply_success'];
        unset($_POST['apply_success']); 
   }
   if(isset($_POST['apply_failure'])){
        $apply_failure = $_POST['apply_failure'];
        unset($_POST['apply_failure']); 
   }
   
   if(isset($_POST['success_message'])){
        $success_message = $_POST['success_message'];
        unset($_POST['success_message']); 
   }
   if(isset($_POST['failure_message'])){
        $failure_message = $_POST['failure_message'];
        unset($_POST['failure_message']); 
   }
   
 
    
 /* end - before post is used, unset messages */
          
     
    if(!isset($_POST["headers"])){
      $post_headers["Content-Type"] =  "application/json";     
   }else{
      $post_headers = $_POST['headers'];
      unset($_POST['headers']);
   } 
   
   $parameters = $_POST;
   $set_parameter_value = 0; 
    
    
    /*build headers string*/
    $html_msg = "";       /*Message parameter used when debugging is turned off*/
    $html_debug_msg = "";  /*Message parameter used when debugging is turned on*/ 
    $html_parameter_debug_msg = "";  /*Message parameter used when debugging is turned on to append all parameter values*/ 
    $html_msg = "<table style='border: 1px dotted black; border-collapse:collapse;' width='600'><tr><td colspan='2' align='center'  style='border: 1px solid black;'>Candidate Application Notification</td></tr>";
    
    $html_debug_msg = $html_msg;
    $html_debug_msg .= "<tr><td colspan='2' style='border: 1px solid black;'>Headers</td></tr>";
    foreach($post_headers as $key => $value){
          $html_debug_msg .= "<tr><td width='200' align='right' style='border: 1px dotted black; padding-right:3px;'>$key:</td><td style='border: 1px dotted black;'>$value</td></tr>"; 
    }
     
    /*build parameters string */
    $ignore_list = array("action","post_id","nonce","headers","apply_success","apply_failure","destination_email");
    foreach($parameters as $key => $value){
       if($key == "file_upload_path"){
         /*trace the file_upload_path array */ 
         $html_debug_msg .= "<tr><td colspan='2' style='border: 1px solid black;'>File Uploaded paths</td></tr>";
         $upload_path_arr = array();
         $upload_path_arr = $parameters[$key];
         foreach($upload_path_arr as $upload_path_key => $upload_path_value){
             $html_debug_msg .= "<tr><td width='200' align='right' style='border: 1px dotted black; padding-right:3px;'>$upload_path_key:</td><td style='border: 1px dotted black;'>$upload_path_value</td></tr>"; 
            if(!empty($upload_path_value)){  
              $attachments[] = UPLOADS_CANDIDATE_APPLICATION_FORM."/".$upload_path_value;
              $response['debug'][] = "Attached File: ".$upload_path_value;
                     
            }
         }
       }
       else  if($key == "filename_original"){
         
         /*trace the file_upload_path array */ 
         $html_debug_msg .= "<tr><td colspan='2' style='border: 1px solid black;'>Original filenames</td></tr>";
         $upload_file_name_arr = array();
         $upload_file_name_arr = $parameters[$key];
         foreach($upload_file_name_arr as $original_file_key => $original_file_value){
            $html_debug_msg .= "<tr><td width='200' align='right' style='border: 1px dotted black; padding-right:3px;'>$original_file_key:</td><td style='border: 1px dotted black;'>$original_file_value</td></tr>"; 
            
         }
       }
       else  if($key == "filename_type"){
         
         /*trace the file_upload_path array */ 
         $html_debug_msg .= "<tr><td colspan='2' style='border: 1px solid black;'>Filename type</td></tr>";
         $file_name_type_arr = array();
         $file_name_type_arr = $parameters[$key];
         foreach($file_name_type_arr as $type_key => $type_value){
            $html_debug_msg .= "<tr><td width='200' align='right' style='border: 1px dotted black; padding-right:3px;'>$type_key:</td><td style='border: 1px dotted black;'>$type_value</td></tr>"; 
          
         }
       }
       else if($key == "script_parameters"){
       
         $script_parameter_arr = array();
         $script_parameter_arr = $parameters[$key]; 
         if(!empty($script_parameter_arr)){
              $html_debug_msg .= "<tr><td colspan='2' style='border: 1px solid black;'>Script parameters</td></tr>";
      
             foreach($script_parameter_arr as $script_key => $script_value){
                $html_debug_msg .= "<tr><td width='200' align='right' style='border: 1px dotted black; padding-right:3px;'>$script_key:</td><td style='border: 1px dotted black;'>$script_value</td></tr>"; 
        
             }
        }
       }
       else{
          
          if($set_parameter_value == 0){
            $html_parameter_debug_msg .= "<tr><td colspan='2' style='border: 1px solid black;'>Parameters</td></tr>";
            $set_parameter_value++;
          }
        
          if($key == "FirstName"){
            $first_name = stripslashes($value);
          }
          else if($key == "LastName"){
            $last_name = stripslashes($value);
          }
          else if($key == "post_id"){
          //  $title = get_the_title($value);
            
            $querystr = "select post_title from wp_posts where id = $value limit 1";
            $result = $wpdb->get_results($querystr, OBJECT);
            if(!empty($result)){
                 $post_arr = $result[0];
                 $title  = stripslashes($post_arr->post_title);
            }
            
            
            $html_msg .= "<tr><td width='150' align='right' style='border: 1px dotted black; padding-right:3px;'>Job Title:</td><td style='border: 1px dotted black;'>$title</td></tr>";
            $html_parameter_debug_msg .= "<tr><td width='150' align='right' style='border: 1px dotted black; padding-right:3px;'>Job Title:</td><td style='border: 1px dotted black;'>$title</td></tr>"; 
             
          }
          if(!in_array($key,$ignore_list)){
             $html_msg .= "<tr><td width='150' align='right' style='border: 1px dotted black; padding-right:3px;'>$key:</td><td style='border: 1px dotted black;'>$value</td></tr>"; 
          } 
          $html_parameter_debug_msg .="<tr><td width='150' align='right' style='border: 1px dotted black; padding-right:3px;'>$key:</td><td style='border: 1px dotted black;'>$value</td></tr>";   
       }

    }
    $html_msg .= "</table>";
    $html_debug_msg .=$html_parameter_debug_msg; 
    $html_debug_msg .="</table>";
    
    $subject = "Candidate Application : $first_name $last_name has applied for the vacancy of $title"; 
//    	$subject = replace_mail_tags( $subject);
//     $subject = strip_newline( $subject );
   
    
   $headers = "From: $from <$from>" . "\r\n";
  /*  $headers .= "Disposition-Notification-To: $from\r\n";*/
   add_filter( 'wp_mail_content_type', 'set_html_content_type' );  

 
   
   
   $email_message = "";
    if(DEBUG_MODE == 'on'){ 
       $html_msg = "";
       $email_message =  stripslashes($html_debug_msg);
    }else if(DEBUG_MODE == 'off'){ 
       unset($response['debug']);
       $html_debug_msg ="";
       $email_message =  stripslashes($html_msg);
    } 
   
    $response['debug'][] = 'Subject '.$subject;
  /*  $response['debug'][] = 'decoded_subject '.html_entity_decode($subject);  */
           
                            
  
   $mail_sent = wp_mail($to, $subject, $email_message, $headers, $attachments );
   // Reset content-type to avoid conflicts
   remove_filter( 'wp_mail_content_type', 'set_html_content_type' ); 

      if($mail_sent){
        $response['debug'][] = "Email sent successfully";
       // $response['response'][] = "Email sent successfully";
        if(DEBUG_MODE == 'off' && $apply_success == 1){
             if(isset($success_message)){
                $response['response'][] = $success_message;  
             }  
        }
        else if(DEBUG_MODE == 'off' && $apply_success == 0){
              $response['response'][] = "Thank you for applying for this position. Your application has been successfully sent to the Hiring Manager for this vacancy"; 
        } 
        
         /*Code to unlink the files once the mail is sent */
         $file_count = 0;
         $file_delete_count = 0;
         foreach($parameters as $key => $value){
          // $response['debug'][] = "In unlink for loop ".$key;
             if($key == "file_upload_path"){
                   /*trace the file_upload_path array */ 
                   $upload_path_arr = array();
                   $upload_path_arr = $parameters[$key];
                  
                   foreach($upload_path_arr as $upload_path_key => $upload_path_value){
                        //    $response['debug'][] = "In upload_path_value ";
                         if(!empty($upload_path_value)){  
                            if(file_exists(UPLOADS_CANDIDATE_APPLICATION_FORM."/".$upload_path_value)){
                             
                                  $unlink_value = 0;
                                  $unlink_value =  unlink(UPLOADS_CANDIDATE_APPLICATION_FORM."/".$upload_path_value);
                              //    $response['debug'][] = "unlink value = ".$unlink_value;
                                  if($unlink_value)
                                  {
                                    $response['debug'][] = "Deleted File: ".$upload_path_value;
                                    $file_delete_count++;
                                  }
                                   $file_count++; /*no of files on the server*/
                            } 
                        } 
                  }
             
             }
         
         }
        //      $response['debug'][] = " File count ".$file_count;
       if($file_count > 0 && ($file_count == $file_delete_count) ){   /*Check if all files uploaded are deleted*/
            $response['debug'][] = "All files deleted successfully";
       }else if($file_count > 0){
            $response['debug'][] = "Failure while deleting files";
       }
   
   
       /*end of unlink files code */
       
      
   }else{
         $response['debug'][] = "Failed to send an email";
      //   $response['response'][] = "Failed to send an email";
         if(DEBUG_MODE == 'off' && $apply_failure == 1){
                   if(isset($failure_message)){
                      $response['response'][] = $failure_message;  
                   }  
         }else if(DEBUG_MODE == 'off' && $apply_failure == 0){
            $response['response'][] = "Failed to send an email"; 
        } 
     
  }
  
    if(DEBUG_MODE == 'on'){ 
       unset($response['response']);
    }else if(DEBUG_MODE == 'off'){ 
       unset($response['debug']);
    }
    
    
    $result = json_encode($response);
    echo $result;
    die();
 
 
 
 
 function set_html_content_type() {
  	return 'text/html';
 }   




  