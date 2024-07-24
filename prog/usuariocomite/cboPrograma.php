<?php
//Importa la librería genérica para bases de datos y la instancia
require_once("../../lib/BD.php");
$BaseDatos = new basedatos();

//Carga codigo y nombre de las roles
$Busca = "";
if (isset($_POST['ProgramaBusca'])) $Busca = $_POST['ProgramaBusca'];
echo json_encode($BaseDatos->ComboBoxDinamico("programas", "codigo", "nombre", $Busca));
exit();