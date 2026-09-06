<?php
  header("Content-Type: text/html; charset=utf-8");
  session_start();
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset = "utf-8">
	<title>HEAP</title>
</head>
<body>
  <?php include 'menu_interface.php';?>
  <script type="text/javascript">
  var main_content = {attr:''};
  main_content.attr="<div style='font-size:24px;text-align:left;padding-left:0px;font-style:italic;font-weight:550;'>Create Project</div>\
  <table>\
  <tr>\
  <td valign='top'>\
  <h1>Form</h1>\
  <form action='project_create_exe.php' method='post' name= 'project_create_form'>\
  <p><label>Project name:<br>\
    <input name = 'project_name' type = 'text' size = '40' style='width:325px;'>\
  </label></p>\
  <p><label>Project description: </br>\
    <textarea name='project_description' style='width:325px;height:100px;'></textarea>\
  </label></p>\
  <input type='hidden' name='action' value='project_create'>\
  <input type='submit' name='button' value='Submit'>\
  </form>\
  </td><td valign='top'>\
  <table><tr>\
  <td><h1 style='padding-left:20px;'>Instruction</h1></td>\
  <td style='position:relative;text-align:right;'><a href ='tutorial_interface.php'  target='_blank' ><button style=''>More information</button></a></td>\
  </tr><tr>\
  <td colspan='2'><img src='./web_data/tutorial_2.png' width='1100px' height='550px' style='position:relative;padding-left:20px;padding-top:0px;'></td>\
  </tr><tr>\
  <td colspan='2'><p style='position:relative;padding-left:20px;padding-right:55px;'>After login, you will enter this page by default or click \"Create Project\" on the main menu <font style='color:red;'>(a)</font>.<br>\
  Fill in the basic information of the project to create a project for the target slide <font style='color:red;'>(b)</font>.<br>\
  You can enter the created project page through the \"Select project\" menu or the \"Project Management\" of the main menu <font style='color:red;'>(c)</font>.</p></td>\
  </tr></table>\
  </td>\
  </tr>\
  </table>";
  document.getElementById("main_content1_page").innerHTML = main_content.attr;
  </script>
</body>
</html>
