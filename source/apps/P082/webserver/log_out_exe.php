<?php
  header('Content-Type: application/json; charset=UTF-8');
  session_start();
  if ($_SERVER['REQUEST_METHOD'] == "GET") {
    if($_SESSION['user_id'] != null){
      session_destroy();
    }
    echo json_encode(array('session_clear' => "ok"));
  }
?>
