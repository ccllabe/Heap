<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
    <title>A-TEAM</title>
	<!-- Bootstrap -->
	<link href="css/bootstrap-4.3.1.css" rel="stylesheet">
	<link href="css/dashboard.css" rel="stylesheet">
	<link href="css/index.css" rel="stylesheet">

	<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
	<script src="js/jquery-3.3.1.min.js"></script>
	<!-- Include all compiled plugins (below), or include individual files as needed -->
	<script src="js/bootstrap-4.3.1.js"></script>
	<script src="js/popper.min.js"></script>
	<!-- fontawesome -->
	<link href="css/fontawesome/css/all.css" rel="stylesheet">
	<?php
    error_reporting(E_ALL^E_NOTICE^E_WARNING);
    ?>
  </head>
  <body>
  <?php //檢查權限
		error_reporting(E_ALL & ~E_NOTICE);
		session_start();
        if(empty($_SESSION['id'])){
			echo "<header class='header-area overlay'>
			<nav class='navbar navbar-expand-md navbar-dark'>
				<a href='#' class='navbar-brand'>A-TEAM</a>
					<button type='button' class='navbar-toggler collapsed' data-toggle='collapse' data-target='#main-nav'>
						<span class='menu-icon-bar'></span>
						<span class='menu-icon-bar'></span>
						<span class='menu-icon-bar'></span>
					</button>

					<div id='main-nav' class='collapse navbar-collapse'>
						<ul class='navbar-nav ml-auto'>
							<li><a href='#' class='nav-item nav-link active'>首頁</a></li>
							<li><a href='#' class='nav-item nav-link'>登入</a></li>
						</ul>
					</div>
				  <script src='js/style.js'></script>
			</nav>

			<div class='banner'>
				<div class='container'>
					<h1>A-TEAM</h1>
					<h1>虛擬顯微鏡之微生物辨識教學系統</h1>
					<p>以虛擬顯微鏡為架構出發，搭載AI人工智慧辨識技術，教學為主要的目的</p>
					<a href='#content' class='button button-primary'>Learn More</a>
					<!--<embed src='lu0917.mpeg' type='audio/mpeg'>
					<a href='lu0917.mpeg'><img src='audio.JPG' width='40' height='40'></a>
					<iframe src='audio.JPG' <a href='language.php'></iframe>
					<audio controls>
					  <source src='lu0917.mpeg' type='audio/mpeg'>
					Your browser does not support the audio element.
					</audio>-->
				</div>
			</div>
		</header>

		<main>
			<section id='content' class='content'>
				<div class='container'>
					<div class='row'>
						<div class='col-md-4'>
							<h1 style='text-align:center;'>來由</h1>
							<p>市面上鮮少有針對教學目的做使用的微生物辨識的學習系統，而當今處在疫情最嚴峻的時刻，學生無法到校上課，使用本系統即可作為實驗課的替代方案。</p>
						</div>
						<div class='col-md-4'>
							<h1 style='text-align:center;'>理念</h1>
							<p>虛擬顯微鏡之微生物辨識教學系統主要的開發目標是對於有需要使用顯微鏡的使用者，不論是學生、助教或是教授，都能夠透過使用虛擬顯微鏡(本系統)而受惠。</p>
						</div>
						<div class='col-md-4'>
							<h1 style='text-align:center;'>說明</h1>
							<p>本作品的主體為虛擬顯微鏡，結合AI人工智慧辨識技術，對玻片上的微生物進行辨識，讓教授及學生可以透過這套系統，藉由操作虛擬顯微鏡，來進行顯微鏡的學習。</p>
						</div>
					</div>
				</div>
			</section>
		</main>";
        }else if (isset($_SESSION['id'])&& $_SESSION['authority']=="teacher"){
			$id_login=$_SESSION['id'];
			$id_logout="<a href='#' class='nav-item nav-link'>登出</a>";
			echo"<header>
			<nav>
				<div class='pageHeader'>
				  <div class='title'>A-TEAM</div>
				  <div class='userpanel'>
				  <i class='fas fa-user-circle'></i>
				  <span class='username'>$id_login</span>
				  <span><a href='#'>登出</a></span>
				  </div>

				</div>
			</nav>
		  </header>

		  <main>
			<div class='row'>
			  <div class='col1'>
				<div align='center' class='card'>
				  <div class='row'>
				  <div class='cardtitle'><b>課程管理</b></div>
				  </div>
				  <div class='row' style='display: flex; justify-content:space-evenly;'>
					<a href='#'>
					  <div class='card-cont' >
						<i class='fas fa-chalkboard-teacher'></i><span class='text'> 課程列表 </span>
					  </div>
					</a>
					<a href='./editcourse.php'>
					  <div   class='card-cont'>
						<i class='far fa-edit'></i><span class='text'> 編輯課程 </span>
					  </div>
					</a>
					<a href='#'>
					  <div   class='card-cont'>
						<i class='fas fa-university'></i><span class='text'> 編輯學生名單 </span>
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
					<a href='#'>
					  <div class='card-cont' >
						<i class='far fa-sticky-note'></i>
						<span class='text'> 出題 </span>
					  </div>
					</a>
					<a href='#'>
					  <div class='card-cont'>
						<i class='fas fa-cubes'></i><span class='text'> 題庫 </span>
					  </div>
					</a>
					<a href='#'>
					  <div class='card-cont' >
						<i class='far fa-paper-plane'></i></i><span class='text'> 出考卷 </span>
					  </div>
					</a>
					<a href='#'>
					  <div   class='card-cont'>
						<i class='far fa-check-square'></i><span class='text'> 批改考卷 </span>
					  </div>
					</a>
				  </div>
				</div>
			  </div>
			</div>
		  </main>";
		}
		else if (isset($_SESSION['id'])&& $_SESSION['authority']=="student"){
			$id_login=$_SESSION['id'];
			$id_logout="<a href='#' class='nav-item nav-link'>登出</a>";
			echo"<header>
			<nav>
				<div class='pageHeader'>
				  <div class='title'>A-TEAM</div>
				  <div class='userpanel'>
				  <i class='fas fa-user-circle'></i>
				  <span class='username'>$id_login</span>
				  <span><a href='#'>登出</a></span>
				  </div>

				</div>
			</nav>
		  </header>

		  <main>
			<div class='row'>
			  <div class='col1'>
				<div align='center' class='card'>
				  <div class='row'>
				  <div class='cardtitle'><b></b></div>
				  </div>
				  <div class='row' style='display: flex; justify-content:space-evenly;'>
					<a href='./editcourse.php'>
					  <div class='card-cont' >
						<i class='fas fa-chalkboard-teacher'></i><span class='text'> 開始上課 </span>
					  </div>
					</a>
					<a href='#'>
					  <div   class='card-cont'>
						<i class='far fa-edit'></i><span class='text'> 考試 </span>
					  </div>
					</a>

				  </div>
				</div>
			  </div>
			</div>
		  </main>";
        }

	?>


  </body>

</html>
