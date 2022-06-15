<?php

function esCoreroUsuario ( $correo, $password, $conexion ) {

	// verifica que esten los dos campos completos.
	if ($correo=='' || $password=='') return false;

	// busqueda de los datos de usuarios para loguear.
	$query = "SELECT idUsuario, usuario, password, email, tipo, carpeta FROM `usuarios` WHERE email = '$correo'";
	$resultado = mysqli_query ($conexion, $query);
	$row = mysqli_fetch_array ($resultado);
	$password_from_db = $row ['password'];
	unset($query);

	// verifica que el pass enviado sea igual al pass de la db.
	if ( $password_from_db == $password ) {
		return $row;
	} else return false;


}

?>
