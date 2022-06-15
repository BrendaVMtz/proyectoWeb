<?php
session_start ();
// archivos necesarios
require_once '../admin/php/config.php';
require_once '../admin/php/conexion.php';
require_once '../admin/php/esUsuario.php';

// obtengo puntero de conexion con la db
$dbConn = conectar();

// vemos si el usuario quiere desloguar
if ( !empty($_GET['salir']) ) {
	// borramos y destruimos todo tipo de sesion del usuario
	session_unset();
	session_destroy();
}

// verificamos que no este conectado el usuario
if ( !empty( $_SESSION['usuario'] ) && !empty($_SESSION['password']) ) {
	$arrUsuario = esUsuario( $_SESSION['usuario'], $_SESSION['password'], $dbConn );
}


// si se envio el formulario
if ( !empty($_POST['submit']) ) {

	// definimos las variables
	if ( !empty($_POST['usuario']) ) 	$usuario 	= $_POST['usuario'];
	if ( !empty($_POST['password']) )	$password 	= $_POST['password'];

	// completamos la variable error si es necesario
	if ( empty($usuario) ) 	$error['usuario'] 		= 'Es obligatorio completar el nombre de usuario';
	if ( empty($password) ) $error['password'] 		= 'Es obligatorio completar la contraseï¿½a';

	// si no hay errores registramos al usuario
	if ( empty($error) ) {

		// verificamos que los datos ingresados corresopndan a un usuario
		if ( $arrUsuario = esUsuario($usuario,base64_encode($password),$dbConn) ) {
			if($arrUsuario['tipo']!="bloqueado"){
				// definimos las sesiones
				$_SESSION['usuario'] 	= $arrUsuario['usuario'];
				$_SESSION['password']	= $arrUsuario['password'];

				header('Location: ../admin/');
				die;
			}else{
				$error['esBloqueado'] 		= 'Tu cuenta se ha bloqueado por el uso indebido de la misma';
			}

		} else {
			$error['noExiste'] 		= 'El nombre de usuario o contrase&ntilde;a no coinciden';
		}

	}

}


?><!DOCTYPE html>
<html lang="es">
  <head>
      <meta charset="UTF-8">
      <meta name="agenda" content="width=device-width, initial-scale=1.0">
      <link rel="stylesheet" href="../StyleSheet/style.css">
      <link href="https://fonts.googleapis.com/css2?family=Montserrat&display=swap" rel="stylesheet">
      <link href="https://fonts.googleapis.com/css2?family=Lato:wght@300&display=swap" rel="stylesheet">
      <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@300&display=swap" rel="stylesheet">
      <link rel="icon" href="https://www.escom.ipn.mx/images/conocenos/escudoESCOM.png">
      <title>Biblioteca Virtual</title>
  </head>


    <body>
			<article class="main">
	      <header class="banner">
	        <div class="titulo">
	          <h1>BIBLIOTECA VIRTUAL DE ESCOM</h1>
	        </div>
	      </header>


        <!-- Nav Bar End -->
        <!-- Banner Start -->
        <div class="banner wow zoomIn" data-wow-delay="0.1s"  id="ingresar">
          <div id="espacio">
            &nbsp;
            </br>
            &nbsp;
          </div>
            <div class="container-fluid">
                <div class="section-header text-center wow zoomIn" data-wow-delay="0.1s">

                  <p>Bienvenido a la puerta trasera</p>
                  <h2>Al ingrear por este medio acepta todas las consecuencias de sus actos.</h2>
                </div>
            </div>
        </div>
        <!-- Banner End &nbsp; -->
        <!-- Price Start -->
        <div class="price">
          <div class="row">
              <div class="col-md-12 wow fadeInUp" data-wow-delay="0.5s">
                  <div class="price-item">
                      <div class="price-header">
                          <div class="price-title">
                              <h2>Introdusca sus datos por favor</h2>
                          </div>

                      </div>
                      <div class="price-header">



													<form action="ingresar.php" method="post">





														  <label for="usuario">



																<p align="center">

																<label for="usuario">Nombre</label>

																<br />

															<input name="usuario" type="text" value="<?php if ( ! empty($usuario) ) echo $usuario; ?>" />

														</p>

														  <p align="center">

															<label for="password">Contrase&ntilde;a</label>

															<br />

															<input name="password" type="password" value="<?php if ( ! empty($password) ) echo $password; ?>" />

														</p>

														<p align="center">

															<input name="submit" type="submit" value="Ingresar" />

														</p>



													</form>



													<p align="center">

													<?php

													  echo '<a href="index.html">Regresar a principal</a>';

													?>

													</p>



                      </div>
                    </div>
              </div>
          </div>

        </div>


        <!-- Price End -->
        <!-- Footer Start -->
        <div class="footer" id="footer">

        </div>
        <!-- Footer End -->



			</article>
    </body>

	<?php
	//cerramos conexion
	mysqli_close($dbConn);
	?>
</html>
