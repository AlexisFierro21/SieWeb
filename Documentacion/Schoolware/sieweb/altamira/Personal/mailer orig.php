<?	include ('../../repositorio/class.ezpdf.php');
	include('../connection.php');

$link = mysql_connect($server, $userName, $password)or die('No se pudo conectar: ' . mysql_error());
mySql_select_db($DB)or die("No se pudo seleccionar DB");
	
//CORREO ELECTRONICO
$hostSMTP="smtp.gmail.com";
$smtpPrefix="tls";
$smtpPort=587;
$mailUser="entrevistas@e-altamira.edu.mx";
$mailFrom="entrevistas@e-altamira.edu.mx";
$mailBCC="entrevistas@e-altamira.edu.mx";
$mailPass="2a3l0t1a1m4!";
$mailSender="Acuerdos Colegio Altamira A.C.";


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
		  if($ro['e_mail_padre']<>''){
	  		$email_padre=$ro['e_mail_padre'];}
		  else{
			  $email_padre="entrevistas@e-altamira.edu.mx";}
		  
		  if($ro['e_mail_madre']<>''){
			$email_madre=$ro['e_mail_madre'];}
		  else{
			  $email_madre="entrevistas@e-altamira.edu.mx";}
			  
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
		$pdf = new Cezpdf('LETTER','portrait');
		$pdf->selectFont('../fonts/Helvetica.afm');
		$pdf->setStrokeColor(0,(27/255),(145/255));
		$pdf->setLineStyle(3);
		$pdf->addJpegFromFile('../im/logo.jpg',0,708,615);
		
	// PARA LA TABLA QUE SERA INSERTADA:
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
	
		$opciones_tabla['width']=500;
		$acuerdosquery=mysql_query("SELECT fec FROM `preceptoria_acuerdos` where `alumno`=$alumno Order By `fec` DESC LIMIT 1");
		$acuerdos=mysql_fetch_array($acuerdosquery);
		$n=$nombreAl;
		$texto="";
		mysql_query("SET CHARACTER SET 'utf8'");
			
		$sql="select alumno, acuerdo, padre, madre, st, obs_padre, obs_madre, obs_Pre, fec
from preceptoria_acuerdos where fec='$acuerdos[fec]' and alumno=$alumno";
				
		
		
		if($rs=mysql_query($sql)){
			$data=array(array('ACUERDOS EN LA ENTREVISTA'));
			$c=0;
			while($row=mysql_fetch_array($rs)){
				$c=$c+1;
	
			$data[]=array(utf8_decode($row['acuerdo']));
				
				$obsMadre=$row['obs_madre'];
				$obsPadre=$row['obs_padre'];				

			}
		}
		
		$htmlM="<p>Atentamente,<br>".$nombreprec."</p>";
		$texto.="\nAtentamente,\n".utf8_decode($nombreprec)."\n";
		$x=0;
		
	// Obtenemos el nombre de pila del alumno y la fecha para crear el nombre del PDF
		$nombreAlumno=explode(' ', $n);
		setlocale(LC_TIME, 'es_MX');
		$fechaActual=strftime("%d%B%Y");
		
		$nombrePDF='Acuerdos_'.$nombreAlumno[0].'_'.$fechaActual.'.pdf';
		
		$pdf->ezText(utf8_decode("\n\n\nAcuerdos de $n\n"), 20);
		setlocale(LC_TIME, 'es_MX');
		
		$pdf->ezText(utf8_decode(strftime("%d de %B de %Y, %H:%M horas.\n")), 12);
		$pdf->ezTable($data, "", '',$opciones_tabla);
		$pdf->ezText($texto, 12);
		$doc=$pdf->output();
		
		$mail->AddStringAttachment($doc, $nombrePDF, 'base64', 'application/pdf');
		$mail->AddAddress($email_padre);
		$mail->AddAddress($email_madre);
		$mail->AddBCC($email_bcc); 
		$mail->AddAddress($email_prec);
			if($correo1!="")
				$mail->AddAddress($correo1);
			if($correo2!="")
				$mail->AddAddress($correo2);
		
		$mail->AddBCC($mailBCC);
		
		$mail->Subject="Acuerdos de $n";
		$mail->Body=$htmlM;
		
		if(!$mail->Send())
			echo"Mailer Error: ".$mail->ErrorInfo;
		$mail->ClearAddresses();
	}
?>