<?php
  header('Content-Type: application/json; charset=UTF-8');
  session_start();
  if ($_SERVER['REQUEST_METHOD'] == "GET") {
    if(isset($_SESSION['user_id'])&&$_SESSION['user_id']!=null){
      $result = "ok";
      #$user_folder_path = $_SERVER['DOCUMENT_ROOT']."/user/".$_SESSION['user_id']."/";
	  $user_folder_path = "./user/".$_SESSION['user_id']."/";
      $lab_folder_path = $user_folder_path.$_GET["project"]."/Lab/".$_GET["lab"]."/";
      $it = new RecursiveDirectoryIterator($lab_folder_path, RecursiveDirectoryIterator::SKIP_DOTS);
      $files = new RecursiveIteratorIterator($it,
               RecursiveIteratorIterator::CHILD_FIRST);
      foreach($files as $file) {
          if ($file->isDir()){
              rmdir($file->getRealPath());
          } else {
              unlink($file->getRealPath());
          }
      }
      rmdir($lab_folder_path);
      echo json_encode(array('result' => $result));
    }
  }
?>