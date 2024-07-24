<?php
//Cierra la sesión y reenvía a la pantalla de inicio de sesión
session_name("loginUsuario");
session_start();
session_unset();
session_destroy();
header("Location: ../../index.php");