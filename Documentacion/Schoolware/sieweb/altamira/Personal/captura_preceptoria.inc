<?php
include('../connection.php');
$link = mysql_connect($server, $userName, $password) or die('No se pudo conectar: ' . mysql_error());
mySql_select_db($DB)or die("No se pudo seleccionar DB");
mysql_query("SET CHARACTER SET 'utf8'");

$eliminar_preceptoria=$_REQUEST['eliminar_preceptoria'];

$opc=$_REQUEST['opc'];
$action=$_REQUEST['action'];
$alumno=$_REQUEST['alumno'];
$pOpen=$_REQUEST['prcp'];
$sec=$_REQUEST['sec'];
$av=$_REQUEST['av'];
$asp=$_REQUEST['asp'];
$fecha=$_POST[fecha];
if($fecha==NULL or $fecha=='')
	$fecha=mysql_result(mysql_query("select curdate()"),0);
if($opc==1)
{
	$idMod=$_REQUEST['idMod'];
	$obs=$_REQUEST['obs'];
	$color=$_REQUEST['color'];
	$validafecha = "select max(preceptoria) as num_preceptoria from preceptoria where ciclo=$_SESSION[periodo_seleccionado] and alumno=$alumno and preceptoria<>$pOpen and fecha='$fecha'";
	$rstValidaFecha = mysql_fetch_array(mysql_query($validafecha));
	$num_preceptoria = $rstValidaFecha['num_preceptoria'];
	if ($num_preceptoria > 0)
	{
		echo" <script language='javascript'>alert('No fue posible grabar la información porque la Preceptoría ' + $num_preceptoria + ' tiene la misma fecha. Deberá capturarla con una fecha diferente.');</script>";
	}
	else
	{
		if(!$idMod)
			$save = "insert into preceptoria (alumno, ciclo, preceptoria, id_aspecto, observaciones, color) values ($alumno, ($_SESSION[periodo_seleccionado]), $pOpen, $asp, '$obs', $color)";
		else
			$save = "update preceptoria set observaciones='$obs',color=$color where id=$idMod";
		mysql_query($save);
		mysql_query("update preceptoria set fecha='$fecha' where ciclo=$_SESSION[periodo_seleccionado] and alumno=$alumno and preceptoria=$pOpen");
		$fin = $_REQUEST['fin'];
		if($_REQUEST['fin']=='on')
		{
			$save = "update preceptoria set fin = 1 where alumno = $alumno and preceptoria = $pOpen and ciclo = ($_SESSION[periodo_seleccionado]);";
			mysql_query($save);
		}
		$opc=0;
	}
}
if($opc==2){
	mysql_query("update test_resultados_areas set comentario='$_POST[comentarioTest]', sugerencia='$_POST[sugerenciaTest]' where id_publicacion=$_POST[id_publicacion] and alumno=$alumno and id_area=$av and id_aspecto=$asp");
	$opc=0;
}
switch($sec){
	case 2:{
		$sqlt="Select observaciones as o, color as c, id from preceptoria where ciclo=$_SESSION[periodo_seleccionado] and Alumno=$alumno and preceptoria=$pOpen and id_aspecto=$asp;";
		 $rst = mysql_fetch_array(mysql_query($sqlt));
		 $obs = $rst['o'];
		 $color = $rst['c'];
		 $idMod = $rst['id'];
		 
		 $sqlt="Select observaciones as o, color as c from preceptoria where ciclo=$_SESSION[periodo_seleccionado] and Alumno=$alumno and preceptoria=$pOpen-1 and id_aspecto=$asp;";
		 $rst = mysql_fetch_array(mysql_query($sqlt));
		 $obsAnt = $rst['o'];
		 $colorAnt = $rst['c'];
		
		} break;
	case 3:{
		
	}
}

$abrirtodo = $_REQUEST['todos'];
if ($abrirtodo == 'on' || $abrirtodo == 1) 
{
	$todos = 'checked';
	$action="expand";
	$abrirtodo = 1;
}
else 
{
	$todos = "";
	$abrirtodo = 0;
}

function drawBlanksIntersecs($i, $last)
{
	for ($ii=1; $ii<$i+1; $ii++)
	{
		if ($last && $ii==1) $img = "trv_blank.gif"; else $img = "trv_nointersec.gif";
		echo "<img src=images/$img height=18 width=18 border=0 vspace=0 hspace=0 align=left>";
	}
}

function buildTreeView($action, $alumno, $pOpen, $av, $asp, $abrirtodo){
$sql_p = "Select count(*) from preceptoria where ciclo=$_SESSION[periodo_seleccionado] and alumno = $alumno and fin <> 1;";
$r = mysql_fetch_array(mysql_query($sql_p));
if($r[0]==0)
	$sql_p="Select distinct(preceptoria) from preceptoria where ciclo=$_SESSION[periodo_seleccionado] and alumno = $alumno union all Select ifnull(max(preceptoria), 0)+1 from preceptoria where ciclo=$_SESSION[periodo_seleccionado] and alumno = $alumno;";
else
	$sql_p="Select distinct(preceptoria) from preceptoria where ciclo=$_SESSION[periodo_seleccionado] and alumno = $alumno;";
$r=mysql_query($sql_p) or die ("$sql_p".mysql_error()); 
$recs=mysql_num_rows($r);
for($nr=0;$nr<$recs;$nr++){
	$prcp=mysql_fetch_array($r);
	$num_prcp=$prcp['preceptoria'];
	$lvl=0;
	$name="PRECEPTORIA $num_prcp";
	$color=0;
	echo "<tr valign=top><td nowrap>";
	if(($abrirtodo==1) || (($action=="expand" && $pOpen==$num_prcp)))
    	echo "<a href=captura_preceptoria.php?action=close&prcp=$num_prcp&alumno=".$alumno."><img src=images/trv_intersecminus.gif height=18 width=18 border=0 vspace=0 hspace=0 align=left>";
    else
		echo "<a href=captura_preceptoria.php?action=expand&prcp=$num_prcp&alumno=".$alumno."><img src=images/trv_intersecplus.gif height=18 width=18 border=0 vspace=0 hspace=0 align=left>";
    echo "		</a>
          		&nbsp;&nbsp;$name
          	</td>			
          	</tr>";
    if($num_prcp==$pOpen || (!$pOpen && $nr==$recs-1)){
	////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$np_=$num_prcp;
		if($num_prcp==2){
			$num_prcp=3;
		}
		$sql="select 1 as lvl, id_area_valor as av, 0 as asp, nombre, '' as obs, 
			(select min(color) from preceptoria p inner join areas_valor_aspectos s on p.id_aspecto = s.id where preceptoria = $num_prcp-1 and id_area_valor=a.id_area_valor and alumno = $alumno) as color, 
			'' as obsant, '' as colorant, id_area_valor as id, 0 as id_publicacion from areas_valor a where ciclo = ($_SESSION[periodo_seleccionado])
			union ALL
			select 2, s.id_area_valor, s.id, s.nombre, p.observaciones, p.color,(select observaciones from preceptoria pr where pr.id_aspecto = s.id and alumno = $alumno and pr.preceptoria = $num_prcp-1 and pr.ciclo = ($_SESSION[periodo_seleccionado])),(select color from preceptoria pr where pr.id_aspecto = s.id and alumno = $alumno and pr.preceptoria = $num_prcp-1 and pr.ciclo = ($_SESSION[periodo_seleccionado])),s.id,0 from areas_valor_aspectos s left join preceptoria p on p.id_aspecto = s.id and alumno = $alumno and p.preceptoria = $num_prcp where s.ciclo = ($_SESSION[periodo_seleccionado])
			union ALL
			select 3, e.id_area, e.id_aspecto, t.descripcion, e.comentario, 0, '', '', t.id_test,p.id_publicacion from test_resultados_areas e inner join test_publicacion p on p.id_publicacion = e.id_publicacion inner join test t on t.id_test = p.id_test where e.alumno=$alumno and p.ciclo=($_SESSION[periodo_seleccionado]) and descripcion not like 'Encuesta%'
			order by 2, 3, 1";			
//			echo" <script language='javascript'>alert('RS $rs');</script>";
    if($rs=mysql_query($sql)){
		$num_prcp=$np_;
    	$EOF = 0;
        $i=1;
        $parent=mysql_fetch_array($rs);
        while(!$EOF){
            $lvl = $parent['lvl'];
			$name = $parent[nombre];
			$area = $parent['av'];
			$aspecto = $parent['asp'];
			$color = $parent['color'];
			$tid = $parent['id'];
			$id_publicacion=$parent[id_publicacion];
        if($abrirtodo==1 || (($lvl==1) || ($lvl==2 && $av==$area) || ($lvl==3 && $av==$area))){
        	echo "<tr valign=top><td class=TitStyle nowrap>";
                drawBlanksIntersecs($lvl, $nr == $recs-1);
                if(!$parent=mysql_fetch_array($rs))
					$EOF=1;
                $BranchEnd=mysql_num_rows($rs)==$i||$parent['lvl']!=$lvl;
                if(($abrirtodo==1) || ($action!="close" && (($lvl==1 && $av==$area) || ($lvl==2 && $av==$area) || ($lvl==3 && $av==$area && $aspecto==$asp)))){
					if($lvl==3)
						echo "<a href=captura_preceptoria.php?action=close&alumno=$alumno&prcp=$num_prcp&sec=1&av=$area&asp=$aspecto&todos=$abrirtodo>";
					else{
						echo "<a href=captura_preceptoria.php?action=close&alumno=$alumno&prcp=$num_prcp&sec=$lvl&av=$area&asp=$aspecto&todos=$abrirtodo>";
							if ($BranchEnd)
								echo "<img src=images/trv_intersecminus_end.gif height=18 width=18 border=0 vspace=0 hspace=0 align=left>";
							else
								echo "<img src=images/trv_intersecminus.gif height=18 width=18 border=0 vspace=0 hspace=0 align=left>";
					}
                }
                else{
					if($lvl==3)
						echo "<a href=captura_preceptoria.php?action=expand&alumno=$alumno&prcp=$num_prcp&sec=1&av=$area&asp=$aspecto&todos=$abrirtodo>";
					else{
						echo "<a href=captura_preceptoria.php?action=expand&alumno=$alumno&prcp=$num_prcp&sec=$lvl&av=$area&asp=$aspecto&todos=$abrirtodo>";
						if ($BranchEnd)
							echo "<img src=images/trv_intersecplus_end.gif height=18 width=18 border=0 vspace=0 hspace=0 align=left>";
						else
							echo "<img src=images/trv_intersecplus.gif height=18 width=18 border=0 vspace=0 hspace=0 align=left>";
					}
				}
               	if($color=='')
					$color=0;
				if($lvl==3){
					$calif=mysql_result(mysql_query("SELECT resultado FROM test_resultados_areas WHERE id_publicacion=$id_publicacion AND alumno=$alumno AND id_area=$area AND id_aspecto=$aspecto"),0);
					$result5=mysql_query("SELECT min,max FROM test_evaluacion WHERE id_test=$tid ORDER BY min");
					$color=1;
					for($i=0;$i<3;$i++){
						$limits=mysql_fetch_array($result5);
						if($calif>=$limits[min] && $calif<=$limits[max])
							break;
						$color++;
					}
				}
                echo "<img src=images/$color.jpg height=18 width=18 border=0 vspace=0 hspace=0 align=left>";
                echo "</a>";
                if($lvl==2)
					echo "<a href=captura_preceptoria.php?alumno=$alumno&action=click&prcp=$num_prcp&sec=$lvl&av=$area&asp=$aspecto&todos=$abrirtodo>";
                if($lvl==3){
					$result_calif=mysql_query("SELECT resultado FROM test_resultados_areas WHERE id_publicacion=$id_publicacion AND alumno=$alumno AND id_area=$area AND id_aspecto=$aspecto");
					$res_cal=mysql_fetch_array($result_calif);
					$num_rows=mysql_num_rows($result_calif);
					if($num_rows==0)
						$res_cal=mysql_fetch_array(mysql_query("SELECT calificacion FROM test_estatus WHERE id_publicacion=$id_publicacion AND responde=$alumno"));
                	echo "Calif $res_cal[0]";
					echo "<a href=captura_preceptoria.php?alumno=$alumno&action=test&id_publicacion=$id_publicacion&test=$tid&calif=$res_cal[0]&prcp=$num_prcp&sec=$lvl&av=$area&asp=$aspecto&todos=$abrirtodo onclick=\"window.open('formularios.php?id=$tid&id_publicacion=$id_publicacion&tabla=test_respuestas&agr_modif_borr=a&alumno=$alumno&preceptor=S','newwin');\">";
                }
                echo "&nbsp;&nbsp;".$name."</a>";
                echo "</td>";
                echo "</tr>";
                $i++;
        }
        else
			if(!$parent=mysql_fetch_array($rs))
				$EOF=1;
        }
    }
    else
		if(!$parent=mysql_fetch_array($rs))
			$EOF=1;
	}
}
}

if($eliminar_preceptoria=='S')
{	
//	echo" <script language='javascript'>alert($del);</script>";
	$del="DELETE from preceptoria where alumno=$alumno and ciclo=$_SESSION[periodo_seleccionado] and 		preceptoria=$pOpen";	
	mysql_query($del,$link)or die(mysql_error());
	
	$total_prec=mysql_result(mysql_query("select max(preceptoria) FROM preceptoria WHERE alumno=$alumno and ciclo=$_SESSION[periodo_seleccionado]",$link),0,0);
	
	$prec_=$pOpen;
	$c=$pOpen;

		for($i=1; $i<=$total_prec; $i++)
		{
			$c=$c+1;
			$up="UPDATE preceptoria SET preceptoria=$prec_ WHERE alumno=$alumno and ciclo=$_SESSION[periodo_seleccionado] and preceptoria=$c";	
			mysql_query($up,$link)or die(mysql_error());
			$prec_=$prec_+1;
		}	

}

?>