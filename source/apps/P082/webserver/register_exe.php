<?php
  header("Content-Type: text/html; charset=utf-8");
  #$log_in_file_path = $_SERVER['DOCUMENT_ROOT']."/user/log_in_info.txt";
  $log_in_file_path = "./user/log_in_info.txt";
  #$log_in_folder_path = $_SERVER['DOCUMENT_ROOT']."/user/";
  $log_in_folder_path = "./user/";
  //get log in form ask
  if(isset($_POST["action"])&&($_POST["action"]=="account_register")){
    //know if _POST["user_id"] in log_in_info.txt
    $reline= null;
    $log_in_info = fopen($log_in_file_path, "r");
    while(! feof($log_in_info)){
      $line_content = fgets($log_in_info);
      $line_content = str_replace(array("\r", "\n", "\r\n", "\n\r"), '', $line_content);
      $line_content_array = explode(":",$line_content);
      if(strcmp($line_content_array[0],$_POST['user_id'])==0){
        $reline = $_POST['user_id'];
        break;
      }
    }
    fclose($log_in_info);
  }
  //decide if build new account and path
  if($_POST["user_id"]!=null){
    if($reline != null){
      //header("Location: register_interface.php");
      echo "<script>alert('already exist the same account'); location.href = 'register_interface.php';</script>";
    }else{
      $pass = password_hash($_POST['password'],PASSWORD_BCRYPT);
      if(strcmp('other',$_POST['job'])==0){
        $job_name = $_POST['job_other'];
      }else{
        $job_name = $_POST['job'];
      }
      $description_content = array(
        'full name' => $_POST["full_name"],
        'gender' => $_POST["gender"],
        'date of birth' => $_POST["date_of_birth"],
        'email address' => $_POST["email_add"],
        'phone number' => $_POST["phone_number"],
        'department' => $_POST["com_sch"],
        'job' => $job_name,
        'address' => $_POST["address"]
      );
      $description_content=json_encode($description_content);
      $user_folder_path = $log_in_folder_path."/".$_POST['user_id'];
      $user_description_path = $user_folder_path."/description.json";
      $log_in_info = fopen($log_in_file_path, "a");
      fwrite($log_in_info,$_POST['user_id'].":".$pass."\n");
      fclose($log_in_info);
      if(mkdir($user_folder_path, 0700)){
        file_put_contents($user_description_path, $description_content);
        //header("Location: log_in_interface.php");
        echo "<script>alert('successful register your account'); location.href = 'log_in_interface.php';</script>";
      }
    }
  }
?>
