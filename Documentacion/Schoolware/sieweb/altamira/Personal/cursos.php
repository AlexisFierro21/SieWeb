<? session_start();?>
<script>
function popupCaptura(id_publicacion){
	child=window.open('tmp.php?id_publicacion='+id_publicacion,'Captura de asistencia','height=300,width=400',(window.screen.width-300)/2,(window.screen.height-400)/2);
	document.formacursos.submit();
}
</script>
<?
	echo "<body>";
if($_POST[password]!=''){
	//echo "<script>alert('".$_POST[password].",".$_SESSION[password]."')</script>";
	if($_POST[password]==$_SESSION[password])
		echo "<script>popupCaptura('$_POST[id_publicacion]');</script>";
}
if($_GET[id_publicacion]!=''){
	echo "<form id=formacursos name=formacursos method=post action=cursos.php>
	<font face=Arial>Contrase&ntilde;a Familia:</font><br>
	<input type=password name=password id=password><br>";
	echo "<input type=hidden name=id_publicacion id=id_publicacion value=$_GET[id_publicacion]>";
	echo "<input type=submit name=pop id=pop value=Capturar></form>";
}
if($_POST[id_publicacion]!=''){
	echo "<form id=formacursos name=formacursos method=post action=cursos.php>
	<font face=Arial>Contrase&ntilde;a Familia:</font><br>
	<input type=password name=password id=password><br>";
	echo "<input type=hidden name=id_publicacion id=id_publicacion value=$_POST[id_publicacion]>";
	echo "<input type=submit name=pop id=pop value=Capturar></form>";
}
?>
</body>