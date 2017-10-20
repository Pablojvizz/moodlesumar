<?php

include ('config.php');
/*
global $DB;

echo '<br />datos: <br />';
$datas = $DB->get_records('view_unq_report');
var_dump($datas);exit;
*/


//conection:
$link = mysqli_connect($CFG->dbhost,$CFG->dbuser,$CFG->dbpass,$CFG->dbname) or die("Error create connect " . mysqli_error($link));

//consultation:

$query = "SELECT * FROM view_unq_report" or die("Error in the consult.." . mysqli_error($link));

//execute the query.

$result = $link->query($query);
//var_dump($result); //exit;
//display information:

while($row = mysqli_fetch_array($result)) {
  echo $row["username"].' '.$row["nombres"].' '.$row["Curso"]."<br>"; 
} 

/*
foreach ($datas as $data){
	echo $data->username .' , '. $data->nombres . ' , '. $data->Curso. '<br />';
}
*/

?>