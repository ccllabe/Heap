<?php //檢查權限
		error_reporting(E_ALL & ~E_NOTICE);
		session_start();
        if(empty($_SESSION['id'])){
			$id_login="登入";
			echo "<script>alert('未登入'); document.location.href='/project/login/login.php';</script>";

        }else{
			$id_login=$_SESSION['id'];
			$id_logout="<a href='/project/login/logout.php' class='nav-item nav-link'>登出</a>";
        }
                
?>

			 
				<a href="/project/index.php" class="navbar-brand">A-TEAM</a>
				
				<button type="button" class="navbar-toggler collapsed" data-toggle="collapse" data-target="#main-nav">
					<span class="menu-icon-bar"></span>
					<span class="menu-icon-bar"></span>
					<span class="menu-icon-bar"></span>
				</button>
				
				<div id="main-nav" class="collapse navbar-collapse">
					<ul class="navbar-nav ml-auto">
						<li><a href="/project/index.php" class="nav-item nav-link active">首頁</a></li>
<?php
	
	if(isset($_SESSION['id']) && $_SESSION['authority']=="teacher"){
						echo'<li class="dropdown">
							<a href="#" class="nav-item nav-link" data-toggle="dropdown">課程管理</a>
							<div class="dropdown-menu">
								<a href="/project/course-outline.php" class="dropdown-item">課程列表</a>
								<a href="/project/editcourse.php" class="dropdown-item">編輯課程</a>
							</div>
						</li>
						<li class="dropdown">
								<a href="#" class="nav-item nav-link">考試出題</a>
								<div class="dropdown-menu">
									<!--<a href="/project/Courses/addcourse.php" class="dropdown-item">新增課程</a>-->
									<a href="/project/ExamInfo/propose.php" class="dropdown-item">題庫</a>
									<a href="/project/ExamInfo/listPaper.php" class="dropdown-item">考卷列表</a>
									<a href="/project/login/register.php" class="dropdown-item">創立學生資料夾</a>
								</div>
							</li>';} 
	if(isset($_SESSION['id']) && $_SESSION['authority']=="student"){
						echo'<li><a href="/project/editcourse.php" class="nav-item nav-link">開始上課</a></li>
							<li><a href="/project/Exam/prequiz.php" class="nav-item nav-link">開始考試</a></li>
							<li><a href="/project/Exam/grade.php" class="nav-item nav-link">查看考試成績</a></li>';} 	
						?>

						<li><a href="/project/login/login.php" class="nav-item nav-link"><?php echo $id_login ?></a></li>
						<li><?php echo $id_logout ?></li>
					</ul>
				</div>
		  

 <script src='/project/js/style.js'></script>

