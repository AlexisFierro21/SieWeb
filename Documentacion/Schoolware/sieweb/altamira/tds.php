<?
session_start();
include('connection.php');
mysql_connect($server, $userName, $password)or die('No se pudo conectar: ' . mysql_error());
mysql_select_db($DB)or die("No se pudo seleccionar DB");
$clave = $_SESSION["clave"];
$_SESSION["letter"];
if($_POST[acepta]=='S'){
	mysql_query("update familias set acepta_tds='$_POST[acepta]', fecha_acepta_tds=NOW(), fecha_modificacion=NOW() where familia=$clave");
	header('Location: sieweb.php');
}
include('config.php');
include('functions.php');
$localtime_assoc = localtime(time(), false);
$hora = intval(date('H'));
if($hora<12)
	$saludo = "Buenos d&iacute;as<br>";
else
	if($hora<19)
		$saludo = "Buenas tardes<br>";
	else
		$saludo = "Buenas noches<br>";
$saludo="$saludo Familia<br>";
$sqlQ="select nombre_familia as c_nombre from familias where familia=$clave";
$saludo.=mysql_result(mysql_query($sqlQ,$link),0,0);
echo "
<head>
<meta http-equiv='Content-Type' content='text/html; charset=windows-1252'>
<title>.:Colmenares:.- Formamos hombres con Car&aacute;cter</title>
</head>
<script>
	function enableSubmit(value){
		if(value=='S')
			document.formulario.enviar.disabled=false;
		else
			document.formulario.enviar.disabled=true;
	}
</script>
<html>
<body bgcolor='$fondo' text='$texto' link='$texto_n' vlink='$texto_s' alink='$texto_s'>
<center><img src='$imagen_header'></center>
<table width='1100' align='center' bgcolor='#FFFFFF'>
  <tr>
    <th width='151'>$saludo<br></th>
	<th><font size='+2'>M&oacute;dulo Web del Sistema de Informaci&oacute;n Escolar</font></th>
  </tr>
  <tr>
	<tr><th width='150' id='t$i' bgcolor='$fondo_n'><a href='login.php'>Salir</a></th></tr><tr><th width='7'></th>
  	<td  style='padding-left:30px;padding-right:30px' align='justify'>
";

//////////////////////////////////////////////////////////////////////////////////////////////////////////
echo
"Aviso de privacidad (Corto)<br>
<br>
En cumplimiento con la Ley Federal de Protecci�n de Datos Personales en Posesi�n de los Particulares <br>
y con el fin de Asegurar la protecci�n y privacidad de los datos personales, as� como regular el acceso, <br>
rectificaci�n, cancelaci�n y oposici�n del manejo de los mismos, <a target='_blank' href='http://liceodelvalle.edu.mx/publico/descargas/politica_privacidad.pdf
'>Liceo del Valle, A.C.</a> informa que la <br>
recolecci�n de datos podr�n ser usados con fines promocionales, informativos y estad�sticos relacionados <br>
con la operaci�n administrativa diaria del Colegio.<br>
<br>
En cualquier momento, el titular de los datos podr� hacer uso de sus derechos de acceso, rectificaci�n, <br>
cancelaci�n y oposici�n. <br>
<br>
Si desea m�s informaci�n, podr� consultar la versi�n completa de este documento, en el sitio web del Colegio <br>
en el apartado Aviso de Privacidad. Se presume que usted consciente t�citamente en el tratamiento de sus datos <br>
mientras no manifieste su oposici�n.<br>
<br>";
//////////////////////////////////////////////////////////////////////////////////////////////////////////

echo"
	</td>
  </tr>
  <tr><form action='tds.php' method='post' name='formulario' id='formulario'>
	<td></td><td><br><br><input type='radio' name='acepta' id='acepta' value='S' onClick='enableSubmit(this.value)'>Acepto los t&eacute;rminos de uso.<br><br><input type='radio' name='acepta' id='acepta' value='N' onClick='enableSubmit(this.value)'>No acepto los t&eacute;rminos de uso.<br><br><input type='submit' name='enviar' id='enviar' value='Enviar' disabled=true><br></td>
  </form></tr>
</table>
</body>
</html>";
?>