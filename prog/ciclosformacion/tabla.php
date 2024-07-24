<?php
//Autor: Rafael Alberto Moreno Parra. https://github.com/ramsoftware

//========================================
//Una clase que manejará la tabla
//========================================
require_once("../../lib/BD.php");

class tabla{
	//Mantiene la conexión activa
	public $BaseDatos;

	//Tabla que va a hacerle el CRUD
	public  $TablaBD;

	//Nombre en vista de esa tabla
	public  $TablaVisual;

	//Registros a mostrar en los grid
	public$Mostrar;
	
	//Conecta a la base de datos
	public function __construct(){
		$this->TablaBD = "ciclosformacion"; //Tabla a la que se se le va a hacer el CRUD
		$this->TablaVisual = "Ciclos de Formación"; //Cómo se va a ver en la parte visual
		$this->BaseDatos = new basedatos();
		$this->BaseDatos->Conectar();
		$this->Mostrar = $this->BaseDatos->RegistrosMostrarGRID();
	}
	
	//Crear el SQL de consulta para búsqueda
	public function Busqueda($nombre, $Posicion){
		$SQL = "SELECT codigo, nombre FROM $this->TablaBD WHERE nombre LIKE :nombre LIMIT $Posicion, 1";
		$Sentencia = $this->BaseDatos->Conexion->prepare($SQL);
		
		//Agrega los valores
		$Sentencia->bindValue(':nombre', '%'.$nombre.'%');
		$Sentencia->execute();  //Ejecuta la consulta
		return $Sentencia->fetch();
	}
	
	//Retorna el registro de la tabla que se quiere actualizar
	public function VerRegistro($codigo){
		$SQL = "SELECT codigo, nombre FROM $this->TablaBD WHERE codigo = :codigo";
		
		//Conecta a la base de datos y hace la consulta
		$Sentencia = $this->BaseDatos->Conexion->prepare($SQL);
		$Sentencia->bindValue(":codigo", $codigo);
		$Sentencia->execute();  //Ejecuta la consulta
		return $Sentencia->fetch();
	}
	
	//Actualiza el registro
	public function Actualizar($codigo, $nombre)
	{
		$SQL = "UPDATE $this->TablaBD SET nombre = :nombre WHERE codigo = :codigo";

		//Conecta a la base de datos y hace la actualización
		$Sentencia = $this->BaseDatos->Conexion->prepare($SQL);
		$Sentencia->bindValue(":nombre", $nombre);
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
	public function Adicionar($nombre)
	{
		$SQL = "INSERT INTO $this->TablaBD (nombre) VALUES(:nombre)";

		//Conecta a la base de datos y hace la adición
		$Sentencia = $this->BaseDatos->Conexion->prepare($SQL);
		$Sentencia->bindValue(":nombre", $nombre);

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
		$SQL = "DELETE FROM $this->TablaBD WHERE codigo = :codigo";

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
		$SQL = "SELECT codigo, nombre FROM $this->TablaBD ORDER BY nombre LIMIT $posicion, $this->Mostrar";
		$Sentencia = $this->BaseDatos->Conexion->prepare($SQL);
		$Sentencia->execute();  //Ejecuta la consulta
		$Registros = $Sentencia->fetchAll();

		$Datos = "";
		for ($Fila=0; $Fila < count($Registros); $Fila++){
			$Datos .= "<tr>";
			$Datos .= "<td>" . htmlentities($Registros[$Fila][1], ENT_QUOTES, "UTF-8") . "</td>";
			$Datos .= '<td><a href=\'iniciar.php?op=2&codigo=' . $Registros[$Fila][0] . '\' class=\'btn btn-primary\'>Más</a></td>';
			$Datos .= '</tr>';
		}
		return $Datos;
	}

	//Configuración de los PATH
	public function actualiza1() { return "../../vista/$this->TablaBD/actualiza1.html"; }
	public function actualiza2() { return "../../vista/$this->TablaBD/actualiza2.html"; }
	public function adiciona1() { return "../../vista/$this->TablaBD/adiciona1.html"; }
	public function adiciona2() { return "../../vista/$this->TablaBD/adiciona2.html"; }
	public function borra1() { return "../../vista/$this->TablaBD/borra1.html"; }
	public function borra2() { return "../../vista/$this->TablaBD/borra2.html"; }
	public function busca1() { return "../../vista/$this->TablaBD/busca1.html"; }
	public function busca2() { return "../../vista/$this->TablaBD/busca2.html"; }
	public function detalle() { return "../../vista/$this->TablaBD/detalle.html"; }
	public function registros() { return "../../vista/$this->TablaBD/registros.html"; }
	public function rutaprog() { return "../../prog/$this->TablaBD/"; }
	public function rutavista() { return "../../vista/$this->TablaBD/"; }
}