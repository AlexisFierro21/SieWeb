<? session_start();
include('../connection.php');
include('../config.php');
$link = mysql_connect($server, $userName, $password) or die('No se pudo conectar: ' . mysql_error());
mySql_select_db($DB)or die("No se pudo seleccionar DB");
mysql_query("SET CHARACTER SET 'utf8'");
?>
	<script src="../../repositorio/jquery/jquery-1.11.3.min.js" type="text/javascript"></script>
	<script src="../../repositorio/jquery/ui/jquery-ui.min.js" type="text/javascript"></script>
	
 	<link rel="stylesheet" type="text/css" href="../css/jquery-ui.theme.css" /><!-- Libreria DatetimePicker-->
 	  
<script type="text/javascript">
$(document).ready(function() {
   $("#fecha").datepicker();
});


jQuery(function($){
	$.datepicker.regional['es'] = {
		closeText: 'Cerrar',
		prevText: '&#x3c;Ant',
		nextText: 'Sig&#x3e;',
		currentText: 'Hoy',
		monthNames: ['Enero','Febrero','Marzo','Abril','Mayo','Junio',
		'Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'],
		monthNamesShort: ['Ene','Feb','Mar','Abr','May','Jun',
		'Jul','Ago','Sep','Oct','Nov','Dic'],
		dayNames: ['Domingo','Lunes','Martes','Mi&eacute;rcoles','Jueves','Viernes','S&aacute;bado'],
		dayNamesShort: ['D','L','M','M;','J','V','S'],
		dayNamesMin: ['Do','Lu','Ma','Mi','Ju','Vi','Sa'],
		weekHeader: 'Sm',
		dateFormat: 'yy-mm-dd',
		firstDay: 1,
		isRTL: false,
		showMonthAfterYear: false,
		yearSuffix: '',
		duration: 10
		};
	$.datepicker.setDefaults($.datepicker.regional['es']);
});   

</script>
<script>
function ActionE(fo,e){
		with(document.forms[fo]){
			eliminar_preceptoria.value=e;
			submit();
		}
	}
	

function eliminar()
{
	if(window.confirm('Desea eliminar la preceptoria???'))
	{
		ActionE('fr','S');
	}
}
	
function NoVacios()
{
	if (document.getElementById('obs').value == '' || document.getElementById('obs').value == ' ')
	{
		alert('Debes capturar una observación.');
		return false;
	}
	if (document.getElementById('metas').value == '' || document.getElementById('metas').value == ' ')
	{
		alert('Debes capturar una meta.');
		return false;
	}
	/*if ( document.getElementById('colorsel').value == 0 )
	{
		alert( 'Debes seleccionar un Color.' );
		return false;
	}*/
	return true
}

function Action(f,o){
		with(document.forms[f]){
			opc.value=o;
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


function parametros(c, p, a, av)
{	
	var col=c.value;
	var color;
	
	var al=a;
	var pr=p;
	var valor=av;
	
	aspecto=col.substring(1,3);
	color=col.substring(0,1);
	window.location.reload();
	//alert("Area"+valor);
/*	alert("Aspecto"+aspecto);
	
	alert("Color y aspecto"+col);
	
	alert("alumno"+al);
	alert("preceptoria"+pr);*/
	//insertarColor(col,aspecto,al,prec);
	//name=\"fr\" action=\"$t?alumno=$alumno&prcp=$pOpen&av=$av&asp=$asp\
	
	document.location.href="captura_preceptoria.php?vcolor="+color+"&vpreceptoria="+pr+"&vaspecto="+aspecto+"&valumno="+al+"&alumno="+al+"&prcp="+pr+"&av="+valor+"&asp="+aspecto;
	
/*	alert('fin'); 
	document.getElementById("col").value=document.getElementById(col).value; 
	document.getElementById('fr').submit();*/
	
}
</script>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Captura Preceptor&iacute;a</title>
</head>
  	
  		<!--[if IE]>
			<style>
				
				
			</style>
		<![endif]-->
  		
  			
 <?php
 
 	
 
	//include "captura_preceptoria.inc";
	
	if(isset($_POST['periodo_select']) && $_POST['periodo_select']!='' && $_POST['periodo_select']!=null)
		$_SESSION['periodo_seleccionado']=$_POST['periodo_select'];
	switch ($color){
		case 1: 	$clr="orange"; break;
		case 2: 	$clr="cyan"; break;
		case 3:		$clr="limegreen";		
	}
	switch($colorAnt){
		case 1: $clrA="orange"; break;
		case 2: $clrA="cyan"; break;
		case 3:	$clrA="limegreen"; break;
	}
	$prcpAnt=$pOpen-1;
//	$t = $_SERVER['PHP_SELF'];
 	//echo "<form id=\"fr\" name=\"fr\" action=\"$t?alumno=$alumno&prcp=$pOpen&av=$av&asp=$asp\" method=POST>";
 	echo "	<body  bgcolor='$fondo' alink=black vlink=blue>";

	echo "<form id=\"fr\" name=\"fr\" action=\"$t?alumno=$alumno&prcp=$pOpen&av=$av&asp=$asp\" method=POST>";
	
	$aal=$_GET['alumno'];
	if($aal=="" or $aal==0)
		$aal=0; if(!empty($_POST['alumno_'])) $aal=$_POST['alumno_'];
	?>
	
	<input type='hidden' name='alumno_' id='alumno_' value='<?=$aal?>'>
	<input type='hidden' name='alumno' id='alumno' value='<?=$aal?>'>
	
	<?		
	$dia="";
	$fechh_ = "select now() as hoy";
	$fh_ = mysql_fetch_array(mysql_query($fechh_));
	$fechoy = $fh_['hoy'];
	
	$fechaHoy=substr($fechoy,0,10);
	$dia=$fechaHoy;
	
	
	$alumnof=$_REQUEST['valumno'];
	$preceptoriaf=$_REQUEST['vpreceptoria'];
	$aspectof=$_REQUEST['vaspecto'];
	$colorf=$_REQUEST['vcolor'];
	$fechaf=$_REQUEST['fecha'];
	
	/*echo" <script language='javascript'>alert('Existe: $existe');</script>";*/
	if($alumnof!='' and $preceptoriaf!='' and $aspectof!='' and $colorf!=''){
		$consultar = "select preceptoria from preceptoria where ciclo=$_SESSION[periodo_seleccionado] and alumno=$alumnof and preceptoria=$preceptoriaf and id_aspecto=$aspectof";
/*		echo" <script language='javascript'>alert('$consultar');</script>";*/
	$consultarR = mysql_fetch_array(mysql_query($consultar));
	$existe = $consultarR['preceptoria'];
		
		if($existe=='' or $existe==0){						
		$save = "insert into preceptoria (alumno, ciclo, preceptoria, id_aspecto, color, fecha, fecha_captura) values ($alumnof, $periodo_actual, $preceptoriaf, null, null, now(), now())";
		mysql_query($save);
		}
		else
		{
			$save = "update preceptoria set color=null where ciclo=$periodo_actual and alumno=$alumnof and preceptoria=$preceptoriaf and id_aspecto=$aspectof";
		mysql_query($save);
		}
	}

$eliminar_preceptoria=$_REQUEST['eliminar_preceptoria'];

$colorS=$_POST["color"];
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
	$metas=$_REQUEST['metas'];
//	$color=$_REQUEST['color'];
	$color=$_POST['color'];

/*	$validafecha = "select max(preceptoria) as num_preceptoria from preceptoria where ciclo=$_SESSION[periodo_seleccionado] and alumno=$alumno and preceptoria<>$pOpen and fecha='$fecha'";
	$rstValidaFecha = mysql_fetch_array(mysql_query($validafecha));
	$num_preceptoria = $rstValidaFecha['num_preceptoria'];*/

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

if($pOpen!='' and $pOpen!=0){
			
	$rstT_ = mysql_query ("select max(preceptoria) as num_preceptoria from preceptoria where ciclo=$_SESSION[periodo_seleccionado] and alumno=$aal and preceptoria<>$pOpen and fecha='$fecha'
	",$link) 
			or die ("select max(preceptoria) as num_preceptoria from preceptoria where ciclo=$_SESSION[periodo_seleccionado] and alumno=$aal and preceptoria<>$pOpen and fecha='$fecha'".mysql_error());
	while($rsT_=mysql_fetch_array($rstT_))
		{ 	
		$num_preceptoria = $rsT_['num_preceptoria'];
		}
		//if($num_preceptoria==0 or $num_preceptoria=='')
			//$num_preceptoria=1;
}

////////////
	
	if ($num_preceptoria > 0)
	{
		echo" <script language='javascript'>alert('No fue posible grabar la información porque la Preceptoría ' + $num_preceptoria + ' tiene la misma fecha. Deberá capturarla con una fecha diferente.');</script>";
	}
	else
	{
		
		if($prcpAnt==-1)
			$preAc=1;
		if($prcpAnt>1)
			$preAc=$prcpAnt+1;
		
		if($metas!='' or $obs!='')
		{
		
			if($alumno=='' or $alumno==0)
				$alumno=$aal;
			$mp = "select max(preceptoria) as max from preceptoria where ciclo=$_SESSION[periodo_seleccionado] and alumno=$alumno";
			$m = mysql_fetch_array(mysql_query($mp));
			$preAc = $m['max'];					

			$pre_=0; if(!empty($_POST['pre_'])) $pre_=$_POST['pre_'];
			$pre_=$pre_+1;

			$mpf = "select fin from preceptoria where preceptoria=$pre_ and ciclo=$_SESSION[periodo_seleccionado] and alumno=$alumno";
			$mf = mysql_fetch_array(mysql_query($mpf));
			$prefin = $mf['fin'];
			if($prefin==1)
				$pre_=$pre_+1;
			/////////ULTIMA PRECEPTORIA
		
			$rst_ = mysql_query ("select max(preceptoria) as m from preceptoria where alumno=$aal and ciclo=$periodo_actual and observaciones!=''",$link) or die ("select max(preceptoria) as m from preceptoria where alumno=$aal and ciclo=$periodo_actual and observaciones!='".mysql_error());
		  while($rs_=mysql_fetch_array($rst_))
		  { 
			$m_prec=$rs_["m"];
		  }
			if($m_prec=='' or $m_prec==0)
				$m_prec=1;
			
			$rsf = mysql_query ("select fecha_captura from preceptoria where preceptoria=$m_prec and alumno=$aal",$link) or die ("select fecha_captura from preceptoria where preceptoria=$m_prec and alumno=$aal".mysql_error());
		  while($rf_=mysql_fetch_array($rsf))
		  { 
			$feultima=$rf_["fecha_captura"];
		  }
			////
					
			$dia="";
			$fechh_ = "select now() as hoy";
			$fh_ = mysql_fetch_array(mysql_query($fechh_));
			$fechoy = $fh_['hoy'];
			
			$fechaHoy=substr($fechoy,0,10);
			$fechaUltima=substr($feultima,0,10);

			/// ya se registro este día
			$yaFecha = "select date('$fechaUltima') = date('$fechaHoy') as igual";			
			$igual = mysql_fetch_array(mysql_query($yaFecha));
			$igualF = $igual['igual'];
			
			
			$f_=$_POST['fecha'];
			
			$fc=$_REQUEST['fin'];
		
			$ff_=substr($f_, 0, 10);
		$consultar = "select preceptoria from preceptoria where ciclo=$_SESSION[periodo_seleccionado] and alumno=$aal and preceptoria=$pre_ and fin!=1";
	$consultarR = mysql_fetch_array(mysql_query($consultar));
	$existe = $consultarR['preceptoria'];
	
	if($existe!='' and $existe!=0)
	{
		$save = "UPDATE preceptoria SET observaciones='$obs', metas='$metas', fecha='$f_' WHERE preceptoria=$m_prec and alumno=$aal and ciclo=$periodo_actual ";

		mysql_query($save);
	}
	else
	{		
		
			if($_REQUEST['fin']== "")
			{
				if($igualF==1)
				{					
						echo" <script language='javascript'>alert('NO PUEDES REGISTRAR MAS DE UNA PRECEPTORIA EL MISMO DIA');</script>";					
				}				
				else
				{																					
					////GUARDA OBS Y MTAS
					mysql_query("insert into preceptoria (alumno, ciclo, preceptoria, observaciones, metas, fecha, fecha_captura) values ($aal, $periodo_actual, $pre_, '$obs', '$metas', '$ff_', now() )",$link)or die(mysql_error());
								
					/*$save = "insert into preceptoria (alumno, ciclo, preceptoria, observaciones, metas, fecha, fecha_captura) values ($aal, $periodo_actual, $pre_, '$obs', '$metas', '$ff_', now() ) ";					
					mysql_query($save);*/
				}
			}
			
		}//		
					
		}
		
		$fin = $_REQUEST['fin'];
		$prcpN=$_REQUEST['prcp'];
		if($_REQUEST['fin']=='on')
		{
			///////////////////////////////////////////////FIN PRECEPTORIA
			$consultar = "select preceptoria from preceptoria where ciclo=$_SESSION[periodo_seleccionado] and alumno=$aal and preceptoria=$pre_";
	$consultarR = mysql_fetch_array(mysql_query($consultar));
	$existe = $consultarR['preceptoria'];
			/////
			if($existe=='' and $existe==0)
			{
				$save = "insert into preceptoria (alumno, ciclo, preceptoria, observaciones, fin, metas, fecha, fecha_captura) values ($aal, $periodo_actual, $pre_, '$obs', 1, '$metas', '$f_', now() );";
			}
			else
			{
			$save = "update preceptoria set fin = 1, observaciones='$obs', metas='$metas', fecha='$f_' where alumno = $aal and preceptoria = $pre_ and ciclo = ($_SESSION[periodo_seleccionado]);";
			}
			
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
		$sqlt="Select metas as m, observaciones as o, color as c, id from preceptoria where ciclo=$_SESSION[periodo_seleccionado] and Alumno=$alumno and preceptoria=$pOpen and id_aspecto=$asp;";
		 $rst = mysql_fetch_array(mysql_query($sqlt));
		 $obs = $rst['o'];
		 $metas = $rst['m'];
		 $color = $rst['c'];
		 $idMod = $rst['id'];
		 
		 $sqlt="Select metas as m, observaciones as o, color as c from preceptoria where ciclo=$_SESSION[periodo_seleccionado] and Alumno=$alumno and preceptoria=$pOpen-1 and id_aspecto=$asp;";
		 $rst = mysql_fetch_array(mysql_query($sqlt));
		 $obsAnt = $rst['o'];
		 $metasAnt = $rst['m'];
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
	/*$todos = "";
	$abrirtodo = 0;*/
	$todos = 'checked';
//	$action="expand";
	$abrirtodo = 1;
}

function drawBlanksIntersecs($i, $last)
{
	for ($ii=1; $ii<$i+1; $ii++)
	{
		if ($last && $ii==1) $img = "trv_blank.gif"; else $img = "trv_nointersec.gif";

	}
}

function buildTreeView($action, $alumno, $pOpen, $av, $asp, $abrirtodo){
$sql_p = "Select count(*) from preceptoria where ciclo=$_SESSION[periodo_seleccionado] and alumno = $alumno and fin <> 1 ORDER BY preceptoria DESC;";
$r = mysql_fetch_array(mysql_query($sql_p));
if($r[0]==0)
	$sql_p="Select distinct(preceptoria) from preceptoria where ciclo=$_SESSION[periodo_seleccionado] and alumno = $alumno union all Select ifnull(max(preceptoria), 0)+1 from preceptoria where ciclo=$_SESSION[periodo_seleccionado] and alumno = $alumno ORDER BY preceptoria DESC;";
else
	$sql_p="Select distinct(preceptoria) from preceptoria where ciclo=$_SESSION[periodo_seleccionado] and alumno = $alumno ORDER BY preceptoria DESC;";
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
	echo "";
    	 else
		echo "<a href=captura_preceptoria.php?action=expand&prcp=$num_prcp&alumno=".$alumno."><img src=images/trv_intersecplus.gif height=18 width=18 border=0 vspace=0 hspace=0 align=left>";
    echo "</a>
          		&nbsp;&nbsp;$name
          	</td>			
          	</tr>";
	
	$contador=0;
	$aspectoG=0;
	
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


    if($rs=mysql_query($sql)){
		$num_prcp=$np_;
    	$EOF = 0;
        $i=1;
        $parent=mysql_fetch_array($rs);
		$cont=0;
        while(!$EOF){
			$cont=$cont+1;
            $lvl = $parent['lvl'];
			$name = $parent[nombre];
			$area = $parent['av'];
			$aspecto = $parent['asp'];
			$color = $parent['color'];
			$tid = $parent['id'];
			$id_publicacion=$parent[id_publicacion];
        if($abrirtodo==1 || (($lvl==1) || ($lvl==2 && $av==$area) || ($lvl==3 && $av==$area))){

                drawBlanksIntersecs($lvl, $nr == $recs-1);
                if(!$parent=mysql_fetch_array($rs))
					$EOF=1;
                $BranchEnd=mysql_num_rows($rs)==$i||$parent['lvl']!=$lvl;
                if(($abrirtodo==1) || ($action!="close" && (($lvl==1 && $av==$area) || ($lvl==2 && $av==$area) || ($lvl==3 && $av==$area && $aspecto==$asp)))){
					if($lvl==3)
					$eee=0;
						
					else{						
							if ($BranchEnd)
								$eee=0;							
							else
								$eee=0;							
					}
                }
                else{
					if($lvl==3)
						$eee=0;
					else{
						if ($BranchEnd)
							$eee=0;
						else
							$eee=0;							
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

              
                if($lvl==2)
					
                if($lvl==3){
					$result_calif=mysql_query("SELECT resultado FROM test_resultados_areas WHERE id_publicacion=$id_publicacion AND alumno=$alumno AND id_area=$area AND id_aspecto=$aspecto");
					$res_cal=mysql_fetch_array($result_calif);
					$num_rows=mysql_num_rows($result_calif);
					if($num_rows==0)
						$res_cal=mysql_fetch_array(mysql_query("SELECT calificacion FROM test_estatus WHERE id_publicacion=$id_publicacion AND responde=$alumno"));                	
                }

				$cl="";
				
				
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
	$actual=mysql_result(mysql_query("select max(preceptoria) FROM preceptoria WHERE alumno=$aal and ciclo=$_SESSION[periodo_seleccionado]",$link),0,0);
	

	if($pOpen=="" or $pOpen==0)
		$pOpen=$actual;
		
	$del="DELETE from preceptoria where alumno=$aal and ciclo=$_SESSION[periodo_seleccionado] and preceptoria=$pOpen";	
	mysql_query($del,$link)or die(mysql_error());
	
	$total_prec=mysql_result(mysql_query("select max(preceptoria) FROM preceptoria WHERE alumno=$aal and ciclo=$_SESSION[periodo_seleccionado]",$link),0,0);
	
	$prec_=$pOpen;
	$c=$pOpen;

		for($i=1; $i<=$total_prec; $i++)
		{
			$c=$c+1;
			$up="UPDATE preceptoria SET preceptoria=$prec_ WHERE alumno=$aal and ciclo=$_SESSION[periodo_seleccionado] and preceptoria=$c";	
			mysql_query($up,$link)or die(mysql_error());
			$prec_=$prec_+1;
		}	
		$num_preceptoria=$pOpen;
/*		echo "<script>parent.location.reload();</script>";*/
		echo "<script>fr.submit();</script>";
}

	
	
	$c= "	<input type=hidden name=idMod value=$idMod>
			<input type=hidden name=opc value=$opc>
			<input type=hidden name=prcp value=$pOpen>
			<input type=hidden name=id value=$id>
			<input type=hidden name=eliminar_preceptoria value=$eliminar_preceptoria>
			<td align=center>
			</td>
			
	<table border=1 valign=top cellpadding=0 cellspacing=0 style=\"font-family: Arial; font-size: 12pt;\">";
	$c.="<td><font size=5 color=#FF0000><u>Captura de Preceptor&iacute;a ($_SESSION[periodo_seleccionado])</u></font></th>";
	$c.="<br><form action='captura_preceptoria.php' method='post' name='form1' id='form1'><select name='periodo_select' onChange='submit()'>";
	$periodo_actual=mysql_result(mysql_query("select periodo from parametros"),0);
	
	for($i=$periodo_actual;$i>2010;$i--){
		$seleccionado='';
		if($i==$_SESSION['periodo_seleccionado'])
			$seleccionado='selected';
		$c.="<option value=$i $seleccionado>$i</option>";
	}
	
	$c.="</select></form>";
	$c.="&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	<input type=image src=\"../im/b_drop_.png\" onclick=\"javascript:eliminar()\"; /><br><br>";
   // if($sec>1){
  
   $alumno=$aal;
   $sqlt = mysql_query ("select ifnull(max(preceptoria),0),(select preceptoria_mod from parametros) from preceptoria where ciclo=$_SESSION[periodo_seleccionado] and alumno=$alumno and fin=1 ",$link) or die ("select ifnull(max(preceptoria),0),(select preceptoria_mod from parametros) from preceptoria where ciclo=$_SESSION[periodo_seleccionado] and alumno=$alumno and fin=1 ".mysql_error());

			
while($rst=mysql_fetch_array($sqlt))
	  {
	  	$max_prcp=$rst[0];
		$rm=$rst[1];
	  }
   
		$RO="";
		$Dis="";
		if($pOpen<=$max_prcp && $rm!=1)
			$RO="readonly";

		$c.="<tr><td></td><td valign=top align=left rowspan=100 style=\"font-family: Arial; font-weight: bold; font-size: 12pt;\"><br><br>";
		
			if($pOpen=="" or $pOpen==0)
				$pOpen=$max_prcp+1;
			if($max_prcp==0)
				$pOpen=$max_prcp+1;
				  
	  		$mp_ = "select id_aspecto from preceptoria where ciclo=$_SESSION[periodo_seleccionado] and alumno=$alumno and fin!=1";
			$m_ = mysql_fetch_array(mysql_query($mp_));
			$preAc_ = $m_['id_aspecto'];
			
			if($preAc_=='' or $preAc_==0)
			{
				$obs="";
				$metas="";
			}
			
			$sqlt = mysql_query ("select observaciones, metas from preceptoria where ciclo=$_SESSION[periodo_seleccionado] and alumno=$alumno and preceptoria=$pOpen  and observaciones!=''  ",$link) or die ("select observaciones, metas from preceptoria where ciclo=$_SESSION[periodo_seleccionado] and alumno=$alumno and preceptoria=$pOpen  and observaciones!=''  ".mysql_error());
	mysql_query("SET CHARACTER SET 'utf8'");
	while($rst=mysql_fetch_array($sqlt))
	  {
	  	$obs=$rst['observaciones'];
		$metas=$rst['metas'];
	  }	
	  ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////
			$c.="Observaciones Preceptor&iacute;a Actual:<br>
				 <textarea name=\"obs\" id=obs cols=40 rows=5 style=\"background-color:$clr\">".$obs."</textarea><br><br>
				 <input type='hidden' name='alumno_' id='alumno_' value='$aal'>				 
				 ";
				 
			$c.="Metas Preceptor&iacute;a Actual:<br>
				 <textarea name=\"metas\" id=metas cols=40 rows=5 style=\"background-color:$clr\">$metas</textarea><br><br>";

			if($pOpen>$max_prcp){
			
			$sqlt = mysql_query ("select fecha from preceptoria where ciclo=$_SESSION[periodo_seleccionado] and alumno=$alumno and preceptoria=$pOpen ",$link) or die ("select fecha from preceptoria where ciclo=$_SESSION[periodo_seleccionado] and alumno=$alumno and preceptoria=$pOpen ".mysql_error());
while($rst=mysql_fetch_array($sqlt))
	  {
	  	$fecha=$rst['fecha'];	
	  }
												
				$sqlt = mysql_query ("select fecha from preceptoria where ciclo=$_SESSION[periodo_seleccionado] and alumno=$alumno and preceptoria=$pOpen ",$link) or die ("select fecha from preceptoria where ciclo=$_SESSION[periodo_seleccionado] and alumno=$alumno and preceptoria=$pOpen ".mysql_error());
while($rst=mysql_fetch_array($sqlt))
	  {
	  	$fecha=$rst['fecha'];
	  }
	  
				$c.="<br>
					<font size=2pt>Fecha de la preceptor&iacutea:&nbsp;
					<input type='hidden' size='15' name='fhoy' id='fhoy' value='$dia' readonly=readonly>
					<br></font>
					<input size='15' name='fecha' id='fecha' value='$fecha' readonly=readonly>
					<br><br>";
				}
				$c.="<font size=2pt>Finalizar preceptor&iacute;a actual</font><input $Dis type=checkbox name=fin id=fin>
					<br><input type=button name=g value=\"   Grabar   \" onClick='if (NoVacios()) show_confirm();'><br>";

		if($_GET[action]=='test'){
			$comentario=mysql_result(mysql_query("select comentario from test_estatus where id_publicacion=$_GET[id_publicacion]"),0);
			$sugerencia=mysql_result(mysql_query("select sugerencia from test_estatus where id_publicacion=$_GET[id_publicacion]"),0);
			$rows=mysql_num_rows(mysql_query("select * from test_resultados_areas where id_publicacion=$_GET[id_publicacion] and id_area=$av and id_aspecto=$asp"));
			if($rows>0){
				$comentario=mysql_result(mysql_query("select comentario from test_resultados_areas where id_publicacion=$_GET[id_publicacion] and alumno=$alumno and id_area=$av and id_aspecto=$asp"),0);
				$sugerencia=mysql_result(mysql_query("select sugerencia from test_resultados_areas where id_publicacion=$_GET[id_publicacion] and alumno=$alumno and id_area=$av and id_aspecto=$asp"),0);
			}
			$c.="<input type=hidden name=id_publicacion id=id_publicacion value=$_GET[id_publicacion]>";
			$c.="<input type=hidden name=alumno id=alumno value=$alumno>";
			$c.="<input type=hidden name=id_area id=id_area value=$av>";
			$c.="<input type=hidden name=id_aspecto id=id_aspecto value=$asp>";
			$c.="Comentario:<br>
				<textarea $RO name=comentarioTest id=comentarioTest cols=40 rows=5>$comentario</textarea><br><br>";
			$c.="Sugerencia:<br>
				 <textarea $RO name=sugerenciaTest id=sugerenciaTest cols=40 rows=5>$sugerencia</textarea><br>";
			$c.="<br><br><input type=button name=g value=\"   Grabar   \" onClick=\"Action('fr',2)\";>";
		}
		
	
	$perAnt=$_SESSION['periodo_seleccionado'];
	$rst_ = mysql_query ("SELECT max(preceptoria) as p, fin FROM preceptoria where ciclo=$perAnt and alumno=$aal",$link) or die ("SELECT max(preceptoria) as p FROM preceptoria where ciclo=$perAnt and alumno=$aal".mysql_error());
  while($rs_=mysql_fetch_array($rst_))
  { 
  	$preceptoriaMax=$rs_["p"];
	$fin_=$rs_['fin'];
  }
  
  			if($preceptoriaMax==1)
				$preceptoriaAnt=$preceptoriaMax-1;
			
			if($preceptoriaMax>1){			
				$preceptoriaAnt=$preceptoriaMax;
				
				if($pOpen>2)
					$preceptoriaAnt=$pOpen-1;
				if($pOpen==2)					
					$preceptoriaAnt=$pOpen-1;

					
				$rst_ = mysql_query ("SELECT observaciones, metas FROM preceptoria where ciclo=$perAnt and alumno=$alumno and preceptoria=$preceptoriaAnt and observaciones!='' ",$link) or die ("SELECT observaciones, metas FROM preceptoria where ciclo=$perAnt and alumno=$alumno and preceptoria=$preceptoriaAnt  and observaciones!='' ".mysql_error());
				mysql_query("SET CHARACTER SET 'utf8'");
				  while($rs_=mysql_fetch_array($rst_))
				  { 
					$metasAnt=$rs_["metas"];
					$obsAnt=$rs_["observaciones"];
				  }		

				if($preceptoriaAnt!=0 and $preceptoriaAnt!=-1)										
				$c.="</br>Observaciones Preceptor&iacute;a $preceptoriaAnt:<br>
				<textarea readonly name=\"obsA\" cols=40 rows=5 style=\"background-color:$clrA\">".$obsAnt."</textarea><br><br>";
				
				$c.="Metas Preceptor&iacute;a $preceptoriaAnt:&nbsp;<br>
				<textarea readonly name=\"metasA\" cols=40 rows=5 style=\"background-color:$clrA\">$metasAnt</textarea>
				<input type='hidden' name='pre_' id='pre_' value='$preceptoriaAnt'>
				";							
					}
					
				if($preceptoriaMax==1 and $fin_==1){			
									
				$rst_ = mysql_query ("SELECT observaciones, metas FROM preceptoria where ciclo=$perAnt and alumno=$alumno and preceptoria=1",$link) or die ("SELECT observaciones, metas FROM preceptoria where ciclo=$perAnt and alumno=$alumno and preceptoria=1".mysql_error());
				  while($rs_=mysql_fetch_array($rst_))
				  { 
					$metasAnt=$rs_["metas"];
					$obsAnt=$rs_["observaciones"];
				  }											
				$c.="<br></br>Observaciones Preceptor&iacute;a 1:&nbsp;<textarea readonly name=\"obsA\" cols=40 rows=5 style=\"background-color:$clrA\">$obsAnt</textarea><br>&nbsp;</br> Metas Preceptor&iacute;a 1:&nbsp;<textarea readonly name=\"metasA\" cols=40 rows=5 style=\"background-color:$clrA\">$metasAnt</textarea>";							
					}
	$c.="</td>";

    $c.="</tr>";
    echo $c;
	
    buildTreeView($action,$alumno,$pOpen,$av,$asp,$abrirtodo);
?>
<script>document.forms.fr['obs'].focus();</script>
</form>
</body>
</html>