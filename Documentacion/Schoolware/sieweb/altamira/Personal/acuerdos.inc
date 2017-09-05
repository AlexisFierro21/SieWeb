<?php
include('../connection.php');
include('../config.php');
require ('../../repositorio/phpMailer/PHPMailerAutoload.php');
$link = mysql_connect($server, $userName, $password) or die('No se pudo conectar: ' . mysql_error());
//mysql_query("SET CHARACTER SET 'utf8'");
mySql_select_db($DB)or die("No se pudo seleccionar DB");
//mysql_query("SET CHARACTER SET 'utf8'");
$st=0;
$opc = $_REQUEST['opc'];
$alumno = $_REQUEST['alumno'];
$obs_p = $_REQUEST['obsPadre'];
$obs_m = $_REQUEST['obsMadre'];
$obs_pre = $_REQUEST['obsPre'];
$acrdE = $_REQUEST['acuerdo1'];

if( $obs_m =="[Observaci&oacute;n de la Madre:]  ")
 	$obs_m=" ";
if( $obs_p =="[Observaci&oacute;n del Padre:]  ")
 	$obs_p=" ";
if( $obs_pre =="[Observaci&oacute;n del preceptor:]  ")
 	$obs_pre=" ";
	
$op= $obs_p;
$om= $obs_m;
$opre= $obs_pre;
$fec = $_REQUEST['fec'];

if ($fec){
	$pcheck = $_REQUEST['padre'];
	$mcheck = $_REQUEST['madre'];
	$dataRO = "disabled='disabled'";
}
else{
	$pcheck = ""; $mcheck = "";
	$dataRO = "";
}
if (isset($_REQUEST['padre']))
{$p = 1; $pcheck = "checked";} else {$p = 0; $pcheck = "";};
//if ($pcheck == 'on') {$p = 1; $pcheck = "checked";} else {$p = 0; $pcheck = "";};
if (isset($_REQUEST['madre']))
{$m = 1; $mcheck = "checked";} else {$m = 0; $mcheck = "";};
//if ($mcheck == 'on') {$m = 1; $mcheck = "checked";} else {$m = 0; $mcheck = "";};
$today = date("Y-m-d");
$imp = 0;
$opcion=2;
if ($opc != 0){
	$i = 1;
//	while ($_REQUEST["acuerdo$i"])	{	
		$idMod = $_REQUEST["id$i"];
		$idA = $_REQUEST["idArea$i"];
		//$acrd = $_REQUEST["acuerdo$i"];
		$acrd = $_REQUEST["acuerdo1"];
		if($p == 0 and $m == 0)
			$st=0;
		else
			$st=1;
		//if($acrd != " " and $acrd!=""){
						
			if (!$idMod)			
			{
				mysql_query("SET CHARACTER SET 'utf8'");
				mysql_query("insert into preceptoria_acuerdos (alumno, ciclo, acuerdo, fec, padre, madre, st, obs_padre, obs_madre, obs_Pre) values ($alumno, (select periodo from parametros), '$acrd', '$today', $p, $m, $pr $st, '$op', '$om', '$opre')",$link)or die(mysql_error());
				
				//$save = "insert into preceptoria_acuerdos (alumno, ciclo, id_area, acuerdo, fec, padre, madre, st, obs_padre, obs_madre) values ($alumno, (select periodo from parametros), $idA, '$acrd', '$today', $p, $m, $st, '$op', '$om')";
				//mysql_query($save);
			}
			else
			{
				mysql_query("SET CHARACTER SET 'utf8'");
				mysql_query("update preceptoria_acuerdos set acuerdo = '$acrd', fec = '$today', padre = $p, madre = $m, preceptor = $pr, st = $st, obs_padre='$op', obs_madre='$om' where id = $idMod",$link)or die(mysql_error());
				//$save = "update preceptoria_acuerdos set acuerdo = '$acrd', fec = '$today', padre = $p, madre = $m, st = $st, obs_padre='$op', obs_madre='$om' where id = $idMod";
				//mysql_query($save);
			}
			mysql_close($link);
	//	}
		$i++;		
//	}
	
	include('mailer.php');
	//echo "<script>parent.location.reload();</script>";
	if ($opc == 2){
		$pcheck = ""; 
		$mcheck = "";		
	}
	$opc = 0;
	$imp = 1;
}
$fecha_actual=date("Y/m/d");
$sqlt = mysql_query ("Select id, area, acuerdo, padre, madre, obs_padre, obs_madre, obs_Pre from preceptoria_acuerdos c inner join areas_valor a on a.id_area_valor = c.id_area where Alumno = $alumno and fec='$fecha_actual' ",$link) or die ("Select id, area, acuerdo, padre, madre, obs_padre, obs_madre, obs_Pre from preceptoria_acuerdos c inner join areas_valor a on a.id_area_valor = c.id_area where Alumno = $alumno and fec='$fecha_actual' ".mysql_error());
while($row=mysql_fetch_array($sqlt))
	  {

//$sqlt= "Select id, area, acuerdo, padre, madre, obs_padre, obs_madre from preceptoria_acuerdos c inner join areas_valor a on a.id_area_valor = c.id_area where Alumno = $alumno and fec='$fecha_actual';";

//$rst = mysql_query($sqlt);
//while ($row=mysql_fetch_array($rst)){
	$a = $row[1];
	$acuerdo[$a] = $row[2];
	$idAcrd[$a] = $row[0];
	$obs_p = $row[5];
	$obs_m = $row[6];
	$obs_pre = $row[7];
	if ($row[3] == 1) $pcheck = "checked"; else $pcheck = "";
	if ($row[4] == 1) $mcheck = "checked"; else $mcheck = "";
	$imp = 1;
}
$rdA = mysql_fetch_array ( mysql_query ( "Select * FROM alumnos WHERE alumno= $alumno") );
$rdF = mysql_fetch_array ( mysql_query ( "Select CONCAT_WS(' ',apellido_paterno_padre,apellido_materno_padre,nombre_padre) as padre, 
												 CONCAT_WS(' ',apellido_paterno_madre,apellido_materno_madre,nombre_madre) as madre  
												 from familias WHERE familia=" . $rdA ['familia']));

$sql = "select nombre from secciones where ciclo = (select periodo from parametros) and seccion='" . $rdA ['seccion'] . "';";
$rdS = mysql_fetch_array ( mysql_query ( $sql));
$NomAlumno = $rdA['apellido_paterno'] . " " . $rdA['apellido_materno'] . ", " . $rdA['nombre'];
$padre = $rdF['padre'];
$madre = $rdF['madre'];
$Secc = $rdS[0];
$Grado = $rdA ['grado'];
$Grupo = $rdA ['grupo'];
//echo" <script language='javascript'>alert('FECHAA $fec  ALUMNO $alumno');</script>";
?>