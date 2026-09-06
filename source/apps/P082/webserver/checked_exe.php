<?php
if ($_SERVER['REQUEST_METHOD'] == "POST") {
  change();
}
function change() {
  //$file_path2 = $_SERVER['DOCUMENT_ROOT']."/tt.txt";
  //file_put_contents($file_path, $_POST["project_folder_rootpath"]);
  #$file_path = $_SERVER['DOCUMENT_ROOT'].$_POST["project_folder_rootpath"]."Lab/".$_POST["lab"]."/place.txt";
  $file_path = $_POST["project_folder_rootpath"]."Lab/".$_POST["lab"]."/place.txt";
  $lineNumber = $_POST["lineNumber"];
  $place_records = [];
  $f = fopen($file_path,'r');
  while ($line = fgets($f)) {
    $line = str_replace(array("\r", "\n", "\r\n", "\n\r"), '', $line);
    $place_records[] = $line;
  }
  fclose($f);
  $changed_string = $place_records[$lineNumber];
  $changed_array = explode(",", $changed_string);
  if ($changed_array[5]==null){
    $changed_string = $changed_string.",".$_POST["checked"];
  }else{
    $changed_string = $changed_array[0].','.$changed_array[1].','.$changed_array[2].','.$changed_array[3].','.$changed_array[4].','.$_POST["checked"];
  }
  $place_records[$lineNumber] = $changed_string;
  $f = fopen($file_path,'w');
  foreach($place_records as $value){
    $txt = $value.PHP_EOL;
    fwrite($f,$txt);
  }
  fclose($f);
  //file_put_contents($file_path2, $place_records);
}
?>
