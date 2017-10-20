<?php 

// script obtener una tabla con los usuarios que los usuarios que estan en la lista de usuarios de un curso pero que no tienen
// ningun rol asignado.

require_once('../config.php');
global $CFG;
global $USER;

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

echo '<html>
	  <head>
	  <link rel="stylesheet" type="text/css" href="style.css">
	  </head>
	  <body><div class="contenedor">';
//echo '<hr />';
//echo '<hr />';
echo '<center><h1>Informe general 2012</h1></center>';
//informe 2012
echo '<div class="botones"><center><div id="boton2">
<a href="'.$CFG->wwwroot.'/report_eabc/list.php?year=2012" title="Boton">
Ver listado
</a>
</div>';
echo '<div class="botones"><center><div id="boton1">
<a href="'.$CFG->wwwroot.'/report_eabc/download.php?format=xls&year=2012" title="Boton">
Descargar en planilla.
</a>
</div></center></div><br />';
echo '<br />';
echo '<center><h1>Informe general 2013</h1></center>';
//informe 2013
echo '<div class="botones"><center><div id="boton2">
<a href="'.$CFG->wwwroot.'/report_eabc/list.php?year=2013" title="Boton">
Ver listado
</a>
</div>';
echo '<div class="botones"><center><div id="boton1">
<a href="'.$CFG->wwwroot.'/report_eabc/download.php?format=xls&year=2013" title="Boton">
Descargar en planilla.
</a>
</div></center></div><br />';
echo '<br />';

?>