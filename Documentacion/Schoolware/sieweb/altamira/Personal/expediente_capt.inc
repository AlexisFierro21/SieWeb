<?php
include('../connection.php');
$link = mysql_connect($server, $userName, $password) or die('No se pudo conectar: ' . mysql_error());
mySql_select_db($DB)or die("No se pudo seleccionar DB");

$opc = $_REQUEST['opc'];

$action = $_REQUEST['action'];
$alumno = $_REQUEST['alumno'];
$pOpen = $_REQUEST['prcp'];

$sec = $_REQUEST['sec'];
$id = $_REQUEST['id'];

if ($opc == 1)
{
	$idMod = $_REQUEST['idMod'];
	$obs = $_REQUEST['obs'];
	$color = $_REQUEST['color'];
	echo "$idMod $obs $color $id";
	if (!$idMod)
		$save = "insert into preceptoria (alumno, ciclo, preceptoria, id_aspecto, observaciones, color) values
										($alumno, (select periodo from parametros), $pOpen, $id, '$obs', $color);";
	else
		$save = "update preceptoria set observaciones = '$obs', color = $color where id = $idMod";
		
	mysql_query($save);
	
	$fin = $_REQUEST['fin'];
	
	if ($_REQUEST['fin'] == 'on')
	{
		$save = "update preceptoria set fin = 1 where alumno = $alumno and preceptoria = $pOpen and ciclo = (select periodo from parametros);";
		mysql_query($save);
	}
	$opc = 0;
}

switch($sec){
	case 2:{
		 $sqlt="Select observaciones as o, color as c, id from preceptoria 
		 			where Alumno = $alumno and preceptoria = $pOpen and id_aspecto = $id;";
		 $rst = mysql_fetch_array(mysql_query($sqlt));
		 $obs = $rst['o'];
		 $color = $rst['c'];
		 $idMod = $rst['id'];

		 $sqlt="Select observaciones as o, color as c from preceptoria 
		 			where Alumno = $alumno and preceptoria = $pOpen-1 and id_aspecto = $id;";
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

function buildTreeView($action, $alumno, $pOpen, $Abrir, $abrirtodo)
{

$sql_p = "Select count(*) from preceptoria where alumno = $alumno and preceptoria <> 1";
$r = mysql_query($sql_p); 

if (!$r)
	$sql_p = "Select distinct(preceptoria) from preceptoria where alumno = $alumno
			  union all 
			  Select max(preceptoria)+1 from preceptoria where alumno = $alumno;";
else
	$sql_p = "Select distinct(preceptoria) from preceptoria where alumno = $alumno";

$r = mysql_query($sql_p); 
$recs = mysql_num_rows($r);

for ($nr=0; $nr<$recs;$nr++)
{
	$prcp=mysql_fetch_array($r);
	
	$num_prcp = $prcp['preceptoria'];
            
	$lvl = 0; $name = "PRECEPTORIA $num_prcp"; $color = 0;

	echo "<tr valign=top>
          	<td nowrap>";
	if (($abrirtodo == 1) || (($action=="expand" && $pOpen == $num_prcp )))
    	echo "<a href=captura_preceptoria.php?action=close&prcp=$num_prcp&alumno=".$alumno.">
			  <img src=images/trv_intersecminus.gif height=18 width=18 border=0 vspace=0 hspace=0 align=left>";
    else
		echo "<a href=captura_preceptoria.php?action=expand&prcp=$num_prcp&alumno=".$alumno.">
			  <img src=images/trv_intersecplus.gif height=18 width=18 border=0 vspace=0 hspace=0 align=left>";
    
    echo "		</a>
          		&nbsp;&nbsp;$name
          	</td>
          	</tr>";
               
	$sql="	select 	1 as lvl, id_area_valor as av, 0 as asp, nombre, '' as obs, 
					(select min(color) from preceptoria p inner join areas_valor_aspectos s on p.id_aspecto = s.id 
							where preceptoria = $num_prcp-1 and id_area_valor =a.id_area_valor and alumno = $alumno) as color, 
					'' as obsant, '' as colorant, 
					id_area_valor as id
			from areas_valor a
			where ciclo = (select periodo from parametros)
			
			union ALL
			
			select 	2, s.id_area_valor, s.id, s.nombre, p.observaciones, p.color,
			
					(select observaciones from preceptoria pr
						where pr.id_aspecto = s.id and alumno = $alumno and pr.preceptoria = $num_prcp-1 
						and pr.ciclo = (select periodo from parametros)),
						
					(select color from preceptoria pr
						where pr.id_aspecto = s.id and alumno = $alumno and pr.preceptoria = $num_prcp-1 
						and pr.ciclo = (select periodo from parametros)),

						s.id
						
						from areas_valor_aspectos s  left join preceptoria p on p.id_aspecto = s.id and alumno = $alumno and p.preceptoria = $num_prcp 
			where s.ciclo = (select periodo from parametros)
			
			union ALL
			
			select 3,  t.id_area, t.id_aspecto, t.descripcion, e.comentario, 0, '', '', e.id_estatus
			from test_estatus e inner join test_publicacion p on p.id_publicacion = e.id_publicacion
			           inner join test t on t.id_test = p.id_test
			where responde = $alumno  and p.ciclo = (select periodo from parametros)

			order by 2, 1, 3";
	
	if  ($abrirtodo == 1 || ($action=="expand" && $num_prcp==$pOpen) || $nr == $recs-1)
	{
    if ($rs=mysql_query($sql))
    {
    	$EOF = 0;
        $i=1;
        $parent=mysql_fetch_array($rs);
        
        while (!$EOF)
        {
        	echo "<tr valign=top>
               			<td class=TitStyle nowrap>";

                
                $lvl = $parent['lvl']; $name = $parent['nombre']; $id = $parent['id']; $color = $parent['color'];
				
                drawBlanksIntersecs($lvl, $nr == $recs-1);
                
                if(!$parent=mysql_fetch_array($rs)) $EOF = 1;

                $BranchEnd = mysql_num_rows($rs)==$i || $parent['lvl'] != $lvl;
                
                if ($parent['lvl'] > $lvl)
                { 	
        
                	if (($abrirtodo == 1) || (($action=="expand" && $num_prcp==$pOpen)))
                     {
                           echo "<a href=captura_preceptoria.php?action=close&prcp=$num_prcp&alumno=".$alumno.">";

                           if ($BranchEnd)
                               echo "<img src=images/trv_intersecminus_end.gif height=18 width=18 border=0 vspace=0 hspace=0 align=left>";
                           else
                               echo "<img src=images/trv_intersecminus.gif height=18 width=18 border=0 vspace=0 hspace=0 align=left>";
                     }
                     else
                     {
                     	echo "<a href=captura_preceptoria.php?action=expand&prcp=$num_prcp&alumno=".$alumno.">";

                           if ($BranchEnd)
                               echo "<img src=images/trv_intersecplus_end.gif height=18 width=18 border=0 vspace=0 hspace=0 align=left>";
                           else
                               echo "<img src=images/trv_intersecplus.gif height=18 width=18 border=0 vspace=0 hspace=0 align=left>";
                     }
                }
                else
                {
                     if ($BranchEnd)
                           echo "<img src=images/trv_end.gif height=18 width=18 border=0 vspace=0 hspace=0 align=left>";
                     else
                           echo "<img src=images/trv_intersec.gif height=18 width=18 border=0 vspace=0 hspace=0 align=left>";
                }

               	if ($color=='') $color = 0;
                echo "<img src=images/$color.ico height=18 width=18 border=0 vspace=0 hspace=0 align=left>";

                echo "</a>";

                if ($lvl==2)
                echo "<a href=captura_preceptoria.php?alumno=".$alumno."&action=expand&prcp=$num_prcp&sec=$lvl&id=$id&todos=$abrirtodo>";
                echo "&nbsp;&nbsp;".$name;
                echo "</td>";
                echo "</tr>";

                $i++;
        }
    }
	}
}
}
?>