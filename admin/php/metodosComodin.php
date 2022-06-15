<?php

  function generaCadenaAlfanumerica($tamano){
    $alfanumericos_base = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    return (substr(str_shuffle($alfanumericos_base), 0, $tamano));
  }

  function quitaEstilos($cadena){
  	return preg_replace ('/<[^>]*>/', ' ', $cadena);
  }
  function quitaHipervinculo($cadena){
  	return preg_replace("/<a(.*)>(.*)<\/a>/i","$2",$cadena);
  }

  function dameLosPrimeros($cantidadCaracteres, $cadena){
  	return substr($cadena, 0, $cantidadCaracteres);
  }

  function dameLoQueHayDespuesDe($cantidadCaracteres, $cadena){
  	return substr($cadena, $cantidadCaracteres);
  }

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



  function sanear_string($string)
  {

      $string = trim($string);

      $string = str_replace(
          array('á', 'à', 'â', 'ä', '�', '�', '�', '�', '�'),
          array('a', 'a', 'a', 'a', 'a', 'A', 'A', 'A', 'A'),
          $string
      );

      $string = str_replace(
          array('�', '�', '�', '�', '�', '�', '�', '�'),
          array('e', 'e', 'e', 'e', 'E', 'E', 'E', 'E'),
          $string
      );

      $string = str_replace(
          array('�', '�', '�', '�', '�', '�', '�', '�'),
          array('i', 'i', 'i', 'i', 'I', 'I', 'I', 'I'),
          $string
      );

      $string = str_replace(
          array('�', '�', '�', '�', '�', '�', '�', '�'),
          array('o', 'o', 'o', 'o', 'O', 'O', 'O', 'O'),
          $string
      );

      $string = str_replace(
          array('�', '�', '�', '�', '�', '�', '�', '�'),
          array('u', 'u', 'u', 'u', 'U', 'U', 'U', 'U'),
          $string
      );

      $string = str_replace(
          array('�', '�', '�', '�'),
          array('n', 'N', 'c', 'C',),
          $string
      );

      //Esta parte se encarga de eliminar otros caracteres extraños
      $string = str_replace(
         array(

              "#", "@", "|", "!",
              "$", "%", "&", "/",
              "(", ")", "?", "'", '"',
              "[", "^", "<code>", "]",
               "+", "}", "{", "�", "�",
               ">", "< ", ";", ",", ":"
  		),
          '',
          $string
      );


      return $string;
  }
  function sanear_comillas($string)
  {
      $string = trim($string);
      //Esta parte se encarga de eliminar cualquier comilla o comilla doble
      $string = str_replace(
         array(
              '"', "'"
  		),
          '',
          $string
      );

      return $string;
  }

  function validar_cargar_imagen($campo)
  {
  	$respuesta=true;
  	if ( !empty($_FILES[$campo.""]["name"]) ){
  		if ($_FILES[$campo.""]["size"]>1000000){
  			$errores['tamimagen']   = 'La imagen del trabajo tiene un peso mayor a 1MB';
  			if ( $_FILES[$campo.""]["type"] == "image/jpeg" ||$_FILES[$campo.""]["type"] == "image/pjpeg" || $_FILES[$campo.""]["type"] == "image/gif" || $_FILES[$campo.""]["type"] == "image/png"){
  			}else{
  				$errores['tipima']   = 'El archivo no corresponde a un formato de imagen permitido';
  			}
  			if ( !empty($error) ) {
  				$respuesta=$errores;
  			}
  		}
  	}else{
  		$respuesta=false;
  	}


      return $respuesta;
  }

  function cargar_imagen_en($ruta, $preposicion, $archivo_de_imagen){
  	//Ruta de la carpeta donde se guardar�n las imagenes
    $nombrearchivo ='fichero no soportado';
    if ( !empty($_FILES[$archivo_de_imagen.""]["name"]) ){


    	//Par�metros optimizaci�n, resoluci�n m�xima permitida
    	$max_ancho = 750;
    	$max_alto = 750;

    	if($_FILES[$archivo_de_imagen.""]['type']=='image/png' || $_FILES[$archivo_de_imagen.""]['type']=='image/jpeg' || $_FILES[$archivo_de_imagen.""]['type']=='image/gif'){


    		$medidasimagen= getimagesize($_FILES[$archivo_de_imagen.'']['tmp_name']);



    		//Si las imagenes tienen una resoluci�n y un peso aceptable se suben tal cual
    		if($medidasimagen[0] < 751 && $_FILES[$archivo_de_imagen.""]['size'] < 1000000){
    			$nombrearchivo=basename($preposicion.date("Y_m_d_H_i_s").$_FILES[$archivo_de_imagen.""]["name"]);
    			move_uploaded_file($_FILES[$archivo_de_imagen.""]['tmp_name'], $ruta.'/'.$nombrearchivo);

    		}
    		//Si no, se generan nuevas imagenes optimizadas
    		else {

    			$nombrearchivo=basename($preposicion.date("Y_m_d_H_i_s").$_FILES[$archivo_de_imagen.""]["name"]);

    			//Redimensionar
    			$rtOriginal=$_FILES[$archivo_de_imagen.""]['tmp_name'];

    			if($_FILES[$archivo_de_imagen.""]['type']=='image/jpeg'){
    				$original = imagecreatefromjpeg($rtOriginal);
    			}
    			else if($_FILES[$archivo_de_imagen.""]['type']=='image/png'){
    				$original = imagecreatefrompng($rtOriginal);
    			}
    			else if($_FILES[$archivo_de_imagen.""]['type']=='image/gif'){
    				$original = imagecreatefromgif($rtOriginal);
    			}


    			list($ancho,$alto)=getimagesize($rtOriginal);

    			$x_ratio = $max_ancho / $ancho;
    			$y_ratio = $max_alto / $alto;


    			if( ($ancho <= $max_ancho) && ($alto <= $max_alto) ){
    				$ancho_final = $ancho;
    				$alto_final = $alto;
    			}
    			elseif (($x_ratio * $alto) < $max_alto){
    				$alto_final = ceil($x_ratio * $alto);
    				$ancho_final = $max_ancho;
    			}
    			else{
    				$ancho_final = ceil($y_ratio * $ancho);
    				$alto_final = $max_alto;
    			}

    			$lienzo=imagecreatetruecolor($ancho_final,$alto_final);

    			imagecopyresampled($lienzo,$original,0,0,0,0,$ancho_final, $alto_final,$ancho,$alto);

    			imagedestroy($original);

    			$cal=8;

    			if($_FILES[$archivo_de_imagen.""]['type']=='image/jpeg'){
    				imagejpeg($lienzo,$ruta."/".$nombrearchivo);
    			}
    			else if($_FILES[$archivo_de_imagen.""]['type']=='image/png'){
    				imagepng($lienzo,$ruta."/".$nombrearchivo);
    			}
    			else if($_FILES[$archivo_de_imagen.""]['type']=='image/gif'){
    				imagegif($lienzo,$ruta."/".$nombrearchivo);
    			}

    		}

    	}
    }
  	return $nombrearchivo;
  }

  function cargar_imagen($archivo_de_imagen,$anteponer,$ruta_de_imagen,$destino_imagen){
  	//Ruta de la carpeta donde se guardar�n las imagenes


  	$info=pathinfo($archivo_de_imagen);

  	$nombrearchivo ='fichero no soportado';

  	//Par�metros optimizaci�n, resoluci�n m�xima permitida
  	$max_ancho = 750;
  	$max_alto = 750;


  		$medidasimagen= getimagesize($ruta_de_imagen);



  		//Si las imagenes tienen una resoluci�n y un peso aceptable se suben tal cual
  		if($medidasimagen[0] < 751 && $_FILES[$archivo_de_imagen]['size'] < 1000000){
  			$nombrearchivo=basename($anteponer.date("Y_m_d_H_i_s").$archivo_de_imagen);
  			move_uploaded_file($ruta_de_imagen, $destino_imagen.$nombrearchivo);

  		}
  		//Si no, se generan nuevas imagenes optimizadas
  		else {

  			$nombrearchivo=basename('trabajo'.date("Y_m_d_H_i_s").$archivo_de_imagen);

  			//Redimensionar
  			$rtOriginal=$ruta_de_imagen;

  			if($info['extension']=='jpg'||$info['extension']=='JPG'||$info['extension']=='jpeg'||$info['extension']=='JPEG'){
  				$original = imagecreatefromjpeg($rtOriginal);
  			}
  			else if($info['extension']=='png'||$info['extension']=='PNG'){
  				$original = imagecreatefrompng($rtOriginal);
  			}
  			else if($info['extension']=='gif'||$info['extension']=='GIF'){
  				$original = imagecreatefromgif($rtOriginal);
  			}


  			list($ancho,$alto)=getimagesize($rtOriginal);

  			$x_ratio = $max_ancho / $ancho;
  			$y_ratio = $max_alto / $alto;


  			if( ($ancho <= $max_ancho) && ($alto <= $max_alto) ){
  				$ancho_final = $ancho;
  				$alto_final = $alto;
  			}
  			elseif (($x_ratio * $alto) < $max_alto){
  				$alto_final = ceil($x_ratio * $alto);
  				$ancho_final = $max_ancho;
  			}
  			else{
  				$ancho_final = ceil($y_ratio * $ancho);
  				$alto_final = $max_alto;
  			}

  			$lienzo=imagecreatetruecolor($ancho_final,$alto_final);

  			imagecopyresampled($lienzo,$original,0,0,0,0,$ancho_final, $alto_final,$ancho,$alto);

  			imagedestroy($original);

  			$cal=8;

  			if($info['extension']=='jpg'||$info['extension']=='JPG'||$info['extension']=='jpeg'||$info['extension']=='JPEG'){
  				imagejpeg($lienzo,$destino_imagen.$nombrearchivo);
  			}
  			else if($info['extension']=='png'||$info['extension']=='PNG'){
  				imagepng($lienzo,$destino_imagen.$nombrearchivo);
  			}
  			else if($info['extension']=='gif'||$info['extension']=='GIF'){
  				imagegif($lienzo,$destino_imagen.$nombrearchivo);
  			}


  	}
  	return $nombrearchivo;
  }

 ?>
