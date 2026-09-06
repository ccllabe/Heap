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
            '</br><button class="btn btn-secondary" onclick="deleteMarker(\'' + UNIID + /|/ + id +
            '\')">Delete Marker</button>' +
            '<button class="btn btn-secondary" onclick="editMarker(\'' + UNIID + '\')">Edit Marker</button>'
            );
         marker.on("customEvent", function(a) {
            layer.removeLayer(marker);
         });
      }
      //console.log(dataArray);
   });
};

function loadXMLDoc(dataArray) {
   var xmlhttp = new XMLHttpRequest();
   xmlhttp.open('POST', 'loadmarker.php', true);
   xmlhttp.setRequestHeader('Content-Type', 'application/json;charset=UTF-8');
   xmlhttp.send(JSON.stringify(dataArray));
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


