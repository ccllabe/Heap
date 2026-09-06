<?php
  #header("Content-Type: text/html; charset=utf-8");
  session_start();
  #$user_save_path = $_SERVER['DOCUMENT_ROOT']."/user/";
  $user_save_path = "./user/";
  #$img_cut_path = $_SERVER['DOCUMENT_ROOT']."/img_view_cut_computing/";
  $img_cut_path = "./img_view_cut_computing/";
  $project = $_POST['project'];
  echo $project;
  if(isset($_POST["action"])&&($_POST["action"]=="img_upload")){
    //check upload way
    $upload_state = True;
    //upload error check
    //url
    if($_POST["upload_way"]>1){
      $u_url = $_POST["upload_img"];
      switch($_POST["upload_way"]){
        case 2:
          //google drive
          if(preg_match("/^https\:\/\/drive\.google\.com\/file\/d\/\w+([-+.]\w+)\/view\?usp\=sharing$/i",$u_url)){
            $u_url = str_replace("https://drive.google.com/file/d/","https://drive.google.com/uc?export=download&id=",$u_url);
            $u_url = str_replace("/view?usp=sharing","",$u_url);
          }else if(preg_match("/^https\:\/\/drive\.google\.com\/open\?id\=\w+$/i",$u_url)){
            $u_url = str_replace("https://drive.google.com/open?id=","https://drive.google.com/uc?export=download&id=",$u_url);
          }else{
            echo "<script>alert('Google drive url error!!'); location.href = 'img_upload_interface.php?project=".$project."';</script>";
            $upload_state = False;
          }
          break;
        case 3:
          //onedrive
          if(preg_match("/^https\:\/\/1drv\.ms\/u\/s\!\S+$/i",$u_url)){
            $u_url = str_replace("https://1drv.ms/u/s!","https://1drv.ws/u/s!",$u_url);
          }else{
            echo "<script>alert('Onedrive url error!!'); location.href = 'img_upload_interface.php?project=".$project."';</script>";
            $upload_state = False;
          }
          break;
        default:
          //dropbox
          if(preg_match("/^https\:\/\/www\.dropbox\.com\/s\/\S+\?dl\=0$/i",$u_url)){
            $u_url = str_replace("?dl=0","?dl=1",$u_url);
          }else{
            echo "<script>alert('Dropbox url error!!'); location.href = 'img_upload_interface.php?project=".$project."';</script>";
            $upload_state = False;
          }
      }
      $size = getimagesize($u_url);
      $file_ext = image_type_to_extension($size[2]);
      $file_ext = ltrim($file_ext,'.');
    }else{
      //FILES
      if($_FILES["upload_img"]["error"]> 0 ){
        echo "<script>alert('Upload Error:".$_FILES["upload_img"]["error"]."'); location.href = 'img_upload_interface.php?project=".$project."';</script>";
        $upload_state = False;
      }
      elseif($_FILES["upload_img"]["size"]>1610612736){
        echo "<script>alert('Size Error'); location.href = 'img_upload_interface.php?project=".$project."';</script>";
        $upload_state = False;
      }
      $file_ext = array_pop(explode('.',(string)$_FILES["upload_img"]["name"]));
    }
    //img format check
    $file_type_restrict = array("jpg","jpeg","JPG","JPEG","png","PNG","bmp","BMP");
    if(!in_array($file_ext,$file_type_restrict)){
      echo "<script>alert('Type Error'); location.href = 'img_upload_interface.php?project=".$project."';</script>";
      $upload_state = False;
    }
    //save path
    if($upload_state){
      /*$save_path = "./test.".$file_ext;
      if($_POST["upload_way"]>1){
        $content = file_get_contents($u_url);
        file_put_contents($save_path, $content);
      }else{
        move_uploaded_file($_FILES["upload_img"]["tmp_name"], $save_path);
      }*/
      $img_folder_name = time();
      $compute_folder_name = $img_folder_name."_".rand(0,100)."_".$_SESSION['user_id']."_".$project;
      $description_content = array(
        'image name' => $_POST["img_name"],
        'research_topic' => $_POST["research_topic"],
        'research_institute' => $_POST["research_institute"],
        'e_mail' => $_POST["e_mail"],
        'image description' => $_POST["img_description"]
      );
      $description_content=json_encode($description_content);
      $img_folder_path = $user_save_path.$_SESSION['user_id']."/".$project."/Image/".$img_folder_name."/";
      $img_save_path = $img_folder_path."img.".$file_ext;
      $img_description_path = $img_folder_path."description.json";
      $compute_folder_path = $img_cut_path.$compute_folder_name."/";
      $compute_state_path = $compute_folder_path."input.json";
      echo $img_folder_path."\n";
      mkdir($img_folder_path, 0777);
      //upload image
      if($_POST["upload_way"]>1){
        /*$content = file_get_contents($u_url);
        file_put_contents($img_save_path, $content);*/
        $url_cmd = "wget --no-check-certificate '".$u_url."' -O '".$img_save_path."'";
        exec($url_cmd);
      }else{
        move_uploaded_file($_FILES["upload_img"]["tmp_name"], $img_save_path);
      }
      file_put_contents($img_description_path, $description_content);
      echo $compute_folder_path."\n";
      mkdir($compute_folder_path, 0777);
      file_put_contents($compute_state_path, "");
      header("Location: img_upload_interface.php?project=$project");
    }
  }
?>
