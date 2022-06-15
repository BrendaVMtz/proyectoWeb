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
if ( empty($arrUsuario) || !$arrUsuario['tipo'] == 'admin') {
	header( 'Location: ../WebPages/index.html' );
	die;
}

// borramos una categoria
if ( !empty($_GET['del']) ) {

    $query  = "DELETE FROM semestres WHERE idSemestre = {$_GET['del']}";
    $result = mysqli_query($dbConn, $query);

    header( 'Location: semestres.php?dele=true' );
    die;

}

// agregamos una categoria en la db
// si se envio el formulario
if ( !empty($_POST['submit']) ) {

    // definimos las variables
    if ( !empty($_POST['nombre']) )     $nombre     = $_POST['nombre'];

    // completamos la variable error si es necesario
    if ( empty($nombre) )   $error['nombre']   = 'Es obligatorio completar el nombre del semestre';

    // si no hay errores registramos al usuario
    if ( empty($error) ) {

        // inserto los datos de registro en la db
        $query  = "INSERT INTO semestres (nombre) VALUES ('$nombre');";
        $result = mysqli_query($dbConn, $query);

        header( 'Location: semestres.php?add=true' );
        die;

    }

}

if ( !empty($_POST['submitEdit']) ) {

    // definimos las variables
    if ( !empty($_POST['nombre']) )         $nombre         = $_POST['nombre'];
    if ( !empty($_POST['idSemestre']) )    $idSemestre   = $_POST['idSemestre'];

    // completamos la variable error si es necesario
    if ( empty($nombre) )     $error['nombre']   = 'Es obligatorio completar el nombre del semestre';
    if ( empty($idSemestre) )  $error['idSemestre']    = 'Falta la ID del semestre';

    // si no hay errores registramos al usuario
    if ( empty($error) ) {

        // inserto los datos de registro en la db
        $query  = "UPDATE semestres set nombre = '$nombre' WHERE idSemestre = $idSemestre";
        $result = mysqli_query($dbConn, $query);

        header( 'Location: semestres.php?edit=true' );
        die;

    }

}



// traemos listado de semestres
$arrSemestres = array();

$query = "SELECT * FROM semestres ORDER BY nombre ASC";
$resultado = mysqli_query ($dbConn, $query);
while ( $row = mysqli_fetch_array ($resultado, MYSQLI_ASSOC)) {
    array_push( $arrSemestres,$row );
}

// si tenemos una categoria puntual
if ( !empty($_GET['id']) ) {
    // traemos una categoria
    $query = "SELECT * FROM semestres WHERE idSemestre = {$_GET['id']}";
    $resultado = mysqli_query ($dbConn, $query);
    $row = mysqli_fetch_array ($resultado, MYSQLI_ASSOC);
}

?>
<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>Panel de Control del Sitio de Freak 33 - semestres</title>
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
							<span class="ico-circle"><i class="bi bi-journal-richtext"></i></span>
						</div>
						<div class="counter-num">
							<p data-purecounter-start="0" data-purecounter-end="<?php echo (count($arrSemestres));?>" data-purecounter-duration="1" class="counter purecounter"></p>
							<span class="counter-text">semestres Existentes</span>
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
                  El semestre se agreg&oacute; con &eacute;xito.
                </div>

              <?php } elseif ( !empty($_GET['dele']) ) { ?>
                <div class="alert alert-info" role="alert">
                  El semestre a ha sido borrado con &eacute;xito.
                </div>

              <?php } elseif ( !empty($_GET['edit']) ) { ?>
                <div class="alert alert-info" role="alert">
                  El semestre ha sido editado con &eacute;xito.
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

                <?php if ( empty($_GET['id']) ) { ?>
                    <div>
                        <h3 id="add">Agregar nuevo semestre </h3>
                        <?php if (!empty($error)) { ?>
                            <ul>
                            <?php foreach ($error as $mensaje) { ?>
                                <li><?php echo $mensaje; ?></li>
                            <?php } ?>
                            </ul>
                        <?php } ?>
												<form action="semestres.php" method="post">

						                <p>
						                    <label for="nombre">Nombre del semestre</label>
						                    <br />
						                    <input name="nombre" type="text" value="" />
						                </p>
						                <p>
						                    <input name="submit" type="submit" value="Agregar" />
						                </p>
						            </form>
                    </div>
                <?php } ?>

                <?php if ( !empty($_GET['id']) ) { ?>
                    <div style="background-color:#ff8800;padding:5px; margin-top:10px;">
                        <h3 id="add">Editar semestre</h3>
                        <?php if (!empty($error)) { ?>
                            <ul>
                            <?php foreach ($error as $mensaje) { ?>
                                <li><?php $mensaje ?></li>
                            <?php } ?>
                            </ul>
                        <?php } ?>
												<form action="semestres.php" method="post">
						                <p>
						                    <label for="nombre">Nombre del semestre</label>
						                    <br />
						                    <input name="nombre" type="text" value="<?php echo $row['nombre']; ?>" />
						                </p>
						                <p>
						                    <input name="idSemestre" type="hidden" value="<?php echo $row['idSemestre']; ?>" />
						                    <input name="submitEdit" type="submit" value="Editar" />
																<input name="submitCancel" type="submit" value="Cancelar" />
						                </p>
						            </form>
                    </div>
                <?php } ?>
            	 <div>
                    <h3>Listado de semestres </h3>

                    <table class="table table-striped">
                      <thead>
                        <tr>
                          <th scope="col">#</th>
                          <th scope="col">Nombre</th>
                          <th scope="col">Opciones</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php foreach ($arrSemestres as $semestres) { ?>
                        <tr>
                          <th scope="row"><?php echo $semestres['idSemestre']; ?></th>
                          <td><?php echo $semestres['nombre']; ?></td>
                          <td><a href="semestres.php?id=<?php echo $semestres['idSemestre']; ?>">Editar</a> - <a href="javascript:confElim('semestres.php','<?php echo $semestres['idSemestre']; ?>');" >Borrar</a></td>
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
