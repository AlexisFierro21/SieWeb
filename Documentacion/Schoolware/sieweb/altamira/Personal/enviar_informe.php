<?php
include ('../connection.php');
include ('../../../repositorio/class.ezpdf.php');
require('../../../repositorio/phpMailer/class.phpmailer.php');

$link = mysql_connect ( $server, $userName, $password ) or die ( 'No se pudo conectar: ' . mysql_error () );
mysql_set_charset('utf8',$link);
mySql_select_db ( $DB ) or die ( "No se pudo seleccionar DB" );

$alumno = $_REQUEST['alumno'];

//Enviar correos electronicos
$hostSMTP="smtp.gmail.com";
$smtpPrefix="tls";
$smtpPort=587;
//$smtpAuth="true";
$smtpDebug=2;

$mailUser="entrevistas@e-altamira.edu.mx";
$mailFrom="entrevistas@e-altmira.edu.mx";
$mailBCC="entrevistas@e-altamira.edu.mx";
$mailBCC="salvador.chavez@colmenares.org.mx";
$mailPass="2t3o0r1r1e4_";
$mailSender="Informes Altamira - Moctezuma";

mysql_query("SET character_set_results = 'utf8', character_set_client = 'utf8', character_set_connection = 'utf8', character_set_database = 'utf8', character_set_server = 'utf8'");
	
	$email_padre="";
	$email_madre="";
	$email_prec="";
	$nombreprec="";
	$nombreAl="";
	$n="";
	$sqlt = mysql_query ("select a.nombre,
				e_mail_padre, e_mail_madre, p.e_mail, concat(p.nombre, ' ', p.apellido_paterno, ' ', p.apellido_materno) as personal,
				concat(a.nombre, ' ', a.apellido_paterno, ' ', a.apellido_materno) as nomalm
				from alumnos a inner join familias f  on f.familia = a.familia
				inner join personal p on p.empleado = a.preceptor
				where alumno=$alumno ",$link) or die ("select a.nombre,
				e_mail_padre, e_mail_madre, p.e_mail, concat(p.nombre, ' ', p.apellido_paterno, ' ', p.apellido_materno) as personal,
				concat(a.nombre, ' ', a.apellido_paterno, ' ', a.apellido_materno) as nomalm
				from alumnos a inner join familias f  on f.familia = a.familia
				inner join personal p on p.empleado = a.preceptor
				where alumno=$alumno ".mysql_error());
	$ok=0;
while($ro=mysql_fetch_array($sqlt))
	  {
	  	$email_padre=$ro['e_mail_padre'];
		$email_madre=$ro['e_mail_madre'];
		$email_prec=$ro['e_mail'];
		$nombreprec=$ro['personal'];
		$nombreAl=$ro['nomalm'];
		$n=$ro['nombre'];
		$ok=1;
	  }
	  
//Crea mail
if ($ok){
		$mail=new PHPMailer();
		$mail->IsSMTP();
		$mail->PluginDir="../phpMailer/";
		$mail->CharSet="Latin1";
		$mail->Mailer="smtp";
		$mail->Host=$hostSMTP;
		$mail->SMTPAuth=true;
		$mail->SMTPSecure=$smtpPrefix;
		$mail->Port=$smtpPort;
		$mail->Username=$mailUser;
		$mail->Password=$mailPass;
		$mail->From=$mailFrom;
		$mail->FromName=$mailSender;
		$mail->IsHTML(true);
		$mail->AddAttachment('../im/logo.jpg', 'logo.jpg');
		$pdf = new Cezpdf('LETTER','portrait');
		$pdf->selectFont('../fonts/Helvetica.afm');
		$pdf->setStrokeColor(0,(27/255),(145/255));
		$pdf->setLineStyle(3);
		$pdf->addJpegFromFile('../im/logo.jpg',0,708,615);
		
$pdf = new Cezpdf('LETTER','landscape');
class Color{
	private $R;
	private $G;
	private $B;
	
	function Color($Ra, $Ga, $Ba){
		$this->R=$Ra;
		$this->G=$Ga;
		$this->B=$Ba;
	}
	function toString(){
		return "R:".$this->R." G:".$this->G." B:".$this->B;
	}
	function R()
	{	return $this->R;}
	function G()
	{	return $this->G;}
	function B()
	{	return $this->B;}
}
$colores=array();

$link = mysql_connect ( $server, $userName, $password ) or die ( 'No se pudo conectar: ' . mysql_error () );
mysql_set_charset('utf8',$link);
mySql_select_db ( $DB ) or die ( "No se pudo seleccionar DB" );

$alumno = $_REQUEST['alumno'];

$pdf->selectFont('../fonts/Helvetica.afm');

$pdf->setStrokeColor(0,(27/255),(145/255));
$pdf->setLineStyle(3);

$origen = 35;
$w = 240; $h = 270;
$tw = 30;

//Datos de alumno y calificaciones
$result=mysql_query("select periodo from parametros",$link)or die(mysql_error());
$row = mysql_fetch_array($result);
$periodo_actual=$row["periodo"];

$result=mysql_query("select * from alumnos,parametros where alumno='$alumno' and activo = 'A' and nuevo_ingreso <> 'P'",$link)or die(mysql_error());

$row = mysql_fetch_array($result);
$seccion = $row["seccion"];
$grado = $row["grado"];
$grad = $row["grado"];
$grupo = $row["grupo"];
$sexo = $row["sexo"];
$nombre=$row["nombre"];
$apellidop= $row["apellido_paterno"];
$apellidom= $row["apellido_materno"];

$grado="".$row["grado"]."-".$row["grupo"]."";
if ($sexo=='F') $alumn="Alumna";
else $alumn="Alumno";
$prcp = $row['preceptor'];

$nombre_seccion=mysql_result(mysql_query("select nombre from secciones where seccion ='$seccion' and ciclo = '$periodo_actual'",$link),0,0);

$preceptor=mysql_result(mysql_query("select concat(nombre, ' ', apellido_paterno, ' ', apellido_materno) from personal where empleado=$prcp;",$link),0,0);

$coord=mysql_result(mysql_query("select concat(nombre, ' ', apellido_paterno, ' ', apellido_materno) from personal where empleado=(select titular from grupos where seccion=$seccion and grado=$grad and grupo='$grupo' and ciclo = '$periodo_actual');",$link),0,0);
		
	
$sql_titulos=mysql_query("select * from boletas_web_2 where seccion = '$seccion' and grado = '$grado'",$link)or die(mysql_error());
$row_titulos = mysql_fetch_array($sql_titulos);
$numCols=$row_titulos["ultima_columna"];// n�mero de columnas en calificaciones 
//if($numCols>10)	AQUI
//	$numCols=10;

if($numCols>18)
	$numCols=18;
	
$periodo=$row_titulos["periodo"];

$columnas_sombreadas=$row_titulos["columnas_sombreadas"];;
$renglones_sombreados=$row_titulos["renglones_sombreados"];
$consigna=$row_titulos["consigna"];
$titulo= array(array($row_titulos["titulo_1"],$row_titulos["posicion_inicial_1"],$row_titulos["posicion_final_1"]),
				array($row_titulos["titulo_2"],$row_titulos["posicion_inicial_2"],$row_titulos["posicion_final_2"]),
				array($row_titulos["titulo_3"],$row_titulos["posicion_inicial_3"],$row_titulos["posicion_final_3"]),
				array($row_titulos["titulo_4"],$row_titulos["posicion_inicial_4"],$row_titulos["posicion_final_4"]),
				array($row_titulos["titulo_5"],$row_titulos["posicion_inicial_5"],$row_titulos["posicion_final_5"]));
$pie1=$row_titulos["pie_pagina_1"];
$pie2=$row_titulos["pie_pagina_2"];
$pie3=$row_titulos["pie_pagina_3"];


$result2=mysql_query("select * from boletas_web where alumno='$alumno' ",$link)or die(mysql_error());
$vacio="";
if (mysql_affected_rows($link)<=0) $vacio="S";
$countRow=0;
$comentario="";
$col1v=true;
$col2v=true;
$col3v=true;


//Inicia PDF
$pdf->addJpegFromFile("images/logobe.jpg",$origen,$origen+$h*1.7,$w*3,100);


$ral = mysql_fetch_array(mysql_query("Select concat(nombre, ' ', apellido_paterno, ' ', apellido_materno) from alumnos where alumno = $alumno;"));
$pdf->addText($origen,			$origen+$h*1.6+8,14,	utf8_decode("ALUMNO: $ral[0]"));
$pdf->addText($origen+$w*1.5,	$origen+$h*1.6+8,14,	utf8_decode("PRECEPTOR: $preceptor"));
$pdf->addText($origen,			$origen+$h*1.6-12,14,	utf8_decode("TITULAR: $coord"));
$pdf->addText($origen+$w*1.5,	$origen+$h*1.6-12,14,	utf8_decode("SECCION: $nombre_seccion"));

//$pdf->rectangle($origen,$origen,$w*1.18,$h*1.5);
//$pdf->rectangle($origen+$w*1.18,$origen,$w*1.82,$h*1.5);

if($numCols<12)
	$pdf->rectangle($origen,$origen+100+15,$w*3,$h*1.13-15);   //AQUI CUADRO AZUL DE CALIFICACIONES

$pdf->rectangle($origen,$origen,$w*3,90+10);

$pdf->addJpegFromFile("images/beb1.jpg",$origen-2, $origen+60+10,723, 32);

for ($iC=1;$iC<=8;$iC++)
{
/*	$sql = "select nombre, (select color from preceptoria_boleta where alumno = $alumno and id_area = a.id_area_valor and id_aspecto = 0), id_area_valor,
			  (select observaciones from preceptoria_boleta where alumno = $alumno and id_area = a.id_area_valor and id_aspecto = 0)
			from areas_valor a where area = $iC and ciclo = (Select periodo from parametros) order by area;";
*/

/*	$sql = "select nombre, (select avg(color) from preceptoria_boleta where alumno = $alumno and id_area = a.id_area_valor and id_aspecto <> 0), id_area_valor,
			  (select observaciones from preceptoria_boleta where alumno = $alumno and id_area = a.id_area_valor and id_aspecto = 0)
			from areas_valor a where area = $iC and ciclo = (Select periodo from parametros) order by area;";
*/

	$sql = "select (select avg(color) from preceptoria_boleta where alumno = $alumno and id_area = a.id_area_valor and id_aspecto <> 0), id_area_valor, (select observaciones from preceptoria_boleta where alumno = $alumno and id_area = a.id_area_valor and id_aspecto = 0) from areas_valor a where area = $iC and ciclo = (Select periodo from parametros) order by area;";
			
	$rsa = mysql_fetch_array(mysql_query($sql));

	$x = $origen+10; $y = $origen+$h*1.37-($iC*20); 
	
		switch ($rsa[0]){
		case 0:					/*$pdf->setColor(1,1,1);*/ $colores[]=new Color(255,255,255);  break;
		case ($rsa[0]<1.51): 	/*$pdf->setColor(230/255,163/255,45/255);*/ $colores[]=new Color(230,163,45); break;
		case ($rsa[0]<2.51): 	/*$pdf->setColor(49/255,24/255,238/255);*/ $colores[]=new Color(49,24,238); break;
		case ($rsa[0]>2.5):		/*$pdf->setColor(109/255,179/255,19/255);*/ $colores[]=new Color(109,179,19); break;		
	}
	
	//$pdf->filledRectangle($x,$y,10,10);
	//$pdf->setColor(0,0,0);
	//$pdf->addText($x+20, $y,14,$rsa[0]);
}

$b = 8; // �rea Moral	

$sql = "select nombre, (select color from preceptoria_boleta where alumno = $alumno and id_area = a.id_area_valor and id_aspecto = 0), id_area_valor, (select observaciones from preceptoria_boleta where alumno = $alumno and id_area = a.id_area_valor and id_aspecto = 0) from areas_valor a where area = $b and ciclo = (Select periodo from parametros) order by area;";

$rsa = mysql_fetch_array(mysql_query($sql));
	
$a = $rsa[2];

$sql = "select nombre, (select color from preceptoria_boleta where alumno = $alumno and id_aspecto = a.id)
		from areas_valor_aspectos a
		where id_area_valor = $a order by aspecto;";

$rsb = mysql_query($sql);
$x = $origen+5;
$y = $origen+$h*.8 -110; 
$mas = 50;

$xAux = $x;
$yOriginal = $y-$mas;
$cuenta=0;
$masOriginal=$mas;

while ($row=mysql_fetch_array($rsb))
{
	switch ($row[1]){
		case 0:		$pdf->setColor(1,1,1); break;
		case 1: 	$pdf->setColor(230/255,163/255,45/255); break;
		case 2: 	$pdf->setColor(49/255,24/255,238/255); break;
		case 3:		$pdf->setColor(109/255,179/255,19/255); break;		
		default:	$pdf->setColor(49/255,24/255,238/255); break;
	}
	
	
	
	if($cuenta!=0 && $cuenta%4==0)
	{
		$x+=130;
		$coordenadaY=$yOriginal;
		$masOriginal=$mas;
	}
	else
		$coordenadaY=$y-$mas;
	
	$pdf->filledRectangle($x+15,$coordenadaY,10,10);
	$pdf->setColor(0,0,0);
	
	
	$pdf->addText($x+30,$coordenadaY,12, utf8_decode($row[0]));
	$mas = $mas + 17;

	$cuenta++;
}

$x = $xAux;

$mas = $mas + 17;		
$pdf->addText($origen+400,$yOriginal-2,14,utf8_decode("Area de oportunidad:"));

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

$incrementoAreaOportunidadMoral=0;
foreach ($name as $n)
{ 
	$mas = $mas + 17;
	$incrementoAreaOportunidadMoral+=17;
	$pdf->addText($origen+400,$yOriginal-2-$incrementoAreaOportunidadMoral,10, utf8_decode($n));
	//$pdf->addText($x+5,$y-$mas,10, utf8_decode($n));
}

//--- SE INSERTA EL CUADRO DE COLOR DEL �REA MORAL
	$cualColor=7;
	$colorR=$colores[$cualColor]->R();
	$colorG=$colores[$cualColor]->G();
	$colorB=$colores[$cualColor]->B();
	$pdf->setColor($colorR/255,$colorG/255,$colorB/255);
	$pdf->filledRectangle($origen+$w*3-30,$origen+40,30,30);
	$pdf->setColor(0,0,0);

// �rea Acad�mica
$b = 1;	

$sql = "select nombre, (select color from preceptoria_boleta where alumno = $alumno and id_area = a.id_area_valor and id_aspecto = 0), id_area_valor, (select observaciones from preceptoria_boleta where alumno = $alumno and id_area = a.id_area_valor and id_aspecto = 0) from areas_valor a where area = $b and ciclo = (Select periodo from parametros) order by area;";

$rsa = mysql_fetch_array(mysql_query($sql));

$nR = 0;
$yy = 10000;

$x = $origen+150; //CAMBIO:
$y = $origen+$h*1.45-($nR*20);

$pdf->addText($x+$w*.5, $origen+$h*1.4,16,utf8_decode("Area " . $rsa[0]));
//$pdf->addText($x,$y,14,"�rea Intelecual"); //$periodo");

$pdf->setStrokeColor(0,0,0);
$pdf->setLineStyle(1);
$c=0;
while($califs = mysql_fetch_array($result2))
{ 
  $c=$c+1;
  $comentario=$califs["comentario"];
  $countRow=$countRow + 1;
  if($countRow>15)
		break;
//if ($countRow==1) $pdf->selectFont('./fonts/Helvetica-Bold.afm'); else $pdf->selectFont('ZapfDingbats.afm');
//  if(strstr($renglones_sombreados,",$countRow,")!=FALSE) echo"<tr align='left' style='background-color:#C0C0C0;'>";
//  else echo "<tr align='left'>";
  for($i=1;$i<=$numCols;$i++)
  { $value=$califs["columna_$i"];
    $alin="align='right'";
	if($value!="")
	{ switch ($i)
	  { case 1: $col1v=false; $col2v=false; $alin="align='left'"; $col3v=false; break;
	    case 2: $col2v=false; $col3v=false; break;
	    case 3: $col3v=false; break;
	  }
	}
	if ($i==1) {$fw = 0; $rw = 100;} 
	else 
	{
		//$fw = 110; 
		$fw = 100; 
		$rw=0;
	}

	//$x = $origen+($i-1)*80+$fw+25;
	
	
	///////////////$x = $origen+($i-1)*50+$fw+25;
	
	/*echo" <script language='javascript'>alert('i: $i');</script>";*/
	if($i<=2 )
		$x = $origen+($i-1)*50+$fw+25;
	else
		$x = $x+50;
	
	//$x = $origen+$w*1.21+($i-1)*45+$fw;
	$y = $origen+$h*1.3-($nR*12);
	if(substr($value,-2,1)=='@'){
		switch(substr($value,-1,1)){
			case 1: 	$pdf->setColor(230/255,163/255,45/255); break;
			case 2: 	$pdf->setColor(49/255,24/255,238/255); break;
			case 3:		$pdf->setColor(109/255,179/255,19/255); break;		
		}
	}
	else
		$pdf->setColor(1,1,1);

	$pdf->filledRectangle($x-2,$y-2,$rw+80,12);
	//$pdf->filledRectangle($x-2,$y-2,$rw+45,12);
	$pdf->setColor(0,0,0);

	if ($y < $yy) $yy = $y;
	if(substr($value,-2,1)=='@')
		$value=substr($value,0,strlen($value)-2);
	
	if($numCols>12)
	{
		$x=$x-15;
		$pdf->addText($x-20,$y,9,utf8_decode($value));
		if($i>1)
			$pdf->Rectangle($x-22,$y-2,$rw+35, 12); // cuadr�cula calificaciones; ancho de columnas
		else
			$pdf->Rectangle($x-22,$y-2,$rw+50, 12); // cuadr�cula calificaciones; ancho de columnas

	}
	else
	{
		$pdf->addText($x,$y,9,utf8_decode($value));    //AQUI
		$pdf->Rectangle($x-2,$y-2,$rw+50, 12); // cuadr�cula calificaciones; ancho de columnas  AQUI
	}
	
	//$pdf->Rectangle($x-2,$y-2,$rw+80, 12);
	//$pdf->Rectangle($x-2,$y-2,$rw+45, 12);
  }
	$nR++;
	
}

$pdf->setStrokeColor(0,(27/255),(145/255));
$pdf->setLineStyle(3);

$b = 1;	

$sql = "select nombre, (select color from preceptoria_boleta where alumno = $alumno and id_area = a.id_area_valor and id_aspecto = 0), id_area_valor,
		  (select observaciones from preceptoria_boleta where alumno = $alumno and id_area = a.id_area_valor and id_aspecto = 0)
		from areas_valor a where area = $b and ciclo = (Select periodo from parametros) order by area;";


$rsa = mysql_fetch_array(mysql_query($sql));
	
$mas = 17;
$coordenadaY=$yy-$mas*2+5;
$pdf->addText($origen+$w*1.2,$coordenadaY,15,utf8_decode("Area de oportunidad:")); //�rea de oportunidad intelectual

$nme = $rsa[3];
$l = 98;

unset ( $name );
while ( strlen ( $nme ) > $l ) {
	$n = substr ( $nme, 0, $l - 1 );
	if (! $p = strripos ( $n, " " )) $p = $l - 1;
	
	$name [] = substr ( $nme, 0, $p );
	$nme = substr ( $nme, $p, strlen ( $nme ) - 1 );
}

$name [] = $nme;

$x = $origen+$w*1.2; $y = $yy-$mas;
$incrementoAreaIntelectual=0;
$yOriginal=$coordenadaY-17;
$cuenta=0;
foreach ($name as $n)
{ 
	$mas = $mas + 17; //%2
	$incrementoAreaIntelectual+=17;
	$pdf->addText($origen+$w*1.2,$coordenadaY-$incrementoAreaIntelectual,10, utf8_decode($n));
	$cuenta++;
	if(cuenta>1) break; // <--- ESTO IMPIDE QUE SE MUESTREN M�S DE DOS �REAS DE OPORTUNIDAD (CUESTI�N DE ESPACIO)
	//$pdf->addText($x+5,$y-$mas,10, utf8_decode($n));
}


//$pdf->Ln(10);
$pdf->newPage();

for ($iC=2;$iC<=7;$iC++)
{
	if ($iC<5)
	{ $x = $origen+($w*($iC-2)); $y = $origen;}
	else	
	{ $x = $origen+($w*($iC-5)); $y = $h+$origen;}
	
	$pdf->rectangle($x,$y,$w,$h);
	//$pdf->rectangle($x,$y+$h-$tw,$w,$tw);
	
	if ($iC>4) $b =$iC-3; else $b=$iC+3;
	 
	$sql = "select nombre, (select color from preceptoria_boleta where alumno = $alumno and id_area = a.id_area_valor and id_aspecto = 0), id_area_valor,
			  (select observaciones from preceptoria_boleta where alumno = $alumno and id_area = a.id_area_valor and id_aspecto = 0)
			from areas_valor a where area = $b and ciclo = (Select periodo from parametros) order by area;";


	$rsa = mysql_fetch_array(mysql_query($sql));
	
	
	$pdf->addJpegFromFile("images/beb$b.jpg",$x+2, $y+$h-$tw-1,$w-2, $tw);
	/*
	$pdf->addText($x+40,$y+$h-$tw+8,18,utf8_decode($rsa[0]));
	
	
	switch ($rsa[1]){
		case 1: 	$pdf->setColor(230/255,163/255,45/255); break;
		case 2: 	$pdf->setColor(49/255,24/255,238/255); break;
		case 3:		$pdf->setColor(109/255,179/255,19/255); break;		
		default:	$pdf->setColor(49/255,24/255,238/255); break;
	}
	
	$pdf->filledRectangle($x+5,$y+$h-$tw+5,20,20);
	*/
	$pdf->setColor(0,0,0);
	
	$a = $rsa[2];
	
	$sql = "select nombre, (select color from preceptoria_boleta where alumno = $alumno and id_aspecto = a.id)
			from areas_valor_aspectos a
			where id_area_valor = $a order by aspecto;";
	

	$rsb = mysql_query($sql);
	$mas = 50;
	
	//--- SE INSERTAN LOS COLORES EN LOS CUADROS POR CADA �REA
	$cualColor=$iC-1;
	//---> la siguiente l�nea parcha un bug en los �ndices del array
	$cualColor+=($iC>1 && $iC<4)?3:-3;
	$colorR=$colores[$cualColor]->R();
	$colorG=$colores[$cualColor]->G();
	$colorB=$colores[$cualColor]->B();
	if($colorR!=255 && $colorG!=255 && $colorB!=255)
	{
		$pdf->setColor($colorR/255,$colorG/255,$colorB/255);
		$pdf->filledRectangle($x+209,$y+$h-$mas-11,30,30);
	}
	//---
	
	while ($row=mysql_fetch_array($rsb))
	{
		switch ($row[1]){
			case 0:		$pdf->setColor(1,1,1); break;
			case 1: 	$pdf->setColor(230/255,163/255,45/255); break;
			case 2: 	$pdf->setColor(49/255,24/255,238/255); break;
			case 3:		$pdf->setColor(109/255,179/255,19/255); break;		
			default:	$pdf->setColor(49/255,24/255,238/255); break;
		}
		
		$pdf->filledRectangle($x+5,$y+$h-$mas,10,10);
		$pdf->setColor(0,0,0);
		
		$pdf->addText($x+25,$y+$h-$mas,12, utf8_decode($row[0]));
		$mas = $mas + 17;		
	}
		
	$mas = $mas + 17;		
	$pdf->addText($x+5,$y+$h-$mas,15,utf8_decode("Area de oportunidad:"));

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
		$pdf->addText($x+5,$y+$h-$mas,10, utf8_decode($n));
	}
}


//$pdf->ezStream();
$doc=$pdf->output();
$opc = $_REQUEST ['opc'];
$action = $_REQUEST ['action'];

		$htmlM="<p>Atentamente,<br>".utf8_decode($nombreprec)."</p>";
		$texto.="\nAtentamente,\n".utf8_decode($nombreprec)."\n";

setlocale(LC_TIME, 'es_MX');
$fechaActual=strftime("%d%B%Y");
$nombrePDF='Informe_Integral_'.$ral[0].'_'.$fechaActual.'.pdf';
$mail->AddStringAttachment($doc, $nombrePDF, 'base64', 'application/pdf');
$mail->Timeout=300;

// Encode
$nombre = utf8_decode($nombre);
$apellidop = utf8_decode($apellidop);
$apellidom =utf8_decode($apellidom);

//$mail->AddAddress($mail_);	
		if($email_padre!="")$mail->AddAddress($email_padre);
		if($email_madre!="")$mail->AddAddress($email_madre);
		if($email_prec!="")$mail->AddAddress($email_prec);
		$mail->AddBCC($mailBCC);
		$mail->Subject="Informe Integral de $nombre $apellidop $apellidom";
		$mail->Body=$htmlM;
				
		if(!$mail->Send())
			echo"Mailer Error: ".$mail->ErrorInfo;
		$mail->ClearAddresses();
}
?>