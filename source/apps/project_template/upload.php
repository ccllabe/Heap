
<?php
session_start();
error_reporting(0);
if(($_POST['del'] == 1) && (isset($_POST['del']))) {
  if (file_exists($_POST['filePath'])) {
   unlink($_POST['filePath']);
   $data = json_encode(array('type'=>'success', 'msg'=>'File deleted successfully.')); 
  } else {
   $data = json_encode(array('type'=>'error', 'msg'=>'Can not delete, File not exist.'));
  }
  echo $data;
  exit;
} else {
    $allowFile = array('image/png','image/jpeg','image/jpg','image/tif','image/tiff','application/x-rar-compressed','application/zip','application/x-zip','application/x-zip-compressed');
    if(empty($_FILES["file"]["name"])) {
      echo $data = json_encode(array('type'=>'error','msg'=>"Please choose file to upload."));
      exit;
    }

  foreach($_FILES["file"]["name"] as $i => $name){
    $dir=$_SESSION['course'].'_tmp/Chapter'.$i.'/';
    if(!in_array(["file"]["type"][$i],$allowFile)) {
      if ($_FILES["file"]["error"][$i] > 0) {
        $data =  json_encode(array('type'=>'error', 'msg'=>"Return Code: " . $_FILES["file"]["error"][$i])); 
      } else {
        if (file_exists($dir . $_FILES["file"]["name"][$i])) {
          $data = json_encode(array('type'=>'error', 'msg'=>$_FILES["file"]["name"][$i] . " already exists. ")); 
        } else {
          move_uploaded_file($_FILES["file"]["tmp_name"][$i],
          $dir. $_FILES["file"]["name"][$i]);
          $data = json_encode(array('fileName'=>$_FILES["file"]["name"][$i],'msg'=>$_FILES["file"]["name"][$i] . " uploaded successfully.", 'type'=>'success')); 
        }
      }
    } 
    else {
    $data = json_encode(array('type'=>'error','msg'=>$_FILES["file"]["type"][$i]." Bad file type."));
    
    }
  }
    echo $data;
} 

?> 

