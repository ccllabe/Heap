<?php 
	Session_start();

	// 刪除所有 Session 變量 
	$_SESSION = array(); 
	//判斷 cookie 中是否保存 Session ID 
	if(isset($_COOKIE[session_name()])){ setcookie(session_name(),'',time()-3600, '/'); } 
	//徹底銷毀 Session 
	session_destroy();
	echo "<script>alert('已登出'); document.location.href='../index.php';</script>";
?>