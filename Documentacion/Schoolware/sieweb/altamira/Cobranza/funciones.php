<?php
/////////////////////////////
/* Archivo para funciones  */
/* Modificación 01/12/2016 */
/*  Filtro para solamente adeudos  
 *  vencidos/ Sin vencer
 * Cambios solicitados por colegio liceo
 * 
 * 
 * 
 * */
/////////////////////////////
//include("configuracion.php");
//////////////////////
/*$dbhost=$server;
$dbname=$DB;
$dbuser=$userName;
$dbpass=$password;
$db = new mysqli($dbhost,$dbuser,$dbpass,$dbname);
*/

		
function conectaBaseDatos(){
	try{
		
include("../connection.php");

		$servidor = $server;
		$puerto = "3306";
		$basedatos = $DB;
		$usuario = $userName;
		$contrasena = $password;

		$conexion = new PDO("mysql:host=$servidor;port=$puerto;dbname=$basedatos",
							$usuario,
							$contrasena,
							array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
		
		$conexion->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
		return $conexion;
	}
	catch (PDOException $e){
		die ("No se puede conectar a la base de datos". $e->getMessage());
	}
}


//AdeudosFamilia 10337
function AdeudosFamilia($Familia = '', $activos = ""){
	
	$activo = $_POST['activos'];
	
	if($activo == "activos"){
		$and = " AND fecha_pago <= CURDATE() ";
	}else{
		$and = "";
	}

		$resultado=false;	
		$consulta= "SELECT *
				,
 CASE                         
    WHEN inscrip_coleg_otros <> 'C' THEN '0.00'
    ELSE(
 CASE WHEN 
 DATEDIFF(curdate(),
    CONCAT(
case M_Relativo
        WHEN '1' then estado_cuenta.periodo +1
        WHEN '2' then estado_cuenta.periodo +1
		WHEN '3' then estado_cuenta.periodo +1
        WHEN '4' then estado_cuenta.periodo +1
        WHEN '5' then estado_cuenta.periodo +1
        WHEN '6' then estado_cuenta.periodo +1
        WHEN '7' then estado_cuenta.periodo +1
        WHEN '8' then estado_cuenta.periodo 
        WHEN '9' then estado_cuenta.periodo 
        WHEN '10' then estado_cuenta.periodo 
        WHEN '11' then estado_cuenta.periodo 
        WHEN '12' then estado_cuenta.periodo 
        ELSE '00'
    END 
,'-',case mes
        WHEN 'Jan' then '01'
        WHEN 'Feb' then '02'
		WHEN 'Mar' then '03'
        WHEN 'Apr' then '04'
        WHEN 'May' then '05'
        WHEN 'Jun' then '06'
        WHEN 'Jul' then '07'
        WHEN 'Aug' then '08'
        WHEN 'Sep' then '09'
        WHEN 'Oct' then '10'
        WHEN 'Nov' then '11'
        WHEN 'Dec' then '12'
        ELSE '00'
    END ,'-','10')) < 1 THEN '0.00'
    ELSE
(IFNULL(cargos,0.00)-IFNULL(abonos,0.00)) * (    
   DATEDIFF(CURDATE(), 
    CONCAT(
case M_Relativo
        WHEN '1' then estado_cuenta.periodo +1
        WHEN '2' then estado_cuenta.periodo +1
		WHEN '3' then estado_cuenta.periodo +1
        WHEN '4' then estado_cuenta.periodo +1
        WHEN '5' then estado_cuenta.periodo +1
        WHEN '6' then estado_cuenta.periodo +1
        WHEN '7' then estado_cuenta.periodo +1
        WHEN '8' then estado_cuenta.periodo 
        WHEN '9' then estado_cuenta.periodo 
        WHEN '10' then estado_cuenta.periodo 
        WHEN '11' then estado_cuenta.periodo 
        WHEN '12' then estado_cuenta.periodo 
        ELSE '00'
    END 
,'-',case mes
        WHEN 'Jan' then '01'
        WHEN 'Feb' then '02'
		WHEN 'Mar' then '03'
        WHEN 'Apr' then '04'
        WHEN 'May' then '05'
        WHEN 'Jun' then '06'
        WHEN 'Jul' then '07'
        WHEN 'Aug' then '08'
        WHEN 'Sep' then '09'
        WHEN 'Oct' then '10'
        WHEN 'Nov' then '11'
        WHEN 'Dec' then '12'
        ELSE '00'
    END ,'-','10'))) * 0.0008  END ) END  AS Intereses
											
						FROM
							estado_cuenta
					";
						
		if($Familia != ''){
				$consulta .= "WHERE
									familia = :familia 
									:and
								ORDER BY
									alumno, periodo, mes, concepto
  				 ";						
	}
	
	$conexion = conectaBaseDatos();
	$consulta = str_replace(':and', $and, $consulta);
	$sentencia = $conexion->prepare($consulta);
	$sentencia->bindParam(':familia',$Familia);
	
	try {
		if(!$sentencia->execute()){
			print_r($sentencia->errorInfo());
		}
		$resultado = $sentencia->fetchAll();
		//$resultado = $sentencia->fetchAll(PDO::FETCH_ASSOC);
		$sentencia->closeCursor();
	}
	catch(PDOException $e){
		echo "Error al ejecutar la sentencia: \n";
			print_r($e->getMessage());
	}
	return $resultado;
}


///AdeudosTotal
function AdeudosTotal(){

		$resultado=false;	
		$activo = $_POST['activos'];
		
		if($activo == "activos"){
			$and = " 
WHERE  
	fecha_pago <= CURDATE() ";
		}else{
				$and = " ";
			}
		
		$consulta= "
SELECT *
							, 
 CASE                             
    WHEN inscrip_coleg_otros <> 'C' THEN '0.00'
    ELSE(
 CASE WHEN 
 DATEDIFF(curdate(),
    CONCAT(
case M_Relativo
        WHEN '1' then estado_cuenta.periodo +1
        WHEN '2' then estado_cuenta.periodo +1
		WHEN '3' then estado_cuenta.periodo +1
        WHEN '4' then estado_cuenta.periodo +1
        WHEN '5' then estado_cuenta.periodo +1
        WHEN '6' then estado_cuenta.periodo +1
        WHEN '7' then estado_cuenta.periodo +1
        WHEN '8' then estado_cuenta.periodo 
        WHEN '9' then estado_cuenta.periodo 
        WHEN '10' then estado_cuenta.periodo 
        WHEN '11' then estado_cuenta.periodo 
        WHEN '12' then estado_cuenta.periodo 
        ELSE '00'
    END 
,'-',case mes
        WHEN 'Jan' then '01'
        WHEN 'Feb' then '02'
		WHEN 'Mar' then '03'
        WHEN 'Apr' then '04'
        WHEN 'May' then '05'
        WHEN 'Jun' then '06'
        WHEN 'Jul' then '07'
        WHEN 'Aug' then '08'
        WHEN 'Sep' then '09'
        WHEN 'Oct' then '10'
        WHEN 'Nov' then '11'
        WHEN 'Dec' then '12'
        ELSE '00'
    END ,'-','10')) < 1 THEN '0.00'
    ELSE
(IFNULL(cargos,0.00)-IFNULL(abonos,0.00)) * (    
   DATEDIFF(CURDATE(), 
    CONCAT(
case M_Relativo
        WHEN '1' then estado_cuenta.periodo +1
        WHEN '2' then estado_cuenta.periodo +1
		WHEN '3' then estado_cuenta.periodo +1
        WHEN '4' then estado_cuenta.periodo +1
        WHEN '5' then estado_cuenta.periodo +1
        WHEN '6' then estado_cuenta.periodo +1
        WHEN '7' then estado_cuenta.periodo +1
        WHEN '8' then estado_cuenta.periodo 
        WHEN '9' then estado_cuenta.periodo 
        WHEN '10' then estado_cuenta.periodo 
        WHEN '11' then estado_cuenta.periodo 
        WHEN '12' then estado_cuenta.periodo 
        ELSE '00'
    END 
,'-',case mes
        WHEN 'Jan' then '01'
        WHEN 'Feb' then '02'
		WHEN 'Mar' then '03'
        WHEN 'Apr' then '04'
        WHEN 'May' then '05'
        WHEN 'Jun' then '06'
        WHEN 'Jul' then '07'
        WHEN 'Aug' then '08'
        WHEN 'Sep' then '09'
        WHEN 'Oct' then '10'
        WHEN 'Nov' then '11'
        WHEN 'Dec' then '12'
        ELSE '00'
    END ,'-','10'))) * 0.0008  END ) END  AS Intereses
			
						FROM
							estado_cuenta
				
						:and
				
						ORDER BY
									familia, alumno, periodo, mes, concepto
                   ";										  
	
	$conexion = conectaBaseDatos();
	$consulta = str_replace(':and', $and, $consulta);
	$sentencia = $conexion->prepare($consulta);
	
	
	try {
		if(!$sentencia->execute()){
			print_r($sentencia->errorInfo());
		}
		$resultado = $sentencia->fetchAll();
		//$resultado = $sentencia->fetchAll(PDO::FETCH_ASSOC);
		$sentencia->closeCursor();
	}
	catch(PDOException $e){
		echo "Error al ejecutar la sentencia: \n";
			print_r($e->getMessage());
	}
	return $resultado;
}


///AdeudosAcumulados
function AdeudosAcumulados(){

/*	
	$activo = $_POST['activos'];
	if(isset($activo)){
		$and = " AND  CONCAT(
case 
        WHEN '1' then estado_cuenta.periodo +1
        WHEN '2' then estado_cuenta.periodo +1
		WHEN '3' then estado_cuenta.periodo +1
        WHEN '4' then estado_cuenta.periodo +1
        WHEN '5' then estado_cuenta.periodo +1
        WHEN '6' then estado_cuenta.periodo +1
        WHEN '7' then estado_cuenta.periodo +1
        WHEN '8' then estado_cuenta.periodo 
        WHEN '9' then estado_cuenta.periodo 
        WHEN '10' then estado_cuenta.periodo 
        WHEN '11' then estado_cuenta.periodo 
        WHEN '12' then estado_cuenta.periodo 
        ELSE '00'
    END 
,'-',case mes
        WHEN 'Jan' then '01'
        WHEN 'Feb' then '02'
		WHEN 'Mar' then '03'
        WHEN 'Apr' then '04'
        WHEN 'May' then '05'
        WHEN 'Jun' then '06'
        WHEN 'Jul' then '07'
        WHEN 'Aug' then '08'
        WHEN 'Sep' then '09'
        WHEN 'Oct' then '10'
        WHEN 'Nov' then '11'
        WHEN 'Dec' then '12'
        ELSE '00'
    END ,'-','10') <= CURDATE() ";
	}else{
		$and = "";
	}
	*/
		$resultado=false;	
		$consulta= "SELECT 
							familia,
        					cargos,
        					abonos
						FROM
							estado_cuenta
						GROUP BY
							familia
						ORDER BY
							cargos-abonos desc    
					";
					
	$conexion = conectaBaseDatos();
	$sentencia = $conexion->prepare($consulta);
	//$sentencia->bindParam(':familia',$Familia);
	
	try {
		if(!$sentencia->execute()){
			print_r($sentencia->errorInfo());
		}
		$resultado = $sentencia->fetchAll();
		//$resultado = $sentencia->fetchAll(PDO::FETCH_ASSOC);
		$sentencia->closeCursor();
	}
	catch(PDOException $e){
		echo "Error al ejecutar la sentencia: \n";
			print_r($e->getMessage());
	}
	return $resultado;
}

///Función para ejecutar Actualizar Adeudos///
function Actualizar(){
   	
   $resultado=false;	
		$consulta= "CALL estado_cuenta ";
					
	$conexion = conectaBaseDatos();
	$sentencia = $conexion->prepare($consulta);
	
	try {
		if(!$sentencia->execute()){
			print_r($sentencia->errorInfo());
		}
		$resultado = $sentencia->fetchAll();
		//$resultado = $sentencia->fetchAll(PDO::FETCH_ASSOC);
		$sentencia->closeCursor();
	}
	catch(PDOException $e){
		echo "Error al ejecutar la sentencia: \n";
			print_r($e->getMessage());
	}
	return $resultado;
}

///Función para insertar valores de los correos a enviar a los padres///
function Enviar(){
   	
   $resultado=false;	
		$consulta= "CALL estado_cuenta ";
					
	$conexion = conectaBaseDatos();
	$sentencia = $conexion->prepare($consulta);
	
	try {
		if(!$sentencia->execute()){
			print_r($sentencia->errorInfo());
		}
		$resultado = $sentencia->fetchAll();
		//$resultado = $sentencia->fetchAll(PDO::FETCH_ASSOC);
		$sentencia->closeCursor();
	}
	catch(PDOException $e){
		echo "Error al ejecutar la sentencia: \n";
			print_r($e->getMessage());
	}
	return $resultado;
}
function AdeudosFiltro(){
	$sgg = $_POST['filtro'];
	
	$activo = $_POST['activos'];
	
	if($activo == "activos"){
		$and = " AND  fecha_pago <= CURDATE() ";
	}else{
		$and = "";
	}
	
	
	$resultado=false;	
		$consulta= "SELECT *
							, 
 CASE                             
    WHEN inscrip_coleg_otros <> 'C' THEN '0.00'
    ELSE(
 CASE WHEN 
 DATEDIFF(curdate(),
    CONCAT(
case M_Relativo
        WHEN '1' then estado_cuenta.periodo +1
        WHEN '2' then estado_cuenta.periodo +1
		WHEN '3' then estado_cuenta.periodo +1
        WHEN '4' then estado_cuenta.periodo +1
        WHEN '5' then estado_cuenta.periodo +1
        WHEN '6' then estado_cuenta.periodo +1
        WHEN '7' then estado_cuenta.periodo +1
        WHEN '8' then estado_cuenta.periodo 
        WHEN '9' then estado_cuenta.periodo 
        WHEN '10' then estado_cuenta.periodo 
        WHEN '11' then estado_cuenta.periodo 
        WHEN '12' then estado_cuenta.periodo 
        ELSE '00'
    END 
,'-',case mes
        WHEN 'Jan' then '01'
        WHEN 'Feb' then '02'
		WHEN 'Mar' then '03'
        WHEN 'Apr' then '04'
        WHEN 'May' then '05'
        WHEN 'Jun' then '06'
        WHEN 'Jul' then '07'
        WHEN 'Aug' then '08'
        WHEN 'Sep' then '09'
        WHEN 'Oct' then '10'
        WHEN 'Nov' then '11'
        WHEN 'Dec' then '12'
        ELSE '00'
    END ,'-','10')) < 1 THEN '0.00'
    ELSE
(IFNULL(cargos,0.00)-IFNULL(abonos,0.00)) * (    
   DATEDIFF(CURDATE(), 
    CONCAT(
case M_Relativo
        WHEN '1' then estado_cuenta.periodo +1
        WHEN '2' then estado_cuenta.periodo +1
		WHEN '3' then estado_cuenta.periodo +1
        WHEN '4' then estado_cuenta.periodo +1
        WHEN '5' then estado_cuenta.periodo +1
        WHEN '6' then estado_cuenta.periodo +1
        WHEN '7' then estado_cuenta.periodo +1
        WHEN '8' then estado_cuenta.periodo 
        WHEN '9' then estado_cuenta.periodo 
        WHEN '10' then estado_cuenta.periodo 
        WHEN '11' then estado_cuenta.periodo 
        WHEN '12' then estado_cuenta.periodo 
        ELSE '00'
    END 
,'-',case mes
        WHEN 'Jan' then '01'
        WHEN 'Feb' then '02'
		WHEN 'Mar' then '03'
        WHEN 'Apr' then '04'
        WHEN 'May' then '05'
        WHEN 'Jun' then '06'
        WHEN 'Jul' then '07'
        WHEN 'Aug' then '08'
        WHEN 'Sep' then '09'
        WHEN 'Oct' then '10'
        WHEN 'Nov' then '11'
        WHEN 'Dec' then '12'
        ELSE '00'
    END ,'-','10'))) * 0.0008  END ) END  AS Intereses,
				 CONCAT(
case 
        WHEN '1' then estado_cuenta.periodo +1
        WHEN '2' then estado_cuenta.periodo +1
		WHEN '3' then estado_cuenta.periodo +1
        WHEN '4' then estado_cuenta.periodo +1
        WHEN '5' then estado_cuenta.periodo +1
        WHEN '6' then estado_cuenta.periodo +1
        WHEN '7' then estado_cuenta.periodo +1
        WHEN '8' then estado_cuenta.periodo 
        WHEN '9' then estado_cuenta.periodo 
        WHEN '10' then estado_cuenta.periodo 
        WHEN '11' then estado_cuenta.periodo 
        WHEN '12' then estado_cuenta.periodo 
        ELSE '00'
    END 
,'-',case mes
        WHEN 'Jan' then '01'
        WHEN 'Feb' then '02'
		WHEN 'Mar' then '03'
        WHEN 'Apr' then '04'
        WHEN 'May' then '05'
        WHEN 'Jun' then '06'
        WHEN 'Jul' then '07'
        WHEN 'Aug' then '08'
        WHEN 'Sep' then '09'
        WHEN 'Oct' then '10'
        WHEN 'Nov' then '11'
        WHEN 'Dec' then '12'
        ELSE '00'
    END ,'-','10') AS fecha_de_pago
				
				
						FROM
							estado_cuenta, alumnos
						WHERE
							alumnos.alumno = estado_cuenta.alumno 
							AND
                            concat(alumnos.seccion,alumnos.grado,alumnos.grupo) = '{$sgg}' 
                            
                            :and
                            
						ORDER BY
									familia, alumno, periodo, mes, concepto ";
					
	$conexion = conectaBaseDatos();
	$consulta = str_replace(':and', $and, $consulta);
	$sentencia = $conexion->prepare($consulta);
	$sentencia->bindParam(':filtro', $filtro);
	
	try {
		if(!$sentencia->execute()){
			print_r($sentencia->errorInfo());
		}
		$resultado = $sentencia->fetchAll();
		//$resultado = $sentencia->fetchAll(PDO::FETCH_ASSOC);
		$sentencia->closeCursor();
	}
	catch(PDOException $e){
		echo "Error al ejecutar la sentencia: \n";
			print_r($e->getMessage());
	}
	return $resultado;
	
}

?>