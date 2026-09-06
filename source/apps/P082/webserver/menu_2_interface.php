<?php
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
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset = "utf-8">
	<title>HEAP</title>
</head>
<body>
	<!--menu-->
  <?php include 'menu_interface.php';?>
  <link rel="stylesheet" href="menu_2_interface.css" type="text/css">
  <!--function forms-->
  <script type="text/javascript">
  var project = <?php echo $user_project ?>;
  var user_project_info = <?php echo json_encode($user_project_info) ?>;
  var menu_2_content = {attr:''};
  var menu_count = 0;
  /*menu_2_out();
  function menu_2_out(){
    menu_2_content.attr="<p><a onclick='menu_2_in();'>&nbsp;⇦&nbsp;</a></p>";
    menu_2_content.attr+="<div id = 'menu_2_div1'><font>Step2. Select function:</font></div><div id = 'menu_2_div2'>"+user_project_info[1]+"</div>";
    menu_2_content.attr+="<ul><li onclick='menu_2_click(0);'>Summary</li>\
    <li onclick='menu_2_click(1);'>Upload img</li>\
    <li onclick='menu_2_click(2);'>Detection(Lab)</li>\
    <li onclick='menu_2_click(3);'>Map view</li>\
    <li onclick='menu_2_click(4);'>List view</li>\
    <li onclick='menu_2_click(5);'>Export</li>\
    </ul>";
    document.getElementById("menu_2").innerHTML = menu_2_content.attr;
    menu2_width.attr = 340;
    document.getElementById("menu_2").style.width = menu2_width.attr+"px";
    menu_count = menu1_width.attr+menu2_width.attr;
    document.getElementById("main_content_page").style.left = menu_count+"px";
  }
  function menu_2_in(){
    menu_content.attr="<p><a onclick='menu_2_out();'>&nbsp;⇨&nbsp;</a></p>\
    <div style='transform: translate(-90px, 120px) rotate(270deg);width:200px;color:#aaa;font-size:12px;font-style:italic;font-weight:700;'>\
                   <a onclick='menu_2_out()'>Step2. Select function</a></div>";
    document.getElementById("menu_2").innerHTML = menu_content.attr;
    menu2_width.attr = 20;
    document.getElementById("menu_2").style.width = menu2_width.attr+"px";
    menu_count = menu1_width.attr+menu2_width.attr;
    document.getElementById("main_content_page").style.left = menu_count+"px";
  }*/
  menu_2_o_out();
  function menu_2_o_out(){
    menu_2_content.attr+="<ul id='menu_2_ul_d' style='position:relative;left:-10px;'><div onclick='menu_2_click(0);'><img src='./web_data/menu_1.png' width='60px' height='72px' title='Summary'></div>\
    <div onclick='menu_2_click(1);'><img src='./web_data/menu_2.png' width='60px' height='72px' title='Upload img'></div>\
    <div onclick='menu_2_click(2);'><img src='./web_data/menu_3.png' width='60px' height='72px' title='Detection(Lab)'></div>\
    <div onclick='menu_2_click(3);'><img src='./web_data/menu_4.png' width='60px' height='72px' title='Map view'></div>\
    <div onclick='menu_2_click(4);'><img src='./web_data/menu_5.png' width='60px' height='72px' title='List view'></div>\
    <div onclick='menu_2_click(5);'><img src='./web_data/menu_6.png' width='60px' height='72px' title='Export'></div>\
    </ul>";
    document.getElementById("menu_2").innerHTML = menu_2_content.attr;
    menu2_width.attr = 80;
    document.getElementById("menu_2").style.width = menu2_width.attr+"px";
    menu_count = menu1_width.attr+menu2_width.attr;
    document.getElementById("main_content_page").style.left = menu_count+"px";
  }
  function menu_2_click(menu_num){
    switch(menu_num){
      case 0:
        document.location.href="summary_interface.php?project="+project;
        //alert(0);
        break;
      case 1:
        document.location.href="img_upload_interface.php?project="+project;
        //alert(1);
        break;
      case 2:
        document.location.href="lab_create_interface.php?project="+project;
        //alert(2);
        break;
      case 3:
        document.location.href="map_view_interface.php?project="+project;
        //alert(3);
        break;
      case 4:
        document.location.href="list_view_interface.php?project="+project;
        //alert(4);
        break;
      case 5:
        document.location.href="export_interface.php?project="+project;
        //alert(5);
        break;
      default:
        document.location.href="#";
        //alert(6);
        break;
    }
  }

  </script>
</body>
</html>
