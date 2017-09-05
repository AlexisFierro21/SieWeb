<?php
include ('../connection.php');
$link = mysql_connect ( $server, $userName, $password ) or die ( 'No se pudo conectar: ' . mysql_error () );
mySql_select_db ( $DB ) or die ( "No se pudo seleccionar DB" );

$opc = $_REQUEST ['opc'];

$action = $_REQUEST ['action'];
$alumno = $_REQUEST ['alumno'];

$sec = $_REQUEST ['sec'];
$area = $_REQUEST ['av'];
$asp = $_REQUEST ['id'];

if ($opc == 1) {
	$idMod = $_REQUEST ['idMod'];
	$obs = $_REQUEST ['obs'];
	$color = $_REQUEST ['color'];
	
	if (! $idMod)
		$save = "insert into preceptoria_boleta (alumno, fec, id_area, id_aspecto, observaciones, color) values
												($alumno, CURDATE(), $area, $asp, '$obs', $color);";
	else
		$save = "update preceptoria_boleta set observaciones = '$obs', color = $color where id = $idMod";
	
	mysql_query ( $save );
	
	$fin = $_REQUEST ['fin'];
	
	if ($_REQUEST ['fin'] == 'on') {
		$save = "update preceptoria_boleta set ini = 1, fin = 1 where alumno = $alumno and ini = 0;";
		mysql_query ( $save );
	}
	$opc = 0;
}


$sqlt = "Select observaciones as o, color as c, id from preceptoria_boleta 
 		where Alumno = $alumno and id_area = $area and id_aspecto = $asp;";
$rst = mysql_fetch_array ( mysql_query ( $sqlt ) );
$obs = $rst ['o'];
$color = $rst ['c'];
$idMod = $rst ['id'];			


$abrirtodo = $_REQUEST ['todos'];
if ($abrirtodo == 'on' || $abrirtodo == 1) {
	$todos = 'checked';
	$action = "expand";
	$abrirtodo = 1;
} else {
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

function buildTreeView($action, $alumno, $area, $asp, $Abrir, $abrirtodo) {
	
	$sql = "select 	0 as lvl, id_area_valor as av, 0 as asp, nombre, '' as obs, 
					(select min(color) from preceptoria p inner join areas_valor_aspectos s on p.id_aspecto = s.id 
							where id_area_valor =a.id_area_valor and alumno = $alumno) as color,  id_area_valor as id
			from areas_valor a
			where ciclo = (select periodo from parametros)
			
			union ALL
			
			select 	1, s.id_area_valor, s.id, s.nombre,  '',
										(select min(color) from preceptoria p where p.id_aspecto = s.id and p.alumno =  $alumno),
										s.id
						
						from areas_valor_aspectos s  
						where s.ciclo = (select periodo from parametros)

			UNION ALL

			select 	2, a.id_area_valor, id_aspecto, concat(preceptoria, '-', observaciones), '', color, p.id
			from preceptoria p inner join areas_valor_aspectos s on s.id = p.id_aspecto 
							inner join areas_valor a on s.id_area_valor = a.id_area_valor
			where alumno = $alumno

			order by 2, 3, 1, 4";
	
	if ($rs = mysql_query ( $sql )) {
		$parent = mysql_fetch_array ( $rs );
		$EOF = 0;
		$nReg = 1;
		
		while ( ! $EOF ) {
			$lvl = $parent ['lvl'];
			if ($lvl == 1) $id = $parent ['id']; else $id = 0;
			
			$av = $parent ['av'];
			$nme = $parent ['nombre'];
			
			$color = $parent ['color'];
			$aspecto = $parent['asp'];

			if ($lvl == 0 || ($lvl == 1 && $av == $area ) || ($lvl == 2 && $av == $area && $aspecto = $asp && $action == "expand"))
			{
				$l = 70;
				$i = 0;
				unset ( $name );
				while ( strlen ( $nme ) > $l ) {
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
				
				if (! $parent = mysql_fetch_array ( $rs ))
					$EOF = 1;
				
				$BranchEnd = mysql_num_rows ( $rs ) == $i || $parent ['lvl'] != $lvl;
				$lvlp = $lvl+1;
				
				//echo "T$abrirtodo A$action Ar$av Ar2$area L$lvl I$id IO$idOpen";
				if (($abrirtodo == 1) || ($action == "expand" && (($lvl == 0 && $av == $area) || ($lvl == 1 && $aspecto == $asp)))) {
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

				if ($color == '') $color = 0;
				echo "<img src=images/$color.ico height=18 width=18 border=0 vspace=0 hspace=0 align=left>";
				
				
				if ($lvl < 2) {
					echo "</a>";
					echo "<a href=captura_boleta_eval.php?alumno=$alumno&action=click&sec=$lvlp&av=$av&id=$id&todos=$abrirtodo>";
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