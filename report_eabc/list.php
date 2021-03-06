<?php 

// script obtener una tabla con los usuarios que los usuarios que estan en la lista de usuarios de un curso pero que no tienen
// ningun rol asignado.

require_once('../config.php');
global $CFG;
global $USER;

$year = $_GET['year'];

if ($_GET['year'] != '2012' and $_GET['year'] != '2013'){
	exit;
}

ini_set('memory_limit', '198M');
set_time_limit(1200);
$time_start = microtime(true);
header('Content-Type: text/html; charset=utf-8');

//conection:
$link = mysqli_connect($CFG->dbhost,$CFG->dbuser,$CFG->dbpass,$CFG->dbname) or die("Error create connect " . mysqli_error($link));

//configuracion para campos de tabla con tildes :( 
$charset = "SET NAMES 'utf8'";
$link->query($charset);

$query_user = "SELECT DISTINCT  provincia_user
          FROM view_unq_report" or die("Error in the consult.." . mysqli_error($link));
$result_user = $link->query($query_user);
$isuser = 0;
while($row_user = mysqli_fetch_assoc($result_user)) {
  if ($USER->username == $row_user['provincia_user'] or  $USER->username == 'eabc' or $USER->username == 'maria' or $USER->username == 'admin' or $USER->username == 'gestionnp'){
  		$isuser = 1;
  }
}
if (!$isuser){
	mysqli_free_result($result_user);
	mysqli_close($link);
	exit;

}

// consulta que trae los registros donde el nivel de avance sea finalizado en cualquier fecha.
$query = "SELECT DISTINCT provincia_de_residencia , tipo_y_lugar_de_trabajo, establecimiento, tipo_de_organismo_y_organismo,tipo_de_función, función_que_desempeña, 
          nombres, documento, email, phone1 , Curso ,fecha_matriculacion , Cantidad_de_horas , Nivel_de_avance , provincia_user
          FROM view_unq_report
          WHERE userid not in (29,109) and ( Nivel_de_avance like '%".$year."%' or fecha_matriculacion like '%".$year."%' ) order by nombres asc , Curso asc , Nivel_de_avance desc" or die("Error in the consult.." . mysqli_error($link));
          
//execute the query.
$result = $link->query($query);

echo '<html>
	  <head>
	  <link rel="stylesheet" type="text/css" href="style.css">
	  </head>
	  <body><div class="contenedor">';
echo '<center><h1>Informe general '.$year.'</h1></center>';
echo '<div class="botones"><center>';
/*
echo '<div id="boton2">
<a href="'.$CFG->wwwroot.'/report_eabc/list.php" title="Boton">
Ver listado
</a>
</div>';
*/
echo '<div class="botones"><center><div id="boton1">
<a href="'.$CFG->wwwroot.'/report_eabc/download.php?format=xls&year='.$year.'" title="Boton">
Descargar en planilla.
</a>
</div><br />';

echo '<div id="boton2">
<a href="'.$CFG->wwwroot.'/report_eabc/index.php" title="Boton">
Volver al índice
</a>
</div>';

echo '</center></div><br />';
echo '<br />';
echo '<table border="1">
  <tr>
    <th>provincia_de_residencia</th>
    <th>tipo_y_lugar_de_trabajo</th>
    <th>establecimiento</th>
    <th>tipo_de_organismo_y_organismo</th>
    <th>tipo_de_función</th>
    <th>función_que_desempeña</th>
    <th>Nombres</th>
    <th>Documento</th>
    <th>email</th>
    <th>TE</th>
    <th>Nombre del curso</th>
    <th>Fecha matriculacion</th>
    <th>Cantidad de horas</th>
    <th>Nivel de avance</th>
  </tr>';

// aca va a el negocio
$counter= 0;
echo '<br />';

while($row = mysqli_fetch_assoc($result)) {
  if ($USER->username == $row['provincia_user'] or  $USER->username == 'eabc' or $USER->username == 'maria' or $USER->username == 'admin' or $USER->username == 'gestionnp'){
  		$data_user[]= $row;
  }
}

$i = 0;
$repetidos = 0;
$agregado = 0;
$data_user_b = array();

foreach ($data_user as $du1) {
	$du1['pos'] = $i;
	$i ++;
}

$data_user_b[] = $data_user['pos'][0];

foreach ($data_user as $du) {
		$flag = 0;
		$aux = $du; 
		$auxpos = 0;
		foreach ($data_user_b as &$dub) {
			
			if (strpos($dub['Nivel_de_avance'] , 'Finalizado') === 0){
				$text = array("Finalizado (", ")");
				$dub['Nivel_de_avance'] = str_replace($text, "", $dub['Nivel_de_avance']);
				$text = array("/");
				$dub['Nivel_de_avance'] = str_replace($text, "-", $dub['Nivel_de_avance']);
				$dub['Nivel_de_avance'] = strtotime($dub['Nivel_de_avance']);
			}
			if (strpos($aux['Nivel_de_avance'] , 'Finalizado') === 0){
				$text = array("Finalizado (", ")");
				$aux['Nivel_de_avance'] = str_replace($text, "", $aux['Nivel_de_avance']);
				$text = array("/");
				$aux['Nivel_de_avance'] = str_replace($text, "-", $aux['Nivel_de_avance']);
				$aux['Nivel_de_avance'] = strtotime($aux['Nivel_de_avance']);
			}
			
			if ( $aux['email'] == $dub['email'] and $aux['Curso'] == $dub['Curso'] ){
					if ( trim($aux['Nivel_de_avance']) != 'En curso' and $dub['Nivel_de_avance'] != 'En curso' and $aux['Nivel_de_avance'] < $dub['Nivel_de_avance'] ){
						$dub['Nivel_de_avance'] = $aux['Nivel_de_avance'];
					}
					elseif(trim($aux['Nivel_de_avance']) != 'En curso' and $dub['Nivel_de_avance'] == 'En curso') {
						$dub['Nivel_de_avance'] = $aux['Nivel_de_avance'];
					}
				$flag = 1;
			}
		}
		unset($dub);
		if(!$flag) {
			$data_user_b[] = $aux;
		}
}

unset($du);

foreach ($data_user_b as $data) {
			if ($data['Nivel_de_avance'] == 'En curso'){
				$date = explode('/',$data['fecha_matriculacion']);
				if ($date[2] == $year){
					echo '<tr><td>'.$data["provincia_de_residencia"].'</td><td>'.$data["tipo_y_lugar_de_trabajo"].'</td><td>'.utf8_encode($data["establecimiento"]).'</td><td>'.$data["tipo_de_organismo_y_organismo"].'</td><td>'.$data["tipo_de_función"].'</td><td>'.$data["función_que_desempeña"].'</td><td>'.$data["nombres"].'</td><td>'.$data["documento"].'</td><td>'.$data["email"].'</td><td>'.$data["phone1"].'</td><td>'.$data["Curso"].'</td><td>'.$data["fecha_matriculacion"].'</td><td>'.$data["Cantidad_de_horas"].'</td><td>'.$data["Nivel_de_avance"].'</td></tr>';
		        	$counter ++;
		        }
			}
	else {
		
		$data['Nivel_de_avance'] = date('d-m-Y',$data['Nivel_de_avance']);
		$date = explode('-',$data['Nivel_de_avance']);
		if ($date[2] == $year){
			echo '<tr><td>'.$data["provincia_de_residencia"].'</td><td>'.$data["tipo_y_lugar_de_trabajo"].'</td><td>'.$data["establecimiento"].'</td><td>'.$data["tipo_de_organismo_y_organismo"].'</td><td>'.$data["tipo_de_función"].'</td><td>'.$data["función_que_desempeña"].'</td><td>'.$data["nombres"].'</td><td>'.$data["documento"].'</td><td>'.$data["email"].'</td><td>'.$data["phone1"].'</td><td>'.$data["Curso"].'</td><td>'.$data["fecha_matriculacion"].'</td><td>'.$data["Cantidad_de_horas"].'</td><td>'.$data["Nivel_de_avance"].'</td></tr>';
        	$counter ++;
        }
	}
}

echo '</table></div><p class="registros">registros: '.$counter.'</p></body></html>';

mysqli_free_result($result);
mysqli_close($link);
$time_end = microtime(true);
$time = $time_end - $time_start;

//echo " \nTiempo de ejecucion del script: $time segundos \n";


?>