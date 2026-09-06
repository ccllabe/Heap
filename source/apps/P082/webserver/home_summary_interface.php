<!DOCTYPE html>
<html>
<head>
	<meta charset = "utf-8">
	<script src="https://cdn.plot.ly/plotly-latest.min.js"></script>
	<title>HEAP</title>
</head>
<body style='background:#333f50;'>
  <link rel="stylesheet" href="home_summary_interface.css" type="text/css">
  <div style='position:relative;top:-8px;left:330px;width:1200px;background:#bdd7ee;'>
    <img src='./web_data/cover_logo.png' width='1200px' height='300px' style=''>
    <div style='position:relative;top:-67px;height:40px;width:1200px;background:black;'>
      <div id='home_title' style='position:relative;left:15px;top:10px;color:white;'>
      <a href = 'home_interface.php'>Home</a>&nbsp;&nbsp;|&nbsp;&nbsp;
      <a href = 'tutorial_interface.php'>Tutorial</a>&nbsp;&nbsp;|&nbsp;&nbsp;
      <a href = # style='color:yellow;'>Summary</a>&nbsp;&nbsp;|&nbsp;&nbsp;
      <a href = 'log_in_interface.php'>Detect</a>&nbsp;&nbsp;|&nbsp;&nbsp;
			<a href = 'donate_data_interface.php?page=donate_img'>Donate Data</a>&nbsp;&nbsp;|&nbsp;&nbsp;
      <a href = 'download_pre_train_data_interface.php'>Download Pre-train Data</a>
      </div>
    </div>
    <div id='home_summary_content' style='position:relative;top:-65px;'>
      <div style='position:relative;padding-left:20px;padding-top:0px;padding-right:55px;line-height:20px;'>
        <h1>Summary</h1>
        <h2>Information about the tools used by HEAP</h2>
        <p class='home_summary_desc'>The goal of HEAP is to help professionals find the target eggs quickly. The internal implementation method is to provide tools to predict the target from the pictures uploaded by the user, so that the user can quickly identify the target eggs from the candidate eggs.</p>
        <p class='home_summary_desc'>The tools provided by the platform mainly use deep learning to predict the position of candidate eggs from the map.At present, two models of SSD300 and U-net are used. Before predicting candidate eggs from the graph, we need to prepare a training data set for the model to learn identification rules.</p>
        </br>
      </div>
			<hr style='border: 20px solid #8497b0;border-radius: 0px;'>
			<div style='position:relative;padding-left:20px;padding-top:0px;padding-right:55px;line-height:20px;'>
				<p class='home_summary_desc'>Here is a summary of the number of parasite egg types provided by the platform, the number of tools provided, and the number of models.</p>
			</div>
			<div style='position:relative;width:100%;background:white;'>
				<br>
				<p class='table_desc' style='position:relative;padding-left:20px;'>Table 1. Introduction table of identification tools provided by the platform.</p>
				<table id='tool_table' width="1000px" style='margin-left:auto;margin-right:auto;text-align:center;'>
					<tr><td>Number of parasite egg types</td><td>Number of tools</td><td>Number of models</td></tr>
					<tr><td>17</td><td>3</td><td>22</td></tr>
					<tr>
						<td valign="top"><ul style='text-align:left;'>
							<li><font style='font-style:italic;'>Trichuris trichiura</font> egg</li>
							<li><font style='font-style:italic;'>Ascaris lumbricoides</font> egg(fertilized)</li>
							<li><font style='font-style:italic;'>Diphyllobothrium latum</font> egg</li>
							<li><font style='font-style:italic;'>Enterobius vermicularis</font> egg</li>
							<li><font style='font-style:italic;'>Taenia/Echinococcus</font> egg</li>
							<li><font style='font-style:italic;'>Fasciola hepatica</font> egg</li>
							<li><font style='font-style:italic;'>Schistosoma japonicum</font> egg</li>
							<li><font style='font-style:italic;'>Toxocara canis</font> egg</li>
							<li><font style='font-style:italic;'>Clonorchis sinensis</font> egg</li>
							<li><font style='font-style:italic;'>Fasciolopsis buski</font> egg</li>
							<li><font style='font-style:italic;'>Paragonimus westermani</font> egg</li>
							<li><font style='font-style:italic;'>Echinostoma</font> spp. egg</li>
							<li><font style='font-style:italic;'>Hymenolepis diminuta</font> egg</li>
							<li>Hookworm egg</li>
							<li><font style='font-style:italic;'>Hymenolepis nana</font> egg</li>
							<li><font style='font-style:italic;'>Schistosoma haematobium</font> egg</li>
							<li><font style='font-style:italic;'>Schistosoma mansoni</font> egg</li>
						</ul></td>
						<td valign="top"><ul style='text-align:left;'>
							<li>SSD300</li>
							<li>U-net</li>
							<li>Faster R-CNN</li>
						</ul></td>
						<td valign="top"><ul style='text-align:left;'>
							<li>Generate various models according to different training parameters</li>
						</ul></td>
					</tr>
				</table>
				<br><br>
				<hr style='border: 5px solid #8497b0;border-radius: 0px;'>
				<div id='tool_c_histogram' style='position:relative; width:600px;margin-left:auto;margin-right:auto;'></div>
			</div>
			<hr style='position:relative;top:-8px;border: 20px solid #8497b0;border-radius: 0px;'>
			<div style='position:relative;padding-left:20px;padding-top:0px;padding-right:55px;line-height:20px;'>
				<p class='home_summary_desc'>Here is an introduction to the types of parasite eggs identified by the platform, the source of the training data set, and the training data set information.</p>
			</div>
      <details open='open' style=''>
        <summary style='font-size:24px;position:relative;padding-left:20px;font-weight:700;'>Identified parasite egg type</summary>
        <p class='home_summary_desc' style='position:relative;padding-left:20px;'>This platform currently provides identification of 17 kinds of parasite eggs, and the available tools are shown in the table. Based on the scalability of the platform, more identifiable types of parasite eggs and available tools will be added in the future to help users find the target eggs.</p>
        <div style='position:relative;width:100%;background:white;'>
          <br>
          <p class='table_desc' style='position:relative;padding-left:20px;'>Table 2. Identification of parasite eggs provided by the platform.</p>
          <table id='tool_table' width="1000px" style='margin-left:auto;margin-right:auto;text-align:center;'>
            <tr><td>Parasite egg category</td><td>SSD300</td><td>U-net</td><td>Faster R-CNN</td></tr>
            <tr><td><font style='font-style:italic;'>Trichuris trichiura</font> egg</td><td>✔</td><td>✔</td><td>✔</td></tr>
            <tr><td><font style='font-style:italic;'>Ascaris lumbricoides</font> egg(fertilized)</td><td>✔</td><td>✔</td><td>constructing</td></tr>
            <tr><td><font style='font-style:italic;'>Diphyllobothrium latum</font> egg</td><td>✔</td><td>✔</td><td>✔</td></tr>
            <tr><td><font style='font-style:italic;'>Enterobius vermicularis</font> egg</td><td>✔</td><td>constructing</td><td>✔</td></tr>
            <tr><td><font style='font-style:italic;'>Taenia/Echinococcus</font> egg</td><td>✔</td><td>✔</td><td>✔</td></tr>
            <tr><td><font style='font-style:italic;'>Fasciola hepatica</font> egg</td><td>✔</td><td>✔</td><td>✔</td></tr>
            <tr><td><font style='font-style:italic;'>Schistosoma japonicum</font> egg</td><td>✔</td><td>constructing</td><td>✔</td></tr>
						<tr><td><font style='font-style:italic;'>Toxocara canis</font> egg</td><td>constructing</td><td>constructing</td><td>✔</td></tr>
						<tr><td><font style='font-style:italic;'>Clonorchis sinensis</font> egg</td><td>constructing</td><td>constructing</td><td>✔</td></tr>
						<tr><td><font style='font-style:italic;'>Fasciolopsis buski</font> egg</td><td>✔</td><td>constructing</td><td>constructing</td></tr>
						<tr><td><font style='font-style:italic;'>Paragonimus westermani</font> egg</td><td>✔</td><td>constructing</td><td>constructing</td></tr>
						<tr><td><font style='font-style:italic;'>Echinostoma</font> spp. egg</td><td>✔</td><td>constructing</td><td>constructing</td></tr>
						<tr><td><font style='font-style:italic;'>Hymenolepis diminuta</font> egg</td><td>✔</td><td>constructing</td><td>constructing</td></tr>
						<tr><td>Hookworm egg</td><td>✔</td><td>constructing</td><td>constructing</td></tr>
						<tr><td><font style='font-style:italic;'>Hymenolepis nana</font> egg</td><td>✔</td><td>constructing</td><td>constructing</td></tr>
						<tr><td><font style='font-style:italic;'>Schistosoma haematobium</font> egg</td><td>✔</td><td>constructing</td><td>constructing</td></tr>
						<tr><td><font style='font-style:italic;'>Schistosoma mansoni</font> egg</td><td>✔</td><td>constructing</td><td>constructing</td></tr>
          </table>
					<br><br>
        </div>
        <hr style='position:relative;top:-8px;border: 20px solid #8497b0;border-radius: 0px;'>
      </details>
      <details open='open' style=''>
        <summary style='font-size:24px;position:relative;padding-left:20px;font-weight:700;'>Training data set source</summary>
        <p class='home_summary_desc' style='position:relative;padding-left:20px;'>The source of the data set used for training is the slide provided by the Parasitology Department of Chang Gung University. Scan the slides through the instrument to obtain images of different depth of fields. The table shows the parameters used to scan the slides.</p>
        <div style='position:relative;width:100%;background:white;'>
          <br>
          <p class='table_desc' style='position:relative;padding-left:20px;'>Table 3. Equipment and parameters for scanning slides</p>
          <table id='source_table' width="600px" style='margin-left:auto;margin-right:auto;text-align:center;'>
            <tr><td>Parameter</td><td>Value</td></tr>
            <tr><td>Scanner Agent:</td><td>Yuanli Instrument</td></tr>
						<tr><td>Microscope equipment:</td><td>Olympus BX53</td></tr>
            <tr><td>Scanning software:</td><td>cellSens Dimension</td></tr>
            <tr><td>Objective magnification:</td><td>10X</td></tr>
            <tr><td>Eyepiece magnification:</td><td>10X</td></tr>
						<tr><td>Exposure time:</td><td>Automatic</td></tr>
            <!--<tr><td>Exposure time:</td><td>625μs</td></tr>
            <tr><td>Light source:</td><td>max</td></tr>-->
          </table>
					<br><br>
        </div>
        <hr style='position:relative;top:-8px;border: 20px solid #8497b0;border-radius: 0px;'>
      </details>
      <details open='open' style=''>
        <summary style='font-size:24px;position:relative;padding-left:20px;font-weight:700;'>Training data set information</summary>
        <p class='home_summary_desc' style='position:relative;padding-left:20px;'>The images scanned from the slides need to be manually labeled to generate a data set for training the model. During model training, feature values ​​are extracted from pictures of different sizes according to the model settings. The training data set information corresponding to the identification tools trained with different models are shown in tables. Tables on the website indicated how many images captured and the total depth of field covered in each specimen. This is an important feature of HEAP to simulate the actual experience of microscopic observation. (Note: Training data can be obtained from the "Download Pre-train Data" page.)</p>
        <hr style='position:relative;top:-8px;border: 20px solid #8497b0;border-radius: 0px;'>
        <p style='position:relative;padding-left:20px;font-size:20px;font-weight:700;'>SSD300</p>
        <p class='home_summary_desc' style='position:relative;padding-left:20px;'>Use pictures with a size of 300*300 and manually labeled .xml files with labelImg for training.</p>
        <div style='position:relative;width:100%;background:white;'>
          <br>
          <p class='table_desc' style='position:relative;padding-left:20px;'>Table 4. Data set information for various types of worm eggs using SSD300 training</p>
          <table id='tool_table' width="1050px" style='margin-left:auto;margin-right:auto;text-align:center;'>
            <tr><td>Parasite egg category</td><td>Slide name</td><td>depth of fields(μm)</td><td>Number of images</td><td>Number of training sets</td></tr>
            <tr><td><font style='font-style:italic;'>Trichuris trichiura</font> egg</td><td>original</td><td>110</td><td>12</td><td>340</td></tr>
            <tr><td><font style='font-style:italic;'>Ascaris lumbricoides</font> egg(fertilized)</td><td>(Csp-1)</td><td>80</td><td>9</td><td>1434</td></tr>
            <tr><td><font style='font-style:italic;'>Diphyllobothrium latum</font> egg</td><td>(92W5257)</td><td>80</td><td>9</td><td>159</td></tr>
            <tr><td><font style='font-style:italic;'>Enterobius vermicularis</font> egg</td><td>(0822)</td><td>80</td><td>8</td><td>638</td></tr>
            <tr><td><font style='font-style:italic;'>Echinococcus granulosus</font> egg</td><td>(PS1710)</td><td>100</td><td>11</td><td>290</td></tr>
            <tr><td><font style='font-style:italic;'>Fasciola hepatica</font> egg</td><td>(30-6406)</td><td>100</td><td>11</td><td>346</td></tr>
            <tr><td><font style='font-style:italic;'>Schistosoma japonicum</font> egg</td><td>(PS1301)</td><td>100</td><td>11</td><td>159</td></tr>
						<tr><td><font style='font-style:italic;'>Fasciolopsis buski</font> egg</td><td>Buski_egg</td><td>100</td><td>11</td><td>112</td></tr>
						<tr><td><font style='font-style:italic;'>Paragonimus westermani</font> egg</td><td>(PS1415)</td><td>330</td><td>34</td><td>100</td></tr>
						<tr><td><font style='font-style:italic;'>Echinostoma</font> spp. egg</td><td>(E-16)</td><td>100</td><td>11</td><td>88</td></tr>
						<tr><td><font style='font-style:italic;'>Hymenolepis diminuta</font> egg</td><td>(92W5341)</td><td>100</td><td>11</td><td>285</td></tr>
						<tr><td>Hookworm egg</td><td>(HL E-3)</td><td>90</td><td>10</td><td>80</td></tr>
						<tr><td><font style='font-style:italic;'>Hymenolepis nana</font> egg</td><td>(92W5361)</td><td>100</td><td>11</td><td>84</td></tr>
						<tr><td><font style='font-style:italic;'>Schistosoma haematobium</font> egg</td><td>(92W5123)</td><td>100</td><td>11</td><td>100</td></tr>
						<tr><td><font style='font-style:italic;'>Schistosoma mansoni</font> egg</td><td>(92W5153)</td><td>90</td><td>10</td><td>80</td></tr>
          </table>
					<br><br>
        </div>
        <hr style='position:relative;top:-8px;border: 20px solid #8497b0;border-radius: 0px;'>
        <p style='position:relative;padding-left:20px;font-size:20px;font-weight:700;'>U-net</p>
        <p class='home_summary_desc' style='position:relative;padding-left:20px;'>Use 512*512 pictures and manually labeled .json files with labelme for training.</p>
        <div style='position:relative;width:100%;background:white;'>
          <br>
          <p class='table_desc' style='position:relative;padding-left:20px;'>Table 5. Data set information for various types of worm eggs using U-net training</p>
          <table id='tool_table' width="1050px" style='margin-left:auto;margin-right:auto;text-align:center;'>
            <tr><td>Parasite egg category</td><td>Slide name</td><td>depth of fields(μm)</td><td>Number of images</td><td>Number of training sets</td></tr>
            <tr><td><font style='font-style:italic;'>Trichuris trichiura</font> egg</td><td>original</td><td>110</td><td>12</td><td>485</td></tr>
            <tr><td><font style='font-style:italic;'>Ascaris lumbricoides</font> egg(fertilized)</td><td>(Csp-1)</td><td>80</td><td>9</td><td>655</td></tr>
            <tr><td><font style='font-style:italic;'>Diphyllobothrium latum</font> egg</td><td>(92W5257)</td><td>80</td><td>9</td><td>117</td></tr>
            <tr><td><font style='font-style:italic;'>Echinococcus granulosus</font> egg</td><td>(PS1710)</td><td>100</td><td>11</td><td>265</td></tr>
            <tr><td><font style='font-style:italic;'>Fasciola hepatica</font> egg</td><td>(30-6406)</td><td>100</td><td>11</td><td>287</td></tr>
          </table>
					<br><br>
        </div>
        <hr style='position:relative;top:-8px;border: 20px solid #8497b0;border-radius: 0px;'>
				<p style='position:relative;padding-left:20px;font-size:20px;font-weight:700;'>Faster R-CNN</p>
        <p class='home_summary_desc' style='position:relative;padding-left:20px;'>Use 300*300 pictures and manually labeled .xml files with labelImg for training.</p>
        <div style='position:relative;width:100%;background:white;'>
          <br>
          <p class='table_desc' style='position:relative;padding-left:20px;'>Table 6. Data set information for various types of worm eggs using Faster R-CNN training</p>
          <table id='tool_table' width="1050px" style='margin-left:auto;margin-right:auto;text-align:center;'>
            <tr><td>Parasite egg category</td><td>Slide name</td><td>depth of fields(μm)</td><td>Number of images</td><td>Faster R-CNN (default)<br>Number of training sets</td><td>Faster R-CNN (clear)<br>Number of training sets</td></tr>
            <tr><td><font style='font-style:italic;'>Trichuris trichiura</font> egg</td><td>original</td><td>110</td><td>12</td><td>619</td><td>332</td></tr>
            <tr><td><font style='font-style:italic;'>Diphyllobothrium latum</font> egg</td><td>(92W5257)</td><td>80</td><td>9</td><td>58</td><td>25</td></tr>
						<tr><td><font style='font-style:italic;'>Enterobius vermicularis</font> egg</td><td>(0822)</td><td>80</td><td>8</td><td>480</td><td>268</td></tr>
            <tr><td><font style='font-style:italic;'>Echinococcus granulosus</font> egg</td><td>(PS1710)</td><td>100</td><td>11</td><td>177</td><td>45</td></tr>
            <tr><td><font style='font-style:italic;'>Fasciola hepatica</font> egg</td><td>(30-6406)</td><td>100</td><td>11</td><td>217</td><td>121</td></tr>
            <tr><td><font style='font-style:italic;'>Schistosoma japonicum</font> egg</td><td>(PS1301)</td><td>100</td><td>11</td><td>97</td><td>60</td></tr>
						<tr><td><font style='font-style:italic;'>Toxocara canis</font> egg</td><td>(92W5823)</td><td>100</td><td>11</td><td>56</td><td>17</td></tr>
            <tr><td><font style='font-style:italic;'>Clonorchis sinensis</font> egg</td><td>(PS1218)</td><td>80</td><td>9</td><td>674</td><td>164</td></tr>
          </table>
					<br><br>
        </div>
        <hr style='position:relative;top:-8px;border: 20px solid #8497b0;border-radius: 0px;'>
      </details>
    </div>
  </div>
  <script type="text/javascript">
	var layout = {
	  title: 'Identification parameters'
	};
	var data = [
	  {
	    x: ['Number of parasite egg types', 'Number of tools', 'Number of models'],
	    y: [17, 3, 22],
			marker: {
				color: ['rgb(255,0,0)','rgb(0,255,0)','rgb(0,0,255)']
			},
	    type: 'bar'
	  }
	];
	Plotly.newPlot('tool_c_histogram', data, layout);
  </script>
</body>
</html>
