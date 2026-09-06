<?php
  header("Content-Type: text/html; charset=utf-8");
  set_time_limit(0);
  session_start();
  $project = $_GET["project"];
  $lab = $_GET['lab'];
  $cof = $_GET['cof'];
  $show_check = $_GET['show_check'];
  #$project_folder_rootpath = "/user/".$_SESSION['user_id']."/".$project."/";
  $project_folder_rootpath = "./user/".$_SESSION['user_id']."/".$project."/";
  #$project_folder_path = $_SERVER['DOCUMENT_ROOT'].$project_folder_rootpath;
  $project_folder_path = $project_folder_rootpath;

  //list all parasite egg place(read place.txt)
  $place_records = [];
  $lab_folder_path = $project_folder_path."Lab/";
  $lab_fds =  array_map('basename', glob($lab_folder_path."*", GLOB_ONLYDIR));
  for($i=0;$i<count($lab_fds);$i++)
  {
	$finish_json_path = $lab_folder_path.$lab_fds[$i]."/running.json";
	if(is_file($finish_json_path)){
		$txt_path = $lab_folder_path.$lab_fds[$i]."/place.txt";
		$f = fopen($txt_path,'r');
		while ($line = fgets($f)) {
			$line = str_replace(array("\r", "\n", "\r\n", "\n\r"), '', $line);
			$place_records[$i][] = $line;
		}
		fclose($f);
	}
  }
?>

<!DOCTYPE html>
<html>
<head>
	<meta charset = "utf-8">
	<title>Parasite egg identification</title>
</head>

<body onContextMenu="return false;">
  <!--<iframe src="demo_iframe.htm" style="position:absolute;left:300px;top:40px"></iframe>-->
  <!--<div id = "showpic" style="position:absolute;left:300px;top:40px"></div>-->
  <div id = "showpic"></div>
  <script type = "text/javascript">
  var project = <?php echo $project ?>;
  var project_folder_rootpath = "<?php echo $project_folder_rootpath ?>";
  var lab_fds = <?php echo json_encode($lab_fds) ?>;
  var place_records = <?php echo json_encode($place_records) ?>;
  var labfolder = {attr:<?php echo $lab;?>};
  var cof = {attr:<?php echo $cof;?>};
  var show_check = {attr:<?php echo $show_check;?>};
  var color_array = ['#8B0000','#FF8C00','#FFFF00','#6B8E23','#008080','#483D8B','#4B0082','#C71585',
                     '#FF0000','#FFA500','#BDB76B','#556B2F','#008B8B','#6A5ACD','#800080','#DB7093',
                     '#B22222','#FFD700','#F0E68C','#808000','#5F9EA0','#7B68EE','#8B008B','#FF1493',
                     '#DC143C','#FF4500','#EEE8AA','#2E8B57','#20B2AA','#191970','#9932CC','#FF69B4',
                     '#CD5C5C','#FF6347','#FFDAB9','#3CB371','#00CED1','#000080','#9400D3','#FFB6C1',
                     '#F08080','#FF7F50','#FFE4B5','#8FBC8F','#48D1CC','#00008B','#8A2BE2','#FFC0CB'];
  //var test = funcdefind(3);
  //alert(test(1));
  iframe_show();
  function iframe_show(){
    var show_string= "";
    var p_numb;
    for (var i = 0; i<lab_fds.length; i++){
      if(lab_fds[i]==labfolder.attr){
        p_numb=i;
        break;
      }
    }
    show_string += "<table border='1' style='border-collapse: collapse;table-layout: fixed;'>";
    var count = 0;
    var txt_cut_array;
    var use_condition = funcdefind(show_check.attr);
    var td_style;
    for (var i = 0; i<place_records[p_numb].length; i++){
      if(count%10<1){
        show_string += "<tr>";
      }
      txt_cut_array=place_records[p_numb][i].split(",");
      //show ask
      if(parseInt(cof.attr)<parseInt(txt_cut_array[4])){
        if(use_condition(txt_cut_array[5])){
          if(txt_cut_array[5]==1){
            td_style = "style='border:3px "+color_array[p_numb]+" solid;'";
          }else{
            td_style="";
          }
          show_string += "<td align='center' valign='center' style='width:100px;'><img src = '"+project_folder_rootpath+"Lab/"+labfolder.attr+"/target/target."+i+".png' onmousedown='img_click(event,"+p_numb+","+i+");' title='lineNumber: "+i+",lab:"+lab_fds[p_numb]+" ("+txt_cut_array[0]+","+txt_cut_array[1]+")' "+td_style+"/></td>";
          count+=1;
        }
        /*if(txt_cut_array[5]!=null&&txt_cut_array[5]==1){
          //if(txt_cut_array[5]==1){
            show_string += "<td style='width:100px'><img src = '"+project_folder_rootpath+"Lab/"+labfolder.attr+"/target/target."+i+".png' onmousedown='img_click(event,"+p_numb+","+i+");' title='lineNumber: "+i+",lab:"+lab_fds[p_numb]+" ("+txt_cut_array[0]+","+txt_cut_array[1]+")'/></td>";
            count+=1;
          //}
        }else{
          show_string += "<td style='width:100px;opacity:0.6;'><img src = '"+project_folder_rootpath+"Lab/"+labfolder.attr+"/target/target."+i+".png' onmousedown='img_click(event,"+p_numb+","+i+");' title='lineNumber: "+i+",lab:"+lab_fds[p_numb]+" ("+txt_cut_array[0]+","+txt_cut_array[1]+")'/></td>";
          count+=1;style="border:3px #cccccc solid;"
        }*/
      }
      if(count%10>9){
        show_string += "</tr>";
      }
    }
    show_string += "</table>";
    showpic.innerHTML = show_string;
  }
  function funcdefind(check){
    var func;
    switch (check) {
      case 1:
        func = function(str){return true;};
        break;
      case 2:
        func = function(str){return str==null;};
        break;
      case 3:
        func = function(str){return str==1;};
        break;
      case 4:
        func = function(str){return str==-1;};
        break;
    }
    return func;
  }
  function img_click(event,labNumber,lineNumber){
    if (event.button == 0) {
      checked = 1;
    }
    if (event.button == 2) {
      checked = -1;
    }
    //write to array
    var pstring_cut;
    for (var lab_fds_n in lab_fds){
      if(lab_fds_n==labNumber){
        prstring_cut=place_records[lab_fds_n][lineNumber].split(",");
        if(prstring_cut[5]!=null){
          place_records[lab_fds_n][lineNumber] = prstring_cut[0]+','+prstring_cut[1]+','+prstring_cut[2]+','+prstring_cut[3]+','+prstring_cut[4]+','+checked;
        }else{
          place_records[lab_fds_n][lineNumber] = place_records[lab_fds_n][lineNumber]+','+checked;
        }
        //alert(place_records[lab_fds_n][lineNumber]);
      }
    }
    //show table
    iframe_show();
    //send to change txt
    var request = new XMLHttpRequest();
    request.open("POST", "list_checked_exe.php");
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
  }
  </script>
</body>
</html>
