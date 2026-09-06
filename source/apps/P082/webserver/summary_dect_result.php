<?php
  header("Content-Type: text/html; charset=utf-8");
  set_time_limit(0);
  session_start();
  $project = $_GET["project"];
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
    $txt_path = $lab_folder_path.$lab_fds[$i]."/place.txt";
    if(is_file($txt_path)){
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
  <!--pie-->
  <script src="https://cdn.plot.ly/plotly-latest.min.js"></script>
</head>

<body>
  <!--<iframe src="demo_iframe.htm" style="position:absolute;left:300px;top:40px"></iframe>-->
  <!--<div id = "showresult" style="position:absolute;left:300px;top:40px"></div>-->
  <div id = "showresult"></div>
  <script type = "text/javascript">
  var project = <?php echo $project ?>;
  var project_folder_rootpath = "<?php echo $project_folder_rootpath ?>";
  var lab_fds = <?php echo json_encode($lab_fds) ?>;
  var place_records = <?php echo json_encode($place_records) ?>;
  var all=0;
  var undetermine=0;
  var confirmed=0;
  var removed=0;
  //if(place_records!=null){
  iframe_show();
  if (all!=0){
    pie_draw();
  }
  function iframe_show(){
    var show_string= "";
    var txt_cut_array;
    for (var i in place_records){
      for (var j in place_records[i]){
        txt_cut_array=place_records[i][j].split(",");
        if(txt_cut_array[5]==null){
          undetermine+=1;
        }
        if(txt_cut_array[5]==1){
          confirmed+=1;
        }
        if(txt_cut_array[5]==-1){
          removed+=1;
        }
      }
    }
    all=undetermine+confirmed+removed;
    show_string+="<p>Confirmed Eggs: "+confirmed+" &nbsp Undetermine Eggs: "+undetermine+"<br>\
    Removed Eggs: "+removed+" &nbsp All Eggs: "+all+" </p><br>\
    <div style='text-align:center;' id='pie_graph'></div>";
    showresult.innerHTML = show_string;
  }
  function pie_draw(){
    var data = [{
      type: "pie",
      values: [confirmed, undetermine, removed],
      labels: ["Confirmed Eggs", "Undetermine Eggs", "Removed Eggs"],
      textinfo: "label+percent",
      textposition: "outside",
      automargin: true
    }];
    var layout = {
      height: 200,
      width: 800,
      margin: {"t": 0, "b": 0, "l": 100, "r": 0},
      showlegend: true
    };
    Plotly.newPlot('pie_graph', data, layout);
  }
  </script>
</body>
</html>
