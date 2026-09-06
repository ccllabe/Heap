<?php
	header("Content-Type: text/html; charset=utf-8");
	session_start();
    #<!DOCTYPE html>
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset = "utf-8">
	<title>HEAP</title>
	<link rel='stylesheet' href='menu_3_interface.css' type='text/css'>
</head>
<body>
	<?php
	include 'menu_2_interface.php';
	?>
	<script type="text/javascript">
	var main_content = {attr:''};
	var project = <?php echo $_GET['project']?>;
	ini_content();
	function ini_content(){
		upload_form_content();
		choose_upload_way(1);
	}
	function upload_form_content(){
		main_content.attr="<div id='title_n'>Upload img</div>\
		<table>\
	  <tr>\
	  <td valign='top'>\
	  <h1 style='position:relative;left:20px;top:20px;'>Form</h1>\
		<div style='position:relative;left:20px;top:20px;'>\
		<form method='POST' action='img_upload_exe.php' enctype='multipart/form-data'  name= 'img_upload_form'>\
			<p><label>Image name:</br>\
			<input name = 'img_name' type = 'text' size = '30' style='width:315px;' required>\
			</label></p>\
			<p><label>Research topic:</br>\
			<input name = 'research_topic' type = 'text' size = '30' style='width:315px;'>\
			</label></p>\
			<p><label>Research institute:</br>\
			<input name = 'research_institute' type = 'text' size = '30' style='width:315px;'>\
			</label></p>\
			<p><label>E-mail:</br>\
			<input name = 'e_mail' type = 'text' size = '30' style='width:315px;'>\
			</label></p>\
			<p><label>Image description: </br>\
			<textarea name='img_description' style='width:315px;height:100px;'></textarea>\
			</label></p>\
			<label>Upload way:</label>\
			<table>\
			<tr id='upload_way_tr'>\
			<td><div id='upload_way_1' onclick='choose_upload_way(1)' style='font-size:14px;'>Localhost</div></td>\
			<td><div id='upload_way_2' onclick='choose_upload_way(2)' style='font-size:14px;'>Google drive</div></td>\
			<td><div id='upload_way_3' onclick='choose_upload_way(3)' style='font-size:14px;'>Onedrive</div></td>\
			<td><div id='upload_way_4' onclick='choose_upload_way(4)' style='font-size:14px;'>Dropbox</div></td>\
			<tr>\
			</table></br>\
			<div id='upload_way_show'></div>\
			<input type ='hidden' name = 'project' value = '"+project+"' required>\
			<input type='hidden' name='action' value='img_upload'>\
			<br><br>\
			<input type='submit' name='button' value='Submit'>\
		</form>\
		</div>\
		</td><td valign='top' style='position:relative;padding-left:20px;top:20px;'>\
	  <table><tr>\
	  <td><h1 style='padding-left:20px;'>Instruction</h1></td>\
	  <td style='position:relative;text-align:right;'><a href ='tutorial_interface.php'  target='_blank' ><button style=''>More information</button></a></td>\
	  </tr><tr>\
	  <td colspan='2'><img src='./web_data/tutorial_3.png' width='1100px' height='550px' style='position:relative;padding-left:20px;padding-top:0px;'></td>\
	  </tr><tr>\
	  <td colspan='2'><p style='position:relative;padding-left:20px;padding-right:55px;'>After creating the project, you can see the functions of uploading, testing, confirming, and downloading the project in the function menu.<br>\
		The first step is to upload the image of the target slide.<br>\
		After selecting the item, enter this page by default, or click \"Upload img\" in the function menu <font style='color:red;'>(a)</font>.<br>\
		Fill in the image information and upload the image <font style='color:red;'>(b)</font>.<br>\
		There are four upload methods, including local, Google Drive, OneDrive and Dropbox <font style='color:red;'>(b-1)</font>.</p></td>\
	  </tr></table>\
	  </td>\
	  </tr>\
	  </table>";
		document.getElementById("main_content_page").innerHTML = main_content.attr;
	}
	function choose_upload_way(way_n){
		var str;
		switch(way_n){
			case 1:
				str="<label>Localhost:</label></br>\
				<input type='file' name='upload_img' required>\
				<input type='hidden' name='upload_way' value='1'>";
				break;
			case 2:
				str="<label>Google drive:</label></br>\
				<input name='upload_img' type = 'text' size = '30' style='width:315px;' required>\
				<input type='hidden' name='upload_way' value='2'>";
				break;
			case 3:
				str="<label>Onedrive:</label></br>\
				<input name='upload_img' type = 'text' size = '30' style='width:315px;' required>\
				<input type='hidden' name='upload_way' value='3'>";
				break;
			default:
				str="<label>Dropbox:</label></br>\
				<input name='upload_img' type = 'text' size = '30' style='width:315px;' required>\
				<input type='hidden' name='upload_way' value='4'>";
		}
		for(var i=1;i<5;i++){
			if(i!=way_n){
				document.getElementById("upload_way_"+i).style.backgroundColor ="#FFFFFF";
				document.getElementById("upload_way_"+i).style.border ="2px solid #1C2833";
				document.getElementById("upload_way_"+i).style.color ="black";
			}else{
				document.getElementById("upload_way_"+i).style.backgroundColor ="#1C2833";
				document.getElementById("upload_way_"+i).style.border ="2px solid #1C2833";
				document.getElementById("upload_way_"+i).style.color ="white";
			}
		}
		document.getElementById("upload_way_show").innerHTML = str;
	}
	</script>
</body>
</html>
