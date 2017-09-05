<?
    $nombre_def=$_POST['nombre_def'];
    $aleatorio = rand(1, 10000000);
    $carpeta =  md5($aleatorio); 
    mkdir("$carpeta",0777);  
    $nuevoarchivo = "$carpeta/$nombre_def";
	$f = fopen($nuevoarchivo,'w'); 
    fwrite($f,$_POST['contenido']); 
    fclose($f); 
	$enlace = "$carpeta/".$nombre_def;  
    header ("Content-Disposition: attachment; filename=".$nombre_def."\n\n");  
    header ("Content-Type: application/octet-stream");  
    header ("Content-Length: ".filesize($enlace));  
    readfile($enlace);  
    //ELIMINA ARCHIVO  
    unlink($enlace);  
    unlink($nombre_def);  
    //ELIMINA DIRECTORIO  
    rmdir($carpeta);
?>