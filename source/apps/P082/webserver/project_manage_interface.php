<?php
  header("Content-Type: text/html; charset=utf-8");
  session_start();
  #$user_folder_path = $_SERVER['DOCUMENT_ROOT']."/user/".$_SESSION['user_id']."/";
  $user_folder_path = "./user/".$_SESSION['user_id']."/";
  $user_projects =  array_map('basename', glob($user_folder_path."*", GLOB_ONLYDIR));
  //project name and project modify time
  for($i=0;$i<count($user_projects);$i++){
    $project_description = file_get_contents($user_folder_path.$user_projects[$i]."/description.json");
    $project_description = json_decode($project_description);
    $modify_time = date("Y-m-d H:i:s",filemtime($user_folder_path.$user_projects[$i]."/"));
    $user_project_info[$i][0] = $user_projects[$i];
    $user_project_info[$i][1] = $project_description->{'project name'};
    $user_project_info[$i][2] = $modify_time;
    $user_project_info[$i][3] = $project_description->{'project description'};
  }
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset = "utf-8">
  <!-- jQuery v1.9.1 -->
  <script src="https://code.jquery.com/jquery-1.9.1.min.js"></script>
  <!-- DataTables v1.10.16 -->
  <link href="https://cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css" rel="stylesheet" />
  <script src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
  <title>HEAP</title>
</head>
<body>
  <?php
  include 'menu_interface.php';
  ?>
	<script type="text/javascript">
    var main_content = {attr:''};
    //get data
    var user_project_info = <?php echo json_encode($user_project_info) ?>;
    //write project table
    main_content.attr="<div style='font-size:24px;text-align:left;padding-left:0px;font-style:italic;font-weight:550;'>Project management</div>\
    <div style='position:relative;top:20px;'>\
    <table id= 'project_manage' border = '1'>";
    //write the first raw
    main_content.attr+="<thead style='background-color:#555555;font-size:14px;font-weight:700;font-style:italic;color:#cccccc;'><tr><th>Name</th><th>Edit time</th><th>Description</th><th>Action</th></tr></thead><tbody>";
    //write all data
    for(var i=0;i<user_project_info.length;i++)
  	{
      main_content.attr+="<tr><td><a href='img_upload_interface.php?project="+user_project_info[i][0]+
      "'>"+user_project_info[i][1]+"</a></td><td>"+
      user_project_info[i][2]+"</td><td>"+
      user_project_info[i][3]+"</td><td><button type='button' onclick='delete_proj("+user_project_info[i][0]+")'>Delete</button></td></tr>";
  	}
    main_content.attr+="</tbody></table></div>";
    document.getElementById("main_content1_page").innerHTML = main_content.attr;
    $('#project_manage').dataTable({
      "sPaginationType":"full_numbers",
      "bPaginate":true,
      "oLanguage": {
        "sLengthMenu": "Show _MENU_ record",
        "sZeroRecords": "Co accord data",
        "sInfo": "Current record：_START_ to _END_, Sum：_TOTAL_"
      }
    });
    //ajax
    function delete_proj(project){
      //alert(project);
      var ajax_string = "project_delete_exe.php";
      ajax_string += "?project="+project;
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
