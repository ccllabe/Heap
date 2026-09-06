<?php
  header("Content-Type: text/html; charset=utf-8");
  session_start();
  #$user_save_path = $_SERVER['DOCUMENT_ROOT']."/user/";
  $user_save_path = "./user/";
  #$img_detect_path = $_SERVER['DOCUMENT_ROOT']."/lab_computing/";
  $img_detect_path = "./lab_computing/";
  $project = $_POST['project'];
  if(isset($_POST["action"])&&($_POST["action"]=="lab_create")){
    //compute folder format: time()_rand(0,100)_user id_project name
    $lab_folder_name = time();
    $compute_folder_name = $lab_folder_name."_".rand(0,100)."_".$_SESSION['user_id']."_".$project;
    $description_content = array(
      'lab name' => $_POST["lab_name"],
      'lab description' => $_POST["lab_description"],
      'image id' => $_POST["image"],
      'parasitic eggs kind' => $_POST["parasitic_eggs_kind"],
      'Tool' => $_POST["tool"]
    );
    $description_content=json_encode($description_content);
    $lab_folder_path = $user_save_path.$_SESSION['user_id']."/".$project."/Lab/".$lab_folder_name."/";
    $lab_description_path = $lab_folder_path."description.json";
    $compute_folder_path = $img_detect_path.$compute_folder_name."/";
    $compute_state_path = $compute_folder_path."input.json";
    mkdir($lab_folder_path, 0777);
    file_put_contents($lab_description_path, $description_content);
    mkdir($compute_folder_path, 0777);
    file_put_contents($compute_state_path, "");
    header("Location: lab_create_interface.php?project=$project");
  }
?>
