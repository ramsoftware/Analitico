<?php
//Autor: Rafael Alberto Moreno Parra. https://github.com/ramsoftware

//Importa la librería que valida la sesión
require_once("../../lib/sesionbasica.php");

//Importa la librería de base de datos para la tabla
require_once("tabla.php");
$Tabla = new tabla();

//Respuesta HTML
$Pantalla = "";
$Resultado = "";
switch(abs(intval($_GET["op"]))) {
	case 0: $Pantalla = file_get_contents($Tabla->actualiza2());
			$Resultado = $Tabla->Actualizar($_POST["codigo"], $_POST["nombre"], $_POST["facultad"]);
			break; //Finaliza actualización
	case 1: $Pantalla = file_get_contents($Tabla->borra2());
			$Resultado = $Tabla->Borrar($_GET["codigo"]);
			break; //Finaliza borrado
	case 2: $Pantalla = file_get_contents($Tabla->adiciona2());
			$Resultado = $Tabla->Adicionar($_POST["nombre"], $_POST["facultad"]);
			break; //Finaliza adición
}

$Pantalla = str_replace("{resultado}", $Resultado, $Pantalla);
$Pantalla = str_replace("{rutaprog}", $Tabla->rutaprog(), $Pantalla);
$Pantalla = str_replace("{rutavista}", $Tabla->rutavista(), $Pantalla);
$Pantalla = str_replace("{tablavisual}", $Tabla->TablaVisual, $Pantalla);
$Pantalla = str_replace("{rolnombre}", $_SESSION['rolnombre'], $Pantalla);
$Pantalla = str_replace("{usuarionombre}", $_SESSION['usuarionombre'], $Pantalla);
echo $Pantalla;