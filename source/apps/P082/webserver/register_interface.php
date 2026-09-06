<!DOCTYPE html>
<html>
<head>
	<meta charset = "utf-8">
	<title>HEAP</title>
</head>
<body>
	<link rel="stylesheet" href="menu_interface.css" type="text/css">
	<div id = "net_title">
    <font>HEAP</font> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
  </div>
	<div id="register_interface">
		<div>
			<h2>Sign up</h2>
			<form action="register_exe.php" method="post" name= "register_form">
			<p><label>Full Name<br>
				<input id = "full_name" name = "full_name" type = "text" size = "30" required='required'>
			</label></p>
			<p><label>Gender<br>
				<input type="radio" id="gender" name="gender" value="Male" checked>Male
				<input type="radio" id="gender" name="gender" value="Female">Female
			</label></p>
			<p><label>Date of birth<br>
				<input id = "date_of_birth" name = "date_of_birth" type = "date" size = "30" required='required'>
			</label></p>
			<p><label>Email Address<br>
				<input id = "email_add" name = "email_add" type = "text" size = "30" required='required' required pattern="[^@\s]+@[^@\s]+\.[^@\s]+" placeholder='Format: ACCOUNT@example.com'>
			</label></p>
			<p><label>Phone number<br>
				<input id = "phone_number" name = "phone_number" type = "text" size = "30" required pattern='(?=^[0-9]{6,10}$)((?=.*[0-9]))^.*$' placeholder='Format: 032118800'>
			</label></p>
			<p><label>Company/School<br>
				<input id = "com_sch" name = "com_sch" type = "text" size = "30">
			</label></p>
			<p><label>Job<br>
				<input type="radio" id="job" name="job" value="Student">Student<br>
				<input type="radio" id="job" name="job" value="Teacher">Teacher<br>
				<input type="radio" id="job" name="job" value="Researcher">Researcher<br>
				<input type="radio" id="job" name="job" value="Other">Other: <input id = "job_other" name = "job_other" type = "text" size = "21">
			</label></p>
			<p><label>Address<br>
				<input id = "address" name = "address" type = "text" size = "30">
			</label></p>
			<p><label>User ID<br>
				(User ID can contain English letters (A-Z, a-z), numbers (0-9))
				<input id = "user_id" name = "user_id" type = "text" size = "30" required='required' required pattern='[a-zA-Z0-9]*'>
			</label></p>
			<p><label>Password<br>
				(Your password must be 8-12 characters, and include at least one lowercase letter, one uppercase letter, and a number.)
				<input id = "password" name = "password" type = "password" size = "30" required='required' required pattern='(?=^[A-Za-z0-9]{8,12}$)((?=.*[A-Z])(?=.*[a-z])(?=.*[0-9]))^.*$'>
				<!--<input id = "password" name = "password" type = "password" size = "30" required='required'>-->
			</label></p>
			<p><label>Confirm Password<br>
				<input id = "confirm_password" name = "confirm_password" type = "password" size = "30"  onblur='checkpwd()' required='required'>
			</label></p>
			<input type="hidden" name="action" value="account_register">
			<input type="submit" name="button" value="Submit">
			</form>
		  <a href = 'log_in_interface.php'>have account</a>
		</div>
	</div>
</body>
</html>
<script type="text/JavaScript">
	function checkpwd(){
		if(password.value != confirm_password.value){
			alert("Password input is inconsistent");
			password.value = "";
			confirm_password.value = "";
		}
	}
</script>
