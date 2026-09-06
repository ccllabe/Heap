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
   <script src="html2canvas/html2canvas.min.js"></script>
   <script src="html2canvas/html2canvas.js"></script>
   <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/leaflet-easybutton@2/src/easy-button.css">
   <script src="https://cdn.jsdelivr.net/npm/leaflet-easybutton@2/src/easy-button.js"></script>
   <!--minimap-->
   <script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet-minimap/3.6.1/Control.MiniMap.min.js"
      type="text/javascript"></script>
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet-minimap/3.6.1/Control.MiniMap.min.css" />

   <?php
error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING);
session_start();
$_SESSION['chapter'] = 'Chapter2';
session_start();
if (empty($_SESSION['id'])) {
    echo "<script>alert('未登入'); document.location.href='login/login.php';</script>";
}
if (empty($_SESSION['chapter'])) {
    echo "<script>alert('未選擇課程'); document.location.href='course-outline.php';</script>";
}
?>
</head>

<body>

   <div class="main" >
      <canvas id="myCanvas" style="display:none"></canvas>
      <!--VR mode needle mask  -->
      <!--side bar -->
      <nav id="side-nav">
         <a id="logo" href="index.php">
            <i class="fas fa-home"></i></a>
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
                  <?php echo $_SESSION['id'] ?>
                  <a href="/project/login/logout.php" class="item-icon">
                     <i class="fas fa-sign-out-alt"></i></a>
               </span>
            </div>
         </div>
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
               <strong style="margin-left: .7rem"> 請選擇焦距</strong>
               <button type="button" class="ml-2 mb-1 close" data-dismiss="toast" aria-label="Close"
                  style="font-size: 2.5rem;">
                  <span aria-hidden="true">&times;</span>
               </button>
            </div>
         </div>
      </div>

   </div>
   <script src="vrmode.js"></script>
   <script src="uuid_generator.js"></script>
   <script src="loadMarker.js"></script>
   <script src="yolov4.js"></script>
   <script src="changeFocal.js"></script>
   <script src="deleteMarker.js"></script>
   <script src="barTool.js"></script>
   <script language="JAVASCRIPT" src="rastercoords.js"></script>
   <script type="text/javascript">



   //leaflet;
   ///////////////////
   var course = "<?php echo $_SESSION['course'] ?>";
   var chapter = "<?php echo $_SESSION['chapter'] ?>";
   var url = 'marker.json?nocache=' + (new Date()).getTime();
   //////////////////////


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
         drawnItems,
         YoloV4
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

      var tmpCollection = {
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
         console.log(diff)
         if (diff > 0) {
            //console.log(myZoom.start, myZoom.end, diff)
            //		circle.setRadius(circle.getRadius() / 2);
            console.log(iconx/ (2 * diff),icony/ (2 * diff))
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
            console.log(iconx* -(2 * diff), icony* -(2 * diff))
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

      var magni;
      var flength; //選擇倍率之所有焦距array
      var focal; //透過側邊選擇的焦距
      var baseLayerUrl




      ////////側邊/////
      

      /*$(".main").on('click', function() {
         $('.col2').focus();
      });*/

      

      
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


      vrtool(myMap);


      //截圖
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
      });

      myMap.addControl(drawControl);


      /////////////////載入座標到圖層//////////////////////////////////////////
      getData(collection, drawnItems, greenIcon);

      var coord = rc.unproject([20091.256832, 24022.610432])
      //var circle= new L.CircleMarker([43.2347565056319,-69.63543000000001],{color: "red",weight:1,fillColor: "#f03",fillOpacity: 0.1,radius:5}).addTo(YoloV4);
      console.log(coord)
      editMarker = function(a) {
         console.log(a);
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
         var popup = marker.bindPopup( //(marker.options.UNIID) +
            '</br><button class="btn btn-secondary" onclick="deleteMarker(\'' + UNIID + /|/ + id +
            '\')">Delete Marker</button>' +
            '<button class="btn btn-secondary" onclick="editMarker(\'' + UNIID + '\')">Edit Marker</button>'
            );

         var data = {
            ID: marker.options.uniqueID,
            lat: event.latlng.lat,
            lng: event.latlng.lng,
         }
         collection.feature.push(data);
         console.log(collection);

         loadXMLDoc(collection);

         //marker.on('click', function(){
         //editMarker();
         //}
      });

      where(0, ' ');

      myMap.on('draw:edited', function(e) { //拖移改變圖標位置
         var layers = e.layers;
         layers.eachLayer(function(layer) {
         //deleteMarker2(layer.options.UNIID)
            for (var i in collection.feature) {
               if (collection.feature[i].ID == layer.options.UNIID) {
                  collection.feature[i].lat = layer._latlng.lat;
                  collection.feature[i].lng = layer._latlng.lng;
               }
            }
            loadXMLDoc(collection);
         });
         var layers = miniLayers(baseLayerUrl); //重整minimap 更新圖釘位置
         miniMap.changeLayer(layers);
      });

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

      

   </script>
</body>


</html>