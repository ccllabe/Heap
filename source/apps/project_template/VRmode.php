<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>備課介面</title>
  <!--disable cache -->
  <meta http-equiv="Pragma" CONTENT="no-cache">
  <meta http-equiv="Cache-Control" CONTENT="no-cache">
  <meta http-equiv="Expires" CONTENT="0">

  <!--leaflet-->
  <link rel="stylesheet" href="./leaflet/leaflet.css" />
  <script src="./leaflet/leaflet.js"></script>
  <!-- Bootstrap -->
  <link href="css/bootstrap-4.3.1.css" rel="stylesheet">
  <link href="css/style.css" rel="stylesheet">
  <link href="css/sidenav.css" rel="stylesheet">
  <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
  <script src="js/jquery-3.3.1.min.js"></script>
  <!-- Include all compiled plugins (below), or include individual files as needed -->
  <script src="js/bootstrap-4.3.1.js"></script>
  <script src="js/popper.min.js"></script>
  <!-- fontawesome -->
  <link href="/project/css/fontawesome/css/all.css" rel="stylesheet">
  <!-- editor -->
  <script src="./ckeditor/ckeditor.js"></script>

  <!--screenshot-->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.css">
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.js"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet.draw/1.0.4/leaflet.draw.css" />
  <script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet.draw/1.0.4/leaflet.draw.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/0.4.1/html2canvas.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/0.5.0-beta4/html2canvas.min.js"></script>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/leaflet-easybutton@2/src/easy-button.css">
  <script src="https://cdn.jsdelivr.net/npm/leaflet-easybutton@2/src/easy-button.js"></script>
  <!--minimap-->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet-minimap/3.6.1/Control.MiniMap.min.js" type="text/javascript"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet-minimap/3.6.1/Control.MiniMap.min.css" />
  <?php
  error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING);
  session_start();
  $_SESSION['chapter'] = 'Chapter2';
  $_SESSION['course'] = 'ITM001';
  session_start();
  
  ?>
</head>

<body>
  
 <!-- VR mode needle mask  -->
  <div class="main">
  <canvas id="myCanvas"> </canvas>
    <!--side bar -->
    <nav id="side-nav" class="width">

      <ul>
        <li class="selected">
          <div>
            <i class="fas fa-search-plus"></i>
            <span>倍率</span>
          </div>
          <ul id='magnification-list'>
            <?php //抓該課程圖有幾種倍率
            $dir = "{$_SESSION['course']}/{$_SESSION['chapter']}/";
            foreach (glob($dir . '*', GLOB_ONLYDIR) as $folder) {
              $magni[] = basename($folder);
            }
            sort($magni); //依倍率大小排序
            foreach ($magni as $key => $each) {
              echo "<li><div name='magnification' value='$each'> $each &times;</div></li>";
            }
            ?>
          </ul>
        </li>
    </nav>

    <div class="col2" id="mapid"></div>
    <div class="col3" id="lecture">
      <div id=editor></div>
      <div style='margin-top:1rem ;'>
        <button type="button" class="btn btn-outline-light" id='last' style='font-size:1.5rem'>上個圖標</button>
        <button type="button" class="btn btn-outline-light" id='next' style='font-size:1.5rem'>下個圖標</button>
      </div>
    </div>
    <!--提示框-->
    <div aria-live="polite" aria-atomic="true" style="min-height: 200px;">
      <div class="toast" data-delay="1200">
        <div class="toast-header">
          <i class="fas fa-exclamation-triangle"></i>
          <strong style="margin-left: .7rem"> 滾輪更改焦距</strong>
          <button type="button" class="ml-2 mb-1 close" data-dismiss="toast" aria-label="Close" style="font-size: 2.5rem;">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
      </div>
    </div>

  </div>

  <script type="text/javascript">
    var canvas = document.getElementById('myCanvas');
    var ctx = canvas.getContext('2d');
    canvas.width = window.innerWidth;
    canvas.height = window.innerHeight;
    
    function draw() { //vr模式 圓形
      var centerX = canvas.width / 2;
      var centerY = canvas.height / 2;
      var radius = centerY;
      ctx.beginPath();
      ctx.moveTo(0, 0);
      ctx.lineTo(centerX, 0);
      ctx.arc(centerX, centerY, radius, Math.PI * 1.5, Math.PI * 0.5, true);
      ctx.lineTo(0, canvas.height);
      ctx.fillStyle = "rgba(0,0,0,.8)";
      ctx.fill();
      ctx.closePath();
      ctx.beginPath();
      ctx.moveTo(canvas.width, 0);
      ctx.lineTo(centerX, 0);
      ctx.arc(centerX, centerY, radius, Math.PI * 1.5, Math.PI * 0.5, false);
      ctx.lineTo(canvas.width, canvas.height);
      ctx.lineTo(canvas.width, 0);
      ctx.fillStyle = "rgba(0,0,0,.8)";
      ctx.fill();
      ctx.closePath();
      ctx.beginPath();
      ctx.moveTo(centerX, centerY);
      ctx.fillStyle = "rgba(0,0,0,.8)";
      ctx.arc(centerX, centerY, radius, -0.85 * Math.PI, -0.87 * Math.PI, true);
      ctx.closePath();
      ctx.fill();
    };
    function resizeCanvas(){
        canvas.width = window.innerWidth;
        canvas.height = window.innerHeight;
        draw();
    }
    $('#side-nav-toggle').click(function() {
      $(this).parent().toggleClass('width');
      $(this).children().toggleClass('fas fa-angle-double-left').toggleClass('fas fa-angle-double-right');
    });
    $('#side-nav ul li div').click(function() {
      $(this).parent().toggleClass('selected');
    });


    $(document).ready(function() {

      $(window).resize(function() {
        $('#mapid').height(window.innerHeight);
        resizeCanvas();
      });
      


    });



    //leaflet;
    ///////////////////
    var course = "<?php echo $_SESSION['course'] ?>";
    var chapter = "<?php echo $_SESSION['chapter'] ?>";
    var url = 'marker.json?nocache=' + (new Date()).getTime();
    //////////////////////


    function getData(dataArray, layer) {
      $.getJSON(url, function(data) {
        var marker;
        for (var i in data.feature) {
          var data1 = {
            ID: data.feature[i].ID,
            lat: data.feature[i].lat,
            lng: data.feature[i].lng,
          }
          dataArray.feature.push(data1);
          UNIID = data.feature[i].ID;
          marker = L.marker([data.feature[i].lat, data.feature[i].lng], {
            UNIID
          }).addTo(layer);
          var id = marker._leaflet_id;
          //console.log(id);
          var popup = marker.bindPopup( //(marker.options.UNIID) +
            '</br><button onclick="deleteMarker(\'' + UNIID + /|/ + id +
            '\')">Delete Marker</button>' +
            '<button onclick="editMarker(\'' + UNIID + '\')">Edit Marker</button>');
          marker.on("customEvent", function(a) {
            layer.removeLayer(marker);
          });
        }
        //console.log(dataArray);
      });
    };

    $(function() {
      //$(document).ready(function(){
      $('#mapid').height(window.innerHeight);
      var img = [4401, 3286]
      var myMap = L.map('mapid', {keyboard: true });
      var rc = new L.RasterCoords(myMap, img); 
      myMap.setView(rc.unproject([2200.5, 1643]), 2)
      var drawnItems = new L.FeatureGroup(); //marker圖層
      baseLayerUrl = 'http://120.126.17.210/project/' + course + '/' + chapter + '/' +'40/100' +
          '/{z}/{x}/{y}.png'
        var layer = L.tileLayer(baseLayerUrl, {
          noWrap: true,
          minZoom: 4,
          maxZoom: 4,
          attribution: 400 + ' X ' + 100 + " &micro;m"
        });
        layer.addTo(myMap);
      myMap.setView([80.459508940007,-91.47216796875001],7);
      num = 64

      L.control.layers({}, {
        drawnItems,
      }).addTo(myMap)


      var collection = {
        //"type": "FeatureCollection",
        "feature": []
      };

      var magni;
      var flength; //選擇倍率之所有焦距array
      var focal; //透過側邊選擇的焦距
      var baseLayerUrl
      var zoom

      function changeFocal(f,zoom) {
        myMap.eachLayer(function(layer) {
          myMap.removeLayer(layer);
        });
        baseLayerUrl = 'http://120.126.17.210/project/' + course + '/' + chapter + '/' + magni + '/' + f +
          '/{z}/{x}/{y}.png'
        var layer = L.tileLayer(baseLayerUrl, {
          noWrap: true,
          minZoom: zoom,
          maxZoom: zoom,
          attribution: magni + ' X ' + f + " &micro;m"
        });
        layer.addTo(myMap);
        rc = new L.RasterCoords(myMap, [15146.5, 10319]);
      
      }
      ////////側邊/////
      $(" #magnification-list > li > div").on('click', function() { //找該倍率下有幾個焦距
        $("#focal-length-list").empty();
        magni = $(this).attr("value")
        
        if(magni==40){
          zoom=4;
        }else if(magni==100){
          zoom=5;
          console.log(zoom)
        }else if(magni==200){
          zoom=6;
          console.log(zoom)
        }
        else if(magni==400){
          zoom=7;
          console.log(zoom)
        }
        $.post({
          url: "markercont.php",
          async: false,
          data: {
            "magni": magni
          },
          datatype: "json",
          success: function(data) {
            flength = JSON.parse(data);
            for (key in flength) {
              $("#focal-length-list").append("<li><div name='focallenth' value='" +
                flength[key] + "'> " + flength[key] + " &micro;m</div></li>"
              );
            };
          },
          error: function(e) {
            alert(e.status + " error occurred!");
          }
        });
        console.log(magni)
        
        
        changeFocal(flength[0],zoom);
        $('.toast').toast('show');
        
        
      });
      
      
      $(".main").on('click', function() { 
        $('.col2').focus();
      });

      myMap.dragging.disable();
      myMap.scrollWheelZoom.disable();
      $("#lecture,.leaflet-control-minimap,.leaflet-control-layers,.unnamed-state-active,.move-active")
        .hide();
      $(".edit-marker").parent().hide();
      draw();
      key = 0; //設定vr模式焦距初始值
      //changeFocal(focal);
      $("html").bind('mousewheel', function(e) {
        if (e.originalEvent.wheelDelta / 120 > 0 && key < flength.length - 1) {
          key++;
          a = flength[key];
          changeFocal(a,zoom);
          sleep(0.5);
        } else if (e.originalEvent.wheelDelta / 120 <= 0 && key > 0) {
          key--;
          a = flength[key];
          changeFocal(a,zoom);
          sleep(0.5);
        } else if (e.originalEvent.wheelDelta / 120 <= 0 && key <= 0) {
          key = flength.length - 1;
          a = flength[key];
          changeFocal(a,zoom);
          sleep(0.5);
          //changeFocal(a);
        } else {
          key = 0;
          a = flength[key];
          changeFocal(a,zoom);
          sleep(0.5);
          //changeFocal(a);
        }
      });

      /////////////////載入座標到圖層//////////////////////////////////////////
      getData(collection, drawnItems);

      var coord = rc.unproject([20091.256832, 24022.610432])
      //var circle= new L.CircleMarker([43.2347565056319,-69.63543000000001],{color: "red",weight:1,fillColor: "#f03",fillOpacity: 0.1,radius:5}).addTo(YoloV4);
      console.log(coord)


      ////////////////////////////////////////////////////////////////////


      function miniLayers(url) {
        osm2 = new L.TileLayer(url, {
          noWrap: true,
          minZoom: 0,
          maxZoom: 2,
        });
        var pubs2 = new L.LayerGroup();
        getData1(pubs2);
        var layers = new L.LayerGroup([osm2, pubs2]); //osm2 ->大圖 ,pubs -> 有標記marker位置的layer
        return layers;
      }

      function getData1(a) { //mini-map讀marker
        $.getJSON(url, function(data) {
          var marker1;
          for (var i in data.feature) {
            var data1 = {
              ID: data.feature[i].ID,
              lat: data.feature[i].lat,
              lng: data.feature[i].lng,
            }
            marker1 = new L.CircleMarker([data.feature[i].lat, data.feature[i].lng], {
              radius: 1,
            }).addTo(a);
          }
        });
      };

    })
  </script>
</body>
<script language="JAVASCRIPT" src="rastercoords.js"></script>

</html>