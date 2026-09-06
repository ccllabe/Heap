<?php
  $ori_path = "./user/demo2/1644893256/Image/1644893807/img.png";
  $target_path1 = "./imgformattest/test1.jpg";
  $target_path2 = "./imgformattest/test2.png";
  $target_path3 = "./imgformattest/Process_6002_z_1650_0.BMP";
  $target_path4 = "./imgformattest/test4.png";
  /*$img = imagecreatefrompng($ori_path);
  imagejpeg($img,$target_path1);
  $img = imagecreatefromjpeg($target_path1);
  imagepng($img,$target_path2);
  $img = imagecreatefrompng($ori_path);
  imagewbmp($img,$target_path3);*/
  //imagecreatefromwbmp($target_path3);
  bmp_func_add();
  $img = imagecreatefrombmp($target_path3);
  imagepng($img,$target_path4);
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
?>
