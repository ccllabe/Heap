<?php
  header("Content-Type: text/html; charset=utf-8");
  set_time_limit(0);
  session_start();
  $project = $_GET["project"];
  #$project_folder_rootpath = "/user/".$_SESSION['user_id']."/".$project."/";
  $project_folder_rootpath = "./user/".$_SESSION['user_id']."/".$project."/";
  #$project_folder_path = $_SERVER['DOCUMENT_ROOT'].$project_folder_rootpath;
  $project_folder_path = $project_folder_rootpath;

  //list all parasite egg place(read description.json, place.txt)
  $tool_array = array("multi_fast_rcnn","multi_fast_rcnn_2","ssd_fordel","u_net");
  $lab_fds_info = [];
  $lab_folder_path = $project_folder_path."Lab/";
  $lab_fds =  array_map('basename', glob($lab_folder_path."*", GLOB_ONLYDIR));
  for($i=0;$i<count($lab_fds);$i++)
  {
	$finish_json_path = $lab_folder_path.$lab_fds[$i]."/running.json";
	if(is_file($finish_json_path)){
		//get lab Info
		$lab_description = file_get_contents($lab_folder_path.$lab_fds[$i]."/description.json");
		$lab_description = json_decode($lab_description);
		$lab_fds_info[$i][0] = $lab_description->{'lab name'};
    $lab_fds_info[$i][1] = array_search($lab_description->{'Tool'},$tool_array);
	}
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
	listview_init();
	function listview_init(){
		var listview_ini_string = "<div id = 'sub_menu' style='position:absolute;left:0%;top:0%;height:100%;overflow:auto;'></div>\
		<div id = 'showpic' style='position:absolute;top:5%;height:95%;'></div>";
		main_content_page.innerHTML = listview_ini_string;
	}
	var htm_string = "";
	var project = <?php echo $project;?>;
	var labfolder = {attr:0};
	var cof_compare = {attr:49};
  var ch_tool = {attr:0};
  var d_tool = ["Faster R-CNN (default)","Faster R-CNN (high quality image)","ssd300","u_net"];
  var cof_compare2 = {"Faster R-CNN (default)":49,"Faster R-CNN (high quality image)":49,"ssd300":70,"u_net":49};
	var showcheck = {attr:1};
	var lab_fds_info = <?php echo json_encode($lab_fds_info) ?>;
	var lab_fds = <?php echo json_encode($lab_fds) ?>;
	//sub_menu
	var submenu_width = {attr:0};
	var submenu_content = {attr:''};
	var menu_user_project_img_num;
	var menu_user_project_lab_num;
	var menu_count = 0;
  var color_array = ['#8B0000','#FF8C00','#FFFF00','#6B8E23','#008080','#483D8B','#4B0082','#C71585',
                     '#FF0000','#FFA500','#BDB76B','#556B2F','#008B8B','#6A5ACD','#800080','#DB7093',
                     '#B22222','#FFD700','#F0E68C','#808000','#5F9EA0','#7B68EE','#8B008B','#FF1493',
                     '#DC143C','#FF4500','#EEE8AA','#2E8B57','#20B2AA','#191970','#9932CC','#FF69B4',
                     '#CD5C5C','#FF6347','#FFDAB9','#3CB371','#00CED1','#000080','#9400D3','#FFB6C1',
                     '#F08080','#FF7F50','#FFE4B5','#8FBC8F','#48D1CC','#00008B','#8A2BE2','#FFC0CB'];
	if(lab_fds==null){
		menu_user_project_lab_num=0;
	}
	else{
		menu_user_project_lab_num=lab_fds.length;
		labfolder.attr = lab_fds[0];
	}
	submenu_out();
	function submenu_out(){
		submenu_content.attr="<p><a onclick='submenu_in();'>&nbsp;⇦&nbsp;</a></p>";
		submenu_content.attr+="<div id = 'submenu_div1'><font>Select parameter:</font></div><div style='position:relative;padding-left:5px;font-size:14px;margin-bottom:-15px;'>List view</div><br><br>\
		<fieldset>Description:\n<br>\
		<li style='padding-left:20px;text-indent:-18px;'>Use left menu to select experimental test results.</li>\
		<li style='padding-left:20px;text-indent:-18px;'>Use the left mouse button and right mouse button to confirm and delete the result.</li>\
		<li style='padding-left:20px;text-indent:-18px;'>View your own confirmation results.</li></fieldset><ul>\
		<form action='list_view_interface.php?project="+project+"' method='post' id='list_view_form_"+project+"' name='list_view_form_"+project+"'>\
		<div style='position:relative;padding-left:5px;font-size:14px;margin-bottom:-15px;'>Confidence</div><br>";
    for(var u_conf in cof_compare2){
      submenu_content.attr+="<p style='text-align:left;padding-left:5px;'>"+u_conf+"</p>";
      submenu_content.attr+="<li><input type = 'range' id = 'rangeinput2_"+project+"_"+u_conf+"' min = '0' max = '100' step = '1' value = '"+cof_compare2[u_conf]+"' onclick='click_list_confidence()'>"+cof_compare2[u_conf]+"</li>";
    }
    //"<li><input type = 'range' id = 'rangeinput2_"+project+"' min = '0' max = '100' step = '1' value = '"+cof_compare.attr+"' onclick='click_list_confidence()'>"+cof_compare.attr+"</li>";
		submenu_content.attr+="<div style='position:relative;padding-left:5px;font-size:14px;margin-bottom:-15px;'>Lab:</div><br>";
		for(var j=0;j<menu_user_project_lab_num;j++){
			if(lab_fds[j]!=labfolder.attr){
				submenu_content.attr+="<li style='background-color:"+color_array[j]+";'><input name = 'listfchoose' type = 'radio' value = '"+j+"' onclick='click_listf_choose()'><label>"+lab_fds_info[j][0]+"</label></li>";
			}else{
				submenu_content.attr+="<li style='background-color:"+color_array[j]+";'><input name = 'listfchoose' type = 'radio' value = '"+j+"' onclick='click_listf_choose()' checked='checked'><label>"+lab_fds_info[j][0]+"</label></li>";
			}
		}
		submenu_content.attr+="</form></ul>";
		submenu_width.attr = 340;
		document.getElementById("sub_menu").style.width = submenu_width.attr+"px";
		document.getElementById("sub_menu").innerHTML = submenu_content.attr;
		menu_count = submenu_width.attr+10;
		document.getElementById("showpic").style.left = menu_count+"px";
	}
	function submenu_in(){
		submenu_content.attr="<p><a onclick='submenu_out();'>&nbsp;⇨&nbsp;</a></p>\
		<div style='transform: translate(-90px, 120px) rotate(270deg);width:200px;color:#aaa;font-size:12px;font-style:italic;font-weight:700;'>\
		<a onclick='submenu_out()'>Step3. Select parameter</a></div>";
		submenu_width.attr = 20;
		document.getElementById("sub_menu").style.width = submenu_width.attr+"px";
		document.getElementById("sub_menu").innerHTML = submenu_content.attr;
		menu_count = submenu_width.attr+10;
		document.getElementById("showpic").style.left = menu_count+"px";
	}
	click_listf_choose();
	//show ini (button)
	function div_ini_show(){
		htm_string = "<div id='title_n' style='position:relative;top:-30px;left:-20px'>List view</div>\
    <input type='button' value='All' onclick='check_show(1);'></input>\
		<input type='button' value='Undetermined' onclick='check_show(2);'></input>\
		<input type='button' value='Confirmed' onclick='check_show(3);'></input>\
		<input type='button' value='Removed' onclick='check_show(4);'></input><br><br>\
    <div id= 'list_view_load' class = 'loader' style = 'z-index:9999;position:absolute;top:40%;left:40%;'></div>\
    <iframe id='list_view_allshow_page' width='1000px' height='800px' frameborder='no'></iframe>";
    showpic.innerHTML = htm_string;
	}
	//confidence click
	function click_list_confidence()
	{
    for(var u_conf in cof_compare2){
      var rangeInput = document.getElementById("rangeinput2_"+project+"_"+u_conf).value;
      cof_compare2[u_conf] = parseInt(rangeInput);
    }
		//var rangeInput = document.getElementById("rangeinput2_"+project).value;
		//alert(rangeInput);
		//cof_compare.attr = parseInt(rangeInput);
		submenu_out();
		cut_to_show();
	}
	//menu_interface click thing
	function click_listf_choose(){
		var form_name = document.getElementById("list_view_form_"+project);
		if (form_name.listfchoose != undefined)
		{
			labfolder.attr = lab_fds[form_name.listfchoose.value];
      ch_tool.attr = lab_fds_info[form_name.listfchoose.value][1];
			//alert(labfolder.attr);
			cut_to_show();
		}
	}
	//button choose show part
	function check_show(check){
		showcheck.attr = check;
		cut_to_show();
	}
	//inform list_view_exe.php to check if cut and cut
	function cut_to_show(){
    div_ini_show();
		var ajax_string = "list_view_exe.php";
		ajax_string += "?project="+project;
		ajax_string += "&lab="+labfolder.attr;
		var request = new XMLHttpRequest();
		request.open("GET", ajax_string);
		request.send();
		request.onreadystatechange = function() {
			if (request.readyState === 4) {
				if (request.status === 200) {
					var type = request.getResponseHeader("Content-Type");
					if (type.indexOf("application/json") === 0) {
						var data = JSON.parse(request.responseText);
						img_table_show(data);
					}
				} else {
					alert("發生錯誤: " + request.status);
				}
			}
		}
	}
	//inform list_view_allshow.php to show img
	function img_table_show(data){

		if(data.output=="ok\n"){
			//htm_string+="<iframe src='list_view_allshow.php?project="+project+"&lab="+labfolder.attr+"&cof="+cof_compare.attr+"&show_check="+showcheck.attr+"' width='1000px' height='800px' frameborder='no'></iframe>";
      var frame = document.getElementById("list_view_allshow_page");
      for(var u_conf in cof_compare2){
        if(d_tool[ch_tool.attr]==u_conf){
          var conf_v = cof_compare2[u_conf];
        }
      }
      frame.src = "list_view_allshow.php?project="+project+"&lab="+labfolder.attr+"&cof="+conf_v+"&show_check="+showcheck.attr;
      document.getElementById("list_view_load").style.display = "none";
			//alert(labfolder.attr);
		}else{
			alert(labfolder.attr);
		}
	}
  </script>
</body>
</html>
