<? session_start();
include('../config.php');
 header("Expires: Tue, 01 Jul 2001 06:00:00 GMT");
 header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
 header("Cache-Control: no-store, no-cache, must-revalidate");
 header("Cache-Control: post-check=0, pre-check=0", false);
 header("Pragma: no-cache"); 
 $options='';
 $res_ciclos=mysql_query("select * from ciclos",$link) or die(mysql_error());
 while($row_ciclos=mysql_fetch_array($res_ciclos))
 { $slct='';
   if($row_ciclos['ciclo']==$periodo_siguiente) $slct="selected='selected'";
    $options.="<option $slct value='".$row_ciclos['ciclo']."'>".$row_ciclos['descripcion']."</option>";
 } ?>
<script language="javascript">
function c_ciclo(ciclo,c)
{ if(c==1) document.getElementById('ifrm1').src='inscripciones.php?tabla=secciones_reinscripcion&where= and ciclo='+ciclo+'&ciclo='+ciclo+'&orderby=seccion';
  if(c==2) document.getElementById('ifrm2').src='destinatarios.php?ciclo='+ciclo;
}
</script>
<table width='915' align='center'>
 <tr>
  <th id='t1' onClick='pestana(1,1,3,1);' style='cursor:hand; cursor:pointer'>Fechas por Secci&oacute;n</th>
  <th id='t2' onClick='pestana(2,1,3,1);' style='cursor:hand; cursor:pointer'>Estatus de Alumnos</th>
  <th id='t3' onClick='pestana(3,1,3,1);' style='cursor:hand; cursor:pointer'>Reportes</th>
 </tr>
 <tr>
  <td colspan='3'>
    <div id='d1' name='d1' style='display:none'>
    Ciclo : <select onchange="c_ciclo(this.value,1);"><?=$options;?></select><br /> 
       <iframe id='ifrm1' name='ifrm1' width='910' height='427'></iframe></div>
  	 <div id='d2' name='d2' style='display:none'>
    Ciclo : <select onchange="c_ciclo(this.value,2);"><?=$options;?></select><br /> 
       <iframe id='ifrm2' name='ifrm2' width='910' height='427'></iframe></div>
     <div id='d3' name='d3' style='display:none'>
       <iframe id='ifrm3' name='ifrm3' width='910' height='427'></iframe></div>
    </td> 
   </tr>
</table>
<script language='javascript'>pestana(1,1,3,1); c_ciclo(<?=$periodo_siguiente;?>,1); c_ciclo(<?=$periodo_siguiente;?>,2); </script>


