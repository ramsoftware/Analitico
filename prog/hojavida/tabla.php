<?php
//Autor: Rafael Alberto Moreno Parra. https://github.com/ramsoftware

//========================================
//Una clase que manejará la tabla
//========================================
require_once("../../lib/BD.php");

class tabla{
	//Mantiene la conexión activa
	public $BaseDatos;

	//Nombre en vista de esa tabla
	public  $TablaVisual;

	//Registros a mostrar en los grid
	public$Mostrar;
	
	//Conecta a la base de datos
	public function __construct(){
		$this->TablaVisual = "Docentes"; //Cómo se va a ver en la parte visual
		$this->BaseDatos = new basedatos();
		$this->BaseDatos->Conectar();
		$this->Mostrar = $this->BaseDatos->RegistrosMostrarGRID();
	}

	//Retorna el registro de la tabla que se quiere actualizar
	public function VerRegistroActualiza($codigo){
		$SQL = "SELECT codigo, nombre, correo1, correo2, perfilacademico, experienciadocente, experienciaprofesional, experienciainvestigacion, prodacademicaprof FROM usuarios WHERE usuarios.codigo = :codigo";

		//Conecta a la base de datos y hace la consulta
		$Sentencia = $this->BaseDatos->Conexion->prepare($SQL);
		$Sentencia->bindValue(":codigo", $codigo);
		$Sentencia->execute();  //Ejecuta la consulta
		return $Sentencia->fetch();
	}

	
	//Actualiza el registro
	public function Actualizar($codigo, $contrasena, $nombre, $correo1, $correo2, $perfil, $docente, $profesional, $investigacion, $produccion)
	{
		if ($contrasena != "") {
			$contrasena = hash('sha512', $contrasena);
			$SQL = "UPDATE usuarios SET contrasena = :contrasena, nombre = :nombre, correo1 = :correo1, correo2 = :correo2, perfilacademico = :perfil, experienciadocente = :docente, experienciaprofesional = :profesional, experienciainvestigacion = :investigacion, prodacademicaprof = :produccion WHERE codigo = :codigo";
		}
		else
			$SQL = "UPDATE usuarios SET nombre = :nombre, correo1 = :correo1, correo2 = :correo2 , perfilacademico = :perfil, experienciadocente = :docente, experienciaprofesional = :profesional, experienciainvestigacion = :investigacion, prodacademicaprof = :produccion WHERE codigo = :codigo";

		//Conecta a la base de datos y hace la actualización
		$Sentencia = $this->BaseDatos->Conexion->prepare($SQL);
		$Sentencia->bindValue(":codigo", $codigo);
		if ($contrasena != "") $Sentencia->bindValue(":contrasena", $contrasena);
		$Sentencia->bindValue(":nombre", $nombre);
		$Sentencia->bindValue(":correo1", $correo1);
		$Sentencia->bindValue(":correo2", $correo2);
		$Sentencia->bindValue(":perfil", $perfil);
		$Sentencia->bindValue(":docente", $docente);
		$Sentencia->bindValue(":profesional", $profesional);
		$Sentencia->bindValue(":investigacion", $investigacion);
		$Sentencia->bindValue(":produccion", $produccion);

		try{
			$Sentencia->execute();  //Ejecuta la actualización
			return "Actualización de registro exitosa";
		}
		catch (Exception $excepcion) {
			return "Falló al actualizar registro. <br>Detalle: " . $excepcion->getMessage();
		}
	}
	
	//Configuración de los PATH
	public function actualiza1() { return "../../vista/hojavida/actualiza1.html"; }
	public function actualiza2() { return "../../vista/hojavida/actualiza2.html"; }
	public function rutaprog() { return "../../prog/hojavida/"; }
	public function rutavista() { return "../../vista/hojavida/"; }
}