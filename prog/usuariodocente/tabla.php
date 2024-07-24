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
	public $Mostrar;
	
	//Conecta a la base de datos
	public function __construct(){
		$this->TablaVisual = "Usuarios"; //Cómo se va a ver en la parte visual
		$this->BaseDatos = new basedatos();
		$this->BaseDatos->Conectar();
		$this->Mostrar = $this->BaseDatos->RegistrosMostrarGRID();
	}

	//Crear el SQL de consulta para búsqueda
	public function Busqueda($Identifica, $Nombre, $Correo1, $Correo2, $Posicion){

		//Crea el SQL (teniendo en cuenta la búsqueda)
		$SQL = "SELECT usuarios.codigo, usuarios.identifica, usuarios.nombre, usuarios.correo1, usuarios.correo2 FROM usuarios WHERE usuarios.rol = 0 ";

		//Crea el filtro de búsqueda
		$Filtro = "";

		$BIdentifica = false;
		if ($Identifica != ""){
			$Filtro .= "usuarios.identifica LIKE :Identifica AND ";
			$BIdentifica = true;
		}

		$BNombre = false;
		if ($Nombre != ""){
			$Filtro .= "usuarios.nombre LIKE :Nombre AND ";
			$BNombre = true;
		}

		$BCorreo1 = false;
		if ($Correo1 != ""){
			$Filtro .= "usuarios.correo1 LIKE :Correo1 AND ";
			$BCorreo1 = true;
		}

		$BCorreo2 = false;
		if ($Correo2 != ""){
			$Filtro .= "usuarios.correo2 LIKE :Correo2 AND ";
			$BCorreo2 = true;
		}

		if (strlen($Filtro)>0) $Filtro = "AND " . substr($Filtro, 0, strlen($Filtro)-4);
		$SQL .= $Filtro . " LIMIT $Posicion, 1";
		$Sentencia = $this->BaseDatos->Conexion->prepare($SQL);

		//Agrega los valores
		if ($BIdentifica) $Sentencia->bindValue(':Identifica', '%'.$Identifica.'%');
		if ($BNombre) $Sentencia->bindValue(':Nombre', '%'.$Nombre.'%');
		if ($BCorreo1) $Sentencia->bindValue(':Correo1', '%'.$Correo1.'%');
		if ($BCorreo2) $Sentencia->bindValue(':Correo2', '%'.$Correo2.'%');

		$Sentencia->execute();  //Ejecuta la consulta
		//$Sentencia->debugDumpParams();
		return $Sentencia->fetch();
	}
	
	//Retorna el registro de la tabla que se quiere detallar o borrar
	public function VerRegistroDetalle($codigo){
		$SQL = "SELECT usuarios.codigo, usuarios.identifica, usuarios.nombre, usuarios.correo1, usuarios.correo2 FROM usuarios WHERE usuarios.codigo = :codigo";
		
		//Conecta a la base de datos y hace la consulta
		$Sentencia = $this->BaseDatos->Conexion->prepare($SQL);
		$Sentencia->bindValue(":codigo", $codigo);
		$Sentencia->execute();  //Ejecuta la consulta
		return $Sentencia->fetch();
	}

	//Retorna el registro de la tabla que se quiere actualizar
	public function VerRegistroActualiza($codigo){
		$SQL = "SELECT codigo, identifica, nombre, correo1, correo2 FROM usuarios WHERE usuarios.codigo = :codigo";

		//Conecta a la base de datos y hace la consulta
		$Sentencia = $this->BaseDatos->Conexion->prepare($SQL);
		$Sentencia->bindValue(":codigo", $codigo);
		$Sentencia->execute();  //Ejecuta la consulta
		return $Sentencia->fetch();
	}

	
	//Actualiza el registro
	public function Actualizar($codigo, $identifica, $contrasena, $nombre, $correo1, $correo2)
	{
		if ($contrasena != "")
			$SQL = "UPDATE usuarios SET identifica = :identifica, contrasena = :contrasena, nombre = :nombre, correo1 = :correo1, correo2 = :correo2 WHERE codigo = :codigo";
		else
			$SQL = "UPDATE usuarios SET identifica = :identifica, nombre = :nombre, correo1 = :correo1, correo2 = :correo2 WHERE codigo = :codigo";

		//Conecta a la base de datos y hace la actualización
		$Sentencia = $this->BaseDatos->Conexion->prepare($SQL);
		$Sentencia->bindValue(":codigo", $codigo);
		$Sentencia->bindValue(":identifica", $identifica);
		if ($contrasena != "") $Sentencia->bindValue(":contrasena", hash('sha512', $contrasena));
		$Sentencia->bindValue(":nombre", $nombre);
		$Sentencia->bindValue(":correo1", $correo1);
		$Sentencia->bindValue(":correo2", $correo2);

		try{
			$Sentencia->execute();  //Ejecuta la actualización
			return "Actualización de registro exitosa";
		}
		catch (Exception $excepcion) {
			return "Falló al actualizar registro. <br>Detalle: " . $excepcion->getMessage();
		}
	}
	
	//Adiciona el registro
	public function Adicionar($identifica, $contrasena, $nombre, $correo1, $correo2)
	{
		$SQL = "INSERT INTO usuarios (identifica, contrasena, nombre, correo1, correo2, rol) VALUES(:identifica, :contrasena, :nombre, :correo1, :correo2, :rol)";

		//Conecta a la base de datos y hace la adición
		$Sentencia = $this->BaseDatos->Conexion->prepare($SQL);
		$Sentencia->bindValue(":identifica", $identifica);
		$Sentencia->bindValue(":contrasena", hash('sha512', $contrasena));
		$Sentencia->bindValue(":nombre", $nombre);
		$Sentencia->bindValue(":correo1", $correo1);
		$Sentencia->bindValue(":correo2", $correo2);
		$Sentencia->bindValue(":rol", 3); //Docente

		try{
			$Sentencia->execute();  //Ejecuta la adición
			return "Adición de registro exitosa";
		}
		catch (Exception $excepcion) {
			return "Falló al adicionar registro. <br>Detalle: " . $excepcion->getMessage();
		}
	}
	
	//Actualiza el registro
	public function Borrar($codigo)
	{
		$SQL = "DELETE FROM usuarios WHERE codigo = :codigo";

		//Conecta a la base de datos y hace la actualización
		$Sentencia = $this->BaseDatos->Conexion->prepare($SQL);
		$Sentencia->bindValue(":codigo", $codigo);

		try{
			$Sentencia->execute();  //Ejecuta el borrado
			return "Borrado de registro exitoso";
		}
		catch (Exception $excepcion) {
			return "Falló al borrar registro. <br>Detalle: " . $excepcion->getMessage();
		}
	}

	//Registros para el grid
	public function DatosGrid($posicion)
	{
		$SQL = "SELECT usuarios.codigo, usuarios.identifica, usuarios.nombre, usuarios.correo1, usuarios.correo2 FROM usuarios WHERE usuarios.rol = 3 ORDER BY usuarios.nombre LIMIT $posicion, $this->Mostrar";
		$Sentencia = $this->BaseDatos->Conexion->prepare($SQL);
		$Sentencia->execute();  //Ejecuta la consulta
		$Registros = $Sentencia->fetchAll();

		$Datos = "";
		for ($Fila=0; $Fila < count($Registros); $Fila++){
			$Datos .= "<tr>";
			$Datos .= "<td>" . htmlentities($Registros[$Fila][1], ENT_QUOTES, "UTF-8") . "</td>";
			$Datos .= "<td>" . htmlentities($Registros[$Fila][2], ENT_QUOTES, "UTF-8") . "</td>";
			$Datos .= "<td>" . htmlentities($Registros[$Fila][3], ENT_QUOTES, "UTF-8") . "</td>";
			$Datos .= "<td>" . htmlentities($Registros[$Fila][4], ENT_QUOTES, "UTF-8") . "</td>";
			$Datos .= '<td><a href=\'iniciar.php?op=2&codigo=' . $Registros[$Fila][0] . '\' class=\'btn btn-primary\'>Más</a></td>';
			$Datos .= '</tr>';
		}
		return $Datos;
	}

	//Configuración de los PATH
	public function actualiza1() { return "../../vista/usuariodocente/actualiza1.html"; }
	public function actualiza2() { return "../../vista/usuariodocente/actualiza2.html"; }
	public function adiciona1() { return "../../vista/usuariodocente/adiciona1.html"; }
	public function adiciona2() { return "../../vista/usuariodocente/adiciona2.html"; }
	public function borra1() { return "../../vista/usuariodocente/borra1.html"; }
	public function borra2() { return "../../vista/usuariodocente/borra2.html"; }
	public function busca1() { return "../../vista/usuariodocente/busca1.html"; }
	public function busca2() { return "../../vista/usuariodocente/busca2.html"; }
	public function detalle() { return "../../vista/usuariodocente/detalle.html"; }
	public function registros() { return "../../vista/usuariodocente/registros.html"; }
	public function rutaprog() { return "../../prog/usuariodocente/"; }
	public function rutavista() { return "../../vista/usuariodocente/"; }
}