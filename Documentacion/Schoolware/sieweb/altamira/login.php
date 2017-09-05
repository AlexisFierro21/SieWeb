<?php
ini_set('session.save_path',realpath(dirname($_SERVER['DOCUMENT_ROOT']) . '/../session'));
session_start();
include('config.php');
include('functions.php');
$inicio_sesion=0;
$script='';
if(!empty($_POST["userName"])){
//valores del formulario
 $userNamew = $_POST["userName"];
 $passwordw = $_POST["password"];
 $letter = $_POST["letter"];
//iniciar las variables de session
 $_SESSION['alumnoN']="none";
 $_SESSION['familia']="none";
 $_SESSION['ciclo']="none";
 $result=mysql_query("select periodo, sede from parametros",$link)or die(mysql_error());
 $row = mysql_fetch_array($result);
 $_SESSION['ciclo']=$row["periodo"];
 $_SESSION['sede']=$row["sede"];
 $_SESSION['password'] = $passwordw;
 $_SESSION['userName'] = $userNamew;
 $_SESSION['letter'] = $letter;
 $clave = intval(substr($userNamew,1));
 $_SESSION['clave'] = $clave;
 $passwordw = f_encripta ($clave, $passwordw, "E") ;
  switch($letter){
	case "P":  $tbl="personal";  $key = "empleado"; $_SESSION['tipo']="personal"; break;
  	case "A":  $tbl="alumnos";   $key = "alumno";   $_SESSION['tipo']="alumno"; break;
  	case "F":  $tbl="familias";  $key = "familia";  $_SESSION['tipo']="familia"; break;
  	case "E":  $tbl="exalumnos"; $key = "exalumno"; $_SESSION['tipo']="exalumno"; break;
  }
$result=mysql_query("select * from $tbl where $key=$clave",$link)or die(mysql_error());
if(mysql_affected_rows($link)==0)
	$script="alert('Nombre De Usuario Incorrecto');";
else{
	$row = mysql_fetch_array($result);
	if($row["password_web"]<>$passwordw)
		$script="alert('Password Incorrecto');";
	else{
		$activo="null";
		$activo=$row["activo_web"];
		if($activo=="S")
			$script="alert('El usuario est� suspendido');";
		else{
			$usuario=$_SESSION['clave'] ;
			$tipo_usuario=$_SESSION['letter'] ;
			$today = getdate();
			$ano=$today["year"];
			$mes=$today["mon"];
			actualizaEstadistico($tipo_usuario,$usuario,$ano,$mes,1); 
			if($version_sie_local!="colmenares" && $activo!='A')
				mysql_query("update $tbl set activo_web='A', fecha_modificacion= Now() where $key=$clave",$link) or die(mysql_error());
			if($letter=="P"){
				$_SESSION['preceptor']=$row["preceptor"];
				$_SESSION['mbv']=$row["mensajes_becas_vencidas"];
				$_SESSION['at']=$row["administra_test"];
			}
			$inicio_sesion=1;
		}
	}
}
}
if ($inicio_sesion==0){
	$_SESSION=array();
	session_destroy();
	echo "<html>";
?>
<head>
<meta http-equiv='Content-Type' content='text/html; charset=utf-8'>
<title>.:Colmenares:.</title>
</head>
<body>

  <link rel="stylesheet" href="css/style.css">
  <!--[if lt IE 9]><script src="//html5shim.googlecode.com/svn/trunk/html5.js"></script><![endif]-->
<script language="javascript">
var letter_=' ';
var keys_code='';</script>
<script src="Scripts/AC_RunActiveContent.js" type="text/javascript"></script>
<div class="encabezado"><center>
  <? echo "<img src='$imagen_header'>";?></center>
</div><br><br><br><br>
<div class="container">
    <section class="register">
      <h1>BIENVENIDOS<br>
      M&oacute;dulo Web del Sistema de Informaci&oacute;n Escolar</h1>
      <div class="reg_section personal_info">
		<h3>Datos de Acceso</h3></div>
     <form id="form1" name="frmLogin" action="login.php" method="post" onSubmit="letter_=document.getElementById('userName').value; letter_=letter_.substring(0,1);  if(letter_=='p') letter_='P'; if(letter_=='a') letter_='A'; if(letter_=='f') letter_='F'; if(letter_=='e') letter_='E'; document.getElementById('letter').value=letter_; document.getElementById('form1').submit();">
     
      <div class="reg_section personal_info"><h3></h3> 
      	<input name="userName" type="text" maxlength="6" id="userName" onKeyPress=" keys_code='65,97,69,101,70,102,80,112'; if((this.value.length==0 && keys_code.indexOf(event.keyCode)==-1) || (this.value.length>0 && (event.keyCode<48 || event.keyCode>57))) event.returnValue = false;" placeholder="User = 'P' Personal, 'F' Familia + 5 d&iacute;gitos">     
      </div>
      <div class="reg_section password"><h3></h3><input name="password" type="password" id="password" placeholder="Contrase&ntilde;a"></div>
      <input type="hidden" name="letter" id="letter" />
      <p class="submit"><input type="Submit" value="Ingresar"></p>
	</p>
        </p>
        <p><p class="terms"><label></label></p><p><div class=""><br></div></p>
        </form>
    </section>
    </div>
      <p id="footer">  
    <section class="about">
<font face='arial' size='1'><a href="mailto:atencion_padres@colmenares.org.mx &subject=Consulta desde SIEWEB Altamira &body=Correo enviado desde SIEWEB - Altamira">Correo de Soporte:</a>
<br><br>
<a target="_blank" href="aviso.pdf">Aviso de Privacidad</a></font>
</p>
</body>
</html>
<?php
 echo"<script language='javascript'>$script</script>";
 mysql_close($link);
} 
else{
	if($letter=='F'){
		$acepta_tds=mysql_result(mysql_query("select acepta_tds from familias where familia=$clave"),0);
		if($acepta_tds=='S')
			echo"<script language='javascript'>location.href='sieweb.php'; </script>";
		else
			echo"<script language='javascript'>location.href='tds.php'; </script>";
	}
	else
		echo"<script language='javascript'>location.href='sieweb.php'; </script>";
}
?>