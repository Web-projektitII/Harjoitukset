<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
        <style>
        html {
            height:100%;
            }

        body {	
            position:relative;
            min-height:100vh;
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

        #footer p {text-align:center;font-size:1.2rem;font-weight:bold;}
        </style>
    </head>
    <body>
        <?php
        // put your code here
        $kieli = "fi";
        include("sanasto_$kieli.php");
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
