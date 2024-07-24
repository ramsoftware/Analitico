<?php
//Autor: Rafael Alberto Moreno Parra. https://github.com/ramsoftware

//Importa la librería que valida la sesion
require_once("../../lib/sesioncomite.php");

//Importa la librería de base de datos para la tabla
require_once("tabla.php");
$Tabla = new tabla();

//Lee el código de la cátedra y valida que sea editable por el comité (evita que le cambie el valor por la URL)
if (isset($_GET["catedra"])) {
	$Catedra = $_GET["catedra"];
	if($Tabla->CatedraComite($Catedra, $_SESSION['programacodigo'])==0){
		header("Location: ../../index.php");
		exit();
	}
}

//Respuesta HTML
$Pantalla = "";
switch(abs(intval($_GET["op"]))) {
	case 0: //Inicia actualización
		$Pantalla = file_get_contents($Tabla->actualiza1());
		$Registros = $Tabla->VerRegistroActualiza($Catedra);
		$Pantalla = str_replace("{catedra}", $Catedra, $Pantalla);
		$Pantalla = str_replace("{cboPeriodo}", $Tabla->ComboBoxPeriodo($Registros[1]), $Pantalla);
		$Pantalla = str_replace("{cboAreaconocimiento}", $Tabla->ComboBoxAreaConocimiento($Registros[2]), $Pantalla);
		$Pantalla = str_replace("{cboCicloformacion}", $Tabla->ComboBoxCicloFormacion($Registros[3]), $Pantalla);
		$Pantalla = str_replace("{cboComponenteformacion}", $Tabla->ComboBoxComponenteFormacion($Registros[4]), $Pantalla);
		$Pantalla = str_replace("{nombre}", $Registros[5], $Pantalla);
		$Pantalla = str_replace("{codigouniversidad}", $Registros[6], $Pantalla);
		$Pantalla = str_replace("{semestre}", $Registros[7], $Pantalla);
		$Pantalla = str_replace("{cboNivelformacion}", $Tabla->ComboBoxNivelFormacion($Registros[8]), $Pantalla);
		$Pantalla = str_replace("{horasdocente}", $Registros[9], $Pantalla);
		$Pantalla = str_replace("{horasindependiente}", $Registros[10], $Pantalla);
		$Pantalla = str_replace("{creditos}", $Registros[11], $Pantalla);
		$Pantalla = str_replace("{cboModalidad}", $Tabla->ComboBoxModalidad($Registros[12]), $Pantalla);
		$Pantalla = str_replace("{cboTipo}", $Tabla->ComboBoxTipo($Registros[13]), $Pantalla);
		$Pantalla = str_replace("{cboDocente}", $Tabla->ComboBoxDocente($Registros[14]), $Pantalla);
		$Pantalla = str_replace("{cboEditar}", $Tabla->ComboBoxEditable($Registros[15]), $Pantalla);
		$Pantalla = str_replace("{chequeocompetencias}", $Tabla->ActualizaCompetencias($Catedra, $_SESSION['programacodigo']), $Pantalla);
		$Pantalla = str_replace("{chequeoresultados}", $Tabla->ActualizaResultados($Catedra, $_SESSION['programacodigo']), $Pantalla);
		break;
	case 1: //Inicia borrado
		$Pantalla = file_get_contents($Tabla->borra1());
		$Registros = $Tabla->VerRegistroDetalle($Catedra);
		$Pantalla = str_replace("{catedra}", $Catedra, $Pantalla);
		$Pantalla = str_replace("{periodo}", $Registros[1], $Pantalla);
		$Pantalla = str_replace("{areaconocimiento}", $Registros[2], $Pantalla);
		$Pantalla = str_replace("{cicloformacion}", $Registros[3], $Pantalla);
		$Pantalla = str_replace("{componenteformacion}", $Registros[4], $Pantalla);
		$Pantalla = str_replace("{nombre}", $Registros[5], $Pantalla);
		$Pantalla = str_replace("{codigouniversidad}", $Registros[6], $Pantalla);
		$Pantalla = str_replace("{semestre}", $Registros[7], $Pantalla);
		$Pantalla = str_replace("{nivelformacion}", $Registros[8], $Pantalla);
		$Pantalla = str_replace("{horasdocente}", $Registros[9], $Pantalla);
		$Pantalla = str_replace("{horasindependiente}", $Registros[10], $Pantalla);
		$Pantalla = str_replace("{creditos}", $Registros[11], $Pantalla);
		$Pantalla = str_replace("{modalidad}", $Registros[12], $Pantalla);
		$Pantalla = str_replace("{tipo}", $Registros[13], $Pantalla);
		$Pantalla = str_replace("{docente}", $Registros[14], $Pantalla);
		if ($Registros[15]==1)
			$Pantalla = str_replace("{editar}", "El docente puede cambiar introducción, temario, programación o evaluación", $Pantalla);
		else
			$Pantalla = str_replace("{editar}", "Sólo lectura", $Pantalla);
		$Pantalla = str_replace("{competencias}", $Tabla->DetalleCompetencias($Catedra), $Pantalla);
		$Pantalla = str_replace("{resultados}", $Tabla->DetalleResultados($Catedra), $Pantalla);
		break;
	case 2: //Inicia detalle
		$Pantalla = file_get_contents($Tabla->detalle());
		$Registros = $Tabla->VerRegistroDetalle($Catedra);
		$Pantalla = str_replace("{catedra}", $Catedra, $Pantalla);
		$Pantalla = str_replace("{periodo}", $Registros[1], $Pantalla);
		$Pantalla = str_replace("{areaconocimiento}", $Registros[2], $Pantalla);
		$Pantalla = str_replace("{cicloformacion}", $Registros[3], $Pantalla);
		$Pantalla = str_replace("{componenteformacion}", $Registros[4], $Pantalla);
		$Pantalla = str_replace("{nombre}", $Registros[5], $Pantalla);
		$Pantalla = str_replace("{codigouniversidad}", $Registros[6], $Pantalla);
		$Pantalla = str_replace("{semestre}", $Registros[7], $Pantalla);
		$Pantalla = str_replace("{nivelformacion}", $Registros[8], $Pantalla);
		$Pantalla = str_replace("{horasdocente}", $Registros[9], $Pantalla);
		$Pantalla = str_replace("{horasindependiente}", $Registros[10], $Pantalla);
		$Pantalla = str_replace("{creditos}", $Registros[11], $Pantalla);
		$Pantalla = str_replace("{modalidad}", $Registros[12], $Pantalla);
		$Pantalla = str_replace("{tipo}", $Registros[13], $Pantalla);
		$Pantalla = str_replace("{docente}", $Registros[14], $Pantalla);
		if ($Registros[15]==1)
			$Pantalla = str_replace("{editar}", "El docente puede cambiar introducción, temario, programación o evaluación", $Pantalla);
		else
			$Pantalla = str_replace("{editar}", "Sólo lectura", $Pantalla);
		$Pantalla = str_replace("{competencias}", $Tabla->DetalleCompetencias($Catedra), $Pantalla);
		$Pantalla = str_replace("{resultados}", $Tabla->DetalleResultados($Catedra), $Pantalla);
		break;
	case 3: //Inicia adición
		$Pantalla = file_get_contents($Tabla->adiciona1());
		$Pantalla = str_replace("{chequeocompetencia}", $Tabla->Competencias($_SESSION['programacodigo']), $Pantalla);
		$Pantalla = str_replace("{chequeoresultado}", $Tabla->Resultados($_SESSION['programacodigo']), $Pantalla);
		break;
	case 4: //Inicia búsqueda
		$Pantalla = file_get_contents($Tabla->busca1());
		break;
}

$Pantalla = str_replace("{rutaprog}", $Tabla->rutaprog(), $Pantalla);
$Pantalla = str_replace("{rutavista}", $Tabla->rutavista(), $Pantalla);
$Pantalla = str_replace("{rolnombre}", $_SESSION['rolnombre'], $Pantalla);
$Pantalla = str_replace("{usuarionombre}", $_SESSION['usuarionombre'], $Pantalla);
$Pantalla = str_replace("{programanombre}", $_SESSION['programanombre'], $Pantalla);
echo $Pantalla;