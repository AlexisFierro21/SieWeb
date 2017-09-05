<?
include ('../connection.php');
$link=mysql_connect($server,$userName,$password) or die('No se pudo conectar: '.mysql_error());
mysql_query("SET CHARACTER SET 'utf8'");
mySql_select_db($DB) or die("No se pudo seleccionar DB");
mysql_query("SET CHARACTER SET 'utf8'");
$opc=$_REQUEST['opc'];
$action=$_REQUEST['action'];
$alumno=$_REQUEST['alumno'];
$sec=$_REQUEST['sec'];
$area=$_REQUEST['av'];
$asp=$_REQUEST['id'];
if($opc==1){
	$idMod=$_REQUEST['idMod'];
	$obs=$_REQUEST['obs'];
	$color=$_REQUEST['color'];
	if(!$idMod){
		if($_POST[es_area]==1)
			$save="insert into preceptoria_boleta (alumno, fec, id_area, id_aspecto, observaciones) values ($alumno, CURDATE(), $area, $asp, '$obs');";
		else
			$save="insert into preceptoria_boleta (alumno, fec, id_area, id_aspecto, color) values ($alumno, CURDATE(), $area, $asp, '$color');";
	}
	else{
		if($_POST[es_area]==1)
			$save="update preceptoria_boleta set observaciones='$obs' where id=$idMod;";
		else
			$save="update preceptoria_boleta set color=$color where id=$idMod;";
	}
	mysql_query($save) or die(mysql_error());
	$fin=$_REQUEST['fin'];
	if($_REQUEST['fin']=='on'){
		if($_POST[es_area]==1)
			$save="update preceptoria_boleta set ultprc=(select max(preceptoria) from preceptoria where alumno=$alumno and fin=1) where alumno=$alumno and (ultprc=0 or ultprc=NULL);";
		else
			$save="update preceptoria_boleta set color=$color where id=$idMod;";
		mysql_query($save) or die(mysql_error());
	}
	$opc = 0;
}

if($area){
	$sqlt="Select observaciones as o, color as c, id from preceptoria_boleta where Alumno = $alumno and id_area=$area and id_aspecto=$asp;";
	$rst=mysql_fetch_array(mysql_query($sqlt));
	$obs = $rst ['o'];
	//$color = $rst ['c'];
	$idMod = $rst ['id'];			
}
$abrirtodo=$_REQUEST['todos'];
if($abrirtodo=='on' || $abrirtodo==1) {
	$todos = 'checked';
	$action = "click";
	$abrirtodo = 1;
}
else{
	$todos = "";
	$abrirtodo = 0;
}

function drawBlanksIntersecs($i, $last) {
	for($ii = 1; $ii < $i + 1; $ii ++) {
		if ($last && $ii == 1)
			$img = "trv_blank.gif";
		else
			$img = "trv_nointersec.gif";
		echo "<img src=images/$img height=18 width=18 border=0 vspace=0 hspace=0 align=left>";
	}
}

function buildTreeView($action, $alumno, $area, $asp, $Abrir, $abrirtodo){
	$sql = "select 	0 as lvl, id_area_valor as av, 0 as asp, nombre, 
			(select observaciones as o from preceptoria_boleta 
			where Alumno = $alumno and id_area = a.id_area_valor and id_aspecto = 0) as obs, 
			(select min(color) from preceptoria p inner join areas_valor_aspectos s on p.id_aspecto = s.id 
			where id_area_valor =a.id_area_valor and alumno = $alumno) as color,  id_area_valor as id
			from areas_valor a
			where ciclo = ($_SESSION[periodo_seleccionado])
			union ALL
			select 	1, s.id_area_valor, s.id, s.nombre, 
			(select observaciones as o from preceptoria_boleta 
			where Alumno = $alumno and id_area = s.id_area_valor and id_aspecto = s.id) as obs, 
			(select min(color) from preceptoria p where p.id_aspecto = s.id and p.alumno =  $alumno), s.id
			from areas_valor_aspectos s  
			where s.ciclo = ($_SESSION[periodo_seleccionado])
			UNION ALL
			select 	2, a.id_area_valor, id_aspecto, concat(preceptoria, '-', observaciones), '', color, p.id
			from preceptoria p inner join areas_valor_aspectos s on s.id = p.id_aspecto 
			inner join areas_valor a on s.id_area_valor = a.id_area_valor
			where alumno = $alumno
			order by 2, 3, 1, 4";
	if($rs=mysql_query($sql)){
		$parent=mysql_fetch_array($rs);
		$EOF=0;
		$nReg=1;
		while(!$EOF){
			$lvl=$parent['lvl'];
			if($lvl==1)
				$id=$parent['id'];
			else
				$id=0;
			$av=$parent['av'];
			$aspecto=$parent['asp'];
			$nme=$parent['nombre'];
			$color=$parent['color'];
			if($abrirtodo==1 || ($lvl==0 || ($lvl==1 && $av==$area) || ($lvl==2 && $av==$area && $aspecto==$asp && $action!="close"))){
				$l = 70;
				$i = 0;
				unset($name);
				while(strlen($nme)>$l){
					$n = substr ( $nme, 0, $l - 1 );
					if (! $p = strripos ( $n, " " ))
						$p = $l - 1;
					$name [] = substr ( $nme, 0, $p );
					$nme = substr ( $nme, $p, strlen ( $nme ) - 1 );
					
					$i ++;
				}
				
				$name [] = $nme;
				
				echo "<tr valign=top>
	               			<td class=TitStyle nowrap>";
				
				drawBlanksIntersecs ( $lvl, $nr == $recs - 1 );
				
				if ($parent ['obs'] == "") $fldr = "C"; else $fldr = "A";
				
				if (! $parent = mysql_fetch_array ( $rs ))
					$EOF = 1;
				
				$BranchEnd = mysql_num_rows ( $rs ) == $i || $parent ['lvl'] != $lvl;
				$lvlp = $lvl+1;
				
				//echo "T$abrirtodo A$action Ar$av Ar2$area L$lvl I$id IO$idOpen";
				if (($abrirtodo == 1) || ($action == "expand" && (($lvl == 0 && $av == $area) || ($lvl == 1 && $av == $area && $aspecto == $asp)))) {
					if ($lvl < 2)
						echo "<a href=captura_boleta_eval.php?action=close&alumno=$alumno&sec=$lvlp&&av=$av&id=$id&todos=$abrirtodo>";
					
					if ($BranchEnd)
						echo "<img src=images/trv_intersecminus_end.gif height=18 width=18 border=0 vspace=0 hspace=0 align=left>";
					else
						echo "<img src=images/trv_intersecminus.gif height=18 width=18 border=0 vspace=0 hspace=0 align=left>";
				} else {
					if ($lvl < 2)
						echo "<a href=captura_boleta_eval.php?action=expand&alumno=$alumno&sec=$lvlp&av=$av&id=$id&todos=$abrirtodo>";
					
					if ($BranchEnd)
						echo "<img src=images/trv_intersecplus_end.gif height=18 width=18 border=0 vspace=0 hspace=0 align=left>";
					else
						echo "<img src=images/trv_intersecplus.gif height=18 width=18 border=0 vspace=0 hspace=0 align=left>";
				}
				if($lvl==0){
					$suma=0;
					$total=0;
					$colorResult=mysql_query("select color from preceptoria_boleta where alumno=$alumno and id_area=$av");
					while($tmp1=mysql_fetch_array($colorResult)){
						$suma+=$tmp1[color];
						$total++;
					}
					if($total==0)
						$promedio=0;
					else
						$promedio=round($suma/$total);
					if($promedio>3)
						$promedio=3;
					if($promedio<1)
						$promedio=0;
					echo "<img src=images/$promedio$fldr.png height=18 width=18 border=0 vspace=0 hspace=0 align=left>";
				}
				if($lvl>0){
					if($color=='')
						$color=0;
					echo "<img src=images/$color$fldr.png height=18 width=18 border=0 vspace=0 hspace=0 align=left>";
				}
				if($lvl<2){
					if($lvl==1){
					$tmp=mysql_fetch_array(mysql_query("select color from preceptoria_boleta where alumno=$alumno and id_area=$av and id_aspecto=$id"));
					echo "</font></a>";
					switch($tmp[color]){
						case 1: $font="orange";
							break;
						case 2: $font="cyan";
							break;
						case 3: $font="limegreen";
							break;
						default: $font="";
							break;
					}
					echo "<a href=captura_boleta_eval.php?alumno=$alumno&action=click&sec=$lvlp&av=$av&id=$id&todos=$abrirtodo><font color=$font>";
					}
					else{
						echo "</a>";
						echo "<a href=captura_boleta_eval.php?alumno=$alumno&action=click&sec=$lvlp&av=$av&id=$id&todos=$abrirtodo>";
					}
				}
				
				$i = 0;
				foreach ( $name as $n ) {
					if ($i > 0) {
						drawBlanksIntersecs ( $lvl + 1, false );
						echo "<img src=images/trv_blank.gif height=18 width=18 border=0 vspace=0 hspace=0 align=left>";
						echo "<img src=images/trv_blank.gif height=18 width=18 border=0 vspace=0 hspace=0 align=left>";
					}
					echo "&nbsp;&nbsp;" . $n . "<br>";
					$i ++;
				
				}
				
				echo "</td>";
				echo "</tr>";
			}
			else
			{
				if (! $parent = mysql_fetch_array ( $rs )) $EOF = 1;
			}	
				
			$nReg++;
		}
	}
}
?>