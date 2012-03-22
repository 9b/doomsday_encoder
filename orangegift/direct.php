<?php 
include_once 'database/database_connection.php';
include_once 'utilities/dramaqueen.php';
$obfuscator = new kenai();
$data_result = $obfuscator->lesda_encoder("document.write('9b+');");
$data_result = $obfuscator->exta_encoder($data_result);
?>

<html>
	<head>
	<title>Demo</title>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.4.4/jquery.min.js" type="text/javascript"></script>
	<script>
		<?php echo $data_result;?>
	</script>
	</head>
	
	<body>

	</body>
</html>
