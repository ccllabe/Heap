<!DOCTYPE html>
<html lang="en">

<!-- 
    參考網址： https://www.twblogs.net/a/5b7ddf7f2b71776838543ad1
    https://www.itread01.com/content/1550341112.html
    https://www.cnblogs.com/jhxxb/p/10790334.html
-->

<?php //檢查權限
        error_reporting(0);
        session_start();
        if($_SESSION['authority']=="student"){
            echo "<script>alert('權限不符'); document.location.href='login.php';</script>";
        }
?>

<head>
    <!--jquery-->
    <script src="../js/jquery-3.3.1.min.js"></script>
    <!--bootstrap-->
    <link href="https://cdn.bootcss.com/twitter-bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet">
    <script src="../js/popper.min.js"></script>
    <script src="../js/bootstrap-4.3.1.js"></script>
	<link href="../css/bootstrap-4.3.1.css" rel="stylesheet">
    <link href="../css/style.css" rel="stylesheet">
    
    
    <!--fontawesome-->
    <script src="https://cdn.bootcss.com/font-awesome/5.8.1/js/all.min.js"></script>
    <!--bootstrap-table-->
    <link href="https://cdn.bootcss.com/bootstrap-table/1.14.2/bootstrap-table.min.css" rel="stylesheet">
    <script src="https://cdn.bootcss.com/bootstrap-table/1.14.2/bootstrap-table.min.js"></script>
    <!--bootstrap-table-lanuage-->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-table/1.17.1/locale/bootstrap-table-zh-TW.js"></script></script>
    <!--bootstrap-table-export-->
    <script src="https://cdn.bootcss.com/bootstrap-table/1.14.2/extensions/export/bootstrap-table-export.min.js"></script>
    <!--在客户端保存生成的導出文件-->
    <script src="https://cdn.bootcss.com/FileSaver.js/2014-11-29/FileSaver.min.js"></script>
    <!--以XLSX（Excel 2007+ XML格式）格式導出表（SheetJS）-->
    <script src="https://cdn.bootcss.com/xlsx/0.14.2/xlsx.core.min.js"></script>
    <!--最後都包含 tableexport.jquery.plugin（不是tableexport）-->
    <script src="https://unpkg.com/tableexport.jquery.plugin/tableExport.min.js"></script> 
   
    

    <!--導出为PDF文件-->
    <script src="https://unpkg.com/tableexport.jquery.plugin/libs/jsPDF/jspdf.min.js"></script>
    <script src="https://unpkg.com/tableexport.jquery.plugin/libs/jsPDF-AutoTable/jspdf.plugin.autotable.js"></script>
    <!--導入excel文件-->
    <script src=" https://cdnjs.cloudflare.com/ajax/libs/bootstrap-fileinput/5.1.2/js/fileinput.min.js"></script>
    <link href=" https://cdnjs.cloudflare.com/ajax/libs/bootstrap-fileinput/5.1.2/css/fileinput.min.css"></script>
    
    
</head>


<body>
    <nav class="navbar navbar-expand-md navbar-color col-12"></nav>  
    <div id="myAlert">

    </div>



    <div class="table-box" style="margin: 20px; font-size:13px;">
        <div id="toolbar">
            <button id="button" class="btn btn-mini btn-info">InsertRow</button>
            <button id="deleteSelects" class="btn btn-mini btn-info">DeleteSelect</button>
            <button id="getTableData" class="btn btn-mini btn-danger">SaveData</button>
            
            <div class="file-container" style="display:inline-block;position:relative;overflow: hidden;vertical-align:middle">
            <button class="btn btn-mini btn-success fileinput-button" type="button">上傳excel</button>
            <input type="file" id="excel" onchange="loadFile(this.files[0])" accept=".xlsx" style="position:absolute;top:0;left:0; opacity:0">
            <span id="filename" style="vertical-align: middle">未上傳文件</span>
            </div>

        </div>
        <table id="table"></table>
    </div>
    

    <script>
         window.onload = function(){
            $.get("/project/nav.php", function(data){
                $("nav").html(data);
            })
        }
    
        $("#table").bootstrapTable('destroy');
        $(function() {
            let $table = $('#table');
            let $button = $('#button');
            let $getTableData = $('#getTableData');
            let $deleteSelects = $('#deleteSelects');
            let $exportData = $('#exportData');


            $button.click(function() {
                $table.bootstrapTable('insertRow', {
                    index: 0,
                    row: {
                        ID: '',
                        Name: '',
                        Passwd: ''
                    }
                });
            });

            $table.bootstrapTable({
                
                url: '../<?php  echo $_SESSION['course'];?>/Students/student.json',
                toolbar: '#toolbar',
                clickEdit: true,
                locale: 'zh-TW',
                //sortName: "id", //排序列 
                uniqueId:'ID',
                showToggle: true,    //是否顯示詳細檢視和列表檢視的切換按鈕
                pagination: true,       //顯示分頁條
                showColumns: true,
                cache:false, //緩存設定，否則更新頁面會加載上次內容
                showPaginationSwitch: true,     //顯示切換分頁按鈕
                //showRefresh: true,      //顯示刷新按鈕(後悔按鈕)
                //clickToSelect: true,  //點擊row選中radio或CheckBox----會導致json多出欄位儲存Ｘ
                pageNumber: 1,   //初始化載入第一頁，預設第一頁
                pageSize: 10,    //每頁的記錄行數（*）
                search: true, //是否顯示搜尋框功能
                showExport: true,              //是否顯示導出按鈕(此方法是自己寫的目的是判斷終端是電腦還是手機,電腦則返回true,手機返回falsee,手機不顯示按鈕)
                exportDataType: "basic",              //basic', 'all', 'selected'.
                exportTypes:['xlsx', 'txt',],	    //導出類型
                //exportButton: $('#btn_export'),     //爲按鈕btn_export  綁定導出事件  自定義導出按鈕(可以不用)
                exportOptions:{  
                    ignoreColumn: [0,0],            //忽略某一列的索引  
                    fileName: '學生名單',              //文件名稱設置  
                    worksheetName: 'Sheet1',          //表格工作區名稱  
                    tableName: '數據表',  
                    excelstyles: ['background-color', 'color', 'font-size', 'font-weight'],  
                    //onMsoNumberFormat: DoOnMsoNumberFormat,

                    
                },
                //導出excel表格設置<<<<<<<<<<<<<<<<
               
                
                columns: [{
                    checkbox: true
                }, {
                    field: 'ID',
                    title: 'ID',
                }, {
                    field: 'Name',
                    title: 'Name'
                }, {
                    field: 'Passwd',
                    title: 'Passwd'
                }, ],
                /**
                * @param {點擊列的 field 名稱} field
                * @param {點擊列的 value 值} value
                * @param {點擊列的整行數據} row
                * @param {td 元素} $element
                */
                onClickCell: function(field, value, row, $element) {
                    $element.attr('contenteditable', true);
                    $element.blur(function() {
                        let index = $element.parent().data('index');

                        var i=1;
                        var data = $table.bootstrapTable('getData');
                        var rows=$table.bootstrapTable("getData").length;
                        let tdValue = $element.html();
                        //檢查id是否重複
                        if(field=="ID"){
                            while(i<rows)
                            {
                                //ID相同且不跟舊資料自己比對以免刪除舊資料
                                if(tdValue==data[i]['ID'] && index!=i){
                                    document.getElementById("myAlert").innerHTML='<div class="container"><div class="alert alert-danger alert-dismissible"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><strong>Danger! </strong> 重複  Student ID</div></div>';
                                    tdValue="";
                                    break;
                                }    
                                i++;
                            }
                        }
                        saveData(index, field, tdValue);
                    })
                }
            });

            //儲存所有表格，覆寫到json檔
            $getTableData.click(function() {
                //照id排序再儲存
                $('#table').bootstrapTable('refreshOptions', {sortName:"ID" });
                //console.log(JSON.stringify($table.bootstrapTable('getData'))); 
                $.ajax({
                    type: "post",
                    url: "loadstudent.php",
                    data: JSON.stringify($table.bootstrapTable('getData')) ,
                    success: function (data, status) {
                        //console.log(data); 
                        if (status == "success") {
                           document.getElementById("myAlert").innerHTML='<div class="container"><div class="alert alert-success alert-dismissible"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><strong>Success!</strong> 編輯成功！</div></div>';
                        }
                    },
                    error: function () {
                        alert("Error");
                    }
                }); 
                //更新table，否則saveData存錯格
                $('#table').bootstrapTable('refresh');
            });

            //儲存頁面上的表格
            function saveData(index, field, value) {

                
                $table.bootstrapTable('updateCell', {
                    index: index,       //行索引
                    field: field,       //列名
                    value: value        //cell值
                })
                
            }

            $deleteSelects.click(function() {
                
                var ids = $.map($table.bootstrapTable('getSelections'), function (row) {
                    return row.ID
                });
                
                $table.bootstrapTable('remove', {field: 'ID',values: ids });
                
            });

             //加載excel數據，先檢查多個ID是否有重複，有則無法匯入。
            function loadData(rows) {
                var array = [];
                for(var i=0; i<rows.length; i++) {
                    array.push(rows[i].ID)
                }
                var result = array.filter(function(element, index, arr){
                    return arr.indexOf(element) !== index;
                });
                if(result.length!=0){
                    document.getElementById("myAlert").innerHTML='<div class="container"><div class="alert alert-danger alert-dismissible"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><strong>Danger! </strong> 重複  Student ID: ' + result+ ' 無法匯入</div></div>';
                }else{
                    $table.bootstrapTable('load', rows);
                }
                
            }
            //excel處理
            $("#excel").change(function(e) {    
                var files = e.target.files;
                var fileReader = new FileReader();
                fileReader.onload = function(ev) {
                    try {
                        var data = ev.target.result,
                            workbook = XLSX.read(data, {
                                type: 'binary'
                            }), //以二進制流方式讀取得到整份excel表格對象
                            rows = []; //t儲存獲取到的數據
                    } catch (e) {
                        console.log('不符xlsx文件類型' + e);   
                        return;
                    }
                    
                    //表格的表格範圍，可用於判斷表頭是否數量是否正確
                    var fromTo = '';
                    // 遍歷每張表讀取
                    for (var sheet in workbook.Sheets) {
                        if (workbook.Sheets.hasOwnProperty(sheet)) {
                            fromTo = workbook.Sheets[sheet]['!ref'];
                            rows = rows.concat(XLSX.utils.sheet_to_json(workbook.Sheets[sheet]));
                            break; //如果只第一張表格，就取消註釋這行。
                        }
                    }
                    for(var i=0; i<rows.length; i++) {
                        rows[i].ID = rows[i].ID;
                        rows[i].Name = rows[i].Name;
                        rows[i].Passwd = rows[i].Passwd;
                    }
                    
                    //加載數據到表格
                    loadData(rows);
                };
                // 以二進制方式打開文件
                fileReader.readAsBinaryString(files[0]);   
                //因為change事件必須value不同才會觸發,所以在將value重新設定
                document.querySelector('#excel').value = '';
            });
        });

        //印出file name
        function loadFile(file){
            $("#filename").html(file.name);
        }   






    </script>


</body>

</html>




