<?
include('config.php');
function f_encripta($clave,$password,$encripta_desencripta){
	$iSumandos=fMod($clave*7,3);
	switch($iSumandos):
	case 0:
		$iSumando[1] = -3;
		$iSumando[2] = -5;
		$iSumando[3] = -7;
		$iSumando[4] = 11;
		$iSumando[5] = 13;
		$iSumando[6] = 17;
		break;
	case 1:
		$iSumando[1] = 13;
		$iSumando[2] = -7;
		$iSumando[3] = 17;
		$iSumando[4] = -3;
		$iSumando[5] = 11;
		$iSumando[6] = -5;
		break;
	case 2:
		$iSumando[1] = -7;
		$iSumando[2] = 13;
		$iSumando[3] = -3;
		$iSumando[4] = 17;
		$iSumando[5] = -5;
		$iSumando[6] = 11;
		break;
	endswitch;
	if($encripta_desencripta=="D")
		for($iSumandos=1;$iSumandos<=6;$iSumandos++)
			$iSumando[$iSumandos]=-$iSumando[$iSumandos];
	$sEncriptado='';
	$iSumandos=1;
	do{
		$sEncriptado=$sEncriptado.chr(ord($password)+$iSumando[$iSumandos]);
		$iSumandos = $iSumandos + 1;
		if($iSumandos>6)
			$iSumandos=1;
		$password=subStr($password,1);
	}
	while(strLen($password)>0);
	RETURN $sEncriptado;
} 
//////////////////////////////////////////////////
$count=0;
echo "<table rules=all><tr style='font-weight:bold'><td>familia</td><td>pass familias</td><td>fecha_modificacion</td><td>pass bitacora</td><td>fecha bitacora</td></tr>";
$result=mysql_query("select familia,password_web,fecha_modificacion from familias where familia in (select distinct familia from inscripciones_bitacora) order by familia");
while($family=mysql_fetch_array($result)){
	$familia=$family[familia];
	$password=$family[password_web];
	$fechamod=$family[fecha_modificacion];
	$fam=f_encripta($familia,$password,'D');
	$result2=mysql_query("select pasword,fecha_modificacion from inscripciones_bitacora where familia=$familia");
	$bit=mysql_result($result2,0,0);
	$fec=mysql_result($result2,0,1);
	if($fam!=$bit){
		echo "<tr><td>$familia</td><td>$fam</td><td>$fechamod</td><td>$bit</td><td>$fec</td></tr>";
		$count++;
	}
}
echo "</table><br><br>total: $count";
?>