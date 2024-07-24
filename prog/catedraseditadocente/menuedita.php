<?php
//Importa la librería que valida la sesion
require_once("../../lib/sesiondocente.php");

//Importa la librería de base de datos para la tabla catedras
require_once("tabla.php");
$Tabla = new tabla();

//Trae los campos del registro
$Catedra = $_GET["catedra"];
if ($Tabla->CatedraAutorizada($Catedra, $_SESSION['usuariocodigo'])) {
	$Resultado = $Tabla->VerRegistroCatedraDocente($Catedra);
	$Pantalla = file_get_contents($Tabla->menuedita());
	$Pantalla = str_replace("{catedra}", $Catedra, $Pantalla);
	$Pantalla = str_replace("{periodo}", $Resultado[1], $Pantalla);
	$Pantalla = str_replace("{nombrecatedra}", $Resultado[2], $Pantalla);
	$Pantalla = str_replace("{rutaprog}", $Tabla->rutaprog(), $Pantalla);
	$Pantalla = str_replace("{rutavista}", $Tabla->rutavista(), $Pantalla);
	$Pantalla = str_replace("{rolnombre}", $_SESSION['rolnombre'], $Pantalla);
	$Pantalla = str_replace("{usuarionombre}", $_SESSION['usuarionombre'], $Pantalla);
	echo $Pantalla;
}