<?php

include ('config.php');

//conection:
$link = mysqli_connect($CFG->dbhost,$CFG->dbuser,$CFG->dbpass,$CFG->dbname) or die("Error create connect " . mysqli_error($link));

//consultation:
$query = "SELECT * FROM view_unq_report" or die("Error in the consult.." . mysqli_error($link));

//execute the query.

$result = $link->query($query);

//display information:

while($row = mysqli_fetch_array($result)) {
  echo $row["username"].' '.$row["nombres"].' '.$row["Curso"]."<br>"; 
} 

?>