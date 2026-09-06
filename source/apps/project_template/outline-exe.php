<?php

error_reporting(E_ALL^E_NOTICE^E_WARNING);
session_start();
$_SESSION['chapter']=$_POST['chapter'];
if (isset($_POST['action'])){
    $chaptername=$_POST['chaptername'];
    $dirname=$_POST['dirname'];
    $inp=file_get_contents("{$_SESSION['course']}/outline.json");
if ($_POST['action']=='save'){
    if ($inp!= NULL){
    $data= json_decode($inp,true);//當該參數為 TRUE 時，將返回 array 而非 object 
    }
    else{
    $data=array();
    }
    array_push($data,array("chaptername"=>$chaptername,"dirname"=>$dirname));
    //print_r($data);
    $jsonData = json_encode($data, JSON_UNESCAPED_UNICODE);
    file_put_contents("{$_SESSION['course']}/outline.json", $jsonData); 
    chmod("{$_SESSION['course']}/outline.json",0777);  
    //建立資料夾
    $file_path = "{$_SESSION['course']}/$dirname";
    if(!file_exists($file_path)){
    mkdir($file_path,0777,true);
    mkdir("{$_SESSION['course']}_tmp/$dirname",0777,true); //ITM001_tmp/Chapterxx 上傳圖片的目的資料夾
    echo "$dirname 建立成功";
    }else{
    echo "$dirname 資料夾已存在";
    }
}
if ($_POST['action']=='del'){
    $del="{$_SESSION['course']}/{$_POST['del']}/";
    $data= json_decode($inp,true);
    foreach($data as $key => $each)
    {
        if ($each['dirname']==$_POST['del'])
        {
            unset ($data[$key]);    
        }
    }
    $jsonData = json_encode($data, JSON_UNESCAPED_UNICODE);
    file_put_contents("{$_SESSION['course']}/outline.json", $jsonData); 
    delete_files($del);
    
}
}

function delete_files($target) {
    if(is_dir($target)){
        $files = glob( $target . '*', GLOB_MARK ); //若檔案為資料夾，在回傳檔案路徑的最後面加上斜線"\"

        foreach( $files as $file ){
            delete_files( $file );      
        }
        rmdir( $target );
        echo"刪除成功";
    } elseif(is_file($target)) {
        unlink( $target );  

    }
}

?>