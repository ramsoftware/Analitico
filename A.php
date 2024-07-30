<?php
//Reporta todo los errores
error_reporting(E_ALL);

//Nombre de la sesión
session_name("loginUsuario");

//Inicia sesión
session_start();

//Valores de sesión
echo "Probando las sesiones";
$_SESSION['usuariocodigo'] = "16832929";
$_SESSION['usuarionombre'] = "Rafael Alberto Moreno Parra";
$_SESSION['rolcodigo'] = 13;
$_SESSION['rolnombre'] = "Docente";
$_SESSION['programacodigo'] = 7;
$_SESSION['programanombre'] = "Ingeniería de Sistemas";

//Llama al siguiente archivo
header("Location:B.php");