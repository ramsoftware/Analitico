<?php
//Autor: Rafael Alberto Moreno Parra. https://github.com/ramsoftware

//Importa la librería que valida la sesion
require_once("../../lib/sesiondocente.php");

//Importa la librería de base de datos para la tabla
require_once("tabla.php");
$Tabla = new tabla();

$Pantalla = file_get_contents($Tabla->actualiza1());
$Registros = $Tabla->VerRegistroActualiza($_SESSION['usuariocodigo']);
$Pantalla = str_replace("{codigo}", $Registros[0], $Pantalla);
$Pantalla = str_replace("{nombre}", $Registros[1], $Pantalla);
$Pantalla = str_replace("{correo1}", $Registros[2], $Pantalla);
$Pantalla = str_replace("{correo2}", $Registros[3], $Pantalla);
$Pantalla = str_replace("{perfil}", $Registros[4], $Pantalla);
$Pantalla = str_replace("{docente}", $Registros[5], $Pantalla);
$Pantalla = str_replace("{profesional}", $Registros[6], $Pantalla);
$Pantalla = str_replace("{investigacion}", $Registros[7], $Pantalla);
$Pantalla = str_replace("{produccion}", $Registros[8], $Pantalla);
$Pantalla = str_replace("{rutaprog}", $Tabla->rutaprog(), $Pantalla);
$Pantalla = str_replace("{rutavista}", $Tabla->rutavista(), $Pantalla);
$Pantalla = str_replace("{tablavisual}", $Tabla->TablaVisual, $Pantalla);
$Pantalla = str_replace("{rolnombre}", $_SESSION['rolnombre'], $Pantalla);
$Pantalla = str_replace("{usuarionombre}", $_SESSION['usuarionombre'], $Pantalla);
echo $Pantalla;