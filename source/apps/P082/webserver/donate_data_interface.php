<?php
  header("Content-Type: text/html; charset=utf-8");
  session_start();
  $check_login=0;
  $log_in_file_path = "./user/log_in_info.txt";
  $user_folder_path = "./user/".$_SESSION['user_id']."/";
  //check log in
  if($_SESSION['user_id']!=null){
    $log_in_info = fopen($log_in_file_path, "r");
    while(! feof($log_in_info)){
      $line_content = fgets($log_in_info);
      $line_content = str_replace(array("\r", "\n", "\r\n", "\n\r"), '', $line_content);
      $line_content_array = explode(":",$line_content);
      if(strcmp($line_content_array[0],$_SESSION['user_id'])==0){
        $check_login=1;
        break;
      }
    }
    fclose($log_in_info);
  }
  if($check_login==0){
    echo "<script>alert('Please log in before performing this operation!!');location.href = 'log_in_interface.php';</script>";
  }
  $page = $_GET["page"];
  //hi user
  $user_description = file_get_contents($user_folder_path."/description.json");
  $user_description = json_decode($user_description);
  $user_name = $user_description->{'full name'};

?>
<!DOCTYPE html>
<html>
<head>
	<meta charset = "utf-8">
	<title>HEAP</title>
</head>
<body style='background:#E5E7E9;'>
  <link rel="stylesheet" href="menu_interface.css" type="text/css">
  <div id = "net_title">
    <div>
      <font>HEAP</font> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
      <a href = 'home_interface.php'>Home</a>&nbsp;&nbsp;|&nbsp;&nbsp;
      <a href = 'project_manage_interface.php'>Project Management</a>&nbsp;&nbsp;|&nbsp;&nbsp;
      <a href = 'project_create_interface.php'>Create Project</a>&nbsp;&nbsp;|&nbsp;&nbsp;
      <a href = #>Donate Data</a>&nbsp;&nbsp;|&nbsp;&nbsp;
      <a href = 'download_pre_train_data_interface.php'>Download Pre-train Data</a>&nbsp;&nbsp;|&nbsp;&nbsp;
      <a href = 'tutorial_interface.php'>Tutorial</a>
      <div id='user_part' style='position:absolute;right:50px;top:-15px;'></div>
    </div>
  </div></br></br>
  <div id = "main_content1_page"></div>
  <script type="text/javascript">
  //user part
  var user_name = '<?php echo $user_name ?>';
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
  </script>
  <script type="text/javascript">
  //initial parameter
  page = "<?php echo $page; ?>";
  var d_Img_form_param = {authorizer_name:'',
                    authorized_institute:'',
                    phone_number:'',
                    e_mail:'',
                    p_o_download:'1',
                    authorization_description:'',
                    parasite_e_k:'',
                    s_model:'1',
                    img_size:'',
                    upload_way:'1',
                    upload_file:'',
                    file_description:''
                  };
  var d_Mod_form_param = {authorizer_name:'',
                    authorized_institute:'',
                    phone_number:'',
                    e_mail:'',
                    p_o_download:'1',
                    authorization_description:'',
                    parasite_e_k:'',
                    s_model:'1',
                    img_size:'',
                    upload_way:'1',
                    upload_file:'',
                    file_description:''
                  };

  //initial load function
  ini_content();
  function ini_content(){
    //show main_content page frame
    main_content();
    //init: show ask page (Img or model)
    if(page=="donate_img"){
      d_Img_b_click();
    }
    if(page=="donate_model"){
      d_Mod_b_click();
    }
  }

  //left page && right page
  //left page(1.donate change button: (1)Donate Image (2)Donate Model 2.follow button show form & introduction)
  //right page(1.model change introduction: (1)ssd300 (2)u-net 2.follow button show introduction)
  function main_content(){
    var t;
    t="<div style='font-size:24px;text-align:left;padding-left:5px;font-style:italic;font-weight:550;'>Donate Data</div>\
    <table id='donate_data_table' style='position:relative;width:100%;height:100%;table-layout:fixed;' border='0'>\
    <tr>\
      <td><div id='l_div'>\
        <table style='position:relative;left:-3px;top:15px;'>\
        <tr>\
        <!--<td><button id='d_Img_b' onclick='d_Img_b_click();' style='font-size:18px;border:2px solid #777;border-radius:6px;padding:3px;'>Donate Image</button></td>\
        <td><button id='d_Mod_b' onclick='d_Mod_b_click();' style='font-size:18px;border:2px solid #777;border-radius:6px;padding:3px;'>Donate Model</button></td>-->\
        <td><button id='d_Img_b' onclick='d_Img_b_click();' style='font-size:18px;border:2px solid #232323;border-radius:6px 6px 0px 0px;padding:3px;'>Donate Image</button></td>\
        <td><button id='d_Mod_b' onclick='d_Mod_b_click();' style='font-size:18px;border:2px solid #232323;border-radius:6px 6px 0px 0px;padding:3px;'>Donate Model</button></td>\
        </tr>\
        </table>\
        <div id='d_form_p' style='position:relative;height:750px;padding-left:20px;padding-right:20px;padding-top:5px;padding-bottom:50px;background:#232323;margin-top:10px;margin-right:200px;color:white;font-size:14px;'></div>\
      </div></td>\
      <td><div id='r_div'></div></td>\
    </tr>\
    </table>";
    document.getElementById("main_content1_page").innerHTML = t;
  }

  //donate Image click & donate model click
  //donate Image button onclick
  function d_Img_b_click(){
    //clear array
    d_Img_form_param = {authorizer_name:'',
                      authorized_institute:'',
                      phone_number:'',
                      e_mail:'',
                      p_o_download:'1',
                      authorization_description:'',
                      parasite_e_k:'',
                      s_model:'1',
                      img_size:'',
                      upload_way:'1',
                      upload_file:'',
                      file_description:''
                    };
    //change button style
    document.getElementById("d_Img_b").style.backgroundColor ="#232323";
    document.getElementById("d_Img_b").style.color ="white";
    document.getElementById("d_Mod_b").style.backgroundColor ="#FFFFFF";
    document.getElementById("d_Mod_b").style.color ="black";
    //build right button(instruction) style='position:relative;height:853px;background:#9f9f9f;padding-left:20px;padding-top:5px;'>
    var t;
    t="<div style='font-size:18px;background:#9f9f9f;padding-left:20px;padding-top:1px;padding-bottom:1px;'><h2>Instruction<h2></div>\
      <div style='background:#c8c8c8;padding-left:15px;'>\
      <table>\
        <tr>\
          <td><p style='font-size:16px;line-height:0px;font-weight:700;'>Model:</p></td>\
          <!--<td><button id='d_Img_intro_ssd300_b' onclick='d_Img_intro_ssd300_b_click();' style='font-size:18px;border:2px solid #9f9f9f;border-radius:6px 6px 0px 0px;padding:3px;'>ssd300</button></td>\
          <td><button id='d_Img_intro_u_net_b' onclick='d_Img_intro_u_net_b_click();' style='font-size:18px;border:2px solid #9f9f9f;border-radius:6px 6px 0px 0px;padding:3px;'>u-net</button></td>-->\
          <td><button id='d_Img_intro_ssd300_b' onclick='d_Img_intro_ssd300_b_click();' style='font-size:18px;padding:3px;'>ssd300</button></td>\
          <td><button id='d_Img_intro_u_net_b' onclick='d_Img_intro_u_net_b_click();' style='font-size:18px;padding:3px;'>u-net</button></td>\
        </tr>\
      </table>\
      </div>\
      <div id='d_instruction_p' style='position:relative;height:723px;overflow-y:auto;background:#9f9f9f;padding-left:20px;padding-top:5px;padding-bottom:5px;'></div>";
    document.getElementById("r_div").innerHTML = t;
    //call 1.donate img step.1 form 2.d_Img_intro_ssd300_content
    d_Img_form_part1_content();
    d_Img_intro_ssd300_b_click();
  }
  //donate Model button onclick
  function d_Mod_b_click(){
    //clear array
    d_Mod_form_param = {authorizer_name:'',
                      authorized_institute:'',
                      phone_number:'',
                      e_mail:'',
                      p_o_download:'1',
                      authorization_description:'',
                      parasite_e_k:'',
                      s_model:'1',
                      img_size:'',
                      upload_way:'1',
                      upload_file:'',
                      file_description:''
                    };
    //change button style
    document.getElementById("d_Img_b").style.backgroundColor ="#FFFFFF";
    document.getElementById("d_Img_b").style.color ="black";
    document.getElementById("d_Mod_b").style.backgroundColor ="#232323";
    document.getElementById("d_Mod_b").style.color ="white";
    //build right button(instruction)
    var t;
    t="<div style='font-size:18px;background:#9f9f9f;padding-left:20px;padding-top:1px;padding-bottom:1px;'><h2>Instruction<h2></div>\
      <div style='background:#c8c8c8;padding-left:15px;'>\
      <table>\
        <tr>\
          <td><p style='font-size:16px;line-height:0px;font-weight:700;'>Model:</p></td>\
          <!--<td><button id='d_Mod_intro_ssd300_b' onclick='d_Mod_intro_ssd300_b_click();' style='font-size:18px;border:2px solid #535353;border-radius:6px;padding:3px;'>ssd300</button></td>\
          <td><button id='d_Mod_intro_u_net_b' onclick='d_Mod_intro_u_net_b_click();' style='font-size:18px;border:2px solid #535353;border-radius:6px;padding:3px;'>u-net</button></td>-->\
          <td><button id='d_Mod_intro_ssd300_b' onclick='d_Mod_intro_ssd300_b_click();' style='font-size:18px;padding:3px;'>ssd300</button></td>\
          <td><button id='d_Mod_intro_u_net_b' onclick='d_Mod_intro_u_net_b_click();' style='font-size:18px;padding:3px;'>u-net</button></td>\
        </tr>\
      </table>\
      </div>\
      <div id='d_instruction_p' style='position:relative;height:723px;overflow-y:auto;background:#9f9f9f;padding-left:20px;padding-top:5px;padding-bottom:5px;'></div>";
    document.getElementById("r_div").innerHTML = t;
    //call 1.donate Mod step.1 form 2.d_Mod_intro_ssd300_content
    d_Mod_form_part1_content();
    d_Mod_intro_ssd300_b_click();
  }

  //Donate Image page
  //Image form
  //donate img: show donate img step.1 form
  function d_Img_form_part1_content(){
    var t;
    t="<form id='d_Img_part_1'>\
    <h2>Donate Image Form</h2>\
    <p>Please read the instructions on the right to confirm that the data preparation is complete, and then fill out the form. Thank you for your support.</p>\
    <label>Step.1 Authorization Part</label>\
    <p><label>Authorizer: *</br>\
      <input id = 'authorizer_name' name = 'authorizer_name' type = 'text' value='"+d_Img_form_param.authorizer_name+"' size = '30' style='width:315px;' required>\
    </label></p>\
    <p><label>Authorized institution: *</br>\
      <input id = 'authorized_institute' name = 'authorized_institute' type = 'text' value='"+d_Img_form_param.authorized_institute+"' size = '30' style='width:315px;' required>\
    </label></p>\
    <p><label>Phone number: *</br>\
      <input id = 'phone_number' name = 'phone_number' type = 'text' value='"+d_Img_form_param.phone_number+"' size = '30' style='width:315px;' required='required' required pattern='(?=^[0-9]{6,10}$)((?=.*[0-9]))^.*$'>\
    </label></p>\
    <p><label>E-mail: *</br>\
      <input id = 'e_mail' name = 'e_mail' type = 'text' value='"+d_Img_form_param.e_mail+"' size = '30' style='width:315px;' required='required' required pattern='[^@\s]+@[^@\s]+\.[^@\s]+'>\
    </label></p>\
    <p><label>Provide other users to download data: *<br>";
    if(d_Img_form_param.p_o_download!='0'){
      t+="<input type='radio' id='p_o_download' name='p_o_download' value='1' checked>Yes<br>\
      <input type='radio' id='p_o_download' name='p_o_download' value='0'>No";
    }else{
      t+="<input type='radio' id='p_o_download' name='p_o_download' value='1'>Yes<br>\
      <input type='radio' id='p_o_download' name='p_o_download' value='0' checked>No";
    }
    t+="</label></p>\
    <p><label>Other authorization instructions:</br>\
      <textarea id='authorization_description' name='authorization_description' style='width:315px;height:100px;'>"+d_Img_form_param.authorization_description+"</textarea>\
    </label></p>\
    <p>As the authorization affects your own rights and interests, please fill in your real personal information for related matters.</p>\
    </form>\
    <button onclick='d_Img_form_part1_n_click()' style='position:relative;top:100px;left:650px;font-size:16px;'>Next</button>";
		document.getElementById("d_form_p").innerHTML = t;
  }
  //donate img: Next button (Part.1 => Part.2)
  function d_Img_form_part1_n_click(){
    //change value
    d_Img_form_param.authorizer_name = document.getElementById("authorizer_name").value;
    d_Img_form_param.authorized_institute = document.getElementById("authorized_institute").value;
    d_Img_form_param.phone_number = document.getElementById("phone_number").value;
    d_Img_form_param.e_mail = document.getElementById("e_mail").value;
    d_Img_form_param.p_o_download = document.getElementById("d_Img_part_1").p_o_download.value;
    d_Img_form_param.authorization_description = document.getElementById("authorization_description").value;
    //check form fill condition
    var v_val = true;
    //if null
    if(d_Img_form_param.authorizer_name == ""){
      v_val = false;
    }
    if(d_Img_form_param.authorized_institute == ""){
      v_val = false;
    }
    test_re = /(?=^[0-9]{6,10}$)((?=.*[0-9]))^.*$/;
    if((d_Img_form_param.phone_number == "")||(!test_re.test(d_Img_form_param.phone_number))){
      v_val = false;
    }
    test_re = /[^@\s]+@[^@\s]+\.[^@\s]+/;
    if((d_Img_form_param.e_mail == "")||(!test_re.test(d_Img_form_param.e_mail))){
      v_val = false;
    }
    if(d_Img_form_param.p_o_download == ""){
      v_val = false;
    }
    //change form content
    if(v_val){
      d_Img_form_part2_content();
    }else{
      alert("Please make sure to fill the correct information!!");
    }
    //d_Img_form_part2_content();
  }
  //donate img: show donate img step.2 form
  function d_Img_form_part2_content(){
    var t;
    t="<form method='POST' action='donate_data_exe.php' enctype='multipart/form-data'  name= 'donate_img_form' id='donate_img_form'>\
    <h2>Donate Image Form</h2>\
    <p>Please read the instructions on the right to confirm that the data preparation is complete, and then fill out the form. Thank you for your support.</p>\
    <label>Step.2 File upload Part</label>\
    <p><label>Parasite egg category: *</br>\
      <input id = 'parasite_e_k' name = 'parasite_e_k' type = 'text' value = '"+d_Img_form_param.parasite_e_k+"' size = '30' style='width:315px;' required>\
    </label></p>\
    <p><label>Support Model: *<br>";
    if(d_Img_form_param.s_model!='2'){
      t+="<input type='radio' id='s_model' name='s_model' onclick='d_Img_form_part2_smodel_click(1);' value='1' checked>ssd300<br>\
      <input type='radio' id='s_model' name='s_model' onclick='d_Img_form_part2_smodel_click(1);' value='2'>u-net";
    }else{
      t+="<input type='radio' id='s_model' name='s_model' onclick='d_Img_form_part2_smodel_click(1);' value='1'>ssd300<br>\
      <input type='radio' id='s_model' name='s_model' onclick='d_Img_form_part2_smodel_click(1);' value='2' checked>u-net";
    }
    t+="</label></p>\
    <p><label>Generated image size: *</br>\
      <div id='g_img_size_p'></div>\
    </label></p>\
    <p><label>Upload method: *<br>";
    switch(d_Img_form_param.upload_way){
      case '1':
        t+="<input type='radio' id='upload_way' name='upload_way' onclick='d_Img_form_part2_uploadway_click(1)' value='1' checked>Localhost<br>\
        <input type='radio' id='upload_way' name='upload_way' onclick='d_Img_form_part2_uploadway_click(1)' value='2'>Google Drive<br>\
        <input type='radio' id='upload_way' name='upload_way' onclick='d_Img_form_part2_uploadway_click(1)' value='3'>OneDrive<br>\
        <input type='radio' id='upload_way' name='upload_way' onclick='d_Img_form_part2_uploadway_click(1)' value='4'>Dropbox<br>";
        break;
      case '2':
        t+="<input type='radio' id='upload_way' name='upload_way' onclick='d_Img_form_part2_uploadway_click(1)' value='1'>Localhost<br>\
        <input type='radio' id='upload_way' name='upload_way' onclick='d_Img_form_part2_uploadway_click(1)' value='2' checked>Google Drive<br>\
        <input type='radio' id='upload_way' name='upload_way' onclick='d_Img_form_part2_uploadway_click(1)' value='3'>OneDrive<br>\
        <input type='radio' id='upload_way' name='upload_way' onclick='d_Img_form_part2_uploadway_click(1)' value='4'>Dropbox<br>";
        break;
      case '3':
        t+="<input type='radio' id='upload_way' name='upload_way' onclick='d_Img_form_part2_uploadway_click(1)' value='1'>Localhost<br>\
        <input type='radio' id='upload_way' name='upload_way' onclick='d_Img_form_part2_uploadway_click(1)' value='2'>Google Drive<br>\
        <input type='radio' id='upload_way' name='upload_way' onclick='d_Img_form_part2_uploadway_click(1)' value='3' checked>OneDrive<br>\
        <input type='radio' id='upload_way' name='upload_way' onclick='d_Img_form_part2_uploadway_click(1)' value='4'>Dropbox<br>";
        break;
      default:
        t+="<input type='radio' id='upload_way' name='upload_way' onclick='d_Img_form_part2_uploadway_click(1)' value='1'>Localhost<br>\
        <input type='radio' id='upload_way' name='upload_way' onclick='d_Img_form_part2_uploadway_click(1)' value='2'>Google Drive<br>\
        <input type='radio' id='upload_way' name='upload_way' onclick='d_Img_form_part2_uploadway_click(1)' value='3'>OneDrive<br>\
        <input type='radio' id='upload_way' name='upload_way' onclick='d_Img_form_part2_uploadway_click(1)' value='4' checked>Dropbox<br>";
        break;
    }
    t+="</label></p>\
    <p><label>Compressed file(Please read the file request format)</label></p>\
      <div id = 'upload_way_p'></div>\
    <p><label>File description:</br>\
    <textarea id='file_description' name='file_description' style='width:315px;height:100px;'>"+d_Img_form_param.file_description+"</textarea>\
    </label></p>\
    <!--Hidden input: Part 1. Form-->\
    <input type='hidden' name='authorizer_name' value='"+d_Img_form_param.authorizer_name+"'>\
    <input type='hidden' name='authorized_institute' value='"+d_Img_form_param.authorized_institute+"'>\
    <input type='hidden' name='phone_number' value='"+d_Img_form_param.phone_number+"'>\
    <input type='hidden' name='e_mail' value='"+d_Img_form_param.e_mail+"'>\
    <input type='hidden' name='p_o_download' value='"+d_Img_form_param.p_o_download+"'>\
    <input type='hidden' name='authorization_description' value='"+d_Img_form_param.authorization_description+"'>\
    <input type='hidden' name='action' value='img_donate'>\
    <br><br>\
    <input type='submit' name='button' value='Submit' style='position:relative;top:15px;left:640px;font-size:16px;'>\
    </form>\
    <button onclick='d_Img_form_part2_b_click()' style='position:relative;font-size:16px;top:-9px;left:570px;'>Back</button>";
    document.getElementById("d_form_p").innerHTML = t;
    d_Img_form_part2_smodel_click(0);
    d_Img_form_part2_uploadway_click(0);
  }
  //donate img: model use img size
  function d_Img_form_part2_smodel_click(act){
    var ch_s_model = document.getElementById("donate_img_form").s_model.value;
    var t;
    //if (load before value: ) else (load default value:)
    if((act!=1)&&(d_Img_form_param.img_size!="")){
      //when have selected
      switch(ch_s_model){
        case '1':
          //alert(d_Img_form_param.img_size);
          if(d_Img_form_param.img_size=="300_300"){
              t="<input type='radio' id='img_size' name='img_size' value='300_300' checked>300x300";
          }
          break;
        default:
          //alert(d_Img_form_param.img_size);
          if(d_Img_form_param.img_size=="512_512"){
              t="<input type='radio' id='img_size' name='img_size' value='512_512' checked>512x512";
          }
      }
    }else{
      switch(ch_s_model){
        case '1':
          t="<input type='radio' id='img_size' name='img_size' value='300_300' checked>300x300";
          break;
        default:
          t="<input type='radio' id='img_size' name='img_size' value='512_512' checked>512x512";
      }
    }
    document.getElementById("g_img_size_p").innerHTML = t;
  }
  //donate img: upload way use input data
  function d_Img_form_part2_uploadway_click(act){
    var ch_upld_way = document.getElementById("donate_img_form").upload_way.value;
    var t;
    //if (load before value: ) else (load default value:)
    if((act!=1)&&(d_Img_form_param.upload_file!="")){
      switch(ch_upld_way){
        case '1':
          d_Img_form_param.upload_file="";
          t="<label>Localhost: *</label></br>\
  				<input type='file' id='upload_file' name='upload_file' required>";
          break;
        case '2':
          t="<label>Google drive: *</label></br>\
  				<input id='upload_file' name='upload_file' value='"+d_Img_form_param.upload_file+"' type = 'text' size = '30' style='width:315px;' required>";
          break;
        case '3':
          t="<label>Onedrive: *</label></br>\
  				<input id='upload_file' name='upload_file' value='"+d_Img_form_param.upload_file+"' type = 'text' size = '30' style='width:315px;' required>";
          break;
        default:
          t="<label>Dropbox: *</label></br>\
  				<input id='upload_file' name='upload_file' value='"+d_Img_form_param.upload_file+"' type = 'text' size = '30' style='width:315px;' required>";
      }
    }else{
      switch(ch_upld_way){
        case '1':
          t="<label>Localhost: *</label></br>\
  				<input type='file' id='upload_file' name='upload_file' required>";
          break;
        case '2':
          t="<label>Google drive: *</label></br>\
  				<input id='upload_file' name='upload_file' type = 'text' size = '30' style='width:315px;' required>";
          break;
        case '3':
          t="<label>Onedrive: *</label></br>\
  				<input id='upload_file' name='upload_file' type = 'text' size = '30' style='width:315px;' required>";
          break;
        default:
          t="<label>Dropbox: *</label></br>\
  				<input id='upload_file' name='upload_file' type = 'text' size = '30' style='width:315px;' required>";
      }
    }
    document.getElementById("upload_way_p").innerHTML = t;
  }
  //donate img: Back button (Part.2 => Part.1)
  function d_Img_form_part2_b_click(){
    //change value
    d_Img_form_param.parasite_e_k = document.getElementById("parasite_e_k").value;
    d_Img_form_param.s_model = document.getElementById("donate_img_form").s_model.value;
    d_Img_form_param.img_size = document.getElementById("donate_img_form").img_size.value;
    d_Img_form_param.upload_way = document.getElementById("donate_img_form").upload_way.value;
    d_Img_form_param.upload_file = document.getElementById("donate_img_form").upload_file.value;
    d_Img_form_param.file_description = document.getElementById("file_description").value;

    //check form fill condition(value null: accept)
    //check if agree to clear the localhost file path
    var v_val = true;
    if(d_Img_form_param.upload_way=="1"&&d_Img_form_param.upload_file!=""){
      v_val = confirm('For security reasons, the path of the local file will be cleared. Do you still want to go back to the previous page?');
    }
    //change form content
    if(v_val){
      d_Img_form_part1_content();
    }
  }
  //Image instruction
  //donate Image => instruction onclick
  //ssd300 instruction onclick
  function d_Img_intro_ssd300_b_click(){
    //change button style
    /*document.getElementById("d_Img_intro_ssd300_b").style.backgroundColor ="#232323";
    document.getElementById("d_Img_intro_ssd300_b").style.color ="white";
    document.getElementById("d_Img_intro_u_net_b").style.backgroundColor ="#FFFFFF";
    document.getElementById("d_Img_intro_u_net_b").style.color ="black";*/
    d_Img_intro_ssd300_content();
  }
  //u-net instruction onclick
  function d_Img_intro_u_net_b_click(){
    //change button style
    /*document.getElementById("d_Img_intro_ssd300_b").style.backgroundColor ="#FFFFFF";
    document.getElementById("d_Img_intro_ssd300_b").style.color ="black";
    document.getElementById("d_Img_intro_u_net_b").style.backgroundColor ="#232323";
    document.getElementById("d_Img_intro_u_net_b").style.color ="white";*/
    d_Img_intro_u_net_content();
  }
  //ssd300 instruction content
  function d_Img_intro_ssd300_content(){
    var t;
    t="<h1 style='font-size:22px;'>How to generate data set of SSD300 model</h1>\
    <details open='open'>\
      <summary style='font-size:18px;'>Step 1. Prepare image</summary>\
      <h3>Prepare a image of the parasite eggs scanned from the microscope.</h3>\
      <img src='./web_data/d_img_ssd300_1.png' width='900px' height='500px' style=''>\
      <br>\
      <br>\
      <hr style='border: 3px solid #474747;border-radius: 5px;'>\
    </details>\
    <details open='open'>\
      <summary style='font-size:18px;'>Step 2. Crop image</summary>\
      <h3>Use image processing software to cut out a 300*300 image containing parasite eggs, and save it. (Note: Please do not crop at the repeated position) (Here takes <a href='https://estore.corel.com/store/crelapac/zh_TW/pd/ThemeID.27600600/productID.106213100' target='_blank'>PhotoImpact x3</a> as an example)</h3>\
      <img src='./web_data/d_img_ssd300_2_all.gif' width='900px' height='500px' style=''>\
      <br>\
      <br>\
      <hr style='border: 3px solid #474747;border-radius: 5px;'>\
    </details>\
    <details open='open'>\
      <summary style='font-size:18px;'>Step 3. Lable image</summary>\
      <h3>Use <a href='https://github.com/tzutalin/labelImg' target='_blank'>labelImg</a> (version: 1.8.3) to label the cropped image with a size of 300*300, and generate an xml file.</h3>\
      <img src='./web_data/d_img_ssd300_3.png' width='900px' height='500px' style=''>\
      <br>\
      <br>\
      <hr style='border: 3px solid #474747;border-radius: 5px;'>\
    </details>\
    <details open='open'>\
      <summary style='font-size:18px;'>Step 4. Organize files and compress</summary>\
      <h3>Put the 300*300 images, the xml files and other description files into the folder to generate a compressed file.</h3>\
      <img src='./web_data/d_img_ssd300_4.png' width='900px' height='500px' style=''>\
      <br>\
      <br>\
      <br>\
    </details>\
    ";
    document.getElementById("d_instruction_p").innerHTML = t;
  }
  //u-net instruction content
  function d_Img_intro_u_net_content(){
    var t;
    t="<h1 style='font-size:22px;'>How to generate data set of U-net model</h1>\
    <details open='open'>\
      <summary style='font-size:18px;'>Step 1. Prepare image</summary>\
      <h3>Prepare a image of the parasite eggs scanned from the microscope.</h3>\
      <img src='./web_data/d_img_unet_1.png' width='900px' height='500px' style=''>\
      <br>\
      <br>\
      <hr style='border: 3px solid #474747;border-radius: 5px;'>\
    </details>\
    <details open='open'>\
      <summary style='font-size:18px;'>Step 2. Crop image</summary>\
      <h3>Use image processing software to cut out a 512*512 image containing parasite eggs, and save it. (Note: Please do not crop at the repeated position) (Here takes <a href='https://estore.corel.com/store/crelapac/zh_TW/pd/ThemeID.27600600/productID.106213100' target='_blank'>PhotoImpact x3</a> as an example)</h3>\
      <img src='./web_data/d_img_unet_2_all.gif' width='900px' height='500px' style=''>\
      <br>\
      <br>\
      <hr style='border: 3px solid #474747;border-radius: 5px;'>\
    </details>\
    <details open='open'>\
      <summary style='font-size:18px;'>Step 3. Lable image</summary>\
      <h3>Use <a href='https://github.com/wkentaro/labelme' target='_blank'>labelme</a> (version: 3.16.1) to label the cropped image with a size of 512*512, and generate an json file.</h3>\
      <img src='./web_data/d_img_unet_3.png' width='900px' height='500px' style=''>\
      <br>\
      <br>\
      <hr style='border: 3px solid #474747;border-radius: 5px;'>\
    </details>\
    <details open='open'>\
      <summary style='font-size:18px;'>Step 4. Organize files and compress</summary>\
      <h3>Put the 512*512 images, the json files and other description files into the folder to generate a compressed file.</h3>\
      <img src='./web_data/d_img_unet_4.png' width='900px' height='500px' style=''>\
      <br>\
      <br>\
      <br>\
    </details>\
    ";
    document.getElementById("d_instruction_p").innerHTML = t;
  }

  //Donate Model page
  //Model form
  //donate model: show donate model step.1 form
  function d_Mod_form_part1_content(){
    var t;
    t="<form id='d_Mod_part_1'>\
    <h2>Donate Model Form</h2>\
    <p>Please read the instructions on the right to confirm that the data preparation is complete, and then fill out the form. Thank you for your support.</p>\
    <label>Step.1 Authorization Part</label>\
    <p><label>Authorizer: *</br>\
      <input id = 'authorizer_name' name = 'authorizer_name' type = 'text' value='"+d_Mod_form_param.authorizer_name+"' size = '30' style='width:315px;' required>\
    </label></p>\
    <p><label>Authorized institution: *</br>\
      <input id = 'authorized_institute' name = 'authorized_institute' type = 'text' value='"+d_Mod_form_param.authorized_institute+"' size = '30' style='width:315px;' required>\
    </label></p>\
    <p><label>Phone number: *</br>\
      <input id = 'phone_number' name = 'phone_number' type = 'text' value='"+d_Mod_form_param.phone_number+"' size = '30' style='width:315px;' required='required' required pattern='(?=^[0-9]{6,10}$)((?=.*[0-9]))^.*$'>\
    </label></p>\
    <p><label>E-mail: *</br>\
      <input id = 'e_mail' name = 'e_mail' type = 'text' value='"+d_Mod_form_param.e_mail+"' size = '30' style='width:315px;' required='required' required pattern='[^@\s]+@[^@\s]+\.[^@\s]+'>\
    </label></p>\
    <p><label>Provide other users to download data: *<br>";
    if(d_Mod_form_param.p_o_download!='0'){
      t+="<input type='radio' id='p_o_download' name='p_o_download' value='1' checked>Yes<br>\
      <input type='radio' id='p_o_download' name='p_o_download' value='0'>No";
    }else{
      t+="<input type='radio' id='p_o_download' name='p_o_download' value='1'>Yes<br>\
      <input type='radio' id='p_o_download' name='p_o_download' value='0' checked>No";
    }
    t+="</label></p>\
    <p><label>Other authorization instructions:</br>\
      <textarea id='authorization_description' name='authorization_description' style='width:315px;height:100px;'>"+d_Mod_form_param.authorization_description+"</textarea>\
    </label></p>\
    <p>As the authorization affects your own rights and interests, please fill in your real personal information for related matters.</p>\
    </form>\
    <button onclick='d_Mod_form_part1_n_click()' style='position:relative;top:100px;left:650px;font-size:16px;'>Next</button>";
		document.getElementById("d_form_p").innerHTML = t;
  }
  //donate model: Next button (Part.1 => Part.2)
  function d_Mod_form_part1_n_click(){
    //change value
    d_Mod_form_param.authorizer_name = document.getElementById("authorizer_name").value;
    d_Mod_form_param.authorized_institute = document.getElementById("authorized_institute").value;
    d_Mod_form_param.phone_number = document.getElementById("phone_number").value;
    d_Mod_form_param.e_mail = document.getElementById("e_mail").value;
    d_Mod_form_param.p_o_download = document.getElementById("d_Mod_part_1").p_o_download.value;
    d_Mod_form_param.authorization_description = document.getElementById("authorization_description").value;
    //check form fill condition
    var v_val = true;
    //if null
    if(d_Mod_form_param.authorizer_name == ""){
      v_val = false;
    }
    if(d_Mod_form_param.authorized_institute == ""){
      v_val = false;
    }
    test_re = /(?=^[0-9]{6,10}$)((?=.*[0-9]))^.*$/;
    if((d_Mod_form_param.phone_number == "")||(!test_re.test(d_Mod_form_param.phone_number))){
      v_val = false;
    }
    test_re = /[^@\s]+@[^@\s]+\.[^@\s]+/;
    if((d_Mod_form_param.e_mail == "")||(!test_re.test(d_Mod_form_param.e_mail))){
      v_val = false;
    }
    if(d_Mod_form_param.p_o_download == ""){
      v_val = false;
    }
    //change form content
    if(v_val){
      d_Mod_form_part2_content();
    }else{
      alert("Please make sure to fill the correct information!!");
    }
    //d_Mod_form_part2_content();
  }
  //donate model: show donate model step.2 form
  function d_Mod_form_part2_content(){
    var t;
    t="<form method='POST' action='donate_data_exe.php' enctype='multipart/form-data'  name= 'donate_mod_form' id='donate_mod_form'>\
    <h2>Donate Model</h2>\
    <p>Please read the instructions on the right to confirm that the data preparation is complete, and then fill out the form. Thank you for your support.</p>\
    <label>Step.2 File upload Part</label>\
    <p><label>Parasite egg category: *</br>\
      <input id = 'parasite_e_k' name = 'parasite_e_k' type = 'text' value = '"+d_Mod_form_param.parasite_e_k+"' size = '30' style='width:315px;' required>\
    </label></p>\
    <p><label>Support Model: *<br>";
    if(d_Mod_form_param.s_model!='2'){
      t+="<input type='radio' id='s_model' name='s_model' onclick='d_Mod_form_part2_smodel_click(1);' value='1' checked>ssd300<br>\
      <input type='radio' id='s_model' name='s_model' onclick='d_Mod_form_part2_smodel_click(1);' value='2'>u-net";
    }else{
      t+="<input type='radio' id='s_model' name='s_model' onclick='d_Mod_form_part2_smodel_click(1);' value='1'>ssd300<br>\
      <input type='radio' id='s_model' name='s_model' onclick='d_Mod_form_part2_smodel_click(1);' value='2' checked>u-net";
    }
    t+="</label></p>\
    <p><label>Generated image size: *</br>\
      <div id='g_img_size_p'></div>\
    </label></p>\
    <p><label>Upload method: *<br>";
    switch(d_Mod_form_param.upload_way){
      case '1':
        t+="<input type='radio' id='upload_way' name='upload_way' onclick='d_Mod_form_part2_uploadway_click(1)' value='1' checked>Localhost<br>\
        <input type='radio' id='upload_way' name='upload_way' onclick='d_Mod_form_part2_uploadway_click(1)' value='2'>Google Drive<br>\
        <input type='radio' id='upload_way' name='upload_way' onclick='d_Mod_form_part2_uploadway_click(1)' value='3'>OneDrive<br>\
        <input type='radio' id='upload_way' name='upload_way' onclick='d_Mod_form_part2_uploadway_click(1)' value='4'>Dropbox<br>";
        break;
      case '2':
        t+="<input type='radio' id='upload_way' name='upload_way' onclick='d_Mod_form_part2_uploadway_click(1)' value='1'>Localhost<br>\
        <input type='radio' id='upload_way' name='upload_way' onclick='d_Mod_form_part2_uploadway_click(1)' value='2' checked>Google Drive<br>\
        <input type='radio' id='upload_way' name='upload_way' onclick='d_Mod_form_part2_uploadway_click(1)' value='3'>OneDrive<br>\
        <input type='radio' id='upload_way' name='upload_way' onclick='d_Mod_form_part2_uploadway_click(1)' value='4'>Dropbox<br>";
        break;
      case '3':
        t+="<input type='radio' id='upload_way' name='upload_way' onclick='d_Mod_form_part2_uploadway_click(1)' value='1'>Localhost<br>\
        <input type='radio' id='upload_way' name='upload_way' onclick='d_Mod_form_part2_uploadway_click(1)' value='2'>Google Drive<br>\
        <input type='radio' id='upload_way' name='upload_way' onclick='d_Mod_form_part2_uploadway_click(1)' value='3' checked>OneDrive<br>\
        <input type='radio' id='upload_way' name='upload_way' onclick='d_Mod_form_part2_uploadway_click(1)' value='4'>Dropbox<br>";
        break;
      default:
        t+="<input type='radio' id='upload_way' name='upload_way' onclick='d_Mod_form_part2_uploadway_click(1)' value='1'>Localhost<br>\
        <input type='radio' id='upload_way' name='upload_way' onclick='d_Mod_form_part2_uploadway_click(1)' value='2'>Google Drive<br>\
        <input type='radio' id='upload_way' name='upload_way' onclick='d_Mod_form_part2_uploadway_click(1)' value='3'>OneDrive<br>\
        <input type='radio' id='upload_way' name='upload_way' onclick='d_Mod_form_part2_uploadway_click(1)' value='4' checked>Dropbox<br>";
        break;
    }
    t+="</label></p>\
    <p><label>Compressed file(Please read the file request format)</label></p>\
      <div id = 'upload_way_p'></div>\
    <p><label>File description:</br>\
    <textarea id='file_description' name='file_description' style='width:315px;height:100px;'>"+d_Mod_form_param.file_description+"</textarea>\
    </label></p>\
    <!--Hidden input: Part 1. Form-->\
    <input type='hidden' name='authorizer_name' value='"+d_Mod_form_param.authorizer_name+"'>\
    <input type='hidden' name='authorized_institute' value='"+d_Mod_form_param.authorized_institute+"'>\
    <input type='hidden' name='phone_number' value='"+d_Mod_form_param.phone_number+"'>\
    <input type='hidden' name='e_mail' value='"+d_Mod_form_param.e_mail+"'>\
    <input type='hidden' name='p_o_download' value='"+d_Mod_form_param.p_o_download+"'>\
    <input type='hidden' name='authorization_description' value='"+d_Mod_form_param.authorization_description+"'>\
    <input type='hidden' name='action' value='mod_donate'>\
    <br><br>\
    <input type='submit' name='button' value='Submit' style='position:relative;top:15px;left:640px;font-size:16px;'>\
    </form>\
    <button onclick='d_Mod_form_part2_b_click()' style='position:relative;font-size:16px;top:-9px;left:570px;'>Back</button>";
    document.getElementById("d_form_p").innerHTML = t;
    d_Mod_form_part2_smodel_click(0);
    d_Mod_form_part2_uploadway_click(0);
  }
  //donate model: model use img size
  function d_Mod_form_part2_smodel_click(act){
    var ch_s_model = document.getElementById("donate_mod_form").s_model.value;
    var t;
    //if (load before value: ) else (load default value:)
    if((act!=1)&&(d_Mod_form_param.img_size!="")){
      //when have selected
      switch(ch_s_model){
        case '1':
          //alert(d_Mod_form_param.img_size);
          if(d_Mod_form_param.img_size=="300_300"){
              t="<input type='radio' id='img_size' name='img_size' value='300_300' checked>300x300";
          }
          break;
        default:
          //alert(d_Mod_form_param.img_size);
          if(d_Mod_form_param.img_size=="512_512"){
              t="<input type='radio' id='img_size' name='img_size' value='512_512' checked>512x512";
          }
      }
    }else{
      switch(ch_s_model){
        case '1':
          t="<input type='radio' id='img_size' name='img_size' value='300_300' checked>300x300";
          break;
        default:
          t="<input type='radio' id='img_size' name='img_size' value='512_512' checked>512x512";
      }
    }
    document.getElementById("g_img_size_p").innerHTML = t;
  }
  //donate mod: upload way use input data
  function d_Mod_form_part2_uploadway_click(act){
    var ch_upld_way = document.getElementById("donate_mod_form").upload_way.value;
    var t;
    //if (load before value: ) else (load default value:)
    if((act!=1)&&(d_Mod_form_param.upload_file!="")){
      switch(ch_upld_way){
        case '1':
          d_Mod_form_param.upload_file="";
          t="<label>Localhost: *</label></br>\
  				<input type='file' id='upload_file' name='upload_file' required>";
          break;
        case '2':
          t="<label>Google drive: *</label></br>\
  				<input id='upload_file' name='upload_file' value='"+d_Mod_form_param.upload_file+"' type = 'text' size = '30' style='width:315px;' required>";
          break;
        case '3':
          t="<label>Onedrive: *</label></br>\
  				<input id='upload_file' name='upload_file' value='"+d_Mod_form_param.upload_file+"' type = 'text' size = '30' style='width:315px;' required>";
          break;
        default:
          t="<label>Dropbox: *</label></br>\
  				<input id='upload_file' name='upload_file' value='"+d_Mod_form_param.upload_file+"' type = 'text' size = '30' style='width:315px;' required>";
      }
    }else{
      switch(ch_upld_way){
        case '1':
          t="<label>Localhost: *</label></br>\
  				<input type='file' id='upload_file' name='upload_file' required>";
          break;
        case '2':
          t="<label>Google drive: *</label></br>\
  				<input id='upload_file' name='upload_file' type = 'text' size = '30' style='width:315px;' required>";
          break;
        case '3':
          t="<label>Onedrive: *</label></br>\
  				<input id='upload_file' name='upload_file' type = 'text' size = '30' style='width:315px;' required>";
          break;
        default:
          t="<label>Dropbox: *</label></br>\
  				<input id='upload_file' name='upload_file' type = 'text' size = '30' style='width:315px;' required>";
      }
    }
    document.getElementById("upload_way_p").innerHTML = t;
  }
  //donate mod: Back button (Part.2 => Part.1)
  function d_Mod_form_part2_b_click(){
    //change value
    d_Mod_form_param.parasite_e_k = document.getElementById("parasite_e_k").value;
    d_Mod_form_param.s_model = document.getElementById("donate_mod_form").s_model.value;
    d_Mod_form_param.img_size = document.getElementById("donate_mod_form").img_size.value;
    d_Mod_form_param.upload_way = document.getElementById("donate_mod_form").upload_way.value;
    d_Mod_form_param.upload_file = document.getElementById("donate_mod_form").upload_file.value;
    d_Mod_form_param.file_description = document.getElementById("file_description").value;

    //check form fill condition(value null: accept)
    //check if agree to clear the localhost file path
    var v_val = true;
    if(d_Mod_form_param.upload_way=="1"&&d_Mod_form_param.upload_file!=""){
      v_val = confirm('For security reasons, the path of the local file will be cleared. Do you still want to go back to the previous page?');
    }
    //change form content
    if(v_val){
      d_Mod_form_part1_content();
    }
  }
  //Image instruction
  //donate Image => instruction onclick
  //ssd300 instruction onclick
  function d_Mod_intro_ssd300_b_click(){
    //change button style
    /*document.getElementById("d_Mod_intro_ssd300_b").style.backgroundColor ="#232323";
    document.getElementById("d_Mod_intro_ssd300_b").style.color ="white";
    document.getElementById("d_Mod_intro_u_net_b").style.backgroundColor ="#FFFFFF";
    document.getElementById("d_Mod_intro_u_net_b").style.color ="black";*/
    d_Mod_intro_ssd300_content();
  }
  //u-net instruction onclick
  function d_Mod_intro_u_net_b_click(){
    //change button style
    /*document.getElementById("d_Mod_intro_ssd300_b").style.backgroundColor ="#FFFFFF";
    document.getElementById("d_Mod_intro_ssd300_b").style.color ="black";
    document.getElementById("d_Mod_intro_u_net_b").style.backgroundColor ="#232323";
    document.getElementById("d_Mod_intro_u_net_b").style.color ="white";*/
    d_Mod_intro_u_net_content();
  }
  //ssd300 instruction content
  function d_Mod_intro_ssd300_content(){
    var t;
    t="<h1 style='font-size:22px;'>How to generate SSD300 model from own data set</h1>\
    <details open='open'>\
      <summary style='font-size:18px;'>Step 1. Generate data set</summary>\
      <h3>You can refer to the instructions on our web page to generate the parasite egg images and the corresponding label files.</h3>\
      <img src='./web_data/d_mod_ssd300_1.png' width='900px' height='500px' style=''>\
      <br>\
      <br>\
      <hr style='border: 3px solid #474747;border-radius: 5px;'>\
    </details>\
    <details open='open'>\
      <summary style='font-size:18px;'>Step 2. Preprocess data set</summary>\
      <h3>Preprocess the images and label files according to the input requirements of the program or training goals.(Refer to github: <a href='https://github.com/rykov8/ssd_keras' target='_blank'>\"A port of SSD: Single Shot MultiBox Detector to Keras framework.\"</a>)</h3>\
      <img src='./web_data/d_mod_ssd300_2.png' width='900px' height='500px' style=''>\
      <br>\
      <br>\
      <hr style='border: 3px solid #474747;border-radius: 5px;'>\
    </details>\
    <details open='open'>\
      <summary style='font-size:18px;'>Step 3. Generate model</summary>\
      <h3>Refer to github instructions to train and generate models on your own data.(Refer to github: <a href='https://github.com/rykov8/ssd_keras' target='_blank'>\"A port of SSD: Single Shot MultiBox Detector to Keras framework.\"</a>)</h3>\
      <img src='./web_data/d_mod_ssd300_3.png' width='900px' height='500px' style=''>\
      <br>\
      <br>\
      <hr style='border: 3px solid #474747;border-radius: 5px;'>\
    </details>\
    <details open='open'>\
      <summary style='font-size:18px;'>Step 4. Organize files and compress</summary>\
      <h3>Put the trained model and other documentation into the folder to generate a compressed file.</h3>\
      <img src='./web_data/d_mod_ssd300_4.png' width='900px' height='500px' style=''>\
      <br>\
      <br>\
      <br>\
    </details>\
    ";
    document.getElementById("d_instruction_p").innerHTML = t;
  }
  //u-net instruction content
  function d_Mod_intro_u_net_content(){
    var t;
    t="<h1 style='font-size:22px;'>How to generate U-net model from own data set</h1>\
    <details open='open'>\
      <summary style='font-size:18px;'>Step 1. Generate data set</summary>\
      <h3>You can refer to the instructions on our web page to generate the parasite egg images and the corresponding label files.</h3>\
      <img src='./web_data/d_mod_unet_1.png' width='900px' height='500px' style=''>\
      <br>\
      <br>\
      <hr style='border: 3px solid #474747;border-radius: 5px;'>\
    </details>\
    <details open='open'>\
      <summary style='font-size:18px;'>Step 2. Preprocess data set</summary>\
      <h3>Preprocess the images and label files according to the input requirements of the program or training goals.(Refer to github: <a href='https://github.com/zhixuhao/unet' target='_blank'>\"Implementation of deep learning framework -- Unet, using Keras\"</a>)</h3>\
      <img src='./web_data/d_mod_unet_2.png' width='900px' height='500px' style=''>\
      <br>\
      <br>\
      <hr style='border: 3px solid #474747;border-radius: 5px;'>\
    </details>\
    <details open='open'>\
      <summary style='font-size:18px;'>Step 3. Generate model</summary>\
      <h3>Refer to github instructions to train and generate models on your own data.(Refer to github: <a href='https://github.com/zhixuhao/unet' target='_blank'>\"Implementation of deep learning framework -- Unet, using Keras\"</a>)</h3>\
      <img src='./web_data/d_mod_unet_3.png' width='900px' height='500px' style=''>\
      <br>\
      <br>\
      <hr style='border: 3px solid #474747;border-radius: 5px;'>\
    </details>\
    <details open='open'>\
      <summary style='font-size:18px;'>Step 4. Organize files and compress</summary>\
      <h3>Put the trained models and other documentation into the folder to generate a compressed file.</h3>\
      <img src='./web_data/d_mod_unet_4.png' width='900px' height='500px' style=''>\
      <br>\
      <br>\
      <br>\
    </details>\
    ";
    document.getElementById("d_instruction_p").innerHTML = t;
  }
  </script>
</body>
</html>
