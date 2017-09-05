<? session_start();
 include('../config.php');
 $ciclo_=0;
 if (!empty($_POST["cambiar_alumnos"]))
 { $cambiar=explode(";",$_POST["cambiar_alumnos"]);
   $cambia_estatus=$_POST['cambia_estatus'];
   $cnt=0;
   foreach($cambiar as $cambia)
   { if($cambia>0){ $update=mysql_query("UPDATE inscripciones_alumnos SET estatus='$cambia_estatus' WHERE id_inscripcion=$cambia",$link) or die(mysql_error()); $cnt++;}
   }
   echo"<script language='javascript'>alert('SE CAMBIO EL ESTATUS DE $cnt ALUMNOS CORRECTAMENTE');</script>";
 }
 if (!empty($_POST["ciclo"])) $ciclo_=$_POST['ciclo'];
 if (!empty($_GET["ciclo"])) $ciclo_=$_GET['ciclo'];
 $selectedSeccionini="none";
 if (!empty($_POST["cuadroSeccionini"])) $selectedSeccionini=$_POST["cuadroSeccionini"];
 $estatus="t";
 if (!empty($_POST["estatus"])) $estatus=$_POST["estatus"];
 $selectedSeccionfin="none";
 if (!empty($_POST["cuadroSeccionfin"])) $selectedSeccionfin=$_POST["cuadroSeccionfin"];
 $selectedGradoini="none";
 if (!empty($_POST["cuadroGradoini"])) $selectedGradoini=$_POST["cuadroGradoini"];
 $selectedGradofin="none";
 if (!empty($_POST["cuadroGradofin"])) $selectedGradofin=$_POST["cuadroGradofin"];
 $mostrar="N";
 if (!empty($_POST["mostrar"])) $mostrar=$_POST["mostrar"];
 
 
?> 

<script  language='JavaScript' type='text/JavaScript'><!--
function carga(ini_fin,sec_gdo_gpo)
{ 	if (sec_gdo_gpo<3) 
	{	if(sec_gdo_gpo<2) 
		{	if (ini_fin==1)	document.all("cuadroGradoini").value="none";
			else document.all("cuadroGradofin").value="none";
		}
  	document.getElementById("form1").submit();
	}	
}
function cambia(chk)
{ for(i=0;ele=chk.form.elements[i];i++) if(ele.type=='checkbox') ele.checked=chk.checked;  }
function cambia_estatus_alumnos()
{ var alumnos="0";
  var total=document.getElementById('total_alumnos').value;
  for(m=0;m<total;m++)
  { if(document.getElementById('alumno_'+m).checked)
    { id=document.getElementById('alumno_'+m).value;
	  alumnos=alumnos+";"+id;
	}
  }
  document.getElementById('cambiar_alumnos').value=alumnos;
  document.getElementById("form1").submit();
}
function editar(ID_)
{ if(editando.value!=0) alert('Ya se esta modificando otro Registro');
  else
  { document.getElementById('iframe_').src='formularios_inscripciones.php?id='+ID_+'&tabla=inscripciones_alumnos&agr_modif_borr=modificar';
	editando.value=ID_;
    document.getElementById('edit_').style.display='inline';
    document.getElementById('edit_').style.display='table-row';
  }
}
--></script> 
<body bgcolor="#F6F6F6" id="destinatarios">
<form id="form1" name="form1" action="destinatarios.php" method="post">
  <input type="hidden" name="editando" id="editando" value="0">
  <input type="hidden" name="ciclo" id="ciclo" value="<?=$ciclo_?>">
<table align="center">
	<tr><td>
  		Secci&oacute;n Inicial : &nbsp; &nbsp; <select onChange="javascript:carga (1,1)" style="font-size:8pt;" id="cuadroSeccionini" name="cuadroSeccionini" tabindex="10">
			<? 	$result=mysql_query("SELECT secciones.seccion, secciones.nombre
			                         FROM secciones, inscripciones_alumnos 
									 WHERE inscripciones_alumnos.seccion=secciones.seccion
									 AND inscripciones_alumnos.ciclo=$ciclo_
									 AND secciones.ciclo=$ciclo_ 
									 GROUP BY secciones.seccion, secciones.nombre, secciones.ciclo, seccion,
									 inscripciones_alumnos.seccion,inscripciones_alumnos.ciclo
									 ORDER BY secciones.seccion",$link)or die(mysql_error());
    		   	$numField=1;  
			   	while($row = mysql_fetch_array($result))
			   	{	$selected="";
			   		if ($selectedSeccionini == $row["seccion"]) $selected="selected";
					if (($numField==1) && ($selectedSeccionini=="none") ) $selectedSeccionini= $row["seccion"];
					echo"<option $selected value='".$row["seccion"]."'>".$row["nombre"]."</option>";
					$numField=$numField + 1;
				}
		 	?></select> &nbsp; &nbsp; &nbsp;<br>
  		Grado Inicial : &nbsp; &nbsp; &nbsp; <select name="cuadroGradoini" id="cuadroGradoini" style="font-size:8pt;" tabindex="20" onChange="javascript:carga (1,2)">
			<? 	$result=mysql_query("SELECT grado FROM inscripciones_alumnos 
									 WHERE ciclo=$ciclo_ 
									 AND seccion = '$selectedSeccionini'
									 GROUP BY ciclo, seccion, grado
									 ORDER BY grado",$link)or die(mysql_error());
    		   	$numField=1;  
			   	while($row = mysql_fetch_array($result))
			   	{	$selected="";
			   		if ($selectedGradoini == $row["grado"]) $selected="selected";
					if (($numField==1) && ($selectedGradoini=="none") ) $selectedGradoini= $row["grado"];
					echo"<option $selected value='".$row["grado"]."'>".$row["grado"]."</option>";
					$numField=$numField + 1;
				}
		 	?>
  		</select></td>
	<td> 
  		Secci&oacute;n Final : &nbsp; &nbsp;  <select name="cuadroSeccionfin" id="cuadroSeccionfin" style="font-size:8pt;" tabindex="10" onChange="javascript: carga(2,1);">
			<? 	$result=mysql_query("SELECT secciones.seccion, secciones.nombre
			                         FROM secciones, inscripciones_alumnos 
									 WHERE inscripciones_alumnos.seccion=secciones.seccion
									 AND inscripciones_alumnos.ciclo=$ciclo_
									 AND secciones.ciclo=$ciclo_ 
									 GROUP BY secciones.seccion, secciones.nombre, secciones.ciclo, seccion,
									 inscripciones_alumnos.seccion,inscripciones_alumnos.ciclo
									 ORDER BY secciones.seccion DESC",$link)or die(mysql_error());
    		   	$numField=1;  
			   	while($row = mysql_fetch_array($result))
			   	{	$selected="";
			   		if ($selectedSeccionfin == $row["seccion"]) $selected="selected";
					if (($numField==1) && ($selectedSeccionfin=="none") ) $selectedSeccionfin= $row["seccion"];
					echo"<option $selected value='".$row["seccion"]."'>".$row["nombre"]."</option>";
					$numField=$numField + 1;
				}
		 	?>
  		</select> &nbsp; &nbsp; &nbsp;<br>
  		Grado Final : &nbsp; &nbsp; &nbsp; <select name="cuadroGradofin" id="cuadroGradofin" style="font-size:8pt;" tabindex="20" onChange="javascript:carga (2,2)">
			<? 	$result=mysql_query("SELECT grado FROM inscripciones_alumnos 
									 WHERE ciclo=$ciclo_ 
									 AND seccion = '$selectedSeccionfin'
									 GROUP BY ciclo, seccion, grado
									 ORDER BY grado desc",$link)or die(mysql_error());
    		   	$numField=1;  
			   	while($row = mysql_fetch_array($result))
			   	{	$selected="";
			   		if ($selectedGradofin == $row["grado"]) $selected="selected";
					if (($numField==1) && ($selectedGradofin=="none") ) $selectedGradofin= $row["grado"];
					echo"<option $selected value='".$row["grado"]."'>".$row["grado"]."</option>";
					$numField=$numField + 1;
				}
		 	?>
  		</select></td>
	</tr>
	<tr><td colspan="2" align="center">Estatus: 
      <select name="estatus" id="estatus">
        <option value="t">TODOS</option>
        <option value="SIN INICIAR">SIN INICIAR</option>
        <option value="INICIADO">INICIADO</option>
        <option value="DETENIDO">DETENIDO</option>
        <option value="CONCLUIDO">CONCLUIDO</option>
      </select> <br><script language="javascript">document.getElementById("estatus").value='<?=$estatus?>';</script> 
		<input name="mostrar" type="hidden" id="mostrar" value="N">
		<input type="submit" onClick="javascript: mostrar.value='S' ;" value="Mostrar"></td>
	</tr>
	<tr><td colspan="2" align="center"><br>
		Cambiar Seleccionados A Estatus: 
      <select name="cambia_estatus" id="cambia_estatus">
        <option value="SIN INICIAR">SIN INICIAR</option>
        <option value="INICIADO">INICIADO</option>
        <option value="DETENIDO">DETENIDO</option>
        <option value="CONCLUIDO">CONCLUIDO</option>
      </select><input name="cambiar_alumnos" type="hidden" id="cambiar_alumnos"> 
		<input type="button" onClick="cambia_estatus_alumnos();" value="Cambiar"></td>
	</tr>

</form>
<? 	$total_alumnos=0;
	$celdas='';
	if($mostrar=="S") 
	{  	echo"</table><table width='885' border='2' align='center'><tr>";
		$t=0;
		$sql_secciones=mysql_query("SELECT secciones.seccion, secciones.nombre
			                         FROM secciones, inscripciones_alumnos 
									 WHERE inscripciones_alumnos.seccion=secciones.seccion
									 AND inscripciones_alumnos.ciclo=$ciclo_
									 AND secciones.ciclo=$ciclo_ 
									 AND secciones.seccion between '$selectedSeccionini' and '$selectedSeccionfin'
									 GROUP BY secciones.seccion, secciones.nombre, secciones.ciclo, seccion,
									 inscripciones_alumnos.seccion,inscripciones_alumnos.ciclo
									 ORDER BY secciones.seccion",$link)or die(mysql_error()); 
		$secciones=mysql_num_rows($sql_secciones);
		if($secciones==0) die("<th>La Seccion inicial es mayor que la final</th>");
		$t_ini=$t+1;
		$t_fin=$t+$secciones;
		while($row = mysql_fetch_array($sql_secciones)) 
		{	$t++;
			$celdas="$celdas <th id='t$t' onclick='javascript: pestana($t,$t_ini,$t_fin,1);' style='cursor:hand; cursor:pointer'> &nbsp; ".$row["nombre"]." &nbsp; </th>";
		}
		if(($secciones==1) && ($selectedGradoini>$selectedGradofin)) die("<th>El grado inicial es mayor que el final</th>"); 		
		$grados=0;
		$es_ini=false;
		$es_fin=false;
		$sql_grados=mysql_query("SELECT seccion, grado FROM inscripciones_alumnos 
									 WHERE ciclo=$ciclo_
									 GROUP BY ciclo, seccion, grado",$link)or die(mysql_error());
		while($query_grados=mysql_fetch_array($sql_grados)) 
		{ if(($query_grados["seccion"]==$selectedSeccionini) && ($query_grados["grado"]==$selectedGradoini)) $es_ini=true;
			if (($es_ini) && (!$es_fin)) 
			{	$grados++;
				$row_grados[$grados]["seccion"]=$query_grados["seccion"];
				$row_grados[$grados]["grado"]=$query_grados["grado"];				
			}			
			if (($query_grados["seccion"]==$selectedSeccionfin) && ($query_grados["grado"]==$selectedGradofin)) $es_fin=true;
		}		
		if ((!$es_fin) || ($grados==0)) die("<th>El grados inicial es mayor que el final</th>"); 
		echo"$celdas </tr><tr><td class='pestanas' colspan='$secciones'>";
		$n_sec=1;		
		$seccion_anterior="none";
		$celdas="";
		for($rg=1;$rg<=$grados;$rg++) 
		{	if ($row_grados[$rg]["seccion"]!=$seccion_anterior)
			{	if ($n_sec>1) echo"</tr><tr>$celdas</tr></table><script language='javascript'> pestana($t_ini_,$t_ini_,$t_fin_,1);</script>";
				$seccion_anterior=$row_grados[$rg]["seccion"];
				echo "<table width='100%' align='center' border='2' id='d$n_sec' class='pestanas' style='display:none;'><tr>";
				$n_sec++;
				$n_gdo=1;
				$celdas="";
				$t_ini_=$t+1;
				$grados_seccion=0;
				for($gs=1;$gs<$grados+1;$gs++) if($row_grados[$gs]["seccion"]==$seccion_anterior) $grados_seccion++;
				$t_fin_=$t+$grados_seccion;
			}
			$t++;
			$n_gdo++;
			echo "<th id='t$t' onclick='javascript: pestana($t,$t_ini_,$t_fin_,1);' style='cursor:hand; cursor:pointer'> &nbsp; ".$row_grados[$rg]["grado"]."&ordm; &nbsp; </a></th>";
		 $and='';
	     if($estatus!='t') $and=" AND estatus=$estatus ";
		 $sql_="SELECT CONCAT_WS(' ',apellido_paterno,apellido_materno,nombre) as n,id_inscripcion,estatus,comentario
								FROM alumnos, inscripciones_alumnos  
								WHERE alumnos.alumno = inscripciones_alumnos.alumno
								AND inscripciones_alumnos.ciclo=$ciclo_
								AND inscripciones_alumnos.seccion='".$row_grados[$rg]["seccion"]."' 
								AND inscripciones_alumnos.grado='".$row_grados[$rg]["grado"]."' $and 
								ORDER BY n";		
			$query_=mysql_query($sql_,$link) or die(mysql_error());
			$celdas.="<td id='d$t' style='display:none;' class='pestanas' colspan='$grados'> <form>
			    <input type='checkbox' checked value='0' id='m$t' onClick='javascript: cambia(this);'><strong> Seleccionar/Quitar todos</strong>
				<table><tr><th>ALUMNO</th><th>ESTATUS</th><th>COMENTARIO</th></tr>";
			while($row_=mysql_fetch_array($query_))
			{	$celdas.="<tr><td><input type='checkbox' checked id='alumno_$total_alumnos' value='".$row_['id_inscripcion']."'> ".$row_['n']."</td><td>&nbsp;".$row_['estatus']."</td><td> ".$row_['comentario']."<a href='#s'><img  alt='Modificar' src='../im/b_edit.png' onclick='editar(".$row_['id_inscripcion'].");'></a></td></tr>";
				$total_alumnos++;
			}
			$celdas.="</table></form></td>";
		}
		echo"</tr><tr>$celdas</tr></table></td></tr><table>
			<script language='javascript'> pestana($t_ini_,$t_ini_,$t_fin_,1); pestana($t_ini,$t_ini,$t_fin,1)</script>";
	}
	echo"<input type='hidden' id='total_alumnos' value='$total_alumnos'>";
?> <tr id='edit_' style='display:none'>
  	<td colspan='100%' align='right'>
     <img onClick="editando.value='0'; document.getElementById('edit_').style.display='none';" alt='Cancelar' src='../im/cancel.png' /> &nbsp; 
     <img onClick="iframe_.document.getElementById('formulario').submit(); mostrar.value='S'; setTimeout('form1.submit()',900);" alt='Guardar' src='../im/ok.png' /><br />
     <iframe width='870' height='77' id='iframe_' name='iframe_'></iframe></td>
   </tr>
</table>
<a name="s">___</a>
</body>