<?php
//Autor: Rafael Alberto Moreno Parra. https://github.com/ramsoftware

//Importa la librería que valida la sesion
require_once("../../lib/sesioncomite.php");

//Importa la librería genérica para bases de datos y la instancia
require_once("tabla.php");
$Tabla = new tabla();

//Para mostrar el listado de registros
$Datos = $Tabla->DatosGrid($_SESSION['programacodigo']);

//Respuesta HTML
$Pantalla = file_get_contents($Tabla->registros());
$Pantalla = str_replace("{Datos}", $Datos, $Pantalla);
$Pantalla = str_replace("{rutaprog}", $Tabla->rutaprog(), $Pantalla);
$Pantalla = str_replace("{rolnombre}", $_SESSION['rolnombre'], $Pantalla);
$Pantalla = str_replace("{usuarionombre}", $_SESSION['usuarionombre'], $Pantalla);
$Pantalla = str_replace("{programanombre}", $_SESSION['programanombre'], $Pantalla);
echo $Pantalla;