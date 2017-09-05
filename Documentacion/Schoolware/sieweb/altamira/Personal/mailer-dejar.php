<?	include ('../../repositorio/class.ezpdf.php');
	include('../connection.php');
	

$hostSMTP="smtp.gmail.com";
$smtpPrefix="tls";
$smtpPort=587;
$mailUser="entrevistas@e-altamira.edu.mx";
$mailFrom="entrevistas@e-altamira.edu.mx";
$mailBCC="entrevistas@e-altamira.edu.mx";
$mailPass="2a3l0t1a1m4!";
$mailSender="Entrevistas Colegio Altamira - Moctezuma A.C.";


	mysql_query("SET CHARACTER SET 'utf8'");
	
	$email_padre="";
	$email_madre="";
	$email_prec="";
	$email_bcc="salvador.chavez@colmenares.org.mx";
	$nombreprec="";
	$nombreAl="";
	$sqlt = mysql_query ("select
				e_mail_padre, e_mail_madre, p.e_mail, concat(p.nombre, ' ', p.apellido_paterno, ' ', p.apellido_materno) as personal,
				concat(a.nombre, ' ', a.apellido_paterno, ' ', a.apellido_materno) as nomalm
				from alumnos a inner join familias f  on f.familia = a.familia
				inner join personal p on p.empleado = a.preceptor
				where alumno=$alumno ",$link) or die ("select
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
		$ok=1;
	  }

	//Dos mails adicionales
		if(!empty($_POST[mail1]))
               $email[3]=$_POST[mail1];
       if(!empty($_POST[mail2]))
               $email[4]=$_POST[mail2];
	if ($ok){
	  
	//Crea mail
		$mail=new PHPMailer();
		$mail->IsSMTP();
		$mail->PluginDir="../../../repositorio/phpMailer/";
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
		$mail->AddAttachment('../im/logo.jpg', 'logo.jpg');
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
		$n=$nombreAl;
		$texto="";
		mysql_query("SET CHARACTER SET 'utf8'");
		//$sql="select area,nombre from areas_valor where ciclo=(select periodo from parametros) order by 1;";
		
		
		$sql="SELECT `alumno`, `areas`.`nombre`, `acuerdo`, `padre`, `madre`, `st`, `obs_padre`, `obs_madre` , `fec` FROM `preceptoria_acuerdos` inner join (select `id_area_valor`, `nombre` from `areas_valor`) as `areas` on `areas`.`id_area_valor`=`preceptoria_acuerdos`.`id_area` where  `alumno`=$alumno AND `fec`=curDate()";
		if($rs=mysql_query($sql)){
			$data=array(array('�rea ','Acuerdos'));
			$c=0;
			while($row=mysql_fetch_array($rs)){
				$c=$c+1;
				//if($row[0]==$acuerdos['id_area'])
//				{					
					if($row['acuerdo']!='')
						$data[]=array(utf8_decode($row[1]), utf8_decode($row['acuerdo']));
					//$a=$row[0];
					//$htmlM.="<p>".$row[1]."<br>".$row['acuerdo']."</p>";//$_REQUEST["acuerdo$a"].$acuerdos['acuerdo']."</p>";
					//El �rea y el acuerdo ya no se agregan al texto porque se incluir�n en la tabla.
					//$texto.=utf8_decode("\n".$row[1]."\n".$row['acuerdo']."\n\n");
					//$htmlM.="<br><br>";
				//	break;
				$obsMadre=$row['obs_madre'];
				$obsPadre=$row['obs_padre'];				
//				}
						//Si la madre asisti�
					if($obsMadre!='' and $c==1){
						//$htmlM.="<br>Observaciones de la madre:<br>".utf8_decode($acuerdos['obs_madre'])."<br>";
						$texto.="\nObservaciones de la madre:\n".utf8_decode($obsMadre)."\n";
					}
						//Si el padre asisti�
					if($obsPadre!='' and $c==1){
						//$htmlM.="<br>Observaciones del padre:<br>".utf8_decode($acuerdos['obs_padre'])."<br>";
						$texto.="\nObservaciones del padre:\n".utf8_decode($obsPadre)."\n";
					}
			}
		}
		$htmlM="<p>Atentamente,<br>".utf8_decode($nombreprec)."</p>";
		$texto.="\nAtentamente,\n".utf8_decode($nombreprec)."\n";
		$x=0;
		
	// Obtenemos el nombre de pila del alumno y la fecha para crear el nombre del PDF
		$nombreAlumno=explode(' ', $n);
		setlocale(LC_TIME, 'es_MX');
		$fechaActual=strftime("%d%B%Y");
		$nombrePDF='Acuerdos_'.utf8_decode($nombreAlumno[0]).'_'.$fechaActual.'.pdf';
		//$pdf->addText($origen,$origen+$h*1.6+8,14,utf8_decode("Ya lo logramos\n"));
		$pdf->ezText(utf8_decode("\n\n\nAcuerdos de $n\n"), 20);
		setlocale(LC_TIME, 'es_MX');
		$pdf->ezText(utf8_decode(strftime("%d de %B de %Y, %H:%M horas.\n")), 12);
		$pdf->ezTable($data, "", '',$opciones_tabla);
		$pdf->ezText($texto, 12);
		$doc=$pdf->output();
		$mail->AddStringAttachment($doc, $nombrePDF, 'base64', 'application/pdf');
/*		foreach($email as $em)
			$mail->AddAddress($em);
		$email__="lushe_ms@hotmail.com";	
		$mail->AddAddress($email__);*/
		
		$mail->AddAddress($email_padre);
		$mail->AddAddress($email_madre);
		$mail->AddBCC($email_bcc); 
		$mail->AddAddress($email_prec);
		if($correo1!="")
			$mail->AddAddress($correo1);
		if($correo2!="")
			$mail->AddAddress($correo2);
		
		$mail->AddBCC($mailBCC);
		//$email__="lushe_ms@hotmail.com";
		$mail->AddBCC($email__);
		
		$mail->Subject="Acuerdos de Alumno $n";
		$mail->Body=$htmlM;
		if(!$mail->Send())
			echo"Mailer Error: ".$mail->ErrorInfo;
		/*echo "<script language='JavaScript'>alert('Los correos del alumno $alumno han sido enviados.');</script>";*/
		$mail->ClearAddresses();
	}
?>