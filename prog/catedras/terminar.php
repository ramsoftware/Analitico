<?php
//Autor: Rafael Alberto Moreno Parra. https://github.com/ramsoftware

//Importa la librería que valida la sesión
require_once("../../lib/sesioncomite.php");

//Importa la librería de base de datos para la tabla
require_once("tabla.php");
$Tabla = new tabla();

$Competencias = array();
if(isset($_POST['competencias'])) $Competencias = $_POST['competencias'];

$Resultados = array();
if(isset($_POST['resultados'])) $Resultados = $_POST['resultados'];

//Respuesta HTML
$Pantalla = "";
$Resultado = "";
switch(abs(intval($_GET["op"]))) {
	case 0: $Pantalla = file_get_contents($Tabla->actualiza2());
			$Resultado = $Tabla->Actualizar($_POST["catedra"], $_POST["periodo"], $_POST["areaconocimiento"], $_POST["cicloformacion"], $_POST["componenteformacion"], $_POST["nombre"], $_POST["codigouniversidad"], $_POST["semestre"], $_POST["nivelformacion"], $_POST["horasdocente"], $_POST["horasindependiente"], $_POST["creditos"], $_POST["modalidad"], $_POST["tipo"], $_POST["docente"], $_POST["editar"], $Competencias, $Resultados);
			break; //Finaliza actualización
	case 1: $Pantalla = file_get_contents($Tabla->borra2());
			$Resultado = $Tabla->Borrar($_GET["catedra"]);
			break; //Finaliza borrado
	case 2: $Pantalla = file_get_contents($Tabla->adiciona2());
			$Resultado = $Tabla->Adicionar($_POST["periodo"], $_SESSION["programacodigo"], $_POST["areaconocimiento"], $_POST["cicloformacion"], $_POST["componenteformacion"], $_POST["nombre"], $_POST["codigouniversidad"], $_POST["semestre"], $_POST["nivelformacion"], $_POST["horasdocente"], $_POST["horasindependiente"], $_POST["creditos"], $_POST["modalidad"], $_POST["tipo"], $_POST["docente"], $_POST["editar"], $Competencias, $Resultados);
			break; //Finaliza adición
}

$Pantalla = str_replace("{resultado}", $Resultado, $Pantalla);
$Pantalla = str_replace("{rutaprog}", $Tabla->rutaprog(), $Pantalla);
$Pantalla = str_replace("{rutavista}", $Tabla->rutavista(), $Pantalla);
$Pantalla = str_replace("{rolnombre}", $_SESSION['rolnombre'], $Pantalla);
$Pantalla = str_replace("{usuarionombre}", $_SESSION['usuarionombre'], $Pantalla);
$Pantalla = str_replace("{programanombre}", $_SESSION['programanombre'], $Pantalla);
echo $Pantalla;