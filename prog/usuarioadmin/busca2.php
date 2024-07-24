<?php
//Autor: Rafael Alberto Moreno Parra. https://github.com/ramsoftware

//Importa la librería que valida la sesion
require_once("../../lib/sesionadmin.php");

//Criterio de búsqueda
$Criterio = "";
foreach ($_GET as $Clave=>$Valor){
	if ($Clave != "PosTabla")
		$Criterio .= "&" . $Clave . "=" . $Valor;
}

//¿Cuál conjunto de registros va a traer?
$Posicion = isset($_GET["PosTabla"]) ? abs(intval($_GET["PosTabla"])) : 0;

//Navegación
$Anterior = $Posicion <= 0 ? 0 : $Posicion - 1;
$Siguiente = $Posicion;

//Para mostrar el listado de registros
require_once("tabla.php");
$Tabla = new tabla();
$BuscaRol = "";
if (isset($_GET["rol"])) $BuscaRol = $_GET["rol"];
$Registros = $Tabla->Busqueda($_GET["identifica"], $BuscaRol, $_GET["nombre"], $_GET["correo1"], $_GET["correo2"], $Posicion);
$Codigo = -1;
$Identifica = "---";
$Rol = "---";
$Nombre = "---";
$Correo1 = "---";
$Correo2 = "---";
$Oculto = "hidden";
if ($Registros != null){
	$Siguiente++;
	$Codigo = $Registros[0];
	$Identifica = $Registros[1];
	$Rol = $Registros[2];
	$Nombre = $Registros[3];
	$Correo1 = $Registros[4];
	$Correo2 = $Registros[5];
	$Oculto = "";
}

//Respuesta HTML
$Pantalla = file_get_contents($Tabla->busca2());
$Pantalla = str_replace("{codigo}", $Codigo, $Pantalla);
$Pantalla = str_replace("{identifica}", $Identifica, $Pantalla);
$Pantalla = str_replace("{usuariorol}", $Rol, $Pantalla);
$Pantalla = str_replace("{nombre}", $Nombre, $Pantalla);
$Pantalla = str_replace("{correo1}", $Correo1, $Pantalla);
$Pantalla = str_replace("{correo2}", $Correo2, $Pantalla);
$Pantalla = str_replace("{hidden}", $Oculto, $Pantalla);
$Pantalla = str_replace("{criterio}", $Criterio, $Pantalla);
$Pantalla = str_replace("{anterior}", $Anterior, $Pantalla);
$Pantalla = str_replace("{siguiente}", $Siguiente, $Pantalla);
$Pantalla = str_replace("{rutaprog}", $Tabla->rutaprog(), $Pantalla);
$Pantalla = str_replace("{rutavista}", $Tabla->rutavista(), $Pantalla);
$Pantalla = str_replace("{tablavisual}", $Tabla->TablaVisual, $Pantalla);
$Pantalla = str_replace("{rolnombre}", $_SESSION['rolnombre'], $Pantalla);
$Pantalla = str_replace("{usuarionombre}", $_SESSION['usuarionombre'], $Pantalla);
echo $Pantalla;