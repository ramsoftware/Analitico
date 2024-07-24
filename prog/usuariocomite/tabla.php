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
		$this->TablaVisual = "Usuarios"; //Cómo se va a ver en la parte visual
		$this->BaseDatos = new basedatos();
		$this->BaseDatos->Conectar();
		$this->Mostrar = $this->BaseDatos->RegistrosMostrarGRID();
	}

	//Crear el SQL de consulta para búsqueda
	public function Busqueda($Identifica, $Programa, $Nombre, $Correo1, $Correo2, $Posicion){

		//Crea el SQL (teniendo en cuenta la búsqueda)
		$SQL = "SELECT usuarios.codigo, usuarios.identifica, programas.nombre, usuarios.nombre, usuarios.correo1, usuarios.correo2 FROM programas, usuarios WHERE usuarios.rol = 2 and usuarios.programa = programas.codigo ";

		//Crea el filtro de búsqueda
		$Filtro = "";

		$BIdentifica = false;
		if ($Identifica != ""){
			$Filtro .= "usuarios.identifica LIKE :Identifica AND ";
			$BIdentifica = true;
		}

		$BPrograma = false;
		if ($Programa != ""){
			$Filtro .= "usuarios.programa = :Rol AND ";
			$BPrograma = true;
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
		if ($BPrograma) $Sentencia->bindValue(':Programa', $Programa);
		if ($BCorreo1) $Sentencia->bindValue(':Correo1', '%'.$Correo1.'%');
		if ($BCorreo2) $Sentencia->bindValue(':Correo2', '%'.$Correo2.'%');

		$Sentencia->execute();  //Ejecuta la consulta
		//$Sentencia->debugDumpParams();
		return $Sentencia->fetch();
	}
	
	//Retorna el registro de la tabla que se quiere detallar o borrar
	public function VerRegistroDetalle($codigo){
		$SQL = "SELECT usuarios.codigo, usuarios.identifica, programas.nombre, usuarios.nombre, usuarios.correo1, usuarios.correo2 FROM usuarios, programas WHERE usuarios.programa = programas.codigo AND usuarios.codigo = :codigo";
		
		//Conecta a la base de datos y hace la consulta
		$Sentencia = $this->BaseDatos->Conexion->prepare($SQL);
		$Sentencia->bindValue(":codigo", $codigo);
		$Sentencia->execute();  //Ejecuta la consulta
		return $Sentencia->fetch();
	}

	//Retorna el registro de la tabla que se quiere actualizar
	public function VerRegistroActualiza($codigo){
		$SQL = "SELECT codigo, identifica, programa, nombre, correo1, correo2 FROM usuarios WHERE usuarios.codigo = :codigo";

		//Conecta a la base de datos y hace la consulta
		$Sentencia = $this->BaseDatos->Conexion->prepare($SQL);
		$Sentencia->bindValue(":codigo", $codigo);
		$Sentencia->execute();  //Ejecuta la consulta
		return $Sentencia->fetch();
	}

	
	//Actualiza el registro
	public function Actualizar($codigo, $identifica, $contrasena, $programa, $nombre, $correo1, $correo2)
	{
		if ($contrasena != "")
			$SQL = "UPDATE usuarios SET identifica = :identifica, contrasena = :contrasena, programa = :programa, nombre = :nombre, correo1 = :correo1, correo2 = :correo2 WHERE codigo = :codigo";
		else
			$SQL = "UPDATE usuarios SET identifica = :identifica, programa = :programa, nombre = :nombre, correo1 = :correo1, correo2 = :correo2 WHERE codigo = :codigo";

		//Conecta a la base de datos y hace la actualización
		$Sentencia = $this->BaseDatos->Conexion->prepare($SQL);
		$Sentencia->bindValue(":codigo", $codigo);
		$Sentencia->bindValue(":identifica", $identifica);
		if ($contrasena != "") $Sentencia->bindValue(":contrasena", hash('sha512', $contrasena));
		$Sentencia->bindValue(":programa", $programa);
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
	public function Adicionar($identifica, $contrasena, $programa, $nombre, $correo1, $correo2)
	{
		$SQL = "INSERT INTO usuarios (identifica, contrasena, programa, nombre, correo1, correo2, rol) VALUES(:identifica, :contrasena, :programa, :nombre, :correo1, :correo2, :rol)";

		//Conecta a la base de datos y hace la adición
		$Sentencia = $this->BaseDatos->Conexion->prepare($SQL);
		$Sentencia->bindValue(":identifica", $identifica);
		$Sentencia->bindValue(":contrasena", hash('sha512', $contrasena));
		$Sentencia->bindValue(":programa", $programa);
		$Sentencia->bindValue(":nombre", $nombre);
		$Sentencia->bindValue(":correo1", $correo1);
		$Sentencia->bindValue(":correo2", $correo2);
		$Sentencia->bindValue(":rol", 2); //Comité académico

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
		$SQL = "SELECT usuarios.codigo, usuarios.identifica, programas.nombre, usuarios.nombre, usuarios.correo1, usuarios.correo2 FROM usuarios, programas WHERE usuarios.programa = programas.codigo and usuarios.rol = 2 ORDER BY usuarios.nombre LIMIT $posicion, $this->Mostrar";
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

	//Para crear el combobox de Roles
	public function ComboBoxPrograma($Codigo){
		$SQL = "SELECT nombre FROM programas WHERE codigo = :Codigo";

		//Hace la consulta
		$Sentencia = $this->BaseDatos->Conexion->prepare($SQL);
		$Sentencia->bindValue(":Codigo", $Codigo);
		$Sentencia->execute();
		$Lista = $Sentencia->fetch();
		return '<option value="'. $Codigo .'" selected="selected">'. $Lista[0] . '</option>';
	}

	//Configuración de los PATH
	public function actualiza1() { return "../../vista/usuariocomite/actualiza1.html"; }
	public function actualiza2() { return "../../vista/usuariocomite/actualiza2.html"; }
	public function adiciona1() { return "../../vista/usuariocomite/adiciona1.html"; }
	public function adiciona2() { return "../../vista/usuariocomite/adiciona2.html"; }
	public function borra1() { return "../../vista/usuariocomite/borra1.html"; }
	public function borra2() { return "../../vista/usuariocomite/borra2.html"; }
	public function busca1() { return "../../vista/usuariocomite/busca1.html"; }
	public function busca2() { return "../../vista/usuariocomite/busca2.html"; }
	public function detalle() { return "../../vista/usuariocomite/detalle.html"; }
	public function registros() { return "../../vista/usuariocomite/registros.html"; }
	public function rutaprog() { return "../../prog/usuariocomite/"; }
	public function rutavista() { return "../../vista/usuariocomite/"; }
}