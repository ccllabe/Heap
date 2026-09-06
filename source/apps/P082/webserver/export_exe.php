<?php
  header('Content-Type: application/json; charset=UTF-8');
  session_start();
  set_time_limit(0);
  if ($_SERVER['REQUEST_METHOD'] == "GET") {
    /*$program_path = "cut_target_img.py";
    $shell_string = "python ".$program_path." ".$_SESSION['user_id']." ".$_GET['project']." ".$_GET['lab'];
    //echo json_encode(array('user' => $_SESSION['user_id'],'project' => $_GET['project'],'lab' => $_GET['lab']));
    $output = shell_exec($shell_string);
    echo json_encode(array('output' => $output));*/
    $project = $_GET["project"];
    $img_fd = $_GET["img_fd"];
    $program_path = "draw_target_img.php";
    $shell_string = "/usr/bin/php ".$program_path." ".$_SESSION['user_id']." ".$_GET['project']." ".$_GET["img_fd"]." 2>&1";
    $output = shell_exec($shell_string);
    echo json_encode(array('output' => $output));
  }
?>
