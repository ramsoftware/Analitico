<?php
//Autor: Rafael Alberto Moreno Parra. https://github.com/ramsoftware

/* ========================================
Genera los informes
======================================== */
require_once("../../lib/BD.php");

class tabla{
	//Mantiene la conexión activa
	public $BaseDatos;
	
	//Variable para el texto del informe
	public $TextoInforme;

	//Variable para los valores del diagrama de pastel
	public $EtiquetasPastel;
	public $ValoresPastel;
	
	//Conecta a la base de datos
	public function __construct(){
		$this->BaseDatos = new basedatos();
		return $this->BaseDatos->Conectar();
	}

	/* Genera el informe de número de documentos que ha registrado el docente por idioma */
	public function Informe01($usuario){
		$SQL = "SELECT idiomas.nombre, COUNT(*) AS cantidad, 
	ROUND(COUNT(*) * 100.0 / SUM(COUNT(*)) OVER(), 2) AS porcentaje FROM idiomas 
	JOIN documentos ON documentos.idioma = idiomas.codigo AND documentos.usuario = :usuario GROUP BY idiomas.codigo";
		$Sentencia = $this->BaseDatos->Conexion->prepare($SQL);
		$Sentencia->bindValue(":usuario", $usuario);
		$Sentencia->execute();  //Ejecuta la consulta
		$Registros = $Sentencia->fetchAll();
		
		$this->TextoInforme = "";
		$this->ValoresPastel = "";

		$Datos = "";
		for ($Fila=0; $Fila < count($Registros); $Fila++){
			$Datos .= "<tr>";
			$Datos .= "<td>" . htmlentities($Registros[$Fila][0], ENT_QUOTES, "UTF-8") . "</td>";
			$Datos .= "<td>" . htmlentities($Registros[$Fila][1], ENT_QUOTES, "UTF-8") . "</td>";
			$Datos .= "<td>" . htmlentities($Registros[$Fila][2], ENT_QUOTES, "UTF-8") . "%</td>";
			$Datos .= '</tr>';

			$this->EtiquetasPastel .= "'" . $Registros[$Fila][0] . "',";
			$this->ValoresPastel .= $Registros[$Fila][1] . ",";
		}
		$this->ValoresPastel = substr_replace($this->ValoresPastel ,"", -1);
		$this->TextoInforme = $Datos;
	}

	//Retorna el número de fuentes de información usadas en la cátedra (según sesiones)

	//Muestra el porcentaje de idiomas de las fuentes de información usadas (según sesiones)

	//Mostrar cuántas veces fue abordado cada resultado de aprendizaje (según las sesiones)

	//Mostrar cuántas veces fue abordado cada resultado de aprendizaje (según las evaluaciones)

	//Mostrar uso de los diferentes recursos (según las sesiones)	
}
