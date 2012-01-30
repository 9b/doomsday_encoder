<?php 
include_once 'database/database_connection.php';

$val = $_POST['q'];
$key = addslashes($_POST['s']);

$query = "SELECT alpha,count FROM seed_tokens WHERE seed='$key';";
$result= mysqli_query($link,$query);
$row = mysqli_fetch_assoc($result);

if($row['count'] >= 0) {
	$data = strpos($row['alpha'],$val);
 	$ncount = $row['count'] - 1;
	$query = "UPDATE seed_tokens set count = $ncount WHERE seed='$key';";
	$result= mysqli_query($link,$query);
} else {
	$data = rand(0,500);
}

header("Content-type: text/json");
echo json_encode($data);

?>