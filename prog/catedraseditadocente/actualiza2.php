<?php
//Importa la librería que valida la sesion
require_once("../../lib/sesiondocente.php");

//Importa la librería de base de datos para la tabla catedras
require_once("tabla.php");
$Tabla = new tabla();

//Edita el registro
$Competencias = array();
if(isset($_POST['competencias'])) $Competencias = $_POST['competencias'];

$Resultados = array();
if(isset($_POST['resultados'])) $Resultados = $_POST['resultados'];

$Resultado = $Tabla->Actualizar($_POST["catedra"], $_POST["descripcion"], $_POST["justificacion"], $_POST["metodologia"], $_POST["bibliografia"], $Competencias, $Resultados);

//Respuesta HTML
$Pantalla = file_get_contents($Tabla->actualiza2());
$Pantalla = str_replace("{resultado}", $Resultado, $Pantalla);
$Pantalla = str_replace("{catedra}", $_POST["catedra"], $Pantalla);
$Pantalla = str_replace("{rutaprog}", $Tabla->rutaprog(), $Pantalla);
$Pantalla = str_replace("{rolnombre}", $_SESSION['rolnombre'], $Pantalla);
$Pantalla = str_replace("{usuarionombre}", $_SESSION['usuarionombre'], $Pantalla);
echo $Pantalla;