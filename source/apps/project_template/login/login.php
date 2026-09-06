<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>登入</title>
	<!--disable cache -->
	<meta http-equiv="Content-Type" content="text/html; charset=gb2312" />
	<meta http-equiv="Pragma" CONTENT="no-cache">
	<meta http-equiv="Cache-Control" CONTENT="no-cache">
	<meta http-equiv="Expires" CONTENT="0">
	
	<!-- Bootstrap -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
	<link href="../css/bootstrap-4.3.1.css" rel="stylesheet">
	<link href="../css/style.css" rel="stylesheet">
	
	<!-- jQuery (necessary for Bootstrap's JavaScript plugins) --> 
	<script src="../js/jquery-3.3.1.min.js"></script>
	<!-- Include all compiled plugins (below), or include individual files as needed -->
	<script src="../js/bootstrap-4.3.1.js"></script>
	<script src="../js/popper.min.js"></script> 
	<!-- fontawesome -->  
	<link href="/project/css/fontawesome/css/all.css" rel="stylesheet"> 
   
	

    <!-- Custom Styles -->
	<style>



#login-bg.container-fluid {
	padding: 0;
	height: 100%;
	position: absolute;
}

/* Background image an color divs*/

.bg-img , .bg-color {
	min-width: 50%;
	vertical-align: top;
	padding: 0;
	margin-left: 0;
	height: 100%;
	background-color:#E2E9EE;
	display: inline-block;
	overflow: hidden;
}

.bg-color {
	margin-left: -5px;
}

.bg-img {
	background-image: url(./bg-image.jpeg);
	background-size: cover;
}

#login{
	padding-top: 10%;
	text-align: center;
	text-transform: uppercase;
}


.login {
	width: 100%;
	height: 500px;
	background-color: #fff;
	padding: 15px;
	padding-top: 30px;
}

.login h1 {
	margin-top: 30px;
	font-weight: bold;
	font-size: 60px;
	letter-spacing: 3px;
}

.login form {
	max-width: 420px;
	margin: 30px auto;
}

.login .btn {
	border-radius: 50px;
	text-transform: uppercase;
	font-weight: bold;
	letter-spacing: 2px;
	font-size: 20px;
	padding: 14px;
	background-color: #00B72E;
}

.form-group input {
	font-size: 20px;
	font-weight: lighter;
	border: none;
	background-color: #F0F0F0;
	color: #465347!important;
	padding: 26px 30px;
	border-radius: 50px;
	transition : 0.2s;
}


	</style>
   


    
</head>

<body>

<!-- body code goes here -->  
		<div id="myAlert" >
		</div>

    <!-- Backgrounds -->
    <div id="login-bg" class="container-fluid">

      <div class="bg-color"></div>
	  <div class="bg-color"></div>

    </div> 

	

    <!-- End Backgrounds -->
    <div class="container" id="login" >

        <div class="row justify-content-center">
        <div class="col-lg-8 ">
          <div class="login">
            <h2>虛擬顯微鏡之微生物辨識教學系統</h2>

			<form name="form" id="form" method="post" action="loginProcess.php"  >
						
					<div class="form-group">              
						<input type="text" class="form-control"  placeholder="Course" id="course"name="course">
					</div>

                    <div class="form-group">
						<input type="text" class="form-control" placeholder="Account" id="uname" name="uname">                      
                    </div>
					
                    <div class="form-group">              
						<input type="password" class="form-control" placeholder="Password"  id="upass"name="upass">
					</div>

                      <div class="form-check">
                    </label>

                    </div>
                  
                    <br>
					<input type="submit" name="submit" id="submit" class="btn btn-lg btn-block btn-success" value="Sign in" />
			</form> 	

<?php
	echo "課程 : \t ITM001<br>";
?>
<?php
	echo "教授身份  -- ";
?> 
Account : ACCOUNT
,
Password : PASSWORD
<br>
<?php
	echo "學生身份  -- ";
?> 
Account : ACCOUNT
,
Password : PASSWORD
				</div>				
			</div>		
        </div>
	</div>

	<?php
	
	if($_GET['msg']==1){
		//echo "<script>alert('無此課程');</script>";
		//echo "<script> document.getElementById('myAlert').innerHTML='grhyter';</script>";
		//echo "<script> document.getElementById('myAlert').innerHTML='<div class='container'><div class='alert alert-success alert-dismissible'><a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a><strong>Success!</strong> 編輯成功！</div></div>'; </script>";
		//echo "<script> document.getElementById('myAlert').innerHTML='<div class='alert alert-success alert-dismissible'><a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a><strong>Success!</strong> 無此課表！</div> </script>";
	}
	elseif($_GET['msg']=='error'){
		//echo "<script>alert('使用者名稱或密碼錯誤');</script>";
	}
	elseif($_GET['msg']=='empty'){
		//echo "<script>alert('請輸入使用者名稱密碼');</script>";
	}

	?>

	
	

  </body>
</html>


