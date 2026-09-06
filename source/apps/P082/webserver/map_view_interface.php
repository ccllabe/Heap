<?php
  header("Content-Type: text/html; charset=utf-8");
  ini_set('memory_limit','4096M');
  session_start();
  $project = $_GET["project"];
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset = "utf-8">
	<title>HEAP</title>
  <link rel="stylesheet" href="menu_3_interface.css" type="text/css">
</head>

<body>
  <?php
	include 'menu_2_interface.php';
	?>
  <!--menu3-->
  <script type = "text/javascript">
  //get server content
  var project = <?php echo $project ?>;
  var project_folder_rootpath;
  var ori_imgs_size;
  var thumbnail_imgs_size;
  var img_fds;
  var img_fds_info;
  var cut_img_names;
  var lab_fds_info;
  var lab_fds;
  var place_records;
  var scale_bar_img_name;
  var imgmin_x = {attr:0};
  var imgmin_y = {attr:0};
  var imgfolder = {attr:''};
  var imgfolder_num = {attr:0};
  var scalebar_ch = {attr:1};
  var labfolder = {attr:''};
  //var cof_compare = {attr:49};
  var d_tool = ["Faster R-CNN (default)","Faster R-CNN (high quality image)","ssd300","u_net"];
  var cof_compare2 = {"Faster R-CNN (default)":49,"Faster R-CNN (high quality image)":49,"ssd300":70,"u_net":49};
  var color_array = ['#8B0000','#FF8C00','#FFFF00','#6B8E23','#008080','#483D8B','#4B0082','#C71585',
                     '#FF0000','#FFA500','#BDB76B','#556B2F','#008B8B','#6A5ACD','#800080','#DB7093',
                     '#B22222','#FFD700','#F0E68C','#808000','#5F9EA0','#7B68EE','#8B008B','#FF1493',
                     '#DC143C','#FF4500','#EEE8AA','#2E8B57','#20B2AA','#191970','#9932CC','#FF69B4',
                     '#CD5C5C','#FF6347','#FFDAB9','#3CB371','#00CED1','#000080','#9400D3','#FFB6C1',
                     '#F08080','#FF7F50','#FFE4B5','#8FBC8F','#48D1CC','#00008B','#8A2BE2','#FFC0CB',
                     '#930000','#D9006C','#AE00AE','#6F00D2','#003D79','#005757','#01814A','#007500',
                     '#64A600','#737300','#977C00','#BB5E00','#A23400','#743A3A','#707038','#3D7878',
                     '#FF5151','#FF95CA','#FF8EFF','#CA8EFF','#B9B9FF','#97CBFF','#BBFFFF','#ADFEDC',
                     '#A6FFA6','#CCFF80','#FFFF93','#FFE66F','#FFC78E','#8080C0','#AE57A4','#A3D1D1'];
  //sub_menu
  var submenu_width = {attr:0};
  var submenu_content = {attr:''};
  var menu_user_project_img_num;
  var menu_user_project_lab_num;
  var place_records_num = 0;
  var menu_count = 0;
  mapview_init();
  function mapview_init(){
    var mapview_ini_string = "<div id= 'map_view_load' class = 'loader' style = 'z-index:9999;position:relative;top:40%;left:40%;'></div>\
    <div id = 'sub_menu' style='position:absolute;left:0%;top:0%;height:100%;overflow:auto;'></div>\
    <div id = 'showpic' oncontextmenu='return false;' onmousewheel='scrollFunc();' style='position:absolute;top:7px;height:100%;'></div>";
    main_content_page.innerHTML = mapview_ini_string;
    document.body.style.background = "#000000";
    php_init(project);
  }
  function php_init(project){
    //alert(project);
    var ajax_string = "map_view_init_exe.php";
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
            project_folder_rootpath = data.project_folder_rootpath;
            ori_imgs_size = data.ori_imgs_size;
            thumbnail_imgs_size = data.thumbnail_imgs_size;
            img_fds = data.img_fds;
            img_fds_info = data.img_fds_info;
            cut_img_names = data.cut_img_names;
            lab_fds_info = data.lab_fds_info;
            lab_fds = data.lab_fds;
            place_records = data.place_records;
            scale_bar_img_name = data.scale_bar_img_name;
            //initial
            if(img_fds==null){
              menu_user_project_img_num=0;
            }
            else{
              menu_user_project_img_num=img_fds.length;
              imgfolder.attr=img_fds[0];
              imgfolder_num.attr = 0;
            }
            if(lab_fds==null){
              menu_user_project_lab_num=0;
            }
            else{
              var select = [];
              menu_user_project_lab_num=lab_fds.length;
              for (var i=0; i<menu_user_project_lab_num; i++)
              {
                  select.push(lab_fds[i]);
              }
              if(select.length==0&&menu_user_project_lab_num!=0){
                select.push(lab_fds);
              }
              labfolder.attr = select.join();
            }
            submenu_out();
            click_frame_choose();
            document.getElementById("map_view_load").style.display = "none";
          }
        } else {
          alert("發生錯誤: " + request.status);
        }
      }
    }
  }
  function submenu_out(){
    submenu_content.attr="<p><a onclick='submenu_in();'>&nbsp;⇦&nbsp;</a></p>";
    submenu_content.attr+="<div id = 'submenu_div1'><font>Select parameter:</font></div><div style='position:relative;font-size:16px;padding-left:5px;margin-bottom:-15px;'>Map view</div><br><br>\
    <fieldset>Description:\n<br>\
    <li style='padding-left:20px;text-indent:-18px;'>Use left menu to select pictures and experimental test results.</li>\
    <li style='padding-left:20px;text-indent:-18px;'>Click the button on the right or the keyboard direction button to move the viewing position.</li>\
    <li style='padding-left:20px;text-indent:-18px;'>Use the left mouse button and right mouse button to confirm and delete the result.</li>\
    </fieldset>\
    <!--little picture and control keyboard-->\
    <div id = 'move_button' style='position: relative;top: 0px;height:375px;'>\
      <div style='position:absolute;left:30%;'>\
        <div style='position:absolute;left:50px;top:10px;'>\
        <input type='button' style='width:25px;height:25px;' value='↑' onclick='movepic(imgmin_x,imgmin_y,1);'>\
        </div>\
        <div style='position:absolute;left:50px;top:35px;'>\
        <input type='button' style='width:25px;height:25px;' value='↓' onclick='movepic(imgmin_x,imgmin_y,2);'>\
        </div>\
        <div style='position:absolute;left:25px;top:35px;'>\
        <input type='button' style='width:25px;height:25px;' value='←' onclick='movepic(imgmin_x,imgmin_y,3);'>\
        </div>\
        <div style='position:absolute;left:75px;top:35px;'>\
        <input type='button' style='width:25px;height:25px;' value='→' onclick= 'movepic(imgmin_x,imgmin_y,4);'>\
        </div>\
      </div>\
      <div id = show_thumbnail style='position:absolute;left:20px;top:75px;'></div>\
    </div><ul>\
    <form action='map_view_interface.php?project="+project+"' method='post' id='map_view_form_"+project+"' name='map_view_form_"+project+"'>\
    <div id='submenu_div1_scale'>Scale bar image:<br><br>";
    if(scalebar_ch.attr>0){
      submenu_content.attr+="<input name='scalecheck' type='radio' value='1' onclick='click_scalebar_choose()' checked='checked'>Show\
      <input name='scalecheck' type='radio' value='0' onclick='click_scalebar_choose()'>Not show";
    }else{
      submenu_content.attr+="<input name='scalecheck' type='radio' value='1' onclick='click_scalebar_choose()'>Show\
      <input name='scalecheck' type='radio' value='0' onclick='click_scalebar_choose()' checked='checked'>Not show";
    }
    submenu_content.attr+="</div>\
    <div id='submenu_div1_conf'>Confidence<br>";
    //<li><input type = 'range' id = 'rangeinput_"+project+"' min = '0' max = '100' step = '1' value = '"+cof_compare.attr+"' onclick='click_confidence()'>"+cof_compare.attr+"</li>
    for(var u_conf in cof_compare2){
      submenu_content.attr+="<p style='text-align:left;'>"+u_conf+"</p>";
      submenu_content.attr+="<li><input type = 'range' id = 'rangeinput_"+project+"_"+u_conf+"' min = '0' max = '100' step = '1' value = '"+cof_compare2[u_conf]+"' onclick='click_confidence()'>"+cof_compare2[u_conf]+"</li>";
    }
    submenu_content.attr+="</div>\
    <div id='submenu_div1_ch' style='position:absolute;top:1048px;width:95%;'>Background Image:<br>";//height:calc(100% - 725px);
    for(var j=0;j<menu_user_project_img_num;j++){
      if(img_fds[j]!=imgfolder.attr){
        submenu_content.attr+="<li><input name = 'backchoose' type = 'radio' value = '"+img_fds[j]+"' onclick='click_img_choose()'><label>"+img_fds_info[j]+"</label></li>";
      }
      else{
        submenu_content.attr+="<li><input name = 'backchoose' type = 'radio' value = '"+img_fds[j]+"' onclick='click_img_choose()' checked='checked'><label>"+img_fds_info[j]+"</label></li>";
        imgfolder_num.attr = j;
      }
    }
    submenu_content.attr+="Lab:<br>";
    var select_folder_string=labfolder.attr.split(",");
    var showfolderplace=new Boolean(false);
    for(var j=0;j<menu_user_project_lab_num;j++){
      showfolderplace=false;
      for (var m = 0; m<select_folder_string.length; m++)
      {
        if(select_folder_string[m]==lab_fds[j])
        {
            showfolderplace=true;
        }
      }
      if (showfolderplace==true){
        submenu_content.attr+="<li style='background-color:"+color_array[j]+";'><input name = 'framechoose' type = 'checkbox' value = '"+lab_fds[j]+"' onclick='click_frame_choose()' checked='checked'><label>"+lab_fds_info[j][0]+"</label></li>";
      }else{
        submenu_content.attr+="<li style='background-color:"+color_array[j]+";'><input name = 'framechoose' type = 'checkbox' value = '"+lab_fds[j]+"' onclick='click_frame_choose()'><label>"+lab_fds_info[j][0]+"</label></li>";
      }
    }
    /*for(var j=0;j<menu_user_project_lab_num;j++){
      submenu_content.attr+="<li><label>"+lab_fds_info[j][0]+"</label><input name = 'framechoose' type = 'checkbox' value = '"+lab_fds[j]+"' onclick='click_frame_choose()' checked='checked'></li>";
    }*/
    submenu_content.attr+="</div></form></ul>";
    submenu_width.attr = 360;
    document.getElementById("sub_menu").style.width = submenu_width.attr+"px";
    document.getElementById("sub_menu").innerHTML = submenu_content.attr;
    document.getElementById("showpic").style.left = submenu_width.attr+"px";
    click_frame_choose();
  }
  function submenu_in(){
    submenu_content.attr="<p><a onclick='submenu_out();'>&nbsp;⇨&nbsp;</a></p>\
    <div style='transform: translate(-90px, 120px) rotate(270deg);width:200px;color:#aaa;font-size:12px;font-style:italic;font-weight:700;'>\
                   <a onclick='submenu_out()'>Step3. Select parameter</a></div>";
    submenu_width.attr = 20;
    document.getElementById("sub_menu").style.width = submenu_width.attr+"px";
    document.getElementById("sub_menu").innerHTML = submenu_content.attr;
    document.getElementById("showpic").style.left = submenu_width.attr+"px";
  }
  //page initial show
  //movepic(imgmin_x,imgmin_y,0);
  //click and reaction
  //range(confidence choose)
  function click_confidence()
  {
    for(var u_conf in cof_compare2){
      var rangeInput = document.getElementById("rangeinput_"+project+"_"+u_conf).value;
      cof_compare2[u_conf] = parseInt(rangeInput);
    }
    //var rangeInput = document.getElementById("rangeinput_"+project).value;
    //alert(rangeInput);
    //cof_compare.attr = parseInt(rangeInput);
    submenu_out();
    movepic(imgmin_x,imgmin_y,0);
  }
  //Radio(image choose)
  function click_img_choose()
  {
    var form_name = document.getElementById("map_view_form_"+project);
    if (form_name.backchoose.value != undefined)
    {
        imgfolder.attr = form_name.backchoose.value;
        for (var i = 0; i<menu_user_project_img_num; i++)
        {
          if (img_fds[i]==imgfolder.attr)
          {
            imgfolder_num.attr=i;
            break;
          }
        }
        scalebar_ch.attr=1;
        movepic(imgmin_x,imgmin_y,0);
    }
  }
  var scrollFunc = function (e) {
      var wheelvalue;
      e = e || window.event;
      if (e.wheelDelta) {//IE/Opera/Chrome
          wheelvalue = e.wheelDelta;
      } else if (e.detail) {//Firefox
          wheelvalue = e.detail;
      }
      if(wheelvalue>0&&imgfolder_num.attr>0){
        imgfolder_num.attr-=1;
      }else if(wheelvalue<0&&imgfolder_num.attr<menu_user_project_img_num-1){
        imgfolder_num.attr+=1;
      }
      imgfolder.attr = img_fds[imgfolder_num.attr];
      scalebar_ch.attr=1;
      submenu_out();
      movepic(imgmin_x,imgmin_y,0);
      //ScrollText(direct);
  }
  /*註冊事件*/
  if (document.addEventListener) {
      document.addEventListener('DOMMouseScroll', scrollFunc, false);
  }//W3C
  //Multiple selection(frame choose)
  function click_frame_choose()
  {
    var form_name = document.getElementById("map_view_form_"+project);
    var obj = new Array();
    obj = form_name.framechoose;
    var obj_length;
    if(obj!=null){
      obj_length = obj.length;
    }else{
      obj_length = 0;
    }
    var select = [];
    for (var i=0; i<obj_length; i++)
    {
        if (obj[i].checked)
        {
          select.push(obj[i].value);
        }
    }
    if(select.length==0&&obj_length!=0){
      select.push(obj.value);
    }
    labfolder.attr = select.join();
    movepic(imgmin_x,imgmin_y,0);
  }
  //click if show scale bar image
  function click_scalebar_choose(){
    var form_name = document.getElementById("map_view_form_"+project);
    scalebar_ch.attr = form_name.scalecheck.value;
    movepic(imgmin_x,imgmin_y,0)
  }
  //function key_click_way(up,down,left,right)
  function key_click_way() {
  	//alert("Key code = " + event.keyCode);
  	if (event.keyCode==38) {
  		movepic(imgmin_x,imgmin_y,1);
  	} else if (event.keyCode==40) {
  		movepic(imgmin_x,imgmin_y,2);
  	} else if (event.keyCode==37) {
  		movepic(imgmin_x,imgmin_y,3);
  	} else if (event.keyCode==39) {
  		movepic(imgmin_x,imgmin_y,4);
  	}
  }
  document.onkeydown=key_click_way;
  //function click_chos_img()
  function movepic(imgmin_x,imgmin_y,way){
    var showpicplace = document.getElementById("showpic");
    var showpic_array = new Array();
    var showpic_array_len = 0;
    var fnstring_cut = new Array();
    var prstring_cut = new Array();
    var ori_w;
    var ori_h;
    var imgstring = "<div id='title_n' style='position:relative;top:0px;left:0px;z-index:9999;'>Map&nbsp;view</div>";
    if(menu_user_project_img_num==0){
      return;
    }
    //choose folder pic
    for (var i = 0; i<menu_user_project_img_num; i++)
    {
      if (img_fds[i]==imgfolder.attr)
      {
        showpic_array=cut_img_names[i];
        ori_w = ori_imgs_size[i][0];
        ori_h = ori_imgs_size[i][1];
      }
    }
    if(showpic_array != []){
      showpic_array_len = showpic_array.length;
    }
    //up,down,left,right
    if (way==1&&imgmin_y.attr>0)
    {
      imgmin_y.attr -=100;
    }
    if (way==2&&imgmin_y.attr<(ori_h-900))
    {
      imgmin_y.attr +=100;
    }
    if (way==3&&imgmin_x.attr>0)
    {
      imgmin_x.attr -=100;
    }
    if (way==4&&imgmin_x.attr<(ori_w-900))
    {
      imgmin_x.attr +=100;
    }
    //show every pic
    for (var i = 0; i<showpic_array_len; i++)
    {
      fnstring_cut=showpic_array[i].split(".");
      //if (picmax_x>=fnstring_cut[2]&&picmax_y>=fnstring_cut[3])
      if (imgmin_x.attr+900>=fnstring_cut[2]&&imgmin_y.attr+900>=fnstring_cut[3]&&imgmin_x.attr-100<=fnstring_cut[2]&&imgmin_y.attr-100<=fnstring_cut[3])
      {
        //document.writeln(fnstring_cut[2])
        var x = parseInt(fnstring_cut[2])-imgmin_x.attr;
        var y = parseInt(fnstring_cut[3])-imgmin_y.attr;
        //show part
        if (imgmin_x.attr+800>=fnstring_cut[2]&&imgmin_y.attr+800>=fnstring_cut[3]&&imgmin_x.attr<=fnstring_cut[2]&&imgmin_y.attr<=fnstring_cut[3]){
          //document.writeln("<img src = '../test/"+cut_img_names[i]+"' width = '100' height = '100' style = 'position:absolute;left:"+x+"px;top:"+y+"px'/>")
          imgstring=imgstring + "<img src = '"+project_folder_rootpath+"Image/"+imgfolder.attr+"/100_100_imgs/"+showpic_array[i]+"' width = '100' height = '100' style = 'position:absolute;left:"+x+"px;top:"+y+"px'/>";
        }else{
          //pre-load part
          imgstring=imgstring + "<img src = '"+project_folder_rootpath+"Image/"+imgfolder.attr+"/100_100_imgs/"+showpic_array[i]+"' width = '100' height = '100' style = 'display:none;position:absolute;left:"+x+"px;top:"+y+"px'/>";
        }
      }
    }
    //imgstring=imgstring + "<div style='border:2px red solid;width:42px;height:74px;position:absolute;left:186px;top:157px;z-index:1;'></div>"
    /*for (var i = 0; i<place_records.length; i++)
    {
      draw_framediv(place_records[i],'red',imgmin_x.attr,imgmin_y.attr)
    }*/
    //var color_array = ['red','yellow','blue','green','white'];
    //var color_array = ['#8B0000','#FF8C00','#FFFF00','#6B8E23','#008080','#483D8B','#4B0082','#C71585',
    /*var color_array = ['#8B0000','#FF8C00','#FFFF00','#6B8E23','#008080','#483D8B','#4B0082','#C71585',
                       '#FF0000','#FFA500','#BDB76B','#556B2F','#008B8B','#6A5ACD','#800080','#DB7093',
                       '#B22222','#FFD700','#F0E68C','#808000','#5F9EA0','#7B68EE','#8B008B','#FF1493',
                       '#DC143C','#FF4500','#EEE8AA','#2E8B57','#20B2AA','#191970','#9932CC','#FF69B4',
                       '#CD5C5C','#FF6347','#FFDAB9','#3CB371','#00CED1','#000080','#9400D3','#FFB6C1',
                       '#F08080','#FF7F50','#FFE4B5','#8FBC8F','#48D1CC','#00008B','#8A2BE2','#FFC0CB'];*/
    var select_folder_string=labfolder.attr.split(",");
    var select_folder_string_len=0;
    if(place_records!=null){
      place_records_num = place_records.length;
    }else{
      place_records_num = 0;
    }
    if(select_folder_string!=null){
      select_folder_string_len = select_folder_string.length;
    }else{
      select_folder_string_len = 0;
    }
    //var select_folder_string=new Array();
    //select_folder_string=labfolder.attr.split(",");
    //select_folder_string[0]=labfolder.attr;
    var showfolderplace=new Boolean(false);
    for (var i = 0; i<place_records_num; i++)
    {
      showfolderplace=false;
      for (var m = 0; m<select_folder_string_len; m++)
      {
        if(select_folder_string[m]==lab_fds[i])
        {
            showfolderplace=true;
        }
      }
      if(showfolderplace==true)
      {
        var place_records_i_len = 0;
        if(place_records[i]!=null){
          place_records_i_len = place_records[i].length;
        }
        //decide which tool conf
        for(var u_conf in cof_compare2){
          if(d_tool[lab_fds_info[i][1]]==u_conf){
            var conf_v = cof_compare2[u_conf];
          }
        }
        for(var j = 0; j<place_records_i_len;j++)
        {
          draw_framediv(place_records[i][j],color_array[i],imgmin_x.attr,imgmin_y.attr,conf_v, i, j);
        }
      }
    }
    if(scalebar_ch.attr>0){
      draw_scale_bar_img();
    }
    showpicplace.innerHTML = imgstring;
    function draw_framediv(filen_string,framecolor,x_distmin,y_distmin,confmin,labNumber,lineNumber){
      prstring_cut=filen_string.split(",");
      //if (picmax_x>=fnstring_cut[2]&&picmax_y>=fnstring_cut[3])
      if (x_distmin+900>=prstring_cut[0]&&y_distmin+900>=prstring_cut[1]&&x_distmin<=prstring_cut[2]&&y_distmin<=prstring_cut[3]&&(confmin<=prstring_cut[4]))
      {
        //document.writeln(fnstring_cut[2])
        var x_fmin = parseInt(prstring_cut[0])-x_distmin;
        var y_fmin = parseInt(prstring_cut[1])-y_distmin;
        var x_fmax = parseInt(prstring_cut[2])-x_distmin;
        var y_fmax = parseInt(prstring_cut[3])-y_distmin;
        var framestring = '';
        if (x_fmin<0)
        {
          x_fmin=0;
        }
        if (y_fmin<0)
        {
          y_fmin=0;
        }
        if (x_fmax>900)
        {
          x_fmax=900;
        }
        if (y_fmax>900)
        {
          y_fmax=900;
        }
        f_widt=x_fmax-x_fmin;
        f_high=y_fmax-y_fmin;
        if(prstring_cut[5]!=null){
          if(prstring_cut[5]==1){
            framestring += "<div style='border:6px "+framecolor+" solid;border-style:solid;width:"+f_widt+"px;height:"+f_high+"px;position:absolute;left:"+x_fmin+"px;top:"+y_fmin+"px;z-index:1;' onmousedown='frame_click(event,"+labNumber+","+lineNumber+");' title='lineNumber: "+lineNumber+lab_fds[labNumber]+" ("+prstring_cut[0]+","+prstring_cut[1]+")'></div>";
          }
        }else{
          framestring += "<div style='border:6px "+framecolor+" solid;border-style:dashed;width:"+f_widt+"px;height:"+f_high+"px;position:absolute;left:"+x_fmin+"px;top:"+y_fmin+"px;z-index:1;' onmousedown='frame_click(event,"+labNumber+","+lineNumber+");' title='lineNumber: "+lineNumber+lab_fds[labNumber]+" ("+prstring_cut[0]+","+prstring_cut[1]+")'></div>";
        }
        //border-style:dashed;
        //document.writeln("<img src = '../test/"+cut_img_names[i]+"' width = '100' height = '100' style = 'position:absolute;left:"+x+"px;top:"+y+"px'/>")
        imgstring=imgstring + framestring;
      }
    }
    function draw_scale_bar_img(){
      imgstring+="<img style='position:absolute;top:900px;' src = '"+scale_bar_img_name[imgfolder_num.attr]+"'/>";
      //alert(scale_bar_img_name[imgfolder_num.attr]);
    }
    thumbnail_show();
  }
  //function thumbnail_show
  function thumbnail_show(){
    var thumbnail_content = {attr:''};
    thumbnail_content.attr = "<img src = '"+project_folder_rootpath+"Image/"+imgfolder.attr+"/thumbnail.png'/>";
    dot_thumbnail_show(thumbnail_content);
    document.getElementById("show_thumbnail").innerHTML = thumbnail_content.attr;
  }
  //function accord lab draw thumbnail dot
  function dot_thumbnail_show(thumbnail_content){
    var ori_h,ori_w;
    for (var i = 0; i<menu_user_project_img_num; i++)
    {
      if (img_fds[i]==imgfolder.attr)
      {
        ori_w = ori_imgs_size[i][0];
        th_w = thumbnail_imgs_size[i][0];
        ori_h = ori_imgs_size[i][1];
        th_h = thumbnail_imgs_size[i][1];
      }
    }
    //accord choose lab
    /*var color_array = ['#8B0000','#FF8C00','#FFFF00','#6B8E23','#008080','#483D8B','#4B0082','#C71585',
                       '#FF0000','#FFA500','#BDB76B','#556B2F','#008B8B','#6A5ACD','#800080','#DB7093',
                       '#B22222','#FFD700','#F0E68C','#808000','#5F9EA0','#7B68EE','#8B008B','#FF1493',
                       '#DC143C','#FF4500','#EEE8AA','#2E8B57','#20B2AA','#191970','#9932CC','#FF69B4',
                       '#CD5C5C','#FF6347','#FFDAB9','#3CB371','#00CED1','#000080','#9400D3','#FFB6C1',
                       '#F08080','#FF7F50','#FFE4B5','#8FBC8F','#48D1CC','#00008B','#8A2BE2','#FFC0CB'];*/
    var select_folder_string=labfolder.attr.split(",");
    var showfolderplace=new Boolean(false);
    var select_folder_string_len=0;
    if(place_records!=null){
      place_records_num = place_records.length;
    }else{
      place_records_num = 0;
    }
    if(select_folder_string!=null){
      select_folder_string_len = select_folder_string.length;
    }else{
      select_folder_string_len = 0;
    }
    for (var i = 0; i<place_records_num; i++){
      //select lab map user lab
      showfolderplace=false;
      for (var m = 0; m<select_folder_string_len; m++)
      {
        if(select_folder_string[m]==lab_fds[i])
        {
            showfolderplace=true;
        }
      }
      //find in user lab then draw point
      if(showfolderplace==true)
      {
        var place_records_i_len = 0;
        if(place_records[i]!=null){
          place_records_i_len = place_records[i].length;
        }
        //decide which tool conf
        for(var u_conf in cof_compare2){
          if(d_tool[lab_fds_info[i][1]]==u_conf){
            var conf_v = cof_compare2[u_conf];
          }
        }
        for(var j = 0; j<place_records_i_len;j++)
        {
          draw_thumbnail_div(place_records[i][j],color_array[i], i, j,thumbnail_content,ori_w,ori_h,th_w,th_h,conf_v);
        }

      }
    }
    draw_thumbnail_range(thumbnail_content,ori_w,ori_h,th_w,th_h);
  }
  //draw one thumbnail dot
  function draw_thumbnail_div(filen_string,framecolor,labNumber,lineNumber,thumbnail_content,ori_w,ori_h,th_w,th_h,confmin){
    var prstring_cut=filen_string.split(",");
    if (confmin<=prstring_cut[4]){
      var x_fmin = parseInt(prstring_cut[0])*th_w/ori_w;
      var y_fmin = parseInt(prstring_cut[1])*th_h/ori_h;
      if(prstring_cut[5]!=null){
        if(prstring_cut[5]==1){
          thumbnail_content.attr += "<div style='border: 1px"+framecolor+" solid;border-style:solid;width:1px;height:1px;position:absolute;left:"+x_fmin+"px;top:"+y_fmin+"px;z-index:2;' onclick='thumb_click("+prstring_cut[0]+","+prstring_cut[1]+");' title='lineNumber: "+lineNumber+lab_fds[labNumber]+" ("+prstring_cut[0]+","+prstring_cut[1]+")'></div>";
        }
      }else{
        thumbnail_content.attr += "<div style='border: 1px"+framecolor+" solid;border-style:dashed;width:1px;height:1px;position:absolute;left:"+x_fmin+"px;top:"+y_fmin+"px;z-index:2;' onclick='thumb_click("+prstring_cut[0]+","+prstring_cut[1]+");' title='lineNumber: "+lineNumber+lab_fds[labNumber]+" ("+prstring_cut[0]+","+prstring_cut[1]+")'></div>";
      }
    }
  }
  function draw_thumbnail_range(thumbnail_content,ori_w,ori_h,th_w,th_h){
    var x_fmin = imgmin_x.attr*th_w/ori_w;
    var y_fmin = imgmin_y.attr*th_h/ori_h;
    var x_width = 900*th_w/ori_w;
    var y_height = 900*th_h/ori_h;
    thumbnail_content.attr += "<div style='border: 2px red solid;border-style:solid;width:"+x_width+"px;height:"+y_height+"px;position:absolute;left:"+x_fmin+"px;top:"+y_fmin+"px;z-index:2;'></div>";
  }
  function thumb_click(f_l,f_t){
    var x_fmin = parseInt(f_l)-400;
    var y_fmin = parseInt(f_t)-400;
    if (y_fmin<0)
    {
      imgmin_y.attr =0;
    }else{
      imgmin_y.attr =parseInt(y_fmin/100)*100;
    }
    if (x_fmin<0)
    {
      imgmin_x.attr =0;
    }else{
      imgmin_x.attr =parseInt(x_fmin/100)*100;
    }
    movepic(imgmin_x,imgmin_y,0);
  }
  function frame_click(event,labNumber,lineNumber){
    if (event.button == 0) {
      checked = 1;
    }
    if (event.button == 2) {
      checked = -1;
      //document.onmousedown = block;
    }
    //write to array
    var pstring_cut;
    var debug_string='';
    for (var lab_fds_n in lab_fds){
      if(lab_fds_n==labNumber){
        debug_string += labNumber;
        prstring_cut=place_records[lab_fds_n][lineNumber].split(",");
        if(prstring_cut[5]!=null){
          place_records[lab_fds_n][lineNumber] = prstring_cut[0]+','+prstring_cut[1]+','+prstring_cut[2]+','+prstring_cut[3]+','+prstring_cut[4]+','+checked;
        }else{
          place_records[lab_fds_n][lineNumber] = place_records[lab_fds_n][lineNumber]+','+checked;
        }
        //alert(place_records[lab_fds_n][lineNumber]);
      }
    }
    movepic(imgmin_x,imgmin_y,0);
    //alert(debug_string);
    var request = new XMLHttpRequest();
    request.open("POST", "checked_exe.php");
    var data = "project_folder_rootpath="+project_folder_rootpath+"&lab=" + lab_fds[labNumber] + "&lineNumber=" + lineNumber + "&checked=" + checked;
    request.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    request.send(data);
    request.onreadystatechange = function() {
      if (request.readyState === 4) {
          if (request.status === 200) {
            //alert("ok" + request.status);
          } else {
            alert("發生錯誤" + request.status);
          }
      }
    }
    //write to array(result_check_content_array)
    //alert(lab_ids);
  }
  </script>
</body>
</html>
