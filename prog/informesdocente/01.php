<?php
//Autor: Rafael Alberto Moreno Parra. https://github.com/ramsoftware

//Importa la librería que valida la sesión
require_once("../../lib/sesiondocente.php");

//Importa la librería de base de datos para la tabla areasconoce
require_once("tabla.php");
$bdInforme = new tabla();

//Ejecuta el informe
$bdInforme->Informe01($_SESSION['usuariocodigo']);

//Cambia el informe
$Pantalla = file_get_contents("../../vista/informesdocente/01.html");
$Pantalla = str_replace("{Datos}", $bdInforme->TextoInforme, $Pantalla);
$Pantalla = str_replace("{etiquetaspastel}", $bdInforme->EtiquetasPastel, $Pantalla);
$Pantalla = str_replace("{valorespastel}", $bdInforme->ValoresPastel, $Pantalla);
$Pantalla = str_replace("{rolnombre}", $_SESSION['rolnombre'], $Pantalla);
$Pantalla = str_replace("{usuarionombre}", $_SESSION['usuarionombre'], $Pantalla);
echo $Pantalla;
