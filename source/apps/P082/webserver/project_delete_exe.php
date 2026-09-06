<?php
  header('Content-Type: application/json; charset=UTF-8');
  session_start();
  if ($_SERVER['REQUEST_METHOD'] == "GET") {
    $result = "ok";
    #$user_folder_path = $_SERVER['DOCUMENT_ROOT']."/user/".$_SESSION['user_id']."/";
	$user_folder_path = "./user/".$_SESSION['user_id']."/";
    $project_folder_path = $user_folder_path.$_GET["project"]."/";
    $it = new RecursiveDirectoryIterator($project_folder_path, RecursiveDirectoryIterator::SKIP_DOTS);
    $files = new RecursiveIteratorIterator($it,
             RecursiveIteratorIterator::CHILD_FIRST);
    foreach($files as $file) {
        if ($file->isDir()){
            rmdir($file->getRealPath());
        } else {
            unlink($file->getRealPath());
        }
    }
    rmdir($project_folder_path);
    echo json_encode(array('result' => $result));
  }
?>
