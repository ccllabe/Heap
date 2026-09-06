function changeFocal(e,zoom) {
   myMap.eachLayer(function(layer) {
      myMap.removeLayer(layer);
   });
   baseLayerUrl = 'http://120.126.17.210/project/' + course + '/' + chapter + '/' + magni + '/' + e +
      '/{z}/{x}/{y}.png'
   var layer = L.tileLayer(baseLayerUrl, {
      noWrap: true,
      minZoom: 3,
      maxZoom: zoom,
      attribution: magni + ' X ' + e + " &micro;m"
   });
   layer.addTo(myMap);
   //rc = new L.RasterCoords(myMap, [15146.5, 10319]);
   rc = new L.RasterCoords(myMap, img);
   myMap.addLayer(drawnItems);
   //myMap.addLayer(YoloV4);
   var layers = miniLayers(baseLayerUrl);
   miniMap.changeLayer(layers);
}