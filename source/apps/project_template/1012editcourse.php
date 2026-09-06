<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1">
   <title>備課介面</title>
   <!--disable cache -->
   <meta http-equiv="Content-Type" content="text/html; charset=gb2312" />
   <meta http-equiv="Pragma" CONTENT="no-cache">
   <meta http-equiv="Cache-Control" CONTENT="no-cache">
   <meta http-equiv="Expires" CONTENT="0">

   <!--leaflet-->
   <link rel="stylesheet" href="./leaflet/leaflet.css" />
   <script src="./leaflet/leaflet.js"></script>
   <link href="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.0.1/leaflet.css" rel="stylesheet" />
   <!-- Bootstrap -->
   <link href="css/bootstrap-4.3.1.css" rel="stylesheet">
   <link href="css/style.css" rel="stylesheet">
   <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
   <script src="js/jquery-3.3.1.min.js"></script>
   <!-- Include all compiled plugins (below), or include individual files as needed -->
   <script src="js/bootstrap-4.3.1.js"></script>
   <script src="js/popper.min.js"></script>
   <!-- fontawesome -->
   <script src="https://kit.fontawesome.com/bb5edf3117.js" crossorigin="anonymous"></script>
   <!-- editor -->
   <script src="./ckeditor/ckeditor.js"></script>

   <!--screenshot-->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.4/jquery-confirm.min.css">
   <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.4/jquery-confirm.min.js"></script>
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet.draw/1.0.4/leaflet.draw.css" />
   <script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet.draw/1.0.4/leaflet.draw.js"></script>
   <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/0.4.1/html2canvas.js"></script>
   <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/0.5.0-beta4/html2canvas.min.js"></script>
   <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/leaflet-easybutton@2/src/easy-button.css">
   <script src="https://cdn.jsdelivr.net/npm/leaflet-easybutton@2/src/easy-button.js"></script>
   <!--minimap-->
   <script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet-minimap/3.6.1/Control.MiniMap.min.js"
      type="text/javascript"></script>
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet-minimap/3.6.1/Control.MiniMap.min.css" />
   <?php //檢查權限
error_reporting(E_ALL & ~E_NOTICE);
session_start();
if (empty($_SESSION['id'])) {
    $id_login = "登入";
    echo "<script>alert('未登入'); document.location.href='login/login.php';</script>";

} else {
    $id_login = $_SESSION['id'];
    $id_logout = "<a href='login/logout.php' class='nav-item nav-link'>登出</a>";
}

?>
</head>

<body>
   <!-- body code goes here -->
   <nav class="navbar navbar-expand-md navbar-color col-12">
      <!-- <div class="container"> -->
      <a href="index.php" class="navbar-brand">A-TEAM</a>

      <button type="button" class="navbar-toggler collapsed" data-toggle="collapse" data-target="#main-nav">
         <span class="menu-icon-bar"></span>
         <span class="menu-icon-bar"></span>
         <span class="menu-icon-bar"></span>
      </button>

      <div id="main-nav" class="collapse navbar-collapse">
         <ul class="navbar-nav ml-auto">
            <li><a href="index.php" class="nav-item nav-link ">首頁</a></li>
            <?php

if ($_SESSION['authority'] == "teacher") {
    echo '<li class="dropdown active">
								<a href="#" class="nav-item nav-link" data-toggle="dropdown ">課程管理</a>
								<div class="dropdown-menu">
									<a href="courselist.php" class="dropdown-item">課程列表</a>
									<a href="editcourse.php" class="dropdown-item">編輯課程</a>
								</div>
							</li>
							<li class="dropdown">
								<a href="./Exam/propose.php" class="nav-item nav-link">考試出題</a>
								<div class="dropdown-menu">
									<!--<a href="./Courses/addcourse.php" class="dropdown-item">新增課程</a>-->
									<a href="./Exam/propose.php" class="dropdown-item">出題</a>
									<a href="./Exam/paperbank.php" class="dropdown-item">題庫</a>
									<a href="./Exam/setquiz.php" class="dropdown-item">出考卷(教授)</a>
									<a href="./login/register.php" class="dropdown-item">創立學生資料夾</a>
								</div>
							</li>';}
if ($_SESSION['authority'] == "student") {
    echo '<li><a href="editcourse.php" class="nav-item nav-link">開始上課</a></li>
								<li><a href="./Exam/prequiz.php" class="nav-item nav-link">考試</a></li>';}
?>

            <li><a href="login/login.php" class="nav-item nav-link"><?php echo $id_login ?></a></li>
            <li><?php echo $id_logout ?></li>
         </ul>
      </div>
      <!--</div> -->
   </nav>
   <div class="col">
      <!--side bar -->
      <div class="col1">
         <button class="btn1">
            <i id="1open" class="fa fa-angle-down fa-lg menu__icon--open"></i>
            <!--icon-->
            <i id="1close" class="fa fa-angle-up fa-lg menu__icon--close" style="display: none;"></i>
            圖層
         </button><br>

         <div class="collapse" id="layer">
            <input type="checkbox"> 助教標定
            <form><input id='add-layer' type=text placeholder='新增圖層' /></form><br>
         </div>

         <button class="btn2">
            <i id="2open" class="fa fa-angle-down fa-lg menu__icon--open"></i>
            <!--icon-->
            <i id="2close" class="fa fa-angle-up fa-lg menu__icon--close" style="display: none;"></i>
            焦距
         </button><br>

         <div class="collapse" id="focallength">
            <input type="radio" name="focallength" value="220"> 220&micro;m<br>
            <input type="radio" name="focallength" value="240"> 240&micro;m<br>
            <input type="radio" name="focallength" value="260"> 260&micro;m<br>
            <input type="radio" name="focallength" value="270"> 270&micro;m<br>
            <input type="radio" name="focallength" value="280"> 280&micro;m<br>
         </div>

         <button class="btn3">
            <i id="3open" class="fa fa-angle-down fa-lg menu__icon--open"></i>
            <!--icon-->
            <i id="3close" class="fa fa-angle-up fa-lg menu__icon--close" style="display: none;"></i>
            放大倍率
         </button>

         <div class="collapse" id="magnification">
            <input type="radio" name="magnification"> 40&times;<br>
            <input type="radio" name="magnification"> 100&times;<br>
            <input type="radio" name="magnification"> 400&times;<br>
         </div>
      </div>
      <div class="col2" id="mapid"></div>

      <div class="col3" id="lec"></div>
   </div>

   <script type="text/javascript">
   // style
   $(document).ready(function() {
      function adjustNav() {
         var winWidth = $(window).width(),
            dropdown = $('.dropdown'),
            dropdownMenu = $('.dropdown-menu');

         if (winWidth >= 768) {
            dropdown.on('mouseenter', function() {
               $(this).addClass('show')
                  .children(dropdownMenu).addClass('show');
            });

            dropdown.on('mouseleave', function() {
               $(this).removeClass('show')
                  .children(dropdownMenu).removeClass('show');
            });
         } else {
            dropdown.off('mouseenter mouseleave');
         }
      }
      $(window).on('resize', adjustNav);
      adjustNav();


      $(window).resize(function() {
         $('#mapid').height(window.innerHeight - 70);
      });

      $(".btn1").click(function() {
         $("#layer").slideToggle();
         $("#1open,#1close").toggle();
      });
      $(".btn2").click(function() {
         $("#focallength").slideToggle();
         $("#2open,#2close").toggle();
      });
      $(".btn3").click(function() {
         $("#magnification").slideToggle();
         $("#3open,#3close").toggle();
      });
   });
   ////style end////

   ////側邊選項////
   $('#add-layer').on('change', function() {
      var checkbox = $('<input type="checkbox"> s</>');
      $('#layer').append(checkbox);
      //var layername = $(this).val();

   });


   //leaflet;
   var src = 'marker.json?nocache=' + (new Date()).getTime();

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

   function getData(dataArray, layer,icon) {
      $.getJSON(src, function(data) {
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
               UNIID,icon: icon
            }).addTo(layer);
            var id = marker._leaflet_id;
            //console.log(id);
            var popup = marker.bindPopup((marker.options.UNIID) +
               '</br><button onclick="deleteMarker(\'' + UNIID + /|/ + id + '\')">Delete Marker</button>' +
               '<button onclick="editMarker(\'' + UNIID + '\')">Edit Marker</button>');
            marker.on("customEvent", function(a) {
               layer.removeLayer(marker);
            });
         }
         //			console.log(dataArray);
      });
   };

   var src1 = 'new_updated-file_90.json?nocache=' + (new Date()).getTime();

   function getYolo(rc, dataArray, layer, num) {
      $.getJSON(src1, function(yolo_data) {
         var marker;
         for (var i in yolo_data) {
            for (var r in yolo_data[i].objects) {
               if (yolo_data[i].objects[r].confidence) {
                  var data2 = {
                     confidence: yolo_data[i].objects[r].confidence,
                     center: [Math.round(yolo_data[i].objects[r].relative_coordinates.center_x), Math.round(
                        yolo_data[i].objects[r].relative_coordinates.center_y)],
                     height: Math.round(yolo_data[i].objects[r].relative_coordinates.height),
                     width: Math.round(yolo_data[i].objects[r].relative_coordinates.width)
                  }
                  //										var coord = rc.project([82.0152,-90.16479]);
                  //										console.log(coord);
                  circle = new L.CircleMarker(rc.unproject(data2.center), {
                     color: "red",
                     weight: 1,
                     fillColor: "#f03",
                     fillOpacity: 0.05,
                     radius: Math.min(data2.width, data2.height) / num
                  }).addTo(layer);
                  //	var marker = L.marker(rc.unproject(data2.center)).addTo(layer);
               }

            }
            dataArray.push(data2);
         }
         //	console.log(dataArray);
      });
   };

   function loadXMLDoc(dataArray) {
      var xmlhttp = new XMLHttpRequest();
      xmlhttp.open('POST', 'loadmarker.php', true);
      xmlhttp.setRequestHeader('Content-Type', 'application/json;charset=UTF-8');
      xmlhttp.send(JSON.stringify(dataArray));
      console.log("xmlhttp success");
   }


   $(function() {
      //$(document).ready(function(){
      $('#mapid').height(window.innerHeight - 70); //減navbar高度
      var img = [30293, 20638];
      var myMap = L.map('mapid', {
         //center: [0, 0],
         //zoom: 2
      });

      var rc = new L.RasterCoords(myMap, img);
      myMap.setView(rc.unproject([27400, 27600]), 2)
      var drawnItems = new L.FeatureGroup();
      myMap.addLayer(drawnItems);
      num = 64
      var YoloV4 = new L.FeatureGroup();
      myMap.addLayer(YoloV4);

      L.control.layers({}, {
         drawnItems,
         YoloV4
      }).addTo(myMap)

      var yolo_result = []
      getYolo(rc, yolo_result, YoloV4, 64);
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
      });

      myMap.on('zoomend', function(e) {
         myZoom.end = myMap.getZoom();
         var diff = myZoom.start - myZoom.end;

         if (diff > 0) {
            console.log(myZoom.start, myZoom.end, diff)
            //		circle.setRadius(circle.getRadius() / 2);
            YoloV4.clearLayers()
            getYolo(rc, yolo_result, YoloV4, num * (2 * diff));
            num = num * 2 * diff
         } else if (diff < 0) {
            console.log(myZoom.start, myZoom.end, diff)
            YoloV4.clearLayers()
            getYolo(rc, yolo_result, YoloV4, num / -(2 * diff));
            //		circle.setRadius(circle.getRadius() * 2);
            num = num / -(2 * diff)
         }
      });

      //	var bounds = [[0,0], [800,800]];
      L.tileLayer('http://120.126.17.210/project/ITM001/Chapter2/400/100/{z}/{x}/{y}.png', {
         noWrap: true,
         minZoom: 2,
         maxZoom: 8,
         attribution: 'My Tile Server'
      }).addTo(myMap);
      /////////////////載入座標到圖層//////////////////////////////////////////
      

      var coord = rc.unproject([20091.256832, 24022.610432])

      var greenIcon = L.icon({
            iconUrl: 'marker.png',
            shadowUrl: 'pin_shadow.png',
            iconSize:     [36, 52.5], // size of the icon
            shadowSize:   [50, 45],
            iconAnchor:   [18, 52.5], // point of the icon which will correspond to marker's location
            shadowAnchor: [4, 32],  // the same for the shadow
            popupAnchor:  [-3, -76] // point from which the popup should open relative to the iconAnchor
      });

      getData(collection, drawnItems,greenIcon);
      //					var circle= new L.CircleMarker([43.2347565056319,-69.63543000000001],{color: "red",weight:1,fillColor: "#f03",fillOpacity: 0.1,radius:5}).addTo(YoloV4);
      console.log(coord)
      editMarker = function(a) {
         console.log(a);
         //document.getElementById("lec").innerHTML='<iframe src="./generate_html/'+ a +'" frameborder="1" class=" " ;></iframe>';
         $.ajax({
            url: "editor.php",
            data: "uid=" + a,
            datatype: "text",
            type: "POST",
            success: function(data) {
               $("#lec").html(data);
            },
            error: function() {
               alert('error');
            }
         });

      };

      deleteMarker = function(a) {
         var value = a.split("/|/");
         console.log(value[0])
         console.log(a)
         for (var i in collection.feature) {
            if (collection.feature[i].ID == value[0]) {
               collection.feature.splice(i, 1);
               console.log(collection);
               var col_JSON = JSON.stringify(collection);
               //console.log(col_JSON);
            }
         }
         loadXMLDoc(collection);
         console.log(value[1])
         drawnItems.removeLayer(value[1]);
         //marker.fire("customEvent");

         $.ajax({
            url: "delete_markercontent.php",
            data: "uid=" + value[0],
            datatype: "text",
            type: "POST",
            success: function(data) {
               console.log(data);
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
            uniqueID,icon: greenIcon,draggable:true
         }).addTo(drawnItems);
         var id = marker._leaflet_id;
         var popup = marker.bindPopup(uniqueID +
            '</br><button onclick="deleteMarker(\'' + uniqueID + /|/ + id +
            '\')">Delete Marker</button>' +
            '<button onclick="editMarker(\'' + uniqueID + '\')">Edit Marker</button>');

         var data = {
            ID: marker.options.uniqueID,
            lat: event.latlng.lat,
            lng: event.latlng.lng,
         }
         collection.feature.push(data);
         console.log(collection);

         loadXMLDoc(collection);

      });



      //初始先載入縮圖
      where(0);
      //控制縮圖兩種模式
      var toggle = L.easyButton({
         states: [{
            stateName: 'move',
            icon: 'fa-star',
            onClick: function(control) {
               //osm2.remove();
               miniMap.remove();
               where([30, -30]); //固定縮圖
               control.state('fixed');
            }
         }, {
            icon: 'fa-undo',
            stateName: 'fixed',
            onClick: function(control) {
               //osm2.remove();
               miniMap.remove();
               where(0); //可移動縮圖
               control.state('move');
            },
            title: '縮圖'
         }]
      });
      toggle.addTo(myMap);



      //縮圖
      function where(center) {
         var osmAttrib = 'Map data &copy; OpenStreetMap contributors';
         osm2 = new L.TileLayer('http://120.126.17.210/project/pic1/{z}/{x}/{y}.png', {
            noWrap: true,
            minZoom: 0,
            maxZoom: 2,
            attribution: osmAttrib
         });
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

         var pubs2 = new L.LayerGroup();
         var pubs = getData1(pubs2);
         var layers = new L.LayerGroup([osm2, pubs2]);

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


      function getData1(a) {

         $.getJSON(src, function(data) {
            var marker1;
            for (var i in data.feature) {
               var data1 = {
                  ID: data.feature[i].ID,
                  lat: data.feature[i].lat,
                  lng: data.feature[i].lng,
               }
               //console.log(data.feature[i].lat);
               //return new L.CircleMarker([data.feature[i].lat, data.feature[i].lng]);
               //marker1=new L.CircleMarker([27.430185314994723,-39.20888697216043]).addTo(pubs2);
               marker1 = new L.CircleMarker([data.feature[i].lat, data.feature[i].lng], {
                  radius: 1,
               }).addTo(a);
               //new L.CircleMarker([data.feature[i].lat, data.feature[i].lng]);
               //return marker1;
            }
         });
      };

      //截圖
      L.easyButton('<img src="./picture.svg" style="left: -2px;position: absolute;top: 2px;width: 18px;">',
         function(btn, myMap) {
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
            rectangle.enable(); //繪製

         }).addTo(myMap);

      myMap.on(L.Draw.Event.CREATED, (e) => {
         if (e.layerType == 'rectangle') {
            myMap.addLayer(e.layer);
            if (!e.layer.flag) {
               $.confirm({
                  title: '提示!',
                  content: '是否要截圖!',
                  buttons: {
                     confirm: function() {

                        let latlngs = e.layer._latlngs[0] //獲取矩形的經緯度list
                        console.log(latlngs);
                        //let p=e.layer.getLatLngs();
                        miniMap.remove();
                        captureScreenEnd(latlngs); //開始截圖
                        $.alert('成功!');
                        myMap.removeLayer(e.layer); //移除框選
                        where(0);

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

      function captureScreenEnd(latlngs) {
         let bounds = myMap.getBounds(),
            zero = [(bounds._northEast.lat), bounds._southWest.lng],
            //計算當前視窗內的原點經緯度 ==>對應的屏幕座標（位移座標縮放的計算startPoint的偏移量）
            zeroPoint = myMap.latLngToLayerPoint(zero)

         let startPoint = myMap.latLngToLayerPoint(latlngs[1]), //經緯度轉 屏幕座標 計算 起點與寬高
            endPoint = myMap.latLngToLayerPoint(latlngs[3]),
            width = Math.abs(startPoint.x - endPoint.x),
            height = Math.abs(startPoint.y - endPoint.y);

         console.log(zero + zeroPoint);

         html2canvas(document.getElementById('mapid'), {
            useCORS: true, // 底圖跨域
            allowTaint: false
         }).then((canvas) => {
            downloadIamge(canvas, (startPoint.x - zeroPoint.x), (startPoint.y - zeroPoint.y), width, height)

         });

      }

      function downloadIamge(canvas, capture_x, capture_y, capture_width, capture_height) {
         // 創建一個用於擷取的canvas
         var clipCanvas = document.createElement('canvas')
         var pic_max = Math.max(capture_width, capture_height); //確保使用者是拉正方形
         clipCanvas.width = capture_width //capture_width
         clipCanvas.height = capture_height //capture_height
         //擷取圖片

         clipCanvas.getContext('2d').drawImage(canvas, capture_x, capture_y, capture_width, capture_height, 0, 0,
            capture_width, capture_height);
         //clipCanvas.getContext('2d').drawImage(canvas, capture_x, capture_y, pic_max,pic_max , 0, 0, pic_max, pic_max);
         var clipImgBase64 = clipCanvas.toDataURL() //生成圖片url

         /// 下载图片
         let link = document.createElement("a");
            link.href = clipImgBase64;//下载链接
            link.setAttribute("download", new Date().toLocaleString() + "_截图.png");
            link.style.display = "none";//a标签隐藏
            document.body.appendChild(link);
            link.click(); // 点击下载
            document.body.removeChild(link); // 移除a标签



      }

      //myMap.fitBounds(bounds);
      //});
   })
   </script>
</body>
<script language="JAVASCRIPT" src="rastercoords.js"></script>

</html>