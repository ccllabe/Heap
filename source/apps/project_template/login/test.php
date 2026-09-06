<!DOCTYPE html>
<html lang="en">

<?php //檢查權限
        error_reporting(E_ALL & ~E_NOTICE);
        session_start();
        if(empty($_SESSION['id'])){
            echo "<script>alert('未登入'); document.location.href='login.php';</script>";

        }else if($_SESSION['authority']=="student"){
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
    <link href="../css/prepare.css" rel="stylesheet">
    
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

    <script src="https://cdn.bootcss.com/xlsx/0.12.12/xlsx.core.min.js"></script>
    
</head>




    
<body>

<div class="row" style="margin-left: 0px; margin-right: 5px;">
      <div class="file-container" style="display:inline-block;position:relative;overflow: hidden;vertical-align:middle">
        <button class="btn btn-mini btn-success fileinput-button" type="button">导入人员</button>
        <input type="file" id="excel" onchange="loadFile(this.files[0])" accept=".xls" style="position:absolute;top:0;left:0; opacity:0">
        <span id="filename" style="vertical-align: middle">未上传文件</span>
      </div>
       
      <div>
        <table id="dataGrid" class="table text-nowrap"></table>
      </div>
    </div>
  
    <script type="text/javascript">
        function loadFile(file){
            $("#filename").html(file.name);
        }   
    
        $(function() {
            $('#dataGrid').bootstrapTable({
                toolbar : '#toolbar',
                height : 330,
                striped : true,
                pagination : true,
                pageSize : 10,
            //  sortName : 'userName',
                sidePagination : 'client',
                columns : [ 
                    {
                        field : 'userName',
                        title : '用户名称'
                    }, 
                    {
                        field : 'userMobile',
                        title : '手机号'
                    },
                    {
                        title : "操作",
                        formatter : function(value, row, index){
                            var str = "";
                            str += "<a onclick='del(\"userPhone\", \"" + row.userPhone + "\");' "
                            + "href='javascript:void(0);' class='btn btn-mini btn-danger' >"
                            + "删除" + "</a>";
                            return str;
                        }
                    }
                ]
            });
        });
        
        //删除一行数据
        function del(field, value){
            $('#dataGrid').bootstrapTable('remove', {field: field, values: [value]});
        }
        
        //加载数据
        function loadData(rows) {
            $('#dataGrid').bootstrapTable('load', rows);
        }
        
    </script>
    
    <script type="text/javascript">
        //捕捉文件组件更改事件, 如果文件发生改变, 就获取文件内容
        $("#excel").change(function(e) {    
            var files = e.target.files;

            var fileReader = new FileReader();
            fileReader.onload = function(ev) {
                try {
                    var data = ev.target.result,
                        workbook = XLSX.read(data, {
                            type: 'binary'
                        }), // 以二进制流方式读取得到整份excel表格对象
                        rows = []; // 存储获取到的数据
                } catch (e) {
                    console.log('文件类型不正确' + e);
                    bootbox.alert('文件类型不正确');
                    return;
                }
                // 表格的表格范围，可用于判断表头是否数量是否正确
                var fromTo = '';
                // 遍历每张表读取
                for (var sheet in workbook.Sheets) {
                    if (workbook.Sheets.hasOwnProperty(sheet)) {
                        fromTo = workbook.Sheets[sheet]['!ref'];
                        rows = rows.concat(XLSX.utils.sheet_to_json(workbook.Sheets[sheet]));
                        break; // 如果只取第一张表，就取消注释这行
                    }
                }
                for(var i=0; i<rows.length; i++) {
                    rows[i].userName = rows[i].用户名称;
                    rows[i].userMobile = rows[i].手机号;
                    rows[i].userPhone = rows[i].用户名称 + rows[i].手机号;
                }
                
                //加载数据到表格
                loadData(rows);
            };
            // 以二进制方式打开文件
            fileReader.readAsBinaryString(files[0]);   
        });
    </script>


</body>

</html>




