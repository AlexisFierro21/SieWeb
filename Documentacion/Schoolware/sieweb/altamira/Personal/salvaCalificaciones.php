<? session_start();
include('../connection.php');
include('../functions.php');
set_time_limit(0);
$link = mysql_connect($server, $userName, $password);
mySql_select_db($DB)or die("No se pudo seleccionar DB");

$ciclo=$_SESSION['ciclo'];
//$ciclo=2005;

if (!$link) {
   die('No se pudo conectar: ' . mysql_error());
}
$dondeSub=$_SESSION['dondeSub'];
$dondeAspectos=$_SESSION['dondeAspectos'];
$hayObservaciones=$_SESSION['hayObservaciones'];
$dondeCalif=$_SESSION['dondeCalif'];
$dondeApreciativa=$_SESSION['dondeApreciativa'];
$dondeFaltas=$_SESSION['dondeFaltas'];
$dondeCirculares=$_SESSION['dondeCirculares'];
$dondeTareas=$_SESSION['dondeTareas'];
$dondeConducta=$_SESSION['dondeConducta'];
$seccion=$_SESSION['seccion'];
$grado=$_SESSION['grado'];
$grupo=$_SESSION['grupo'];
$grupoPond=$_SESSION['grupoPond'];
//$ciclo=$_SESSION['ciclo'];
$materia=$_SESSION['materia'];
$identificadores= $_SESSION['cols'];
$periodoEv=$_SESSION['periodoEv'];
	$newFaltas=$_POST["parFaltas"];
	$newTareas=$_POST["parTareas"];
	$newConducta=$_POST["parConducta"];
	$newCirculares=$_POST["parCirculares"];
	$strCambios=$_POST["modificados"];
	$usuario=$_SESSION['clave'] ;
	$tipo_usuario=$_SESSION['letter'] ;
	$today = getdate();
     $ano=$today["year"];
     $mes=$today["mon"];
     	$cuantosCalif=0;

actualizaEstadistico($tipo_usuario,$usuario,$ano,$mes,2); 

$sqlOptativa="select optativa from materias_ciclos where materia='$materia' and ciclo=$ciclo and seccion=$seccion";
////echo $sqlOptativa;

$resultoptativas=mysql_query($sqlOptativa,$link)or die(mysql_error());
$rowoptativa = mysql_fetch_array($resultoptativas);
$entraOptativa=0;
 if (($rowoptativa["optativa"]!=0) and ($rowoptativa["optativa"]!=""))
 {
	$entraOptativa=1;
}

if ($_SESSION['salvaTareas']==1)
{
  //	//echo "entro salva";
  	//die();
  	
  	if($entraOptativa==1)
  	{

		//echo"Actualizaci&oacute;n 1<br>";
		$opt=mysql_query("SELECT * FROM tareas_periodo where ciclo='$ciclo' and seccion='$seccion' and grado='$grado' and grupo='$grupoPond' and periodo='$periodoEv' and materia='$materia'",$link)or die(mysql_error());

		if (mysql_affected_rows($link)!=0 ){

		  $sqlQInsert="UPDATE tareas_periodo SET asistencia='$newFaltas',tareas='$newTareas',circulares='$newCirculares',fecha_modificacion=now(),usuario='$usuario' WHERE ciclo='$ciclo' and seccion='$seccion' and grado='$grado' and grupo='$grupoPond' and periodo='$periodoEv' and materia='$materia' " ;	

		//echo"Actualizaci&oacute;n 2<br>";
		}

		else{

		//echo"Actualizaci&oacute;n 3<br>";
		 $sqlQInsert="INSERT INTO tareas_periodo( ciclo,seccion,grado,grupo,periodo, materia,asistencia,tareas,circulares,fecha_modificacion, usuario )  VALUES  ( '$ciclo', '$seccion', $grado,'$grupoPond',  '$periodoEv', '$materia', '$newFaltas','$newTareas', '$newCirculares',now(), '$usuario' ) " ;	

}

	}
	else
	{
		$opt=mysql_query("SELECT * FROM tareas_periodo where ciclo='$ciclo' and seccion='$seccion' and grado='$grado' and grupo='$grupo' and periodo='$periodoEv' and materia='$materia'",$link)or die(mysql_error());

		//echo"Actualizaci&oacute;n 4<br>";
		if (mysql_affected_rows($link)!=0 ){

		//echo"Actualizaci&oacute;n 5<br>";
		  $sqlQInsert="UPDATE tareas_periodo SET asistencia='$newFaltas',tareas='$newTareas',circulares='$newCirculares',fecha_modificacion=now(), usuario='$usuario' WHERE ciclo='$ciclo' and seccion='$seccion' and grado='$grado' and grupo='$grupoPond' and periodo='$periodoEv' and materia='$materia' " ;	

		}

		else{

		//echo"Actualizaci&oacute;n 6<br>";
	 $sqlQInsert="INSERT INTO tareas_periodo( ciclo,seccion,grado,grupo,periodo, materia,asistencia,tareas,circulares,fecha_modificacion, usuario )  VALUES  ( '$ciclo', '$seccion', '$grado','$grupo',  '$periodoEv', '$materia', '$newFaltas','$newTareas', '$newCirculares',now(), '$usuario' ) " ;

}

	 }	

  			mysql_query($sqlQInsert,$link)or die(mysql_error());
  			//actualizaEstadistico($tipo_usuario,$usuario,$ano,$mes,2);
  		//	//echo $sqlQInsert;
}
if ($_SESSION['salvaTareas']==2)
{
 
 	if($entraOptativa==1)
  	{
		//echo"Actualizaci&oacute;n 7<br>";
  	   	$sqlQUpdate="UPDATE tareas_periodo SET    tareas = '$newTareas',fecha_modificacion=now(), usuario='$usuario' WHERE  ciclo='$ciclo' and seccion = '$seccion' and grado   = '$grado' and   grupo = '$grupoPond' and periodo ='$periodoEv'  and materia = '$materia'";
		//	//echo $sqlQUpdate;
			mysql_query($sqlQUpdate,$link)or die(mysql_error());
		//echo"Actualizaci&oacute;n 8<br>";
				$sqlQUpdate="UPDATE tareas_periodo SET    asistencia = '$newFaltas' WHERE  ciclo='$ciclo' and seccion = '$seccion' and grado   = '$grado' and   grupo = '$grupoPond' and periodo ='$periodoEv'  and materia = '$materia'";
		//	//echo $sqlQUpdate;
			mysql_query($sqlQUpdate,$link)or die(mysql_error());
		//echo"Actualizaci&oacute;n 9<br>";
				$sqlQUpdate="UPDATE tareas_periodo SET    circulares = '$newCirculares' WHERE  ciclo='$ciclo' and seccion = '$seccion' and grado   = '$grado' and   grupo = '$grupoPond' and periodo ='$periodoEv'  and materia = '$materia'";
  	 
  	 }
  	 else
  	 {
		//echo"Actualizaci&oacute;n 10<br>";
  	$sqlQUpdate="UPDATE tareas_periodo SET    tareas = '$newTareas',fecha_modificacion= now(), usuario='$usuario' WHERE  ciclo='$ciclo' and seccion = '$seccion' and grado   = '$grado' and   grupo = '$grupo' and periodo ='$periodoEv'  and materia = '$materia'";
		//	//echo $sqlQUpdate;
			mysql_query($sqlQUpdate,$link)or die(mysql_error());
		//echo"Actualizaci&oacute;n 11<br>";
				$sqlQUpdate="UPDATE tareas_periodo SET    asistencia = '$newFaltas' WHERE  ciclo='$ciclo' and seccion = '$seccion' and grado   = '$grado' and   grupo = '$grupo' and periodo ='$periodoEv'  and materia = '$materia'";
		//	//echo $sqlQUpdate;
			mysql_query($sqlQUpdate,$link)or die(mysql_error());
		//echo"Actualizaci&oacute;n 12<br>";
				$sqlQUpdate="UPDATE tareas_periodo SET    circulares = '$newCirculares' WHERE  ciclo='$ciclo' and seccion = '$seccion' and grado   = '$grado' and   grupo = '$grupo' and periodo ='$periodoEv'  and materia = '$materia'";
		//	//echo $sqlQUpdate;
		}
		//echo"Actualizaci&oacute;n 13<br> $sqlQUpdate<br>";
			mysql_query($sqlQUpdate,$link)or die(mysql_error());
 				
}
/****porcentaje submaterias*****/
for($i=0;$i<$dondeSub;$i++)
{
	$subLocal= $identificadores[$i];
	$ponderacion=$_POST["C$i"];
	
		//echo"Actualizaci&oacute;n 14<br>";
	$selectPor="select * from pond_periodo_submat WHERE  ciclo='$ciclo' and seccion = '$seccion' and grado   = '$grado' and   grupo = '$grupoPond' and periodo ='$periodoEv'  and materia = '$materia'and submateria='$subLocal'";
mysql_query($selectPor,$link)or die(mysql_error());
if (mysql_affected_rows($link)==0 )
{
		//echo"Actualizaci&oacute;n 15<br>";
	$sqlQueryPor="INSERT INTO pond_periodo_submat( ciclo,seccion,grado,grupo,periodo, materia,submateria,ponderacion)  VALUES  ( '$ciclo', '$seccion', '$grado','$grupo',  '$periodoEv', '$materia', '$subLocal','$ponderacion') " ;	

}
else
{
	$sqlQueryPor="UPDATE pond_periodo_submat SET ponderacion= '$ponderacion' WHERE  ciclo='$ciclo' and seccion = '$seccion' and grado   = '$grado' and   grupo = '$grupoPond' and periodo ='$periodoEv'  and materia = '$materia' and submateria='$subLocal'";
}
		//echo"Actualizaci&oacute;n 16<br>";
mysql_query($sqlQueryPor,$link)or die(mysql_error());

	
}

/****porcentaje aspectos*****/
for($i=$dondeSub;$i<$dondeAspectos;$i++)
{
		$subLocal= $identificadores[$i];
	$ponderacion=$_POST["C$i"];
	
		//echo"Actualizaci&oacute;n 17<br>";
	$selectPor="select * from pond_periodo_aspecto WHERE  ciclo=$ciclo and seccion = $seccion and grado   = $grado and   grupo = '$grupoPond' and periodo ='$periodoEv'  and materia = '$materia'and aspecto='$subLocal'";
mysql_query($selectPor,$link)or die(mysql_error());
if (mysql_affected_rows($link)==0 )
{
		//echo"Actualizaci&oacute;n 18<br>";
	$sqlQueryPor="INSERT INTO pond_periodo_aspecto( ciclo,seccion,grado,grupo,periodo, materia,aspecto,ponderacion, fecha_modificacion, usuario)  VALUES  ( '$ciclo', '$seccion', '$grado','$grupo',  '$periodoEv', '$materia', '$subLocal','$ponderacion', now(), '$usuario') " ;	

}
else
{
		//echo"Actualizaci&oacute;n 19<br>";
	$sqlQueryPor="UPDATE pond_periodo_aspecto SET ponderacion= '$ponderacion',fecha_modificacion=now(), usuario='$usuario' WHERE  ciclo='$ciclo' and seccion = '$seccion' and grado   = '$grado' and   grupo = '$grupoPond' and periodo ='$periodoEv'  and materia = '$materia' and aspecto='$subLocal'";
}
mysql_query($sqlQueryPor,$link)or die(mysql_error());
}
/*********/
//die();

////echo $newFaltas;
////echo $newTareas;
////echo $newCirculares;

////echo $identificadores[1];

/*//echo "Aspectos=$dondeAspectos";
//echo "Calif=$dondeCalif";
//echo "Apreciativa=$dondeApreciativa";
//echo "Faltas=$dondeFaltas";
//echo "Circulares=$dondeCirculares";
//echo "Tareas=$dondeTareas";
*/
$sede=$_SESSION['sede'];
$ciclo=$_SESSION['ciclo'];

/* OPTATIVAS
AQUI SE VERIFICA SI LA MATERIA ES OPTATIVA, SI LO ES SE CAMBIA EL QUERY $SQLQ, Y DESPUES SE HACE UN PEQUEÑO QUERY PARA VERIFICAR EL gRUPO DEL ALUMNO MAS ABAJO
*/

/**/

if($entraOptativa==1)
		  {
		//echo"Actualizaci&oacute;n 20<br>";
		      $sqlAlumnosOpt = "select * from alumnos_optativas where seccion='$seccion' and grado='$grado'  and ciclo='$ciclo' and materia='$materia'";
		      $resultalumoptativas=mysql_query($sqlAlumnosOpt,$link)or die(mysql_error());
		      $cadenaAlOpt="";
		      	while ($rowalumoptativa = mysql_fetch_array($resultalumoptativas))
			{
		      	 $cadenaAlOpt="$cadenaAlOpt,".$rowalumoptativa["alumno"];
			}
		      if ($cadenaAlOpt!="")
		      {
				$cadenaAlOpt=substr($cadenaAlOpt, 1);	
				
				$sqlQ = "select * from alumnos where seccion='$seccion' and grado='$grado'   and activo='A' and plantel=$sede and nuevo_ingreso <> 'P' and alumno in ($cadenaAlOpt)order by apellido_paterno, apellido_materno, nombre ";
				
			//	$sqlQ = "select * from alumnos where alumno in ($cadenaAlOpt)order by apellido_paterno, apellido_materno, nombre ";
			  }
		  }

else{
    $sqlQ = "select * from alumnos where seccion='$seccion' and grado='$grado' and grupo='$grupo' and activo='A' and plantel=$sede and nuevo_ingreso <> 'P' order by apellido_paterno, apellido_materno, nombre ";
    }
/**/

//$sqlQ = "select * from alumnos where seccion='$seccion' and grado='$grado' and grupo='$grupo' and activo='A' and plantel=$sede and nuevo_ingreso <> 'P' order by apellido_paterno, apellido_materno, nombre ";

		//echo"Actualizaci&oacute;n 21<br>";
$result=mysql_query($sqlQ,$link)or die(mysql_error());

$modificado=true;
////echo $sqlQ;
while ($row = mysql_fetch_array($result))
{
 
  $iniciaCol=0;
  $alumno=$row['alumno'];
  
  $grupo=$row['grupo'];
  /*SI LA MATERIA ERA OPTATIVA AQU&Iacute; SE HACE UN QUERY PARA CAMBIAR EL gRUPO DEL ALUMNO EN TRUNO $gRUPO*/
  
  $aalumno="A$alumno";
  
  
  $pos=strpos($strCambios,$aalumno);
  ////echo"strCambios=$strCambios -- alumno=$aalumno -- pos=$pos \n ";
  if (strpos($strCambios,$aalumno)===false)
  {
	$modificado=false;
  }
  else
  {
	$modificado=true;
	////echo "entre";
}

///SUBMATERIAS
	for ($i=$iniciaCol; $i<$dondeSub;$i++) 
	{

	  $sub=$identificadores[$i] ;
	  $cadena="C$i$aalumno";
	  $calificacion=$_POST[$cadena];
	  $cadena2="A$sub";
	  //$ponderacion=$_POST[$cadena2]; 
	  
	  if (is_numeric($calificacion))
	  	{	 
	  $sqlQRe="SELECT count(*)as cuantos FROM   calif_submaterias WHERE  ciclo=$ciclo and seccion ='$seccion' and grado   = $grado and    grupo = '$grupo' and periodo = '$periodoEv' and materia = '$materia' and    submateria = '$sub' and alumno = $alumno";
		
		$result2=mysql_query($sqlQRe,$link)or die(mysql_error());
		
		
		$rowCuantos = mysql_fetch_array($result2);
		if ($rowCuantos['cuantos']==0 )
			{	
		//echo"Actualizaci&oacute;n 22<br>";
			   $sqlQInsert="INSERT INTO calif_submaterias( ciclo,seccion,grado,grupo,periodo,   materia,submateria,alumno,calificacion, fecha_modificacion, usuario )  VALUES  ( '$ciclo', '$seccion', '$grado','$grupo',  '$periodoEv', '$materia', '$sub','$alumno', '$calificacion',now(),'$usuario' ) " ;	
  			mysql_query($sqlQInsert,$link)or die(mysql_error());
  
			}
		elseif ($modificado)
			{
		//echo"Actualizaci&oacute;n 23<br>";
		$sqlQUpdate="UPDATE calif_submaterias SET    calificacion = '$calificacion', fecha_modificacion=now(), usuario='$usuario' WHERE  ciclo='$ciclo' and seccion = '$seccion' and grado   = '$grado' and   grupo = '$grupo' and periodo ='$periodoEv'  and materia = '$materia' and    submateria ='$sub' and alumno = '$alumno';";
	
			mysql_query($sqlQUpdate,$link)or die(mysql_error());
			$modificado=true;
			}
		}
		
		
	$iniciaCol=$iniciaCol+1;
  	}
////////////  //Aspectos
	for ($i=$iniciaCol; $i<$dondeAspectos;$i++) 
	{
	  $aspecto=$identificadores[$i] ;
	  $cadena="C$i$aalumno";
	  $calificacion=$_POST[$cadena];
	  $cadena2="A$aspecto";
	  //$ponderacion=$_POST[$cadena2];
	  
	  
	  
	  
	  if (is_numeric($calificacion))
	  	{
	  	  
	 
	  $sqlQRe="SELECT count(*)as cuantos FROM   calif_aspectos WHERE  ciclo=$ciclo and seccion ='$seccion' and grado   = $grado and    grupo = '$grupo' and periodo = '$periodoEv' and materia = '$materia' and    aspecto = '$aspecto' and alumno = $alumno";
		
		//echo"Actualizaci&oacute;n 24<br>";
		$result2=mysql_query($sqlQRe,$link)or die(mysql_error());
		
		
		$rowCuantos = mysql_fetch_array($result2);
		if ($rowCuantos['cuantos']==0 )
			{	
		//echo"Actualizaci&oacute;n 25<br>";
			   $sqlQInsert="INSERT INTO calif_aspectos( ciclo,seccion,grado,grupo,periodo,   materia,aspecto,alumno,calificacion, fecha_modificacion, usuario )  VALUES  ( '$ciclo', '$seccion', '$grado','$grupo',  '$periodoEv', '$materia', '$aspecto','$alumno', '$calificacion',now(), '$usuario' ) " ;	
  			mysql_query($sqlQInsert,$link)or die(mysql_error());
  
			}
		elseif ($modificado)
			{
		//echo"Actualizaci&oacute;n 26<br>";
		$sqlQUpdate="UPDATE calif_aspectos SET    calificacion = '$calificacion', fecha_modificacion=now(), usuario='$usuario' WHERE  ciclo='$ciclo' and seccion = '$seccion' and grado   = '$grado' and   grupo = '$grupo' and periodo ='$periodoEv'  and materia = '$materia' and    aspecto ='$aspecto' and alumno = '$alumno';";
		//	//echo $sqlQUpdate;
			mysql_query($sqlQUpdate,$link)or die(mysql_error());
			$modificado=true;
			}
		}
		
		
	/*	 if (is_numeric($ponderacion))
	  	{
	 
	  $sqlQRe="SELECT count(*)FROM   calif_aspectos WHERE  ciclo=$ciclo and seccion ='$seccion' and grado   = $grado and    grupo = '$grupo' and periodo = '$periodoEv' and materia = '$materia' and    aspecto = '$aspecto' and alumno = $alumno";
		
		$result2=mysql_query($sqlQRe,$link)or die(mysql_error());
		
		if (mysql_affected_rows($link)==0 )
			{	
	  		$sqlQInsert="INSERT INTO calif_aspectos( ciclo,seccion,grado,grupo,periodo,   materia,aspecto,alumno,calificacion,ponderacion,fecha_modificacion, usuario )  VALUES  ( $ciclo, $seccion, $grado,$grupo,  $periodoEv, $materia, $aspecto,$alumno, $calificacion,$ponderacion, now(), $usuario ) " ;	
  			mysql_query($sqlQInsert,$link)or die(mysql_error());
  
			}
		else
			{
			
			$sqlQUpdate="UPDATE calif_aspectos SET    calificacion = $calificacion,ponderacion=$ponderacion, fecha_modificacion=now(), usuario=$usuario WHERE  ciclo=$ciclo and seccion = $seccion and grado   = $grado and   grupo = '$grupo' and periodo ='$periodoEv'  and materia = '$materia' and    aspecto =$aspecto and alumno = $alumno;";
		//	//echo $sqlQUpdate;
			mysql_query($sqlQUpdate,$link)or die(mysql_error());
			
			}
		}*/
		
		$iniciaCol=$iniciaCol+1;
  	}
  	
  	 //Calif
	for ($i=$iniciaCol; $i<$dondeCalif;$i++) 
	{
	  $aspecto=$identificadores[$i] ;
	  $cadena="C$i$aalumno";
	  $calificacion=$_POST[$cadena];
	  
	    $sqlQRe="SELECT count(*) as cuantos FROM   calificaciones WHERE  ciclo=$ciclo and seccion =$seccion and grado   = $grado and    grupo = '$grupo' and periodo = '$periodoEv' and materia = '$materia' and  alumno = $alumno";
		
		$result2=mysql_query($sqlQRe,$link)or die(mysql_error());
		
		
		$rowCuantos = mysql_fetch_array($result2);
	
		$cuantosCalif=$rowCuantos['cuantos'];
	    
	  if (is_numeric($calificacion))
	  	{
	 
	
		if ($cuantosCalif==0 )
			{	
		//echo"Actualizaci&oacute;n 27<br>";
	  		$sqlQInsert="INSERT INTO calificaciones( ciclo,seccion,grado,grupo,periodo,   materia,alumno,calificacion, fecha_modificacion, usuario )  VALUES  ( '$ciclo', '$seccion', '$grado','$grupo',  '$periodoEv', '$materia', '$alumno', '$calificacion', now(), '$usuario' ) " ;	
  			mysql_query($sqlQInsert,$link)or die(mysql_error());
  			$cuantosCalif=1;
  
			}
		elseif ($modificado)
			{
			
		//echo"Actualizaci&oacute;n 28<br>";
			$sqlQUpdate="UPDATE calificaciones SET    calificacion = '$calificacion', fecha_modificacion=now(), usuario='$usuario' WHERE  ciclo='$ciclo' and seccion = '$seccion' and grado   = '$grado' and   grupo = '$grupo' and periodo ='$periodoEv'  and materia = '$materia'   and alumno = '$alumno';";
			mysql_query($sqlQUpdate,$link)or die(mysql_error());
			$modificado=true;
			}
		}
		$iniciaCol=$iniciaCol+1;
  	}
  	
  	//Materias Apreciativas
  		for ($i=$iniciaCol; $i<$dondeApreciativa;$i++) 
	{
	  $matapre=$identificadores[$i] ;
	  $cadena="C$i$aalumno";
	  $calificacion=$_POST[$cadena];
  
	  if (is_numeric($calificacion))
	  	{
	 
	  $sqlQRe="SELECT count(*)as cuantos FROM   calif_apreciativas WHERE  ciclo=$ciclo and seccion =$seccion and grado   = $grado and    grupo = '$grupo' and periodo = '$periodoEv' and materia = '$materia' and    materia_apreciativa = '$matapre' and alumno = $alumno";
		

		$result2=mysql_query($sqlQRe,$link)or die(mysql_error());
		
			
		$rowCuantos = mysql_fetch_array($result2);
		if ($rowCuantos['cuantos']==0 )
			{	
		//echo"Actualizaci&oacute;n 29<br>";
	  		$sqlQInsert="INSERT INTO calif_apreciativas( ciclo,seccion,grado,grupo,periodo, materia,materia_apreciativa,alumno,calificacion, fecha_modificacion, usuario )  VALUES  ( '$ciclo', '$seccion', '$grado','$grupo',  '$periodoEv', '$materia', '$matapre','$alumno', '$calificacion', now(), '$usuario' ) " ;	
  			mysql_query($sqlQInsert,$link)or die(mysql_error());
  		//	//echo $sqlQInsert;
  
			}
		elseif ($modificado)
			{
			
		//echo"Actualizaci&oacute;n 30<br>";
			$sqlQUpdate="UPDATE calif_apreciativas SET    calificacion = '$calificacion', fecha_modificacion=now(), usuario='$usuario' WHERE  ciclo='$ciclo' and seccion = '$seccion' and grado   = '$grado' and   grupo = '$grupo' and periodo ='$periodoEv'  and materia = '$materia' and    materia_apreciativa ='$matapre' and alumno = $alumno;";
		//	//echo $sqlQUpdate;
			mysql_query($sqlQUpdate,$link)or die(mysql_error());
			$modificado=true;
			}
		}
		$iniciaCol=$iniciaCol+1;
  	}
  	
  	
  		  	 //faltas
	for ($i=$iniciaCol; $i<$dondeFaltas;$i++) 
	{
	  $aspecto=$identificadores[$i] ;
	  $cadena="C$i$aalumno";
	  $faltas=$_POST[$cadena];
	// //echo "cadenafaltas=$cadena"; 
	 ////echo "faltas=$faltas"; 
	  if (is_numeric($faltas))
	  	{
	 
//	 //echo "cuantosCalif=$cuantosCalif ";
		if ($cuantosCalif==0 )
			{	
		//echo"Actualizaci&oacute;n 31<br>";
	  		$sqlQInsert="INSERT INTO calificaciones( ciclo,seccion,grado,grupo,periodo,   materia,alumno,faltas, fecha_modificacion, usuario )  VALUES  ( '$ciclo', '$seccion', '$grado','$grupo',  '$periodoEv', '$materia', '$alumno', '$faltas', now(), '$usuario' ) " ;	
  			mysql_query($sqlQInsert,$link)or die(mysql_error());
  			$cuantosCalif=1;
  
			}
		elseif ($modificado)
			{
			
		//echo"Actualizaci&oacute;n 32<br>";
			$sqlQUpdate="UPDATE calificaciones SET    faltas = '$faltas', fecha_modificacion=now(), usuario='$usuario' WHERE  ciclo='$ciclo' and seccion = '$seccion' and grado   = '$grado' and   grupo = '$grupo' and periodo ='$periodoEv'  and materia = '$materia'   and alumno = '$alumno';";
			////echo $sqlQUpdate;
			mysql_query($sqlQUpdate,$link)or die(mysql_error());
			$modificado=true;
			}
		}
		$iniciaCol=$iniciaCol+1;
  	}
  	
  	
  	 //Tareas

  	for ($i=$iniciaCol; $i<$dondeTareas;$i++) 

	{

	  $aspecto=$identificadores[$i] ;

	  $cadena="C$i$aalumno";

	  $tareas=$_POST[$cadena];

	  if (is_numeric($tareas))

	  	{

		if ($cuantosCalif==0 )

			{

		//echo"Actualizaci&oacute;n 33<br>";
	  		$sqlQInsert="INSERT INTO calificaciones( ciclo,seccion,grado,grupo,periodo,   materia,alumno,tareas_entregadas, fecha_modificacion, usuario )  VALUES  ( '$ciclo', '$seccion', '$grado','$grupo',  '$periodoEv', '$materia', '$alumno', '$tareas', now(), '$usuario' ) " ;	

  			mysql_query($sqlQInsert,$link)or die(mysql_error());

  			$cuantosCalif=1;

			}

		elseif ($modificado)

			{

		//echo"Actualizaci&oacute;n 34<br>";
			$sqlQUpdate="UPDATE calificaciones SET    tareas_entregadas = '$tareas', fecha_modificacion=now(), usuario='$usuario' WHERE  ciclo='$ciclo' and seccion = '$seccion' and grado   = '$grado' and   grupo = '$grupo' and periodo ='$periodoEv'  and materia = '$materia'   and alumno = '$alumno';";

			mysql_query($sqlQUpdate,$link)or die(mysql_error());

			$modificado=true;

			}

		}

		$iniciaCol=$iniciaCol+1;

  	}
  	 //Conducta

  	for ($i=$iniciaCol; $i<$dondeConducta;$i++) 

	{	

	  $aspecto=$identificadores[$i] ;

	  $cadena="C$i$aalumno";

	  $conducta=$_POST[$cadena];

	  if (is_numeric($conducta))

	  	{	  

		if ($cuantosCalif==0 )

			{

		//echo"Actualizaci&oacute;n 35<br>";
	  		$sqlQInsert="INSERT INTO calificaciones( ciclo,seccion,grado,grupo,periodo,   materia,alumno,conducta, fecha_modificacion, usuario='$usuario' )  VALUES  ( '$ciclo', '$seccion', '$grado','$grupo',  '$periodoEv', '$materia', '$alumno', '$conducta', now() ) " ;	

  			mysql_query($sqlQInsert,$link)or die(mysql_error());

  			

			}

		elseif ($modificado)

			{

		//echo"Actualizaci&oacute;n 36<br>";
			$sqlQUpdate="UPDATE calificaciones SET    conducta = '$conducta', fecha_modificacion =now(), usuario='$usuario' WHERE  ciclo='$ciclo' and seccion = '$seccion' and grado   = '$grado' and   grupo = '$grupo' and periodo ='$periodoEv'  and materia = '$materia'   and alumno = '$alumno';";

			mysql_query($sqlQUpdate,$link)or die(mysql_error());

			$modificado=true;

			}

		}

		$iniciaCol=$iniciaCol+1;

  	}	

  //Circulares	

  	for ($i=$iniciaCol; $i<$dondeCirculares;$i++) 

	{

	 $aspecto=$identificadores[$i] ;

	  $cadena="C$i$aalumno";

	  $circulares=$_POST[$cadena];

	  if (is_numeric($circulares))

	  	{ 	if ($cuantosCalif==0 )

			{	

		//echo"Actualizaci&oacute;n 37<br>";
	  		$sqlQInsert="INSERT INTO calificaciones( ciclo,seccion,grado,grupo,periodo,   materia,alumno,circulares_entregadas, fecha_modificacion, usuario )  VALUES  ( '$ciclo', '$seccion', '$grado','$grupo',  '$periodoEv', '$materia', '$alumno', '$circulares', now(), '$usuario' ) " ;	

  			mysql_query($sqlQInsert,$link)or die(mysql_error());

			}

		elseif ($modificado)

			{

		//echo"Actualizaci&oacute;n 38<br>";
			$sqlQUpdate="UPDATE calificaciones SET    circulares_entregadas = '$circulares', fecha_modificacion=now(), usuario='$usuario' WHERE  ciclo='$ciclo' and seccion = '$seccion' and grado   = '$grado' and   grupo = '$grupo' and periodo ='$periodoEv'  and materia = '$materia'   and alumno = '$alumno';";

			mysql_query($sqlQUpdate,$link)or die(mysql_error());

			$modificado=true;

			}

		}

		$iniciaCol=$iniciaCol+1;

  	} 
  	//Observaciones
  	
  		if ($hayObservaciones=='S')
	{
	 $aspecto=$identificadores[$i] ;
	  $cadena="C$i$aalumno";
	  $observacion=$_POST[$cadena];
	  
	  if (is_numeric($observacion))
	  	{
	 
	 
		if ($cuantosCalif==0 )
			{	
		//echo"Actualizaci&oacute;n 39<br>";
	  		$sqlQInsert="INSERT INTO calificaciones( ciclo,seccion,grado,grupo,periodo,   materia,alumno,observacion, fecha_modificacion, usuario )  VALUES  ( '$ciclo', '$seccion', '$grado','$grupo',  '$periodoEv', '$materia', '$alumno', '$observacion', now(), '$usuario' ) " ;	
  			mysql_query($sqlQInsert,$link)or die(mysql_error());
  
			}
		elseif ($modificado)
			{
			
		//echo"Actualizaci&oacute;n 40<br>";
			$sqlQUpdate="UPDATE calificaciones SET    observacion = '$observacion', fecha_modificacion=now(), usuario='$usuario' WHERE  ciclo='$ciclo' and seccion = '$seccion' and grado   = '$grado' and   grupo = '$grupo' and periodo ='$periodoEv'  and materia = '$materia'   and alumno = '$alumno';";
		//	//echo $sqlQUpdate;
			mysql_query($sqlQUpdate,$link)or die(mysql_error());
			$modificado=true;
			}
		}
		$iniciaCol=$iniciaCol+1;
	 
 	
	}
	//comentarios
	if ($dondeCirculares>0)
	{
	 
	 
	 
	 	 $aspecto=$identificadores[$i] ;
	  $cadena="C$i$aalumno";
	  $comentario=$_POST[$cadena];
	  
	  if (is_numeric($comentario))
	  	{
	 //	//echo "entre leve";
	 //	//echo "cuantosCalif=$cuantosCalif";
	 //	//echo "modificado=$modificado";
	  
		if ($cuantosCalif==0 )
			{
	//		 //echo "entro insert2";	
		//echo"Actualizaci&oacute;n 41<br>";
	  		$sqlQInsert="INSERT INTO calificaciones( ciclo,seccion,grado,grupo,periodo,   materia,alumno,comentario, fecha_modificacion, usuario )  VALUES  ( '$ciclo', '$seccion', '$grado','$grupo',  '$periodoEv', '$materia', '$alumno', '$comentario', now(), '$usuario' ) " ;	
  			mysql_query($sqlQInsert,$link)or die(mysql_error());
  
			}
		elseif ($modificado)
			{
	//		//echo "entro update2";
		//echo"Actualizaci&oacute;n 42<br>";
			$sqlQUpdate="UPDATE calificaciones SET    comentario = '$comentario', fecha_modificacion=now(), usuario='$usuario' WHERE  ciclo='$ciclo' and seccion = '$seccion' and grado   = '$grado' and   grupo = '$grupo' and periodo ='$periodoEv'  and materia = '$materia'   and alumno = '$alumno';";
		//	//echo $sqlQUpdate;
			mysql_query($sqlQUpdate,$link)or die(mysql_error());
			$modificado=true;
			}
		}
		$iniciaCol=$iniciaCol+1;
		
	}
//	//echo "noentro2 /n";
}
?>
<html>
   <head> 
      <title>Untitled</title>
   </head>
   <body bgcolor="#FFFFFF" text="#000000" link="#0000FF" vlink="#800080" alink="#FF0000">
   <script language="javascript">
<!-- 
alert("Las Calificaciones se guardaron con exito");
-->
</script>
   </body>
</html>