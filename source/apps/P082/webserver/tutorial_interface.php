<!DOCTYPE html>
<html>
<head>
	<meta charset = "utf-8">
	<title>HEAP</title>
</head>
<body style='background:#333f50;'>
  <link rel="stylesheet" href="tutorial_interface.css" type="text/css">
  <div style='position:relative;top:-8px;left:330px;height:950px;width:1200px;background:#bdd7ee;'>
    <img src='./web_data/cover_logo.png' width='1200px' height='300px' style=''>
    <div style='position:relative;top:-67px;height:40px;width:1200px;background:black;'>
      <div id='home_title' style='position:relative;left:15px;top:10px;color:white;'>
      <a href = 'home_interface.php'>Home</a>&nbsp;&nbsp;|&nbsp;&nbsp;
      <a href = # style='color:yellow;'>Tutorial</a>&nbsp;&nbsp;|&nbsp;&nbsp;
      <a href = 'home_summary_interface.php'>Summary</a>&nbsp;&nbsp;|&nbsp;&nbsp;
      <a href = 'log_in_interface.php'>Detect</a>&nbsp;&nbsp;|&nbsp;&nbsp;
			<a href = 'donate_data_interface.php?page=donate_img'>Donate Data</a>&nbsp;&nbsp;|&nbsp;&nbsp;
      <a href = 'download_pre_train_data_interface.php'>Download Pre-train Data</a>
      </div>
    </div>
    <div id='outline_title' style='position:relative;top:-80px;left:-300px;width:250px;height:400px;background:white;border-radius:10px;text-align:center;'>
      <h1 style='position:relative;padding-top:10px;'>Outline</h1>
      <table style='position:relative;padding-left:30px;padding-top:-50px;'>
        <tr><td><a class='outline_a' href = '#t_reg_log'><div>Step.1 Register & Log in</div></a></td></tr>
        <tr><td><a class='outline_a' href = '#t_add_proj'><div>Step.2 Add project</div></a></td></tr>
        <tr><td><a class='outline_a' href = '#t_upld_img'><div>Step.3 Upload image</div></a></td></tr>
				<tr><td><a class='outline_a' href = '#t_manage'><div>Step.4 Management</div></a></td></tr>
        <tr><td><a class='outline_a' href = '#t_detect'><div>Step.5 Detect</div></a></td></tr>
        <tr><td><a class='outline_a' href = '#t_confirm'><div>Step.6 Confirm</div></a></td></tr>
        <tr><td><a class='outline_a' href = '#t_export'><div>Step.7 Export</div></a></td></tr>
      </table>
    </div>
    <div id='tutorial_content' style='position:relative;top:-488px;height:690px;overflow-y:auto;'>
      <div style='position:relative;height:200px;padding-left:20px;padding-top:0px;padding-right:55px;line-height:20px;'>
        <h1>HEAP Tutorial</h1>
        <h2>Identify the parasite eggs in the images</h2>
        <p class='tutorial_desc'>Millions of people in the world are suffered from parasite infections[<a href='https://www.who.int/news-room/fact-sheets/detail/soil-transmitted-helminth-infections' target='_blank'>World Health Organization 2020</a>].</br>
          Parasite detection requires long-time trained professionals to spend a lot of time observing and identifying. This platform provides an easy-to-use operation interface to assist in rapid detection.</br></br>
          Here is a brief description of the operation of the platform.</p>
      </div>
      <div style='position:relative;'>
        <div style='position:relative;height:650px;background:#8497b0;'>
					<table><tr>
          	<td><a name='t_reg_log' id='t_reg_log'><div class='tutorial_title'>Step.1 Register & Log in</div></a></td>
						<td><a href ='log_in_interface.php'  target='_blank' style='position:relative;left:20px;top:10px;'><button style=''>Start</button></a></td>
					</tr></table>
          <img src="./web_data/tutorial_1.png" width='1100px' height='550px' style='position:relative;padding-left:20px;padding-top:30px;image-rendering:crisp-edges;'>
        </div>
        <p class='tutorial_desc' style='position:relative;padding-left:20px;padding-right:55px;'>First, you will enter the login page. If you have an account, you can log in directly <font style='color:red;'>(a)</font>; otherwise, please go to the registration page to register before using the platform <font style='color:red;'>(b)</font>. When registering, please fill in your personal information and account password <font style='color:red;'>(c)</font>. After successful registration, you can log in with this account and password.</p>
        <hr style='border: 10px solid black;border-radius: 0px;'>
        <br><br>
      </div>
      <div style='position:relative;'>
        <div style='position:relative;height:650px;background:#8497b0;'>
          <a name='t_add_proj' id='t_add_proj'><div class='tutorial_title'>Step.2 Add project</div></a>
          <img src="./web_data/tutorial_2.png" width='1100px' height='550px' style='position:relative;padding-left:20px;padding-top:30px;'>
        </div>
        <p class='tutorial_desc' style='position:relative;padding-left:20px;padding-right:55px;'>After login, you will enter this page by default or click "Create Project" on the main menu <font style='color:red;'>(a)</font>. Fill in the basic information of the project to create a project for the target slide <font style='color:red;'>(b)</font>. You can enter the created project page through the "Select project" menu or the "Project Management" of the main menu <font style='color:red;'>(c)</font>.</p>
        <hr style='border: 10px solid black;border-radius: 0px;'>
        <br><br>
      </div>
      <div style='position:relative;'>
        <div style='position:relative;height:650px;background:#8497b0;'>
          <a name='t_upld_img' id='t_upld_img'><div class='tutorial_title'>Step.3 Upload image</div></a>
          <img src="./web_data/tutorial_3.png" width='1100px' height='550px' style='position:relative;padding-left:20px;padding-top:30px;'>
        </div>
        <p class='tutorial_desc' style='position:relative;padding-left:20px;padding-right:55px;'>After creating the project, you can see the functions of uploading, testing, confirming, and downloading the project in the function menu. The first step is to upload the image of the target slide. After selecting the item, enter this page by default, or click "Upload img" in the function menu <font style='color:red;'>(a)</font>. Fill in the image information and upload the image <font style='color:red;'>(b)</font>. There are four upload methods, including local, Google Drive, OneDrive and Dropbox <font style='color:red;'>(b-1)(b-2)</font>.</p>
        <hr style='border: 10px solid black;border-radius: 0px;'>
        <br><br>
      </div>
			<div style='position:relative;'>
        <div style='position:relative;height:650px;background:#8497b0;'>
          <a name='t_manage' id='t_manage'><div class='tutorial_title'>Step.4 Management</div></a>
          <img src="./web_data/tutorial_4.png" width='1100px' height='550px' style='position:relative;padding-left:20px;padding-top:30px;'>
        </div>
        <p class='tutorial_desc' style='position:relative;padding-left:20px;padding-right:55px;'>Click "Summary" in the function menu, the files used in the frame selection process and the confirmation status will be displayed <font style='color:red;'>(a)</font>. Use the pie chart to display the confirmation status of the eggs detected by the project, which is convenient for users to quantify the eggs of parasites <font style='color:red;'>(b)</font>. The description data of the project, the image information used, and the preliminary identification information can be modified in this interface to facilitate user management of the project <font style='color:red;'>(c)</font>.</p>
        <hr style='border: 10px solid black;border-radius: 0px;'>
        <br><br>
      </div>
      <div style='position:relative;'>
        <div style='position:relative;height:650px;background:#8497b0;'>
          <a name='t_detect' id='t_detect'><div class='tutorial_title'>Step.5 Detect</div></a>
          <img src="./web_data/tutorial_5.png" width='1100px' height='550px' style='position:relative;padding-left:20px;padding-top:30px;'>
        </div>
        <p class='tutorial_desc' style='position:relative;padding-left:20px;padding-right:55px;'>After uploading the image, you can set the identification parameters to initially identify the eggs from the image. Click "Detection(Lab)" in the function menu to enter this page <font style='color:red;'>(a)</font>. Fill in the identification information and select the tool to identify the parasite egg type in the target image <font style='color:red;'>(b)</font>.The target image is derived from an image uploaded in the past <font style='color:red;'>(b-1)</font>. The menu will display the type of parasite eggs currently available for identification and the tools that support this type <font style='color:red;'>(b-2)</font>.</p>
        <hr style='border: 10px solid black;border-radius: 0px;'>
        <br><br>
      </div>
      <div style='position:relative;'>
        <div style='position:relative;height:720px;background:#8497b0;'>
          <a name='t_confirm' id='t_confirm'><div class='tutorial_title'>Step.6 Confirm</div></a>
          <p class='tutorial_desc' style='position:relative;padding-left:20px;padding-right:55px;'>After the initial identification is performed inside the platform, the "Map view" and "List view" pages provided by the platform can be found from the function menu to allow users to verify the selected parasite eggs.</p>
          <img src="./web_data/tutorial_6_1.png" width='1100px' height='550px' style='position:relative;padding-left:20px;padding-top:0px;'>
        </div>
        <p class='tutorial_desc' style='position:relative;padding-left:20px;padding-right:55px;'>The "Map View" page displays the initial recognition result of the platform on the image <font style='color:red;'>(a)</font>. The page contains a display interface and a menu to operate the interface <font style='color:red;'>(b)</font>. You can first understand the operation of the page through the description <font style='color:red;'>(b-1)</font>. The thumbnail shows the location of the original image and the location of the parasite egg box. The user can change the display position of the original image by using the arrow keys and clicking the box on the thumbnail <font style='color:red;'>(b-2)</font>. The menu provides selection of the background image and the results of different identification methods <font style='color:red;'>(b-4)</font>. The mouse wheel can control the images of different depth of fields displayed in the background, which can be visually inspected by technicians. The user can drag the confidence threshold bar to filter out the predicted candidate eggs <font style='color:red;'>(b-3)</font>, and then click the mouse to confirm and delete the predicted candidate eggs <font style='color:red;'>(c)</font>.</p>
        <div style='position:relative;height:650px;background:#8497b0;'>
          <img src="./web_data/tutorial_6_2.png" width='1100px' height='550px' style='position:relative;padding-left:20px;padding-top:30px;'>
        </div>
        <p class='tutorial_desc' style='position:relative;padding-left:20px;padding-right:55px;'>The "List View" page summarizes the recognition results in a list <font style='color:red;'>(a)</font>. The page contains a display interface and a menu to operate the interface <font style='color:red;'>(b)</font>. A more concise interface is provided to allow users to quickly view the confirmation status of candidate parasite eggs. You can first understand the operation of the page through the description <font style='color:red;'>(b-1)</font>. Users can choose to list the results of different identification methods <font style='color:red;'>(b-2)</font>. In order to get a clearer understanding of the confirmation status of the eggs, you can select "All", "Undetermined", "Confirm", and "Remove" to display the eggs in four different confirmed states on the interface <font style='color:red;'>(b-4)</font>. Similar to the "Map View" page, the user can drag the confidence threshold bar to filter out the predicted candidate eggs <font style='color:red;'>(b-3)</font>, and then click the mouse to confirm and delete the predicted candidate eggs <font style='color:red;'>(c)</font>.</p>
        <hr style='border: 10px solid black;border-radius: 0px;'>
        <br><br>
      </div>
      <div style='position:relative;'>
        <div style='position:relative;height:650px;background:#8497b0;'>
          <a name='t_export' id='t_export'><div class='tutorial_title'>Step.7 Export</div></a>
          <img src="./web_data/tutorial_7.png" width='1100px' height='550px' style='position:relative;padding-left:20px;padding-top:30px;'>
        </div>
        <p class='tutorial_desc' style='position:relative;padding-left:20px;padding-right:55px;'>After the above series of steps, the user can obtain the result of selecting the egg of the target slide. After clicking "Export" in the function menu, you can go to the page where the results are generated <font style='color:red;'>(a)</font>. The user can select an appropriate background image to display the frame selection of the eggs <font style='color:red;'>(b)</font>. The image will be processed in the background, and then a download link will be generated <font style='color:red;'>(c)</font>. Click the download link to get a compressed file of the result <font style='color:red;'>(d)</font>.</p>
        <hr style='border: 10px solid black;border-radius: 0px;'>
        <br><br>
      </div>
    </div>
  </div>
  <script type="text/javascript">
  </script>
</body>
</html>
