<?php
//=======================================
//Una clase que manejará la base de datos
//======================================= 
class basedatos{
	public $Servidor = "mysql:host=localhost";
	public $Sesion = "root";
	public $Contrasena = "";
	public $Instancia = "id20196501_analitico";
	
	public $Conexion; //Mantiene la conexión con la base de datos


	public function Conectar(){
		if (isset($this->Conexion)) return true; //Si ya está definida la conexión
		try {
			//Usando PDO (PHP Data Objects) para conectarse.
			$this->Conexion = new PDO($this->Servidor.";dbname=".$this->Instancia, $this->Sesion, $this->Contrasena, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
			$this->Conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		} catch (PDOException $UnError){
			echo $UnError->getMessage();
			return false;
		}
		return true;
	}
	
	//Sentencias para llenar los combobox poco a poco
	function ComboBoxDinamico($Tabla, $valorCampo, $textoCampo, $Busca){
		$this->Conectar();
		if ($Busca == ""){
			$SQL = "SELECT $valorCampo, $textoCampo FROM $Tabla ORDER BY $textoCampo LIMIT 10";
			$Sentencia = $this->Conexion->prepare($SQL);
		}
		else {
			$SQL = "SELECT $valorCampo, $textoCampo FROM $Tabla WHERE $textoCampo LIKE :buscando ORDER BY $textoCampo LIMIT 10";
			$Sentencia = $this->Conexion->prepare($SQL);
			$Sentencia->bindValue(':buscando', '%'.$Busca.'%', PDO::PARAM_STR);
		}
		
		$Sentencia->execute();
		$Lista = $Sentencia->fetchAll();
		
		$Respuesta = array();
		foreach($Lista as $Registro){
			$Respuesta[] = array(
				"id" => $Registro[0],
				"text" => $Registro[1]
			);
		}
		return $Respuesta;
	}


	//Sentencias para llenar la lista de valores poco a poco
	function ComboBoxFiltrado($tabla, $valorCampo, $textoCampo, $campoCondicion, $condicion, $Busca){
		$this->Conectar();
		if ($Busca == ""){
			$SQL = "SELECT $valorCampo, $textoCampo FROM $tabla WHERE $campoCondicion = :condicion ORDER BY $textoCampo LIMIT 10";
			$Sentencia = $this->Conexion->prepare($SQL);
			$Sentencia->bindValue(':condicion', $condicion);
		}
		else {
			$SQL = "SELECT $valorCampo, $textoCampo FROM $tabla WHERE $campoCondicion = :condicion AND $textoCampo LIKE :buscando ORDER BY $textoCampo LIMIT 10";
			$Sentencia = $this->Conexion->prepare($SQL);
			$Sentencia->bindValue(':condicion', $condicion);
			$Sentencia->bindValue(':buscando', '%'.$Busca.'%', PDO::PARAM_STR);
		}
		
		$Sentencia->execute();
		$Lista = $Sentencia->fetchAll();
		
		$Respuesta = array();
		foreach($Lista as $Registro){
			$Respuesta[] = array(
				"id" => $Registro[0],
				"text" => $Registro[1]
			);
		}
		return $Respuesta;
	}

	//Número de registros a mostrar en los grid
	public function RegistrosMostrarGRID(){
		return 10;
	}

}
