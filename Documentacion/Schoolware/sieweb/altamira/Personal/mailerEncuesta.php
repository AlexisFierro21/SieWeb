<?	include ('../../repositorio/class.ezpdf.php');
	include('../connection.php');

$link = mysql_connect($server, $userName, $password)or die('No se pudo conectar: ' . mysql_error());
mySql_select_db($DB)or die("No se pudo seleccionar DB");

//CORREO ELECTRONICO
$hostSMTP="smtp.gmail.com";
$smtpPrefix="tls";
$smtpPort=587;
$mailUser="encuestas@e-altamira.edu.mx";
$mailFrom="encuestas@e-altamira.edu.mx";
$mailBCC="encuestas@e-altamira.edu.mx";
$mailBCC="salvador.chavez@colmenares.org.mx";
$mailPass="2a3l0t1a1m4!";
$mailSender="Encuesta Colegio Altamira - Moctezuma A.C.";

$e_mail='encuestas@e-altamira.edu.mx';

	mysql_query("SET CHARACTER SET 'utf8'");
	
	//$alumno=$_GET[alumno];
	$alumno=$_POST['responde'];
	$sqlt = mysql_query ("Select concat(nombre,' ',apellido_paterno,' ',apellido_materno) as nombre from alumnos where alumno=$alumno ",$link) or die ("Select concat(nombre,' ',apellido_paterno,' ',apellido_materno) from alumnos where alumno=$alumno ".mysql_error());
while($row=mysql_fetch_array($sqlt))
	  {
	  	$nombreAl = $row['nombre'];
	  }
/*	$sql = "Select concat(nombre,' ',apellido_paterno,' ',apellido_materno) from alumnos where alumno=$_POST[responde]";
	$r = mysql_fetch_array(mysql_query($sql));
	$nombreAl = $r[0];*/
	
	$sqlte = mysql_query ("Select id_test from test where nombre like 'Encuesta%' ",$link) or die ("Select id_test from test where nombre like 'Encuesta%' ".mysql_error());
while($row_=mysql_fetch_array($sqlte))
	  {
	  	$idt = $row_['id_test'];
	  }
	  	
	$sql = mysql_query ("Select id_publicacion from test_publicacion where id_test = $idt and aplica_a  = 'Todos' ",$link) or die ("Select id_publicacion from test_publicacion where id_test = $idt and aplica_a  = 'Todos' ".mysql_error());
while($rowp_=mysql_fetch_array($sql))
	  {
	  	$idp = $rowp_['id_publicacion'];
	  }
	  
	//////
	$c=0;
		
	$result_p=mysql_query("SELECT * FROM test_preguntas WHERE id_test=$idt ORDER BY orden ASC",$link) or die ("SELECT * FROM test_preguntas WHERE id_test=$idt ORDER BY orden ASC ".mysql_error());
	while($row_p=mysql_fetch_array($result_p))
	{
	//////////
		$nombrePreg=$row_p['pregunta'];
		$idPreg=$row_p['id_pregunta'];
		
		$result_respuesta = mysql_query( "SELECT * from test_respuestas WHERE id_publicacion=$idp and responde=$alumno and id_pregunta=$idPreg",$link) or die ("SELECT * from test_respuestas WHERE id_publicacion=$idp and responde=$alumno and id_pregunta=$idPreg".mysql_error());
		while($r_resp=mysql_fetch_array($result_respuesta))
		{				
			$resp_opc = $r_resp['id_opcion'];
			$resp = $r_resp['respuesta'];
			$id_resp = $r_resp['id_respuesta'];
			
			
			if($resp_opc > 0)
			{
				$respuesta_Alumno=mysql_query("SELECT * FROM test_opciones WHERE id_pregunta=$idPreg and id_opcion=$resp_opc",$link) or die ("SELECT * FROM test_opciones WHERE id_pregunta=$idPreg and id_opcion=$resp_opc".mysql_error());
				while ( $r_respA=mysql_fetch_array($respuesta_Alumno)) 
				{
					$respuestaAl = $r_respA['opcion'];
				}
			}
			else
				$respuestaAl=$resp;
					
			if($respuestaAl=='N')
				$respuestaAl='No';
			if($respuestaAl=='S')
				$respuestaAl='Si';
					
			$c=$c+1;
			$tabla.="$c.-$nombrePreg \n     $respuestaAl \n";
		
		}				
	}
	$ok = 1;	
	//echo $tabla;
	

	

	if ($ok){
	  
	//Crea mail
		$mail=new PHPMailer();
		$mail->IsSMTP();
		$mail->PluginDir="../../repositorio/phpMailer/";
		$mail->CharSet="utf-8";
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
		//$mail->AddAttachment('../im/logo.jpg', 'logo.jpg');
		$pdf = new Cezpdf('LETTER','portrait');
		$pdf->selectFont('../fonts/Helvetica.afm');
		$pdf->setStrokeColor(0,(27/255),(145/255));
		$pdf->setLineStyle(3);
		$pdf->addJpegFromFile('../im/logo.jpg',0,708,615);
		
	// PARA LA TABLA QUE SER� INSERTADA:
		unset ($opciones_tabla);
		
	// Mostrar las lineas
		$opciones_tabla['showlines']=1;
	
	// Mostrar las cabeceras
		$opciones_tabla['showHeadings']=0;
		
	// Lineas sombreadas
		$opciones_tabla['shaded']= 1;
		
	// Tama�o letra del texto
		$opciones_tabla['fontSize']= 12;
		
	// Color del texto
		$opciones_tabla['textCol'] = array(0,0,0);
		
	// Tama�o de las cabeceras (texto)
		$opciones_tabla['titleFontSize'] = 14;
		
	// Margen interno de las celdas
		$opciones_tabla['rowGap'] = 5;
		$opciones_tabla['colGap'] = 5;
		//$opciones_tabla['justification']='justify'; No funcion�.
		$opciones_tabla['width']=500;
	//	$acuerdosquery=mysql_query("SELECT `id_area`,`acuerdo`,`padre`, `madre`,`obs_padre`,`obs_madre` FROM `preceptoria_acuerdos` where `alumno`=$_POST[responde] Order By `id` DESC LIMIT 1");
	//	$acuerdos=mysql_fetch_array($acuerdosquery, MYSQL_ASSOC);
		
		$x=0;
		
	// Obtenemos el nombre de pila del alumno y la fecha para crear el nombre del PDF
		$nombreAlumno=explode(' ', $nombreAl);
		setlocale(LC_TIME, 'es_MX');
		$fechaActual=strftime("%d%B%Y");
		
		$nombrePDF='Encuesta_'.$nombreAlumno[0].'_'.$fechaActual.'.pdf';
		//$pdf->addText($origen,$origen+$h*1.6+8,14,utf8_decode("Ya lo logramos\n"));
		$pdf->ezText(utf8_decode("\n\n\nEncuesta de $nombreAl\n"), 20);
		setlocale(LC_TIME, 'es_MX');
		$pdf->ezText(utf8_decode(strftime("%d de %B de %Y, %H:%M horas.\n")), 12);
		$pdf->ezTable($data, "", '',$opciones_tabla);
		$pdf->ezText(utf8_decode($tabla), 12);
		$doc=$pdf->output();
		
		$mail->AddStringAttachment($doc, $nombrePDF, 'base64', 'application/pdf');
		$mail->AddAddress($e_mail);			
		
		$mail->AddBCC($mailBCC);
		$mail->Subject="Encuesta de $nombreAl";
		$mail->Body=$htmlM;
		if(!$mail->Send())
			echo"Mailer Error: ".$mail->ErrorInfo;
		/*else
			echo "<script>alert('Las respuestas del alumno $nombreAl han sido enviada.');</script>";*/
		$mail->ClearAddresses();
	}
?>