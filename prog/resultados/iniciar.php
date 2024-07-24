<?php
//Autor: Rafael Alberto Moreno Parra. https://github.com/ramsoftware

//Importa la librería que valida la sesion
require_once("../../lib/sesioncomite.php");

//Importa la librería de base de datos para la tabla
require_once("tabla.php");
$Tabla = new tabla($_SESSION['programanombre']);

//Respuesta HTML
$Pantalla = "";
switch(abs(intval($_GET["op"]))) {
	case 0: $Pantalla = file_get_contents($Tabla->actualiza1()); break; //Inicia actualización
	case 1: $Pantalla = file_get_contents($Tabla->borra1()); break; //Inicia borrado
	case 2: $Pantalla = file_get_contents($Tabla->detalle()); break; //Inicia detalle
	case 3: $Pantalla = file_get_contents($Tabla->adiciona1()); break; //Inicia adición
	case 4: $Pantalla = file_get_contents($Tabla->busca1()); break; //Inicia búsqueda
}

//Si son operaciones de actualización, borrado o detalle, se debe traer el registro existente.
if (abs(intval($_GET["op"])<=2)){
	$Registros = $Tabla->VerRegistro($_GET["codigo"]);
	$Pantalla = str_replace("{codigo}", $Registros['codigo'], $Pantalla);
	$Pantalla = str_replace("{nombre}", $Registros['nombre'], $Pantalla);
}

$Pantalla = str_replace("{rutaprog}", $Tabla->rutaprog(), $Pantalla);
$Pantalla = str_replace("{rutavista}", $Tabla->rutavista(), $Pantalla);
$Pantalla = str_replace("{tablavisual}", $Tabla->TablaVisual, $Pantalla);
$Pantalla = str_replace("{rolnombre}", $_SESSION['rolnombre'], $Pantalla);
$Pantalla = str_replace("{usuarionombre}", $_SESSION['usuarionombre'], $Pantalla);
echo $Pantalla;