<?php
		$uname = $_POST['uname'];
		$upass =  $_POST['upass'];
		console.log($uname);

		/*
		define('UPLOAD_DIR', 'teacher_info/');      //老師名單路徑 
		if(!is_dir(UPLOAD_DIR . $uname)){
			echo "無此老師資料！";
			exit;
		}
		*/
        //:q$json_string = file_get_contents("list.json");
        //$data = json_decode($json_string,true);
		$list = array($uname => $upass);
		//array_push($list, array('uname' => $uname, 'upass' => $upass));
		//array_push($list, array('uname' => $uname, 'upass' => $upass));

		// 把PHP陣列轉成JSON字串
		$json_string = json_encode($list);
		// 寫入檔案
        file_put_contents('list.json', $json_string);
        
        


	

	?>