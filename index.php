<?php
// WEB PARA MOSTRAR LAS FARMACIAS DE GUARDIA A PARTIR DE DOS FICHEROS EN TEXTO PLANO
// EN UNA WEB REFERENCIADA A PARTIR DE QR
// FICHERO: turnos.txt, que contiene FECHAS;NUMERO_FARMACIA;
// FICHERO: farmacias.txt, el que contiene NUMERO_FARMACIA;NOMBRE_FARMACIA;DIRECCION;TELEFONO;DISTANCIA_A_PIE;DISTANCIA_COCHE;URL_GOOGLE_MAPS

// LAS GUARDIAS CAMBIAN A UNA HORA FIJA CADA DIA Y DURAN 24 HORAS
// Al indicarse los dias de la guardia, hay que tener en cuenta si estamos antes o despues de la hora de cambio de guardia
// En la franja entre las 00:00AM y la hora de cambio la guardia corresponde a la farmacia del dia anterior
// Mientras que a partir de la hora de cambio corresponderia a la farmacia de la fecha del dia actual

//FARMACIA QUE OFRECE LA INFORMACION
$f_nombre = "";
$f_direccion = "";
$localidad = "";
$provincia = "";
$f_web = "";
$f_maps = "";
$f_telefono = "";
$f_whatsapp = "";

$f_url_informacion = ""; //url de esta web
$f_url_qr = "qr.png"; // url fichero qr

$hora_cambio_guardia = 9; // valor numerico 0-23 de la hora en la que se realiza el cambio de guardia
if ($hora_cambio_guardia<12) $hora_cambio_guardia_am_pm="AM"; else $hora_cambio_guardia_am_pm="PM"; //AM-PM

// TELEFONOS DE INTERES
$t_urgencias = "112";
$t_taxi = "";
$t_centro_salud = "";
$t_hospital = "";
$t_policia = "";

// BUSQUEDA FARMACIA SEGUN HORA Y DIA DEL MOMENTO
$hora = date("H");  // VARIABLE hora 24 horas, con valores: 0-23
if ($hora<$hora_cambio_guardia) // SI ES MENOR LA GUARDIA CORRESPONDE A LA FARMACIA DEL DIA ANTERIOR
{
 $fecha = date("d/m/Y",strtotime("- 1 days")); // GUARDIA CORRESPONDE A LA FARMACIA DEL DIA ANTERIOR
 $fecha_hasta = date("d/m/Y");
}
else // GUARDIA CORRESPONDE A LA FARMACIA DEL DIA ACTUAL
{
 $fecha = date("d/m/Y");
 $fecha_hasta = date("d/m/Y",strtotime("+ 1 days"));
}

// BUSQUEDA NUMERO DE FARMACIA SEGUN FECHA - $fecha
$turnos = 'turnos.txt';
$c_turnos = file_get_contents($turnos);
$p_turnos = preg_quote($fecha, '/');
$p_turnos = "/^.*$p_turnos.*\$/m";

if(preg_match_all($p_turnos, $c_turnos, $matches)){
 $match = implode($matches[0]);
 $str_n_farmacia = (explode(";",$match));
}
$numero_farmacia = $str_n_farmacia[1];

// BUSQUEDA DATOS DE FARMACIA SEGUN NUMERO - $numero_farmacia
$farmacias = 'farmacias.txt';
$c_farmacias = file_get_contents($farmacias);
$p_farmacias = preg_quote($numero_farmacia, '/');
$p_farmacias = "/^.*$p_farmacias.*\$/m";
if(preg_match_all($p_farmacias, $c_farmacias, $matches2)){
 $match2 = implode($matches2[0]);
 $str_nombre_farmacia = (explode(";",$match2));
}

// VALORES A MOSTRAR
$fecha_real = date("d/m/Y");
$fecha_hasta;
$nombre_farmacia = $str_nombre_farmacia[1];
$direccion_farmacia = $str_nombre_farmacia[2];
$telefono_farmacia = $str_nombre_farmacia[3];
$distancia_pie = $str_nombre_farmacia[4];
$distancia_coche = $str_nombre_farmacia[5];
$url_google_maps_farmacia = $str_nombre_farmacia[6];
?>

<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Farmacia de Guardia en <?php echo $localidad; ?></title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="stile.css" rel="stylesheet" />
</head>
<body>

<main>
	<header><h1>Farmacia de guardia hoy <?php echo $fecha_real; ?>, en <?php echo $localidad; ?> <font size=4><br>(Hasta las <?php echo $hora_cambio_guardia; ?>:00 <?php echo $hora_cambio_guardia_am_pm; ?> del <?php echo $fecha_hasta; ?>)</font></h1></header>

	<section>
	<article class="farmacia"><h2><?php echo $nombre_farmacia; ?></h2>
		<br><?php echo $direccion_farmacia; ?>
		<br><?php echo $localidad; ?> (<?php echo $provincia; ?>)
		<br><b><?php echo $telefono_farmacia; ?></b>
	</article>

	<article><h2>INFORMACIÓN DE ACCESO</h2>
		<br>La distancia a pie desde <?php echo $f_nombre; ?> es de <b><?php echo $distancia_pie; ?></b>
		<br>
		<br>La distancia en coche desde <?php echo $f_nombre; ?> es de <b><?php echo $distancia_coche; ?></b>
	</article>

	<article><h2>UBICACIÓN EN GOOGLE MAPS</h2>
	  	<center><a href="<?php echo $url_google_maps_farmacia ?>"><img src="google_maps.svg" width="30%"></a></center>
	</article>
	<article>
		<h2>TELEFONOS INTERÉS</h2>
		<br>URGENCIAS: <b><?php echo $t_urgencias; ?></b>
		<br>TAXI: <b><?php echo $t_taxi; ?></b>
		<br>CENTRO SALUD LOCALIDAD: <b><?php echo $t_centro_salud; ?></b>
		<br>HOSPITAL CERCANO: <b><?php echo $t_hospital; ?></b>
		<br>POLICIA LOCAL: <b><?php echo $t_policia; ?></b>
	</article>
	</section>

	<aside>
		<h3>CÓDIGO QR</h3>
		<p>
			<br>Guarde este código QR o esta dirección, para futuras consultas:
			<br>
			<br>
			<center><a href="<?php echo $f_url_informacion; ?>"><?php echo $f_url_informacion; ?></a></center>
			<br>
			<img src="<?php echo $f_url_qr; ?>" width="100%">
		</p>
	</aside>

	<footer><h5>Información ofrecida por:
	<br><b><a href="<?php echo $f_web; ?>"><?php echo $f_nombre; ?></a> <?php echo $f_direccion; ?>, <?php echo $localidad; ?>, <?php echo $provincia; ?> (<a href="<?php echo $f_maps; ?>">MAPS</a>) Teléfono: <?php echo $f_telefono; ?> - WhatsApp(Solo texto): <?php echo $f_whatsapp; ?></b>
</main>
</body>
</html>
