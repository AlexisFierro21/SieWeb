<?php
include ('../class.ezpdf.php');
$pdf = new Cezpdf('LETTER','landscape');

include ('../connection.php');
$link = mysql_connect ( $server, $userName, $password ) or die ( 'No se pudo conectar: ' . mysql_error () );
mySql_select_db ( $DB ) or die ( "No se pudo seleccionar DB" );

$alumno = $_REQUEST ['alumno'];

$pdf->selectFont('./fonts/Helvetica.afm');

$pdf->setStrokeColor(0,0,255);
$pdf->setLineStyle(3);

$origen = 35;
$w = 240; $h = 270;
$tw = 30;

$ral = mysql_fetch_array(mysql_query("Select concat(nombre, ' ', apellido_paterno, ' ', apellido_materno) from alumnos where alumno = $alumno;"));
$pdf->rectangle($origen,$origen+$h*2+5,$w*3,30);
$pdf->addText($origen+5,$origen+$h*2+12,16,"Alumno: $ral[0]");

for ($iC=2;$iC<=4;$iC++)
{
	if ($iC<4)
	{ $x = $origen+($w*($iC-1)); $y = $origen;}
	else	
	{ $x = $origen+($w*($iC-4)); $y = $h+$origen;}
	
	$pdf->rectangle($x,$y,$w,$h);
	$pdf->rectangle($x,$y+$h-$tw,$w,$tw);
	
	if ($iC>3) $b =$iC-3+2; else $b=$iC+3+2;
	 
	$sql = "select nombre, (select color from preceptoria_boleta where alumno = $alumno and id_area = a.id_area_valor and id_aspecto = 0), id_area_valor,
			  (select observaciones from preceptoria_boleta where alumno = $alumno and id_area = a.id_area_valor and id_aspecto = 0)
			from areas_valor a where area = $b and ciclo = (Select periodo from parametros) order by area;";
	
	
	$rsa = mysql_fetch_array(mysql_query($sql));


	$pdf->addText($x+40,$y+$h-$tw+8,18,$rsa[0]);
	
	switch ($rsa[1]){
		case 1: 	$pdf->setColor(0,1,0); break;
		case 2: 	$pdf->setColor(0,0,255); break;
		case 3:		$pdf->setColor(0,255,0); break;		
		default:	$pdf->setColor(0,0,255);
	}
	
	$pdf->filledRectangle($x+5,$y+$h-$tw+5,20,20);
	$pdf->setColor(0,0,0);
	
	$a = $rsa[2];
	
	$sql = "select nombre, (select color from preceptoria_boleta where alumno = $alumno and id_aspecto = a.id)
			from areas_valor_aspectos a
			where id_area_valor = $a order by aspecto;";
	

	$rsb = mysql_query($sql);
	$mas = 50;
	
	while ($row=mysql_fetch_array($rsb))
	{
		switch ($row[1]){
			case 1: 	$pdf->setColor(1,0,0); break;
			case 2: 	$pdf->setColor(0,0,255); break;
			case 3:		$pdf->setColor(0,255,0); break;		
			default:	$pdf->setColor(0,0,255);
		}
		
		$pdf->filledRectangle($x+5,$y+$h-$mas,10,10);
		$pdf->setColor(0,0,0);
		
		$pdf->addText($x+25,$y+$h-$mas,12, $row[0]);
		$mas = $mas + 17;		
	}
		
	$mas = $mas + 17;		
	$pdf->addText($x+5,$y+$h-$mas,15,"Area de oportunidad:");

	$nme = $rsa[3];
	$l = 55;

	unset ( $name );
	while ( strlen ( $nme ) > $l ) {
		$n = substr ( $nme, 0, $l - 1 );
		if (! $p = strripos ( $n, " " )) $p = $l - 1;
		
		$name [] = substr ( $nme, 0, $p );
		$nme = substr ( $nme, $p, strlen ( $nme ) - 1 );
	}
	
	$name [] = $nme;
	
	foreach ($name as $n)
	{ 
		$mas = $mas + 17;
		$pdf->addText($x+5,$y+$h-$mas,10, $n);
	}
}
$pdf->ezStream();

$opc = $_REQUEST ['opc'];

$action = $_REQUEST ['action'];

?>