<?php
class connection {
	public $link=null;
	public function conectar($colegio){
		switch ($colegio) {
			case 'altamira':
			$server="schoolware.com.mx";
			$userName="schoolwa_econtre";
			$password="si@pTs%FQ9GI";
			$DB="schoolwa_Altamira";
			$DB_gral="schoolwa_general";
			$this->$link = mysql_connect($server, $userName, $password)or die('No se pudo conectar: ' . mysql_error());
			mySql_select_db($DB)or die("No se pudo seleccionar DB");
			break;

			default:
			# code...
			break;
		}
	}
	public function sql($sql)
	{
		$result=mysql_query($sql,$link)or die(mysql_error());
		$row = mysql_fetch_array($result);
		return $row;
	}
}
?>