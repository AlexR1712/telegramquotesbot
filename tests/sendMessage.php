<?php 

require "src/Telegram.php";
$bot = new Telegram();


var_dump($bot->sendMessage(133433434,"Prueba"));

