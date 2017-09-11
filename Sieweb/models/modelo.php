<?php
require_once 'connection.php'; 
class modelo extends connection
{
	public function setConect($colegio){
		$this->conectar($colegio);
	}
	public function iniciar_Sesion($user,$pass,$table,$key){
		$sql='SELECT * from $table where $key=$user';
		$res= $this->sql($sql);
		echo($res);
	}

}
?>