<?php
//Importa la librería que valida la sesion
require_once("../../lib/sesiondocente.php");

//Importa la librería de base de datos para la tabla catedras
require_once("tabla.php");
$Tabla = new tabla();

//Trae los campos del registro
$Catedra = $_GET["catedra"];
$Resultados = $Tabla->TraeBasico($Catedra);

//Respuesta HTML
$Pantalla = file_get_contents($Tabla->datosbasicos());
$Pantalla = str_replace("{catedra}", $Catedra, $Pantalla);
$Pantalla = str_replace("{periodo}", $Resultados[1], $Pantalla);
$Pantalla = str_replace("{programa}", $Resultados[2], $Pantalla);
$Pantalla = str_replace("{areaconocimiento}", $Resultados[3], $Pantalla);
$Pantalla = str_replace("{cicloformacion}", $Resultados[4], $Pantalla);
$Pantalla = str_replace("{componenteformacion}", $Resultados[5], $Pantalla);
$Pantalla = str_replace("{nombre}", $Resultados[6], $Pantalla);
$Pantalla = str_replace("{codigouniversidad}", $Resultados[7], $Pantalla);
$Pantalla = str_replace("{semestre}", $Resultados[8], $Pantalla);
$Pantalla = str_replace("{nivelformacion}", $Resultados[9], $Pantalla);
$Pantalla = str_replace("{horasdocente}", $Resultados[10], $Pantalla);
$Pantalla = str_replace("{horasindependiente}", $Resultados[11], $Pantalla);
$Pantalla = str_replace("{creditos}", $Resultados[12], $Pantalla);
$Pantalla = str_replace("{modalidad}", $Resultados[13], $Pantalla);
$Pantalla = str_replace("{tipo}", $Resultados[14], $Pantalla);
$Pantalla = str_replace("{rutaprog}", $Tabla->rutaprog(), $Pantalla);
$Pantalla = str_replace("{rolnombre}", $_SESSION['rolnombre'], $Pantalla);
$Pantalla = str_replace("{usuarionombre}", $_SESSION['usuarionombre'], $Pantalla);
$Pantalla = str_replace("{competencias}", $Tabla->DetalleCompetencias($Catedra), $Pantalla);
$Pantalla = str_replace("{resultados}", $Tabla->DetalleResultados($Catedra), $Pantalla);
echo $Pantalla;