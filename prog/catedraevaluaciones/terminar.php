<?php
//Autor: Rafael Alberto Moreno Parra. https://github.com/ramsoftware

//Importa la librería que valida la sesión
require_once("../../lib/sesiondocente.php");

//Importa la librería de base de datos para la tabla
require_once("tabla.php");
$Tabla = new tabla();

//Respuesta HTML
$Pantalla = "";
$Resultado = "";

if (isset($_POST["unidades"]))
	$Unidades = $_POST["unidades"];
else
	$Unidades = array();

if (isset($_POST["resultados"]))
	$Resultados = $_POST["resultados"];
else
	$Resultados = array();

if (isset($_POST["estrategias"]))
	$Estrategias = $_POST["estrategias"];
else
	$Estrategias = array();

switch(abs(intval($_GET["op"]))) {
	case 0: //Actualización
			$Pantalla = file_get_contents($Tabla->actualiza2());
			$Pantalla = str_replace("{catedra}", $_POST["catedra"], $Pantalla);
			$Resultado = $Tabla->Actualizar($_POST["evaluacion"], $_POST["descripcion"], $_POST["catedra"], $_POST["momento"], $Unidades, $Resultados, $Estrategias);
			if ($Resultado == 1) {
				header("Location:registros.php?catedra=" . $_POST["catedra"]);
				exit;
			}
			break;

	case 1: //Borrado
			$Pantalla = file_get_contents($Tabla->borra2());
			$EvalCatedra = $Tabla->CodigoCatedra($_GET["evaluacion"]);
			$Pantalla = str_replace("{catedra}", $EvalCatedra, $Pantalla);
			$Resultado = $Tabla->Borrar($_GET["evaluacion"]);
			if ($Resultado == 1) {
				header("Location:registros.php?catedra=" . $EvalCatedra);
				exit;
			}
			break;

	case 2: //Adición
			$Pantalla = file_get_contents($Tabla->adiciona2());
			$Pantalla = str_replace("{catedra}", $_POST["catedra"], $Pantalla);
			$Resultado = $Tabla->Adicionar($_POST["descripcion"], $_POST["catedra"], $_POST["momento"], $Unidades, $Resultados, $Estrategias);
			if ($Resultado == 1) {
				header("Location:registros.php?catedra=" . $_POST["catedra"]);
				exit;
			}
			break;
}

$Pantalla = str_replace("{resultado}", $Resultado, $Pantalla);
$Pantalla = str_replace("{rutaprog}", $Tabla->rutaprog(), $Pantalla);
$Pantalla = str_replace("{rutavista}", $Tabla->rutavista(), $Pantalla);
$Pantalla = str_replace("{rolnombre}", $_SESSION['rolnombre'], $Pantalla);
$Pantalla = str_replace("{usuarionombre}", $_SESSION['usuarionombre'], $Pantalla);
echo $Pantalla;