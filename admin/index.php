<?php

// iniciamos session
session_start ();

// archivos necesarios
require_once 'php/config.php';
require_once 'php/conexion.php';
require_once 'php/esUsuario.php';



// obtengo puntero de conexion con la db
$dbConn = conectar();
// vemos si el usuario quiere desloguar

if ( !empty($_GET['salir']) ) {

	// borramos y destruimos todo tipo de sesion del usuario

	session_unset();

	session_destroy();


}

$arrUsuario = array();

// verificamos que no este conectado el usuario
if ( !empty( $_SESSION['usuario'] ) && !empty($_SESSION['password']) ) {
	$arrUsuario = esUsuario( $_SESSION['usuario'], $_SESSION['password'], $dbConn );
}

// verificamos que sea un admin
if ( empty($arrUsuario) || $arrUsuario['tipo'] == 'comun' || $arrUsuario['tipo'] == 'cliente' || $arrUsuario['tipo'] == 'integrante') {
	header( 'Location: ../WebPages/index.html' );
	die;
}

// traemos listado de materias
$arrmaterias = array();
$query = "SELECT materias.idmateria, materias.nombre FROM materias ORDER BY nombre ASC";
if ($resultado = mysqli_query ($dbConn, $query)){
	while ( $row = mysqli_fetch_array ($resultado, MYSQLI_ASSOC)) {
		array_push( $arrmaterias,$row );
	}
}

// traemos listado de semestres
$arrsemestres = array();
//$query = "SELECT idsemestre, valor FROM 'semestres' ORDER BY valor ASC";
$query = "SELECT * FROM semestres ORDER BY nombre ASC";
$resultado = mysqli_query ($dbConn, $query);
while ( $row = mysqli_fetch_array ($resultado, MYSQLI_ASSOC)) {
    array_push( $arrsemestres,$row );
}

// traemos listado de usuarios
$arrUsuarios = array();
$query = "SELECT * FROM `usuarios`
ORDER BY usuario ASC";
$resultado = mysqli_query ($dbConn, $query);
while ( $row = mysqli_fetch_assoc ($resultado)) {
	array_push( $arrUsuarios,$row );
}


?>
<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>Panel de Control de la BIBLIOTECA de Freak 33 - Index</title>
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
  <!--<script type="text/javascript" src="../ckeditor/ckeditor.js"></script>-->
</head>

<body>

  <!-- ======= Header ======= -->

    <?php
      include "header.php";
    ?>
  </header><!-- End Header -->

  <!-- ======= Hero Section ======= -->
  <div id="hero" class="hero route bg-image" style="background-image: url(assets/img/hero-bg.jpg)">
    <div class="overlay-itro"></div>
    <div class="hero-content display-table">
      <div class="table-cell">
        <div class="container">
          <!--<p class="display-6 color-d">Hello, world!</p>-->
          <h1 class="hero-title mb-4">Panel de Control de la BIBLIOTECA Freak 33</h1>
          <p class="hero-subtitle"><span class="typed" data-typed-items="Hola <?php echo $arrUsuario['usuario'];?>, No hay noticias nuevas"></span></p>
          <!-- <p class="pt-3"><a class="btn btn-primary btn js-scroll px-4" href="#about" role="button">Learn More</a></p> -->
        </div>
      </div>
    </div>
  </div><!-- End Hero Section -->

  <main id="main">

    <!-- ======= About Section ======= -->
    <section id="about" class="about-mf sect-pt4 route">
      <div class="container">
        <div class="row">
          <div class="col-sm-12">
            <div class="box-shadow-full">
              <div class="row">

                <h3>De la p&aacute;gina</h3>


								<button type="button" onclick="mandarAPagina('semestres.php');" class="btn btn-outline-primary" >semestres</button>
								<button type="button" onclick="mandarAPagina('materias.php');" class="btn btn-outline-primary" >materias</button>
								<button type="button" onclick="mandarAPagina('libros.php');" class="btn btn-outline-primary" >libros</button>


              </div>
            </div>
          </div>
        </div>
      </div>
    </section><!-- End About Section -->



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
