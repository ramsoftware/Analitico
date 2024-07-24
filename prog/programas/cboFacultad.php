<?php
//Importa la librería genérica para bases de datos y la instancia
require_once("../../lib/BD.php");
$BaseDatos = new basedatos();

//Carga codigo y nombre de las facultades
$Busca = "";
if (isset($_POST['FacultadBusca'])) $Busca = $_POST['FacultadBusca'];
echo json_encode($BaseDatos->ComboBoxDinamico("facultades", "codigo", "nombre", $Busca));
exit();