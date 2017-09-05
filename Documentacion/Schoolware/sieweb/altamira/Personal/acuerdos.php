<script>
	function Action(f, o){
		with (document.forms[f]){
			opc.value = o;
			if (o == 2)
				window.print();
			submit();
		}
	}
</script>

<script language="JavaScript">  
	function NoVacios()
	{
		$acuerdo1 = document.getElementById('acuerdo1').value;
/*		$acuerdo2 = document.getElementById('acuerdo2').value;
		$acuerdo3 = document.getElementById('acuerdo3').value;
		$acuerdo4 = document.getElementById('acuerdo4').value;
		$acuerdo5 = document.getElementById('acuerdo5').value;
		$acuerdo6 = document.getElementById('acuerdo6').value;
		$acuerdo7 = document.getElementById('acuerdo7').value;
		$acuerdo8 = document.getElementById('acuerdo8').value;*/		
		if ($acuerdo1 != '' && $acuerdo1 != ' ') return true;
/*		if ($acuerdo2 != '' && $acuerdo2 != ' ') return true;
		if ($acuerdo3 != '' && $acuerdo3 != ' ') return true;
		if ($acuerdo4 != '' && $acuerdo4 != ' ') return true;
		if ($acuerdo5 != '' && $acuerdo5 != ' ') return true;
		if ($acuerdo6 != '' && $acuerdo6 != ' ') return true;
		if ($acuerdo7 != '' && $acuerdo7 != ' ') return true;
		if ($acuerdo8 != '' && $acuerdo8 != ' ') return true;*/
		alert( 'Debes capturar el acuerdo.' );
		return false;
	}
/*function mostrarOcultarP() { 
	if (document.getElementById('padre').checked==true) 
    document.getElementById('obsPadre').style.display='none'; 
    else 
    document.getElementById('obsPadre').style.display='block';
} 

function mostrarOcultarM() { 
	if (document.getElementById('madre').checked==true) 
    document.getElementById('obsMadre').style.display='none'; 
    else 
    document.getElementById('obsMadre').style.display='block'; 
}*/
</script>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>Acuerdos</title>
</head>
<?php
	include "acuerdos.inc";
	
	echo utf8_encode($resultAcuerdos);
	echo utf8_encode($tempResult);
	
	$id=0;
	$resultAcuerdos= mysql_query("select distinct fec from preceptoria_acuerdos where alumno=$alumno order by fec");
	$tempResult=mysql_fetch_array(mysql_query("select distinct fec from preceptoria_acuerdos where alumno=$alumno order by fec"));

	echo "<script>function cambia_acuerdos(id){
		switch(id){
		case '':
			";
		//for($n=1;$n<=mysql_num_rows(mysql_query("select * from preceptoria_acuerdos where alumno=$alumno and fec='$tempResult[fec]' order by fec"));$n++)
		for($n=1;$n<=mysql_num_rows(mysql_query("select * from areas_valor where ciclo=(select periodo from parametros)"));$n++)
			echo "document.getElementById('acuerdo$n').value=' ';
			document.getElementById('acuerdo$n').disabled=false;
			";
		echo "document.getElementById('obsPadre').value='';
			document.getElementById('obsPadre').disabled=false;
			document.getElementById('obsMadre').value='';
			document.getElementById('obsMadre').disabled=false;
			document.getElementById('obsPre').value='';
			document.getElementById('obsPre').disabled=false;
			document.getElementById('padre').checked=false;
			document.getElementById('madre').checked=false;
			document.getElementById('padre').disabled=false;
			document.getElementById('madre').disabled=false;
			document.getElementById('impr').style.visibility='hidden';
			document.getElementById('grab').style.visibility='visible';
			";
		echo "break;
		";
		while($acuerdos=mysql_fetch_array($resultAcuerdos)){
			echo "case '$id':
			";
			$resultFecha=mysql_query("select id, alumno, preceptoria_acuerdos.ciclo, id_area, acuerdo, fec, padre, madre, st, obs_padre, obs_madre,obs_Pre,area from areas_valor left outer join preceptoria_acuerdos on areas_valor.id_area_valor = preceptoria_acuerdos.id_area and fec='$acuerdos[fec]' and alumno=$alumno where areas_valor.ciclo=(select max(ciclo) from preceptoria_acuerdos where fec='$acuerdos[fec]' and alumno=$alumno) order by area");
			$j=1;
			while($acuerdoFecha=mysql_fetch_array($resultFecha)){
				$asistP=$acuerdoFecha[padre]==1?"true":"false";
				$asistM=$acuerdoFecha[madre]==1?"true":"false";
				$acuFec=$acuerdoFecha[acuerdo];
				$acuFec=mysql_real_escape_string($acuFec);
			echo "document.getElementById('acuerdo$j').value='$acuFec';
			document.getElementById('acuerdo$j').disabled=true;
			";
			$j++;
			}
			echo "document.getElementById('obsPadre').value='$acuerdoFecha[obs_padre]';
			document.getElementById('obsPadre').disabled=true;
			document.getElementById('obsMadre').value='$acuerdoFecha[obs_madre]';
			document.getElementById('obsMadre').disabled=true;
			document.getElementById('obsPre').value='$acuerdoFecha[obs_Pre]';
			document.getElementById('obsPre').disabled=true;
			document.getElementById('padre').checked=$asistP;
			document.getElementById('padre').disabled=true;
			document.getElementById('madre').checked=$asistM;
			document.getElementById('madre').disabled=true;
			document.getElementById('impr').style.visibility='visible';
			document.getElementById('grab').style.visibility='hidden';
			";
			echo "break;
		";
			$id++;
		}
	echo "default: break;}
	}
	</script>
	";
	//$t = $_SERVER['PHP_SELF'];
 	echo "<form id=\"fr\" name=\"fr\" action=\"acuerdos.php?alumno=$alumno\" method=POST>";
	$fecha_mes=date("F");
	switch($fecha_mes){
		case("January"): $fecha_mes="Enero"; break;
		case("February"): $fecha_mes="Febrero"; break;
		case("March"): $fecha_mes="Marzo"; break;
		case("April"): $fecha_mes="Abril"; break;
		case("May"): $fecha_mes="Mayo"; break;
		case("June"): $fecha_mes="Junio"; break;
		case("July"): $fecha_mes="Julio"; break;
		case("August"): $fecha_mes="Agosto"; break;
		case("September"): $fecha_mes="Septiembre"; break;
		case("October"): $fecha_mes="Octubre"; break;
		case("November"): $fecha_mes="Noviembre"; break;
		case("December"): $fecha_mes="Diciembre"; break;
		default: break;
	}
	$fecha_dia=date("j");
	$fecha_anio=date("Y");
	$fecha_hora=date("g:i a");
 	$today=$fecha_dia.' de '.$fecha_mes.', '.$fecha_anio.'. '.$fecha_hora;
	$c = "<body onload=cambia_acuerdos('') bgcolor='$fondo' alink=black vlink=blue>
	<style>
		
		 .t th, td{
				width: auto;/
				height: 25px; /*Aquí el Alto*/
			}
			
			hr{ border: 0; height: 1px; background: #333; background-image: linear-gradient(to right, #ccc, #333, #ccc); }
		
	</style>
			<table valign=top cellpadding=0 cellspacing=0 width=100% style=\"font-family: Arial; font-size: 12pt;\" class='t'>
			<tr>
				<th>Captura de Acuerdos</th>
				<th width='10'>
				&nbsp;
				</th>
				<td align='right'>
					$today					
					<input type=hidden name=opc value=$opc>
				</td>					
			</tr>
			<tr>
				<td colspan=3>
					<hr>
				";
			$c.= "<select name=fec onchange=\"fr.submit();\"><option value=''>(Nueva captura)</option>";
	$rm=mysql_query("select distinct fec from preceptoria_acuerdos where alumno=$alumno order by fec");
	$tr=mysql_num_rows($rm);
	$s=0;
	
	for($i = 0; $i < $tr; $i ++) {
		$d = mysql_fetch_array ( $rm );
		$f = date("d-m-Y", strtotime($d[0]));
		if ($d[0] == $fec) {
			$slc = "selected";
			$s = 1;
		}
		else
			$slc = "";
		$c .= "<option value='".($f)."' $slc>$f</option>";
	}
/////////////////////
if($fec!=''){
$d=substr($fec,0,2);
$m=substr($fec,3,3);
$y=substr($fec,6,10);
$fe=$y.'-'.$m.''.$d;

	$rstF_ = mysql_query ("SELECT * FROM preceptoria_acuerdos where alumno=$alumno and fec='$fe'",$link) or die ("SELECT * FROM preceptoria_acuerdos where alumno=$alumno and fec='$fe'".mysql_error());
		while($rsF_=mysql_fetch_array($rstF_))
		  {
			$pcheck_=$rsF_['padre'];
			$mcheck_=$rsF_['madre'];
			$acuerdo_=$rsF_['acuerdo'];
			$opadre_=$rsF_['obs_padre'];
			$omadre_=$rsF_['obs_madre'];
			$oPre_=$rsF_['obs_Pre'];
	
		  }
}

////////////////////
if($fec!='')
{
	if($pcheck_==1)
		$pcheck = "checked";
	if($mcheck_==1)
		$mcheck = "checked";
	
}
	$c .= utf8_encode("			</select>
					
				</td>
			</tr>
				<td colspan='2'>
					Alumno: $NomAlumno
				</td>
				<td>
					$Secc $Grado - $Grupo
				</td>
			<tr>
				<td colspan='3' align=center>
					<input $pcheck type=checkbox name=padre id=padre>Asiste $padre
					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					<input $mcheck type=checkbox name=madre id=madre>Asiste $madre
				</td>
			</tr>
			<tr>
				<td colspan=3>
					<hr>
				</td>
			</tr>");

			$idMod=$idAcrd[$i];

 			$acrd=$acuerdo[$i];
			if($acuerdo[$i]=="")
				$acrd=" ";
			if($fec!='')
			{
				$acrdE=$acuerdo_;
				$acrdE=nl2br($acrdE);
			}
			
/*	echo" <script language='javascript'>alert('SELECT * FROM preceptoria_acuerdos where alumno=$alumno and fec=$fe and ciclo=$periodo_actual .... ');</script>";*/

			if($fec!='')
			{
				$c.=utf8_encode("<tr>
					<td colspan='100%'>
						Acuerdos en la Entrevista:<br>	
          				$acrdE 
        			</td>
        		</tr>");
			}
			else
			{
			$c.="	<input type=hidden name=id$i value=$idMod>
					<input type=hidden name=idArea$i value=$idA>
					<tr>
					<td colspan='100%' >
						Acuerdos en la Entrevista:<br>
					
<textarea $dataRO id=\"acuerdo1\" name=\"acuerdo1\" cols=106 rows=4 style=\"background-color:$clrA\">$acrdE</textarea>
        			</td>
        		</tr>";
				}
			//$i++;
        //}
    //}
	$opadre=$obs_p;
	if($obs_p=="")
	 	$opadre=" ";
	$omadre = $obs_m;
	if($obs_m=="")
	 	$omadre=" ";
	$oPre = $obs_pre;
	if($obs_pre=="")
	 	$oPre=" ";
	
	if($fec!='')
	{
		$opadre=$opadre_;
		$omadre=$omadre_;
		$oPre=$oPre_;
	}
			
	$c.="
			<tr>
			<td>Observaci&oacute;n padre:<br>
					<label><textarea $dataRO id='obsPadre' name='obsPadre' cols=50 rows=2>$opadre</textarea></label>
			</td>
			<td>
			</td>
			";
	$c.="
			<td>Observaci&oacute;n madre:<br>				
						<label><textarea $dataRO id='obsMadre' name='obsMadre' cols=50 rows=2>$omadre</textarea></label>
			</tr> ";
	$c.="<tr>
			<td colspan='3'>Observaci&oacute;n preceptor:<br>
						<label><textarea $dataRO id='obsPre' name='obsPre' cols=50 rows=2>$oPre</textarea></label>
					</td>
			</tr> ";
	//if(!$fec){
					
	$preNum=mysql_query("SELECT * from alumnos where alumno=$alumno");
	$preceptor_Num=mysql_fetch_array($preNum);
	$preceptor_Num = $preceptor_Num['preceptor'];
	$preNom=mysql_query("SELECT * from personal WHERE empleado = $preceptor_Num");
	$preceptor_Nom = mysql_fetch_array($preNom);
	$nombre_preceptor = $preceptor_Nom['apellido_paterno'].", ".$preceptor_Nom['nombre'];
			
		$c.="<tr>
				<td>".utf8_encode($nombre_preceptor)."</td>
				<td>
				</td>
				<td align='right'>
		<input type=button id='grab' name=g value=\"   Grabar   \" onClick=\"if (NoVacios()) {window.open('encuesta.php?alumno='+$alumno);Action('fr',1)}\";>
		";
		//if($imp==1)
			$c.="<input type=button id='impr' name=g value=\"Imprimir\" onClick=\"Action('fr',2)\";>
			";
	
    //}
   
    
	
		$c.="</td>	
		</tr>";
     $c.="</table>";
    echo $c;
	
?>
</form>
</body>
</html>