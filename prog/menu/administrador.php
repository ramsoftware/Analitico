<?php
//Autor: Rafael Alberto Moreno Parra. https://github.com/ramsoftware

//Importa la librería que valida la sesion
require_once("../../lib/sesionadmin.php");

//Respuesta HTML
$Pantalla = file_get_contents("../../vista/menu/administrador.html");
$Pantalla = str_replace("{rolnombre}", $_SESSION['rolnombre'], $Pantalla);
$Pantalla = str_replace("{usuarionombre}", $_SESSION['usuarionombre'], $Pantalla);
echo $Pantalla;
