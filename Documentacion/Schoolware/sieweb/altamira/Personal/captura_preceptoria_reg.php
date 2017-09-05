<? session_start();
include('../connection.php');
require('../../repositorio/phpMailer/PHPMailerAutoload.php');
/////Fecha de última modificación 24-08-2016

$dbhost=$server;
$dbname=$DB;
$dbuser=$userName;
$dbpass=$password;
$db = new mysqli($dbhost,$dbuser,$dbpass,$dbname);
 
$query=$db->query("SET NAMES 'utf8'");
$query=$db->query("SET CHARACTER SET utf8 ");
  
 $command = $_REQUEST['command'];
 //echo $command;
if($command == 1){
					$alumno=$_REQUEST['alumno'];
					$ciclo=$_REQUEST['ciclo'];
					$preceptoria=$_REQUEST['preceptoria'];
					$observaciones=$_REQUEST['observaciones'];
					$metas =$_REQUEST['metas'];
					$fin=$_REQUEST['fin'];
					$fecha =$_REQUEST['fecha'];
 
	if ($db->connect_errno) 
	{
		die ("<span class='ko'>Fallo al conectar a MySQL: (" . $db->connect_errno . ") " . $db->connect_error."</span>");
	}
	else
	{
		$query=$db->query("INSERT
								INTO
									preceptoria
										(alumno, 
										 ciclo, 
										 preceptoria, 
										 observaciones, 
										 fin, 
										 fecha, 
										 metas, 
										 fecha_captura) 
								VALUES
									('$alumno', 
									 '$ciclo', 
									 '$preceptoria', 
									 '$observaciones', 
									 '$fin', 
									 '$fecha', 
									 '$metas', 
									 curdate()
									)");
		if ($query){
			 echo "<span class='ok'>Preceptoria grabada satisfactoriamente.</span>";
			
		}else echo "<span class='ko'>".$db->error."</span>";
	}
}elseif($command == 2){
					$id = $_REQUEST['id'];//
					$alumno=$_REQUEST['alumno'];//
					$preceptoria=$_REQUEST['preceptoria'];
					$observaciones=$_REQUEST['observaciones'];//
					$metas =$_REQUEST['metas'];//
					$fin=$_REQUEST['fin'];//
					$fecha =$_REQUEST['fecha'];//
					
					$query=$db->query("UPDATE 
												preceptoria 
											SET 
													observaciones = '".$observaciones."', 
													metas = '".$metas."', 
													fecha = '".$fecha."',
													fin = '".$fin."',
													preceptoria = '".$preceptoria."',
													fecha_captura = curdate() 
											WHERE 
													id='".$id."' 
													AND 
													alumno = '".$alumno."'");
		if ($query) {
			echo "<span class='ok'>Valores modificados correctamente.</span>";
			
			
				}else echo "<span class='ko'>".$db->error."</span>";
}


///////////////Última modificación al sistema, se incluye envió de e-mails
$fin=$_REQUEST['fin'];

if($fin == 1){


$consulta = "SELECT  
				alumnos.alumno,
                CONCAT(alumnos.apellido_paterno, ' ',alumnos.apellido_materno,', ',alumnos.nombre) AS alum_nom, 
				CONCAT(familias.apellido_paterno_padre, ' ',familias.apellido_materno_padre, ', ',familias.nombre_padre) AS nom_padre, 
                familias.e_mail_padre,
                CONCAT(familias.nombre_madre, ' ', familias.apellido_paterno_madre, ', ', 
                familias.apellido_materno_madre) AS nom_madre,
                familias.e_mail_madre,
                CONCAT(personal.apellido_paterno, ' ', personal.apellido_materno, ', ', personal.nombre) AS nom_preceptor, 
                personal.e_mail as e_mail_preceptor
			from 
				alumnos 
                LEFT JOIN 
                familias 
                ON 
                alumnos.familia = familias.familia 
                LEFT JOIN
                personal
                ON
                alumnos.preceptor = personal.empleado
			WHERE
				alumnos.alumno = '{$alumno}' ";


$result = $db->query($consulta);
    		if (!$result) {
        print "  <tr><td colspan='100%'>Error en la consulta.</td></tr> ";
		
    }else{
        foreach ($result as $row) {
         		$nombre_alum = $row['alum_nom'];
         		$nom_padre = $row['nom_padre'];
				$e_mail_padre = $row['e_mail_padre'];
         		$nom_madre = $row['nom_madre'];
				$e_mail_madre = $row['e_mail_madre'];
         		$nom_preceptor = $row['nom_preceptor'];
				$e_mail_preceptor = $row['e_mail_preceptor'];

$html =  '
<!DOCTYPE html>
<html>
  <head>
    <meta  content="text/html; charset=UTF-8"  http-equiv="content-type">
  </head>
    <body>    
	  <table style="width: 100%">
      <tbody>
        <tr>
          <td><br>
          </td>
        </tr>
        <tr>
          <td>Estimados Padres de Familia:</td>
        </tr>
        <tr>
          <td><br>
          </td>
        </tr>
        <tr>
          <td>
          	Les informamos que el día de hoy se capturó la entrevista que el preceptor ha tenido con su hijo, correspondiente a su plan de formación personal. 
          </td>
        </tr>
        <tr>
          <td><br>
          </td>
        </tr>
        <tr>
          <td>atentamente.</td>
        </tr>
        <tr>
          <td>'.$nom_preceptor.'.</td>
        </tr>
      </tbody>
    </table>
  </body>
</html>   
';


		}
     }

//////////////////////PHP Mailer

//// PHP Mailer
$hostSMTP="mail.ecolmenares.net";
$smtpPrefix="tls";
$smtpPort=26;
$mailPass="9yqfedhnOa*P";
$mailSender="Registro de Preceptoria - ".$nombre_alum;

/// Datos Sistemas
$mailUser="preceptorias@ecolmenares.net";
$mailFrom="preceptorias@ecolmenares.net";
$mailBCC="preceptorias@ecolmenares.net";
$mailBCCO= "sistemas@colmenares.org.mx";
$mailBCC1=$mail_preceptor;

		$mail=new PHPMailer();
		$mail->SetLanguage("en","PhpMailer/language/");
		$mail->IsSMTP();
		$mail->PluginDir="../phpMailer/";
		$mail->CharSet="UTF-8";
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
		$mail->Timeout=300;

		$mail->AddBCC($mailBCC);
		$mail->AddBCC($e_mail_preceptor);
		$mail->AddBCC($mailBCCO);
		$mail->AddReplyTo($e_mail_preceptor, $nom_preceptor);
		$mail->AddCC($e_mail_padre);
		$mail->AddCC($e_mail_madre);

		$mail->Subject="Registro de Preceptoria - ".$nombre_alum;
		$mail->Body=$html;



$exito = $mail->Send();

  $intentos=1; 
  
  while ((!$exito) && ($intentos < 5)) {
	sleep(5);
     	//echo $mail->ErrorInfo;
     	$exito = $mail->Send();
     	$intentos=$intentos+1;	
	
   }
 
		
   if(!$exito)
   {
	echo "<span class='ko'>Error al intentar enviar el e-mail.</span>";
	//echo "<br/>".$mail->ErrorInfo;	
   }
   else
   {
	echo "<span class='ok'>Envio de correo satisfactorio.</span>";
   } 


		$mail->ClearAddresses();
}
?>
 
 