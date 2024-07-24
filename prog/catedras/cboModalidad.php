<?php
//Importa la librería genérica para bases de datos y la instancia
require_once("../../lib/BD.php");
$BaseDatos = new basedatos();

//Carga codigo y nombre de la modalidad
$Busca = "";
if (isset($_POST['ModalidadBusca'])) $Busca = $_POST['ModalidadBusca'];
echo json_encode($BaseDatos->ComboBoxDinamico("modalidades", "codigo", "nombre", $Busca));
exit();