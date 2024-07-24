<?php
//Importa la librería que valida la sesion
require_once("../../lib/sesiondocente.php");

//Importa la librería de base de datos para la tabla catedras
require_once("tabla.php");
$Tabla = new tabla();

//Trae los campos del registro
$Catedra = $_GET["catedra"];
if ($Tabla->CatedraAutorizada($Catedra, $_SESSION['usuariocodigo'])) {
	$Resultados = $Tabla->VerRegistroDescripcion($Catedra);
	$Pantalla = file_get_contents($Tabla->descripcion());
	$Pantalla = str_replace("{catedra}", $Catedra, $Pantalla);
	$Pantalla = str_replace("{periodo}", $Resultados[1], $Pantalla);
	$Pantalla = str_replace("{descripcion}", $Resultados[2], $Pantalla);
	$Pantalla = str_replace("{justificacion}", $Resultados[3], $Pantalla);
	$Pantalla = str_replace("{metodologia}", $Resultados[4], $Pantalla);
	$Pantalla = str_replace("{bibliografia}", $Resultados[5], $Pantalla);
	$Pantalla = str_replace("{nombre}", $Resultados[6], $Pantalla);
	$Pantalla = str_replace("{rutaprog}", $Tabla->rutaprog(), $Pantalla);
	$Pantalla = str_replace("{rolnombre}", $_SESSION['rolnombre'], $Pantalla);
	$Pantalla = str_replace("{usuarionombre}", $_SESSION['usuarionombre'], $Pantalla);
	$Pantalla = str_replace("{competencias}", $Tabla->DetalleCompetencias($Catedra), $Pantalla);
	$Pantalla = str_replace("{resultados}", $Tabla->DetalleResultados($Catedra), $Pantalla);
	echo $Pantalla;
}