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
   <link href="css/fontawesome/css/all.css" rel="stylesheet">
   <!-- editor -->
   <script src="./ckeditor/ckeditor.js"></script>

   <!--screenshot-->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.css">
   <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.js"></script>
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet.draw/1.0.4/leaflet.draw.css" />
   <script src="./leaflet/leaflet.draw.js"></script>
   <script src="html2canvas/html2canvas.min.js"></script>
   <script src="html2canvas/html2canvas.js"></script>
   <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/leaflet-easybutton@2/src/easy-button.css">
   <script src="https://cdn.jsdelivr.net/npm/leaflet-easybutton@2/src/easy-button.js"></script>
   <!--minimap-->
   <script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet-minimap/3.6.1/Control.MiniMap.min.js"
      type="text/javascript"></script>
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet-minimap/3.6.1/Control.MiniMap.min.css" />

   <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>


   <style type = "text/css">
      .leaflet-draw-toolbar .leaflet-draw-edit-edit{
         background-position: -122px -2px;

      }
      </style>


   <?php
    error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING);
    session_start();
    $_SESSION['course'] = 'ITM001';
    $_SESSION['chapter'] = 'Chapter2';
    $_SESSION['id'] = 'ACCOUNT';
    $_SESSION['authority'] = 'teacher';
    session_start();
    /*
    if(empty($_SESSION['id'])){
        echo "<script>alert('未登入'); document.location.href='login/login.php';</script>";
    }
    if (empty($_SESSION['chapter'])) {
        echo "<script>alert('未選擇課程'); document.location.href='course-outline.php';</script>";
    }
   */
    ?>
</head>

<body>

   <div class="main" >
      <canvas id="myCanvas" style="display:none"></canvas>
      <!--VR mode needle mask  -->
      <!--side bar -->
      <nav id="side-nav">
         <a id="logo" >
            <i class="fas fa-home"></i></a>
         <ul>
            <li class="selected">
               <div>
                  <i class="fas fa-search-plus"></i>
                  <span>倍率</span>
               </div>
               <ul id='magnification-list'>
                  <?php //抓該課程圖有幾種倍率
                           $dir = "./{$_SESSION['course']}/{$_SESSION['chapter']}/";
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
            <li>
               <div>
                  <i class="fas fa-ruler-horizontal"></i>
                  <span>焦距</span>
               </div>
               <ul id='focal-length-list'></ul>
            </li>
            <li>
               <div class="edit-marker">
                  <i class="fas fa-map-marker-alt"></i>
                  <span>編輯圖標</span>
               </div>
            </li>
         </ul>
         <div id="side-nav-toggle">
            <i class="fas fa-angle-double-left"></i>
         </div>
         <div id="side-nav-bottom">
            <div>
               <span class="item-icon user-icon"><i class="fas fa-user-circle"></i></span>
               <span class="profile">
                  <?php echo "Teacher" ?>
                  <a href="#" class="item-icon">
                     <i class="fas fa-sign-out-alt"></i></a>
               </span>
            </div>
         </div>
      </nav>

      <div class="col2" id="mapid"></div>
      <div class="col3" id="lecture">
         <div id=editor></div>
         <div style='margin-top:1rem ;'>
            <button type="button" class="btn btn-outline-light" id='last' style='font-size:1.5rem'>Previous</button>
            <button type="button" class="btn btn-outline-light" id='next' style='font-size:1.5rem'>Next</button>
         </div>
      </div>
      <!--提示框-->
      <div aria-live="polite" aria-atomic="true" style="min-height: 200px;">
         <div class="toast" data-delay="3600">
            <div class="toast-header">
               <i class="fas fa-exclamation-triangle"></i>
               <strong style="margin-left: .7rem"> 請選擇焦距</strong>
               <button type="button" class="ml-2 mb-1 close" data-dismiss="toast" aria-label="Close"
                  style="font-size: 2.5rem;">
                  <span aria-hidden="true">&times;</span>
               </button>
            </div>
         </div>
      </div>

   </div>

   <script type="text/javascript">
   var view_data = {};
   var magni;
   var flength; //選擇倍率之所有焦距array
   var focal; //透過側邊選擇的焦距
   var baseLayerUrl
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

   function resizeCanvas() {
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
   //$('#focal-length-list').hasClass('selected'){

   //}



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

   function gen_uuid() {
      var d = Date.now();
      if (typeof performance !== 'undefined' && typeof performance.now === 'function') {
         d += performance.now(); //use high-precision timer if available
      }
      return 'xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx'.replace(/[xy]/g, function(c) {
         var r = (d + Math.random() * 16) % 16 | 0;
         d = Math.floor(d / 16);
         return (c === 'x' ? r : (r & 0x3 | 0x8)).toString(16);
      });
   };

   function getData(dataArray, layer, icon1) {
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
               UNIID,
               icon: icon1
            }).addTo(layer);
            var id = marker._leaflet_id;
            //console.log(id);
            var popup = marker.bindPopup( //(marker.options.UNIID) +
               '</br><button class="btn btn-info " onclick="set_view(\'' + marker._latlng.lat+ " " + marker._latlng.lng +'\')">Broadcast</button>&nbsp;' +
               '<button class="btn btn-secondary" onclick="editMarker(\'' + UNIID + '\')">Edit Marker</button>&nbsp;'+
               '<button class="btn btn-secondary" onclick="deleteMarker(\'' + UNIID + /|/ + id + '\')">Delete Marker</button>&nbsp;'

               );
            marker.on("customEvent", function(a) {
               layer.removeLayer(marker);
            });
         }
         //console.log(marker);
      });
   };

   var url1 = 'new_updated-file_90.json?nocache=' + (new Date()).getTime(); //yolo辨識結果

   function getYolo(rc, dataArray, layer, num,leafIcon) {
      $.getJSON(url1, function(yolo_data) {
         var marker;
         var clonorchisIcon = new leafIcon({iconUrl: 'clonorchis.png'})
        var toxocara_canisIcon = new leafIcon({iconUrl: 'toxocara_canis.png'})
        var whipwormIcon = new leafIcon({iconUrl: 'whipworm.png'})
        var Fasciola_hepatica_eggIcon = new leafIcon({iconUrl: 'Fasciola_hepatica_egg.png'})
        var Diphyllobothrium_latum_eggIcon = new leafIcon({iconUrl: 'Diphyllobothrium_latum_egg.png'})
        var Pinworm_eggIcon = new leafIcon({iconUrl: 'Pinworm_egg.png'})
        var Echinococcus_granulosus_eggIcon = new leafIcon({iconUrl: 'Echinococcus_granulosus_egg.png'})
         for (var i in yolo_data) {
            for (var r in yolo_data[i].objects) {
               if (yolo_data[i].objects[r].confidence) {
                  var data2 = {
                    name: yolo_data[i].objects[r].name,
                     confidence: yolo_data[i].objects[r].confidence,
                     center: [Math.round(yolo_data[i].objects[r].relative_coordinates.center_x), Math
                        .round(yolo_data[i].objects[r].relative_coordinates.center_y)
                     ],
                     height: Math.round(yolo_data[i].objects[r].relative_coordinates.height),
                     width: Math.round(yolo_data[i].objects[r].relative_coordinates.width)
                  }
                  //										var coord = rc.project([82.0152,-90.16479]);
                  //										console.log(coord);
                  if (data2.name == "clonorchis sinensis") {
                     L.marker(rc.unproject(data2.center), {
                        icon: clonorchisIcon
                     }).addTo(layer)
                  } else if(data2.name == "toxocara_canis"){
                     L.marker(rc.unproject(data2.center), {
                        icon:  toxocara_canisIcon
                     }).addTo(layer)
                  } else if(data2.name == "whipworm"){
                     L.marker(rc.unproject(data2.center), {
                        icon:  whipwormIcon
                     }).addTo(layer)
                  }else if(data2.name == "Fasciola_hepatica_egg"){
                     L.marker(rc.unproject(data2.center), {
                        icon:  Fasciola_hepatica_eggIcon
                     }).addTo(layer)
                  }else if(data2.name == "Diphyllobothrium_latum_egg"){
                     L.marker(rc.unproject(data2.center), {
                        icon:  Diphyllobothrium_latum_eggIcon
                     }).addTo(layer)
                  }else if(data2.name == "Pinworm_egg"){
                     L.marker(rc.unproject(data2.center), {
                        icon:  Pinworm_eggIcon
                     }).addTo(layer)
                  }else if(data2.name == "Echinococcus_granulosus_egg"){
              /*       L.marker(rc.unproject(data2.center), {
                        icon:  Echinococcus_granulosus_eggIcon
                     }).addTo(layer)*/
                  }else {
                     circle = new L.CircleMarker(rc.unproject(data2.center), {
                        color: "red",
                        weight: 1,
                        fillColor: "#f03",
                        fillOpacity: 0.05,
                        radius: (Math.min(data2.width, data2.height) / num) * 1.5
                     }).addTo(layer);
                  }
                  //	var marker = L.marker(rc.unproject(data2.center)).addTo(layer);
               }

            }
            dataArray.push(data2);
         }
      });
   };



   function loadXMLDoc(dataArray) {
      var xmlhttp = new XMLHttpRequest();
      xmlhttp.open('POST', 'loadmarker.php', true);
      xmlhttp.setRequestHeader('Content-Type', 'application/json;charset=UTF-8');
      xmlhttp.send(JSON.stringify(dataArray));
   };



   $(function() {

      swal("Welcome!", "Hint : Right-click to add new marker !", "success");


      $.getJSON("tile_config.json", function(tile_config){
         //console.log(parseInt(tile_config[0].magni))
         min_magni=[];
         min_focal=[];
         for (var i in tile_config){
            min_magni.push(tile_config[i].magni)
         }
         magni = Math.min.apply(Math,min_magni);
         for (var i in tile_config){
            if(tile_config[i].magni==Math.min.apply(Math,min_magni)){
               min_focal.push(tile_config[i].focal)
            }
         }
         focal = Math.min.apply(Math,min_focal);

         //$(document).ready(function(){
         $('#mapid').height(window.innerHeight);
         var img = [30293, 20638]
         var myMap = L.map('mapid', {});
         var rc = new L.RasterCoords(myMap, img);
         myMap.setView(rc.unproject([15146.5, 10319]), 2)
         var drawnItems = new L.FeatureGroup(); //marker圖層
         //myMap.addLayer(drawnItems);
         num = 64
         var YoloV4 = new L.FeatureGroup();
         //myMap.addLayer(YoloV4);

         L.control.layers({}, {
            drawnItems
            //,YoloV4
         }).addTo(myMap)

         var greenIcon = L.icon({
            iconUrl: 'marker.png',
            shadowUrl: 'pin_shadow.png',
            iconSize: [30, 45], // size of the icon
            shadowSize: [45, 55],
            iconAnchor: [15, 45], // point of the icon which will correspond to marker's location
            shadowAnchor: [12, 55], // the same for the shadow
            popupAnchor: [-3, -76] // point from which the popup should open relative to the iconAnchor
         });
         var iconx = 8.75
         var icony = 7.5
         var LeafIcon = L.Icon.extend({
                  options: {
                     iconSize: [iconx, icony],
                     iconAnchor: [iconx/2, icony/2],
                  }
               });

         var yolo_result = []
         getYolo(rc, yolo_result, YoloV4, 64,LeafIcon);
         var collection = {
            //"type": "FeatureCollection",
            "feature": []
         };

         var myZoom = {
            start: myMap.getZoom(),
            end: myMap.getZoom()
         };

         myMap.on('zoomstart', function(e) {
            myZoom.start = myMap.getZoom();
            YoloV4.clearLayers()
         });

         myMap.on('zoomend', function(e) {
            myZoom.end = myMap.getZoom();
            var diff = myZoom.start - myZoom.end;
            //console.log(diff)
            if (diff > 0) {
               //console.log(myZoom.start, myZoom.end, diff)
               //		circle.setRadius(circle.getRadius() / 2);
               //console.log(iconx/ (2 * diff),icony/ (2 * diff))
               var LeafIcon = L.Icon.extend({
                  options: {
                     iconSize: [iconx/ (2 * diff), icony/ (2 * diff)],
                     iconAnchor: [iconx/(4*diff), icony/(4* diff)],
                  }
               });

               YoloV4.clearLayers()
               getYolo(rc, yolo_result, YoloV4, num * (2 * diff), LeafIcon);
               iconx = iconx / (2 * diff)
               icony = icony / (2 * diff)
               num = num * 2 * diff
            } else if (diff < 0) {
               //console.log(iconx* -(2 * diff), icony* -(2 * diff))
               var LeafIcon = L.Icon.extend({
                  options: {
                     iconSize: [iconx* -(2 * diff), icony* -(2 * diff)],
                     iconAnchor: [iconx* -(diff), icony* -(diff)],
                  }
               });


               //console.log(myZoom.start, myZoom.end, diff)
               YoloV4.clearLayers()
               getYolo(rc, yolo_result, YoloV4, num / -(2 * diff), LeafIcon);
               //		circle.setRadius(circle.getRadius() * 2);
               iconx = iconx * -(2 * diff)
               icony = icony * -(2 * diff)
               num = num / -(2 * diff)
            }
         });





         function changeFocal(e,zoom,tile_config) {

            $.getJSON("tile_config.json", function(tile_config){
               //console.log(parseInt(magni))


            for (var i in tile_config){
               if(tile_config[i].magni==parseInt(magni) && tile_config[i].focal==e){
                  maxx = tile_config[i].maxx
                  maxy = tile_config[i].maxy
                  zoom = tile_config[i].zoom
               }
            }



               myMap.eachLayer(function(layer) {
                  myMap.removeLayer(layer);
               });
               baseLayerUrl = './' + course + '/' + chapter + '/' + magni + '/' + e +
                  '/{z}/{x}/{y}.png'
               var layer = L.tileLayer(baseLayerUrl, {
                  noWrap: true,
                  minZoom: 2,
                  maxZoom: zoom,
                  attribution: magni + ' X ' + e + " &micro;m"
               });
               layer.addTo(myMap);
               rc = new L.RasterCoords(myMap, [maxx, maxy]);
               //rc = new L.RasterCoords(myMap, img);
               myMap.addLayer(drawnItems);
               //myMap.addLayer(YoloV4);
               var layers = miniLayers(baseLayerUrl);
               miniMap.changeLayer(layers);
            });
         }


         ////////側邊/////
         $(" #magnification-list > li > div").on('click', function() { //找該倍率下有幾個焦距

            $("#focal-length-list").empty();


            magni = $(this).attr("value")

            for (var i in tile_config){
               if(tile_config[i].magni==parseInt(magni)){
                  min_focal.push(tile_config[i].focal)

               }
            }
            focal = Math.min.apply(Math,min_focal);


            for (var i in tile_config){
               if(tile_config[i].magni==parseInt(magni) && tile_config[i].focal==focal){
                  zoom = tile_config[i].zoom
               }
            }
            //console.log(zoom)
            /*
            if(magni==40){
               zoom=4;
            }else if(magni==100){
               zoom=5;
               console.log(zoom)
            }else if(magni==200){
               zoom=6;
               console.log(zoom)
            }else if(magni==400){
               zoom=7;
               console.log(zoom)
            }*/
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
            changeFocal(flength[0],zoom,tile_config);
            $('.toast').toast('show');
         });

         $(".main").on('click', function() {
            $('.col2').focus();
         });

         $("#focal-length-list").on('click', "li", function() {
            focal = $(this).children().attr("value") //透過側邊欄選擇焦距
            changeFocal(focal,zoom,tile_config);
         });

         $('.edit-marker').click(function() {
            $('#lecture').toggle();
         })

         function findMarker(e) { //到上/下一個marker
            ID = collection.feature[e].ID;
            lat = collection.feature[e].lat;
            lng = collection.feature[e].lng;
            myMap.flyTo([lat, lng], zoom);
            editMarker(ID);

         }
         var mindex = -1
         $('#last').click(function() {
            if (mindex > 0) {
               mindex--;
               findMarker(mindex);
            } else {
               mindex = mindex + collection.feature.length - 1;
               findMarker(mindex);
            }
         })
         $('#next').click(function() {
            if (mindex < collection.feature.length - 1) {
               mindex++;
               findMarker(mindex);
            } else {
               mindex = mindex - collection.feature.length + 1;
               findMarker(mindex);
            }
         })
         ///左上那排按鈕
         var minimap = L.easyButton({ //控制縮圖兩種模式
            states: [{
               stateName: 'move',
               icon: 'fa-star',
               title: 'Fixed / Unfixed mini map',
               onClick: function(control) {
                  //osm2.remove();
                  miniMap.remove();
                  where([30, -30], baseLayerUrl); //固定縮圖
                  control.state('fixed');
               }
            }, {
               icon: 'fa-undo',
               stateName: 'fixed',
               onClick: function(control) {
                  //osm2.remove();
                  miniMap.remove();
                  where(0, baseLayerUrl); //可移動縮圖
                  control.state('move');
               },
               title: '縮圖'
            }]
         }).addTo(myMap);


         var vr = L.easyButton({
            states: [{
               stateName: 'VR',
               icon: 'fa-glasses',
               title: 'VR mode',
               onClick: function(control) {
                  myMap.dragging.disable();
                  myMap.scrollWheelZoom.disable();
                  $("#lecture,.leaflet-control-minimap,.leaflet-control-layers,#side-nav-toggle,.unnamed-state-active,.move-active")
                     .hide();
                  $(".edit-marker").parent().hide();
                  draw();
                  $("#side-nav").addClass("width");
                  $(myCanvas).show();

                  key = flength.indexOf(focal); //設定vr模式焦距初始值
                  //changeFocal(focal);
                  $("html").bind('mousewheel', function(e) {

                     if (e.originalEvent.wheelDelta / 120 > 0 && key < flength.length - 1) {
                        key++;
                        a = flength[key];
                        changeFocal(a,zoom,tile_config);
                        sleep(0.5);
                     } else if (e.originalEvent.wheelDelta / 120 <= 0 && key >0) {
                        key--;
                        a = flength[key];
                        changeFocal(a,zoom,tile_config);
                        sleep(0.5);
                     } else if (e.originalEvent.wheelDelta / 120 <= 0 && key <= 0) {
                        key = flength.length -1;
                        a = flength[key];
                        changeFocal(a,zoom,tile_config);
                        sleep(0.5);
                        //changeFocal(a);
                     }else {
                        key = 0;
                        a = flength[key];
                        changeFocal(a,zoom,tile_config);
                        sleep(0.5);
                        //changeFocal(a);
                     }/*else {
                        myMap.eachLayer(function(layer) {
                           myMap.removeLayer(layer);
                        });
                        //changeFocal(a);
                     }*/
                  });
                  control.state('undo');
               }
            }, {
               icon: 'fa-glasses',
               stateName: 'undo',
               onClick: function(control) {
                  myMap.dragging.enable();
                  myMap.scrollWheelZoom.enable();
                  $(".leaflet-control-minimap,.leaflet-control-layers,#side-nav-toggle,.unnamed-state-active,.move-active")
                     .show();
                  $(".edit-marker").parent().show();
                  $(myCanvas).hide();
                  control.state('VR');
               },
               title: 'undo'
            }]
         }).addTo(myMap);
         //截圖
         /*
         var screenshot = L.easyButton(
            '<img src="./picture.svg" style="left: -2px;position: absolute;top: 2px;width: 18px;">',
            function(btn, myMap) {
               this.remove(); //控制重複繪矩形
               // 矩形
               let rectangle = new L.Draw.Rectangle(myMap, {
                  shapeOptions: {
                     stroke: true,
                     color: 'blue',
                     weight: 2,
                     opacity: 0.5,
                     fill: true,
                     fillColor: null,
                     fillOpacity: 0.1,
                     smoothFactor: 10,
                  }
               });
               $("#side-nav").addClass('width');
               $('#side-nav-toggle').children().removeClass('fas fa-angle-double-left').addClass(
                  'fas fa-angle-double-right');
               rectangle.enable(); //繪製
            }).addTo(myMap);
            */
         /*var editBar = L.easyBar([
            minimap,
            vr,
            screenshot
         ]);

         editBar.addTo(myMap);*/

         var drawControl = new L.Control.Draw({
            draw: false,
            edit: {


               featureGroup: drawnItems,
               remove: false

            }

            //console.log(featureGroup)
         });



         myMap.addControl(drawControl);


         /////////////////載入座標到圖層//////////////////////////////////////////
         getData(collection, drawnItems, greenIcon);

         var coord = rc.unproject([20091.256832, 24022.610432])
         //var circle= new L.CircleMarker([43.2347565056319,-69.63543000000001],{color: "red",weight:1,fillColor: "#f03",fillOpacity: 0.1,radius:5}).addTo(YoloV4);
         //console.log(coord)
         editMarker = function(a) {
            //console.log(a);
            $('#lecture').show();
            //document.getElementById("lec").innerHTML='<iframe src="./generate_html/'+ a +'" frameborder="1" class=" " ;></iframe>';
            $.ajax({
               url: "editor.php",
               data: "uid=" + a,
               datatype: "text",
               type: "POST",
               success: function(data) {
                  $("#editor").html(data);
               },
               error: function() {
                  alert('error');
               }
            });

         };

         set_view = function(a){

            const words = a.split(' ');
            view_data = {
               lat : words[0],
               lng : words[1],
               focal: focal,
               magni: magni,
               zoom: myMap.getZoom()
            }
            //console.log(view_data);

            var xmlhttp = new XMLHttpRequest();
            xmlhttp.open('POST', 'alertview.php', true);
            xmlhttp.setRequestHeader('Content-Type', 'application/json;charset=UTF-8');
            xmlhttp.send(JSON.stringify(view_data));
         };


         deleteMarker = function(a) {
            var value = a.split("/|/");
            console.log(value[0])
            console.log(value[1])
            for (var i in collection.feature) {
               if (collection.feature[i].ID == value[0]) {
                  collection.feature.splice(i, 1);
                  //console.log(collection);
                  var col_JSON = JSON.stringify(collection);
                  //console.log(col_JSON);
               }
            }
            loadXMLDoc(collection);
            //console.log(value[1])
            drawnItems.removeLayer(value[1]);
            //marker.fire("customEvent");
         };

         deleteMarker_edit = function(a) {
            //var value = a.split("/|/");
            //console.log(value[0])
            console.log("1" + JSON.stringify(collection))
            for (var i in collection.feature) {
               if (collection.feature[i].ID == a) {
                  collection.feature.splice(i, 1);
                  //console.log(collection);
                  var col_JSON = JSON.stringify(collection);
                  //console.log(col_JSON);
               }
            }
            loadXMLDoc(collection);
            console.log("2" + JSON.stringify(collection))
            //console.log(value[1])
            drawnItems.removeLayer();
            //marker.fire("customEvent");
         };
         ////////////////////////////////////////////////////////////////////




         var geojson;
         myMap.on("contextmenu", function(event) {
            //console.log("user right-clicked on map coordinates: " + event.latlng.toString());
            var uniqueID = gen_uuid();
            var marker = L.marker(event.latlng, {
               uniqueID,
               icon: greenIcon
            }).addTo(drawnItems);
            var id = marker._leaflet_id;
            var popup = marker.bindPopup(
               '</br><button class="btn btn-info" onclick="set_view(\'' + marker._latlng.lat+ " "+ marker._latlng.lng + '\')">Broadcast</button>&nbsp;' +
               '<button class="btn btn-secondary" onclick="editMarker(\'' + uniqueID +'\')">Edit Marker</button>&nbsp;' +
               '<button class="btn btn-secondary" onclick="deleteMarker(\'' + uniqueID + /|/ + id +'\')">Delete Marker</button>&nbsp;'
               );

            var data = {
               ID: marker.options.uniqueID,
               lat: event.latlng.lat,
               lng: event.latlng.lng,
            }
            collection.feature.push(data);
            //console.log(collection);

            loadXMLDoc(collection);




            //console.log(view_data);

            //marker.on('click', function(){
            //editMarker();
            //}
         });




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

         where(0, ' ');
         //縮圖
         function where(center, url) {
            var layers = miniLayers(url);
            var rect1 = {
               color: "#ff1100",
               weight: 2
            };
            var rect2 = {
               color: "#0000AA",
               weight: 1,
               opacity: 0,
               fillOpacity: 0
            };
            miniMap = new L.Control.MiniMap(layers, {
               toggleDisplay: true,
               disableZoom: true,
               centerFixed: center, //控制變數(flase:可移動，[經,緯]:固定中心點)
               width: 230,
               height: 230,
               zoomLevelFixed: 0,
               aimingRectOptions: rect1,
               shadowRectOptions: rect2
            }).addTo(myMap);


         };


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


         myMap.on('draw:edited', function(e) { //拖移改變圖標位置
            var layers = e.layers;
            layers.eachLayer(function(layer) {
               console.log(layer.options)
               if(layer.options.UNIID){
                  deleteMarker_edit(layer.options.UNIID)
                  var data = {
                     ID: layer.options.UNIID,
                     lat: layer._latlng.lat,
                     lng: layer._latlng.lng,
                  }
                  collection.feature.push(data);
                  loadXMLDoc(collection);
                  console.log("success  " + collection)
               }else{
                  deleteMarker_edit(layer.options.uniqueID)
                  var data = {
                     ID: layer.options.uniqueID,
                     lat: layer._latlng.lat,
                     lng: layer._latlng.lng,
                  }
                  console.log("3" + JSON.stringify(data))
                  collection.feature.push(data);
                  loadXMLDoc(collection);
                  console.log("error  " + collection)
               }

               console.log("4" + JSON.stringify(collection))

            });
            var layers = miniLayers(baseLayerUrl); //重整minimap 更新圖釘位置
            miniMap.changeLayer(layers);


         });
         /*
         myMap.on(L.Draw.Event.CREATED, (e) => {
            screenshot.addTo(myMap); //控制重複繪矩形
            if (e.layerType == 'rectangle') {
               myMap.addLayer(e.layer);
               if (!e.layer.flag) {
                  $.confirm({
                     title: "<font color='black'>提示!</font>",
                     content: "<font color='black'>是否要截圖!</font>",
                     type: 'dark',
                     buttons: {
                        confirm: function() {
                           let latlngs = e.layer._latlngs[0]; //獲取矩形的經緯度list
                           console.log(latlngs + "1")
                           myMap.removeLayer(e.layer); //移除框選
                           console.log(latlngs + "2");
                           captureScreenEnd(latlngs); //開始截圖
                           console.log(latlngs + "3");
                           //let p=e.layer.getLatLngs();
                           //miniMap.remove();
                           //where(0);


                        },
                        cancel: function() {
                           myMap.removeLayer(e.layer);

                        }
                     }
                  });
                  e.layer.flag = true
               }
            }
         });
         */
         function captureScreenEnd(latlngs) {
            let bounds = myMap.getBounds(),
               zero = [(bounds._northEast.lat), bounds._southWest.lng],
               //計算當前視窗內的原點經緯度 ==>對應的屏幕座標（位移座標縮放的計算startPoint的偏移量）
               zeroPoint = myMap.latLngToLayerPoint(zero)


            console.log("zero1"+zero);
            console.log("zero2"+ myMap.layerPointToLatLng(zeroPoint));

            let startPoint = myMap.latLngToLayerPoint(latlngs[1]), //經緯度轉 屏幕座標 計算 起點與寬高
               endPoint = myMap.latLngToLayerPoint(latlngs[3]),

               width = Math.abs(startPoint.x - endPoint.x),
               height = Math.abs(startPoint.y - endPoint.y);

               console.log("Start" + startPoint);
               console.log("End" + endPoint);
               console.log(width + "///" + height);
               console.log(zero + "/////" + zeroPoint);


            html2canvas(document.getElementById('mapid'), {
               useCORS: true, // 底圖跨域
               //allowTaint: false
            }).then((canvas) => {
               downloadIamge(canvas, (startPoint.x - zeroPoint.x), (startPoint.y - zeroPoint.y), width, height)

            });

         }

         function downloadIamge(canvas, capture_x, capture_y, capture_width, capture_height) {
            // 創建一個用於擷取的canvas
            console.log("i am first")
            var clipCanvas = document.createElement('canvas');
            var pic_max = Math.max(capture_width, capture_height); //確保使用者是拉正方形
            clipCanvas.width = capture_width //capture_width
            clipCanvas.height = capture_height //capture_height
            //擷取圖片

            //clipCanvas.getContext('2d').drawImage(canvas, capture_x, capture_y, capture_width, capture_height, 0, 0,capture_width, capture_height);
            //clipCanvas.getContext('2d').drawImage(canvas, capture_x, capture_y, pic_max, pic_max, 0, 0, pic_max, pic_max);
            clipCanvas.getContext('2d').drawImage(canvas, 0, 0, pic_max, pic_max, 0, 0, pic_max, pic_max);
            var clipImgBase64 = clipCanvas.toDataURL() //生成圖片url

            /*
            // 下载图片
            let link = document.createElement("a");
            link.href = clipImgBase64; //下载链接
            link.setAttribute("download", new Date().toLocaleString() + "_截图.png");
            link.style.display = "none"; //a标签隐藏
            document.body.appendChild(link);
            link.click(); // 点击下载
            document.body.removeChild(link); // 移除a标签




               $.confirm({
                  title: '<font color="black">請輸入檔名!</font>',
                  type: 'dark',
                  content: '' +
                        '<form action="" class="formName">' +
                        '<div class="form-group">' +
                        '<input type="text"  class="name form-control" required />' +
                        '</div>' +
                        '</form>',
                  buttons: {
                        formSubmit: {
                           text: 'Submit',
                           btnClass: 'btn-blue',
                           action: function() {
                              var name = this.$content.find('.name').val();
                              if (!name) {
                                    $.alert({
                                       icon: 'glyphicon glyphicon-heart',
                                       closeIcon: true,
                                       title: '<font color="black">未輸入檔名</font>',
                                       type: 'red',
                                    });
                                    return false;
                              } else {
                                    $.ajax({
                                       type: 'post',
                                       url: 'screenshot.php',
                                       data: {
                                          "img": clipImgBase64,
                                          "name": name
                                       },
                                       //dataType:'json',
                                       success: function(data) {
                                          if (data == "error") {
                                                //alert(data);
                                                $.alert({
                                                   title: '<font color="black">提示!</font>',
                                                   content: '<font color="black">截圖失敗! 重複檔名</font>',
                                                   type: 'red',
                                                });
                                                return;
                                          } else {
                                                window.open(data, 'Image',
                                                   "resizable=1,height=500,width=500");
                                                $.alert({
                                                   title: '<font color="black">提示!</font>',
                                                   content: '<font color="black">成功截圖!</font>',
                                                   type: 'dark',
                                                });
                                          }

                                       }
                                    });

                              }
                              //$.alert('Your name is ' + name);
                           }
                        },
                        cancel: function() {
                           //close
                        },
                  },
                  onContentReady: function() {


                  }
               });
            */


         }
         //myMap.fitBounds(bounds);
         //});


      });

   })
   </script>
</body>
<script language="JAVASCRIPT" src="rastercoords.js"></script>

</html>
