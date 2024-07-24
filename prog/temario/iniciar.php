<?php
//Autor: Rafael Alberto Moreno Parra. https://github.com/ramsoftware

//Importa la librería que valida la sesion
require_once("../../lib/sesiondocente.php");

//Importa la librería de base de datos para la tabla
require_once("tabla.php");
$Tabla = new tabla();

if ( (isset($_GET["unidad"]) && $Tabla->UnidadAutorizada($_GET["unidad"], $_SESSION['usuariocodigo'])) ||
	 (isset($_GET["catedra"]) && $Tabla->CatedraAutorizada($_GET["catedra"], $_SESSION['usuariocodigo']))) {
	$Pantalla = "";
	switch (abs(intval($_GET["op"]))) {
		case 0: //Actualización
			$Pantalla = file_get_contents($Tabla->actualiza1()); //Inicia actualización
			$Registros = $Tabla->VerRegistroActualiza($_GET["unidad"]);
			$Pantalla = str_replace("{titulo}", $Registros[0], $Pantalla);
			$Pantalla = str_replace("{temas}", $Registros[1], $Pantalla);
			$Pantalla = str_replace("{catedranombre}", $Registros[2], $Pantalla);
			$Pantalla = str_replace("{periodo}", $Registros[3], $Pantalla);
			$Pantalla = str_replace("{catedra}", $Registros[4], $Pantalla);
			$Pantalla = str_replace("{unidad}", $_GET["unidad"], $Pantalla);
			break;

		case 1: //Borrado
			$Pantalla = file_get_contents($Tabla->borra1());
			$Registros = $Tabla->VerRegistro($_GET["unidad"]);
			$Pantalla = str_replace("{titulo}", $Registros[0], $Pantalla);
			$Pantalla = str_replace("{temas}", $Registros[1], $Pantalla);
			$Pantalla = str_replace("{catedranombre}", $Registros[2], $Pantalla);
			$Pantalla = str_replace("{periodo}", $Registros[3], $Pantalla);
			$Pantalla = str_replace("{catedra}", $Registros[4], $Pantalla);
			$Pantalla = str_replace("{unidad}", $_GET["unidad"], $Pantalla);
			break;

		case 2: //Detalle
			$Pantalla = file_get_contents($Tabla->detalle());
			$Registros = $Tabla->VerRegistro($_GET["unidad"]);
			$Pantalla = str_replace("{titulo}", $Registros[0], $Pantalla);
			$Pantalla = str_replace("{temas}", $Registros[1], $Pantalla);
			$Pantalla = str_replace("{catedranombre}", $Registros[2], $Pantalla);
			$Pantalla = str_replace("{periodo}", $Registros[3], $Pantalla);
			$Pantalla = str_replace("{catedra}", $Registros[4], $Pantalla);
			$Pantalla = str_replace("{unidad}", $_GET["unidad"], $Pantalla);
			break;

		case 3: //Adición
			$Pantalla = file_get_contents($Tabla->adiciona1());
			$Registros = $Tabla->DatosCatedra($_GET["catedra"]);
			$Pantalla = str_replace("{catedra}", $_GET["catedra"], $Pantalla);
			$Pantalla = str_replace("{catedranombre}", $Registros[0], $Pantalla);
			$Pantalla = str_replace("{periodo}", $Registros[1], $Pantalla);
			break;
	}

	$Pantalla = str_replace("{rutaprog}", $Tabla->rutaprog(), $Pantalla);
	$Pantalla = str_replace("{rutavista}", $Tabla->rutavista(), $Pantalla);
	$Pantalla = str_replace("{rolnombre}", $_SESSION['rolnombre'], $Pantalla);
	$Pantalla = str_replace("{usuarionombre}", $_SESSION['usuarionombre'], $Pantalla);
	echo $Pantalla;
}