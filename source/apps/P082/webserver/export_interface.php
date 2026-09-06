<?php
  header("Content-Type: text/html; charset=utf-8");
  session_start();
  $project = $_GET["project"];
  #$project_folder_rootpath = "/user/".$_SESSION['user_id']."/".$project."/";
  $project_folder_rootpath = "./user/".$_SESSION['user_id']."/".$project."/";
  #$project_folder_path = $_SERVER['DOCUMENT_ROOT'].$project_folder_rootpath;
  $project_folder_path = $project_folder_rootpath;

  //list all images(img_info, ori_img_size, cut 100_100_imgs)
  $img_fds_info = [];
  $img_folder_path = $project_folder_path."Image/";
  $img_fds =  array_map('basename', glob($img_folder_path."*", GLOB_ONLYDIR));
  for($i=0;$i<count($img_fds);$i++)
  {
    //get img Info
    $img_description = file_get_contents($img_folder_path.$img_fds[$i]."/description.json");
    $img_description = json_decode($img_description);
    $img_fds_info[$i] = $img_description->{'image name'};
  }
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset = "utf-8">
	<title>HEAP</title>
</head>

<body>
  <!--menu3-->
  <link rel="stylesheet" href="menu_3_interface.css" type="text/css">
  <?php
	include 'menu_2_interface.php';
	?>
	<script type="text/javascript">
	export_init();
	function export_init(){
		var export_ini_string = "<div id = 'sub_menu' style='position:absolute;left:0%;top:0%;height:100%;'></div>\
		<div id = 'showcontent' style='position:absolute;top:5%;height:100%;'></div>";
		main_content_page.innerHTML = export_ini_string;
	}
	var htm_string = "";
	var imgfolder = {attr:''};
	var project = <?php echo $project;?>;
	var img_fds = <?php echo json_encode($img_fds) ?>;
	var img_fds_info = <?php echo json_encode($img_fds_info) ?>;
	//sub_menu
	var submenu_width = {attr:0};
	var submenu_content = {attr:''};
	var menu_user_project_img_num;
	var menu_count = 0;
	submenu_out();
	function submenu_out(){
		submenu_content.attr="<p><a onclick='submenu_in();'>&nbsp;⇦&nbsp;</a></p>";
		submenu_content.attr+="<div id = 'submenu_div1'><font>Select parameter:</font></div><div style='position:relative;padding-left:5px;font-size:14px;margin-bottom:-15px;'>Export</div><br><br>\
		<fieldset>Description:\n<br>\
		<li style='padding-left:20px;text-indent:-18px;'>Use left menu to select Image.</li>\
		<li style='padding-left:20px;text-indent:-18px;'>Download your data which you confirmed.</li>\
		<li style='padding-left:20px;text-indent:-18px;'>Image which you download contains only confirmed egg information.</li></fieldset>\
		<ul><form action='export_interface.php?project="+project+"' method='post' id='export_form_"+project+"' name='export_form_"+project+"'>\
		<div style='position:relative;padding-left:5px;font-size:14px;margin-bottom:-15px;'>Background Image</div><br>";
		if(img_fds==null){
			menu_user_project_img_num=0;
		}
		else{
			menu_user_project_img_num=img_fds.length;
		}
		for(var j=0;j<menu_user_project_img_num;j++){
			if(j!=0){
				submenu_content.attr+="<li><input name = 'img_choose' type = 'radio' value = '"+img_fds[j]+"' onclick='click_img_choose()'><label>"+img_fds_info[j]+"</label></li>";
			}
			else{
				submenu_content.attr+="<li><input name = 'img_choose' type = 'radio' value = '"+img_fds[j]+"' onclick='click_img_choose()' checked='checked'><label>"+img_fds_info[j]+"</label></li>";
			}
		}
		submenu_content.attr+="</form>";
		submenu_content.attr+="<div style='position:relative;padding-left:5px;'><input type='button' value='Submit' onclick='click_runtozip();'></input></div></ul></li>";
		submenu_content.attr+="</ul>";
		submenu_width.attr = 340;
		document.getElementById("sub_menu").style.width = submenu_width.attr+"px";
		document.getElementById("sub_menu").innerHTML = submenu_content.attr;
		document.getElementById("showcontent").style.left = submenu_width.attr+"px";
	}
	function submenu_in(){
		submenu_content.attr="<p><a onclick='submenu_out();'>&nbsp;⇨&nbsp;</a></p>\
		<div style='transform: translate(-90px, 120px) rotate(270deg);width:200px;color:#aaa;font-size:12px;font-style:italic;font-weight:700;'>\
		<a onclick='submenu_out()'>Step3. Select parameter</a></div>";
		submenu_width.attr = 20;
		document.getElementById("sub_menu").style.width = submenu_width.attr+"px";
		document.getElementById("sub_menu").innerHTML = submenu_content.attr;
		document.getElementById("showcontent").style.left = submenu_width.attr+"px";
	}
	div_ini_show();
	function div_ini_show(){
		//<a href="download.php?download='.$row['file'].'" title="Download File">
		htm_string = "<div id='title_n' style='position:relative;top:-30px;left:-20px'>Export</div>\
    <div><img id='download_show_img' src='./web_data/download_2_all.gif' width='900px' height='500px' style='padding-left:5px;'></div>\
    <div id='download_condition'></div>";
		showcontent.innerHTML = htm_string;
    click_img_choose();
	}
	function click_img_choose()
	{
		var form_name = document.getElementById("export_form_"+project);
		if (form_name.img_choose.value != undefined)
		{
			imgfolder.attr = form_name.img_choose.value;
		}
	}
	function click_runtozip(){
		//htm_string += "圖片處理中...";
		//showcontent.innerHTML = htm_string;
    var d_p_show = document.getElementById("download_part");
    if(d_p_show != undefined){
      d_p_show.style.display = "none";
    }
    var d_show = document.getElementById("download_show_img");
    d_show.src = "./web_data/download_2_all.gif";
    download_condition.innerHTML = "<h1 style='padding-left:5px;'>Image Processing...<br>Please wait...</h1>";
		//inform export_exe handle
		var ajax_string = "export_exe.php";
		ajax_string += "?project="+project;
		ajax_string += "&img_fd="+imgfolder.attr;
		var request = new XMLHttpRequest();
		request.open("GET", ajax_string);
		request.send();
		request.onreadystatechange = function() {
			if (request.readyState === 4) {
				if (request.status === 200) {
					var type = request.getResponseHeader("Content-Type");
					if (type.indexOf("application/json") === 0) {
						var data = JSON.parse(request.responseText);
						htm_change(data["output"]);
						//alert(data["output"]);
					}
				} else {
					alert("發生錯誤: " + request.status);
				}
			}
		}
	}
	function htm_change(download){
		//C:/xampp/htdocs/user/tiffany/1559029302/Result/1565850057
    htm_string = "<div id='title_n' style='position:relative;top:-30px;left:-20px'>Export</div>\
    <div id='download_part' style='padding-left:5px;font-size:20px;font-weight:700;'><a style='background:#888;color:black;border: 2px solid;border-radius:8px;border-color:black;' href='export_download.php?project="+project+"&download="+download+"' title='Download File'>Download</a></div></br>\
    <div><img id='download_show_img' src='./web_data/download_2_all.gif' width='900px' height='500px' style='padding-left:5px;'></div>\
    <div id='download_condition'></div>";
		//alert(htm_string);
		showcontent.innerHTML = htm_string;
	}
	/*function click_download(){
		var ajax_string = "export_exe.php";
		ajax_string += "?project="+project;
		ajax_string += "&img_fd="+imgfolder.attr;
		var request = new XMLHttpRequest();
		request.open("GET", ajax_string);
		request.send();
		request.onreadystatechange = function() {
			if (request.readyState === 4) {
				if (request.status === 200) {
					var type = request.getResponseHeader("Content-Type");
					if (type.indexOf("application/json") === 0) {
						var data = JSON.parse(request.responseText);
						alert(data["output"]);
					}
				} else {
					alert("發生錯誤: " + request.status);
				}
			}
		}
	}*/
  </script>
</body>
</html>
