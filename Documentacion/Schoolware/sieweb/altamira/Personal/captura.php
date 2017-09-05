<?
	session_start();
	include('../config.php');
	$horaCierre=$_POST[horaCierre];
	//echo 'horaCierre: '.$horaCierre.'<br>';
	$minutoCierre=$_POST[minutoCierre];
	//echo 'minutoCierre: '.$minutoCierre.'<br>';
	$horaRetardo=$_POST[horaRetardo];
	//echo 'horaRetardo: '.$horaRetardo.'<br>';
	$minutoRetardo=$_POST[minutoRetardo];
	//echo 'minutoRetardo: '.$minutoRetardo.'<br>';
	$horaActual=date("H");
	//echo 'horaActual: '.$horaActual.'<br>';
	$minutoActual=date("i");
	//echo 'minutoActual: '.$minutoActual.'<br>';
	$result=mysql_result(mysql_query("select nombre from fip_cursos where id_curso=(select id_curso from fip_cursos_publicacion where id_publicacion=$_POST[id_publicacion])"),0);
	//echo "select nombre from fip_cursos where id_curso=(select id_curso from fip_cursos_publicacion where id_publicacion=$_POST[id_publicacion])".'<br>';
	$msj="<input type=text id=registro name=registro onkeyup='limit(this,6)' maxlength=6>";
if($_POST[registro]!=''){
	$fam=substr($_POST[registro],1,5);
	//echo 'fam: '.$fam.'<br>';
	if(substr($_POST[registro],0,1)=='1'){
		$parent="padre";
		$otherparent="madre";
		$saludo="Bienvenido,";
	}
	if(substr($_POST[registro],0,1)=='2'){
		$parent="madre";
		$otherparent="padre";
		$saludo="Bienvenida,";
	}
	$consulta=mysql_query("SELECT CONCAT(nombre_$parent,' ',apellido_paterno_$parent,' ',apellido_materno_$parent) AS nombre,fip_publicacion_familias.id_publicacion_familia FROM familias INNER JOIN fip_publicacion_familias ON familias.familia=fip_publicacion_familias.familia WHERE familias.familia=(SELECT fip_publicacion_familias.familia FROM fip_publicacion_familias WHERE id_publicacion=$_POST[id_publicacion] AND fip_publicacion_familias.familia=$fam AND $parent='S') AND fip_publicacion_familias.id_publicacion=$_POST[id_publicacion]");
	//echo "SELECT CONCAT(nombre_$parent,' ',apellido_paterno_$parent,' ',apellido_materno_$parent) AS nombre,fip_publicacion_familias.id_publicacion_familia FROM familias INNER JOIN fip_publicacion_familias ON familias.familia=fip_publicacion_familias.familia WHERE familias.familia=(SELECT fip_publicacion_familias.familia FROM fip_publicacion_familias WHERE id_publicacion=$_POST[id_publicacion] AND fip_publicacion_familias.familia=$fam AND $parent='S') AND fip_publicacion_familias.id_publicacion=$_POST[id_publicacion]";
	$familia=mysql_result($consulta,0,0);
	$id_publicacion_familia=mysql_result($consulta,0,1);
	if(($horaActual.':'.$minutoActual)>=($horaCierre.':'.$minutoCierre))
		$msj="<font color=red><b>Ha terminado el tiempo de captura.</b></font>";
	else{
		$asist='R';
		if(($horaActual.':'.$minutoActual)<=($horaRetardo.':'.$minutoRetardo))
			$asist='S';
		if($familia!=''){
			$existe=mysql_result(mysql_query("select * from fip_asistencia where id_publicacion_familia=$id_publicacion_familia and fecha_sesion=curdate()"),0);
			if($existe!='')
				//echo "update fip_asistencia set asistencia_$parent='$asist',hora_$parent=curtime() where id_publicacion_familia=$id_publicacion_familia";
				mysql_query("update fip_asistencia set asistencia_$parent='$asist',hora_$parent=curtime() where id_publicacion_familia=$id_publicacion_familia");
			else
				//echo "insert into fip_asistencia (id_publicacion_familia,fecha_sesion,asistencia_$parent,hora_$parent,asistencia_$otherparent,hora_$otherparent) values($id_publicacion_familia,curdate(),'S',curtime(),'N','00:00:00')";
				mysql_query("insert into fip_asistencia (id_publicacion_familia,fecha_sesion,asistencia_$parent,hora_$parent,asistencia_$otherparent,hora_$otherparent) values($id_publicacion_familia,curdate(),'S',curtime(),'N','00:00:00')");
			$fam_hor="$saludo $familia<br>Hora:$horaActual:$minutoActual";
		}
		else
			$fam_hor="<font color=red><b>Padre no registrado para el curso.</b></font>";
	}
}

echo "
<html>
	<head>
		<script type='text/javascript'>
			function limit(field,chars){
				if(field.value.length>=chars)
					document.captura.submit();
				return false;
			}
		</script>
	</head>
	<body onLoad='window.focus();document.captura.registro.focus();'>
		<center><br>Curso \"<b>$result</b>\"<br>Captura cierra: $horaCierre:$minutoCierre
		<br><br>
		<form id=captura name=captura action=captura.php method=post>
		$msj
		<!--<input type='text' id='registro' name='registro' onkeyup='limit(this,6)' maxlength=6>-->
		<input type='hidden' name='id_publicacion' id='id_publicacion' value='$_POST[id_publicacion]'>
		<input type='hidden' name='horaCierre' id='horaCierre' value='$horaCierre'>
		<input type='hidden' name='minutoCierre' id='minutoCierre' value='$minutoCierre'>
		<input type='hidden' name='horaRetardo' id='horaRetardo' value='$horaRetardo'>
		<input type='hidden' name='minutoRetardo' id='minutoRetardo' value='$minutoRetardo'>
		</form>
		$fam_hor
		<br><br><br>
		<input type=button onClick='window.close();' value='Cerrar ventana'>
		</center>
	</body>
</html>";
?>