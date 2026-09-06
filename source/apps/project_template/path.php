<br>
<?php
    $path =$_POST['foldername'];
    if($path!='uploadfile')
    $last= dirname($path);//上一層目錄名稱
    $dir =$path.'/';
    $j=0;
    

    echo"<div>";
    if (isset($last)){
    echo"<button class='folder' value='".$last."'><i class='far fa-folder-open fa-3x'></i></i><br>";
      echo 'back to '.basename($last)."</button>";}
    foreach(glob($dir.'*', GLOB_ONLYDIR) as $folder){
      echo"<button class='folder' value='".$folder."'><i class='far fa-folder fa-3x'></i><br>";
      echo basename($folder)."</button>"; 
      $j++;
    }
    echo "</div>";
?>

    <h3>List of files</h3> 
    <table id='filelist' border=1 width=100% id="tb" align="left">
    <tr style="display:none;"><td colspan=2 ></td></tr>
    
    <?php 
 
    //顯示資料夾內的圖片檔
    $i=0;
    foreach(glob($dir."{*.jpg,*.gif,*.png,*.jpeg}", GLOB_BRACE) as $file){ 
             echo "<tr id='row".$i."'><td><a href='".$file."' target='_blank'><img src='".$file."' width=200 height=200></a></td><td><a href='javascript:void(0);' id='delete' rmid='row".$i."' filename='".$file."'>Delete</a></td></tr>";
             $i++;    
}
?>
   </table>



