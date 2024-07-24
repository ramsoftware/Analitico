<?php
//Importa la librería genérica para bases de datos y la instancia
require_once("../../lib/BD.php");
$BaseDatos = new basedatos();

//Carga codigo y nombre de los ciclos de formación
$Busca = "";
if (isset($_POST['CicloformacionBusca'])) $Busca = $_POST['CicloformacionBusca'];
echo json_encode($BaseDatos->ComboBoxDinamico("ciclosformacion", "codigo", "nombre", $Busca));
exit();