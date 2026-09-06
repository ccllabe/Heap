<?php
  session_start();
  //folder path
  //donate image save path
  #$d_img_save_path = $_SERVER['DOCUMENT_ROOT']."/donate_img_data/";
  $d_img_save_path = "./donate_img_data/";
  //donate model save path
  #$d_mod_save_path = $_SERVER['DOCUMENT_ROOT']."/donate_mod_data/";
  $d_mod_save_path = "./donate_mod_data/";

  //check form (donate image or donate model)
  if(isset($_POST["action"])){
    if($_POST["action"]=="img_donate"){
      donate_Img_exe();
    }
    if($_POST["action"]=="mod_donate"){
      donate_Mod_exe();
    }
    echo $_POST["action"];
  }

  //donate image save
  function donate_Img_exe(){
    /*echo $_POST["authorizer_name"];
    echo $_POST["authorized_institute"];
    echo $_POST["phone_number"];
    echo $_POST["e_mail"];
    echo $_POST["p_o_download"];
    echo $_POST["authorization_description"];
    echo $_POST["parasite_e_k"];
    echo $_POST["s_model"];
    echo $_POST["img_size"];
    echo $_POST["upload_way"];
    echo $_FILES["upload_file"]["name"];
    echo $_POST["file_description"];*/
    global $d_img_save_path;
    //check upload way
    $upload_state = True;
    //upload error check
    //url
    if($_POST["upload_way"]!=1){
      $u_url = $_POST["upload_file"];
      switch($_POST["upload_way"]){
        case 2:
          //google drive
          if(preg_match("/^https\:\/\/drive\.google\.com\/file\/d\/\w+\/view\?usp\=sharing$/i",$u_url)){
            $u_url = str_replace("https://drive.google.com/file/d/","https://drive.google.com/uc?export=download&id=",$u_url);
            $u_url = str_replace("/view?usp=sharing","",$u_url);
          }else if(preg_match("/^https\:\/\/drive\.google\.com\/open\?id\=\w+$/i",$u_url)){
            $u_url = str_replace("https://drive.google.com/open?id=","https://drive.google.com/uc?export=download&id=",$u_url);
          }else{
            echo "<script>alert('Google drive url error!!'); location.href = 'donate_data_interface.php?page=donate_img';</script>";
            $upload_state = False;
          }
          break;
        case 3:
          //onedrive
          if(preg_match("/^https\:\/\/1drv\.ms\/u\/s\!\S+$/i",$u_url)){
            $u_url = str_replace("https://1drv.ms/u/s!","https://1drv.ws/u/s!",$u_url);
          }else{
            echo "<script>alert('Onedrive url error!!'); location.href = 'donate_data_interface.php?page=donate_img';</script>";
            $upload_state = False;
          }
          break;
        default:
          //dropbox
          if(preg_match("/^https\:\/\/www\.dropbox\.com\/s\/\S+\?dl\=0$/i",$u_url)){
            $u_url = str_replace("?dl=0","?dl=1",$u_url);
          }else{
            echo "<script>alert('Dropbox url error!!'); location.href = 'donate_data_interface.php?page=donate_img';</script>";
            $upload_state = False;
          }
      }
      $size = getimagesize($u_url);
      $file_ext = image_type_to_extension($size[2]);
      $file_ext = ltrim($file_ext,'.');
    }else{
      //FILES
      if($_FILES["upload_file"]["error"]> 0 ){
        echo "<script>alert('Upload Error:".$_FILES["upload_file"]["error"]."'); location.href = 'donate_data_interface.php?page=donate_img';</script>";
        $upload_state = False;
      }
      elseif($_FILES["upload_file"]["size"]>1610612736){
        echo "<script>alert('Size Error'); location.href = 'donate_data_interface.php?page=donate_img';</script>";
        $upload_state = False;
      }
      $file_ext = array_pop(explode('.',(string)$_FILES["upload_file"]["name"]));
    }
    //img format check
    $file_type_restrict = array("zip","7z","rar");
    if(!in_array($file_ext,$file_type_restrict)){
      echo "<script>alert('Type Error'); location.href = 'donate_data_interface.php?page=donate_img';</script>";
      $upload_state = False;
    }
    //save path
    if($upload_state){
      //prepare save content
      $d_folder_name = time()."_".rand(0,100)."_".$_SESSION['user_id'];
      //Step.1 form
      $authorization_content = array(
        'authorizer name' => $_POST["authorizer_name"],
        'authorized institute' => $_POST["authorized_institute"],
        'phone number' => $_POST["phone_number"],
        'e_mail' => $_POST["e_mail"],
        'p_o_download' => $_POST["p_o_download"],
        'authorization description' => $_POST["authorization_description"]
      );
      $authorization_content=json_encode($authorization_content);
      //Step.2 form
      $data_info_content = array(
        'parasite_e_k' => $_POST["parasite_e_k"],
        's_model' => $_POST["s_model"],
        'img_size' => $_POST["img_size"],
        'file_description' => $_POST["file_description"]
      );
      $data_info_content=json_encode($data_info_content);
      //path
      //-fd
      $d_folder_path = $d_img_save_path.$d_folder_name."/";
      //-fd-authorization.json
      $authorization_path = $d_folder_path."authorization.json";
      //-fd-data_info.json
      $data_info_path = $d_folder_path."data_info.json";
      //-fd-data.zipformat
      $zip_data_path = $d_folder_path."data.".$file_ext;
      //upload
      //fd
      mkdir($d_folder_path, 0777);
      //data-zip
      if($_POST["upload_way"]>1){
        /*$content = file_get_contents($u_url);
        file_put_contents($zip_data_path, $content);*/
        $url_cmd = "wget --no-check-certificate '".$u_url."' -O '".$zip_data_path."'";
        exec($url_cmd);
      }else{
        move_uploaded_file($_FILES["upload_file"]["tmp_name"], $zip_data_path);
      }
      //json
      file_put_contents($authorization_path, $authorization_content);
      file_put_contents($data_info_path, $data_info_content);
    }
    header("Location: donate_data_interface.php?page=donate_img");
  }

  //donate model save
  function donate_Mod_exe(){
    /*echo $_POST["authorizer_name"];
    echo $_POST["authorized_institute"];
    echo $_POST["phone_number"];
    echo $_POST["e_mail"];
    echo $_POST["p_o_download"];
    echo $_POST["authorization_description"];
    echo $_POST["parasite_e_k"];
    echo $_POST["s_model"];
    echo $_POST["img_size"];
    echo $_POST["upload_way"];
    echo $_FILES["upload_file"]["name"];
    echo $_POST["file_description"];*/
    global $d_mod_save_path;
    //check upload way
    $upload_state = True;
    //upload error check
    //url
    if($_POST["upload_way"]!=1){
      $u_url = $_POST["upload_file"];
      switch($_POST["upload_way"]){
        case 2:
          //google drive
          if(preg_match("/^https\:\/\/drive\.google\.com\/file\/d\/\w+\/view\?usp\=sharing$/i",$u_url)){
            $u_url = str_replace("https://drive.google.com/file/d/","https://drive.google.com/uc?export=download&id=",$u_url);
            $u_url = str_replace("/view?usp=sharing","",$u_url);
          }else if(preg_match("/^https\:\/\/drive\.google\.com\/open\?id\=\w+$/i",$u_url)){
            $u_url = str_replace("https://drive.google.com/open?id=","https://drive.google.com/uc?export=download&id=",$u_url);
          }else{
            echo "<script>alert('Google drive url error!!'); location.href = 'donate_data_interface.php?page=donate_model';</script>";
            $upload_state = False;
          }
          break;
        case 3:
          //onedrive
          if(preg_match("/^https\:\/\/1drv\.ms\/u\/s\!\S+$/i",$u_url)){
            $u_url = str_replace("https://1drv.ms/u/s!","https://1drv.ws/u/s!",$u_url);
          }else{
            echo "<script>alert('Onedrive url error!!'); location.href = 'donate_data_interface.php?page=donate_model';</script>";
            $upload_state = False;
          }
          break;
        default:
          //dropbox
          if(preg_match("/^https\:\/\/www\.dropbox\.com\/s\/\S+\?dl\=0$/i",$u_url)){
            $u_url = str_replace("?dl=0","?dl=1",$u_url);
          }else{
            echo "<script>alert('Dropbox url error!!'); location.href = 'donate_data_interface.php?page=donate_model';</script>";
            $upload_state = False;
          }
      }
      $size = getimagesize($u_url);
      $file_ext = image_type_to_extension($size[2]);
      $file_ext = ltrim($file_ext,'.');
    }else{
      //FILES
      if($_FILES["upload_file"]["error"]> 0 ){
        echo "<script>alert('Upload Error:".$_FILES["upload_file"]["error"]."'); location.href = 'donate_data_interface.php?page=donate_model';</script>";
        $upload_state = False;
      }
      elseif($_FILES["upload_file"]["size"]>1610612736){
        echo "<script>alert('Size Error'); location.href = 'donate_data_interface.php?page=donate_model';</script>";
        $upload_state = False;
      }
      $file_ext = array_pop(explode('.',(string)$_FILES["upload_file"]["name"]));
    }
    //model format check
    $file_type_restrict = array("zip","7z","rar");
    if(!in_array($file_ext,$file_type_restrict)){
      echo "<script>alert('Type Error'); location.href = 'donate_data_interface.php?page=donate_model';</script>";
      $upload_state = False;
    }
    //save path
    if($upload_state){
      //prepare save content
      $d_folder_name = time()."_".rand(0,100)."_".$_SESSION['user_id'];
      //Step.1 form
      $authorization_content = array(
        'authorizer name' => $_POST["authorizer_name"],
        'authorized institute' => $_POST["authorized_institute"],
        'phone number' => $_POST["phone_number"],
        'e_mail' => $_POST["e_mail"],
        'p_o_download' => $_POST["p_o_download"],
        'authorization description' => $_POST["authorization_description"]
      );
      $authorization_content=json_encode($authorization_content);
      //Step.2 form
      $data_info_content = array(
        'parasite_e_k' => $_POST["parasite_e_k"],
        's_model' => $_POST["s_model"],
        'img_size' => $_POST["img_size"],
        'file_description' => $_POST["file_description"]
      );
      $data_info_content=json_encode($data_info_content);
      //path
      //-fd
      $d_folder_path = $d_mod_save_path.$d_folder_name."/";
      //-fd-authorization.json
      $authorization_path = $d_folder_path."authorization.json";
      //-fd-data_info.json
      $data_info_path = $d_folder_path."data_info.json";
      //-fd-data.zipformat
      $zip_data_path = $d_folder_path."data.".$file_ext;
      //upload
      //fd
      mkdir($d_folder_path, 0777);
      //data-zip
      if($_POST["upload_way"]>1){
        /*$content = file_get_contents($u_url);
        file_put_contents($zip_data_path, $content);*/
        $url_cmd = "wget --no-check-certificate '".$u_url."' -O '".$zip_data_path."'";
        exec($url_cmd);
      }else{
        move_uploaded_file($_FILES["upload_file"]["tmp_name"], $zip_data_path);
      }
      //json
      file_put_contents($authorization_path, $authorization_content);
      file_put_contents($data_info_path, $data_info_content);
    }
    header("Location: donate_data_interface.php?page=donate_model");
  }
?>
