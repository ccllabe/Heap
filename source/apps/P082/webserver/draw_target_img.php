<?php
  header("Content-Type: text/html; charset=utf-8");
  ini_set("memory_limit","100000000000");
  set_time_limit(0);

  //get project parameter
  $user_id= $_SERVER['argv'][1];
  $project = $_SERVER['argv'][2];
  $img_fd = $_SERVER['argv'][3];
  //project path
  $project_folder_rootpath = "/user/".$user_id."/".$project."/";
  $project_folder_rootpath = "./user/".$user_id."/".$project."/";
  //$project_folder_path = $_SERVER['DOCUMENT_ROOT'].$project_folder_rootpath;
  #$project_folder_path = "C:/xampp/htdocs".$project_folder_rootpath;
  $project_folder_path = $project_folder_rootpath;

  //image path
  //all img folder path
  $img_folder_path = $project_folder_path."Image/";
  //search spcific img path
  $ori_img_path = $img_folder_path.$img_fd."/img.*";
  $ori_img_path = glob($ori_img_path, GLOB_BRACE);
  //echo $ori_img_path[0];

  //lab path
  $place_records = [];
  $lab_folder_path = $project_folder_path."Lab/";
  $lab_fds =  glob($lab_folder_path."*", GLOB_ONLYDIR);
  for($i=0;$i<count($lab_fds);$i++)
  {
    $txt_path = $lab_fds[$i]."/place.txt";
    $f = fopen($txt_path,'r');

    while ($line = fgets($f)) {
      $line = str_replace(array("\r", "\n", "\r\n", "\n\r"), '', $line);
      $linearray = explode(',',$line);
      if(isset($linearray[5])&&intval($linearray[5])>0){
        $place_records[$i][] = $linearray;
      }
    }
    fclose($f);
  }

  //result wait to download place
  //C:\xampp\htdocs\user\tiffany\1559029302\Result
  //$result_time = time();
  $result_folder_path = $project_folder_path."Result/".$img_fd;



  //delete zip
  function delete_zip(){
    global $result_folder_path;
    $zip_t_path = $result_folder_path.".zip";
    if(file_exists($zip_t_path)) unlink($zip_t_path);
  }

  //draw
  function draw_ori_img(){
    //ini input(image,thick,color)
    global $ori_img_path, $place_records,$result_folder_path;
    //$test = imagecreatefromstring(file_get_contents($ori_img_path[0]));
    $file_ar = explode('.',$ori_img_path[0]);
    $file_ext = array_pop($file_ar);
    $file_type_jpg = array("jpg","jpeg","JPG","JPEG");
    $file_type_bmp = array("bmp","BMP");
    if(in_array($file_ext,$file_type_jpg)){
      $test = imagecreatefromjpeg($ori_img_path[0]);
    }elseif(in_array($file_ext,$file_type_bmp)){
      bmp_func_add();
      $test = imagecreatefrombmp($ori_img_path[0]);
    }else{
      $test = imagecreatefromstring(file_get_contents($ori_img_path[0]));
    }
    imagesetthickness($test, 5);
    $red = imagecolorallocate($test, 255, 0, 0);
    //draw frame which confirmed
    foreach($place_records as $lab_content){
      foreach($lab_content as $frame_array){
        imagerectangle($test, $frame_array[0], $frame_array[1], $frame_array[2], $frame_array[3], $red);
      }
    }
    //if no file than save img file
    if (!file_exists($result_folder_path)){
      mkdir($result_folder_path, 0777, true);
      $img_path = $result_folder_path."/result.png";
      imagepng($test,$img_path);
      imagedestroy($test);
    }
  }

  //compression and delete original folder
  function result_compression(){
    global $result_folder_path,$img_fd;
    //compression
    $zip = new ZipArchive;
    $zip_t_path = $result_folder_path.".zip";
    $zip->open($zip_t_path, ZipArchive::CREATE);
    $srcDir = $result_folder_path;
    $files = scandir($srcDir);
    unset($files[0],$files[1]);
    foreach($files as $file){
      $zip->addFile($srcDir."/".$file, $img_fd."/".$file);
    }
    $zip->close();
    //delete original folder
    foreach($files as $file){
      unlink($srcDir."/".$file);
    }
    rmdir($srcDir);
  }

  function bmp_func_add(){
    if (!function_exists("imagecreatefrombmp")) {
      function imagecreatefrombmp($fileName) {
        $file    =    fopen($fileName,"rb");
        $read    =    fread($file,10);
        while(!feof($file)&&($read<>""))
        $read    .=    fread($file,1024);
        $temp    =    unpack("H*",$read);
        $hex    =    $temp[1];
        $header    =    substr($hex,0,108);
        if (substr($header,0,4)=="424d")
        {
            $header_parts    =    str_split($header,2);
            $width            =    hexdec($header_parts[19].$header_parts[18]);
            $height            =    hexdec($header_parts[23].$header_parts[22]);
            unset($header_parts);
        }
        $x                =    0;
        $y                =    1;
        $image            =    imagecreatetruecolor($width,$height);
        $body            =    substr($hex,108);
        $body_size        =    (strlen($body)/2);
        $header_size    =    ($width*$height);
        $usePadding        =    ($body_size>($header_size*3)+4);
        for ($i=0;$i<$body_size;$i+=3)
        {
            if ($x>=$width)
            {
                if ($usePadding)
                    $i    +=    $width%4;
                $x    =    0;
                $y++;
                if ($y>$height)
                    break;
            }
            $i_pos    =    $i*2;
            $r        =    hexdec($body[$i_pos+4].$body[$i_pos+5]);
            $g        =    hexdec($body[$i_pos+2].$body[$i_pos+3]);
            $b        =    hexdec($body[$i_pos].$body[$i_pos+1]);
            $color    =    imagecolorallocate($image,$r,$g,$b);
            imagesetpixel($image,$x,$height-$y,$color);
            $x++;
        }
        unset($body);
        return $image;
      }
    }
  }

//main running
  delete_zip();
  draw_ori_img();
  result_compression();
  echo $img_fd;

?>
