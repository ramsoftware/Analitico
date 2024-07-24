<?php
//Autor: Rafael Alberto Moreno Parra. https://github.com/ramsoftware

//Importa la librería que valida la sesión
require_once("../../lib/sesiondocente.php");

//Importa la librería de base de datos para la tabla
require_once("tabla.php");
$Tabla = new tabla();

if ( (isset($_GET["unidad"]) && $Tabla->UnidadAutorizada($_GET["unidad"], $_SESSION['usuariocodigo'])) ||
	(isset($_POST["catedra"]) && $Tabla->CatedraAutorizada($_POST["catedra"], $_SESSION['usuariocodigo']))) {
	$Pantalla = "";
	$Resultado = "";
	switch (abs(intval($_GET["op"]))) {
		case 0: //Actualización
			$Pantalla = file_get_contents($Tabla->actualiza2());
			$Pantalla = str_replace("{catedra}", $_POST["catedra"], $Pantalla);
			$Resultado = $Tabla->Actualizar($_POST["unidad"], $_POST["titulo"], $_POST["temas"]);
			if ($Resultado == 1) {
				header("Location:registros.php?catedra=" . $_POST["catedra"]);
				exit;
			}
			break;

		case 1: //Borrado
			$Pantalla = file_get_contents($Tabla->borra2());
			$UnidCatedra = $Tabla->CodigoCatedra($_GET["unidad"]);
			$Pantalla = str_replace("{catedra}", $UnidCatedra, $Pantalla);
			$Resultado = $Tabla->Borrar($_GET["unidad"]);
			if ($Resultado == 1) {
				header("Location:registros.php?catedra=" . $UnidCatedra);
				exit;
			}
			break;

		case 2: //Adición
			$Pantalla = file_get_contents($Tabla->adiciona2());
			$Pantalla = str_replace("{catedra}", $_POST["catedra"], $Pantalla);
			$Resultado = $Tabla->Adicionar($_POST["catedra"], $_POST["titulo"], $_POST["temas"]);
			echo "Resultado fue: " . $Resultado;
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
}