<?php
//Importa la librería genérica para bases de datos y la instancia
require_once("../../lib/BD.php");
$BaseDatos = new basedatos();

//Carga codigo y nombre de los períodos
$Busca = "";
if (isset($_POST['PeriodoBusca'])) $Busca = $_POST['PeriodoBusca'];
echo json_encode($BaseDatos->ComboBoxDinamico("periodos", "codigo", "nombre", $Busca));
exit();