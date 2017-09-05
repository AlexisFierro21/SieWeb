<?session_start();
include('connection.php');
include('functions.php');

function getFiles($path) {
if (is_dir($path)) {
if ($dh = opendir($path)) {
$x=0;
while (($file = readdir($dh)) !== false) {
if ($file != "." && $file != ".."){
$x++;
$name=substr($file,0,-4);
echo "<a href='$path/$file'>$name</a><br> ";}}
if($x==0) echo"Por el momento no hay circulares";
closedir($dh);
} else echo"No pudo abrirse el directorio: $path";
} else echo"La ruta no es un directorio: $path";
}

switch ($_SESSION['letter']): 
case 'P':	$files = getFiles("circulares/personal"); break;
case 'F':	$files = getFiles("circulares/familias"); break;
case 'E':	$files = getFiles("circulares/exalumnos"); break;
case 'A': $link = mysql_connect($server, $userName, $password);
mySql_select_db($DB)or die("No se pudo seleccionar DB");

if (!$link) {
   die('No se pudo conectar: ' . mysql_error());
}$clave=$_SESSION['clave'];
		  $resul=mysql_query("select seccion from alumnos where alumno = $clave",$link)or die(mysql_error());
		  $r = mysql_fetch_array($resul);
		  $dir="circulares/alumnos/".$r['seccion'];
		  $files = getFiles($dir);
		  mysql_close($link);
		  break;
default:
endswitch; ?>