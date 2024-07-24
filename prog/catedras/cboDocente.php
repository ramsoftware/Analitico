<?php
//Importa la librería genérica para bases de datos y la instancia
require_once("../../lib/BD.php");
$BaseDatos = new basedatos();

//Carga codigo y nombre de los docentes
$Busca = "";
if (isset($_POST['DocenteBusca'])) $Busca = $_POST['DocenteBusca'];
echo json_encode($BaseDatos->ComboBoxFiltrado("usuarios", "codigo", "nombre", "rol", 3, $Busca));
exit();