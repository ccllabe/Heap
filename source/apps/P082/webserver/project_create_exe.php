<?php
  header("Content-Type: text/html; charset=utf-8");
  session_start();
  #$user_folder_path = $_SERVER['DOCUMENT_ROOT']."/user/".$_SESSION['user_id']."/";
  $user_folder_path = "./user/".$_SESSION['user_id']."/";
  $create_time = (string)time();
  if(isset($_POST["action"])&&($_POST["action"]=="project_create")){
    //description content
    $description_content = array(
      'project name' => $_POST["project_name"],
      'project description' => $_POST["project_description"]
    );
    $description_content=json_encode($description_content);

    $project_folder_path = $user_folder_path.$create_time.'/';
    mkdir($project_folder_path, 0777);
    //write to json file(project description)
    $description_file = fopen($project_folder_path.'description.json', 'w');
    fwrite($description_file, $description_content);
    fclose($description_file);
    //add space content in a project
    mkdir($project_folder_path.'Image/', 0777);
    mkdir($project_folder_path.'Lab/', 0777);
    mkdir($project_folder_path.'Result/', 0777);
    mkdir($project_folder_path.'Result/all/', 0777);
    mkdir($project_folder_path.'Result/confirmed/', 0777);
    mkdir($project_folder_path.'Result/deleted/', 0777);
    //write to json file(result place)
    $result_record_file = $project_folder_path.'Result/place_check.json';
    file_put_contents($result_record_file, '');
    header("Location: project_create_interface.php");
  }
?>
