<!DOCTYPE html>
<html>
<head>
	<meta charset = "utf-8">
	<title>HEAP</title>
  <script src='https://ajax.googleapis.com/ajax/libs/jquery/2.0.0/jquery.min.js'></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.0.0-beta.2.4/owl.carousel.min.js"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.0.0-beta.2.4/assets/owl.carousel.min.css"></link>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.0.0-beta.2.4/assets/owl.theme.default.min.css"></link>
</head>
<body style='background:#333f50;'>
  <link rel="stylesheet" href="home_interface.css" type="text/css">
  <div style='position:relative;top:-8px;left:330px;height:1300px;width:1200px;background:#bdd7ee;'>
    <img src='./web_data/cover_logo.png' width='1200px' height='300px' style=''>\
    <div style='position:relative;top:-85px;height:40px;width:1200px;background:black;'>
      <div id='home_title' style='position:relative;left:15px;top:10px;color:white;'>
      <a href = # style='color:yellow;'>Home</a>&nbsp;&nbsp;|&nbsp;&nbsp;
      <a href = 'tutorial_interface.php'>Tutorial</a>&nbsp;&nbsp;|&nbsp;&nbsp;
      <a href = 'home_summary_interface.php'>Summary</a>&nbsp;&nbsp;|&nbsp;&nbsp;
      <a href = 'log_in_interface.php'>Detect</a>&nbsp;&nbsp;|&nbsp;&nbsp;
			<a href = 'donate_data_interface.php?page=donate_img'>Donate Data</a>&nbsp;&nbsp;|&nbsp;&nbsp;
      <a href = 'download_pre_train_data_interface.php'>Download Pre-train Data</a>
      </div>
    </div>
    <div id='carousel_p' style='position:relative;top:-85px;color:black;'>
      <div class="owl-carousel owl-theme">
        <div class="item" style="max-width: 1000px; margin-right: 10px;">
          <img src="./web_data/home_intro_1_1.png">
          <div style="position:relative;top:30px;width:990px;height:150px;background:#8497b0;padding-left:10px;padding-top:10px;font-size:18px;">
            When you get images of slides through a microscope, what kind of help can you get through our system?
          </div>
        </div>
        <div class="item" style="max-width: 1000px; margin-right: 10px;">
          <img src="./web_data/home_intro_1_2.png">
          <div style="position:relative;top:30px;width:990px;height:150px;background:#8497b0;padding-left:10px;padding-top:10px;font-size:18px;">
            First of all, you can upload your images to your project through the following four methods: local, Google Drive, OneDrive, and Dropbox.
          </div>
        </div>
        <div class="item" style="max-width: 1000px; margin-right: 10px;">
          <img src="./web_data/home_intro_1_3.png">
          <div style="position:relative;top:30px;width:990px;height:150px;background:#8497b0;padding-left:10px;padding-top:10px;font-size:18px;">
            Second, you can choose your tool to make a preliminary judgment of the parasite eggs.
          </div>
        </div>
        <div class="item" style="max-width: 1000px; margin-right: 10px;">
          <img src="./web_data/home_intro_1_4.png">
          <div style="position:relative;top:30px;width:990px;height:150px;background:#8497b0;padding-left:10px;padding-top:10px;font-size:18px;">
            Third, you can see the location of the possible eggs in the project through the Map view interface, and adjust the parameters for further confirmation.
          </div>
        </div>
        <div class="item" style="max-width: 1000px; margin-right: 10px;">
          <img src="./web_data/home_intro_1_5.png">
          <div style="position:relative;top:30px;width:990px;height:150px;background:#8497b0;padding-left:10px;padding-top:10px;font-size:18px;">
            Fourth, you can also use the List view interface to briefly confirm the initial judgment of the eggs list.
          </div>
        </div>
        <div class="item" style="max-width: 1000px; margin-right: 10px;">
          <img src="./web_data/home_intro_1_6.png">
          <div style="position:relative;top:30px;width:990px;height:150px;background:#8497b0;padding-left:10px;padding-top:10px;font-size:18px;">
            Fifth, the positions of these confirmed eggs are superimposed on the selected background image to generate the required results for download.
          </div>
        </div>
      </div>
      <div style="position:relative;top:-232px;left:0px;width:1200px;height:30px;background:black;"></div>
      <!--<div class="dotsCont" style="position:relative;top:-198px;left:0px;width:1200px;height:30px;z-index:1;background:black;">
        <table style="margin-left:auto;margin-right:auto;">
        <tr>
          <td><div class='owl-dot active'>⚇</div></td>
          <td><div class='owl-dot'>⚇</div></td>
          <td><div class='owl-dot'>⚇</div></td>
          <td><div class='owl-dot'>⚇</div></td>
          <td><div class='owl-dot'>⚇</div></td>
          <td><div class='owl-dot'>⚇</div></td>
        </tr>
        </table>
      </div>-->
    </div>
    <div style='position:relative;top:-150px;padding-left:20px;'>
      <h1>HEAP</h1>
      <p>Parasites survive by parasitizing on the host. This relationship often leads to threats to the host's life.<br><br>
        Medical examiners can observe the parasite eggs through a microscope, identify the types of parasites infected by the patient, and provide relevant medical assistance, but this task requires professionals to spend a lot of time for testing.
      </p>
      <p>HEAP provides a web interface to help professionals find the target eggs quickly, with the following functions:</p>
      <ul>
        <li>User's project management interface</li>
        <li>Provide local and cloud upload methods</li>
        <li>Automatically frame the possible location of the target egg</li>
        <li>Friendly bug confirmation interface</li>
        <li>Frame selection results download</li>
      </ul>
    </div>
  </div>
  <script type="text/javascript">
  $('.owl-carousel').owlCarousel({
    loop:true,
    margin:50,
    nav:true,
    navText: ["<div style='position:relative;height:20px;width:20px;'><</div>", "<div style='position:relative;height:20px;width:20px;'>></div>"],
    center:true,
    autoWidth:true,
    items:1,
    singleItem: 1,
    autoplay:true,
    autoplayTimeout:4000,
    autoplayHoverPause:true,
    dots:true
    //dotsContainer: '.dotsCont'
  });
  /*$(".owl-dot").on("mousedown", function(e){
    $(".owl-dot").removeClass("active");
    $(this).toggleClass( "active", e.type === "mousedown" );
  });*/
  </script>
</body>
</html>
