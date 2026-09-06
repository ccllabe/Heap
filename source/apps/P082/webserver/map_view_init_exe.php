<?php
  ini_set('memory_limit','4096M');
  header('Content-Type: application/json; charset=UTF-8');
  session_start();
  if ($_SERVER['REQUEST_METHOD'] == "GET") {
    project_loading();
  }
  function project_loading(){
    $project = $_GET["project"];
    #$project_folder_rootpath = "/user/".$_SESSION['user_id']."/".$project."/";
	  $project_folder_rootpath = "./user/".$_SESSION['user_id']."/".$project."/";
    #$project_folder_path = $_SERVER['DOCUMENT_ROOT'].$project_folder_rootpath;
    $project_folder_path = $project_folder_rootpath;

    //list all images(img_info, ori_img_size, cut 100_100_imgs)
    $img_fds_info = [];
    $cut_img_names = [];
    $ori_imgs_size = [];
    $thumbnail_imgs_size = [];
    $scale_bar_img_name = [];
    $img_folder_path = $project_folder_path."Image/";
    $img_fds =  array_map('basename', glob($img_folder_path."*", GLOB_ONLYDIR));
    for($i=0;$i<count($img_fds);$i++)
    {
      $finish_json_path = $img_folder_path.$img_fds[$i]."/running.json";
      if(is_file($finish_json_path)){
        //get img Info
        $img_description_path = $img_folder_path.$img_fds[$i]."/description.json";
        $img_description = file_get_contents($img_description_path);
        $img_description = json_decode($img_description);
        $img_fds_info[$i] = $img_description->{'image name'};
        //get img size
        $ori_img_path = $img_folder_path.$img_fds[$i]."/img.*";
        $ori_img_path = glob($ori_img_path, GLOB_BRACE);
        $ori_img_format = getimagesize($ori_img_path[0]);
        $ori_imgs_size[$i] = array($ori_img_format[0],$ori_img_format[1]);
        //get img cut_imgs name
        $cut_img_path = $img_folder_path.$img_fds[$i]."/100_100_imgs/*.*";
        $cut_img_names[$i] = array_map('basename', glob($cut_img_path, GLOB_BRACE));
        //get img thumbnail
        $thumbnail_img_path = $img_folder_path.$img_fds[$i]."/thumbnail.png";
        $thumbnail_img_format = getimagesize($thumbnail_img_path);
        $thumbnail_imgs_size[$i] = array($thumbnail_img_format[0],$thumbnail_img_format[1]);
        //get scale bar img name
        $scale_img_path = $img_folder_path.$img_fds[$i]."/scale_bar_img.*";
        $scale_bar_img_name[$i] = glob($scale_img_path, GLOB_BRACE)[0];
      }
    }
    //list all parasite egg place(read description.json, place.txt)
    $tool_array = array("multi_fast_rcnn","multi_fast_rcnn_2","ssd_fordel","u_net");
    $lab_fds_info = [];
    $place_records = [];
    $lab_folder_path = $project_folder_path."Lab/";
    $lab_fds =  array_map('basename', glob($lab_folder_path."*", GLOB_ONLYDIR));
    for($i=0;$i<count($lab_fds);$i++)
    {
      $finish_json_path = $lab_folder_path.$lab_fds[$i]."/running.json";
      if(is_file($finish_json_path)){
        //get lab Info
        $lab_description = file_get_contents($lab_folder_path.$lab_fds[$i]."/description.json");
        $lab_description = json_decode($lab_description);
        $lab_fds_info[$i][0] = $lab_description->{'lab name'};
        $lab_fds_info[$i][1] = array_search($lab_description->{'Tool'},$tool_array);
        //get lab frame place
        $txt_path = $lab_folder_path.$lab_fds[$i]."/place.txt";
        $f = fopen($txt_path,'r');
        while ($line = fgets($f)) {
          $line = str_replace(array("\r", "\n", "\r\n", "\n\r"), '', $line);
          $place_records[$i][] = $line;
        }
        fclose($f);
      }
    }
    //list all checkplace(read place_check)
    //$result_check_file_path = $project_folder_path."Result/place_check.json";
    //$result_check_content = file_get_contents($result_check_file_path);
    //$result_check_content = str_replace(array("\r", "\n", "\r\n", "\n\r"), '', $result_check_content);

    echo json_encode(array('project_folder_rootpath' => $project_folder_rootpath, 'ori_imgs_size' => $ori_imgs_size,'thumbnail_imgs_size' => $thumbnail_imgs_size,'img_fds' => $img_fds,'img_fds_info' => $img_fds_info,'cut_img_names' => $cut_img_names,'lab_fds_info' => $lab_fds_info,'lab_fds' => $lab_fds,'place_records' => $place_records,'scale_bar_img_name' => $scale_bar_img_name));
  }
?>
