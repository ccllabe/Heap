<?php
  header('Content-Type: application/json; charset=UTF-8');
  session_start();
  if ($_SERVER['REQUEST_METHOD'] == "GET") {
    $program_path = "cut_target_img.py";
    //$shell_string = "/home/ACCOUNT/P082/P082/bin/python ".$program_path." ".$_SESSION['user_id']." ".$_GET['project']." ".$_GET['lab']." 2>&1";
    $shell_string = "/home/ACCOUNT/miniconda3/envs/tf-1.0.0-gpu/bin/python ".$program_path." ".$_SESSION['user_id']." ".$_GET['project']." ".$_GET['lab']." 2>&1";
    //echo json_encode(array('user' => $_SESSION['user_id'],'project' => $_GET['project'],'lab' => $_GET['lab']));
    $output = shell_exec($shell_string);
    echo json_encode(array('output' => $output));
  }
?>
