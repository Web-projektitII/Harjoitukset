<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->

<html>
    <head>
        <title>Rekisterinumeron lisäys tietokantaan</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <style>
          label {display:inline-block;width:150px;color:blue;} 
          input[type="submit"] {margin-left:154px;margin-top:4px;}
          span {display:inline-block;width:100px;border:1px solid #808080;}
          a {width:50px;padding:2px;}
          ul {list-style-type: none;}
        </style>
    </head>
    <body>
        <div><p>LISÄTÄÄN UUSI REKISTERINUMERO TIETOKANTAAN</p></div>
        <form action="db_palvelin.php" method="post">
            <label>Rekisterinumero</label>
            <input name="rekisterinro" placeholder="rekisterinumero"><br>
            <label>Merkki</label>
            <input name="merkki" placeholder="merkki"><br>
            <label>Väri</label>
            <input name="vari" placeholder="väri"><br>
            <input type="submit" name="button" value='tallenna'">
        </form>
    <?php
    $palvelin = "localhost";
    $kayttaja = "Jukka";
    $salasana = "jukka1";
    $tietokanta = "db";
    $yhteys = new mysqli($palvelin, $kayttaja, $salasana, $tietokanta);
    if ($yhteys->connect_error) {
      die("Yhteyden muodostaminen epäonnistui: ".$yhteys->connect_error)."<br>";
      }
    $query = "SELECT id,rekisterinro,merkki,vari FROM auto ORDER BY rekisterinro LIMIT 0,25";
    $tulos = $yhteys->query($query);
    if (!$tulos) {
      echo "Virhe: $query<br>".$yhteys->error;
      }
    echo "<ul>";  
    while($rivi = $tulos->fetch_assoc()){
       $id = $rivi['id'];
       $rekisterinro = $rivi["rekisterinro"];
       $merkki = $rivi["merkki"];
       $vari = $rivi["vari"];       
       echo "<li><span>$rekisterinro</span><span>$merkki</span><span>$vari</span><a href=\"muutoslomake.php?id=$id&button=muuta\">Muuta</a><a href=\"db_palvelin.php?id=$id&button=poista\">Poista</a></li>";
       }        
    echo "</ul>";  
    ?>
    </body>
</html>
