<?php
    session_start();
    $UPLOAD_DIR=$_SESSION['course']."/screenshot";
    //define('UPLOAD_DIR', "{$_SESSION['course']}/screenshot");       //圖片保存路徑 
    $img = $_POST['img'];
    $img = str_replace('data:image/png;base64,', '', $img);
    $img = str_replace(' ', '+', $img);
    $data = base64_decode($img);
    $day = date("Ymd",time());
    //$file_name = mt_rand(1000,9999);
    if(!is_dir($UPLOAD_DIR)){
        mkdir($UPLOAD_DIR, 0777, true);
    }
    $file = $UPLOAD_DIR ."/".$_POST['name']. '.png';

    if(file_exists($UPLOAD_DIR ."/".$_POST['name']. '.png')){
      echo "error";
      return;
    }
    
    shell_exec("sudo chmod 777 ".$file."");
    $success = file_put_contents($file, $data);
    echo $file;
    // return $success;
    
    // output string
    // return  $output = '<img src="'. $file .'" alt="Canvas Image" />' ;
    


?>
