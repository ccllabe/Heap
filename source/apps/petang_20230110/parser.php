<pre>
<?php
error_reporting(E_ALL & ~E_NOTICE);

$dbList = array();
$dbList[] = "ProSiteProfiles";
$dbList[] = "ProSitePattern";
$dbList[] = "MobiDBLite";
$dbList[] = "CDD";
$dbList[] = "Gene3D";
$dbList[] = "G3DSA";
$dbList[] = "Pfam";
$dbList[] = "PANTHER";
$dbList[] = "SUPERFAMILY";
$dbList[] = "Coils";
$dbList[] = "SMART";
$dbList[] = "TIGRFAM";
$dbList[] = "PIRSF";
$dbList[] = "PRINTS";
$dbList[] = "GO";
$dbList[] = "Reactome";
$dbList[] = "MetaCyc";
   


$entrylListAll = array();
$dbLabelList = array();
$dbLabelList['ID'] = 'ID';
$dbLabelList['token'] = 'token';
$dbLabelList['length'] = 'length';



$file = "GCA_000002825.2_NYU_TvagG3_2_protein.faa.tsv";
#$file = "./test.1000.tsv";
$f= fopen($file, 'r');
while(!feof($f)){
   $line = trim(fgets($f));
   $tmp=$tmp2=$tmp3='';
   if($line=='') continue;
   
   foreach($dbList as $eachDb){
      $line = preg_replace("/$eachDb/","\t___\t$eachDb", $line);
   }//end foreach
   
   $a = explode("\t___\t", $line);
   #print_R($a);
   
   
   $entryList1 = array();
   $entryList2 = array();
   $tmp3 = explode("\t",$a[0]);
   $entryList2["ID"]=array_shift($tmp3);
   $entryList2["token"]=array_shift($tmp3);
   $entryList2["length"]=implode("\t",$tmp3);
   for($i=1; $i<count($a);$i++){
      $tmp = preg_split("/[\s:]/", trim($a[$i]));
      $dbLabel = array_shift($tmp);
      #echo $dbLabel."\n";
      #print_r($tmp);
      
      $tmp2 = str_replace($dbLabel, "", trim($a[$i]));
      #$tmp2 = str_replace(":", "", trim($a[$i]));
      $tmp2 = str_replace(":", "", $tmp2);
      $tmp2 = str_replace("|", "", $tmp2);
      $entryList1[$dbLabel][] = trim($tmp2);
   }//end for
   #echo "=================\n";
   foreach($entryList1 as $dbLabel => $entryList){
      $entryList = array_unique($entryList);
      $entryList2[$dbLabel] = implode(";", $entryList);
      $dbLabelList[$dbLabel] = $dbLabel;
   }//end foreach
   $entrylListAll[] = $entryList2;
   #print_R($entryList2);
   #exit;
   #echo "\n========================\n";
   #$c++; if($c>=200) break;
}//end while
fclose($f);


#print_R($entrylListAll);exit;

echo "<table border=1><tr>";
foreach($dbLabelList as $dbLabel){
	echo "<td>".$dbLabel."</td>";
}//end foreach
echo "</tr>";
#exit();




foreach($entrylListAll as $eachEntry){
	echo "<tr>";
	foreach($dbLabelList as $dbLabel){		
		echo "<td>";
		echo $eachEntry[$dbLabel]."";
		echo "</td>";
	}//end foreach
	echo "</tr>";
}//end foreach


?>
