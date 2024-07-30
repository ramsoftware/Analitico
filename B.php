<?php
//Reporta todo los errores
error_reporting(E_ALL);

//Inicia sesión
session_name("loginUsuario");
session_start() or die('Error iniciando sesiones');

//Imprime los valores:
echo "<br>Estando en B.php: ";
echo "<br>Código: " . $_SESSION['usuariocodigo'];
echo "<br>Nombre: " . $_SESSION['usuarionombre'];
echo "<br>Código Rol: " . $_SESSION['rolcodigo'];
echo "<br>Nombre Rol: " . $_SESSION['rolnombre'];
echo "<br>Código Programa: " . $_SESSION['programacodigo'];
echo "<br>Nombre Programa: " . $_SESSION['programanombre'];

//Cierra la sesión
session_unset();
session_destroy();