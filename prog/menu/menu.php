<?php
//Importa la librería de base de datos para la tabla usuarios
require_once("tabla.php");
$Tabla = new tabla();

//Verifica si las credenciales son correctas
//echo "Identifica: [" . $_POST["identifica"] . "]<br>";
//echo "Contraseña: [" . $_POST["contrasena"] . "]<br>";
$Resultado = $Tabla->Validar($_POST["identifica"], $_POST["contrasena"]);
//echo $Resultado ? 'true' : 'false';
//exit;

//Si las credenciales son correctas
if ($Resultado) {

	//Abre la sesión
	session_name("loginUsuario");
	session_start();

	//Genera las variables de sesión
	$_SESSION['usuariocodigo'] = $Tabla->UsuarioCodigo;
	$_SESSION['usuarionombre'] = $Tabla->UsuarioNombre;
	$_SESSION['rolcodigo'] = $Tabla->RolCodigo;
	$_SESSION['rolnombre'] = $Tabla->RolNombre;
	$_SESSION['programacodigo'] = $Tabla->ProgramaCodigo;
	$_SESSION['programanombre'] = $Tabla->ProgramaNombre;

	//Dependiendo del tipo de usuario lo redirige a un determinado menú
	switch($Tabla->RolCodigo){
		case 0: header("Location:administrador.php"); break;
		case 1: header("Location:basicas.php"); break;
		case 2:
			$_SESSION['rolnombre'] = $_SESSION['rolnombre'] . " de " . $_SESSION['programanombre'];
			header("Location:comite.php"); break;
		case 3: header("Location:docente.php"); break;
		case 4: header("Location:estudiante.php"); break;
	}
}
else { //Retorna a la pantalla de inicio de sesión para mostrar el error de identificación al usuario
	header("Location:../../index.php?iniciar=0");
}

