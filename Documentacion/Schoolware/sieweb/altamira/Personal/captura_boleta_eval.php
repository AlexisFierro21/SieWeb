<?session_start();?>
<script>
function Action(f, o){
	with (document.forms[f]){
		opc.value = o;
		submit();
	}
}
function show_confirm(){
	if(document.getElementById('fin').checked==true){
		var r=confirm("¿Estás seguro de que deseas cerrar la captura en proceso?");
		if (r==true)
			Action('fr',1);
		else
			document.getElementById('fin').checked=false;
	}
	Action('fr',1);
}
</script>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Captura Preceptor&iacute;a</title>
</head>
  		
 <?php
	include "captura_boleta_eval.inc";
	if(isset($_POST['periodo_select']) && $_POST['periodo_select']!='' && $_POST['periodo_select']!=null)
		$_SESSION['periodo_seleccionado']=$_POST['periodo_select'];
	switch ($color){
		case 1: 	$clr="orange"; break;
		case 2: 	$clr="cyan"; break;
		case 3:		$clr="limegreen";		
	}
	
	switch ($colorAnt){
		case 1: 	$clrA="orange"; break;
		case 2: 	$clrA="cyan"; break;
		case 3:		$clrA="limegreen";		
	}
	$prcpAnt = $pOpen-1;

	$t = $_SERVER['PHP_SELF'];
 	echo "<form id=\"fr\" name=\"fr\" action=\"$t?alumno=$alumno&av=$area\" method=POST>";
	
 	$c = "	<body  bgcolor=LemonChiffon alink=black vlink=blue>
		    
			<input type=hidden name=idMod value=$idMod>
			<input type=hidden name=opc value=$opc>
			
			<input type=hidden name=prcp value=$pOpen>
			<input type=hidden name=id value=$asp>
			
			<td align=center>
				
			</td>
			
			<table valign=top border=0 cellpadding=0 cellspacing=0 width=100% style=\"font-family: Arial; font-size: 12pt;\">";

 	//La sección se manda +1 para evitar parametros ceros y se restaura --
 	if ($action == 'click' && ($sec == 1 || $sec == 2))
 	{
 		$sec--;
	    switch ($sec)
	    {
	    	case 0:
	    		$s = "Select nombre from areas_valor where id_area_valor = $area;";
	    		
			    $rm = mysql_fetch_array(mysql_query($s));
			    $nom = $rm['nombre'];
				$c.= "	<tr>
			    			<td></td>
			    		    <td valign=top align=right rowspan=1000 style=\"font-family: Arial; font-weight: bold; font-size: 12pt;\">
			    		    
							$nom<br><br>";
				break;
	    	case 1:
			    $rm = mysql_fetch_array(mysql_query("Select nombre from areas_valor_aspectos where id = $asp;"));
			    $nom = $rm['nombre'];
	    
				$c.= "	<tr>
			    			<td></td>
			    		    <td valign=top align=right rowspan=1000 style=\"font-family: Arial; font-weight: bold; font-size: 12pt;\">
							$nom<br><br>";
	    }					

	    if($sec==0){
			$o = "Area de oportunidad"; //else $o = "Observaciones";
			$c.="$o:<br><textarea name=\"obs\" id=obs cols=30 rows=6>$obs</textarea><br>";
			$c.="<input type='hidden' name='es_area' id='es_area' value='1'>";
	    }
		if($sec!=0){
			//echo '<script language="javascript" type="text/javascript">alert("'.$idMod.'");</script>';
			if(!empty($idMod))
				$tmp=mysql_fetch_array(mysql_query("select color from preceptoria_boleta where id=$idMod"));
			if($tmp[color]!=NULL)
				$color=$tmp[color];
			//echo '<script language="javascript" type="text/javascript">alert("'.$color.'");</script>';
			$c.= "Color:&nbsp;<select name=color><option value=0></option><option value=1"; if ($color==1) $c.= " selected";
			$c.=">Naranja</option><option value=2"; if ($color==2) $c.= " selected";
			$c.=">Azul</option><option value=3"; if ($color==3) $c.= " selected";
			$c.=">Verde</option></select><br><br>";
			$c.="<input type='hidden' name='es_area' id='es_area' value='0'>";
	    }
	    $c.= "			<font size=2pt>Finalizar captura</font><input type=checkbox name=fin id=fin><br>
	          			<input type=button name=g value=\"   Grabar   \" onClick='show_confirm();'>
	          			    
	          		</td>";
    }
    		
    $c.= "		<tr>
    			<td valign=top style=\"font-family: Arial; font-weight: bold; font-size: 8pt;\">
    				<p><img src=images/gphoto.png> Mostrar todo <input type=checkbox name=\"todos\" $todos size=3 maxlength=3 onclick=\"this.form.submit();\">
        		</td>";
    if (!$area)
		$c.= "	<td><font size=5 color=#FF0000><u>Captura de Boleta de Evaluaci&oacute;n ($_SESSION[periodo_seleccionado])</u></font></th>";
	$c.="<br><form action='captura_preceptoria.php' method='post' name='form1' id='form1'><select name='periodo_select' onChange='submit()'>";
	$periodo_actual=mysql_result(mysql_query("select periodo from parametros"),0);
	for($i=$periodo_actual;$i>2010;$i--){
		$seleccionado='';
		if($i==$_SESSION['periodo_seleccionado'])
			$seleccionado='selected';
		$c.="<option value=$i $seleccionado>$i</option>";
	}
	$c.="</select></form>";
    $c .= "		</tr>";
    echo $c;
    buildTreeView($action, $alumno, $area, $asp, $Abrir, $abrirtodo);
?>
<script>document.forms.fr['obs'].focus();</script>

</form>
</body>
</html>
