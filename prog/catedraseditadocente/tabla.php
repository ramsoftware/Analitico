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

	//Valida que la cátedra pertenezca al docente
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
	public function DatosGrid($posicion, $docente)
	{
		$SQL = "SELECT catedras.codigo, catedras.codigouniversidad, catedras.nombre, programas.nombre, periodos.nombre, catedras.semestre
FROM programas, periodos, catedras
WHERE catedras.programa = programas.codigo 
AND catedras.periodo = periodos.codigo
AND catedras.docente = :docente
AND catedras.editable = 1
ORDER BY catedras.nombre LIMIT $posicion, $this->Mostrar";
		$Sentencia = $this->BaseDatos->Conexion->prepare($SQL);
		$Sentencia->bindValue(":docente", $docente);
		$Sentencia->execute();  //Ejecuta la consulta
		$Registros = $Sentencia->fetchAll();

		$Datos = "";
		for ($Fila=0; $Fila < count($Registros); $Fila++){
			$Datos .= "<tr>";
			$Datos .= "<td>" . htmlentities($Registros[$Fila][1], ENT_QUOTES, "UTF-8") . "</td>";
			$Datos .= "<td>" . htmlentities($Registros[$Fila][2], ENT_QUOTES, "UTF-8") . "</td>";
			$Datos .= "<td>" . htmlentities($Registros[$Fila][3], ENT_QUOTES, "UTF-8") . "</td>";
			$Datos .= "<td>" . htmlentities($Registros[$Fila][4], ENT_QUOTES, "UTF-8") . "</td>";
			$Datos .= "<td>" . htmlentities($Registros[$Fila][5], ENT_QUOTES, "UTF-8") . "</td>";
			$Datos .= '<td><a href=\'menuedita.php?catedra=' . $Registros[$Fila][0] . '\' class=\'btn btn-primary\'>Editar</a></td>';
			$Datos .= '</tr>';
		}
		return $Datos;
	}

	//Retorna el registro de la tabla que se quiere ver en detalle
	public function TraeBasico($codigo){
		//La sentencia de consulta
		$SQL = "SELECT catedras.codigo, periodos.nombre, programas.nombre, areasconoce.nombre, ciclosformacion.nombre, componentes.nombre, 
catedras.nombre, catedras.codigouniversidad, catedras.semestre, nivelesformacion.nombre, catedras.horasdocente, catedras.horasindependiente, catedras.creditos, 
modalidades.nombre, tiposasignatura.nombre, catedras.descripcion, catedras.justificacion, catedras.metodologia, catedras.documentos 
FROM catedras, periodos, areasconoce, ciclosformacion, componentes, nivelesformacion,  modalidades, tiposasignatura, programas
WHERE periodos.codigo = catedras.periodo
AND programas.codigo = catedras.programa
AND areasconoce.codigo = catedras.areaconocimiento
AND ciclosformacion.codigo = catedras.cicloformacion
AND componentes.codigo = catedras.componenteformacion
AND nivelesformacion.codigo = catedras.nivelformacion
AND modalidades.codigo = catedras.modalidad
AND tiposasignatura.codigo = catedras.tipo AND catedras.codigo = :codigo";

		//Conecta a la base de datos y hace la consulta
		$Sentencia = $this->BaseDatos->Conexion->prepare($SQL);
		$Sentencia->bindValue(":codigo", $codigo);
		$Sentencia->execute();  //Ejecuta la consulta
		return $Sentencia->fetch();
	}

	//Retorna periodo y nombre de la catedra para el menú de gestión de cátedras
	public function VerRegistroCatedraDocente($codigo){
		//La sentencia de consulta
		$SQL = "SELECT catedras.codigo, periodos.nombre, catedras.nombre FROM catedras, periodos WHERE periodos.codigo = catedras.periodo AND catedras.codigo = :codigo";

		//Conecta a la base de datos y hace la consulta
		$Sentencia = $this->BaseDatos->Conexion->prepare($SQL);
		$Sentencia->bindValue(":codigo", $codigo);
		$Sentencia->execute();  //Ejecuta la consulta
		return $Sentencia->fetch();
	}

	//Retorna el registro de la tabla que se quiere ver en detalle
	public function VerRegistroDescripcion($codigo){
		//La sentencia de consulta
		$SQL = "SELECT catedras.codigo, periodos.nombre, catedras.descripcion, catedras.justificacion, catedras.metodologia, catedras.documentos, catedras.nombre 
FROM catedras, periodos
WHERE periodos.codigo = catedras.periodo
AND catedras.codigo = :codigo";

		//Conecta a la base de datos y hace la consulta
		$Sentencia = $this->BaseDatos->Conexion->prepare($SQL);
		$Sentencia->bindValue(":codigo", $codigo);
		$Sentencia->execute();  //Ejecuta la consulta
		return $Sentencia->fetch();
	}

	//Actualiza el registro
	public function Actualizar($codigo, $descripcion, $justificacion, $metodologia, $bibliografia, $competencias, $resultados){
		$SQL = "UPDATE catedras SET descripcion = :descripcion, justificacion = :justificacion, metodologia = :metodologia, documentos = :bibliografia WHERE codigo = :codigo";

		//Conecta a la base de datos y hace la actualización
		$Sentencia = $this->BaseDatos->Conexion->prepare($SQL);
		$Sentencia->bindValue(":codigo", $codigo);
		$Sentencia->bindValue(":descripcion", $descripcion);
		$Sentencia->bindValue(":justificacion", $justificacion);
		$Sentencia->bindValue(":metodologia", $metodologia);
		$Sentencia->bindValue(":bibliografia", $bibliografia);

		try{
			$Sentencia->execute();  //Ejecuta la actualización

			$SQL = "DELETE FROM catedracompetencias WHERE catedra = :codigo";
			$Sentencia = $this->BaseDatos->Conexion->prepare($SQL);
			$Sentencia->bindValue(":codigo", $codigo);
			$Sentencia->execute();  //Ejecuta el borrado

			$SQL = "DELETE FROM catedraresultados WHERE catedra = :codigo";
			$Sentencia = $this->BaseDatos->Conexion->prepare($SQL);
			$Sentencia->bindValue(":codigo", $codigo);
			$Sentencia->execute();  //Ejecuta el borrado

			//Si logra ejecutar entonces crea los registros de competencias
			foreach ($competencias as $competencia) {
				$SQL = "INSERT INTO catedracompetencias (catedra, competencia) VALUES (:catedra, :competencia)";
				$Sentencia = $this->BaseDatos->Conexion->prepare($SQL);
				$Sentencia->bindValue(":catedra", $codigo);
				$Sentencia->bindValue(":competencia", $competencia);
				$Sentencia->execute();  //Ejecuta la adición
			}

			//Si logra ejecutar entonces crea los registros de resultados
			foreach ($resultados as $resultado) {
				$SQL = "INSERT INTO catedraresultados (catedra, resultado) VALUES (:catedra, :resultado)";
				$Sentencia = $this->BaseDatos->Conexion->prepare($SQL);
				$Sentencia->bindValue(":catedra", $codigo);
				$Sentencia->bindValue(":resultado", $resultado);
				$Sentencia->execute();  //Ejecuta la adición
			}

			return "Actualización de registro exitosa";
		}
		catch (Exception $e) {
			return "Falló al actualizar registro. Error: " . implode(" | ",$Sentencia->errorinfo());
		}
	}

	//Retorna para el detalle del registro
	public function DetalleCompetencias($Catedra){
		$SQL = "SELECT nombre FROM competencias
WHERE codigo IN (SELECT competencia FROM catedracompetencias WHERE catedra = :catedra) ORDER BY nombre";
		$Sentencia = $this->BaseDatos->Conexion->prepare($SQL);
		$Sentencia->bindValue(":catedra", $Catedra);
		$Sentencia->execute();  //Ejecuta la consulta
		$Registros = $Sentencia->fetchAll();

		$Datos = "";
		for ($Fila=0; $Fila < count($Registros); $Fila++)
			$Datos .= $Registros[$Fila][0] . "<br/>";
		return $Datos;
	}

	//Retorna para el detalle del registro
	public function DetalleResultados($Catedra){
		$SQL = "SELECT nombre FROM resultados
WHERE codigo IN (SELECT resultado FROM catedraresultados WHERE catedra = :catedra) ORDER BY nombre";
		$Sentencia = $this->BaseDatos->Conexion->prepare($SQL);
		$Sentencia->bindValue(":catedra", $Catedra);
		$Sentencia->execute();  //Ejecuta la consulta
		$Registros = $Sentencia->fetchAll();

		$Datos = "";
		for ($Fila=0; $Fila < count($Registros); $Fila++)
			$Datos .= $Registros[$Fila][0] . "<br/>";
		return $Datos;
	}

	//Retorna para la actualización del registro
	public function ActualizaResultados($catedra, $programa){
		$SQL = "SELECT codigo, nombre FROM resultados
WHERE codigo IN (SELECT resultado FROM catedraresultados WHERE catedra = :catedra) ORDER BY nombre";
		$Sentencia = $this->BaseDatos->Conexion->prepare($SQL);
		$Sentencia->bindValue(":catedra", $catedra);
		$Sentencia->execute();  //Ejecuta la consulta
		$Registros = $Sentencia->fetchAll();

		$Datos = "";
		for ($Fila=0; $Fila < count($Registros); $Fila++)
			$Datos .= "<input type='checkbox' class='form-check-input' value = '" . $Registros[$Fila][0] . "' name='resultados[]' checked='checked'>" . $Registros[$Fila][1] . "<br/>";

		$SQL = "SELECT codigo, nombre FROM resultados
WHERE codigo NOT IN (SELECT resultado FROM catedraresultados WHERE catedra = :catedra)
AND codigo IN (SELECT codigo FROM resultados WHERE programa = :programa) ORDER BY nombre";
		$Sentencia = $this->BaseDatos->Conexion->prepare($SQL);
		$Sentencia->bindValue(":catedra", $catedra);
		$Sentencia->bindValue(":programa", $programa);
		$Sentencia->execute();  //Ejecuta la consulta
		$Registros = $Sentencia->fetchAll();

		for ($Fila=0; $Fila < count($Registros); $Fila++)
			$Datos .= "<input type='checkbox' class='form-check-input' value = '" . $Registros[$Fila][0] . "' name='resultados[]'>" . $Registros[$Fila][1] . "<br/>";
		return $Datos;
	}

	//Retorna para la actualización del registro
	public function ActualizaCompetencias($catedra, $programa){
		$SQL = "SELECT codigo, nombre FROM competencias
WHERE codigo IN (SELECT competencia FROM catedracompetencias WHERE catedra = :catedra) ORDER BY nombre";
		$Sentencia = $this->BaseDatos->Conexion->prepare($SQL);
		$Sentencia->bindValue(":catedra", $catedra);
		$Sentencia->execute();  //Ejecuta la consulta
		$Registros = $Sentencia->fetchAll();

		$Datos = "";
		for ($Fila=0; $Fila < count($Registros); $Fila++)
			$Datos .= "<input type='checkbox' class='form-check-input' value = '" . $Registros[$Fila][0] . "' name='competencias[]' checked='checked'>" . $Registros[$Fila][1] . "<br/>";

		$SQL = "SELECT codigo, nombre FROM competencias
WHERE codigo NOT IN (SELECT competencia FROM catedracompetencias WHERE catedra = :catedra)
AND codigo IN (SELECT codigo FROM competencias WHERE programa = :programa) ORDER BY nombre";
		$Sentencia = $this->BaseDatos->Conexion->prepare($SQL);
		$Sentencia->bindValue(":catedra", $catedra);
		$Sentencia->bindValue(":programa", $programa);
		$Sentencia->execute();  //Ejecuta la consulta
		$Registros = $Sentencia->fetchAll();

		for ($Fila=0; $Fila < count($Registros); $Fila++)
			$Datos .= "<input type='checkbox' class='form-check-input' value = '" . $Registros[$Fila][0] . "' name='competencias[]'>" . $Registros[$Fila][1] . "<br/>";
		return $Datos;
	}

	//Configuración de los PATH
	public function registros() { return "../../vista/catedraseditadocente/registros.html"; }
	public function menuedita() { return "../../vista/catedraseditadocente/menuedita.html"; }
	public function rutaprog() { return "../../prog/catedraseditadocente/"; }
	public function rutavista() { return "../../vista/catedraseditadocente/"; }
	public function datosbasicos() { return "../../vista/catedraseditadocente/datosbasicos.html"; }
	public function descripcion() { return "../../vista/catedraseditadocente/descripcion.html"; }
	public function actualiza1() { return "../../vista/catedraseditadocente/actualiza1.html"; }
	public function actualiza2() { return "../../vista/catedraseditadocente/actualiza2.html"; }
}