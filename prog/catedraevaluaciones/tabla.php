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
		$this->BaseDatos = new basedatos();
		$this->BaseDatos->Conectar();
	 }

	//Valida que la evaluación pertenezca al docente
	 public function EvaluacionAutorizada($evaluacion, $docente){
		$SQL = "SELECT COUNT(*) FROM catedras WHERE docente = :docente and editable = 1 AND codigo IN (SELECT catedra FROM catedraevaluaciones WHERE codigo = :evaluacion)";
		$Sentencia = $this->BaseDatos->Conexion->prepare($SQL);
		$Sentencia->bindValue(":evaluacion", $evaluacion);
		$Sentencia->bindValue(":docente", $docente);
		$Sentencia->execute();  //Ejecuta la consulta
		$Registros = $Sentencia->fetchAll();
		if ($Registros[0][0] > 0) return true;
		return false;
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
	public function DatosGrid($Catedra)
	{
		$SQL = "SELECT catedraevaluaciones.codigo, catedraevaluaciones.descripcion, momentos.nombre FROM catedraevaluaciones, momentos WHERE catedraevaluaciones.momento = momentos.codigo AND catedra = :catedra";
		$Sentencia = $this->BaseDatos->Conexion->prepare($SQL);
		$Sentencia->bindValue(":catedra", $Catedra);
		$Sentencia->execute();  //Ejecuta la consulta
		$Registros = $Sentencia->fetchAll();

		$Datos = "";
		for ($Fila=0; $Fila < count($Registros); $Fila++){
			$Datos .= "<tr>";
			$Datos .= "<td>" . htmlentities($Registros[$Fila][1], ENT_QUOTES, "UTF-8") . "</td>";
			$Datos .= "<td>" . htmlentities($Registros[$Fila][2], ENT_QUOTES, "UTF-8") . "</td>";
			$Datos .= '<td><a href=\'iniciar.php?evaluacion=' . $Registros[$Fila][0] . '&op=2 \' class=\'btn btn-primary\'>Detalles</a></td>';
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
	public function VerRegistro($evaluacion){
		$SQL = "SELECT catedras.codigo, catedras.nombre, periodos.nombre, catedraevaluaciones.descripcion,
momentos.nombre FROM catedras, periodos, catedraevaluaciones, momentos
WHERE catedras.codigo = catedraevaluaciones.catedra
AND momentos.codigo = catedraevaluaciones.momento
AND periodos.codigo = catedras.periodo
AND catedraevaluaciones.codigo = :codigo";

		//Conecta a la base de datos y hace la consulta
		$Sentencia = $this->BaseDatos->Conexion->prepare($SQL);
		$Sentencia->bindValue(":codigo", $evaluacion);
		$Sentencia->execute();  //Ejecuta la consulta
		return $Sentencia->fetch();
	}

	//Retorna el registro de la tabla para actualizar
	public function VerRegistroActualiza($codigo){
		$SQL = "SELECT catedras.codigo, catedras.nombre, periodos.nombre, catedraevaluaciones.descripcion,
catedraevaluaciones.momento FROM catedras, periodos, catedraevaluaciones
WHERE catedras.codigo = catedraevaluaciones.catedra
AND periodos.codigo = catedras.periodo
AND catedraevaluaciones.codigo = :codigo";

		//Conecta a la base de datos y hace la consulta
		$Sentencia = $this->BaseDatos->Conexion->prepare($SQL);
		$Sentencia->bindValue(":codigo", $codigo);
		$Sentencia->execute();  //Ejecuta la consulta
		return $Sentencia->fetch();
	}

	//Actualiza el registro
	public function Actualizar($evaluacion, $descripcion, $catedra, $momento, $unidades, $resultados, $estrategias){
		$SQL = "UPDATE catedraevaluaciones SET descripcion = :descripcion,  catedra = :catedra, momento = :momento WHERE codigo = :codigo";

		//Conecta a la base de datos y hace la actualización
		$Sentencia = $this->BaseDatos->Conexion->prepare($SQL);
		$Sentencia->bindValue(":descripcion", $descripcion);
		$Sentencia->bindValue(":catedra", $catedra);
		$Sentencia->bindValue(":momento", $momento);
		$Sentencia->bindValue(":codigo", $evaluacion);

		try{
			$Sentencia->execute();  //Ejecuta la actualización

			$SQL = "DELETE FROM evalresultados WHERE evaluacion = :codigo";
			$Sentencia = $this->BaseDatos->Conexion->prepare($SQL);
			$Sentencia->bindValue(":codigo", $evaluacion);
			$Sentencia->execute();  //Ejecuta el borrado

			$SQL = "DELETE FROM evalunidades WHERE evaluacion = :codigo";
			$Sentencia = $this->BaseDatos->Conexion->prepare($SQL);
			$Sentencia->bindValue(":codigo", $evaluacion);
			$Sentencia->execute();  //Ejecuta el borrado

			$SQL = "DELETE FROM evalestrategias WHERE evaluacion = :codigo";
			$Sentencia = $this->BaseDatos->Conexion->prepare($SQL);
			$Sentencia->bindValue(":codigo", $evaluacion);
			$Sentencia->execute();  //Ejecuta el borrado

			//Si logra ejecutar entonces crea los registros de unidades vistas
			foreach ($unidades as $unidad) {
				$SQL = "INSERT INTO evalunidades (evaluacion, unidad) VALUES (:evaluacion, :unidad)";
				$Sentencia = $this->BaseDatos->Conexion->prepare($SQL);
				$Sentencia->bindValue(":evaluacion", $evaluacion);
				$Sentencia->bindValue(":unidad", $unidad);
				$Sentencia->execute();  //Ejecuta la adición
			}

			//Si logra ejecutar entonces crea los registros de resultados de aprendizaje cubiertos
			foreach ($resultados as $resultado) {
				$SQL = "INSERT INTO evalresultados (evaluacion, resultado) VALUES (:evaluacion, :resultado)";
				$Sentencia = $this->BaseDatos->Conexion->prepare($SQL);
				$Sentencia->bindValue(":evaluacion", $evaluacion);
				$Sentencia->bindValue(":resultado", $resultado);
				$Sentencia->execute();  //Ejecuta la adición
			}

			//Si logra ejecutar entonces crea los registros de materiales de apoyo
			foreach ($estrategias as $estrategia) {
				$SQL = "INSERT INTO evalestrategias (evaluacion, estrategia) VALUES (:evaluacion, :estrategia)";
				$Sentencia = $this->BaseDatos->Conexion->prepare($SQL);
				$Sentencia->bindValue(":evaluacion", $evaluacion);
				$Sentencia->bindValue(":estrategia", $estrategia);
				$Sentencia->execute();  //Ejecuta la adición
			}

			return 1;
		}
		catch (Exception $excepcion) {
			return "Falló al actualizar registro. <br>Detalle: " . $excepcion->getMessage();
		}
	}

	//Adiciona el registro de temas de la cátedra
	public function Adicionar($descripcion, $catedra, $momento, $unidades, $resultados, $estrategias){
		$SQL = "INSERT INTO catedraevaluaciones(descripcion, catedra, momento)
		VALUES (:descripcion, :catedra, :momento)";

		//Conecta a la base de datos y hace la actualización
		$Sentencia = $this->BaseDatos->Conexion->prepare($SQL);
		$Sentencia->bindValue(":descripcion", $descripcion);
		$Sentencia->bindValue(":catedra", $catedra);
		$Sentencia->bindValue(":momento", $momento);

		try{
			$Sentencia->execute();  //Ejecuta la adición
			$evaluacion = $this->BaseDatos->Conexion->lastInsertId();

			//Si logra ejecutar entonces crea los registros de unidades vistas
			foreach ($unidades as $unidad) {
				$SQL = "INSERT INTO evalunidades (evaluacion, unidad) VALUES (:evaluacion, :unidad)";
				$Sentencia = $this->BaseDatos->Conexion->prepare($SQL);
				$Sentencia->bindValue(":evaluacion", $evaluacion);
				$Sentencia->bindValue(":unidad", $unidad);
				$Sentencia->execute();  //Ejecuta la adición
			}

			//Si logra ejecutar entonces crea los registros de resultados de aprendizaje cubiertos
			foreach ($resultados as $resultado) {
				$SQL = "INSERT INTO evalresultados (evaluacion, resultado) VALUES (:evaluacion, :resultado)";
				$Sentencia = $this->BaseDatos->Conexion->prepare($SQL);
				$Sentencia->bindValue(":evaluacion", $evaluacion);
				$Sentencia->bindValue(":resultado", $resultado);
				$Sentencia->execute();  //Ejecuta la adición
			}

			//Si logra ejecutar entonces crea los registros de materiales de apoyo
			foreach ($estrategias as $estrategia) {
				$SQL = "INSERT INTO evalestrategias (evaluacion, estrategia) VALUES (:evaluacion, :estrategia)";
				$Sentencia = $this->BaseDatos->Conexion->prepare($SQL);
				$Sentencia->bindValue(":evaluacion", $evaluacion);
				$Sentencia->bindValue(":estrategia", $estrategia);
				$Sentencia->execute();  //Ejecuta la adición
			}

			return 1;
		}
		catch (Exception $e) {
			return "Falló al adicionar registro. Error: " . implode(" | ",$Sentencia->errorinfo());
		}
	}

	//Actualiza el registro
	public function Borrar($codigo)
	{
		try{
			$SQL = "DELETE FROM evalresultados WHERE evaluacion = :codigo";
			$Sentencia = $this->BaseDatos->Conexion->prepare($SQL);
			$Sentencia->bindValue(":codigo", $codigo);
			$Sentencia->execute();  //Ejecuta el borrado

			$SQL = "DELETE FROM evalunidades WHERE evaluacion = :codigo";
			$Sentencia = $this->BaseDatos->Conexion->prepare($SQL);
			$Sentencia->bindValue(":codigo", $codigo);
			$Sentencia->execute();  //Ejecuta el borrado

			$SQL = "DELETE FROM evalestrategias WHERE evaluacion = :codigo";
			$Sentencia = $this->BaseDatos->Conexion->prepare($SQL);
			$Sentencia->bindValue(":codigo", $codigo);
			$Sentencia->execute();  //Ejecuta el borrado

			$SQL = "DELETE FROM catedraevaluaciones WHERE codigo = :codigo";
			$Sentencia = $this->BaseDatos->Conexion->prepare($SQL);
			$Sentencia->bindValue(":codigo", $codigo);
			$Sentencia->execute();  //Ejecuta el borrado

			return 1;
		}
		catch (Exception $excepcion) {
			return "Falló al borrar registro. <br>Detalle: " . $excepcion->getMessage();
		}
	}

	//Retorna la cátedra dada la evaluacón
	public function CodigoCatedra($evaluacion){
		$SQL = "SELECT catedra FROM catedraevaluaciones WHERE codigo = :evaluacion";

		//Conecta a la base de datos y hace la consulta
		$Sentencia = $this->BaseDatos->Conexion->prepare($SQL);
		$Sentencia->bindValue(":evaluacion", $evaluacion);
		$Sentencia->execute();  //Ejecuta la consulta
		$Registro = $Sentencia->fetch();
		return $Registro[0];
	}


	public function UnidadesCatedra($Catedra){
		$SQL = "SELECT codigo, titulo FROM unidades WHERE catedra = :catedra ORDER BY titulo";
		$Sentencia = $this->BaseDatos->Conexion->prepare($SQL);
		$Sentencia->bindValue(":catedra", $Catedra);
		$Sentencia->execute();  //Ejecuta la consulta
		$Registros = $Sentencia->fetchAll();

		$Datos = "";
		for ($Fila=0; $Fila < count($Registros); $Fila++)
			$Datos .= "<input type='checkbox' class='form-check-input' value = '" . $Registros[$Fila][0] . "' name='unidades[]'>" . $Registros[$Fila][1] . "<br/>";
		return $Datos;
	}

	//Retorna para el detalle del registro
	public function DetalleUnidades($evaluacion){
		$SQL = "SELECT titulo FROM unidades
WHERE codigo IN (SELECT unidad FROM evalunidades WHERE evaluacion = :evaluacion) ORDER BY titulo";
		$Sentencia = $this->BaseDatos->Conexion->prepare($SQL);
		$Sentencia->bindValue(":evaluacion", $evaluacion);
		$Sentencia->execute();  //Ejecuta la consulta
		$Registros = $Sentencia->fetchAll();

		$Datos = "";
		for ($Fila=0; $Fila < count($Registros); $Fila++)
			$Datos .= $Registros[$Fila][0] . "<br/>";
		return $Datos;
	}

	//Retorna para la actualización del registro
	public function ActualizaUnidades($evaluacion){
		$SQL = "SELECT codigo, titulo FROM unidades
WHERE codigo IN (SELECT unidad FROM evalunidades WHERE evaluacion = :evaluacion) ORDER BY titulo";
		$Sentencia = $this->BaseDatos->Conexion->prepare($SQL);
		$Sentencia->bindValue(":evaluacion", $evaluacion);
		$Sentencia->execute();  //Ejecuta la consulta
		$Registros = $Sentencia->fetchAll();

		$Datos = "";
		for ($Fila=0; $Fila < count($Registros); $Fila++)
			$Datos .= "<input type='checkbox' class='form-check-input' value = '" . $Registros[$Fila][0] . "' name='unidades[]' checked='checked'>" . $Registros[$Fila][1] . "<br/>";

		$SQL = "SELECT codigo, titulo FROM unidades
WHERE codigo NOT IN (SELECT unidad FROM evalunidades WHERE evaluacion = :evaluacion) ORDER BY titulo";
		$Sentencia = $this->BaseDatos->Conexion->prepare($SQL);
		$Sentencia->bindValue(":evaluacion", $evaluacion);
		$Sentencia->execute();  //Ejecuta la consulta
		$Registros = $Sentencia->fetchAll();

		for ($Fila=0; $Fila < count($Registros); $Fila++)
			$Datos .= "<input type='checkbox' class='form-check-input' value = '" . $Registros[$Fila][0] . "' name='unidades[]'>" . $Registros[$Fila][1] . "<br/>";
		return $Datos;
	}


	public function Resultados($Catedra){
		$SQL = "SELECT catedraresultados.resultado, resultados.nombre FROM catedraresultados, resultados
WHERE catedraresultados.catedra = :codigo AND resultados.codigo = catedraresultados.resultado";
		//$SQL = "SELECT codigo, nombre FROM resultados WHERE programa IN (SELECT programa FROM catedras WHERE codigo = :codigo)";
		$Sentencia = $this->BaseDatos->Conexion->prepare($SQL);
		$Sentencia->bindValue(":codigo", $Catedra);
		$Sentencia->execute();  //Ejecuta la consulta
		$Registros = $Sentencia->fetchAll();

		$Datos = "";
		for ($Fila=0; $Fila < count($Registros); $Fila++)
			$Datos .= "<input type='checkbox' class='form-check-input' value = '" . $Registros[$Fila][0] . "' name='resultados[]'>" . $Registros[$Fila][1] . "<br/>";
		return $Datos;
	}

	//Retorna para el detalle del registro
	public function DetalleResultados($evaluacion){
		$SQL = "SELECT nombre FROM resultados
WHERE codigo IN (SELECT resultado FROM evalresultados WHERE evaluacion = :evaluacion) ORDER BY nombre";
		$Sentencia = $this->BaseDatos->Conexion->prepare($SQL);
		$Sentencia->bindValue(":evaluacion", $evaluacion);
		$Sentencia->execute();  //Ejecuta la consulta
		$Registros = $Sentencia->fetchAll();

		$Datos = "";
		for ($Fila=0; $Fila < count($Registros); $Fila++)
			$Datos .= $Registros[$Fila][0] . "<br/>";
		return $Datos;
	}

	//Retorna para la actualización del registro
	public function ActualizaResultados($evaluacion){
		$SQL = "SELECT codigo, nombre FROM resultados WHERE codigo IN (SELECT resultado FROM evalresultados WHERE evaluacion = :evaluacion) ORDER BY nombre";
		$Sentencia = $this->BaseDatos->Conexion->prepare($SQL);
		$Sentencia->bindValue(":evaluacion", $evaluacion);
		$Sentencia->execute();  //Ejecuta la consulta
		$Registros = $Sentencia->fetchAll();

		$Datos = "";
		for ($Fila=0; $Fila < count($Registros); $Fila++)
			$Datos .= "<input type='checkbox' class='form-check-input' value = '" . $Registros[$Fila][0] . "' name='resultados[]' checked='checked'>" . $Registros[$Fila][1] . "<br/>";

		$SQL = "SELECT catedraresultados.resultado, resultados.nombre FROM catedraresultados, resultados
WHERE resultados.codigo = catedraresultados.resultado AND catedra IN (SELECT catedra FROM catedraevaluaciones WHERE codigo = :evaluacion)
AND resultado NOT IN (SELECT resultado FROM evalresultados WHERE evaluacion = :evaluacion) ORDER BY resultados.nombre";

		$Sentencia = $this->BaseDatos->Conexion->prepare($SQL);
		$Sentencia->bindValue(":evaluacion", $evaluacion);
		$Sentencia->execute();  //Ejecuta la consulta
		$Registros = $Sentencia->fetchAll();

		for ($Fila=0; $Fila < count($Registros); $Fila++)
			$Datos .= "<input type='checkbox' class='form-check-input' value = '" . $Registros[$Fila][0] . "' name='resultados[]'>" . $Registros[$Fila][1] . "<br/>";
		return $Datos;
	}

	public function Estrategias(){
		$SQL = "SELECT codigo, nombre FROM estrategias ORDER BY nombre";
		$Sentencia = $this->BaseDatos->Conexion->prepare($SQL);
		$Sentencia->execute();  //Ejecuta la consulta
		$Registros = $Sentencia->fetchAll();

		$Datos = "";
		for ($Fila=0; $Fila < count($Registros); $Fila++)
			$Datos .= "<input type='checkbox' class='form-check-input' value = '" . $Registros[$Fila][0] . "' name='estrategias[]'>" . $Registros[$Fila][1] . "<br/>";
		return $Datos;
	}

	//Retorna para el detalle del registro
	public function DetalleEstrategias($evaluacion){
		$SQL = "SELECT nombre FROM estrategias
WHERE codigo IN (SELECT estrategia FROM evalestrategias WHERE evaluacion = :evaluacion) ORDER BY nombre";
		$Sentencia = $this->BaseDatos->Conexion->prepare($SQL);
		$Sentencia->bindValue(":evaluacion", $evaluacion);
		$Sentencia->execute();  //Ejecuta la consulta
		$Registros = $Sentencia->fetchAll();

		$Datos = "";
		for ($Fila=0; $Fila < count($Registros); $Fila++)
			$Datos .= $Registros[$Fila][0] . "<br/>";
		return $Datos;
	}

	//Retorna para la actualización del registro
	public function ActualizaEstrategias($evaluacion){
		$SQL = "SELECT codigo, nombre FROM estrategias WHERE codigo IN (SELECT estrategia FROM evalestrategias WHERE evaluacion = :evaluacion) ORDER BY nombre";
		$Sentencia = $this->BaseDatos->Conexion->prepare($SQL);
		$Sentencia->bindValue(":evaluacion", $evaluacion);
		$Sentencia->execute();  //Ejecuta la consulta
		$Registros = $Sentencia->fetchAll();

		$Datos = "";
		for ($Fila=0; $Fila < count($Registros); $Fila++)
			$Datos .= "<input type='checkbox' class='form-check-input' value = '" . $Registros[$Fila][0] . "' name='estrategias[]' checked='checked'>" . $Registros[$Fila][1] . "<br/>";

		$SQL = "SELECT codigo, nombre FROM estrategias WHERE codigo NOT IN (SELECT estrategia FROM evalestrategias WHERE evaluacion = :evaluacion) ORDER BY nombre";
		$Sentencia = $this->BaseDatos->Conexion->prepare($SQL);
		$Sentencia->bindValue(":evaluacion", $evaluacion);
		$Sentencia->execute();  //Ejecuta la consulta
		$Registros = $Sentencia->fetchAll();

		for ($Fila=0; $Fila < count($Registros); $Fila++)
			$Datos .= "<input type='checkbox' class='form-check-input' value = '" . $Registros[$Fila][0] . "' name='estrategias[]'>" . $Registros[$Fila][1] . "<br/>";
		return $Datos;
	}

	//Para crear el combobox de momentos de conocimiento de documentos
	public function ComboBoxMomento($Codigo){
		$SQL = "SELECT nombre FROM momentos WHERE codigo = :Codigo";

		//Hace la consulta
		$Sentencia = $this->BaseDatos->Conexion->prepare($SQL);
		$Sentencia->bindValue(":Codigo", $Codigo);
		$Sentencia->execute();
		$Lista = $Sentencia->fetch();
		return '<option value="'. $Codigo .'" selected="selected">'. $Lista[0] . '</option>';
	}

	//Configuración de los PATH
	public function actualiza1() { return "../../vista/catedraevaluaciones/actualiza1.html"; }
	public function actualiza2() { return "../../vista/catedraevaluaciones/actualiza2.html"; }
	public function adiciona1() { return "../../vista/catedraevaluaciones/adiciona1.html"; }
	public function adiciona2() { return "../../vista/catedraevaluaciones/adiciona2.html"; }
	public function borra1() { return "../../vista/catedraevaluaciones/borra1.html"; }
	public function borra2() { return "../../vista/catedraevaluaciones/borra2.html"; }
	public function detalle() { return "../../vista/catedraevaluaciones/detalle.html"; }
	public function registros() { return "../../vista/catedraevaluaciones/registros.html"; }
	public function rutaprog() { return "../../prog/catedraevaluaciones/"; }
	public function rutavista() { return "../../vista/catedraevaluaciones/"; }
}