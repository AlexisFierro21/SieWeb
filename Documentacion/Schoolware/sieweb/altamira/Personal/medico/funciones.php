<?php
/////////////////////////////
/* Archivo para funciones */
///////////////////////////
include("configuracion.php");
//////////////////////
/*$dbhost=$server;
$dbname=$DB;
$dbuser=$userName;
$dbpass=$password;
$db = new mysqli($dbhost,$dbuser,$dbpass,$dbname);
*/

		
function conectaBaseDatos(){
	try{
		
include("configuracion.php");
/*
		$servidor = "localhost";
		$puerto = "3306";
		$basedatos = "schoolwa_Altamira";
		$usuario = "schoolwa_econtre";
		$contrasena = "si@pTs%FQ9GI";
*/
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

//Seccion
function dameSeccion(){
	
	$resultado = false;
	$consulta = "SELECT * FROM secciones WHERE
    				(CASE
						WHEN month(NOW())>7 THEN year(NOW())
						WHEN month(NOW())<=7 THEN year(NOW())-1
					END ) = ciclo
  				";
	echo $consulta;
	$conexion = conectaBaseDatos();
	$sentencia = $conexion->prepare($consulta);
	
	try {
		if(!$sentencia->execute()){
			print_r($sentencia->errorInfo());
		}
		//$resultado = $sentencia->fetchAll();
		$resultado = $sentencia->fetchAll(PDO::FETCH_ASSOC);
		$sentencia->closeCursor();
	}
	catch(PDOException $e){
		echo "Error al ejecutar la sentencia: \n";
			print_r($e->getMessage());
	}
	
	return $resultado;
}

///Grado
function dameGrado($seccion = ''){
	$resultado = false;
	$consulta = "SELECT 
						ciclo, 
						concat(seccion,'-', grado) as grado , 
						nombre 
					FROM 
						grados ";
	
	if($seccion != ''){
		$consulta .= " WHERE seccion = :seccion 
											AND
							    				(CASE
													WHEN month(NOW())>7 THEN year(NOW())
													WHEN month(NOW())<=7 THEN year(NOW())-1
												END ) = ciclo";
	}
	
	$conexion = conectaBaseDatos();
	$sentencia = $conexion->prepare($consulta);
	$sentencia->bindParam(':seccion',$seccion);
	
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


///Grupo
function dameGrupo($grado = ''){
	$resultado = false;
	$consulta = "SELECT  
						ciclo, 
						CONCAT(seccion,'-', grado,'-', grupo) as grupo,
						nombre
					FROM 
						grupos ";

	if($grado != ''){
		$consulta .= " WHERE  CONCAT(seccion,'-', grado) = :grado	 
											 AND
							    				(CASE
													WHEN month(NOW())>7 THEN year(NOW())
													WHEN month(NOW())<=7 THEN year(NOW())-1
												END ) = ciclo ";
	}
	
	$conexion = conectaBaseDatos();
	$sentencia = $conexion->prepare($consulta);
	$sentencia->bindParam(':grado',$grado);
	
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

///Alumnos
function dameAlumno($grupo = ''){
	$resultado = false;
	$consulta = "SELECT * FROM alumnos ";

	if($grupo != ''){
		$consulta .= " WHERE  
								CONCAT(seccion,'-', grado,'-', grupo) = :grupo 
								AND activo='A' 
						ORDER BY nombre, apellido_paterno, apellido_materno
						
						";
	}
	
	$conexion = conectaBaseDatos();
	$sentencia = $conexion->prepare($consulta);
	$sentencia->bindParam(':grupo',$grupo);
	
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

//// Datos M�dicos
function dameMedico($paciente = ''){
		$resultado=false;	
		$consulta= "SELECT 
						alumno, 
						doctor,
						telefono_doctor,
						tipo_sangre,
						alergias,
						clinica,
						telefono_clinica,
						aseguradora,
						poliza,
						usa_lentes,
						nombre_emer_1,
						parentesco_emer_1,
						tel_casa_emer_1,
						tel_ofna_emer_1
						tel_cel_emer_1,
						nombre_emer_2,
						parentesco_emer_2,
						tel_casa_emer_2,
						tel_ofna_emer_2,
						tel_cel_emer_2
					FROM 
						alumnos ";
						
		if($paciente != ''){
				$consulta .= " WHERE  
									alumno = :paciente ";						
	}
	
	$conexion = conectaBaseDatos();
	$sentencia = $conexion->prepare($consulta);
	$sentencia->bindParam(':paciente',$paciente);
	
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


////////////////////////////////////////////////////
///Empezamos con las funciones de Personal//////
////////////////////////////////////////////////////


function dameProfesor(){
	
	$resultado = false;
	$consulta = "SELECT 
						* 
					FROM 
						personal 
					WHERE 
						status = 'A'
					ORDER BY 
						nombre, apellido_paterno, apellido_materno
  				";
	echo $consulta;
	$conexion = conectaBaseDatos();
	$sentencia = $conexion->prepare($consulta);
	
	try {
		if(!$sentencia->execute()){
			print_r($sentencia->errorInfo());
		}
		//$resultado = $sentencia->fetchAll();
		$resultado = $sentencia->fetchAll(PDO::FETCH_ASSOC);
		$sentencia->closeCursor();
	}
	catch(PDOException $e){
		echo "Error al ejecutar la sentencia: \n";
			print_r($e->getMessage());
	}
	
	return $resultado;
}


function dameFamilia(){
	
	$resultado = false;
	$consulta = "SELECT DISTINCT
						familias.familia, 
						familias.nombre_familia 
					FROM 
						familias, 
						alumnos 
					WHERE 
						alumnos.familia = familias.familia 
						AND 
						alumnos.activo='A'
					ORDER BY 2
  				";
	echo $consulta;
	$conexion = conectaBaseDatos();
	$sentencia = $conexion->prepare($consulta);
	
	try {
		if(!$sentencia->execute()){
			print_r($sentencia->errorInfo());
		}
		//$resultado = $sentencia->fetchAll();
		$resultado = $sentencia->fetchAll(PDO::FETCH_ASSOC);
		$sentencia->closeCursor();
	}
	catch(PDOException $e){
		echo "Error al ejecutar la sentencia: \n";
			print_r($e->getMessage());
	}
	
	return $resultado;
}

function damePadreMadre($familia= ''){
	
	$resultado = false;
	$consulta = "SELECT DISTINCT
					familias.familia,
    				familias.nombre_familia,
					apellido_paterno_padre, 
    				apellido_paterno_madre, 
    				apellido_materno_padre,
    				apellido_materno_madre, 
   					nombre_padre,
    				nombre_madre
				FROM 
					familias, 
    				alumnos 
				WHERE 
					alumnos.familia = familias.familia 
    			AND 
    				alumnos.activo='A'  
					";
					
		if($familia != ''){
				$consulta .= " AND  
									familias.familia = :familia 
							   ORDER BY 2, apellido_paterno, apellido_materno";						
	}
	
	$conexion = conectaBaseDatos();
	$sentencia = $conexion->prepare($consulta);
	$sentencia->bindParam(':familia',$familia);
	
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



function dameTop10($familia= ''){
	
	$resultado = false;
	$consulta = "SELECT DISTINCT
					familias.familia,
    				familias.nombre_familia,
					apellido_paterno_padre, 
    				apellido_paterno_madre, 
    				apellido_materno_padre,
    				apellido_materno_madre, 
   					nombre_padre,
    				nombre_madre
				FROM 
					familias, 
    				alumnos 
				WHERE 
					alumnos.familia = familias.familia 
    			AND 
    				alumnos.activo='A'  ";
					
		if($familia != ''){
				$consulta .= " AND  
									familias.familia = :familia 
							   ORDER BY 2";						
	}
	
	$conexion = conectaBaseDatos();
	$sentencia = $conexion->prepare($consulta);
	$sentencia->bindParam(':familia',$familia);
	
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
////////////////////////////////////////////////////////////////////
///  Obtenemos los valores de Motivo de La Consulta para mostrarlos/ 
///	 en pantalla												   /		
////////////////////////////////////////////////////////////////////
function dameMotivos(){
	
	$resultado = false;
	$consulta = "SELECT *
					FROM
						medico_motivos_de_consulta";
						
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

/////////////////////////////////////////////////
// Solicitamos los diagnosticos				////
////////////////////////////////////////////////

function dameDiagnosticos(){
	
	$resultado = false;
	$consulta = "SELECT *
					FROM
						medico_diagnostico";
						
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


function dameReferido($referido_tipo=''){
	$resultado = false;
	if($referido_tipo == '1'){
	///Familia
	
		$consulta = "SELECT nombre_familia AS nombre FROM familias WHERE activo_web = 'A' ";
	
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
	elseif($referido_tipo == '2'){
	///Personal	
	$consulta = "SELECT CONCAT(apellido_paterno,' ',apellido_materno,', ', nombre) AS nombre FROM personal WHERE status = 'A' ";
	
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
	elseif($referido_tipo == '3'){
	///Externos	
		
	}
	
	
	
}


function damePersonal(){
	
	$resultado = false;
	$consulta = "SELECT 
						  				empleado AS id,
						  				CONCAT(apellido_paterno,' ', apellido_materno,', ', nombre) AS nombre
						  			FROM 
										personal 
									WHERE 
										status = 'A'
									ORDER BY nombre	
  				";
	
	//echo $consulta;
	$conexion = conectaBaseDatos();
	$sentencia = $conexion->prepare($consulta);
	
	try {
		if(!$sentencia->execute()){
			print_r($sentencia->errorInfo());
		}
		//$resultado = $sentencia->fetchAll();
		$resultado = $sentencia->fetchAll(PDO::FETCH_ASSOC);
		$sentencia->closeCursor();
	}
	catch(PDOException $e){
		echo "Error al ejecutar la sentencia: \n";
			print_r($e->getMessage());
	}
	
	return $resultado;
}

function dameHistorialHijo(){
	
	$resultado = false;
	$consulta = "SELECT * FROM expediente_medico 
								WHERE 
									tipo_de_paciente='1' 
  				";
	if($hijo != ''){
				$consulta .= " AND  
									paciente= :paciente 
							   ORDER BY fecha_y_hora_de_consulta AS DESC";						
	}
	
	$conexion = conectaBaseDatos();
	$sentencia = $conexion->prepare($consulta);
	$sentencia->bindParam(':paciente',$paciente);
	
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