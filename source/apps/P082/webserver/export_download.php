<?php
  session_start();
  $project = $_GET["project"];
  #$project_folder_rootpath = "/user/".$_SESSION['user_id']."/".$project."/";
  $project_folder_rootpath = "./user/".$_SESSION['user_id']."/".$project."/";
  //$project_folder_path = $_SERVER['DOCUMENT_ROOT'].$project_folder_rootpath;
  #$project_folder_path = "C:/xampp/htdocs".$project_folder_rootpath;
  $project_folder_path = $project_folder_rootpath;
  
  $download=$project_folder_path."Result/".$_GET["download"];
  result_download();
  //download
  function result_download(){
    global $project,$download;
    $zip_t_path = $download.".zip";
    if (file_exists($zip_t_path)) {
      ob_start();
      //header("Cache-Control: public");
      //header("Content-Description: File Transfer");
      header('Content-Type: application/zip');
      header("Content-Transfer-Encoding: binary");
      header('Content-Disposition: attachment; filename="'.basename($zip_t_path).'"');
      header('Content-Length: ' . filesize($zip_t_path));
      //flush();
      //ob_end_clean();
      while (ob_get_level()){
        ob_end_clean();
      }
      readfile($zip_t_path);
      //delete zip file
      //unlink($zip_t_path);
    }
    //header("location: export_interface.php?project=".$project);
    exit;
  }
?>
