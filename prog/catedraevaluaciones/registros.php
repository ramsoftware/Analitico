<?php
//Importa la librería que valida la sesion
require_once("../../lib/sesiondocente.php");

//Importa la librería genérica para bases de datos y la instancia
require_once("tabla.php");
$Tabla = new tabla();

//Para mostrar el listado de títulos de cada unidad
$Resultado = $Tabla->DatosGrid($_GET['catedra']);

//Trae nombre de la cátedra y período
$Datos = $Tabla->DatosCatedra($_GET['catedra']);

//Modifica el HTML (parte visual) con los datos a mostrar y luego presenta la pantalla

if ($Tabla->CatedraAutorizada($_GET['catedra'], $_SESSION['usuariocodigo'])) {
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