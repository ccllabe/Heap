<!DOCTYPE html>
<title>課程管理</title>
<head>
<meta charset="utf-8">
<link href="css/bootstrap-4.3.1.css" rel="stylesheet">
<link href="css/dashboard.css" rel="stylesheet">
  
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
<script src="js/upload.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<!-- fontawesome -->  
<script src="https://kit.fontawesome.com/bb5edf3117.js" crossorigin="anonymous"></script>
<?php //檢查權限
		error_reporting(E_ALL & ~E_NOTICE);
		session_start();
        if(empty($_SESSION['id'])){
			$id_login="登入";
			echo "<script>alert('未登入'); document.location.href='login/login.php';</script>";

        }else{
			$id_login=$_SESSION['id'];
			$id_logout="<a href='login/logout.php' class='nav-item nav-link'>登出</a>";
        }
                
?>
</head>
<body>
  <header>
    <nav>
        <div class='pageHeader'>
          <div class='title'>A-TEAM</div>
          <div class='userpanel'><span class='username'><?php echo $_SESSION['id']; ?> </span><i class='fas fa-user-circle'></i></div>
        </div>
    </nav>
  </header>

  <main>
    <div class='row' style='flex-wrap:wrap'>
      <div class='col1'> 
        <div align='center' class='card'>
          <div class='row'>   
          <div class='cardtitle'><b>課程管理</b></div>   
          </div>
          <div class='row' style='display: flex; justify-content:space-evenly;'>
            <a href='/project/course-outline.php'> 
              <div class='card-cont' >
                <i class='fas fa-chalkboard-teacher'></i><span class='text'><h5>課程列表</h5></span>
              </div>
            </a>
            <a href='/project/editcourse.php'>
              <div   class='card-cont'>
                <i class='far fa-edit'></i><span class='text'><h5>編輯課程</h5></span>
              </div>
            </a>
            <a href='/project/login/register.php'>
              <div   class='card-cont'>
                <i class='fas fa-university'></i><span class='text'><h5>編輯學生名單</h5></span>
              </div>
            </a>
          </div>
        </div>
      </div>
      <div class='col2'> 
        <div align='center' class='card'>
          <div class='row'>   
            <div class='cardtitle'><b>考試出題</b></div>   
          </div>
          <div class='row' style='display: flex; justify-content:space-evenly;'>
            <a href='/project/Exam/propose.php'>
              <div class='card-cont' >
                <i class='far fa-sticky-note'></i>  
                <span class='text'><h5>出題</h5></span>
              </div>
            </a>
            <a href='/project/Exam/paperbank.php'>
              <div class='card-cont'>
                <i class='fas fa-cubes'></i><span class='text'><h5>題庫</h5></span>
              </div>
            </a>
            <a href='/project/Exam/setpaper.php'>
              <div class='card-cont' >
                <i class='far fa-paper-plane'></i></i><span class='text'><h5>出考卷</h5></span>
              </div>
            </a>
            <a href='/project/Exam/correct.php'>
              <div   class='card-cont'>
                <i class='far fa-check-square'></i><span class='text'><h5>批改考卷</h5></span>
              </div>
            </a>
          </div>
        </div>
      </div>
    </div>
  </main>
</body>

</html>



<script type='text/javascript'>
/*  $(function(){
    $('.option').on('click',function(){
    $.ajax({
          url:'course-outline.php',
          type:'POST',
          dataType: 'text',
          data:{id:$(this).attr('id')},
          success:function(data){
            $('main').append(data);}
          });
      });
});*/

</script>
