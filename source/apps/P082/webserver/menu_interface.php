<?php
  if(!isset($_SESSION)) {
    session_start();
  }
  #$user_folder_path = $_SERVER['DOCUMENT_ROOT']."/user/".$_SESSION['user_id']."/";
  $user_folder_path = "./user/".$_SESSION['user_id']."/";
  $user_description = file_get_contents($user_folder_path."/description.json");
  $user_description = json_decode($user_description);
  $user_name = $user_description->{'full name'};
  $user_projects =  array_map('basename', glob($user_folder_path."*", GLOB_ONLYDIR));
  //print_r($user_projects);
  //show which project now
  if(isset($_GET['project'])){
    $project=$_GET['project'];
  }else{
    $project=null;
  }
  $menu_user_project_info=null;
  for($i=0;$i<count($user_projects);$i++){
    //project info (description)
    $project_description = file_get_contents($user_folder_path.$user_projects[$i]."/description.json");
    $project_description = json_decode($project_description);
    $menu_user_project_info[$i][0] = $user_projects[$i];
    $menu_user_project_info[$i][1] = $project_description->{'project name'};
    //project info (images)
    $project_img_path = $user_folder_path.$user_projects[$i]."/Image/";
    $project_imgs =  array_map('basename', glob($project_img_path."*", GLOB_ONLYDIR));
    for($j=0;$j<count($project_imgs);$j++){
      $img_description = file_get_contents($project_img_path.$project_imgs[$j]."/description.json");
      $img_description = json_decode($img_description);
      $menu_user_project_info[$i][2][$j][0] = $project_imgs[$j];
      $menu_user_project_info[$i][2][$j][1] = $img_description->{'image name'};
    }
    //project info (lab->frame)
    $project_lab_path = $user_folder_path.$user_projects[$i]."/Lab/";
    $project_labs =  array_map('basename', glob($project_lab_path."*", GLOB_ONLYDIR));
    $confirmed_sum = 0;
    for($j=0;$j<count($project_labs);$j++){
      $lab_description = file_get_contents($project_lab_path.$project_labs[$j]."/description.json");
      $lab_description = json_decode($lab_description);
      $menu_user_project_info[$i][3][$j][0] = $project_labs[$j];
      $menu_user_project_info[$i][3][$j][1] = $lab_description->{'lab name'};
      //read place count confirm Eggs
      $txt_path = $project_lab_path.$project_labs[$j]."/place.txt";
      if(is_file($txt_path)){
        $f = fopen($txt_path,'r');
        while ($line = fgets($f)){
          $line = str_replace(array("\r", "\n", "\r\n", "\n\r"), '', $line);
          $line_ar=explode(",",$line);
          if(isset($line_ar[5])&&$line_ar[5]==1){
            $confirmed_sum+=1;
          }
        }
        fclose($f);
      }
    }
    $menu_user_project_info[$i][4]=$confirmed_sum;
  }
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset = "utf-8">
	<title>HEAP</title>
</head>
<body>
	<!--menu-->
  <link rel="stylesheet" href="menu_interface.css" type="text/css">
  <div id = "net_title">
    <div>
    <font>HEAP</font> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    <a href = 'home_interface.php'>Home</a>&nbsp;&nbsp;|&nbsp;&nbsp;
    <a href = 'project_manage_interface.php'>Project Management</a>&nbsp;&nbsp;|&nbsp;&nbsp;
    <a href = 'project_create_interface.php'>Create Project</a>&nbsp;&nbsp;|&nbsp;&nbsp;
    <a href = 'donate_data_interface.php?page=donate_img'>Donate Data</a>&nbsp;&nbsp;|&nbsp;&nbsp;
    <a href = 'download_pre_train_data_interface.php'>Download Pre-train Data</a>&nbsp;&nbsp;|&nbsp;&nbsp;
    <a href = 'tutorial_interface.php'>Tutorial</a>
    <div id='user_part' style='position:absolute;right:50px;top:-15px;'></div>
    </div>
  </div>
  <div id = "menu" style="position:absolute;left:0%;top:26px;height:calc(100% - 26px);"></div>
  <div id = 'main_content1_page' style="position:absolute;top:5%;"></div>
  <div id = "menu_2" style="position:absolute;top:26px;height:calc(100% - 26px);"></div>
  <div id = "main_content_page" style="position:absolute;top:26px;height:calc(100% - 26px);"></div>
    <!--project form(project manager)-->
    <script type="text/javascript">
    var user_name = '<?php echo $user_name ?>';
    var project = '<?php echo $project ?>';
    var menu1_width = {attr:0};
    var menu2_width = {attr:0};
    var menu_content = {attr:''};
    var menu_user_project_info = <?php echo json_encode($menu_user_project_info) ?>;
    var menu_count = 0;
    menu_out();
    user_content();
    function user_content(){
      var t;
      t="<table><tr>\
      <td><p style='color:white;font-style:italic;'>Hi, "+user_name+"&nbsp;&nbsp;&nbsp;&nbsp;<p></td>\
      <td><a onclick='log_out_click();'>Log out</a></td>\
      </tr></table>";
      document.getElementById("user_part").innerHTML = t;
    }
    function log_out_click(){
      var ajax_string = "log_out_exe.php";
      var request = new XMLHttpRequest();
      request.open("GET", ajax_string);
      request.send();
      request.onreadystatechange = function() {
        if (request.readyState === 4) {
          if (request.status === 200) {
            var type = request.getResponseHeader("Content-Type");
            if (type.indexOf("application/json") === 0) {
              var data = JSON.parse(request.responseText);
              if(data.session_clear=="ok"){
                document.location.href="log_in_interface.php";
              }
            }
          } else {
            alert("發生錯誤: " + request.status);
          }
        }
      }
    }
    function menu_out(){
      menu_content.attr="<p><a onclick='menu_in();'>&nbsp;⇦&nbsp;</a></p>\
      <div id = 'menu_div1'><font>Select project:</font></div><ul>";
      if(menu_user_project_info==null){
        menu_user_project_num=0;
      }
      else{
        menu_user_project_num=menu_user_project_info.length;
      }
      for(var i=0;i<menu_user_project_num;i++)
    	{
        if(project!=menu_user_project_info[i][0]){
          menu_content.attr+="<li onclick='menu_1_click("+menu_user_project_info[i][0]+");'>"+menu_user_project_info[i][1]+"<br><font style='color:grey;'>Confirmed Eggs:"+menu_user_project_info[i][4]+"</font></li>";
        }else{
          menu_content.attr+="<li onclick='menu_1_click("+menu_user_project_info[i][0]+");' style='background:black;color:white;border-color:black;'>"+menu_user_project_info[i][1]+"<br><font style='color:grey;'>Confirmed Eggs:"+menu_user_project_info[i][4]+"</font></li>";
        }
      }
      menu_content.attr+="</ul>";
      document.getElementById("menu").innerHTML = menu_content.attr;
      menu1_width.attr = 310;
      document.getElementById("menu").style.width = menu1_width.attr+"px";
      menu_count = menu1_width.attr+15;
      document.getElementById("main_content1_page").style.left = menu_count+"px";
      document.getElementById("menu_2").style.left = menu1_width.attr+"px";
      menu_count = menu1_width.attr+menu2_width.attr;
      document.getElementById("main_content_page").style.left = menu_count+"px";

    }
    function menu_in(){
      menu_content.attr="<p><a onclick='menu_out();'>&nbsp;⇨&nbsp;</a></p>\
      <div style='transform: translate(-90px, 120px) rotate(270deg);width:200px;color:#aaa;font-size:12px;font-style:italic;font-weight:700;'>\
                     <a onclick='menu_out()'>Step1. Select project</a></div>";
      document.getElementById("menu").innerHTML = menu_content.attr;
      menu1_width.attr = 20;
      document.getElementById("menu").style.width = menu1_width.attr+"px";
      menu_count = menu1_width.attr+15;
      document.getElementById("main_content1_page").style.left = menu_count+"px";
      document.getElementById("menu_2").style.left = menu1_width.attr+"px";
      menu_count = menu1_width.attr+menu2_width.attr;
      document.getElementById("main_content_page").style.left = menu_count+"px";
    }
    function menu_1_click(prj_num){
      document.location.href="img_upload_interface.php?project="+prj_num;
    }
    </script>
</body>
</html>
