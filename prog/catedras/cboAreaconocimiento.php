<?php
//Importa la librería genérica para bases de datos y la instancia
require_once("../../lib/BD.php");
$BaseDatos = new basedatos();

//Carga codigo y nombre de las áreas de conocimiento
$Busca = "";
if (isset($_POST['AreaconocimientoBusca'])) $Busca = $_POST['AreaconocimientoBusca'];
echo json_encode($BaseDatos->ComboBoxDinamico("areasconoce", "codigo", "nombre", $Busca));
exit();