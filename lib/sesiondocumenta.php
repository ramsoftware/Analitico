<?php
/**
 * Chequea que la sesión este activa
 *
 * @author Rafael Alberto Moreno Parra <rafael-morenop@unilibre.edu.co>
 * @version Febrero 2023
 */

session_name("loginUsuario");
session_start();
if (!isset($_SESSION['usuariocodigo'])|| $_SESSION['rolcodigo'] != 5) {
	header("Location: ../../index.php");
	exit();
}