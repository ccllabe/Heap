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
    //original image check
    $img_n=1;
    $upload_state1 = True;
    if($_POST["upload_way".$img_n]>1){
      //change_url、check upload
      list($u_url1, $file_ext1, $upload_state1) = upload_link_check($upload_state1,$img_n,$project);
    }else{
      list($file_ext1,$upload_state1)=upload_local_check($upload_state1,$img_n,$project);
    }
    $upload_state1=upload_size_check($file_ext1,$upload_state1,$project);
    //scale bar image check
    $img_n=2;
    $upload_state2 = True;
    if($_POST["upload_way".$img_n]>1){
      //change_url、check upload
      list($u_url2,$file_ext2,$upload_state2)=upload_link_check($upload_state2,$img_n,$project);
    }else{
      list($file_ext2,$upload_state2)=upload_local_check($upload_state2,$img_n,$project);
    }
    $upload_state2=upload_size_check($file_ext2,$upload_state2,$project);
    //save path
    if($upload_state1&&$upload_state2){
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
      $img_save_path1 = $img_folder_path."img.".$file_ext1;
      $img_save_path2 = $img_folder_path."scale_bar_img.".$file_ext2;
      $img_save_path1_t = $img_folder_path."img.png";
      $img_description_path = $img_folder_path."description.json";
      $compute_folder_path = $img_cut_path.$compute_folder_name."/";
      $compute_state_path = $compute_folder_path."input.json";
      echo $img_folder_path."\n";
      mkdir($img_folder_path, 0777);
      //upload image
      //1.upload original image
      $img_n=1;
      if($_POST["upload_way".$img_n]>1){
        /*$content = file_get_contents($u_url1);
        file_put_contents($img_save_path1, $content);*/
        $url_cmd = "wget --no-check-certificate '".$u_url1."' -O '".$img_save_path1."'";
        exec($url_cmd);
      }else{
        move_uploaded_file($_FILES["upload_img".$img_n]["tmp_name"], $img_save_path1);
      }
      //2.upload scale bar image
      $img_n=2;
      if($_POST["upload_way".$img_n]>1){
        /*$content = file_get_contents($u_url2);
        file_put_contents($img_save_path2, $content);*/
        //large file(goole drive)
        //wget --load-cookies /tmp/cookies.txt "https://docs.google.com/uc?export=download&confirm=$(wget --quiet --save-cookies /tmp/cookies.txt --keep- session-cookies --no-check-certificate 'https://docs.google.com/uc?export=download&id=文件ID' -O- | sed -rn 's/.*confirm=([0-9A-Za-z_]+).*/\1\n/p')&id=文件ID"-O文件名&& rm -rf /tmp/cookies.txt
        $url_cmd = "wget --no-check-certificate '".$u_url2."' -O '".$img_save_path2."'";
        exec($url_cmd);
      }else{
        move_uploaded_file($_FILES["upload_img".$img_n]["tmp_name"], $img_save_path2);
      }
      file_put_contents($img_description_path, $description_content);
      echo $compute_folder_path."\n";
      mkdir($compute_folder_path, 0777);
      file_put_contents($compute_state_path, "");
      header("Location: img_upload_interface.php?project=$project");
    }else{
      echo "<script>alert('Type Error'); location.href = 'img_upload_interface.php?project=".$project."';</script>";
    }
  }
  function upload_link_check($upload_state,$img_n,$project){
    $u_url = $_POST["upload_img".$img_n];
    switch($_POST["upload_way".$img_n]){
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
    return array($u_url,$file_ext,$upload_state);
  }
  function upload_local_check($upload_state,$img_n,$project){
    if($_FILES["upload_img".$img_n]["error"]> 0 ){
      echo "<script>alert('Upload Error:".$_FILES["upload_img".$img_n]["error"]."'); location.href = 'img_upload_interface.php?project=".$project."';</script>";
      $upload_state = False;
    }
    elseif($_FILES["upload_img".$img_n]["size"]>1610612736){
      echo "<script>alert('Size Error'); location.href = 'img_upload_interface.php?project=".$project."';</script>";
      $upload_state = False;
    }
    $file_ext = array_pop(explode('.',(string)$_FILES["upload_img".$img_n]["name"]));
    return array($file_ext,$upload_state);
  }
  function upload_size_check($file_ext,$upload_state,$project){
    //img format check
    $file_type_restrict = array("jpg","jpeg","JPG","JPEG","png","PNG","bmp","BMP");
    if(!in_array($file_ext,$file_type_restrict)){
      echo "<script>alert('Type Error'); location.href = 'img_upload_interface.php?project=".$project."';</script>";
      $upload_state = False;
    }
    return $upload_state;
  }
?>
