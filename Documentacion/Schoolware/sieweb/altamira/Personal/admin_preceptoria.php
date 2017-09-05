<script>
	function Action(f, o)
	{
		with (document.forms[f])
		{
			opc.value = o;
			submit();
		}
	}
</script>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<!-- <meta http-equiv="Content-Type" content="text/html; charset=utf-8" /> -->
 <meta http-equiv="Content-Type" content="text/html; charset=ISO8859-1"> 
<title>Adminitraci&oacute;n Preceptor&iacute;a</title>
</head>
  		
 <?php
	include "admin_preceptoria.inc";
	
	$t = $_SERVER ['PHP_SELF'];
	echo "<form id=\"fr\" name=\"fr\" action=\"$t\" method=POST>";
	
	$c = "	<body  bgcolor=LemonChiffon alink=black vlink=blue>
		    
			<input type=hidden name=idArea value=$idArea>
			<input type=hidden name=idAsp value=$idAsp>
			<input type=hidden name=opc value=$opc>
			
			<td align=center>
				
			</td>
			
	<table valign=top border=1 cellpadding=0 cellspacing=0 width=100% style=\"font-family: Arial; font-size: 16pt;\">
		<tr>
			<td width=55% align=center bgcolor=#00658A><br>Control de Areas<br><br></td>
			<td width=45% align=center bgcolor=#00658A><br>Restricciones<br><br></td>
		</tr>
		
		<tr>
		<td align=center>";
	
	$c .= "	<table valign=top border=0 cellpadding=0 cellspacing=0 width=100% style=\"font-family: Arial; font-size: 10pt;\">
			
			<tr>
				<td cols>
					Ciclo:
				</td>
				<td> 
					<select name=ciclo onchange=\"this.form.submit()\">
			";
	
	if ($CicloA == "") {
		$rm = mysql_fetch_array ( mysql_query ( "select periodo from parametros;" ) );
		$CicloA = $rm [0];
	}
	
	$rm = mysql_query ( "select distinct(ciclo) from areas_valor order by 1;" );
	$tr = mysql_num_rows ( $rm );
	$s = 0;
	
	for($i = 0; $i < $tr; $i ++) {
		$d = mysql_fetch_array ( $rm );
		$ciclo = $d [0];
		if ($ciclo == $CicloA) {
			$slc = "selected";
			$s = 1;
		} else
			$slc = "";
		
		$c .= "<option value=$ciclo $slc>$ciclo</option>";
	}
	
	if ($s == 0)
		$c .= "<option value=$CicloA selected>$CicloA</option></select>";
	else
		$c .= "</select>";
	
	$c .= "		</td>
			</tr>
			<tr><td colspan=2><hr></td></tr>
			<tr>
				<td>
		 			Area: 
		 		</td>
		 		<td>
		 			<select name=area onchange=\"this.form.submit()\"><option value=0>(Nueva)</option>";
	
	$rm = mysql_query ( "Select * from areas_valor where ciclo = $CicloA order by area;" );
	$tr = mysql_num_rows ( $rm );
	$s = 0;
	
	for($i = 0; $i < $tr; $i ++) {
		$d = mysql_fetch_array ( $rm );
		$id = $d [0];
		$nu = $d [2];
		$no = $d [3];
		
		if ($id == $AreaA) {
			$slc = "selected";
			$s = 1;
		} else
			$slc = "";
		
		$c .= "<option value=$id $slc>$nu - $no</option>";
	}
	
	$c .= "			</select>
    			</td>
    		</tr>
    		<tr><td colspan=2><hr></td></tr>
    		<tr>
	 			<td>
    				N&uacute;mero &aacute;rea: 
	    		</td>
    			<td>
    				<input name=num type=text id=num size=2 maxlength=2 value=$num>
    			</td>
    		</tr>
    		<tr>
    			<td>
    				Nombre &aacute;rea:
    			</td>
    			<td>
    				<input name=nom type=text id=nom  size=50 maxlength=50 value=\"$nom\">
    			</td>
    		</tr>
    		<tr><td colspan=2><br><hr></td></tr>";
	
	if ($AreaA != 0) {
		$c .= "	<tr>
    			<td>
    				Aspecto:
    			</td>
    			<td>    
		 			<select name=aspecto onchange=\"this.form.submit()\"><option value=0>(Nuevo)</option>";
		
		$rm = mysql_query ( "Select * from areas_valor_aspectos where id_area_valor = $AreaA order by aspecto;" );
		$tr = mysql_num_rows ( $rm );
		$s = 0;
		
		for($i = 0; $i < $tr; $i ++) {
			$d = mysql_fetch_array ( $rm );
			$id = $d [0];
			$nu = $d [3];
			$no = $d [4];
			
			if ($id == $AspA) {
				$slc = "selected";
				$s = 1;
			} else
				$slc = "";
			
			$c .= "<option value=$id $slc>$nu - $no</option>";
		}
		
		$c .= "			</select>
    			</td>
    		</tr>
    		<tr><td colspan=2><hr></td></tr>
    		<tr>
	 			<td>
    				N&uacute;mero aspecto: 
	    		</td>
    			<td>
    				<input name=numasp type=text id=num size=2 maxlength=2 value=$numasp>
    			</td>
    		</tr>
    		<tr>
    			<td>
    				Nombre aspecto: 
    			</td>
    			<td>
    				<input name=nomasp type=text id=nom  size=50 maxlength=50 value=\"$nomasp\">
    			</td>
    		</tr>
    		<tr>
    			<td></td>
    			<td>
    				<input type=checkbox name=\"vis\" $vis size=3 maxlength=3> Visible en boleta
    			</td>
    		</tr>
    		<tr><td colspan=2><br><br><hr></td></tr>";
	}
	
	$c .= "	<tr>
    			<td></td>
    			<td>
    				<input type=button name=g value=\"Grabar\" onClick=\"Action('fr',1)\">
   				</td>
   			</tr>
			<tr><td><br></td></tr>
   		</table>
   	</td>
   	<td align=center style=\"font-family: Arial; font-size: 10pt;\"> 
   		<input type=checkbox name=\"Permite\" $permite size=3 maxlength=3 onClick=\"this.form.submit();\">Permitir modificaciones Preceptor&iacute;as Anteriores";
	";
   	</td>
   	</table>";
	
	echo $c;
	?>
</form>
</body>
</html>
