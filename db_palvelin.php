<?php
/* 
 */
//include("header.html");
$palvelin = "localhost";
$kayttaja = "Jukka";
$salasana = "jukka1";
$tietokanta = "db";
$yhteys = new mysqli($palvelin, $kayttaja, $salasana, $tietokanta);
if ($yhteys->connect_error) {
   die("Yhteyden muodostaminen epäonnistui: ".$yhteys->connect_error."<br>");
   }
else echo "Tietokantayhteys luotu.<br>";
//$yhteys->set_charset("utf8");
//var_export($_POST);

if (!isset($_REQUEST['button'])){
  echo "Pääsy kielletty."; 
  }

if ($_REQUEST['button'] == 'tallenna'){  
    
$rekisterinro = strtoupper($_POST['rekisterinro']);
$merkki= $_POST['merkki'];
$vari = $_POST['vari'];
    
$lisayssql = "INSERT INTO auto (rekisterinro, merkki, vari) VALUES ('$rekisterinro', '$merkki', '$vari')";
$tulos = $yhteys->query($lisayssql);
if ($tulos === TRUE) {
   echo "Auto lisätty.";
} else {
   echo "Virhe: " . $lisayssql . "<br>" . $yhteys->error;
}
}
elseif ($_REQUEST['button'] == 'poista'){
    $id = $_GET['id'];    
    $query = "DELETE FROM auto WHERE id = '$id'";
    $tulos = $yhteys->query($query);
    if ($tulos === TRUE) {
      echo "Auto poistettu.";
      } 
    else {
      echo "Virhe: " . $query . "<br>" . $yhteys->error;
      }
    }
?>
<form method="post" action="lisayslomake.php">
 <input type="submit" value="Takaisin">    
</form>    

