<!DOCTYPE html>
<html>
<head>
<title>Hello</title>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<!--<link rel="stylesheet" href="bootstrap_media_queries.css">-->
<style>
/* vaihtoehto I */
body {	
position:relative;
min-height: 100vh;
}

#footer {
position:absolute;
bottom:0;
height:2.5rem;
width:100%;
} 

.container {
padding-bottom:2.5rem;
}

h1 {color:#FF0000;text-align:center;}
p {color:blue;text-align:center;}
#footer p {text-align:center;font-size:1.2rem;font-weight:bold;}
</style>
</head>
<body>
<?php
//include("header.html");
$palvelin = "localhost";
$kayttaja = "Jukka";
$salasana = "jukka1";
$tietokanta = "db";
$yhteys = new mysqli($palvelin, $kayttaja, $salasana, $tietokanta);
if ($yhteys->connect_error) {
   die("Yhteyden muodostaminen epäonnistui: ".$yhteys->connect_error);
   }
else echo "Tietokantayhteys luotu.";
$yhteys->set_charset("utf8");

$lisayssql = "INSERT INTO auto (rekisterinro, merkki, vari) VALUES ('CEX-267', 'Volvo', 'sininen')";
$tulos = $yhteys->query($lisayssql);
if ($tulos === TRUE) {
   echo "Auto lisätty.";
} else {
   echo "Virhe: " . $lisayssql . "<br>" . $conn->error;
}


$kieli = "fi";
include("sanasto_$kieli.php");
$x = "";
$y = 0;
$z = false;
$arr1 = array();
$arr2 = [];
//Kommentti
/* Kommentti */
#Kommmentti
?>
<div class="container" style="width:50%;margin:auto;">
<h1>
<?php
echo $tekstit['hello'];
?> 
</h1>
<p>Tämä on sivun sisältöä.</p>
</div>
<?php
include("footer.html");
?>
</body>
</html>