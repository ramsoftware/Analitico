<?php
//========================================
//Una clase que manejará la tabla usuarios
//========================================
require_once("../../lib/BD.php");

class tabla{
	//Mantiene la conexión activa
	public $BaseDatos;

	//Tiene la identificación
	public $UsuarioNombre;
	public $UsuarioCodigo;

	//Tiene el rol
	public $RolCodigo;
	public $RolNombre;

	//Tiene el programa (para comité académico)
	public $ProgramaCodigo;
	public $ProgramaNombre;

	//Conecta a la base de datos
	public function __construct(){
		$this->BaseDatos = new basedatos();
		$this->BaseDatos->Conectar();
	}
	
	//Crear el SQL de validación de existencia del usuario
	public function Validar($Identifica, $Contrasena){
		$PuedeEntrar = false;
		$SQL = "SELECT contrasena, rol FROM usuarios WHERE identifica = :identifica";
		$Sentencia = $this->BaseDatos->Conexion->prepare($SQL);
		$Sentencia->bindValue(":identifica", $Identifica);
		$Sentencia->execute();  //Ejecuta la consulta
		$Registros = $Sentencia->fetch();
		$this->RolCodigo = $Registros[1];
		if($Registros[0] == hash('sha512', $Contrasena)) {
			$PuedeEntrar = true;
			$SQL = "SELECT usuarios.contrasena, usuarios.rol, roles.nombre, usuarios.nombre, usuarios.codigo, usuarios.programa, programas.nombre FROM usuarios, roles, programas WHERE usuarios.rol = roles.codigo AND usuarios.programa = programas.codigo AND identifica = :identifica";
			$Sentencia = $this->BaseDatos->Conexion->prepare($SQL);
			$Sentencia->bindValue(":identifica", $Identifica);
			$Sentencia->execute();  //Ejecuta la consulta
			$Registros = $Sentencia->fetch();
			$this->RolNombre = $Registros[2];
			$this->UsuarioNombre = $Registros[3];
			$this->UsuarioCodigo = $Registros[4];
			$this->ProgramaCodigo = $Registros[5];
			$this->ProgramaNombre = $Registros[6];
		}
		return $PuedeEntrar;
	}
	
}