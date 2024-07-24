<?php
//Importa la librería que valida la sesion
require_once("../../lib/sesiondocente.php");

//Importa la librería genérica para bases de datos y la instancia
require_once("tabla.php");
$Tabla = new tabla();

if ($Tabla->CatedraAutorizada($_GET['catedra'], $_SESSION['usuariocodigo'])) {
	$Resultado = $Tabla->DatosGrid($_GET['catedra']);
	$Datos = $Tabla->DatosCatedra($_GET['catedra']);
	$Pantalla = file_get_contents($Tabla->registros());
	$Pantalla = str_replace("{catedra}", $_GET['catedra'], $Pantalla);
	$Pantalla = str_replace("{Datos}", $Resultado, $Pantalla);
	$Pantalla = str_replace("{catedranombre}", $Datos[0], $Pantalla);
	$Pantalla = str_replace("{periodo}", $Datos[1], $Pantalla);
	$Pantalla = str_replace("{rutaprog}", $Tabla->rutaprog(), $Pantalla);
	$Pantalla = str_replace("{rutavista}", $Tabla->rutavista(), $Pantalla);
	$Pantalla = str_replace("{rolnombre}", $_SESSION['rolnombre'], $Pantalla);
	$Pantalla = str_replace("{usuarionombre}", $_SESSION['usuarionombre'], $Pantalla);
	echo $Pantalla;
}