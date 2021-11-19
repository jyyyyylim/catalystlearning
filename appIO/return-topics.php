<?php
//raison d'etre: repeated db calls for topic fetching
//spits all topics available into array $results
require './src/db_socket.php';

$operation= "select topic from topic";
$query= mysqli_query($sock, $operation);

//predefine array
$results= array();

//fetchassoc only returns a single match, so execute a whileloop
while ($row = mysqli_fetch_assoc($query)) {
    array_push($results, $row['topic']);
}
?>