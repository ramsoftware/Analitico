<?php
//Importa la librería genérica para bases de datos y la instancia
require_once("../../lib/BD.php");
$BaseDatos = new basedatos();

//Carga codigo y nombre de las áreas de componente de formación
$Busca = "";
if (isset($_POST['ComponenteformacionBusca'])) $Busca = $_POST['ComponenteformacionBusca'];
echo json_encode($BaseDatos->ComboBoxDinamico("componentes", "codigo", "nombre", $Busca));
exit();