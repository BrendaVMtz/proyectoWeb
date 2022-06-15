<?php

function tieneInyecciones($cadena)
{
	$tiene = false;
	$arrPalabrasInyeccion = array(
								"SELECT", "CONCAT", "INSERT", "UPDATE", "--",
								"SET", "LOAD", "CREATE", "DELETE", "DROP","SHOW",
								"USE", "DESCRIBE", "FLUSH","ALTER","mysqldump"
							);
	foreach ($arrPalabrasInyeccion as $palabra){
	
		if(strcasecmp ($cadena, $palabra)==0){
			$tiene = true;
		}
	}
    return $tiene;
}
?>
