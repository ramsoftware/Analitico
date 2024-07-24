<?php
//Autor: Rafael Alberto Moreno Parra. https://github.com/ramsoftware

//Importa la librería que valida la sesion
require_once("../../lib/sesionbasica.php");

//Criterio de búsqueda
$Criterio = "";
foreach ($_GET as $Clave=>$Valor){
	if ($Clave != "PosTabla")
		$Criterio .= "&" . $Clave . "=" . $Valor;
}

//¿Cuál conjunto de registros va a traer?
$Posicion = isset($_GET["PosTabla"]) ? abs(intval($_GET["PosTabla"])) : 0;

//Navegación
$Anterior = $Posicion <= 0 ? 0 : $Posicion - 1;
$Siguiente = $Posicion;

//Para mostrar el listado de registros
require_once("tabla.php");
$BuscaFacultad = "";
if (isset($_GET["facultad"])) $BuscaFacultad = $_GET["facultad"];
$Tabla = new tabla();
$Registros = $Tabla->Busqueda($BuscaFacultad, $_GET["nombre"], $Posicion);
$Codigo = -1;
$Facultad = "---";
$Nombre = "---";
$Oculto = "hidden";
if ($Registros != null){
	$Siguiente++;
	$Codigo = $Registros[0];
	$Facultad = $Registros[1];
	$Nombre = $Registros[2];
	$Oculto = "";
}

//Respuesta HTML
$Pantalla = file_get_contents($Tabla->busca2());
$Pantalla = str_replace("{codigo}", $Codigo, $Pantalla);
$Pantalla = str_replace("{facultad}", $Facultad, $Pantalla);
$Pantalla = str_replace("{nombre}", $Nombre, $Pantalla);
$Pantalla = str_replace("{hidden}", $Oculto, $Pantalla);
$Pantalla = str_replace("{criterio}", $Criterio, $Pantalla);
$Pantalla = str_replace("{anterior}", $Anterior, $Pantalla);
$Pantalla = str_replace("{siguiente}", $Siguiente, $Pantalla);
$Pantalla = str_replace("{rutaprog}", $Tabla->rutaprog(), $Pantalla);
$Pantalla = str_replace("{rutavista}", $Tabla->rutavista(), $Pantalla);
$Pantalla = str_replace("{tablavisual}", $Tabla->TablaVisual, $Pantalla);
$Pantalla = str_replace("{rolnombre}", $_SESSION['rolnombre'], $Pantalla);
$Pantalla = str_replace("{usuarionombre}", $_SESSION['usuarionombre'], $Pantalla);
echo $Pantalla;