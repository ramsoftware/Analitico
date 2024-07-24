<?php
//========================================
//Una clase que manejará la tabla catedras
//========================================
require_once("../../lib/BD.php");

class tabla{
	//Mantiene la conexión activa
	public $BaseDatos;

	//Conecta a la base de datos
	public function __construct(){
		$this->TablaBD = "catedras"; //Tabla a la que se se le va a hacer el CRUD
		$this->TablaVisual = "Cátedras"; //Cómo se va a ver en la parte visual
		$this->BaseDatos = new basedatos();
		$this->BaseDatos->Conectar();
		$this->Mostrar = $this->BaseDatos->RegistrosMostrarGRID();
	}

	public function UnidadAutorizada($unidad, $docente){
		$SQL = "SELECT COUNT(*) FROM catedras WHERE docente = :docente and editable = 1 AND codigo IN (SELECT catedra FROM unidades WHERE codigo = :unidad)";
		$Sentencia = $this->BaseDatos->Conexion->prepare($SQL);
		$Sentencia->bindValue(":unidad", $unidad);
		$Sentencia->bindValue(":docente", $docente);
		$Sentencia->execute();  //Ejecuta la consulta
		$Registros = $Sentencia->fetchAll();
		if ($Registros[0][0] > 0) return true;
		return false;
	}

	public function CatedraAutorizada($catedra, $docente){
		$SQL = "SELECT COUNT(*) FROM catedras WHERE codigo = :codigo AND docente = :docente AND editable = 1";
		$Sentencia = $this->BaseDatos->Conexion->prepare($SQL);
		$Sentencia->bindValue(":codigo", $catedra);
		$Sentencia->bindValue(":docente", $docente);
		$Sentencia->execute();  //Ejecuta la consulta
		$Registros = $Sentencia->fetchAll();
		if ($Registros[0][0] > 0) return true;
		return false;
	}

	//Registros para el grid
	public function DatosGrid($Catedra)
	{
		$SQL = "SELECT codigo, titulo FROM unidades WHERE catedra = :catedra ORDER BY titulo";
		$Sentencia = $this->BaseDatos->Conexion->prepare($SQL);
		$Sentencia->bindValue(":catedra", $Catedra);
		$Sentencia->execute();  //Ejecuta la consulta
		$Registros = $Sentencia->fetchAll();

		$Datos = "";
		for ($Fila=0; $Fila < count($Registros); $Fila++){
			$Datos .= "<tr>";
			$Datos .= "<td>" . htmlentities($Registros[$Fila][1], ENT_QUOTES, "UTF-8") . "</td>";
			$Datos .= '<td><a href=\'iniciar.php?unidad=' . $Registros[$Fila][0] . '&op=2 \' class=\'btn btn-primary\'>Editar</a></td>';
			$Datos .= '</tr>';
		}
		return $Datos;
	}

	//Periodo y nombre de la cátedra
	public function DatosCatedra($catedra){
		//La sentencia de consulta
		$SQL = "SELECT catedras.nombre, periodos.nombre FROM catedras, periodos WHERE periodos.codigo = catedras.periodo AND catedras.codigo = :catedra";

		//Conecta a la base de datos y hace la consulta
		$Sentencia = $this->BaseDatos->Conexion->prepare($SQL);
		$Sentencia->bindValue(":catedra", $catedra);
		$Sentencia->execute();  //Ejecuta la consulta
		return $Sentencia->fetch();
	}

	//Retorna el registro de la tabla para borrar o detalle
	public function VerRegistro($codigo){
		$SQL = "SELECT unidades.titulo, unidades.temas, catedras.nombre, periodos.nombre, catedras.codigo
FROM unidades, catedras, periodos
WHERE unidades.catedra = catedras.codigo
AND periodos.codigo = catedras.periodo
AND unidades.codigo = :codigo";

		//Conecta a la base de datos y hace la consulta
		$Sentencia = $this->BaseDatos->Conexion->prepare($SQL);
		$Sentencia->bindValue(":codigo", $codigo);
		$Sentencia->execute();  //Ejecuta la consulta
		return $Sentencia->fetch();
	}

	//Retorna el registro de la tabla para actualizar
	public function VerRegistroActualiza($codigo){
		$SQL = "SELECT unidades.titulo, unidades.temas, catedras.nombre, periodos.nombre, catedras.codigo
FROM unidades, catedras, periodos
WHERE unidades.catedra = catedras.codigo
AND periodos.codigo = catedras.periodo
AND unidades.codigo = :codigo";

		//Conecta a la base de datos y hace la consulta
		$Sentencia = $this->BaseDatos->Conexion->prepare($SQL);
		$Sentencia->bindValue(":codigo", $codigo);
		$Sentencia->execute();  //Ejecuta la consulta
		return $Sentencia->fetch();
	}

	//Actualiza el registro
	public function Actualizar($codigo, $titulo, $temas)
	{
		$SQL = "UPDATE unidades SET titulo = :titulo, temas = :temas WHERE codigo = :codigo";

		//Conecta a la base de datos y hace la actualización
		$Sentencia = $this->BaseDatos->Conexion->prepare($SQL);
		$Sentencia->bindValue(":titulo", $titulo);
		$Sentencia->bindValue(":temas", $temas);
		$Sentencia->bindValue(":codigo", $codigo);

		try{
			$Sentencia->execute();  //Ejecuta la actualización
			return 1;
		}
		catch (Exception $excepcion) {
			return "Falló al actualizar registro. <br>Detalle: " . $excepcion->getMessage();
		}
	}

	//Adiciona el registro de temas de la cátedra
	public function Adicionar($catedra, $titulo, $temas){
		$SQL = "INSERT INTO unidades(catedra, titulo, temas)
		VALUES (:catedra, :titulo, :temas)";

		//Conecta a la base de datos y hace la actualización
		$Sentencia = $this->BaseDatos->Conexion->prepare($SQL);
		$Sentencia->bindValue(":catedra", $catedra);
		$Sentencia->bindValue(":titulo", $titulo);
		$Sentencia->bindValue(":temas", $temas);

		try{
			$Sentencia->execute();  //Ejecuta la actualización
			return 1;
		}
		catch (Exception $e) {
			return "Falló al adicionar registro. Error: " . implode(" | ",$Sentencia->errorinfo());
		}
	}

	//Actualiza el registro
	public function Borrar($codigo)
	{
		$SQL = "DELETE FROM unidades WHERE codigo = :codigo";

		//Conecta a la base de datos y hace la actualización
		$Sentencia = $this->BaseDatos->Conexion->prepare($SQL);
		$Sentencia->bindValue(":codigo", $codigo);

		try{
			$Sentencia->execute();  //Ejecuta el borrado
			return 1;
		}
		catch (Exception $excepcion) {
			return "Falló al borrar registro. <br>Detalle: " . $excepcion->getMessage();
		}
	}

	//Retorna la cátedra dada la unidad
	public function CodigoCatedra($Unidad){
		$SQL = "SELECT catedra FROM unidades WHERE codigo = :Unidad";

		//Conecta a la base de datos y hace la consulta
		$Sentencia = $this->BaseDatos->Conexion->prepare($SQL);
		$Sentencia->bindValue(":Unidad", $Unidad);
		$Sentencia->execute();  //Ejecuta la consulta
		$Registro = $Sentencia->fetch();
		return $Registro[0];
	}

	//Para crear el combobox de Recursos Pedagógicos
	public function ComboBoxPedogico($Codigo){
		$SQL = "SELECT nombre FROM recursospedagogicos WHERE codigo = :Codigo";

		//Hace la consulta
		$Sentencia = $this->BaseDatos->Conexion->prepare($SQL);
		$Sentencia->bindValue(":Codigo", $Codigo);
		$Sentencia->execute();
		$Lista = $Sentencia->fetch();
		return '<option value="'. $Codigo .'" selected="selected">'. $Lista[0] . '</option>';
	}

	//Para crear el combobox de Materiales de Apoyo
	public function ComboBoxMaterialApoyo($Codigo){
		$SQL = "SELECT nombre FROM materialesapoyo WHERE codigo = :Codigo";

		//Hace la consulta
		$Sentencia = $this->BaseDatos->Conexion->prepare($SQL);
		$Sentencia->bindValue(":Codigo", $Codigo);
		$Sentencia->execute();
		$Lista = $Sentencia->fetch();
		return '<option value="'. $Codigo .'" selected="selected">'. $Lista[0] . '</option>';
	}


	//Para crear el combobox de Herramientas TIC
	public function ComboBoxHerramientaTIC($Codigo){
		$SQL = "SELECT nombre FROM herramientastic WHERE codigo = :Codigo";

		//Hace la consulta
		$Sentencia = $this->BaseDatos->Conexion->prepare($SQL);
		$Sentencia->bindValue(":Codigo", $Codigo);
		$Sentencia->execute();
		$Lista = $Sentencia->fetch();
		return '<option value="'. $Codigo .'" selected="selected">'. $Lista[0] . '</option>';
	}

	//Configuración de los PATH
	public function actualiza1() { return "../../vista/temario/actualiza1.html"; }
	public function actualiza2() { return "../../vista/temario/actualiza2.html"; }
	public function adiciona1() { return "../../vista/temario/adiciona1.html"; }
	public function adiciona2() { return "../../vista/temario/adiciona2.html"; }
	public function borra1() { return "../../vista/temario/borra1.html"; }
	public function borra2() { return "../../vista/temario/borra2.html"; }
	public function detalle() { return "../../vista/temario/detalle.html"; }
	public function registros() { return "../../vista/temario/registros.html"; }
	public function rutaprog() { return "../../prog/temario/"; }
	public function rutavista() { return "../../vista/temario/"; }
}