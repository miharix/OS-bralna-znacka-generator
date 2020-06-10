<?php
//Avtor: Miha Kočar 27.6.2015
//Zadnji popravek 29.6.2015
if (($_POST["geslo"]!="OSMKLJ")&&($_POST["geslo"]!="OSMKLJ+")){
echo "
<html>
<head>
<meta http-equiv=\"content-type\" content=\"text/html;charset=UTF-8\" />
</head>
<body>
Napačno ste odgovorili na vprašanje.
<body></html>
";}else{

//EASTER EGG ;)
$egg=false;
if ($_POST["geslo"]=="OSMKLJ+"){$egg=true;}

/*
error_reporting(E_ALL);
ini_set('display_errors', 1);*/

require_once('tcpdf_include.php');
require_once('pozicije.php');

$mimes = array(
    'text/csv',
    'text/plain',
    'application/csv',
    'text/comma-separated-values',
    'application/excel',
    'application/vnd.ms-excel',
    'application/vnd.msexcel',
    'text/anytext',
    'application/octet-stream',
    'application/txt',
);

$type = explode(".",$_FILES['userfile']['name']);

if ($_FILES['userfile']['error'] == UPLOAD_ERR_OK               //checks for errors
      && is_uploaded_file($_FILES['userfile']['tmp_name'])
      && in_array($_FILES['userfile']['type'] ,$mimes)
      && (strtolower(end($type)) == 'csv')
      
      ) { //checks that file is uploaded
// echo file_get_contents($_FILES['userfile']['tmp_name']); 
 
 $CsvString = file_get_contents($_FILES['userfile']['tmp_name']);

 $delilec=',';
 if (stristr($CsvString, ';')!=false){$delilec=';';}else{
  if (stristr($CsvString, ',')!=false){$delilec=',';}else{
    if (stristr($CsvString, ':')!=false){$delilec=':';}
  }
 }
 
$lines = explode(PHP_EOL, $CsvString);
$Data = array();
foreach ($lines as $line) {
    $Data[] = str_getcsv($line,$delilec);
}


/*print_r(array_values($Data));
echo '<br><br>';
*/
$kolicina= count($Data)-1;

if ($kolicina>1){

//echo $kolicina;

  /*for ($x=0;$x<$kolicina;$x++){
    echo '<br>'.$Data[$x][1];
  }*/
  


// create new PDF document
$pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);

// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Miha Kočar');
$pdf->SetTitle('Avtomatizacija tiskanja potrdil OŠ bralne značke');
$pdf->SetSubject('PHP verzija');
$pdf->SetKeywords('bralna značka,OŠ,tiskanje,printanje');

// remove default header/footer
$pdf->setPrintHeader(false);
$pdf->setPrintFooter(false);

// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

// set margins
$pdf->SetMargins(0,0);//(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);

// set auto page breaks
$pdf->SetAutoPageBreak(false);//(TRUE, PDF_MARGIN_BOTTOM);

// set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

 //set some language-dependent strings (optional)
if (@file_exists(dirname(__FILE__).'/lang/slv.php')) {
	require_once(dirname(__FILE__).'/lang/slv.php');
	$pdf->setLanguageArray($l);
}


// ---------------------------------------------------------


// add a page

$pdf->SetTextColor(180,20,30);
$pdf->SetDrawColor(170);

$pdf->SetFont('freeserif', 'B', 20,'',false); //nastavi false če hočeš da se da editirat tudi če oseba nima fonta

$regexfilter='/[^\p{Latin}\d\s\p{P}]|[*]|(INSERT|DROP|SELECT|http|https|ftp)/u';


$PrejRazredOddelek=$Data[1][2]; //hrani kateri razred je bil na listu prej - za vmesni beli list
$PrejRazredOddelek=trim($PrejRazredOddelek); //odstrani presledke na začetku in koncu
$PrejRazredOddelek=preg_replace($regexfilter, '', $PrejRazredOddelek); //manjša injection zaščita


  for ($x=1;$x<$kolicina;$x++){
  
  

$pdf->AddPage();

$ImePriimek=$Data[$x][0].' '.$Data[$x][1];
$ImePriimek=trim($ImePriimek); //odstrani presledke na začetku in koncu
$ImePriimek=preg_replace($regexfilter, '', $ImePriimek); //manjša injection zaščita

$Sola=$Data[$x][5];
$Sola=trim($Sola); //odstrani presledke na začetku in koncu
$Sola=preg_replace($regexfilter, '', $Sola); //manjša injection zaščita

$Leto=$Data[$x][4];
$Leto=trim($Leto); //odstrani presledke na začetku in koncu
$Leto=preg_replace($regexfilter, '', $Leto); //manjša injection zaščita

$RazredOddelek=$Data[$x][2];
$RazredOddelek=trim($RazredOddelek); //odstrani presledke na začetku in koncu
$RazredOddelek=preg_replace($regexfilter, '', $RazredOddelek); //manjša injection zaščita

$Mentor=$Data[$x][3];
$Mentor=trim($Mentor); //odstrani presledke na začetku in koncu
$Mentor=preg_replace($regexfilter, '', $Mentor); //manjša injection zaščita

if ($RazredOddelek[0]!=''){

$Razred=$RazredOddelek[0];
if ($Razred=='s'){ //samo za spominska

if ($PraznaVmes==true){
$pdf->AddPage();
}
if($egg==true){
$pdf->Image('ozadja/'.$Razred.'R.jpg', 0, 0, 210, 297, '', '', '', false, 300, '', false, false, 0);
$pdf->setPageMark();
}
$pdf->SetFont('freeserif', $Polje_ImePriimek[$Razred]['t'], $Polje_ImePriimek[$Razred]['f'],'',false);
$pdf->SetXY($Polje_ImePriimek[$Razred]['x'],$Polje_ImePriimek[$Razred]['y'],true);
$pdf->Multicell($Polje_ImePriimek[$Razred]['w'], $Polje_ImePriimek[$Razred]['h'], $ImePriimek , $Okvir, $Polje_ImePriimek[$Razred]['p'],false,1,'','', true, 1,false,true,$Polje_ImePriimek[$Razred]['h']+1,'B',true);


}else{


$Razred=(int)$RazredOddelek[0];
//DODAJ ERROR ČE JE RAZRED VEČ MANJ OD 1,9
if ($Razred>0 && $Razred<10) {


if ($PraznaVmes==true){
if ($Razred!=(int)$PrejRazredOddelek[0]){
$pdf->AddPage();
}
}


$PrejRazredOddelek=$RazredOddelek;

if($egg==true){
$pdf->Image('ozadja/'.$Razred.'R.jpg', 0, 0, 210, 297, '', '', '', false, 300, '', false, false, 0);
$pdf->setPageMark();
}

$pdf->SetFont('freeserif', $Polje_ImePriimek[$Razred-1]['t'], $Polje_ImePriimek[$Razred-1]['f'],'',false);
$pdf->SetXY($Polje_ImePriimek[$Razred-1]['x'],$Polje_ImePriimek[$Razred-1]['y'],true);
$pdf->Multicell($Polje_ImePriimek[$Razred-1]['w'], $Polje_ImePriimek[$Razred-1]['h'], $ImePriimek , $Okvir, $Polje_ImePriimek[$Razred-1]['p'],false,1,'','', true, 1,false,true,$Polje_ImePriimek[$Razred-1]['h']+1,'B',true);

$pdf->SetFont('freeserif', $Polje_Sola[$Razred-1]['t'], $Polje_Sola[$Razred-1]['f'],'',false);
$pdf->SetXY($Polje_Sola[$Razred-1]['x'],$Polje_Sola[$Razred-1]['y'],true);
$pdf->Multicell($Polje_Sola[$Razred-1]['w'], $Polje_Sola[$Razred-1]['h'],$Sola , $Okvir,  $Polje_Sola[$Razred-1]['p'],false,1,'','', true, 1,false,true,$Polje_Sola[$Razred-1]['h']+1,'B',true);

$pdf->SetFont('freeserif', $Polje_Leto[$Razred-1]['t'], $Polje_Leto[$Razred-1]['f'],'',false);
$pdf->SetXY($Polje_Leto[$Razred-1]['x'],$Polje_Leto[$Razred-1]['y'],true);
$pdf->Multicell($Polje_Leto[$Razred-1]['w'], $Polje_Leto[$Razred-1]['h'],$Leto , $Okvir, $Polje_Leto[$Razred-1]['p'],false,1,'','', true, 1,false,true,$Polje_Leto[$Razred-1]['h']+1,'B',true);

$pdf->SetFont('freeserif', $Polje_Oddelek[$Razred-1]['t'], $Polje_Oddelek[$Razred-1]['f'],'',false);
$pdf->SetXY($Polje_Oddelek[$Razred-1]['x'],$Polje_Oddelek[$Razred-1]['y'],true);
$pdf->Multicell($Polje_Oddelek[$Razred-1]['w'], $Polje_Oddelek[$Razred-1]['h'],$RazredOddelek , $Okvir, $Polje_Oddelek[$Razred-1]['p'],false,1,'','', true, 1,false,true,$Polje_Oddelek[$Razred-1]['h']+1,'B',true);

$pdf->SetFont('freeserif', $Polje_Mentor[$Razred-1]['t'], $Polje_Mentor[$Razred-1]['f'],'',false);
$pdf->SetXY($Polje_Mentor[$Razred-1]['x'],$Polje_Mentor[$Razred-1]['y'],true);
$pdf->Multicell($Polje_Mentor[$Razred-1]['w'], $Polje_Mentor[$Razred-1]['h'],$Mentor , $Okvir, $Polje_Mentor[$Razred-1]['p'],false,1,'','', true, 1,false,true,$Polje_Mentor[$Razred-1]['h']+1,'B',true);



}
}//if spominsko


}//if oddelek ''
}




//Close and output PDF document
$pdf->Output('priznanja_bralna.pdf', 'D');//D za vsiljen download I za odprtje v brskaniku
}else{
echo "
<html>
<head>
<meta http-equiv=\"content-type\" content=\"text/html;charset=UTF-8\" />
</head>
<body>
Vaš .csv vsebuje smo 1 vrstico - potrebni sta vsaj 2 (naslovna ter 1 učenec/ka).
<body></html>
";
}

}else{
echo "Brez datoteke ali napaka pri prenosu ali neveljavna datoteka <i>(samo *.scv !)</i>";
//echo  $_FILES['userfile']['type'];
}

}

?>
