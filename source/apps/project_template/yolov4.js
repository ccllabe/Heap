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

