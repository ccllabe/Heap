<?php
  Session_start();
  //寫入json

  //假如沒有student.json，開777
  if(!file_exists("../".$_SESSION["course"]."/Students/student.json")){
    $a = fopen("../".$_SESSION["course"]."/Students/student.json", "w+") or die("Unable to open file!");
    shell_exec("chmod 777 ../".$_SESSION["course"]."/Students/student.json");
    fclose($a);
  }

  //寫入名單
  $request_body = file_get_contents("php://input",'r');
  $myfile = fopen("../".$_SESSION["course"]."/Students/student.json", "w") or die("Unable to open file!");
  fwrite($myfile, $request_body);
  
  //創建資料夾
  $json_string = file_get_contents("../".$_SESSION["course"]."/Students/student.json");
  $data = json_decode($json_string,true);
  $path='../'.$_SESSION["course"].'/Students/';
  $exam='/Exam';
  for ( $i=0 ; $i<count($data) ; $i++ ) {
    //echo $data[$i]['ID'];
    if(!is_dir($path.$data[$i]['ID'])){
      mkdir($path.$data[$i]['ID'], 0777, true);
      mkdir($path.$data[$i]['ID'].$exam, 0777, true);
    }

  }
  fclose($myfile);
?>