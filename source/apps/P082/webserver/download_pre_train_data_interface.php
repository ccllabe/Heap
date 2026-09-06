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
  //$page = $_GET["page"];
  //hi user
  $user_description = file_get_contents($user_folder_path."/description.json");
  $user_description = json_decode($user_description);
  $user_name = $user_description->{'full name'};
  //

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
<body style='background:#E5E7E9;'>
  <link rel="stylesheet" href="menu_interface.css" type="text/css">
  <div id = "net_title">
    <div>
      <font>HEAP</font> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
      <a href = 'home_interface.php'>Home</a>&nbsp;&nbsp;|&nbsp;&nbsp;
      <a href = 'project_manage_interface.php'>Project Management</a>&nbsp;&nbsp;|&nbsp;&nbsp;
      <a href = 'project_create_interface.php'>Create Project</a>&nbsp;&nbsp;|&nbsp;&nbsp;
      <a href = 'donate_data_interface.php?page=donate_img'>Donate Data</a>&nbsp;&nbsp;|&nbsp;&nbsp;
      <a href = #>Download Pre-train Data</a>&nbsp;&nbsp;|&nbsp;&nbsp;
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
  init_content();
  function init_content(){
    main_content();
    ssd300_table_content();
    u_net_table_content();
  }
  function main_content(){
    var t;
    t="<div style='font-size:24px;text-align:left;padding-left:5px;font-style:italic;font-weight:550;'>Download Pre-train Data</div>\
    <br>\
    <div style='position:relative;width:1600px;padding-left:5px;font-size:14px;font-weight:700;'>The platform helps users find the eggs more quickly by predicting the location of the parasite eggs. The implementation method is to feed the image to a pre-trained model to find the possible position of the parasite eggs. These pre-trained models need to prepare images of parasite eggs, but obtaining these images is not easy and requires a lot of manpower to label. Here we provide data sets and trained models used by the platform for training.</div>\
    <table>\
    <tr><td>\
      <table>\
        <tr><td><h1>SSD300</h1></td></tr>\
        <tr><td><div style='position:relative;width:1600px;font-size:14px;font-weight:700;'>Use a microscope to scan different slides with corresponding parasite eggs with different depth of fields to generate slide images. From these images, cut out a 300*300 image data set containing parasite eggs. Use <a href='https://github.com/tzutalin/labelImg' target='_blank'>labelImg</a> (version: 1.8.3) to label these pictures. Refer to the instructions on github to train and generate data models. (See github: <a href='https://github.com/rykov8/ssd_keras' target='_blank'>\"A port of SSD: Single Shot MultiBox Detector to Keras framework.\"</a>)<br></div></td></tr>\
        <tr><td><div id='ssd300_table_p'></div></td></tr>\
      </table>\
    </td></tr>\
    <tr><td>\
      <table>\
        <tr><td><h1>U-net</h1></td></tr>\
        <tr><td><div style='position:relative;width:1600px;font-size:14px;font-weight:700;'>Use a microscope to scan different slides with corresponding parasite eggs with different depth of fields to generate slide images. Cut out a 512*512 image data set containing parasite eggs from these images. Use <a href='https://github.com/wkentaro/labelme' target='_blank'>labelme</a> (version: 3.16.1) to label these pictures. Refer to the instructions on github to train and generate data models. (See github: <a href='https://github.com/zhixuhao/unet' target='_blank'>\"Implementation of deep learning framework -- Unet, using Keras\"</a>)<br></div></td></tr>\
        <tr><td><div id='u_net_table_p'></div></td></tr>\
      </table>\
    </td></tr>\
    </table>";
    document.getElementById("main_content1_page").innerHTML = t;
  }
  function ssd300_table_content(){
    var t;
    t="<table id = 'ssd300_table' border = '1' style='width:1600px;'>\
    <thead style='background-color:#777;font-size:14px;font-weight:700;font-style:italic;color:#cccccc;'>\
      <tr>\
        <th>Parasite egg category</th>\
        <th>Slide name</th>\
        <th>depth of fields(μm)</th>\
        <th>Number of images</th>\
        <th>Number of training sets</th>\
        <th>Image download</th>\
        <th>Label download</th>\
        <th>Model download</th>\
      </tr>\
    </thead>\
    <tbody>\
      <tr>\
      <td><font style='font-style:italic;'>Trichuris trichiura</font> egg</td>\
      <td>original</td>\
      <td>110</td>\
      <td>12</td>\
      <td>340</td>\
      <td><a style='' href='pre_train_data_download.php?model=SSD300&egg_type=Trichuris_trichiura_egg&slide_name=original&zip_file=300_300_cut_img'><button>Download</button></a></td>\
      <td><a style='' href='pre_train_data_download.php?model=SSD300&egg_type=Trichuris_trichiura_egg&slide_name=original&zip_file=300_300_rec_xml'><button>Download</button></a></td>\
      <td><a style='' href='pre_train_data_download.php?model=SSD300&egg_type=Trichuris_trichiura_egg&slide_name=original&zip_file=model'><button>Download</button></a></td>\
      </tr><tr>\
      <td><font style='font-style:italic;'>Ascaris lumbricoides</font> egg(fertilized)</td>\
      <td>(Csp-1)</td>\
      <td>80</td>\
      <td>9</td>\
      <td>1434</td>\
      <td><a style='' href='pre_train_data_download.php?model=SSD300&egg_type=Ascaris_lumbricoides_egg_fertilized&slide_name=Csp-1&zip_file=300_300_cut_img'><button>Download</button></a></td>\
      <td><a style='' href='pre_train_data_download.php?model=SSD300&egg_type=Ascaris_lumbricoides_egg_fertilized&slide_name=Csp-1&zip_file=300_300_rec_xml'><button>Download</button></a></td>\
      <td><a style='' href='pre_train_data_download.php?model=SSD300&egg_type=Ascaris_lumbricoides_egg_fertilized&slide_name=Csp-1&zip_file=model'><button>Download</button></a></td>\
      </tr><tr>\
      <td><font style='font-style:italic;'>Diphyllobothrium latum</font> egg</td>\
      <td>(92W5257)</td>\
      <td>80</td>\
      <td>9</td>\
      <td>159</td>\
      <td><a style='' href='pre_train_data_download.php?model=SSD300&egg_type=Diphyllobothrium_latum_egg&slide_name=92W5257&zip_file=300_300_cut_img'><button>Download</button></a></td>\
      <td><a style='' href='pre_train_data_download.php?model=SSD300&egg_type=Diphyllobothrium_latum_egg&slide_name=92W5257&zip_file=300_300_rec_xml'><button>Download</button></a></td>\
      <td><a style='' href='pre_train_data_download.php?model=SSD300&egg_type=Diphyllobothrium_latum_egg&slide_name=92W5257&zip_file=model'><button>Download</button></a></td>\
      </tr><tr>\
      <td><font style='font-style:italic;'>Enterobius vermicularis</font> egg</td>\
      <td>(0822)</td>\
      <td>80</td>\
      <td>8</td>\
      <td>638</td>\
      <td><a style='' href='pre_train_data_download.php?model=SSD300&egg_type=Enterobius_vermicularis_egg&slide_name=0822&zip_file=300_300_cut_img'><button>Download</button></a></td>\
      <td><a style='' href='pre_train_data_download.php?model=SSD300&egg_type=Enterobius_vermicularis_egg&slide_name=0822&zip_file=300_300_rec_xml'><button>Download</button></a></td>\
      <td><a style='' href='pre_train_data_download.php?model=SSD300&egg_type=Enterobius_vermicularis_egg&slide_name=0822&zip_file=model'><button>Download</button></a></td>\
      </tr><tr>\
      <td><font style='font-style:italic;'>Echinococcus granulosus</font> egg</td>\
      <td>(PS1710)</td>\
      <td>100</td>\
      <td>11</td>\
      <td>290</td>\
      <td><a style='' href='pre_train_data_download.php?model=SSD300&egg_type=Echinococcus_granulosus_egg&slide_name=PS1710&zip_file=300_300_cut_img'><button>Download</button></a></td>\
      <td><a style='' href='pre_train_data_download.php?model=SSD300&egg_type=Echinococcus_granulosus_egg&slide_name=PS1710&zip_file=300_300_rec_xml'><button>Download</button></a></td>\
      <td><a style='' href='pre_train_data_download.php?model=SSD300&egg_type=Echinococcus_granulosus_egg&slide_name=PS1710&zip_file=model'><button>Download</button></a></td>\
      </tr><tr>\
      <td><font style='font-style:italic;'>Fasciola hepatica</font> egg</td>\
      <td>(30-6406)</td>\
      <td>100</td>\
      <td>11</td>\
      <td>346</td>\
      <td><a style='' href='pre_train_data_download.php?model=SSD300&egg_type=Fasciola_hepatica_egg&slide_name=30-6406&zip_file=300_300_cut_img'><button>Download</button></a></td>\
      <td><a style='' href='pre_train_data_download.php?model=SSD300&egg_type=Fasciola_hepatica_egg&slide_name=30-6406&zip_file=300_300_rec_xml'><button>Download</button></a></td>\
      <td><a style='' href='pre_train_data_download.php?model=SSD300&egg_type=Fasciola_hepatica_egg&slide_name=30-6406&zip_file=model'><button>Download</button></a></td>\
      </tr><tr>\
      <td><font style='font-style:italic;'>Schistosoma japonicum</font> egg</td>\
      <td>(PS1301)</td>\
      <td>100</td>\
      <td>11</td>\
      <td>159</td>\
      <td><a style='' href='pre_train_data_download.php?model=SSD300&egg_type=Schistosoma_japonicum_egg&slide_name=PS1301&zip_file=300_300_cut_img'><button>Download</button></a></td>\
      <td><a style='' href='pre_train_data_download.php?model=SSD300&egg_type=Schistosoma_japonicum_egg&slide_name=PS1301&zip_file=300_300_rec_xml'><button>Download</button></a></td>\
      <td><a style='' href='pre_train_data_download.php?model=SSD300&egg_type=Schistosoma_japonicum_egg&slide_name=PS1301&zip_file=model'><button>Download</button></a></td>\
      </tr><tr>\
      <td><font style='font-style:italic;'>Fasciolopsis buski</font> egg</td>\
      <td>Buski_egg</td>\
      <td>100</td>\
      <td>11</td>\
      <td>112</td>\
      <td><a style='' href='pre_train_data_download.php?model=SSD300&egg_type=Fasciolopsis_buski_egg&slide_name=Buski_egg&zip_file=300_300_cut_img'><button>Download</button></a></td>\
      <td><a style='' href='pre_train_data_download.php?model=SSD300&egg_type=Fasciolopsis_buski_egg&slide_name=Buski_egg&zip_file=300_300_rec_xml'><button>Download</button></a></td>\
      <td><a style='' href='pre_train_data_download.php?model=SSD300&egg_type=Fasciolopsis_buski_egg&slide_name=Buski_egg&zip_file=model'><button>Download</button></a></td>\
      </tr><tr>\
      <td><font style='font-style:italic;'>Paragonimus westermani</font> egg</td>\
      <td>(PS1415)</td>\
      <td>330</td>\
      <td>34</td>\
      <td>100</td>\
      <td><a style='' href='pre_train_data_download.php?model=SSD300&egg_type=Paragonimus_westermani_egg&slide_name=PS1415&zip_file=300_300_cut_img'><button>Download</button></a></td>\
      <td><a style='' href='pre_train_data_download.php?model=SSD300&egg_type=Paragonimus_westermani_egg&slide_name=PS1415&zip_file=300_300_rec_xml'><button>Download</button></a></td>\
      <td><a style='' href='pre_train_data_download.php?model=SSD300&egg_type=Paragonimus_westermani_egg&slide_name=PS1415&zip_file=model'><button>Download</button></a></td>\
      </tr><tr>\
      <td><font style='font-style:italic;'>Echinostoma</font> spp. egg</td>\
      <td>(E-16)</td>\
      <td>100</td>\
      <td>11</td>\
      <td>88</td>\
      <td><a style='' href='pre_train_data_download.php?model=SSD300&egg_type=Echinostoma_spp._egg&slide_name=E-16&zip_file=300_300_cut_img'><button>Download</button></a></td>\
      <td><a style='' href='pre_train_data_download.php?model=SSD300&egg_type=Echinostoma_spp._egg&slide_name=E-16&zip_file=300_300_rec_xml'><button>Download</button></a></td>\
      <td><a style='' href='pre_train_data_download.php?model=SSD300&egg_type=Echinostoma_spp._egg&slide_name=E-16&zip_file=model'><button>Download</button></a></td>\
      </tr><tr>\
      <td><font style='font-style:italic;'>Hymenolepis diminuta</font> egg</td>\
      <td>(92W5341)</td>\
      <td>100</td>\
      <td>11</td>\
      <td>285</td>\
      <td><a style='' href='pre_train_data_download.php?model=SSD300&egg_type=Hymenolepis_diminuta_egg&slide_name=92W5341&zip_file=300_300_cut_img'><button>Download</button></a></td>\
      <td><a style='' href='pre_train_data_download.php?model=SSD300&egg_type=Hymenolepis_diminuta_egg&slide_name=92W5341&zip_file=300_300_rec_xml'><button>Download</button></a></td>\
      <td><a style='' href='pre_train_data_download.php?model=SSD300&egg_type=Hymenolepis_diminuta_egg&slide_name=92W5341&zip_file=model'><button>Download</button></a></td>\
      </tr><tr>\
      <td><font style='font-style:italic;'>Ancylostoma duodnenale</font> egg</td>\
      <td>(HL E-3)</td>\
      <td>90</td>\
      <td>10</td>\
      <td>80</td>\
      <td><a style='' href='pre_train_data_download.php?model=SSD300&egg_type=Ancylostoma_duodnenale_egg&slide_name=HL_E-3&zip_file=300_300_cut_img'><button>Download</button></a></td>\
      <td><a style='' href='pre_train_data_download.php?model=SSD300&egg_type=Ancylostoma_duodnenale_egg&slide_name=HL_E-3&zip_file=300_300_rec_xml'><button>Download</button></a></td>\
      <td><a style='' href='pre_train_data_download.php?model=SSD300&egg_type=Ancylostoma_duodnenale_egg&slide_name=HL_E-3&zip_file=model'><button>Download</button></a></td>\
      </tr><tr>\
      <td><font style='font-style:italic;'>Hymenolepis nana</font> egg</td>\
      <td>(92W5361)</td>\
      <td>100</td>\
      <td>11</td>\
      <td>84</td>\
      <td><a style='' href='pre_train_data_download.php?model=SSD300&egg_type=Hymenolepis_nana_egg&slide_name=92W5361&zip_file=300_300_cut_img'><button>Download</button></a></td>\
      <td><a style='' href='pre_train_data_download.php?model=SSD300&egg_type=Hymenolepis_nana_egg&slide_name=92W5361&zip_file=300_300_rec_xml'><button>Download</button></a></td>\
      <td><a style='' href='pre_train_data_download.php?model=SSD300&egg_type=Hymenolepis_nana_egg&slide_name=92W5361&zip_file=model'><button>Download</button></a></td>\
      </tr><tr>\
      <td><font style='font-style:italic;'>Schistosoma haematobium</font> egg</td>\
      <td>(92W5123)</td>\
      <td>100</td>\
      <td>11</td>\
      <td>100</td>\
      <td><a style='' href='pre_train_data_download.php?model=SSD300&egg_type=Schistosoma_haematobium_egg&slide_name=92W5123&zip_file=300_300_cut_img'><button>Download</button></a></td>\
      <td><a style='' href='pre_train_data_download.php?model=SSD300&egg_type=Schistosoma_haematobium_egg&slide_name=92W5123&zip_file=300_300_rec_xml'><button>Download</button></a></td>\
      <td><a style='' href='pre_train_data_download.php?model=SSD300&egg_type=Schistosoma_haematobium_egg&slide_name=92W5123&zip_file=model'><button>Download</button></a></td>\
      </tr><tr>\
      <td><font style='font-style:italic;'>Schistosoma mansoni</font> egg</td>\
      <td>(92W5153)</td>\
      <td>90</td>\
      <td>10</td>\
      <td>80</td>\
      <td><a style='' href='pre_train_data_download.php?model=SSD300&egg_type=Schistosoma_mansoni_egg&slide_name=92W5153&zip_file=300_300_cut_img'><button>Download</button></a></td>\
      <td><a style='' href='pre_train_data_download.php?model=SSD300&egg_type=Schistosoma_mansoni_egg&slide_name=92W5153&zip_file=300_300_rec_xml'><button>Download</button></a></td>\
      <td><a style='' href='pre_train_data_download.php?model=SSD300&egg_type=Schistosoma_mansoni_egg&slide_name=92W5153&zip_file=model'><button>Download</button></a></td>\
      </tr>\
    </tbody>\
    </table>";
    document.getElementById("ssd300_table_p").innerHTML = t;
    $('#ssd300_table').dataTable({
      "columns": [
        null,
        null,
        null,
        null,
        null,
        null,
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
  }

  function u_net_table_content(){
    var t;
    t="<table id = 'u_net_table' border = '1' style='width:1600px;'>\
    <thead style='background-color:#777;font-size:14px;font-weight:700;font-style:italic;color:#cccccc;'>\
      <tr>\
        <th>Parasite egg category</th>\
        <th>Slide name</th>\
        <th>depth of fields(μm)</th>\
        <th>Number of images</th>\
        <th>Number of training sets</th>\
        <th>Image download</th>\
        <th>Label download</th>\
        <th>Model download</th>\
      </tr>\
    </thead>\
    <tbody>\
      <tr>\
      <td><font style='font-style:italic;'>Trichuris trichiura</font> egg</td>\
      <td>original</td>\
      <td>110</td>\
      <td>12</td>\
      <td>485</td>\
      <td><a style='' href='pre_train_data_download.php?model=U_net&egg_type=Trichuris_trichiura_egg&slide_name=original&zip_file=512_512_cut_img'><button>Download</button></a></td>\
      <td><a style='' href='pre_train_data_download.php?model=U_net&egg_type=Trichuris_trichiura_egg&slide_name=original&zip_file=512_512_pol_json'><button>Download</button></a></td>\
      <td><a style='' href='pre_train_data_download.php?model=U_net&egg_type=Trichuris_trichiura_egg&slide_name=original&zip_file=model'><button>Download</button></a></td>\
      </tr><tr>\
      <td><font style='font-style:italic;'>Ascaris lumbricoides</font> egg(fertilized)</td>\
      <td>(Csp-1)</td>\
      <td>80</td>\
      <td>9</td>\
      <td>655</td>\
      <td><a style='' href='pre_train_data_download.php?model=U_net&egg_type=Ascaris_lumbricoides_egg_fertilized&slide_name=Csp-1&zip_file=512_512_cut_img'><button>Download</button></a></td>\
      <td><a style='' href='pre_train_data_download.php?model=U_net&egg_type=Ascaris_lumbricoides_egg_fertilized&slide_name=Csp-1&zip_file=512_512_pol_json'><button>Download</button></a></td>\
      <td><a style='' href='pre_train_data_download.php?model=U_net&egg_type=Ascaris_lumbricoides_egg_fertilized&slide_name=Csp-1&zip_file=model'><button>Download</button></a></td>\
      </tr><tr>\
      <td><font style='font-style:italic;'>Diphyllobothrium latum</font> egg</td>\
      <td>(92W5257)</td>\
      <td>80</td>\
      <td>9</td>\
      <td>117</td>\
      <td><a style='' href='pre_train_data_download.php?model=U_net&egg_type=Diphyllobothrium_latum_egg&slide_name=92W5257&zip_file=512_512_cut_img'><button>Download</button></a></td>\
      <td><a style='' href='pre_train_data_download.php?model=U_net&egg_type=Diphyllobothrium_latum_egg&slide_name=92W5257&zip_file=512_512_pol_json'><button>Download</button></a></td>\
      <td><a style='' href='pre_train_data_download.php?model=U_net&egg_type=Diphyllobothrium_latum_egg&slide_name=92W5257&zip_file=model'><button>Download</button></a></td>\
      </tr><tr>\
      <td><font style='font-style:italic;'>Echinococcus granulosus</font> egg</td>\
      <td>(PS1710)</td>\
      <td>100</td>\
      <td>11</td>\
      <td>265</td>\
      <td><a style='' href='pre_train_data_download.php?model=U_net&egg_type=Echinococcus_granulosus_egg&slide_name=PS1710&zip_file=512_512_cut_img'><button>Download</button></a></td>\
      <td><a style='' href='pre_train_data_download.php?model=U_net&egg_type=Echinococcus_granulosus_egg&slide_name=PS1710&zip_file=512_512_pol_json'><button>Download</button></a></td>\
      <td><a style='' href='pre_train_data_download.php?model=U_net&egg_type=Echinococcus_granulosus_egg&slide_name=PS1710&zip_file=model'><button>Download</button></a></td>\
      </tr><tr>\
      <td><font style='font-style:italic;'>Fasciola hepatica</font> egg</td>\
      <td>(30-6406)</td>\
      <td>100</td>\
      <td>11</td>\
      <td>287</td>\
      <td><a style='' href='pre_train_data_download.php?model=U_net&egg_type=Fasciola_hepatica_egg&slide_name=30-6406&zip_file=512_512_cut_img'><button>Download</button></a></td>\
      <td><a style='' href='pre_train_data_download.php?model=U_net&egg_type=Fasciola_hepatica_egg&slide_name=30-6406&zip_file=512_512_pol_json'><button>Download</button></a></td>\
      <td><a style='' href='pre_train_data_download.php?model=U_net&egg_type=Fasciola_hepatica_egg&slide_name=30-6406&zip_file=model'><button>Download</button></a></td>\
      </tr>\
    </tbody>\
    </table>";
    document.getElementById("u_net_table_p").innerHTML = t;
    $('#u_net_table').dataTable({
      "columns": [
        null,
        null,
        null,
        null,
        null,
        null,
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
  }
  </script>
</body>
</html>
