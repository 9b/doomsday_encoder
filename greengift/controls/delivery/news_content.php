<?php 

include_once '../database/database_connection.php';
include_once '../utilities/dramaqueen.php';
	
$obfuscator = new kenai();
$data_result = $obfuscator->lesda_encoder("document.write('9b+');");
$data_result = $obfuscator->hilma_encoder($data_result);

echo $data_result;

?>