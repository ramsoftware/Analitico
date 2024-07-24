<?php
//Autor: Rafael Alberto Moreno Parra. https://github.com/ramsoftware

//Importa la librería que valida la sesion
require_once("../../lib/sesioncomite.php");

//Importa la librería de base de datos para la tabla catedras
require_once("tabla.php");
$bdCatedras = new tabla();

//¿Cuál conjunto de registros va a traer?
$Posicion = isset($_GET["PosTabla"]) ? abs($_GET["PosTabla"]) : 0;

//Navegación
$anterior = $Posicion - 1;
if ($anterior < 0) $anterior = 0;
$siguiente = $Posicion;

//Para mostrar el listado de registros
$Periodo = isset($_GET["periodo"]) ? $_GET["periodo"] : "";
$Programa = isset($_GET["programa"]) ? $_GET["programa"] : "";
$Areaconocimiento = isset($_GET["areaconocimiento"]) ? $_GET["areaconocimiento"] : "";
$Cicloformacion = isset($_GET["cicloformacion"]) ? $_GET["cicloformacion"] : "";
$Componenteformacion = isset($_GET["componenteformacion"]) ? $_GET["componenteformacion"] : "";
$Nivelformacion = isset($_GET["nivelformacion"]) ? $_GET["nivelformacion"] : "";
$Modalidad = isset($_GET["modalidad"]) ? $_GET["modalidad"] : "";
$Tipo = isset($_GET["tipo"]) ? $_GET["tipo"] : "";
$Docente = isset($_GET["docente"]) ? $_GET["docente"] : "";

$registros = $bdCatedras->Busqueda($Periodo, $Programa, $Areaconocimiento, $Cicloformacion, $Componenteformacion, $_GET["nombre"], $_GET["codigouniversidad"], $_GET["semestre"], $Nivelformacion, $_GET["horasdocente"], $_GET["horasindependiente"], $_GET["creditos"], $Modalidad, $Tipo, $Docente, $Posicion);

$Pantalla = file_get_contents("../../vista/catedras/busca2.html");

if ($registros != null){ //Si hay registros
	$Codigo= $registros[0];
	$Pantalla = str_replace("{periodo}", $registros[1], $Pantalla);
	$Pantalla = str_replace("{programa}", $registros[2], $Pantalla);
	$Pantalla = str_replace("{areaconocimiento}", $registros[3], $Pantalla);
	$Pantalla = str_replace("{cicloformacion}", $registros[4], $Pantalla);
	$Pantalla = str_replace("{componenteformacion}", $registros[5], $Pantalla);
	$Pantalla = str_replace("{nombre}", $registros[6], $Pantalla);
	$Pantalla = str_replace("{codigouniversidad}", $registros[7], $Pantalla);
	$Pantalla = str_replace("{semestre}", $registros[8], $Pantalla);
	$Pantalla = str_replace("{nivelformacion}", $registros[9], $Pantalla);
	$Pantalla = str_replace("{horasdocente}", $registros[10], $Pantalla);
	$Pantalla = str_replace("{horasindependiente}", $registros[11], $Pantalla);
	$Pantalla = str_replace("{creditos}", $registros[12], $Pantalla);
	$Pantalla = str_replace("{modalidad}", $registros[13], $Pantalla);
	$Pantalla = str_replace("{tipo}", $registros[14], $Pantalla);
	$Pantalla = str_replace("{docente}", $registros[15], $Pantalla);

	$siguiente = $Posicion + 1;
	$Pantalla = str_replace("{codigo}", $registros[0], $Pantalla);
	$Pantalla = str_replace("{hidden}", "", $Pantalla);
}
else{ //Si no hay más registros
	$Pantalla = str_replace("{periodo}", "---", $Pantalla);
	$Pantalla = str_replace("{programa}", "---", $Pantalla);
	$Pantalla = str_replace("{areaconocimiento}", "---", $Pantalla);
	$Pantalla = str_replace("{cicloformacion}", "---", $Pantalla);
	$Pantalla = str_replace("{componenteformacion}", "---", $Pantalla);
	$Pantalla = str_replace("{nombre}", "---", $Pantalla);
	$Pantalla = str_replace("{codigouniversidad}", "---", $Pantalla);
	$Pantalla = str_replace("{semestre}", "---", $Pantalla);
	$Pantalla = str_replace("{nivelformacion}", "---", $Pantalla);
	$Pantalla = str_replace("{horasdocente}", "---", $Pantalla);
	$Pantalla = str_replace("{horasindependiente}", "---", $Pantalla);
	$Pantalla = str_replace("{creditos}", "---", $Pantalla);
	$Pantalla = str_replace("{modalidad}", "---", $Pantalla);
	$Pantalla = str_replace("{tipo}", "---", $Pantalla);
	$Pantalla = str_replace("{docente}", "---", $Pantalla);
	$Pantalla = str_replace("{hidden}", "hidden", $Pantalla);
}

//Criterio de búsqueda
$Criterio = "";
foreach ($_GET as $clave=>$valor){
	if ($clave != "PosTabla")
		$Criterio .= "&" . $clave . "=" . $valor;
}
$Pantalla = str_replace("{criterio}", $Criterio, $Pantalla);
$Pantalla = str_replace("{anterior}", $anterior, $Pantalla);
$Pantalla = str_replace("{siguiente}", $siguiente, $Pantalla);
$Pantalla = str_replace("{rutaprog}", $bdCatedras->rutaprog(), $Pantalla);
$Pantalla = str_replace("{rutavista}", $bdCatedras->rutavista(), $Pantalla);
$Pantalla = str_replace("{rolnombre}", $_SESSION['rolnombre'], $Pantalla);
$Pantalla = str_replace("{usuarionombre}", $_SESSION['usuarionombre'], $Pantalla);
echo $Pantalla;