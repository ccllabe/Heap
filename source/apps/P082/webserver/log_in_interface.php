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
	<div id="access_interface">
		<div>
			<table><tr>
				<td>
					<h2>Login</h2>
					<form action="log_in_exe.php" method="post" name= "log_in_form">
					<table>
						<tr>
						<p><label>
							<td>User ID</td>
							<td><input name = "user_id" type = "text" size = "12" value='demo2'></td>
						</label></p>
						</tr><tr>
						<label>
							<td>Password</td>
							<td><input name = "password" type = "password" size = "12" placeholder='PASSWORD' value=''></td>
						</label>
						</tr>
					</table>
					<br>
					<u><a href = 'register_interface.php' style='font-size:16px;'>Create an account</a></u>
					<br>
					<p style='background-color:gray; color:blue; font-size:16px;'>Testing account: demo  (Password: PASSWORD)</p>
					<p style='background-color:gray; color:blue; font-size:16px;'>Testing account: demo2  (Password: PASSWORD)</p>
					<br>
					<input type="hidden" name="action" value="account_log_in">
					<input type="submit" name="button" value="Login">
					</form><br>
				</td>
				<td></td>
				<td>
					<p>Testing Image:<br>
						You can create a new project and upload these images<br>
						Each project must use images with the same size. </p>
					<br>
					<p>Small image (runs faster):</p>
					<ul style='margin-left:-10px;'>
						<li><a href='./demo_data/Demo_170μm.png' download='Demo_1'>Demo_1.png (170μm)</a></li>
						<li><a href='./demo_data/Demo_180μm.png' download='Demo_2'>Demo_2.png (180μm)</a></li>
						<li><a href='./demo_data/Demo_190μm.png' download='Demo_3'>Demo_3.png (190μm)</a></li>
					</ul>
					<p>Large image:</p>
					<ul style='margin-left:-10px;'>
						<li><a href='./demo_data/Demo2_200μm.png' download='Demo2'>Demo2.png (200μm)</a></li>
					</ul>
				</td>
			</div>
	</div>
</body>
</html>
