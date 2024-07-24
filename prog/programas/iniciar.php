<?php
//Autor: Rafael Alberto Moreno Parra. https://github.com/ramsoftware

//Importa la librería que valida la sesion
require_once("../../lib/sesionbasica.php");

//Importa la librería de base de datos para la tabla
require_once("tabla.php");
$Tabla = new tabla();

//Respuesta HTML
$Pantalla = "";
switch(abs(intval($_GET["op"]))) {
	case 0: //Inicia actualización
		$Pantalla = file_get_contents($Tabla->actualiza1());
		$Registros = $Tabla->VerRegistroActualiza($_GET["codigo"]);
		$Pantalla = str_replace("{codigo}", $Registros[0], $Pantalla);
		$Pantalla = str_replace("{nombre}", $Registros[1], $Pantalla);
		$Pantalla = str_replace("{cboFacultad}", $Tabla->ComboBoxFacultad($Registros[2]), $Pantalla);
		break;
	case 1: //Inicia borrado
		$Pantalla = file_get_contents($Tabla->borra1());
		$Registros = $Tabla->VerRegistroDetalle($_GET["codigo"]);
		$Pantalla = str_replace("{codigo}", $Registros[0], $Pantalla);
		$Pantalla = str_replace("{nombre}", $Registros[1], $Pantalla);
		$Pantalla = str_replace("{facultad}", $Registros[2], $Pantalla);
		break;
	case 2: //Inicia detalle
		$Pantalla = file_get_contents($Tabla->detalle());
		$Registros = $Tabla->VerRegistroDetalle($_GET["codigo"]);
		$Pantalla = str_replace("{codigo}", $Registros[0], $Pantalla);
		$Pantalla = str_replace("{nombre}", $Registros[1], $Pantalla);
		$Pantalla = str_replace("{facultad}", $Registros[2], $Pantalla);
		break;
	case 3: //Inicia adición
		$Pantalla = file_get_contents($Tabla->adiciona1());
		break;
	case 4: //Inicia búsqueda
		$Pantalla = file_get_contents($Tabla->busca1());
		break;
}

$Pantalla = str_replace("{rutaprog}", $Tabla->rutaprog(), $Pantalla);
$Pantalla = str_replace("{rutavista}", $Tabla->rutavista(), $Pantalla);
$Pantalla = str_replace("{tablavisual}", $Tabla->TablaVisual, $Pantalla);
$Pantalla = str_replace("{rolnombre}", $_SESSION['rolnombre'], $Pantalla);
$Pantalla = str_replace("{usuarionombre}", $_SESSION['usuarionombre'], $Pantalla);
echo $Pantalla;