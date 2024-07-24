<?php
//Importa la librería genérica para bases de datos y la instancia
require_once("../../lib/BD.php");
$BaseDatos = new basedatos();

//Carga codigo y nombre los tipos de cátedra
$Busca = "";
if (isset($_POST['TipoBusca'])) $Busca = $_POST['TipoBusca'];
echo json_encode($BaseDatos->ComboBoxDinamico("tiposasignatura", "codigo", "nombre", $Busca));
exit();