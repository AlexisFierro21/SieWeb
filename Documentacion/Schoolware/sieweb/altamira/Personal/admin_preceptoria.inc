<?php
include('../connection.php');
$link = mysql_connect($server, $userName, $password) or die('No se pudo conectar: ' . mysql_error());
mySql_select_db($DB)or die("No se pudo seleccionar DB");

$opc = $_REQUEST['opc'];
if (!$opc) $opc = 0;

$CicloA = $_REQUEST['ciclo'];

$AreaA = $_REQUEST['area'];
$AspA = $_REQUEST['aspecto'];

$idArea = $_REQUEST['idArea'];
$idAsp = $_REQUEST['idAsp'];
$permite = $_REQUEST['Permite'];

if (empty($CicloA))
{
	$r = mysql_fetch_array(mysql_query("select preceptoria_mod from parametros;"));
	if ($r[0] == 1) $permite = "on"; else $permite = "";
}

if ($permite=='on') 
{
	$permite = "Checked";
	mysql_query("update parametros set preceptoria_mod=1;");
}
else 
{
	$permite = ""; 
	mysql_query("update parametros set preceptoria_mod=0;");
}

switch($opc){
	case 1:
		$nu = $_REQUEST['num'];
		$no = $_REQUEST['nom'];
		if (!$AreaA && $nu && $no)
			$save = "insert into areas_valor (ciclo, area, nombre) values
											($CicloA, $nu, '$no');";
		else
			$save = "update areas_valor set area = $nu, nombre = '$no' where id_area_valor = $AreaA;";

		mysql_query($save);

		if ($AreaA)
		{
			$nu = $_REQUEST['numasp'];
			$no = $_REQUEST['nomasp'];
			if ($_REQUEST['vis']=='on') $vis = 1; else $vis=0;
			
			if (!$AspA && $nu & no)
				$save = "insert into areas_valor_aspectos (id_area_valor, ciclo, aspecto, nombre, visib) values
												($AreaA, $CicloA, $nu, '$no', $vis);";
			else
				$save = "update areas_valor_aspectos set aspecto = $nu, nombre = '$no', visib = $vis where id = $AspA";

			
			mysql_query($save);
		}		
		$opc = 0;
		$AreaA = 0; $AspA = 0; $num = ''; $nom = ''; $numasp = ''; $nomasp = ''; $vis = 0; 
		break;
	
	case 0:
		if ($AreaA)		
		{
			$r  = mysql_fetch_array(mysql_query("select * from areas_valor where id_area_valor = $AreaA;"));
			$num = $r[2];
			$nom = $r[3];
		}		

		if ($AspA)		
		{
			$r  = mysql_fetch_array(mysql_query("select * from areas_valor_aspectos where id = $AspA;"));
			$numasp = $r[3];
			$nomasp = $r[4];
			
			if ($r[5]==1) $vis ="checked"; else $vis = "";
		}		
		
}
?>