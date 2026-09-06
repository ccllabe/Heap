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
		choose_upload_way(1,1);
		choose_upload_way(2,1);
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
			<h6>Original image</h6>\
			<label>Upload way:</label>\
			<table>\
			<tr id='upload_way1_tr'>\
			<td><div id='upload_way1_1' onclick='choose_upload_way(1,1)' style='font-size:14px;'>Localhost</div></td>\
			<td><div id='upload_way1_2' onclick='choose_upload_way(1,2)' style='font-size:14px;'>Google drive</div></td>\
			<td><div id='upload_way1_3' onclick='choose_upload_way(1,3)' style='font-size:14px;'>Onedrive</div></td>\
			<td><div id='upload_way1_4' onclick='choose_upload_way(1,4)' style='font-size:14px;'>Dropbox</div></td>\
			</tr>\
			</table></br>\
			<div id='upload_way_show1'></div><br><br>\
			<h6>Scale bar image</h6>\
			<label>Upload way:</label>\
			<table>\
			<tr id='upload_way2_tr'>\
			<td><div id='upload_way2_1' onclick='choose_upload_way(2,1)' style='font-size:14px;'>Localhost</div></td>\
			<td><div id='upload_way2_2' onclick='choose_upload_way(2,2)' style='font-size:14px;'>Google drive</div></td>\
			<td><div id='upload_way2_3' onclick='choose_upload_way(2,3)' style='font-size:14px;'>Onedrive</div></td>\
			<td><div id='upload_way2_4' onclick='choose_upload_way(2,4)' style='font-size:14px;'>Dropbox</div></td>\
			</tr>\
			</table></br>\
			<div id='upload_way_show2'></div>\
			<input type='hidden' name = 'project' value = '"+project+"' required>\
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
		There are four upload methods, including local, Google Drive, OneDrive and Dropbox <font style='color:red;'>(b-1)(b-2)</font>.</p></td>\
	  </tr><tr>\
	  <td><h1 style='padding-left:20px;'>How to get the image of the scale bar?</h1></td>\
	  </tr><tr>\
	  <td colspan='2'><img src='./web_data/tutorial_3_scalebar.png' width='1100px' height='550px' style='position:relative;padding-left:20px;padding-top:0px;'></td>\
	  </tr><tr>\
	  <td colspan='2'><p style='position:relative;padding-left:20px;padding-right:55px;'>The user extracts an image of scale-related information from the original scanned image of the slide <font style='color:red;'>(a)</font>.<br>\
		After uploading the image <font style='color:red;'>(b)</font>, the scale information will be displayed at the bottom of the original image on the “Map view” page to provide users with information about the size of the eggs <font style='color:red;'>(c)</font>.<br>\
		The menu can control whether the information is displayed <font style='color:red;'>(c-1)</font>.</td>\
	  </tr>\
		</table>\
	  </td>\
	  </tr>\
	  </table>";
		document.getElementById("main_content_page").innerHTML = main_content.attr;
	}
	function choose_upload_way(img_n, way_n){
		var str;
		switch(way_n){
			case 1:
				str="<label>Localhost:</label></br>\
				<input type='file' name='upload_img"+img_n+"' required>\
				<input type='hidden' name='upload_way"+img_n+"' value='1'>";
				break;
			case 2:
				str="<label>Google drive:</label></br>\
				<input name='upload_img"+img_n+"' type = 'text' size = '30' style='width:315px;' required>\
				<input type='hidden' name='upload_way"+img_n+"' value='2'>";
				break;
			case 3:
				str="<label>Onedrive:</label></br>\
				<input name='upload_img"+img_n+"' type = 'text' size = '30' style='width:315px;' required>\
				<input type='hidden' name='upload_way"+img_n+"' value='3'>";
				break;
			default:
				str="<label>Dropbox:</label></br>\
				<input name='upload_img"+img_n+"' type = 'text' size = '30' style='width:315px;' required>\
				<input type='hidden' name='upload_way"+img_n+"' value='4'>";
		}
		for(var i=1;i<5;i++){
			if(i!=way_n){
				document.getElementById("upload_way"+img_n+"_"+i).style.backgroundColor ="#FFFFFF";
				document.getElementById("upload_way"+img_n+"_"+i).style.border ="2px solid #1C2833";
				document.getElementById("upload_way"+img_n+"_"+i).style.color ="black";
			}else{
				document.getElementById("upload_way"+img_n+"_"+i).style.backgroundColor ="#1C2833";
				document.getElementById("upload_way"+img_n+"_"+i).style.border ="2px solid #1C2833";
				document.getElementById("upload_way"+img_n+"_"+i).style.color ="white";
			}
		}
		document.getElementById("upload_way_show"+img_n).innerHTML = str;
	}
	</script>
</body>
</html>
