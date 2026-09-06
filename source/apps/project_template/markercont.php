<?php
if(isset($_POST['uid'])&&$_POST['action']=='save'){
	$uid =$_POST['uid'];
	$content=$_POST['content'];
	$file = fopen('./markercontent/'.$uid.'.txt',"w");
	fwrite($file,$content);
	echo $content;
	fclose($file);
}
if(isset($_POST['magni'])){
	session_start();
	$dir="./{$_SESSION['course']}/{$_SESSION['chapter']}/{$_POST['magni']}/";
	foreach(glob($dir.'*', GLOB_ONLYDIR) as $folder){
		$flength[]=basename($folder);
	}
	echo json_encode($flength, JSON_UNESCAPED_UNICODE);

}
?>
