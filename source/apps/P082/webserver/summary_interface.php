<?php
  header("Content-Type: text/html; charset=utf-8");
  session_start();
  $user_project = $_GET["project"];
  #$user_folder_path = $_SERVER['DOCUMENT_ROOT']."/user/".$_SESSION['user_id']."/";
  $user_folder_path = "./user/".$_SESSION['user_id']."/";
  //project description
  $project_description = file_get_contents($user_folder_path.$user_project."/description.json");
  $project_description = json_decode($project_description);
  $modify_time = date("Y-m-d H:i:s",filemtime($user_folder_path.$user_project."/"));
  $user_project_info[0] = $user_project;
  $user_project_info[1] = $project_description->{'project name'};
  $user_project_info[2] = $modify_time;
  $user_project_info[3] = $project_description->{'project description'};
  //img description
  $project_img_path = $user_folder_path.$user_project."/Image/";
  $project_imgs =  array_map('basename', glob($project_img_path."*", GLOB_ONLYDIR));
  $project_img_info=null;
  for($j=0;$j<count($project_imgs);$j++){
    $modify_time = date("Ymd",filemtime($project_img_path.$project_imgs[$j]."/"));
    $img_description = file_get_contents($project_img_path.$project_imgs[$j]."/description.json");
    $img_description = json_decode($img_description);
    $project_img_info[$j][0] = $project_imgs[$j];
    $project_img_info[$j][1] = $img_description->{'image name'};
    $project_img_info[$j][2] = $modify_time;
    $project_img_info[$j][3] = $img_description->{'image description'};
    $project_img_info[$j][4] = $project_img_path.$project_imgs[$j]."/thumbnail.png";
  }
  //lab description
  $project_lab_path = $user_folder_path.$user_project."/Lab/";
  $project_labs =  array_map('basename', glob($project_lab_path."*", GLOB_ONLYDIR));
  $project_lab_info=null;
  for($j=0;$j<count($project_labs);$j++){
    $modify_time = date("Ymd",filemtime($project_lab_path.$project_labs[$j]."/"));
    $lab_description = file_get_contents($project_lab_path.$project_labs[$j]."/description.json");
    $lab_description = json_decode($lab_description);
    $project_lab_info[$j][0] = $project_labs[$j];
    $project_lab_info[$j][1] = $lab_description->{'lab name'};
    $project_lab_info[$j][2] = $lab_description->{'lab description'};
    $project_lab_info[$j][3] = $modify_time;
  }
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset = "utf-8">
	<title>HEAP</title>
  <!-- jQuery v1.9.1 -->
  <script src="https://code.jquery.com/jquery-1.9.1.min.js"></script>
  <!-- DataTables v1.10.16 -->
  <link href="https://cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css" rel="stylesheet" />
  <script src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
</head>
<body>
  <?php
	include 'menu_2_interface.php';
	?>
	<script type="text/javascript">
    summary_init();
    function summary_init(){
		var summary_ini_string = "<div style='position:absolute;left:5%;'>\
    <div id='title_n'>Summary</div>\
		<div style = '' id = 'showform'></div></div>";
		main_content_page.innerHTML = summary_ini_string;
	}
	//project information
	var project = <?php echo $user_project ?>;
	var user_project_info = <?php echo json_encode($user_project_info) ?>;
	var project_img_info = <?php echo json_encode($project_img_info) ?>;
	var project_lab_info = <?php echo json_encode($project_lab_info) ?>;
	//var user_folder_path = <?php echo $user_folder_path ?>;
	var htm_string = "";
	summary_page();
	function summary_page(){
		//project introduction
		//htm_string = "<table><tr><td colspan='3'></td></tr>";
		htm_string = "<div style='position:relative;top:20px;left:20px;width:100%;height:100%;'>\
		<table style='width:100%;border:3px #cccccc solid;border-spacing:0;border-radius:10px;'>\
		<tr style=''>\
		<th style='border:1px solid #000;background-color:#444;border-top-left-radius:8px;border-top-right-radius:8px;'>\
		<label style='font-size:16px;font-weight:700;font-style:italic;color:#cccccc;margin-left:10px;'>Project detection result</label>\
		</th>\
		</tr>\
		<tr>\
		<td style='border:1px solid #000;border-bottom-left-radius:8px;border-bottom-right-radius:8px;'>\
		<iframe src='summary_dect_result.php?project="+project+"' width=1100px height=300px scrolling='no' frameborder='no'></iframe>\
		<br><br>\
		</td>\
		</tr>\
		</table><br><br>";
		htm_string += "<table style='width:100%;height:100%;border-spacing:0;'>\
		<!--<tr>\
		<th colspan='3' style='border:1px solid #000;background-color:#444;'>\
		<label style='font-size:16px;font-weight:700;font-style:italic;color:#cccccc;margin-left:10px;'>Project information</label>\
		</th>\
		</tr>-->";
		//project
		htm_string += "<tr>\
		<td colspan='1' style='vertical-align:top;'>\
		<table style='width:100%;height:100%;border:3px #cccccc solid;border-spacing:0;border-radius:10px;'>\
		<tr>\
		<th style='border:1px solid #000;background-color:#444;height:20px;border-top-left-radius:8px;border-top-right-radius:8px;'>\
		<label style='font-size:16px;font-weight:700;font-style:italic;color:#cccccc;margin-left:10px;'>Project</label>\
		</th>\
		</tr>\
		<tr>\
		<td style='vertical-align:top;position:relative;height:100%;'>\
		<form action='project_update_exe.php' method='post' name= 'project_update_form'>\
		<p><label>Project name:</br><input style='width:300px;' name = 'project_name' type = 'text' size = '30' value = '"+user_project_info[1]+"'></label></p>\
		<p><label>Project description: </br>\
		<textarea name='project_description' style='width:300px;height:100px;'>"+user_project_info[3]+"</textarea>\
		</label></p>\
		<input type='hidden' name='project' value='"+user_project_info[0]+"'>\
		<input type='hidden' name='action' value='project_update'>\
		<input type='submit' name='button' value='Update'>\
		</form>\
		</td>\
		</tr>\
		</table>\
		</td>";
		//image introduction
		htm_string += "<td colspan='1' style='vertical-align:top;'>\
		<table style='width:100%;height:100%;border:3px #cccccc solid;border-spacing:0;border-radius:10px;'>\
		<tr>\
		<th style='border:1px solid #000;background-color:#444;height:20px;border-top-left-radius:8px;border-top-right-radius:8px;'>\
		<label style='font-size:16px;font-weight:700;font-style:italic;color:#cccccc;margin-left:10px;'>Image</label>\
		</th>\
		</tr>\
		<tr>\
		<td style='vertical-align:top;position:relative;height:100%;'>";
		if (project_img_info!=null){
			htm_string += "<table id = 'img_summary' border = '1'>\
			<thead style='background-color:#777;font-size:14px;font-weight:700;font-style:italic;color:#cccccc;'>\
			<tr>\
			<th>Name</th><th>Edit time</th><th>Description</th><th>Action</th>\
			</tr>\
			</thead>\
			<tbody>";
			for(var i=0;i<project_img_info.length;i++){
				htm_string += "<tr><td onmouseover='showthumbnail("+i+",1);' onmouseout='showthumbnail("+i+",0);'>"+
				project_img_info[i][1]+"<div id='thumbnail_"+i+"' style='position: absolute;'></div></td><td>"+
				project_img_info[i][2]+"</td><td>"+
				project_img_info[i][3]+"</td>"+
				"<td><button type='button' onclick='delete_img("+project_img_info[i][0]+")'>Delete</button></td></tr>";
			}
			htm_string += "</tbody></table>";
		}else{
			htm_string += "<p>尚無圖片資料</p>";
		}
		htm_string += "</td></tr></table></td>";
		//lab instruction
		htm_string += "<td colspan='1' style='vertical-align:top;'>\
		<table style='width:100%;height:100%;border:3px #cccccc solid;border-spacing:0;border-radius:10px;'>\
		<tr>\
		<th style='border:1px solid #000;background-color:#444;height:20px;border-top-left-radius:8px;border-top-right-radius:8px;'>\
		<label style='font-size:16px;font-weight:700;font-style:italic;color:#cccccc;margin-left:10px;'>Lab</label>\
		</th>\
		</tr>\
		<tr>\
		<td style='vertical-align:top;position:relative;height:100%;'>";
		if(project_lab_info!=null){
			htm_string += "<table id = 'lab_summary' border = '1'>\
			<thead style='background-color:#777;font-size:14px;font-weight:700;font-style:italic;color:#cccccc;'>\
			<tr>\
			<th>Name</th><th>Edit time</th><th>Description</th><th>Action</th>\
			</tr>\
			</thead>\
			<tbody>";
			for(var i=0;i<project_lab_info.length;i++){
				htm_string += "<tr><td>"+
				project_lab_info[i][1]+"</td><td>"+
				project_lab_info[i][3]+"</td><td>"+
				project_lab_info[i][2]+"</td>"+
				"<td><button type='button' onclick='delete_lab("+project_lab_info[i][0]+")'>Delete</button></td></tr>";
			}
			htm_string += "</tbody></table>";
			}else{
				htm_string += "<p>尚無檢測資料</p>";
			}
		htm_string += "</td></tr></table></td>";
		htm_string += "</tr></table></div>";
		showform.innerHTML = htm_string;
    }
    $('#img_summary').dataTable({
      "columns": [
        null,
        { "width": "30px" },
        null,
        null
      ],
      "lengthMenu": [ [5, 10, 25, 50, -1], [5, 10, 25, 50, "All"] ],
      "sPaginationType":"full_numbers",
      "bPaginate":true,
      "oLanguage": {
        "sLengthMenu": "Show _MENU_ record",
        "sZeroRecords": "Co accord data",
        "sInfo": "Current record：_START_ to _END_, Sum：_TOTAL_"
      }
    });
    $('#lab_summary').dataTable({
      "columns": [
        null,
        { "width": "30px" },
        null,
        null
      ],
      "lengthMenu": [ [5, 10, 25, 50, -1], [5, 10, 25, 50, "All"] ],
      "sPaginationType":"full_numbers",
      "bPaginate":true,
      "oLanguage": {
        "sLengthMenu": "Show _MENU_ record",
        "sZeroRecords": "Co accord data",
        "sInfo": "Current record：_START_ to _END_, Sum：_TOTAL_"
      }
    });
    function showthumbnail(img,act){
      show_place = "thumbnail_"+(img);
      show = document.getElementById(show_place);
      if(act>0){
        show.innerHTML = "<img src='"+project_img_info[img][4]+"'>";
      }else{
        show.innerHTML = '';
      }
    }
    function delete_img(img){
      //alert(project);
      var ajax_string = "img_delete_exe.php";
      ajax_string += "?project="+project;
      ajax_string += "&img="+img;
      var request = new XMLHttpRequest();
      request.open("GET", ajax_string);
      request.send();
      request.onreadystatechange = function() {
        if (request.readyState === 4) {
          if (request.status === 200) {
            var type = request.getResponseHeader("Content-Type");
            if (type.indexOf("application/json") === 0) {
              var data = JSON.parse(request.responseText);
              window.location.reload();
            }
          } else {
            alert("發生錯誤: " + request.status);
          }
        }
      }
    }
    function delete_lab(lab){
      //alert(project);
      var ajax_string = "lab_delete_exe.php";
      ajax_string += "?project="+project;
      ajax_string += "&lab="+lab;
      var request = new XMLHttpRequest();
      request.open("GET", ajax_string);
      request.send();
      request.onreadystatechange = function() {
        if (request.readyState === 4) {
          if (request.status === 200) {
            var type = request.getResponseHeader("Content-Type");
            if (type.indexOf("application/json") === 0) {
              var data = JSON.parse(request.responseText);
              window.location.reload();
            }
          } else {
            alert("發生錯誤: " + request.status);
          }
        }
      }
    }
  </script>
</body>
</html>
