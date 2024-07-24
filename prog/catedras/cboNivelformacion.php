<?php
//Importa la librería genérica para bases de datos y la instancia
require_once("../../lib/BD.php");
$BaseDatos = new basedatos();

//Carga codigo y nombre de nivel de formación
$Busca = "";
if (isset($_POST['NivelformacionBusca'])) $Busca = $_POST['NivelformacionBusca'];
echo json_encode($BaseDatos->ComboBoxDinamico("nivelesformacion", "codigo", "nombre", $Busca));
exit();