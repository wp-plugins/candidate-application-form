<?php
$file_name = $_GET["fileName"];
$path = $_GET["fileUrl"];
$fullfile = $path.$file_name;
if (file_exists('../../uploads/candidate_application_form/'.$file_name)) {
    header('Pragma: public');   // required
    header('Expires: 0');       // no cache
    header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
    header('Last-Modified: '.gmdate ('D, d M Y H:i:s', filemtime ('../../uploads/candidate_application_form/'.$file_name)).' GMT');
    header('Cache-Control: private',false);
    header('Content-Type: '.'application/pdf');
    header('Content-Disposition: attachment; filename="'.basename('../../uploads/candidate_application_form/'.$file_name).'"');
    header('Content-Transfer-Encoding: binary');
    header('Content-Length: '.filesize('../../uploads/candidate_application_form/'.$file_name));    // provide file size
    header('Connection: close');
    readfile('../../uploads/candidate_application_form/'.$file_name);     // push it out
    exit();
}




//die($fullfile);  //wp-content/uploads/candidate_application_form/LINK_Certification_Steps.pdf
//die($_SERVER['DOCUMENT_ROOT']); ///home/asifarif/phpdevelopment
//die($_SERVER['SERVER_NAME']);  //localhost
//die($_SERVER['PHP_SELF');
?>
