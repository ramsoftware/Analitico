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

	//Registros para el grid
	public function DatosGrid($posicion, $docente)
	{
		if ($docente == -1) {
			$SQL = "SELECT catedras.codigo, catedras.codigouniversidad, catedras.nombre, programas.nombre, periodos.nombre 
FROM programas, periodos, catedras 
WHERE catedras.programa = programas.codigo 
AND catedras.periodo = periodos.codigo
ORDER BY catedras.nombre LIMIT $posicion, $this->Mostrar";
			$Sentencia = $this->BaseDatos->Conexion->prepare($SQL);
		}
		else {
			$SQL = "SELECT catedras.codigo, catedras.codigouniversidad, catedras.nombre, programas.nombre, periodos.nombre 
FROM programas, periodos, catedras 
WHERE catedras.programa = programas.codigo 
AND catedras.periodo = periodos.codigo
AND catedras.docente = :docente
ORDER BY catedras.nombre LIMIT $posicion, $this->Mostrar";
			$Sentencia = $this->BaseDatos->Conexion->prepare($SQL);
			$Sentencia->bindValue(":docente", $docente);
		}

		$Sentencia->execute();  //Ejecuta la consulta
		$Registros = $Sentencia->fetchAll();

		$Datos = "";
		for ($Fila=0; $Fila < count($Registros); $Fila++){
			$Datos .= "<tr>";
			$Datos .= "<td>" . htmlentities($Registros[$Fila][1], ENT_QUOTES, "UTF-8") . "</td>";
			$Datos .= "<td>" . htmlentities($Registros[$Fila][2], ENT_QUOTES, "UTF-8") . "</td>";
			$Datos .= "<td>" . htmlentities($Registros[$Fila][3], ENT_QUOTES, "UTF-8") . "</td>";
			$Datos .= "<td>" . htmlentities($Registros[$Fila][4], ENT_QUOTES, "UTF-8") . "</td>";
			if ($docente == -1) //Docente es -1 signfica cualquier docente
				$Datos .= '<td><a href=\'analitico.php?catedra=' . $Registros[$Fila][0] . '\' class=\'btn btn-primary\'>Detalle</a></td>';
			else
				$Datos .= '<td><a href=\'analitico.php?catedra=' . $Registros[$Fila][0] . '&docente=1\' class=\'btn btn-primary\'>Detalle</a></td>';
			$Datos .= '</tr>';
		}
		return $Datos;
	}

	//Retorna el registro de la tabla que se quiere ver en detalle
	public function TraeBasico($codigo){
		//La sentencia de consulta
		$SQL = "SELECT catedras.codigo, periodos.nombre, programas.nombre, areasconoce.nombre, ciclosformacion.nombre, componentes.nombre, 
catedras.nombre, catedras.codigouniversidad, catedras.semestre, nivelesformacion.nombre, catedras.horasdocente, catedras.horasindependiente, catedras.creditos, 
modalidades.nombre, tiposasignatura.nombre, catedras.descripcion, catedras.justificacion, catedras.metodologia, facultades.nombre  
FROM facultades, catedras, periodos, areasconoce, ciclosformacion, componentes, nivelesformacion,  modalidades, tiposasignatura, programas
WHERE periodos.codigo = catedras.periodo
AND programas.codigo = catedras.programa
AND facultades.codigo = programas.facultad
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

	public function TraeUnidades($catedra){
		$SQL = "SELECT unidades.codigo, unidades.titulo, unidades.temas FROM unidades WHERE unidades.catedra = :catedra ORDER BY unidades.titulo";
		$Sentencia = $this->BaseDatos->Conexion->prepare($SQL);
		$Sentencia->bindValue(":catedra", $catedra);
		$Sentencia->execute();
		$Registros = $Sentencia->fetchAll();

		$Datos = "";
		for ($Fila=0; $Fila < count($Registros); $Fila++){
			$Datos .= "<h4>Unidad " . htmlentities($Registros[$Fila][1], ENT_QUOTES, "UTF-8"). "</h4>";
			$Datos .= "<p>" . $Registros[$Fila][2] . "</p>";
		}
		return $Datos;
	}

	public function TraeCompetencias($catedra){
		$SQL = "SELECT nombre FROM competencias WHERE competencias.codigo IN (select competencia FROM catedracompetencias WHERE catedra = :catedra)";
		$Sentencia = $this->BaseDatos->Conexion->prepare($SQL);
		$Sentencia->bindValue(":catedra", $catedra);
		$Sentencia->execute();
		$Registros = $Sentencia->fetchAll();

		$Datos = "";
		for ($Fila=0; $Fila < count($Registros); $Fila++){
			$Datos .= "<tr><td>" . $Registros[$Fila][0] . "</td></tr>";
		}
		return $Datos;
	}

	public function TraeResultados($catedra){
		$SQL = "SELECT nombre FROM resultados WHERE resultados.codigo IN (select resultado FROM catedraresultados WHERE catedra = :catedra)";
		$Sentencia = $this->BaseDatos->Conexion->prepare($SQL);
		$Sentencia->bindValue(":catedra", $catedra);
		$Sentencia->execute();
		$Registros = $Sentencia->fetchAll();

		$Datos = "";
		for ($Fila=0; $Fila < count($Registros); $Fila++){
			$Datos .= "<tr><td>" . $Registros[$Fila][0] . "</td></tr>";
		}
		return $Datos;
	}

	public function TraeDocumentos($catedra){
		$SQL = "SELECT documentos FROM catedras WHERE codigo = :catedra";
		$Sentencia = $this->BaseDatos->Conexion->prepare($SQL);
		$Sentencia->bindValue(":catedra", $catedra);
		$Sentencia->execute();
		$Registros = $Sentencia->fetchAll();
		return $Registros[0][0];
	}

	public function TraeEvaluaciones($catedra){
		$SQL = "SELECT catedraevaluaciones.codigo, catedraevaluaciones.descripcion, momentos.nombre FROM catedraevaluaciones, momentos WHERE momentos.codigo = catedraevaluaciones.momento AND catedraevaluaciones.catedra = :catedra";

		$Sentencia = $this->BaseDatos->Conexion->prepare($SQL);
		$Sentencia->bindValue(":catedra", $catedra);
		$Sentencia->execute();
		$Registros = $Sentencia->fetchAll();

		$Datos = "";
		for ($Fila=0; $Fila < count($Registros); $Fila++){
			$Resultados = $this->DetalleResultados($Registros[$Fila][0]);
			$Unidades = $this->DetalleUnidades($Registros[$Fila][0]);
			$Estrategias = $this->DetalleEstrategias($Registros[$Fila][0]);

			$Datos .= "<tr>";
			$Datos .= "<td>" . htmlentities($Registros[$Fila][1], ENT_QUOTES, "UTF-8") . "</td>";
			$Datos .= "<td>" . $Unidades . "</td>";
			$Datos .= "<td>" . $Resultados . "</td>";
			$Datos .= "<td>" . $Estrategias . "</td>";
			$Datos .= "<td>" . htmlentities($Registros[$Fila][2], ENT_QUOTES, "UTF-8") . "</td>";
			$Datos .= '</tr>';
		}
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

	public function Programacion($catedra){
		$SQL = "SELECT nombre, presencial, presencialhoras, independiente, independientehoras, codigo FROM sesiones WHERE catedra=:catedra";
		$Sentencia = $this->BaseDatos->Conexion->prepare($SQL);
		$Sentencia->bindValue(":catedra", $catedra);
		$Sentencia->execute();
		$Registros = $Sentencia->fetchAll();

		$Datos = "";
		for ($Fila=0; $Fila < count($Registros); $Fila++){
			$Datos .= "<tr>";
			$Datos .= "<td>" . strval($Fila+1) . "</td>";
			$Datos .= "<td>" . htmlentities($Registros[$Fila][0], ENT_QUOTES, "UTF-8") . "</td>";
			$Datos .= "<td>" . $Registros[$Fila][1] . "</td>";
			$Datos .= "<td>" . htmlentities($Registros[$Fila][2], ENT_QUOTES, "UTF-8") . "</td>";
			$Datos .= "<td>" . $Registros[$Fila][3] . "</td>";
			$Datos .= "<td>" . htmlentities($Registros[$Fila][4], ENT_QUOTES, "UTF-8") . "</td>";

			//Trae unidades estudiadas
			$Datos .= '<td>';
			$SQL = "SELECT unidades.titulo FROM sesionesunidad, unidades WHERE sesionesunidad.unidad = unidades.codigo
						AND sesionesunidad.sesion = :sesion";
			$Sentencia = $this->BaseDatos->Conexion->prepare($SQL);
			$Sentencia->bindValue(":sesion", $Registros[$Fila][5]);
			$Sentencia->execute();
			$Registros2 = $Sentencia->fetchAll();
			for ($Fila2=0; $Fila2 < count($Registros2); $Fila2++){
				$Datos .= htmlentities($Registros2[$Fila2][0], ENT_QUOTES, "UTF-8") . "<br><br>";
			}
			$Datos .= '</td>';

			//Trae resultados de aprendizaje abordados
			$Datos .= '<td>';
			$SQL = "SELECT resultados.nombre FROM sesionesresultado, resultados WHERE sesionesresultado.resultado = resultados.codigo
						AND sesionesresultado.sesion = :sesion";
			$Sentencia = $this->BaseDatos->Conexion->prepare($SQL);
			$Sentencia->bindValue(":sesion", $Registros[$Fila][5]);
			$Sentencia->execute();
			$Registros2 = $Sentencia->fetchAll();
			for ($Fila2=0; $Fila2 < count($Registros2); $Fila2++){
				$Datos .= htmlentities($Registros2[$Fila2][0], ENT_QUOTES, "UTF-8") . "<br><br>";
			}
			$Datos .= '</td>';

			$Datos .= '</tr>';
		}
		return $Datos;
	}

	public function Docente($catedra){
		$SQL = "SELECT nombre, correo1, perfilacademico, experienciadocente, experienciaprofesional, experienciainvestigacion,
prodacademicaprof  FROM usuarios WHERE codigo = (SELECT docente FROM catedras WHERE codigo = :catedra)";
		$Sentencia = $this->BaseDatos->Conexion->prepare($SQL);
		$Sentencia->bindValue(":catedra", $catedra);
		$Sentencia->execute();
		return $Sentencia->fetch();
	}


	//Configuración de los PATH
	public function analitico() { return "../../vista/catedrasver/analitico.html"; }
	public function registros() { return "../../vista/catedrasver/registros.html"; }
	public function rutaprog() { return "../../prog/catedrasver/"; }
	public function rutavista() { return "../../vista/catedrasver/"; }
}