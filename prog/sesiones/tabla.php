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

	public function SesionAutorizada($sesion, $docente){
		$SQL = "SELECT COUNT(*) FROM catedras WHERE docente = :docente and editable = 1 AND codigo IN (SELECT catedra FROM sesiones WHERE codigo = :sesion)";
		$Sentencia = $this->BaseDatos->Conexion->prepare($SQL);
		$Sentencia->bindValue(":sesion", $sesion);
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
	public function DatosGrid($Catedra){
		$SQL = "SELECT codigo, nombre FROM sesiones WHERE catedra = :catedra ORDER BY nombre";
		$Sentencia = $this->BaseDatos->Conexion->prepare($SQL);
		$Sentencia->bindValue(":catedra", $Catedra);
		$Sentencia->execute();  //Ejecuta la consulta
		$Registros = $Sentencia->fetchAll();

		$Datos = "";
		for ($Fila=0; $Fila < count($Registros); $Fila++){
			$Datos .= "<tr>";
			$Datos .= "<td>" . htmlentities($Registros[$Fila][1], ENT_QUOTES, "UTF-8") . "</td>";
			$Datos .= '<td><a href=\'iniciar.php?sesion=' . $Registros[$Fila][0] . '&op=2 \' class=\'btn btn-primary\'>Detalles</a></td>';
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
		$SQL = "SELECT sesiones.codigo, catedras.nombre, periodos.nombre, sesiones.nombre,
sesiones.presencial, sesiones.presencialhoras, sesiones.independiente, 
sesiones.independientehoras, sesiones.recursos, catedras.codigo
FROM sesiones, catedras, periodos
WHERE catedras.codigo = sesiones.catedra
AND periodos.codigo = catedras.periodo
AND sesiones.codigo = :codigo";

		//Conecta a la base de datos y hace la consulta
		$Sentencia = $this->BaseDatos->Conexion->prepare($SQL);
		$Sentencia->bindValue(":codigo", $codigo);
		$Sentencia->execute();  //Ejecuta la consulta
		return $Sentencia->fetch();
	}

	//Retorna el registro de la tabla para actualizar
	public function VerRegistroActualiza($codigo){
		$SQL = "SELECT sesiones.codigo, catedras.nombre, periodos.nombre, sesiones.nombre, 
sesiones.presencial, sesiones.presencialhoras, sesiones.independiente, 
sesiones.independientehoras, sesiones.recursos, catedras.codigo
FROM sesiones, catedras, periodos
WHERE catedras.codigo = sesiones.catedra
AND periodos.codigo = catedras.periodo
AND sesiones.codigo = :codigo";

		//Conecta a la base de datos y hace la consulta
		$Sentencia = $this->BaseDatos->Conexion->prepare($SQL);
		$Sentencia->bindValue(":codigo", $codigo);
		$Sentencia->execute();  //Ejecuta la consulta
		return $Sentencia->fetch();
	}

	//Actualiza el registro
	public function Actualizar($codigo, $nombre, $presencial, $presencialhoras, $independiente, $independientehoras, $recursos, $unidades, $resultados){
		$SQL = "UPDATE sesiones SET nombre = :nombre, presencial = :presencial, presencialhoras = :presencialhoras, independiente = :independiente, independientehoras = :independientehoras, recursos = :recursos WHERE codigo = :codigo";

		//Conecta a la base de datos y hace la actualización
		$Sentencia = $this->BaseDatos->Conexion->prepare($SQL);
		$Sentencia->bindValue(":codigo", $codigo);
		$Sentencia->bindValue(":nombre", $nombre);
		$Sentencia->bindValue(":presencial", $presencial);
		$Sentencia->bindValue(":presencialhoras", $presencialhoras);
		$Sentencia->bindValue(":independiente", $independiente);
		$Sentencia->bindValue(":independientehoras", $independientehoras);
		$Sentencia->bindValue(":recursos", $recursos);

		try{
			$Sentencia->execute();  //Ejecuta la actualización

			$SQL = "DELETE FROM sesionesunidad WHERE sesion = :codigo";
			$Sentencia = $this->BaseDatos->Conexion->prepare($SQL);
			$Sentencia->bindValue(":codigo", $codigo);
			$Sentencia->execute();  //Ejecuta el borrado

			$SQL = "DELETE FROM sesionesresultado WHERE sesion = :codigo";
			$Sentencia = $this->BaseDatos->Conexion->prepare($SQL);
			$Sentencia->bindValue(":codigo", $codigo);
			$Sentencia->execute();  //Ejecuta el borrado

			//Si logra ejecutar entonces crea los registros de unidades vistas
			foreach ($unidades as $unidad) {
				$SQL = "INSERT INTO sesionesunidad (sesion, unidad) VALUES (:sesion, :unidad)";
				$Sentencia = $this->BaseDatos->Conexion->prepare($SQL);
				$Sentencia->bindValue(":sesion", $codigo);
				$Sentencia->bindValue(":unidad", $unidad);
				$Sentencia->execute();  //Ejecuta la adición
			}

			//Si logra ejecutar entonces crea los registros de resultados de aprendizaje cubiertos
			foreach ($resultados as $resultado) {
				$SQL = "INSERT INTO sesionesresultado (sesion, resultado) VALUES (:sesion, :resultado)";
				$Sentencia = $this->BaseDatos->Conexion->prepare($SQL);
				$Sentencia->bindValue(":sesion", $codigo);
				$Sentencia->bindValue(":resultado", $resultado);
				$Sentencia->execute();  //Ejecuta la adición
			}

			return 1;
		}
		catch (Exception $excepcion) {
			return "Falló al actualizar registro. <br>Detalle: " . $excepcion->getMessage();
		}
	}

	//Adiciona el registro de temas de la cátedra
	public function Adicionar($catedra, $nombre, $presencial, $presencialhoras, $independiente, $independientehoras, $recursos, $unidades, $resultados){
		$SQL = "INSERT INTO sesiones(catedra, nombre, presencial, presencialhoras, independiente, independientehoras, recursos)
		VALUES (:catedra, :nombre, :presencial, :presencialhoras, :independiente, :independientehoras, :recursos)";

		//Conecta a la base de datos y hace la actualización
		$Sentencia = $this->BaseDatos->Conexion->prepare($SQL);
		$Sentencia->bindValue(":catedra", $catedra);
		$Sentencia->bindValue(":nombre", $nombre);
		$Sentencia->bindValue(":presencial", $presencial);
		$Sentencia->bindValue(":presencialhoras", $presencialhoras);
		$Sentencia->bindValue(":independiente", $independiente);
		$Sentencia->bindValue(":independientehoras", $independientehoras);
		$Sentencia->bindValue(":recursos", $recursos);

		try{
			$Sentencia->execute();  //Ejecuta la adición
			$sesion = $this->BaseDatos->Conexion->lastInsertId();

			//Si logra ejecutar entonces crea los registros de unidades vistas
			foreach ($unidades as $unidad) {
				$SQL = "INSERT INTO sesionesunidad (sesion, unidad) VALUES (:sesion, :unidad)";
				$Sentencia = $this->BaseDatos->Conexion->prepare($SQL);
				$Sentencia->bindValue(":sesion", $sesion);
				$Sentencia->bindValue(":unidad", $unidad);
				$Sentencia->execute();  //Ejecuta la adición
			}

			//Si logra ejecutar entonces crea los registros de resultados de aprendizaje cubiertos
			foreach ($resultados as $resultado) {
				$SQL = "INSERT INTO sesionesresultado (sesion, resultado) VALUES (:sesion, :resultado)";
				$Sentencia = $this->BaseDatos->Conexion->prepare($SQL);
				$Sentencia->bindValue(":sesion", $sesion);
				$Sentencia->bindValue(":resultado", $resultado);
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
			$SQL = "DELETE FROM sesionesresultado WHERE sesion = :codigo";
			$Sentencia = $this->BaseDatos->Conexion->prepare($SQL);
			$Sentencia->bindValue(":codigo", $codigo);
			$Sentencia->execute();  //Ejecuta el borrado

			$SQL = "DELETE FROM sesionesunidad WHERE sesion = :codigo";
			$Sentencia = $this->BaseDatos->Conexion->prepare($SQL);
			$Sentencia->bindValue(":codigo", $codigo);
			$Sentencia->execute();  //Ejecuta el borrado

			$SQL = "DELETE FROM sesiones WHERE codigo = :codigo";
			$Sentencia = $this->BaseDatos->Conexion->prepare($SQL);
			$Sentencia->bindValue(":codigo", $codigo);
			$Sentencia->execute();  //Ejecuta el borrado

			return 1;
		}
		catch (Exception $excepcion) {
			return "Falló al borrar registro. <br>Detalle: " . $excepcion->getMessage();
		}
	}

	//Retorna la cátedra dada la unidad
	public function CodigoCatedra($sesion){
		$SQL = "SELECT catedra FROM sesiones WHERE codigo = :sesion";

		//Conecta a la base de datos y hace la consulta
		$Sentencia = $this->BaseDatos->Conexion->prepare($SQL);
		$Sentencia->bindValue(":sesion", $sesion);
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
	public function DetalleUnidades($sesion){
		$SQL = "SELECT titulo FROM unidades
WHERE codigo IN (SELECT unidad FROM sesionesunidad WHERE sesion = :sesion) ORDER BY titulo";
		$Sentencia = $this->BaseDatos->Conexion->prepare($SQL);
		$Sentencia->bindValue(":sesion", $sesion);
		$Sentencia->execute();  //Ejecuta la consulta
		$Registros = $Sentencia->fetchAll();

		$Datos = "";
		for ($Fila=0; $Fila < count($Registros); $Fila++)
			$Datos .= $Registros[$Fila][0] . "<br/>";
		return $Datos;
	}

	//Retorna para la actualización del registro
	public function ActualizaUnidades($sesion, $catedra){
		$SQL = "SELECT codigo, titulo FROM unidades
WHERE codigo IN (SELECT unidad FROM sesionesunidad WHERE sesion = :sesion) ORDER BY titulo";
		$Sentencia = $this->BaseDatos->Conexion->prepare($SQL);
		$Sentencia->bindValue(":sesion", $sesion);
		$Sentencia->execute();  //Ejecuta la consulta
		$Registros = $Sentencia->fetchAll();

		$Datos = "";
		for ($Fila=0; $Fila < count($Registros); $Fila++)
			$Datos .= "<input type='checkbox' class='form-check-input' value = '" . $Registros[$Fila][0] . "' name='unidades[]' checked='checked'>" . $Registros[$Fila][1] . "<br/>";

		$SQL = "SELECT codigo, titulo FROM unidades
WHERE catedra = :catedra AND codigo NOT IN (SELECT unidad FROM sesionesunidad WHERE sesion = :sesion) ORDER BY titulo";
		$Sentencia = $this->BaseDatos->Conexion->prepare($SQL);
		$Sentencia->bindValue(":sesion", $sesion);
		$Sentencia->bindValue(":catedra", $catedra);
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
	public function DetalleResultados($sesion){
		$SQL = "SELECT nombre FROM resultados
WHERE codigo IN (SELECT resultado FROM sesionesresultado WHERE sesion = :sesion) ORDER BY nombre";
		$Sentencia = $this->BaseDatos->Conexion->prepare($SQL);
		$Sentencia->bindValue(":sesion", $sesion);
		$Sentencia->execute();  //Ejecuta la consulta
		$Registros = $Sentencia->fetchAll();

		$Datos = "";
		for ($Fila=0; $Fila < count($Registros); $Fila++)
			$Datos .= $Registros[$Fila][0] . "<br/>";
		return $Datos;
	}

	//Retorna para la actualización del registro
	public function ActualizaResultados($sesion){
		$SQL = "SELECT codigo, nombre FROM resultados WHERE codigo IN (SELECT resultado FROM sesionesresultado WHERE sesion = :sesion) ORDER BY nombre";
		$Sentencia = $this->BaseDatos->Conexion->prepare($SQL);
		$Sentencia->bindValue(":sesion", $sesion);
		$Sentencia->execute();  //Ejecuta la consulta
		$Registros = $Sentencia->fetchAll();

		$Datos = "";
		for ($Fila=0; $Fila < count($Registros); $Fila++)
			$Datos .= "<input type='checkbox' class='form-check-input' value = '" . $Registros[$Fila][0] . "' name='resultados[]' checked='checked'>" . $Registros[$Fila][1] . "<br/>";

		$SQL = "SELECT catedraresultados.resultado, resultados.nombre FROM catedraresultados, resultados
WHERE resultados.codigo = catedraresultados.resultado AND catedra IN (SELECT catedra FROM sesiones WHERE codigo = :sesion)
AND resultado NOT IN (SELECT resultado FROM sesionesresultado WHERE sesion = :sesion) ORDER BY resultados.nombre";

		//$SQL = "SELECT codigo, nombre FROM resultados WHERE codigo NOT IN (SELECT resultado FROM sesionesresultado WHERE sesion = :sesion) ORDER BY nombre";
		$Sentencia = $this->BaseDatos->Conexion->prepare($SQL);
		$Sentencia->bindValue(":sesion", $sesion);
		$Sentencia->execute();  //Ejecuta la consulta
		$Registros = $Sentencia->fetchAll();

		for ($Fila=0; $Fila < count($Registros); $Fila++)
			$Datos .= "<input type='checkbox' class='form-check-input' value = '" . $Registros[$Fila][0] . "' name='resultados[]'>" . $Registros[$Fila][1] . "<br/>";
		return $Datos;
	}

	//Configuración de los PATH
	public function actualiza1() { return "../../vista/sesiones/actualiza1.html"; }
	public function actualiza2() { return "../../vista/sesiones/actualiza2.html"; }
	public function adiciona1() { return "../../vista/sesiones/adiciona1.html"; }
	public function adiciona2() { return "../../vista/sesiones/adiciona2.html"; }
	public function borra1() { return "../../vista/sesiones/borra1.html"; }
	public function borra2() { return "../../vista/sesiones/borra2.html"; }
	public function detalle() { return "../../vista/sesiones/detalle.html"; }
	public function registros() { return "../../vista/sesiones/registros.html"; }
	public function rutaprog() { return "../../prog/sesiones/"; }
	public function rutavista() { return "../../vista/sesiones/"; }
}