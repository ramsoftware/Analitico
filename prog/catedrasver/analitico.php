<?php
//Autor: Rafael Alberto Moreno Parra. https://github.com/ramsoftware

//Importa la librería que valida la sesion
require_once("../../lib/sesiondocente.php");

//Importa la librería genérica para bases de datos y la instancia
require_once("tabla.php");
$Tabla = new tabla();

//Trae los datos básicos de la cátedra
$Basico = $Tabla->TraeBasico($_GET["catedra"]);
$Pantalla = file_get_contents($Tabla->analitico());
$Pantalla = str_replace("{catedra}", $Basico[0], $Pantalla);
$Pantalla = str_replace("{periodo}", $Basico[1], $Pantalla);
$Pantalla = str_replace("{programa}", $Basico[2], $Pantalla);
$Pantalla = str_replace("{areaconocimiento}", $Basico[3], $Pantalla);
$Pantalla = str_replace("{cicloformacion}", $Basico[4], $Pantalla);
$Pantalla = str_replace("{componenteformacion}", $Basico[5], $Pantalla);
$Pantalla = str_replace("{nombre}", $Basico[6], $Pantalla);
$Pantalla = str_replace("{codigouniversidad}", $Basico[7], $Pantalla);
$Pantalla = str_replace("{semestre}", $Basico[8], $Pantalla);
$Pantalla = str_replace("{nivelformacion}", $Basico[9], $Pantalla);
$Pantalla = str_replace("{horasdocente}", $Basico[10], $Pantalla);
$Pantalla = str_replace("{horasindependiente}", $Basico[11], $Pantalla);
$Pantalla = str_replace("{creditos}", $Basico[12], $Pantalla);
$Pantalla = str_replace("{modalidad}", $Basico[13], $Pantalla);
$Pantalla = str_replace("{tipo}", $Basico[14], $Pantalla);
$Pantalla = str_replace("{descripcion}", $Basico[15], $Pantalla);
$Pantalla = str_replace("{justificacion}", $Basico[16], $Pantalla);
$Pantalla = str_replace("{metodologia}", $Basico[17], $Pantalla);
$Pantalla = str_replace("{facultad}", $Basico[18], $Pantalla);

//Trae los resultados y competencias
$Pantalla = str_replace("{competencias}", $Tabla->TraeCompetencias($_GET["catedra"]), $Pantalla);
$Pantalla = str_replace("{resultados}", $Tabla->TraeResultados($_GET["catedra"]), $Pantalla);

//Trae el temario
$Unidades = $Tabla->TraeUnidades($_GET["catedra"]);
$Pantalla = str_replace("{unidades}", $Unidades, $Pantalla);

//Trae la programación
$Programacion = $Tabla->Programacion($_GET["catedra"]);
$Pantalla = str_replace("{programacion}", $Programacion, $Pantalla);

//Trae la documentación usada
$Pantalla = str_replace("{documentos}", $Tabla->TraeDocumentos($_GET["catedra"]), $Pantalla);

//Trae las evaluaciones
$Evaluaciones = $Tabla->TraeEvaluaciones($_GET["catedra"]);
$Pantalla = str_replace("{evaluaciones}", $Evaluaciones, $Pantalla);

//Trae al docente
$Docente = $Tabla->Docente($_GET["catedra"]);
$Pantalla = str_replace("{nombredocente}", $Docente[0], $Pantalla);
$Pantalla = str_replace("{correo}", $Docente[1], $Pantalla);
$Pantalla = str_replace("{experienciadocente}", $Docente[2], $Pantalla);
$Pantalla = str_replace("{experienciaprofesional}", $Docente[3], $Pantalla);
$Pantalla = str_replace("{experienciainvestigacion}", $Docente[4], $Pantalla);
$Pantalla = str_replace("{produccion}", $Docente[5], $Pantalla);

$Pantalla = str_replace("{rolnombre}", $_SESSION['rolnombre'], $Pantalla);
$Pantalla = str_replace("{usuarionombre}", $_SESSION['usuarionombre'], $Pantalla);

//Enlace para devolverse
if (isset($_GET["docente"]))
	$Pantalla = str_replace("{docente}", "?docente=1", $Pantalla);
else
	$Pantalla = str_replace("{docente}", "", $Pantalla);

echo $Pantalla;