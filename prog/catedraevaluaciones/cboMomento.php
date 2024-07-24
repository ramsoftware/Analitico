<?php
//Importa la librería genérica para bases de datos y la instancia
require_once("../../lib/BD.php");
$BaseDatos = new basedatos();

//Carga codigo y nombre los idiomas de los documentos
$Busca = "";
if (isset($_POST['MomentoBusca'])) $Busca = $_POST['MomentoBusca'];
echo json_encode($BaseDatos->ComboBoxDinamico("momentos", "codigo", "nombre", $Busca));
exit();