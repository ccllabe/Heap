<?php
  session_start();
  //ex. ?model=SSD300&egg_type=Trichuris_trichiura_egg&slide_name=original&zip_file=300_300_cut_img
  $model = $_GET["model"];
  $egg_type = $_GET["egg_type"];
  $slide_name = $_GET["slide_name"];
  $zip_file = $_GET["zip_file"];
  $project_folder_rootpath = "./Pre_train_data/";

  $download = $project_folder_rootpath.$model."/".$egg_type."/".$slide_name."/".$zip_file;
  //echo $download;
  data_download();
  //download
  function data_download(){
    global $download;
    $zip_t_path = $download.".zip";
    if (file_exists($zip_t_path)) {
      ob_start();
      //header("Cache-Control: public");
      //header("Content-Description: File Transfer");
      header('Content-Type: application/zip');
      header("Content-Transfer-Encoding: binary");
      header('Content-Disposition: attachment; filename="'.basename($zip_t_path).'"');
      header('Content-Length: ' . filesize($zip_t_path));
      //flush();
      //ob_end_clean();
      while (ob_get_level()){
        ob_end_clean();
      }
      readfile($zip_t_path);
      //delete zip file
      //unlink($zip_t_path);
    }
    exit;
  }
?>
