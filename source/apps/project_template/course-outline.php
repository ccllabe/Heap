<!DOCTYPE html>
<title>課程管理</title>
<head>
<meta charset="utf-8">
<link href="css/bootstrap-4.3.1.css" rel="stylesheet">
<link href="css/style.css" rel="stylesheet">
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
<script src="js/upload.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<!-- fontawesome -->  
<link href="/project/css/fontawesome/css/all.css" rel="stylesheet">
</head>
<body>
<nav class="navbar navbar-expand-md navbar-color col-12"></nav>  		
<br>
<div class='container'>
<div id="course-outline">
<form enctype='multipart/form-data' id='myform'>
<table>
 
<?php
error_reporting(E_ALL^E_NOTICE^E_WARNING);
session_start();
$dir =$_SESSION['course'].'/';
echo "Course : ".$_SESSION['course']."<br>";
$inp=file_get_contents("{$_SESSION['course']}/outline.json");
$data= json_decode($inp,true);
$n=count($data);
foreach($data as $key => $each){
	
	echo"<tr>";
	$chaptername=$each['chaptername'];
	echo"<td><button name='del-chapter'id='Chapter$key'class='far fa-times-circle' style=display:contents;'></button></td>";
	echo " <td>".$chaptername."</td>";
	echo "<td><button name='edit-chapter' value='Chapter$key'class='fas fa-arrow-right' style='margin-left:10px;display:contents;'></button></td>";
	
	echo "<td><input type='file' name='file[]' id='image' style='margin-left: 30px;'/></td>"; 
	echo"</tr>";
}

//class='Chapter$key'
?>

  </table>
  </form>
  <div id="progBar" style="display:none;">
	<progress value="0" max="100" style="width:750px;"></progress><span id="prog" style="font-weight:bold;">0%</span>
  </div>
  <!--<div id="alertMsg" style="font-size:16px; color:blue; display:none;"></div>-->



</div>

<form><input id='add-chapter'name='Chapter<?php echo $n ?>'type=text placeholder='新增課程'/> <br>
<input type="button" value="Upload image" class="upload" />
</form> <br>
<!--
<br>
<br>
<button class='folder'value='uploadfile'>查看圖片</button> 
<div id='showpath'>uploadfile/ </div>
<div id="selectpath"></div> 顯示資料夾路徑 -->
  </div>
 </div>
</div>



<script>
	window.onload = function(){
            $.get("/project/nav.php", function(data){
                $("nav").html(data);
            })
        }
  $(function() {
     ///////新增課程單元/////////
     
     $("#add-chapter").on('change',function(){
            //addchapter":$(this).attr("name") 
            $.ajax({
                url:'outline-exe.php',
                type:'POST',
                data:{"action":"save","chaptername":$(this).val(),"dirname":$(this).attr("name")},
                success:function(data){
                alert(data);
				window.location.reload();
				},
                error: function(e) {
                        alert(e.status+" error occurred!");
                        } 
                });
        });

		$("button[name=del-chapter]").on('click',function(){
            //addchapter":$(this).attr("name") 
            $.ajax({
                url:'outline-exe.php',
                type:'POST',
                data:{"action":"del","del":$(this).attr("id")},
                success:function(data){
                alert(data);
				window.location.reload();
				},
                error: function(e) {
                        alert(e.status+" error occurred!");
                        } 
                });
		});

		$("button[name=edit-chapter]").on('click',function(){
            //addchapter":$(this).attr("name") 
            $.ajax({
                url:'outline-exe.php',
                type:'POST',
                data:{"chapter":$(this).val()},
                success:function(data){
				//alert(data);
				window.location.href ="editcourse.php";
				},
                error: function(e) {
                        alert(e.status+" error occurred!");
                        } 
                });
		});
		
		
		
    /// 查看圖片 ///
    $(document).on('click','.folder',function() {
    var value= $(this).val();
    $.ajax({
      url:'path.php',
      type:'POST',
      dataType: 'text',
      data:"foldername="+value,
      success:function(data){
        //alert(value);
        $("#selectpath").html(data);
		$("#showpath").html(value+"/");
              },
      error: function(e) {
              alert(e.status+" error occurred to change folder!");
             } 
      });
    });

  });


  </script>

 </body>
</html>
