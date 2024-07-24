﻿<?php
//Autor: Rafael Alberto Moreno Parra. https://github.com/ramsoftware

//Importa la librería que valida la sesión
require_once("../../lib/sesiondocente.php");

//Importa la librería de base de datos para la tabla
require_once("tabla.php");
$Tabla = new tabla();

//Respuesta HTML
$Pantalla = "";
$Resultado = "";
$Pantalla = file_get_contents($Tabla->actualiza2());
$Resultado = $Tabla->Actualizar($_POST["codigo"], $_POST["contrasena"], $_POST["nombre"], $_POST["correo1"], $_POST["correo2"], $_POST["perfil"], $_POST["docente"], $_POST["profesional"], $_POST["investigacion"], $_POST["produccion"]);
$Pantalla = str_replace("{resultado}", $Resultado, $Pantalla);
$Pantalla = str_replace("{rutaprog}", $Tabla->rutaprog(), $Pantalla);
$Pantalla = str_replace("{rutavista}", $Tabla->rutavista(), $Pantalla);
$Pantalla = str_replace("{tablavisual}", $Tabla->TablaVisual, $Pantalla);
$Pantalla = str_replace("{rolnombre}", $_SESSION['rolnombre'], $Pantalla);
$Pantalla = str_replace("{usuarionombre}", $_SESSION['usuarionombre'], $Pantalla);
echo $Pantalla;