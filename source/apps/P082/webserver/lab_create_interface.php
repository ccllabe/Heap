<?php
  header("Content-Type: text/html; charset=utf-8");
  session_start();
  $project = $_GET["project"];
  #$project_folder_path = $_SERVER['DOCUMENT_ROOT']."/user/".$_SESSION['user_id']."/".$project."/";
  $project_folder_path = "./user/".$_SESSION['user_id']."/".$project."/";
  #$kind_link_tool_path = $_SERVER['DOCUMENT_ROOT']."/Parasite_egg_identification_version_1_1/computing_node/img_parasite_egg_detection/kind_link_tool.json";
  $kind_link_tool_path = "../computing_node/img_parasite_egg_detection/kind_link_tool.json";
  //find project all image
  $img_folder_path = $project_folder_path."Image/";
  $project_imgs = array_map('basename', glob($img_folder_path."*", GLOB_ONLYDIR));
  $project_img_info =[];
  for($i=0;$i<count($project_imgs);$i++){
    $img_description = file_get_contents($img_folder_path.$project_imgs[$i]."/description.json");
    $img_description = json_decode($img_description);
    $project_img_info[$i][0] = $project_imgs[$i];
    $project_img_info[$i][1] = $img_description->{'image name'};
  }
  //find rebuild model which used to test the target
  $kind_link_tool_table = file_get_contents($kind_link_tool_path);
  $kind_link_tool_table = json_decode($kind_link_tool_table);
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset = "utf-8">
	<title>HEAP</title>
</head>
<body>
  <?php
	include 'menu_2_interface.php';
	?>
  <script type="text/javascript">
    var project_img_info = <?php echo json_encode($project_img_info) ?>;
    var kind_link_tool_table = <?php echo json_encode($kind_link_tool_table) ?>;
    var main_content = {attr:''};
    var project = <?php echo $_GET['project']?>;
    main_content.attr = "<div id='title_n'>Detection</div>\
    <table>\
	  <tr>\
	  <td valign='top'>\
	  <h1 style='position:relative;left:20px;top:20px;'>Form</h1>\
    <div style='position:relative;left:20px;top:20px;'>\
    <form action='lab_create_exe.php' method='post' name= 'lab_create_form' onsubmit= 'return check_value(this);'>\
    <p><label>Lab name:</br>\
      <input id = 'lab_name' name = 'lab_name' type = 'text' size = '30' style='width:300px;'>\
    </label></p>\
    <p><label>Lab description: </br>\
      <textarea id = 'lab_description' name='lab_description' style='width:300px;height:100px;'></textarea>\
    </label></p>";
    main_content.attr += "<p><label>Image: </br>";
    main_content.attr += "<select id = 'image' name = 'image' style='width:308px;'>";
    for(var i=0;i<project_img_info.length;i++)
  	{
      main_content.attr += "<option value='"+project_img_info[i][0]+"'>"+project_img_info[i][1]+"</option>";
    }
    main_content.attr += "</select>";
  	main_content.attr += "</label></p>";
    //kind choose
    main_content.attr += "<p><label>Parasitic eggs kind: </br>";
    main_content.attr += "<select id = 'parasitic_eggs_kind' name = 'parasitic_eggs_kind' onchange='choose_tool();' style='width:308px;'>";
    for(var i in kind_link_tool_table)
  	{
      //var n_string = i.split("egg");
      main_content.attr += "<option value='"+i+"'>"+i+"</option>";
    }
    main_content.attr += "</select>";
  	main_content.attr += "</label></p>";
    main_content.attr += "<div id='tool_choose_place'></div>";
    main_content.attr += "<input type ='hidden' name = 'project' value = '"+project+"'>\
  	<input type='hidden' name='action' value='lab_create'>\
  	<input type='submit' name='button' value='Submit'>\
  	</form>\
    </div>\
		</td><td valign='top' style='position:relative;padding-left:20px;top:20px;'>\
	  <table><tr>\
	  <td><h1 style='padding-left:20px;'>Instruction</h1></td>\
	  <td style='position:relative;text-align:right;'><a href ='tutorial_interface.php'  target='_blank' ><button style=''>More information</button></a></td>\
	  </tr><tr>\
	  <td colspan='2'><img src='./web_data/tutorial_5.png' width='1100px' height='550px' style='position:relative;padding-left:20px;padding-top:0px;'></td>\
	  </tr><tr>\
	  <td colspan='2'><p style='position:relative;padding-left:20px;padding-right:55px;'>After uploading the image, you can set the identification parameters to initially identify the eggs from the image.<br>\
    Click \"Detection(Lab)\" in the function menu to enter this page <font style='color:red;'>(a)</font>.<br>\
    Fill in the identification information and select the tool to identify the parasite egg type in the target image <font style='color:red;'>(b)</font>.<br>\
    The target image is derived from an image uploaded in the past <font style='color:red;'>(b-1)</font>.<br>\
    The menu will display the type of parasite eggs currently available for identification and the tools that support this type <font style='color:red;'>(b-2)</font>.</p></td>\
	  </tr></table>\
	  </td>\
	  </tr>\
	  </table>";
    main_content_page.innerHTML = main_content.attr;
    choose_tool()
    //function choose
    function choose_tool(){
      var parasitic_eggs_kind = document.getElementById("parasitic_eggs_kind").value;
      var tool_choose_place = document.getElementById("tool_choose_place");
      var htmlstring = '';
      var toolname;
      htmlstring = htmlstring+"<p><label>Tool: <br>";
      for(var i=0;i<kind_link_tool_table[parasitic_eggs_kind].length;i++)
    	{
        if(kind_link_tool_table[parasitic_eggs_kind][i]=="ssd_fordel"){
          toolname="ssd300";
        }else if(kind_link_tool_table[parasitic_eggs_kind][i]=="multi_fast_rcnn"){
          toolname="Faster R-CNN (default)";
        }else if(kind_link_tool_table[parasitic_eggs_kind][i]=="multi_fast_rcnn_2"){
          toolname="Faster R-CNN (clear)";
        }else{
          toolname=kind_link_tool_table[parasitic_eggs_kind][i];
        }
        htmlstring = htmlstring+"<label><input  type='radio' name='tool' value='"+kind_link_tool_table[parasitic_eggs_kind][i]+"'>"+toolname+"</label></br>";
      }
      htmlstring = htmlstring+"<input  type='radio' id='tool' name='tool' value='' disabled><label style='background-color:gray;'>Yolo</label></br>";
      htmlstring = htmlstring+"<input  type='radio' id='tool' name='tool' value='' disabled><label style='background-color:gray;'>R-CNN</label></br>";
      //htmlstring = htmlstring+"<input  type='radio' id='tool' name='tool' value=''><label style='background-color:gray;'>Faster R-CNN</label>";
      htmlstring = htmlstring+"</label></p>";
      document.getElementById("tool_choose_place").innerHTML=htmlstring;
    }
	function check_value(form){
      if(lab_name.value == ""){
        alert("Please input Lab name");
        return false;
      }else if(image.value == ""){
        alert("You didn't upload image");
        return false;
      }else if(document.querySelector('input[name="tool"]:checked').value == ""){
        alert("tool is not yet open");
        return false;
      }else{
        return true;
      }
    }
  </script>
</body>
</html>
