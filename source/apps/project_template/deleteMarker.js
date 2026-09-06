deleteMarker = function (a) {
   $.confirm({
      title: "<font color='black'>提示!</font>",
      content: "<font color='black'>確認刪除此標記？</font>",
      type: 'dark',
      buttons: {
         確定: function () {
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
         },
         取消: function () {

         }
      }
   });

};
