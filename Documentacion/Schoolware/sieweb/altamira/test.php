<?php session_start();
$user = $_SESSION["userName"];
$pass = $_SESSION["password"];
$clave = $_REQUEST["clave"];
?>
<style>

 html { 
 	overflow-x:hidden; 
	}

.foo{
	width: 992px;
    height: 480px; 
	max-height: 500px;
	min-height: 300px;
    border: 0px solid black;
        
	-ms-zoom:0.90;
    -moz-transform: scale(0.90);
    -moz-transform-origin: 0 0;
    -o-transform: scale(0.90);
    -o-transform-origin: 0 0;
    -webkit-transform: scale(0.90);
    -webkit-transform-origin: 0 0;
	 overflow-x: visible;

	}
</style>
<body>

<iframe src="http://www.schoolware.education/testAng/#/revisa/F/<? echo $clave ?>" id="iframe1" marginheight="0" frameborder="0"  class="foo" scrolling="no"></iframe>   
</body>

