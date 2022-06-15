<?php

function conectar () {
	
	$db_con = new mysqli(DB_SERVER, DB_USER, DB_PASS, DB_NAME);
	/* comprueba la conexión */
	if (mysqli_connect_errno()) {
		printf("Conecci&oacute;n fallida: %s\n", mysqli_connect_error());
		exit();
	}  
	return $db_con; 


}

?>