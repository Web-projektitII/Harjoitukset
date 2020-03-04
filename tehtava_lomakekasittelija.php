<?php
/* Responsiivinen lomake, jossa on sekä client- että server-validointi bootstrap 4 perustuen. 
   PHP-debuggausrutiineja omassa tiedostossaan. Kenttäkohtaiset
   virheilmoitukset ja palaute lähetetään Ajaxilla, jos saapuvassa
   lomakkeessa on jokin Ajax-kenttä, muuten virheilmoitukset ja
   lomakkeen kenttien syötetyt arvot palautetaan perinteiseen tapaan 
   lomakkeella selaimelle, joka lähettää sen javascript-komennolla edelleen lomakesivulle.
*/
include('debuggeri.php');
define('DEBUG',true);
date_default_timezone_set ("Europe/Helsinki");
if (file_exists('Exception.php')) require 'Exception.php';
else debuggeri("Virhe:tiedostoa Exception.php ei löydy.");
if (file_exists('PHPMailer.php')) require 'PHPMailer.php';
else debuggeri("Virhe:tiedostoa PHPMailer.php ei löydy.");
if (file_exists('SMTP.php')) require 'SMTP.php';
else debuggeri("Virhe:tiedostoa SMTP.php ei löydy.");

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$to = "jukka.aula@omnia.fi";

function posti($emailTo,$msg,$subject){
if (file_exists('../t/tunnukset.php')) include('../t/tunnukset.php');  
else {
  debuggeri(__FUNCTION__.",virhe:tiedostoa tunnukset.php ei löydy. Sähköpostia ei voi lähettää.");
  return false;	
  }		
$emailFrom = "omniakurssi@email.com";
$emailFromName = "Ohjelmointikurssi";
$emailToName = "";
$mail = new PHPMailer;
$mail->isSMTP(); 
$mail->CharSet = 'UTF-8';
$mail->SMTPDebug = 0; // 0 = off (for production use) - 1 = client messages - 2 = client and server messages
$mail->Host = "smtp.gmail.com"; // use $mail->Host = gethostbyname('smtp.gmail.com'); // if your network does not support SMTP over IPv6
$mail->Port = 587; // TLS only
$mail->SMTPSecure = 'tls'; // ssl is depracated
$mail->SMTPAuth = true;
$mail->Username = $smtpUsername;
$mail->Password = $smtpPassword;
$mail->setFrom($emailFrom, $emailFromName);
$mail->addAddress($emailTo, $emailToName);
$mail->Subject = $subject;
$mail->msgHTML($msg); //$mail->msgHTML(file_get_contents('contents.html'), __DIR__); //Read an HTML message body from an external file, convert referenced images to embedded,
$mail->AltBody = 'HTML messaging not supported';
// $mail->addAttachment('images/phpmailer_mini.png'); //Attach an image file
if(!@$mail->send()){
    $tulos = false;
    debuggeri("Mailer Error: " . $mail->ErrorInfo);
}else{
    $tulos = true;
    debuggeri("Message sent!");
}
return $tulos;
}

//$tulos = posti($to,"Testiä","Testausta lisää.");
//echo "Tulos: $tulos";
//exit;

function palaute($ajax,$message){
if ($ajax){
  echo json_encode($message);
  }    
else {
  echo "<p>$message</p";   
}    
return;   
}

debuggeri(basename($_SERVER['SCRIPT_NAME']).",post:".var_export($_POST,true));
$error_required = array();
$error_numeric = array();
$kentat = array('title','description','release_year','language_id',
   'rental_duration','rental_rate','length','replacement_cost',
   'rating','special_features');
$numeeriset = array('release_year','rental_duration','rental_rate','length','replacement_cost');			

foreach ($kentat AS $kentta) {
  if (empty($_POST[$kentta]) and $kentta <> 'special_features') 
	$error_required[$kentta] = true;
  if (in_array($kentta,$numeeriset) and (!is_numeric(str_replace(",",".",$_POST[$kentta])) or $_POST[$kentta] < 0)) 
	$error_numeric[$kentta] = true;
  }	
  
$ajax = (isset($_POST['validation']) and !empty($_POST['validation']));  
if ($error_required or $error_numeric){
  if ($ajax){
    /* Huom. assosiatiivinen array muuntuu Ajaxissa Javascript-objektiksi.
     * Numeerinen array muuntuu Ajaxissa Javascript-arrayksi.
     */  
    //$virheet = array_merge(array_keys($error_required),array_keys($error_numeric));
    $virheet = array_merge($error_required,$error_numeric);
      if ($virheet) {
      echo json_encode($virheet);
      exit;
      }
    }

  elseif ($error_required or $error_numeric){
    echo "<form name=\"error_form\" action=\"tehtava_db_testi.php\" method=\"post\">";
    if ($error_required){
      /* Lomakkeelta puuttuvat kentät palautetaan virheinä lomakkeelle. */    
      foreach ($error_required AS $error_field => $error_value)
      echo "<input type=\"hidden\" value=\"$error_field\" name=\"error_required[]\">";
      }
    if ($error_numeric){
      foreach ($error_numeric AS $error_field => $error_value)
      echo "<input type=\"hidden\" value=\"$error_field\" name=\"error_numeric[]\">";
      }
    foreach ($kentat AS $kentta){
    /* Lomakkeen kenttien jo syötetyt arvot palautetaan valmiiksi lomakkeelle. */    
      $arvo = isset($_POST[$kentta]) ? $_POST[$kentta] : ""; 
      if (is_array($arvo)) {
        $name = $kentta."[]";	
        foreach ($arvo AS $arvo_i){
          echo "<input type=\"hidden\" value=\"$arvo_i\" name=\"$name\">";
	  }
        }	
      elseif (!empty($arvo)) echo "<input type=\"hidden\" value=\"$arvo\" name=\"$kentta\">";
      }
    echo "</form>";
    echo "<script>document.forms['error_form'].submit();</script>";
    }
  }

$local = in_array($_SERVER['REMOTE_ADDR'],array('127.0.0.1','REMOTE_ADDR' => '::1'));
debuggeri("local:$local");	
if (!$local) {	
  $password = "6#vWHD_$";
  $user = "azure";
  //$server = "localhost:49492";
  $server = "localhost:50431";
  }
else {
  $password = "";
  $user = "root";
  $server = "localhost";	
  }
//echo "server:$server,user:$user";
//exit;

try {
  $db = new mysqli($server,$user,$password,'sakila');
  if (mysqli_connect_errno()){  
    throw new Exception("Virhe tietokantayhteydessä", 42);
    }
  }
catch (Exception $e) {
  if (defined('DEBUG') and DEBUG)  
    echo "Poikkeus ".$e->getCode().": ".$e->getMessage()." ".
    "rivillä ". $e->getLine().", tiedosto: ".$e->getFile()."<br />";
  else echo "Virhe tietokantayhteydessä. Yritä hetken päästä uudestaan.<br>";
  exit;
  }
  


/*
$query = "SELECT f.title,c.name FROM film f,film_category fc,category c WHERE
          fc.film_id = f.film_id AND c.category_id = fc.category_id AND f.title LIKE 'A%'";
$query = "SELECT f.title,c.name FROM film f INNER JOIN film_category fc 
  ON fc.film_id = f.film_id INNER JOIN category c 
  ON c.category_id = fc.category_id 
  WHERE c.name LIKE 'Horror'";
$result = $db->query($query);
$num_results = $result->num_rows;
echo "Sakila film:$num_results riviä.<br>"; 
while ($row = $result->fetch_assoc()){
  echo $row['title']." ".$row['name']."<br>";	
  }
exit;  */
  
$mysql_arvot = array();  
$kentat = array_intersect($kentat,array_keys($_POST));
foreach ($kentat AS $kentta){
  if (isset($_POST[$kentta]) and is_array($_POST[$kentta])) {
    $mysql_arvot[$kentta] = implode(",",$_POST[$kentta]);  
    }	
  elseif ($kentta <> 'special_features') {
    $arvo = trim(strip_tags($_POST[$kentta])); 	
    if (in_array($kentta,$numeeriset)) $arvo = str_replace(",",".",$arvo);
    //$encoding = mb_detect_encoding($arvo);
    //echo "$arvo,encoding:$encoding<br>";
    $mysql_arvot[$kentta] = $db->real_escape_string($arvo);
    //$mysql_arvot[$kentta] = $arvo;
    }
  }	
$kentat = implode(",",$kentat);
$arvot = implode("','",$mysql_arvot);
$db->set_charset('utf8');
$query = "INSERT INTO film ($kentat) VALUES ('$arvot')";
//echo $query."<br>";
$result = $db->query($query);
if (!$result) {
  debuggeri_backtrace("$query\nvirhe:".$db->error);
  debuggeri("kentat:$kentat");
  debuggeri("arvot:'$arvot'");
  palaute($ajax,"Elokuvan tallennus ei onnistunut.");
  }
else {
  debuggeri(basename(__FILE__).",$query");
  $title = isset($_POST['title']) ? trim($_POST['title']) : "";
  $msg = "Elokuva $title on lisätty tietokontaan.";
  posti($to,$msg,"Elokuva lisätty tietokantaan.");
  palaute($ajax,"Elokuvan tiedot on tallennettu.");
  }
if (!$ajax){
echo "<form action=\"tehtava_db_testi.php\"><input type=\"submit\" value=\"OK\"></form>";  
}
?>
