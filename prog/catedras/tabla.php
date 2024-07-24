<?php
//Autor: Rafael Alberto Moreno Parra. https://github.com/ramsoftware
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
		$this->BaseDatos = new basedatos();
		$this->BaseDatos->Conectar();
		$this->Mostrar = $this->BaseDatos->RegistrosMostrarGRID();
	}

	//Crear el SQL de consulta para búsqueda
	public function Busqueda($Periodo, $Programa, $Areaconocimiento, $Cicloformacion, $Componenteformacion, $Nombre, $Codigouniversidad, $Semestre, $Nivelformacion, $Horasdocente, $Horasindependiente, $Creditos, $Modalidad, $Tipo, $Docente, $Posicion){
		
		//Crea el SQL (teniendo en cuenta la búsqueda)
		$SQL = "SELECT catedras.codigo, periodos.nombre, programas.nombre, areasconoce.nombre, ciclosformacion.nombre, componentes.nombre, 
catedras.nombre, catedras.codigouniversidad, catedras.semestre, nivelesformacion.nombre, catedras.horasdocente, catedras.horasindependiente, catedras.creditos, 
modalidades.nombre, tiposasignatura.nombre, usuarios.nombre 
FROM catedras, periodos, areasconoce, ciclosformacion, componentes, nivelesformacion, modalidades, tiposasignatura, programas, usuarios
WHERE periodos.codigo = catedras.periodo
		AND programas.codigo = catedras.programa
		AND areasconoce.codigo = catedras.areaconocimiento
		AND ciclosformacion.codigo = catedras.cicloformacion
		AND componentes.codigo = catedras.componenteformacion
		AND nivelesformacion.codigo = catedras.nivelformacion
		AND modalidades.codigo = catedras.modalidad
		AND usuarios.codigo = catedras.docente
		AND tiposasignatura.codigo = catedras.tipo ";

		//Crea el filtro de búsqueda
		$Filtro = "";

		$BPeriodo = false;
		if ($Periodo != ""){
			$Filtro .= "catedras.periodo = :Periodo AND ";
			$BPeriodo = true;
		}

		$BPrograma = false;
		if ($Programa != ""){
			$Filtro .= "catedras.programa = :Programa AND ";
			$BPrograma = true;
		}

		$BAreaconocimiento = false;
		if ($Areaconocimiento != ""){
			$Filtro .= "catedras.areaconocimiento = :Areaconocimiento AND ";
			$BAreaconocimiento = true;
		}

		$BNombre = false;
		if ($Nombre != ""){
			$Filtro .= "catedras.nombre LIKE :Nombre AND ";
			$BNombre = true;
		}

		$BCicloformacion = false;
		if ($Cicloformacion != ""){
			$Filtro .= "catedras.cicloformacion = :Cicloformacion AND ";
			$BCicloformacion = true;
		}

		$BComponenteformacion = false;
		if ($Componenteformacion != ""){
			$Filtro .= "catedras.componenteformacion = :Componenteformacion AND ";
			$BComponenteformacion = true;
		}

		$BCodigouniversidad = false;
		if ($Codigouniversidad != ""){
			$Filtro .= "catedras.codigouniversidad = :Codigouniversidad AND ";
			$BCodigouniversidad = true;
		}

		$BSemestre = false;
		if ($Semestre != ""){
			$Filtro .= "catedras.semestre = :Semestre AND ";
			$BSemestre = true;
		}

		$BNivelformacion = false;
		if ($Nivelformacion != ""){
			$Filtro .= "catedras.nivelformacion = :Nivelformacion AND ";
			$BNivelformacion = true;
		}

		$BHorasdocente = false;
		if ($Horasdocente != ""){
			$Filtro .= "catedras.horasdocente = :Horasdocente AND ";
			$BHorasdocente = true;
		}

		$BHorasindependiente = false;
		if ($Horasindependiente != ""){
			$Filtro .= "catedras.horasindependiente = :Horasindependiente AND ";
			$BHorasindependiente = true;
		}

		$BCreditos = false;
		if ($Creditos != ""){
			$Filtro .= "catedras.Creditos = :Creditos AND ";
			$BCreditos = true;
		}

		$BModalidad = false;
		if ($Modalidad != ""){
			$Filtro .= "catedras.modalidad = :Modalidad AND ";
			$BModalidad = true;
		}

		$BTipo = false;
		if ($Tipo != ""){
			$Filtro .= "catedras.Tipo = :Tipo AND ";
			$BTipo = true;
		}

		$BDocente = false;
		if ($Docente != ""){
			$Filtro .= "catedras.docente = :Docente AND ";
			$BDocente = true;
		}

		if (strlen($Filtro)>0) $Filtro = "AND " . substr($Filtro, 0, strlen($Filtro)-4);
		$SQL .= $Filtro . " LIMIT $Posicion, 1";
		$Sentencia = $this->BaseDatos->Conexion->prepare($SQL);

		//Agrega los valores
		if ($BPeriodo) $Sentencia->bindValue(':Periodo', $Periodo);
		if ($BPrograma) $Sentencia->bindValue(':Programa', $Programa);
		if ($BAreaconocimiento) $Sentencia->bindValue(':Areaconocimiento', $Areaconocimiento);
		if ($BCicloformacion) $Sentencia->bindValue(':Cicloformacion', $Cicloformacion);
		if ($BComponenteformacion) $Sentencia->bindValue(':Componenteformacion', $Componenteformacion);
		if ($BNombre) $Sentencia->bindValue(':Nombre', '%'.$Nombre.'%');
		if ($BCodigouniversidad) $Sentencia->bindValue(':Codigouniversidad', $Codigouniversidad);
		if ($BSemestre) $Sentencia->bindValue(':Semestre', $Semestre);
		if ($BNivelformacion) $Sentencia->bindValue(':Nivelformacion', $Nivelformacion);
		if ($BHorasdocente) $Sentencia->bindValue(':Horasdocente', $Horasdocente);
		if ($BHorasindependiente) $Sentencia->bindValue(':Horasindependiente', $Horasindependiente);
		if ($BCreditos) $Sentencia->bindValue(':Creditos', $Creditos);
		if ($BModalidad) $Sentencia->bindValue(':Modalidad', $Modalidad);
		if ($BTipo) $Sentencia->bindValue(':Tipo', $Tipo);
		if ($BDocente) $Sentencia->bindValue(':Docente', $Docente);

		$Sentencia->execute();  //Ejecuta la consulta

		//$Sentencia->debugDumpParams();
		return $Sentencia->fetch();
	}

	//Para crear el combobox de Períodos
	public function ComboBoxPeriodo($Codigo){
		$SQL = "SELECT nombre FROM periodos WHERE codigo = :Codigo";

		//Hace la consulta
		$Sentencia = $this->BaseDatos->Conexion->prepare($SQL);
		$Sentencia->bindValue(":Codigo", $Codigo);
		$Sentencia->execute();
		$Lista = $Sentencia->fetch();
		return '<option value="'. $Codigo .'" selected="selected">'. $Lista[0] . '</option>';
	}

	//Para crear el combobox de Áreas de Conocimiento
	public function ComboBoxAreaConocimiento($Codigo){
		$SQL = "SELECT nombre FROM areasconoce WHERE codigo = :Codigo";

		//Hace la consulta
		$Sentencia = $this->BaseDatos->Conexion->prepare($SQL);
		$Sentencia->bindValue(":Codigo", $Codigo);
		$Sentencia->execute();
		$Lista = $Sentencia->fetch();
		return '<option value="'. $Codigo .'" selected="selected">'. $Lista[0] . '</option>';
	}

	//Para crear el combobox de Ciclos de formación
	public function ComboBoxCicloFormacion($Codigo){
		$SQL = "SELECT nombre FROM ciclosformacion WHERE codigo = :Codigo";

		//Hace la consulta
		$Sentencia = $this->BaseDatos->Conexion->prepare($SQL);
		$Sentencia->bindValue(":Codigo", $Codigo);
		$Sentencia->execute();
		$Lista = $Sentencia->fetch();
		return '<option value="'. $Codigo .'" selected="selected">'. $Lista[0] . '</option>';
	}

	//Para crear el combobox de Componentes de formación
	public function ComboBoxComponenteFormacion($Codigo){
		$SQL = "SELECT nombre FROM componentes WHERE codigo = :Codigo";

		//Hace la consulta
		$Sentencia = $this->BaseDatos->Conexion->prepare($SQL);
		$Sentencia->bindValue(":Codigo", $Codigo);
		$Sentencia->execute();
		$Lista = $Sentencia->fetch();
		return '<option value="'. $Codigo .'" selected="selected">'. $Lista[0] . '</option>';
	}

	//Para crear el combobox de niveles de formación
	public function ComboBoxNivelFormacion($Codigo){
		$SQL = "SELECT nombre FROM nivelesformacion WHERE codigo = :Codigo";

		//Hace la consulta
		$Sentencia = $this->BaseDatos->Conexion->prepare($SQL);
		$Sentencia->bindValue(":Codigo", $Codigo);
		$Sentencia->execute();
		$Lista = $Sentencia->fetch();
		return '<option value="'. $Codigo .'" selected="selected">'. $Lista[0] . '</option>';
	}

	//Para crear el combobox de Modalidad
	public function ComboBoxModalidad($Codigo){
		$SQL = "SELECT nombre FROM modalidades WHERE codigo = :Codigo";

		//Hace la consulta
		$Sentencia = $this->BaseDatos->Conexion->prepare($SQL);
		$Sentencia->bindValue(":Codigo", $Codigo);
		$Sentencia->execute();
		$Lista = $Sentencia->fetch();
		return '<option value="'. $Codigo .'" selected="selected">'. $Lista[0] . '</option>';
	}

	//Para crear el combobox de tipo de cátedra
	public function ComboBoxTipo($Codigo){
		$SQL = "SELECT nombre FROM tiposasignatura WHERE codigo = :Codigo";

		//Hace la consulta
		$Sentencia = $this->BaseDatos->Conexion->prepare($SQL);
		$Sentencia->bindValue(":Codigo", $Codigo);
		$Sentencia->execute();
		$Lista = $Sentencia->fetch();
		return '<option value="'. $Codigo .'" selected="selected">'. $Lista[0] . '</option>';
	}

	//Para crear el combobox de docente
	public function ComboBoxDocente($Codigo){
		$SQL = "SELECT nombre FROM usuarios WHERE codigo = :Codigo";

		//Hace la consulta
		$Sentencia = $this->BaseDatos->Conexion->prepare($SQL);
		$Sentencia->bindValue(":Codigo", $Codigo);
		$Sentencia->execute();
		$Lista = $Sentencia->fetch();
		return '<option value="'. $Codigo .'" selected="selected">'. $Lista[0] . '</option>';
	}

	//Para crear el combobox de docente
	public function ComboBoxEditable($Codigo){
		if ($Codigo == 1)
			return '<option value=0 >Sólo lectura</option><option value=1 selected="selected">El docente puede cambiar introducción, justificación, temario</option>';
		else
			return '<option value=0 selected="selected">Sólo lectura</option><option value=1 >El docente puede cambiar introducción, justificación, temario</option>';
	}

	//Retorna el registro de la tabla que se quiere ver en detalle
	public function VerRegistroDetalle($codigo){
		//La sentencia de consulta
		$SQL = "SELECT catedras.codigo, periodos.nombre, areasconoce.nombre, ciclosformacion.nombre, componentes.nombre, 
catedras.nombre, catedras.codigouniversidad, catedras.semestre, nivelesformacion.nombre, catedras.horasdocente, catedras.horasindependiente, catedras.creditos, 
modalidades.nombre, tiposasignatura.nombre, usuarios.nombre, catedras.editable 
FROM catedras, periodos, areasconoce, ciclosformacion, componentes, nivelesformacion,  modalidades, tiposasignatura, usuarios
WHERE periodos.codigo = catedras.periodo
AND areasconoce.codigo = catedras.areaconocimiento
AND ciclosformacion.codigo = catedras.cicloformacion
AND componentes.codigo = catedras.componenteformacion
AND nivelesformacion.codigo = catedras.nivelformacion
AND modalidades.codigo = catedras.modalidad
AND usuarios.codigo = catedras.docente  
AND tiposasignatura.codigo = catedras.tipo AND catedras.codigo = :codigo";
		
		//Conecta a la base de datos y hace la consulta
		$Sentencia = $this->BaseDatos->Conexion->prepare($SQL);
		$Sentencia->bindValue(":codigo", $codigo);
		$Sentencia->execute();  //Ejecuta la consulta
		return $Sentencia->fetch();
	}

	//Retorna el registro de la tabla que se quiere actualizar
	public function VerRegistroActualiza($codigo){
		//La sentencia de consulta
		$SQL = "SELECT codigo, periodo, areaconocimiento, 
					cicloformacion, componenteformacion, nombre, 
					codigouniversidad, semestre, nivelformacion, 
					horasdocente, horasindependiente, creditos, 
					modalidad, tipo, docente, editable FROM catedras WHERE codigo = :codigo";

		//Conecta a la base de datos y hace la consulta
		$Sentencia = $this->BaseDatos->Conexion->prepare($SQL);
		$Sentencia->bindValue(":codigo", $codigo);
		$Sentencia->execute();  //Ejecuta la consulta
		return $Sentencia->fetch();
	}
	

	//Actualiza el registro
	public function Actualizar($codigo, $periodo, $areaconocimiento, $cicloformacion, $componenteformacion, $nombre, $codigouniversidad, $semestre, $nivelformacion, $horasdocente, $horasindependiente, $creditos, $modalidad, $tipo, $docente, $editar, $competencias, $resultados){
		$SQL = "UPDATE catedras SET periodo = :periodo, areaconocimiento = :areaconocimiento, 
					cicloformacion = :cicloformacion, componenteformacion = :componenteformacion, nombre = :nombre, 
					codigouniversidad = :codigouniversidad, semestre = :semestre, nivelformacion = :nivelformacion, 
					horasdocente = :horasdocente, horasindependiente = :horasindependiente, creditos = :creditos, 
					modalidad = :modalidad, tipo = :tipo, docente = :docente, editable = :editar WHERE codigo = :codigo";

		//Conecta a la base de datos y hace la actualización
		$Sentencia = $this->BaseDatos->Conexion->prepare($SQL);
		$Sentencia->bindValue(":codigo", $codigo);
		$Sentencia->bindValue(":periodo", $periodo);
		$Sentencia->bindValue(":areaconocimiento", $areaconocimiento);
		$Sentencia->bindValue(":cicloformacion", $cicloformacion);
		$Sentencia->bindValue(":componenteformacion", $componenteformacion);
		$Sentencia->bindValue(":nombre", $nombre);
		$Sentencia->bindValue(":codigouniversidad", $codigouniversidad);
		$Sentencia->bindValue(":semestre", $semestre);
		$Sentencia->bindValue(":nivelformacion", $nivelformacion);
		$Sentencia->bindValue(":horasdocente", $horasdocente);
		$Sentencia->bindValue(":horasindependiente", $horasindependiente);
		$Sentencia->bindValue(":creditos", $creditos);
		$Sentencia->bindValue(":modalidad", $modalidad);
		$Sentencia->bindValue(":tipo", $tipo);
		$Sentencia->bindValue(":docente", $docente);
		$Sentencia->bindValue(":editar", $editar);

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
	
	//Adiciona el registro
	public function Adicionar($periodo, $programa, $areaconocimiento, $cicloformacion, $componenteformacion, $nombre, $codigouniversidad, $semestre, $nivelformacion, $horasdocente, $horasindependiente, $creditos, $modalidad, $tipo, $docente, $editar, $competencias, $resultados){
		$SQL = "INSERT INTO catedras (periodo, programa, areaconocimiento, cicloformacion, componenteformacion, nombre, codigouniversidad, semestre, nivelformacion, horasdocente, horasindependiente, creditos, modalidad, tipo, docente, editable) VALUES(:periodo, :programa, :areaconocimiento, :cicloformacion, :componenteformacion, :nombre, :codigouniversidad, :semestre, :nivelformacion, :horasdocente, :horasindependiente, :creditos, :modalidad, :tipo, :docente, :editar)";

		//Conecta a la base de datos y hace la adición
		$Sentencia = $this->BaseDatos->Conexion->prepare($SQL);
		$Sentencia->bindValue(":periodo", $periodo);
		$Sentencia->bindValue(":programa", $programa);
		$Sentencia->bindValue(":areaconocimiento", $areaconocimiento);
		$Sentencia->bindValue(":cicloformacion", $cicloformacion);
		$Sentencia->bindValue(":componenteformacion", $componenteformacion);
		$Sentencia->bindValue(":nombre", $nombre);
		$Sentencia->bindValue(":codigouniversidad", $codigouniversidad);
		$Sentencia->bindValue(":semestre", $semestre);
		$Sentencia->bindValue(":nivelformacion", $nivelformacion);
		$Sentencia->bindValue(":horasdocente", $horasdocente);
		$Sentencia->bindValue(":horasindependiente", $horasindependiente);
		$Sentencia->bindValue(":creditos", $creditos);
		$Sentencia->bindValue(":modalidad", $modalidad);
		$Sentencia->bindValue(":tipo", $tipo);
		$Sentencia->bindValue(":docente", $docente);
		$Sentencia->bindValue(":editar", $editar);

		try{
			$Sentencia->execute();  //Ejecuta la adición
			$catedra = $this->BaseDatos->Conexion->lastInsertId();

			//Si logra ejecutar entonces crea los registros de competencias
			foreach ($competencias as $competencia) {
				$SQL = "INSERT INTO catedracompetencias (catedra, competencia) VALUES (:catedra, :competencia)";
				$Sentencia = $this->BaseDatos->Conexion->prepare($SQL);
				$Sentencia->bindValue(":catedra", $catedra);
				$Sentencia->bindValue(":competencia", $competencia);
				$Sentencia->execute();  //Ejecuta la adición
			}

			//Si logra ejecutar entonces crea los registros de resultados
			foreach ($resultados as $resultado) {
				$SQL = "INSERT INTO catedraresultados (catedra, resultado) VALUES (:catedra, :resultado)";
				$Sentencia = $this->BaseDatos->Conexion->prepare($SQL);
				$Sentencia->bindValue(":catedra", $catedra);
				$Sentencia->bindValue(":resultado", $resultado);
				$Sentencia->execute();  //Ejecuta la adición
			}
			return "Adición de registro exitosa";
		}
		catch (Exception $e) {
			return "Falló al adicionar registro. Error: " . implode(" | ",$Sentencia->errorinfo());
		}
	}
	
	//Borra el registro
	public function Borrar($codigo){
		try {
			$SQL = "DELETE FROM catedracompetencias WHERE catedra = :codigo";
			$Sentencia = $this->BaseDatos->Conexion->prepare($SQL);
			$Sentencia->bindValue(":codigo", $codigo);
			$Sentencia->execute();  //Ejecuta el borrado

			$SQL = "DELETE FROM catedraresultados WHERE catedra = :codigo";
			$Sentencia = $this->BaseDatos->Conexion->prepare($SQL);
			$Sentencia->bindValue(":codigo", $codigo);
			$Sentencia->execute();  //Ejecuta el borrado

			//Conecta a la base de datos y hace la actualización
			$SQL = "DELETE FROM catedras WHERE codigo = :codigo";
			$Sentencia = $this->BaseDatos->Conexion->prepare($SQL);
			$Sentencia->bindValue(":codigo", $codigo);
			$Sentencia->execute();  //Ejecuta el borrado
			return "Borrado de registro exitoso";
		}
		catch (Exception $e) {
			return "Falló al borrar registro. Error: " . implode(" | ",$Sentencia->errorinfo());
		}
	}

	//Registros para el grid
	public function DatosGrid($Programa){
		$SQL = "SELECT catedras.codigo, catedras.codigouniversidad, catedras.nombre, catedras.semestre, periodos.nombre FROM periodos, catedras WHERE catedras.programa = :programa AND catedras.periodo = periodos.codigo ORDER BY catedras.nombre";
		$Sentencia = $this->BaseDatos->Conexion->prepare($SQL);
		$Sentencia->bindValue(":programa", $Programa);
		$Sentencia->execute();  //Ejecuta la consulta
		$Registros = $Sentencia->fetchAll();

		$Datos = "";
		for ($Fila=0; $Fila < count($Registros); $Fila++){
			$Datos .= "<tr>";
			$Datos .= "<td>" . htmlentities($Registros[$Fila][1], ENT_QUOTES, "UTF-8") . "</td>";
			$Datos .= "<td>" . htmlentities($Registros[$Fila][2], ENT_QUOTES, "UTF-8") . "</td>";
			$Datos .= "<td>" . htmlentities($Registros[$Fila][3], ENT_QUOTES, "UTF-8") . "</td>";
			$Datos .= "<td>" . htmlentities($Registros[$Fila][4], ENT_QUOTES, "UTF-8") . "</td>";
			$Datos .= '<td><a href=\'iniciar.php?op=2&catedra=' . $Registros[$Fila][0] . '\' class=\'btn btn-primary\'>Más</a></td>';
			$Datos .= '</tr>';
		}
		return $Datos;
	}

	//Valida que la cátedra pueda ser actualizada por el comité
	public function CatedraComite($Catedra, $Programa){
		$SQL = "SELECT count(*) FROM catedras WHERE codigo = :codigo AND programa = :programa";
		$Sentencia = $this->BaseDatos->Conexion->prepare($SQL);
		$Sentencia->bindValue(":codigo", $Catedra);
		$Sentencia->bindValue(":programa", $Programa);
		$Sentencia->execute();  //Ejecuta la consulta
		$Registros = $Sentencia->fetchAll();
		return $Registros[0][0];
	}

	public function Resultados($Programa){
		$SQL = "SELECT codigo, nombre FROM resultados WHERE programa = :programa";
		$Sentencia = $this->BaseDatos->Conexion->prepare($SQL);
		$Sentencia->bindValue(":programa", $Programa);
		$Sentencia->execute();  //Ejecuta la consulta
		$Registros = $Sentencia->fetchAll();

		$Datos = "";
		for ($Fila=0; $Fila < count($Registros); $Fila++)
			$Datos .= "<input type='checkbox' class='form-check-input' value = '" . $Registros[$Fila][0] . "' name='resultados[]'>" . $Registros[$Fila][1] . "<br/>";
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

	public function Competencias($Programa){
		$SQL = "SELECT codigo, nombre FROM competencias WHERE programa = :programa";
		$Sentencia = $this->BaseDatos->Conexion->prepare($SQL);
		$Sentencia->bindValue(":programa", $Programa);
		$Sentencia->execute();  //Ejecuta la consulta
		$Registros = $Sentencia->fetchAll();

		$Datos = "";
		for ($Fila=0; $Fila < count($Registros); $Fila++)
			$Datos .= "<input type='checkbox' class='form-check-input' value = '" . $Registros[$Fila][0] . "' name='competencias[]'>" . $Registros[$Fila][1] . "<br/>";
		return $Datos;
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
	public function actualiza1() { return "../../vista/catedras/actualiza1.html"; }
	public function actualiza2() { return "../../vista/catedras/actualiza2.html"; }
	public function adiciona1() { return "../../vista/catedras/adiciona1.html"; }
	public function adiciona2() { return "../../vista/catedras/adiciona2.html"; }
	public function borra1() { return "../../vista/catedras/borra1.html"; }
	public function borra2() { return "../../vista/catedras/borra2.html"; }
	public function busca1() { return "../../vista/catedras/busca1.html"; }
	public function busca2() { return "../../vista/catedras/busca2.html"; }
	public function detalle() { return "../../vista/catedras/detalle.html"; }
	public function registros() { return "../../vista/catedras/registros.html"; }
	public function rutaprog() { return "../../prog/catedras/"; }
	public function rutavista() { return "../../vista/catedras/"; }
}