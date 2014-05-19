<?php
  
  /*File: ic_api_script.php
    Use: Script used to send a http post (headers and parameters) to the api 
  */
   $response = array();
   $post_response = array();
   $error_occurred = 0;
   $file_path_arr = array();  /*will contain a list of files uploaded on WP server */

   $response['debug'][] = "Running ic_api_script";

    $url = 'http://uatapi.interviewcoordinator.com/api/v1/createnewcandidate';   
             

   /*Get all parameters from apply form */ 
   $headers = array();    
   $parameters = array();  
   
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
 
     
   $parameter_apply_form = $_POST;
   
   if(!isset($_POST["headers"])){
      $headers["Content-Type"] =  "application/json";     
   }else{
      $headers = $_POST['headers'];
      unset($_POST['headers']);
   }
 
 
    /* Build parameters for createnewCandidate method */
    $parameter_createnewcandidate = array();
    $createnewcandidate_arr = array();
    $createnewcandidate_arr = $_POST;
    
    /* Filter out unwanted parameters for createnewcandidate method     */
    $parameter_createnewcandidate = filter_createnewcandidate($createnewcandidate_arr);
    $parameter_createnewcandidate['DD'] = "1"; 
        
    $param_first_api_call = array();
    $param_first_api_call['Content'] = $parameter_createnewcandidate;
    $jsonData = json_encode($param_first_api_call); 
       
    $post_response = wp_remote_post(
                    $url,
                    array(
                        "method" => "POST",
                        "headers" =>  $headers, 
                        "timeout" => 90,
                        "body" => $jsonData,
                        "sslverify" => false
                    ));


    if(is_wp_error( $post_response ) ) {   
           $error_message = $post_response->get_error_message();
           $response['debug'][] = "Something went wrong: $error_message";
           if(DEBUG_MODE == 'off'){
             if($apply_failure == 0){
                $response['response'][] = "$error_message";
             }
           }  
        
          /*to delete files on createcandidatenew time out*/ 
           if(isset($parameter_apply_form["file_upload_path"])){
              $files_arr = array(); 
              $files_arr = $parameter_apply_form["file_upload_path"];
              $i=1;
              foreach($files_arr as $key => $value){
                  if(!empty($value)){  
                      $file_path = UPLOADS_CANDIDATE_APPLICATION_FORM."/".$value;
                    if(file_exists($file_path)){           
                             $file_path_arr[$i]  = $value;
                             $i++;
  
                      } 
                 } 
             }
          }   
            /*end of delete files array code*/     
            $error_occurred = 1;
  
    }else{
       $output = $post_response['body'];
       $decoded_output = json_decode($output);
  
       $status = $decoded_output->Status;
       $response['debug'][] = "Status on createnewcandidate: $status";
          
       if($status == 'Ok'){ /*status is ok*/
        $content = $decoded_output->Content; 
        $candidate_id = $content->CandidateId;
        
        /*Update the Response Array */
        
        if(DEBUG_MODE == 'on'){
            if(isset($candidate_id)){
               $message_recvd = $content->Message;
               $response['debug'][] = "CandidateId: $candidate_id"; 
               if(isset($message_recvd)){ 
                $response['debug'][] = "Message Received: $message_recvd";  
               }
            }
        }
        if(DEBUG_MODE == 'off'){
             if($apply_success == 0){
               $message_recvd = $content->Message; 
               if(isset($message_recvd)){ 
                $response['response'][] = "$message_recvd";  
               }
             }
        }
        
        
        /*2nd api call to upload files */
         $upload_document_url = 'http://uatapi.interviewcoordinator.com/api/v1/uploadcandidatedocument';     
   
            if(isset($parameter_apply_form["file_upload_path"])){
              $file_api_parameter = array();
              $files_arr = array(); 
              $files_arr = $parameter_apply_form["file_upload_path"];
              $file_api_parameter = $parameter_apply_form['script_parameters'];
              $i=1;
              $total_file_size = 0;
              $timeout_val = 0;
              
              foreach($files_arr as $key => $value){
                  if(!empty($value)){  
                      $file_path = UPLOADS_CANDIDATE_APPLICATION_FORM."/".$value;
                    if(file_exists($file_path)){           
                            $file_content = file_get_contents($file_path);
                            $file_api_parameter["File".$i] =  base64_encode($file_content);
                            $file_api_parameter["File".$i."Type"] =  $parameter_apply_form["filename_type"][$key];
                            $file_api_parameter["File".$i."Name"] =  $parameter_apply_form["filename_original"][$key];
                             $file_path_arr[$i]  = $value;
                             $total_file_size += filesize ($file_path); 
                            $i++;
  
                      } 
                 } 
            }
    
           /*once the file array is formed post it to the uploadcandidatedocuments*/
            $file_api_parameter["DD"] = "0";
            $file_api_parameter["CandidateID"] = "$candidate_id";
           
           /*specs - check if it needs to be commented*/ 
            if(isset($file_api_parameter["CampaignID"])){
               unset($file_api_parameter["CampaignID"]);
            }
                      
            $file_api_data = array();
            $file_api_data["Content"] = $file_api_parameter;
            $jsonFileData = json_encode($file_api_data); 
            
             if($total_size >  0 && $total_size <= 2097152){    /* 0 - 2 mb */
                    $timeout_val = 180;
             }else if($total_size > 2097152 && $total_size <= 4194304){   /* 2 - 4 mb */
                    $timeout_val = 240;
             }else if($total_size > 4194304 && $total_size <= 6291456){  /* 4 - 6 mb */
                    $timeout_val = 300;
             } 
             
            $fileupload_response = wp_remote_post($upload_document_url,
                                array(
                                    "method" => "POST",
                                    "headers" =>  $headers, 
                                    "timeout" => $timeout_val,
                                    "body" => $jsonFileData,
                                    "sslverify" => false
                                ));

          
           $timeout_message = "An error has occurred when sending your application. Your application may have been received correctly, but please try again to make sure. We are sorry for any inconvenience caused.";
                                             
           if ( is_wp_error( $fileupload_response ) ) {   
                   $fileupload_error_message = $fileupload_response->get_error_message();
                   $response['debug'][] = "Something went wrong:  ".$fileupload_error_message;
                   $response['debug'][] = $timeout_message;
                   if(DEBUG_MODE == 'off'){
                       if($apply_failure == 0){
                          $response['response'][] = $timeout_message;  
                       }
                  }
                   
                   $error_occurred = 1;
  
          }else{
                 $output_fileupload = $fileupload_response['body'];
                 $decoded_fileupload = json_decode($output_fileupload);
        
                 $status_fileupload = $decoded_fileupload->Status;
                 $response['debug'][] = "After file upload: ".$status_fileupload;  
                    
                 if($status_fileupload == 'Ok'){ /*status is ok*/
                   $content_fileupload = $decoded_fileupload->Content; 
                   $response['debug'][] = "Message: ".$content_fileupload->Message;  
                  //   $response['response'][] = $content_fileupload->Message;  
                     if(DEBUG_MODE == 'off'){
                       if($apply_success == 0){
                        $response['response'][] = $content_fileupload->Message;  
                       }
                       
                     }
                   
                    /*file upload successful */
                    $file_upload_success = 1;
                    /*unlink the files from the server */
                    if(isset($file_path_arr)){
                      $file_delete_val = delete_files($file_path_arr,$response);
                      if(DEBUG_MODE == 'on'){
                          if(count($file_delete_val) > 0) {
                            foreach($file_delete_val as $delkey => $delval){
                                   $response['debug'][] = $delval;
                            } 
                          } 
                      }    
                           
                    }
                     /*end of unlink code*/
                } 
                else{
                
                
                   $errors_fileupload = $decoded_fileupload->Errors;
                   $message_file = $errors_fileupload->Message;  
                   if($status_fileupload == 'ERR'){    
                   $response['debug'][] = "Status: ".$decoded_fileupload->Status." Error ".$message_file;  
            
                 if(DEBUG_MODE == 'off'){
                       if($apply_failure == 0){
                        $response['response'][] = $message_file;  
                       }
                       
                     }   
                 }
                 else{
                     $response['debug'][] =  $timeout_message;
                      if(DEBUG_MODE == 'off'){
                       if($apply_failure == 0){
                        $response['response'][] = $timeout_message;  
                       }
                       
                     }   
                 
                 }    
                     
                   $error_occurred = 1;
      
                }                    
                                            
        }              
              /*end of file upload  api call code*/ 
            
            
            }
        
        
        
        
        
       }else{   /* Errors */
         $errors = $decoded_output->Errors;
         $code = $errors->Code;
         $message = $errors->Message;    
         if(!isset($candidate_id)){
              $response['debug'][] = "Error<br />Code: $code <br />Message: $message";  
              if(DEBUG_MODE == 'off'){
                       if($apply_failure == 0){
                        $response['response'][] = $message;  
                       }
                       
                     }
         }
         $error_occurred = 1;    
        
         /*check if files were uploaded */
          if(isset($parameter_apply_form["file_upload_path"]) && empty($file_path_arr) ){
              $files_arr_temp = array(); 
              $files_arr_temp = $parameter_apply_form["file_upload_path"];
              $i=1;
              
              foreach($files_arr_temp as $key => $value){
                  if(!empty($value)){  
                    $file_path_temp = UPLOADS_CANDIDATE_APPLICATION_FORM."/".$value;
                    if(file_exists($file_path_temp)){           
                            $file_path_arr_temp[$i]  = $value;
                            $i++;
                    } 
                  } 
              }
              $file_path_arr = $file_path_arr_temp;
          } 
          /*end of file upload code */   
         
          
       }
   
       } 
      
      
  /*Delete file uploaded if error occurred before termination  of the script */       
        if(isset($file_path_arr) && $error_occurred == 1){
            $file_delete_val = delete_files($file_path_arr);
            if(DEBUG_MODE == 'on'){
                if (count($file_delete_val) > 0) {
                  foreach($file_delete_val as $delkey => $delval){
                         $response['debug'][] = $delval;
                  } 
                }  
             }       
        } 
        
       if(DEBUG_MODE == 'on'){
          unset($response['response']);
       }else if(DEBUG_MODE == 'off'){
          if($error_occurred == 1){
               if($apply_failure == 1){
                       if(isset($failure_message)){
                          $response['response'][] = $failure_message;  
                       }   
               }
          
          }else{
              if($apply_success == 1){
                       if(isset($success_message)){
                          $response['response'][] = $success_message;  
                       }   
               }
          
          }
       
       
          unset($response['debug']);
       }
         
          $result = json_encode($response);
          echo $result;
          die();
         


       
/*  Function: delete_files
 *  Use:  unlinks the files from the uploads folder in the plugin
 */
  function delete_files($file_path_arr){
      $output_message = array(); 
      foreach($file_path_arr as $path_key => $path_val){
          if(!empty($path_val) && file_exists(UPLOADS_CANDIDATE_APPLICATION_FORM."/".$path_val) ){  
                    $file_unlink_value = 0;
                    $file_unlink_value =  unlink(UPLOADS_CANDIDATE_APPLICATION_FORM."/".$path_val);
                    if($file_unlink_value)
                    {
                      $output_message[] = "Deleted File on Wp server: ".$path_val;
                     }
               
          } 
      }
      return $output_message;
  
  }     
  
 /*filter for createnewcandidate*/
 
 function filter_createnewcandidate($parameter_createnewcandidate){
          if(isset($parameter_createnewcandidate["filename_original"])){
             unset($parameter_createnewcandidate["filename_original"]);
          }
          if(isset($parameter_createnewcandidate["file_upload_path"])){
             unset($parameter_createnewcandidate["file_upload_path"]);
          }
          if(isset($parameter_createnewcandidate["filename_type"])){
             unset($parameter_createnewcandidate["filename_type"]);
          }
          if(isset($parameter_createnewcandidate['script_parameters'])){
            $parameter_createnewcandidate = array_merge($parameter_createnewcandidate, $parameter_createnewcandidate['script_parameters']);
            unset($parameter_createnewcandidate['script_parameters']); 
          }
          
          if(isset($parameter_createnewcandidate["ResponseURL"])){
             unset($parameter_createnewcandidate["ResponseURL"]);
          }
          
          if(isset($parameter_createnewcandidate["ApplyNotifications"])){
             unset($parameter_createnewcandidate["ApplyNotifications"]);
          }
          
          if(isset($parameter_createnewcandidate["MessageName"])){
             unset($parameter_createnewcandidate["MessageName"]);
          } 
          
          if(isset($parameter_createnewcandidate["action"])){
             unset($parameter_createnewcandidate["action"]);
          } 
          if(isset($parameter_createnewcandidate["post_id"])){
             unset($parameter_createnewcandidate["post_id"]);
          } 
          if(isset($parameter_createnewcandidate["nonce"])){
             unset($parameter_createnewcandidate["nonce"]);
          } 
              
          return $parameter_createnewcandidate; 
 
 
 }     
         
         
   
