<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="robots" content="noindex, nofollow">
  <title></title>
  <script src="js/jquery-3.3.1.min.js"></script>
  <script src="https://cdn.ckeditor.com/4.14.0/standard-all/ckeditor.js"></script>
</head>

<body> 
 
    <?php
    error_reporting(E_ALL & ~E_NOTICE);
    $uid =$_POST["uid"];
    if(is_file('./markercontent/'.$uid.'.txt')==false){
      $myfile = fopen('./markercontent/default.txt', "r") or die("Unable to open file!");
      $content = fread($myfile,filesize('./markercontent/default.txt')); 

    }
    else{
      $myfile = fopen('./markercontent/'.$uid.'.txt', "r") or die("Unable to open file!");
      $content = fread($myfile,filesize('./markercontent/'.$uid.'.txt')); 
      }
    fclose($myfile); 
    ?>

	<script>
    $(function(){
      var editor1 = '';
      var id="<?php echo $uid;?>";
      var html = '<?php echo $content?>';	  
      var config = {
      <?php session_start();
      
      if(isset($_SESSION['id']) && $_SESSION['authority']=="student"){  
			echo "readOnly :true ,
		  toolbarCanCollapse: true,
		  toolbarStartupExpanded:false,";}
      ?>
		  };
      
      editor1 = CKEDITOR.appendTo('editor1', config, html);

      
      
	});
  </script>

<div id="editor1"></div>
</body>

</html>