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
		$this->TablaVisual = "Programas"; //Cómo se va a ver en la parte visual
		$this->BaseDatos = new basedatos();
		$this->BaseDatos->Conectar();
		$this->Mostrar = $this->BaseDatos->RegistrosMostrarGRID();
	}

	//Crear el SQL de consulta para búsqueda
	public function Busqueda($Facultad, $Nombre, $Posicion){

		//Crea el SQL (teniendo en cuenta la búsqueda)
		$SQL = "SELECT programas.codigo, facultades.nombre, programas.nombre FROM facultades, programas WHERE programas.facultad = facultades.codigo ";

		//Crea el filtro de búsqueda
		$Filtro = "";

		$BNombre = false;
		if ($Nombre != ""){
			$Filtro .= "programas.nombre LIKE :Nombre AND ";
			$BNombre = true;
		}

		$BFacultad = false;
		if ($Facultad != ""){
			$Filtro .= "programas.Facultad = :Facultad AND ";
			$BFacultad = true;
		}

		if (strlen($Filtro)>0) $Filtro = "AND " . substr($Filtro, 0, strlen($Filtro)-4);
		$SQL .= $Filtro . " LIMIT $Posicion, 1";
		$Sentencia = $this->BaseDatos->Conexion->prepare($SQL);

		//Agrega los valores
		if ($BNombre) $Sentencia->bindValue(':Nombre', '%'.$Nombre.'%');
		if ($BFacultad) $Sentencia->bindValue(':Facultad', $Facultad);
		$Sentencia->execute();  //Ejecuta la consulta
		//$Sentencia->debugDumpParams();
		return $Sentencia->fetch();
	}
	
	//Retorna el registro de la tabla que se quiere detallar o borrar
	public function VerRegistroDetalle($codigo){
		$SQL = "SELECT programas.codigo, programas.nombre, facultades.nombre FROM programas, facultades WHERE programas.facultad = facultades.codigo AND programas.codigo = :codigo";
		
		//Conecta a la base de datos y hace la consulta
		$Sentencia = $this->BaseDatos->Conexion->prepare($SQL);
		$Sentencia->bindValue(":codigo", $codigo);
		$Sentencia->execute();  //Ejecuta la consulta
		return $Sentencia->fetch();
	}

	//Retorna el registro de la tabla que se quiere actualizar
	public function VerRegistroActualiza($codigo){
		$SQL = "SELECT codigo, nombre, facultad FROM programas WHERE programas.codigo = :codigo";

		//Conecta a la base de datos y hace la consulta
		$Sentencia = $this->BaseDatos->Conexion->prepare($SQL);
		$Sentencia->bindValue(":codigo", $codigo);
		$Sentencia->execute();  //Ejecuta la consulta
		return $Sentencia->fetch();
	}

	
	//Actualiza el registro
	public function Actualizar($codigo, $nombre, $facultad)
	{
		$SQL = "UPDATE programas SET nombre = :nombre, facultad = :facultad WHERE codigo = :codigo";

		//Conecta a la base de datos y hace la actualización
		$Sentencia = $this->BaseDatos->Conexion->prepare($SQL);
		$Sentencia->bindValue(":nombre", $nombre);
		$Sentencia->bindValue(":facultad", $facultad);
		$Sentencia->bindValue(":codigo", $codigo);

		try{
			$Sentencia->execute();  //Ejecuta la actualización
			return "Actualización de registro exitosa";
		}
		catch (Exception $excepcion) {
			return "Falló al actualizar registro. <br>Detalle: " . $excepcion->getMessage();
		}
	}
	
	//Adiciona el registro
	public function Adicionar($nombre, $facultad)
	{
		$SQL = "INSERT INTO programas (nombre, facultad) VALUES(:nombre, :facultad)";

		//Conecta a la base de datos y hace la adición
		$Sentencia = $this->BaseDatos->Conexion->prepare($SQL);
		$Sentencia->bindValue(":nombre", $nombre);
		$Sentencia->bindValue(":facultad", $facultad);

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
		$SQL = "DELETE FROM programas WHERE codigo = :codigo";

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

	//Registros para el grid se crean en un JSON y se envía a una bootstrap-table
	public function DatosGrid($posicion)
	{
		$SQL = "SELECT programas.codigo, programas.nombre, facultades.nombre FROM programas, facultades WHERE programas.facultad = facultades.codigo ORDER BY programas.nombre LIMIT $posicion, $this->Mostrar";
		$Sentencia = $this->BaseDatos->Conexion->prepare($SQL);
		$Sentencia->execute();  //Ejecuta la consulta
		$Registros = $Sentencia->fetchAll();

		$Datos = "";
		for ($Fila=0; $Fila < count($Registros); $Fila++){
			$Datos .= "<tr>";
			$Datos .= "<td>" . htmlentities($Registros[$Fila][1], ENT_QUOTES, "UTF-8") . "</td>";
			$Datos .= "<td>" . htmlentities($Registros[$Fila][2], ENT_QUOTES, "UTF-8") . "</td>";			
			$Datos .= '<td><a href=\'iniciar.php?op=2&codigo=' . $Registros[$Fila][0] . '\' class=\'btn btn-primary\'>Más</a></td>';
			$Datos .= '</tr>';
		}
		return $Datos;
	}

	//Para crear el combobox de Facultades
	public function ComboBoxFacultad($Codigo){
		$SQL = "SELECT nombre FROM facultades WHERE codigo = :Codigo";

		//Hace la consulta
		$Sentencia = $this->BaseDatos->Conexion->prepare($SQL);
		$Sentencia->bindValue(":Codigo", $Codigo);
		$Sentencia->execute();
		$Lista = $Sentencia->fetch();
		return '<option value="'. $Codigo .'" selected="selected">'. $Lista[0] . '</option>';
	}

	//Configuración de los PATH
	public function actualiza1() { return "../../vista/programas/actualiza1.html"; }
	public function actualiza2() { return "../../vista/programas/actualiza2.html"; }
	public function adiciona1() { return "../../vista/programas/adiciona1.html"; }
	public function adiciona2() { return "../../vista/programas/adiciona2.html"; }
	public function borra1() { return "../../vista/programas/borra1.html"; }
	public function borra2() { return "../../vista/programas/borra2.html"; }
	public function busca1() { return "../../vista/programas/busca1.html"; }
	public function busca2() { return "../../vista/programas/busca2.html"; }
	public function detalle() { return "../../vista/programas/detalle.html"; }
	public function registros() { return "../../vista/programas/registros.html"; }
	public function rutaprog() { return "../../prog/programas/"; }
	public function rutavista() { return "../../vista/programas/"; }
}