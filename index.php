<?php
//Autor: Rafael Alberto Moreno Parra. https://github.com/ramsoftware

$FallaInicio = "";
if (isset($_GET["iniciar"]))
	if ($_GET["iniciar"]=='0')
		$FallaInicio = "Identificación o contraseña inválidos";

//Respuesta HTML
$Pantalla = file_get_contents("iniciar.html");
$Pantalla = str_replace("{FallaInicio}", $FallaInicio, $Pantalla);
echo $Pantalla;
