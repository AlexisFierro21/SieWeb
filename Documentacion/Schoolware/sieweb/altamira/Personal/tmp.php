<? session_start();
include('../config.php');
$retardo=mysql_result(mysql_query("select retardo from fip_cursos_publicacion where id_publicacion=$_GET[id_publicacion]"),0);
$horaActual=date("H",time(date("H")));
$minutoActual=date("i",time(date("i")));
$horaCierre=date("H",time(date("H"))+3600);
$minutoCierre=date("i",time(date("i"))+3600);
$queryHoraInicio=mysql_result(mysql_query("select fecha_ini from fip_cursos_publicacion where id_publicacion=$_GET[id_publicacion]"),0);
$horainicio=substr($queryHoraInicio,11,2); //HH
$minutoinicio=substr($queryHoraInicio,14,2); //MM
//$HoraMinutoRetardo=date("H:i",time(date("H:i"))+$retardo*60);
$retardoH=0;
if($retardo>59){
	$retardoH=$retardo/60;
	$retardo=$retardo%60;
}
$horaRetardo=$horainicio+$retardoH;
$minutoRetardo=$minutoinicio+$retardo;
if($minutoRetardo>59)
	$horaRetardo++;
//$horaRetardo=substr($HoraMinutoRetardo,0,2);
//$minutoRetardo=substr($HoraMinutoRetardo,3,2);
/*echo 'retardo: '.$retardo.'<br>';
echo 'horaActual: '.$horaActual.'<br>';
echo 'minutoActual: '.$minutoActual.'<br>';
echo 'HoraMinutoActual: '.$horaActual.':'.$minutoActual.'<br>';
echo 'horaCierre: '.$horaCierre.'<br>';
echo 'minutoCierre: '.$minutoCierre.'<br>';
echo 'HoraMinutoCierre: '.$horaCierre.':'.$minutoCierre.'<br>';
echo "horainicio: $horainicio<br>";
echo "minutoinicio: $minutoinicio<br>";
echo 'HoraMinutoRetardo: '.$HoraMinutoRetardo.'<br>';
echo 'horaRetardo: '.$horaRetardo.'<br>';
echo 'minutoRetardo: '.$minutoRetardo.'<br>';*/
?>
<html>
<head>
</head>
<body onLoad='document.cursos.submit();'>
<form id='cursos' name='cursos' action='captura.php' method='post'>
<?
	echo "<input name='id_publicacion' id='id_publicacion' type='hidden' value='$_GET[id_publicacion]'>
	<input name='horaCierre' id='horaCierre' type='hidden' value='$horaCierre'>
	<input name='minutoCierre' id='minutoCierre' type='hidden' value='$minutoCierre'>
	<input name='horaRetardo' id='horaRetardo' type='hidden' value='$horaRetardo'>
	<input name='minutoRetardo' id='minutoRetardo' type='hidden' value='$minutoRetardo'>
	<!--<input type='submit' value='Enviar'>-->
	</form>";
?>
</body>
</html>