<?php
  header("Content-Type: text/html; charset=utf-8");
  session_start();
  if($_SESSION['user_id'] != null){
    session_destroy();
  }
  session_start();
  #$log_in_file_path = $_SERVER['DOCUMENT_ROOT']."/user/log_in_info.txt";
  $log_in_file_path = "./user/log_in_info.txt";
  //get log in form ask
  if(isset($_POST["action"])&&($_POST["action"]=="account_log_in")){
    //know if _POST["user_id"] in log_in_info.txt
    $log_in_info = fopen($log_in_file_path, "r");
    while(! feof($log_in_info)){
      $line_content = fgets($log_in_info);
      $line_content = str_replace(array("\r", "\n", "\r\n", "\n\r"), '', $line_content);
      $line_content_array = explode(":",$line_content);
      if((strcmp($line_content_array[0],$_POST['user_id'])==0)&&(password_verify($_POST['password'],$line_content_array[1]))){
        $_SESSION['user_id'] = $_POST['user_id'];
        break;
      }
    }
    fclose($log_in_info);
  }

  //decide go which path
  if($_SESSION['user_id'] != null){
    header("Location: project_create_interface.php");
  }
  else{
    //header("Location: log_in_interface.php");
    echo "<script>alert('account is not exist'); location.href = 'log_in_interface.php';</script>";
  }
?>
