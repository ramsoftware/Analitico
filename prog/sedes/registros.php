<?php
//Autor: Rafael Alberto Moreno Parra. https://github.com/ramsoftware

//Importa la librería que valida la sesion
require_once("../../lib/sesionbasica.php");

//Importa la librería genérica para bases de datos y la instancia
require_once("tabla.php");
$Tabla = new tabla();

//¿Cuál conjunto de registros va a traer?
$Posicion = isset($_GET["PosTabla"]) ? abs(intval($_GET["PosTabla"])) : 0;

//Para mostrar el listado de registros
$Datos = $Tabla->DatosGrid($Posicion);

//Paginación
$PaginaAnterior = $Posicion > $Tabla->Mostrar ? $Posicion - $Tabla->Mostrar : 0;
$PaginaSigue = $Posicion + $Tabla->Mostrar;

//Respuesta HTML
$Pantalla = file_get_contents($Tabla->registros());
$Pantalla = str_replace("{Datos}", $Datos, $Pantalla);
$Pantalla = str_replace("{anterior}", $PaginaAnterior, $Pantalla);
$Pantalla = str_replace("{siguiente}", $PaginaSigue, $Pantalla);
$Pantalla = str_replace("{rutaprog}", $Tabla->rutaprog(), $Pantalla);
$Pantalla = str_replace("{rutavista}", $Tabla->rutavista(), $Pantalla);
$Pantalla = str_replace("{tablavisual}", $Tabla->TablaVisual, $Pantalla);
$Pantalla = str_replace("{rolnombre}", $_SESSION['rolnombre'], $Pantalla);
$Pantalla = str_replace("{usuarionombre}", $_SESSION['usuarionombre'], $Pantalla);
echo $Pantalla;