<?php

// iniciamos session
session_start ();

// archivos necesarios
require_once 'php/config.php';
require_once 'php/conexion.php';
require_once 'php/esUsuario.php';
require_once 'php/metodosComodin.php';



// obtengo puntero de conexion con la db
$dbConn = conectar();

$arrUsuario = array();

// verificamos que no este conectado el usuario
if ( !empty( $_SESSION['usuario'] ) && !empty($_SESSION['password']) ) {
	$arrUsuario = esUsuario( $_SESSION['usuario'], $_SESSION['password'], $dbConn );
}

// verificamos que sea un admin
if ( empty($arrUsuario) || !$arrUsuario['tipo'] == 'superadmin') {
	header( 'Location: ../index.php' );
	die;
}


// borramos un usuario
if ( !empty($_GET['del']) ) {
	elimina_directorio($_GET['del'],$dbConn);
	$query  = "DELETE FROM `usuarios` WHERE idUsuario = {$_GET['del']}";
	$result = mysqli_query($dbConn, $query);
	header( 'Location: usuarios.php?dele=true' );
	die;

}




// aprobamos como admin
if ( !empty($_GET['adm']) ) {

	$query  = "UPDATE `usuarios` set tipo = 'admin' WHERE idUsuario = {$_GET['adm']}";
	$result = mysqli_query($dbConn, $query);
	crea_directorio($_GET['adm'], $dbConn);
	header( 'Location: usuarios.php?aprobar=true' );
	die;
}

// aprobamos como superadmin
if ( !empty($_GET['sad']) ) {

	$query  = "UPDATE `usuarios` set tipo = 'superadmin' WHERE idUsuario = {$_GET['sad']}";
	$result = mysqli_query($dbConn, $query);
	crea_directorio($_GET['sad'], $dbConn);
	header( 'Location: usuarios.php?aprobar=true' );
	die;
}

// aprobamos como integrante activo
if ( !empty($_GET['blo']) ) {

	$query  = "UPDATE `usuarios` set tipo = 'bloqueado' WHERE idUsuario = {$_GET['blo']}";
	$result = mysqli_query($dbConn, $query);
	elimina_directorio($_GET['blo'],$dbConn);
	header( 'Location: usuarios.php?aprobar=true' );
	die;
}



// aprobamos como comun
if ( !empty($_GET['com']) ) {

	$query  = "UPDATE `usuarios` set tipo = 'comun' WHERE idUsuario = {$_GET['com']}";
	$result = mysqli_query($dbConn, $query);
	$resultado=elimina_directorio($_GET['com'],$dbConn);
	header( 'Location: usuarios.php?aprobar=true&'.$resultado );
	die;
}




// traemos listado de usuarios
$arrUsuarios = array();
$query = "SELECT * FROM `usuarios`
ORDER BY usuario ASC";
$resultado = mysqli_query ($dbConn, $query);
while ( $row = mysqli_fetch_assoc ($resultado)) {
	array_push( $arrUsuarios,$row );
}



// traemos listado de proyectos
$arrProyectos = array();
$query = "SELECT * FROM proyectos ORDER BY idProyecto DESC";
if ($arrUsuario['tipo'] != 'superadmin'){$query = "SELECT * FROM proyectos WHERE idUsuario = '".$arrUsuario['idUsuario']."' ORDER BY idProyecto DESC";}
if ($resultado = mysqli_query ($dbConn, $query)){
	while ( $row = mysqli_fetch_array ($resultado, MYSQLI_ASSOC)) {
    	array_push( $arrProyectos,$row );
	}
}

// traemos listado de servicios
$arrServicios = array();
//$query = "SELECT idServicio, valor FROM 'servicios' ORDER BY valor ASC";
$query = "SELECT * FROM servicios ORDER BY valor ASC";
$resultado = mysqli_query ($dbConn, $query);
while ( $row = mysqli_fetch_array ($resultado, MYSQLI_ASSOC)) {
    array_push( $arrServicios,$row );
}

// si tenemos una usurio puntual
if ( !empty($_GET['id']) ) {
	// traemos una categoria
	$query = "SELECT * FROM `usuarios` WHERE idUsuario = {$_GET['id']}";
	$resultado = mysqli_query ($dbConn, $query);
	$row = mysqli_fetch_assoc ($resultado);
}



?>
<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>Panel de Control del Sitio de Freak 33 - Usuarios</title>
  <meta content="" name="description">
  <meta content="" name="keywords">

  <!-- Favicons -->
  <link href="assets/img/favicon.png" rel="icon">
  <link href="assets/img/apple-touch-icon.png" rel="apple-touch-icon">

  <!-- Vendor CSS Files -->
  <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link href="assets/vendor/glightbox/css/glightbox.min.css" rel="stylesheet">
  <link href="assets/vendor/swiper/swiper-bundle.min.css" rel="stylesheet">

  <!-- Template Main CSS File -->
  <link href="assets/css/style.css" rel="stylesheet">

  <!-- =======================================================
  * Template Name: DevFolio - v4.3.0
  * Template URL: https://bootstrapmade.com/devfolio-bootstrap-portfolio-html-template/
  * Author: BootstrapMade.com
  * License: https://bootstrapmade.com/license/
  ======================================================== -->
  <script type="text/javascript" src="../js/control.js"></script>
  <script type="text/javascript" src="ckeditor/ckeditor.js"></script>
</head>

<body>

  <!-- ======= Header ======= -->

    <?php
      include "header.php";
    ?>
  </header><!-- End Header -->

	<!-- ======= Counter Section ======= -->
	<div class="section-counter paralax-mf bg-image" style="background-image: url(assets/img/counters-bg.jpg)">
		<div class="overlay-mf"></div>
		<div class="container position-relative">
			<div class="row">

				<div class="col-sm-3 col-lg-3">
					<div class="counter-box pt-4 pt-md-0">
						<div class="counter-ico">
							<span class="ico-circle"><i class="bi bi-people"></i></span>
						</div>
						<div class="counter-num">
							<p data-purecounter-start="0" data-purecounter-end="<?php echo (count($arrUsuarios));?>" data-purecounter-duration="1" class="counter purecounter"></p>
							<span class="counter-text">Usuarios Existentes</span>
						</div>
					</div>
				</div>

			</div>
		</div>
	</div><!-- End Counter Section -->

  <main id="main">



    <!-- ======= Blog Single Section ======= -->
    <section class="blog-wrapper sect-pt4" id="blog">
      <div class="container">
        <div class="row">
          <div class="col-md-12">
            <div class="post-box">

              <?php if ( !empty($_GET['add']) ) { ?>
                <div class="alert alert-info" role="alert">
                  El usuario se agreg&oacute; con &eacute;xito.
                </div>

              <?php } elseif ( !empty($_GET['dele']) ) { ?>
                <div class="alert alert-info" role="alert">
                  El usuario a ha sido borrado con &eacute;xito.
                </div>

              <?php } elseif ( !empty($_GET['edit']) ) { ?>
                <div class="alert alert-info" role="alert">
                  El usuario ha sido editado con &eacute;xito.
                </div>

              <?php } ?>

              <?php if (!empty($error)) { ?>

                  <?php foreach ($error as $msjError) { ?>
                    <div class="alert alert-danger" role="alert">
                      <?php echo $msjError ?>
                    </div>

                  <?php } ?>

              <?php } ?>




              <div class="article-content">


            	 <div>
                    <h3>Listado de Usuarios </h3>

                    <table class="table table-striped">
                      <thead>
                        <tr>
                          <th scope="col">#</th>
                          <th scope="col">Usuario</th>
													<th scope="col">Correo y<br> Password</th>
													<th scope="col">Tipo</th>
                          <th scope="col">Opciones</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php foreach ($arrUsuarios as $usuario) { ?>
                        <tr>
                          <th scope="row"><?php echo $usuario['idUsuario']; ?></th>
                          <td> <a href="usuario.php?id=<?php echo $usuario['idUsuario']; ?>"> <img src="<?php echo $usuario['foto']; ?>" width="50" height="50"> <br> <?php echo $usuario['usuario']; ?> </a></td>
													<td><?php echo $usuario['email']; ?> <br> <?php echo base64_decode($usuario['password']); ?> </td>
													<td><?php echo $usuario['tipo']; ?></td>
                          <td><a href="usuarios.php?com=<?php echo $usuario['idUsuario']; ?>">Comun</a> <br> <a href="usuarios.php?adm=<?php echo $usuario['idUsuario']; ?>">Administrador</a> <br> <a href="usuarios.php?sad=<?php echo $usuario['idUsuario']; ?>">Superadministrador</a> <br>  <a href="usuarios.php?blo=<?php echo $usuario['idUsuario']; ?>">Bloquear</a> <br> <a href="javascript:confElim(&apos;usuarios.php&apos;,<?php echo $usuario['idUsuario']; ?>);">Borrar</a></td>
                        </tr>
                        <?php
                        }
                         ?>


                      </tbody>
                    </table>

                </div>
              </div>
            </div>



          </div>
        </div>
      </div>
    </section><!-- End Blog Single Section -->

  </main><!-- End #main -->

  <!-- ======= Footer ======= -->


  <?php
  	include "footer.php";
  ?>
  <!-- End  Footer -->

  <div id="preloader"></div>
  <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

  <!-- Vendor JS Files -->
  <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="assets/vendor/glightbox/js/glightbox.min.js"></script>
  <script src="assets/vendor/php-email-form/validate.js"></script>
  <script src="assets/vendor/purecounter/purecounter.js"></script>
  <script src="assets/vendor/swiper/swiper-bundle.min.js"></script>
  <script src="assets/vendor/typed.js/typed.min.js"></script>

  <!-- Template Main JS File -->
  <script src="assets/js/main.js"></script>
  <?php
  //cerramos conexion
  mysqli_close($dbConn);
  ?>
</body>

</html>
