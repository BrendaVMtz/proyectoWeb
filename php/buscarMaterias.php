<?php

// archivos necesarios
require_once '../admin/php/config.php';
require_once '../admin/php/conexion.php';
require_once '../admin/php/esUsuario.php';
require_once '../admin/php/metodosComodin.php';
// obtengo puntero de conexion con la db
$dbConn = conectar();

// traemos listado de semestres
$arrSemestres = array();
$query = "SELECT * FROM semestres ORDER BY nombre ASC";
$resultado = mysqli_query ($dbConn, $query);
while ( $row = mysqli_fetch_array ($resultado, MYSQLI_ASSOC)) 
{
    array_push( $arrSemestres,$row );
}



foreach ($arrSemestres as $semestre) 
{
	// traemos listado de materias
	$arrMaterias = array();

	$query = "SELECT * FROM materias where idSemestre = '".$semestre['idSemestre']."' ORDER BY nombre ASC ";
	$resultado = mysqli_query ($dbConn, $query);
	while ( $row = mysqli_fetch_array ($resultado, MYSQLI_ASSOC)) 
	{
		array_push( $arrMaterias,$row );
	}
	
	?>
		<div class="card-container">
			<div class="card-header">
				<h3 class="card-header__titulo">
				<?php echo $semestre['nombre']; ?>
				</h3>
			</div>
			<div class="card-grid">
				<?php foreach ($arrMaterias as $materia) 
				{
					// code...
					?>
					<a class="card" href="<?php echo $materia['link']; ?>">
						<div class="card__fondo"></div>
						<div class="card__contenido">
							<h4 class="card__titulo"><?php echo $materia['nombre']; ?></h3>
						</div>
					</a>
					<?php
				
				} //termina cada materia ?>

</div>
</div>

<?php
}// termina por cada semestre
mysqli_close($dbConn);
?>
