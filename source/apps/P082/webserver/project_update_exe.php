<?php
  header("Content-Type: text/html; charset=utf-8");
  session_start();
  #$user_folder_path = $_SERVER['DOCUMENT_ROOT']."/user/".$_SESSION['user_id']."/";
  $user_folder_path = "./user/".$_SESSION['user_id']."/";
  $project = $_POST["project"];
  if(isset($_POST["action"])&&($_POST["action"]=="project_update")){
    //description content
    $description_content = array(
      'project name' => $_POST["project_name"],
      'project description' => $_POST["project_description"]
    );
    $description_content=json_encode($description_content);
    $project_folder_path = $user_folder_path.$_POST["project"].'/';
    $description_file = fopen($project_folder_path.'description.json', 'w');
    fwrite($description_file, $description_content);
    fclose($description_file);
    header("Location: summary_interface.php?project=$project&link_page_title=1");
  }
?>
