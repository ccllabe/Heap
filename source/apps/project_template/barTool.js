function vrtool(map){
   var vr = L.easyButton({
      states: [{
         stateName: 'VR',
         icon: 'fa-glasses',
         title: 'VR mode',
         onClick: function(control) {
            map.dragging.disable();
            map.scrollWheelZoom.disable();
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
                  changeFocal(a,zoom);
                  sleep(0.5);
               } else if (e.originalEvent.wheelDelta / 120 <= 0 && key >0) {
                  key--;
                  a = flength[key];
                  changeFocal(a,zoom);
                  sleep(0.5);
               } else if (e.originalEvent.wheelDelta / 120 <= 0 && key <= 0) {
                  key = flength.length -1;
                  a = flength[key];
                  changeFocal(a,zoom);
                  sleep(0.5);
                  //changeFocal(a);
               }else {
                  key = 0;
                  a = flength[key];
                  changeFocal(a,zoom);
                  sleep(0.5);
                  //changeFocal(a);
               }/*else {
                  map.eachLayer(function(layer) {
                     map.removeLayer(layer);
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
            map.dragging.enable();
            map.scrollWheelZoom.enable();
            $(".leaflet-control-minimap,.leaflet-control-layers,#side-nav-toggle,.unnamed-state-active,.move-active")
               .show();
            $(".edit-marker").parent().show();
            $(myCanvas).hide();
            control.state('VR');
         },
         title: 'undo'
      }]
   }).addTo(map);
}
///////縮圖
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

function findMarker(e) { //到上/下一個marker
   ID = collection.feature[e].ID;
   lat = collection.feature[e].lat;
   lng = collection.feature[e].lng;
   myMap.flyTo([lat, lng], 5);
   editMarker(ID);

}

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
   changeFocal(flength[0],zoom);
   $('.toast').toast('show');
});

$("#focal-length-list").on('click', "li", function() {
   focal = $(this).children().attr("value") //透過側邊欄選擇焦距
   changeFocal(focal,zoom);
});
$('.edit-marker').click(function() {
   $('#lecture').toggle();
})