<head><title>Calendario</title></head>
<script language="javascript">
var month_names = new Array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
var year="";
var month;
function color(campos,tipo){
if(tipo==2){ bg='#ffffff'; c='#000000';}
if(tipo==1){ bg='#ff0000'; c='#ffffff';}
document.all(campos.name).style.background=bg;
document.all(campos.name).style.color=c;
}
function initCalendar() 
{
	if (year=="")
	{	dt		= new Date();
		year 	= dt.getFullYear();
		month   = dt.getMonth();
	}
	else 
	{
        /* Moving in calendar */
        if (month > 11) {
            month = 0;
            year++;
        }
        if (month < 0) {
            month = 11;
            year--;
        }
	}
	document.all('nombre_mes').value=month_names[month];
	document.all('año').value=year;
    var firstDay = new Date(year, month, 1).getDay();
    var lastDay = new Date(year, month + 1, 0).getDate();
	var dias;
	for (i = 0; i < 42; i++)
	{
		if(i<firstDay)  				dias=""; 
		else if(i==firstDay)			dias=1;
		else if(i<(firstDay+lastDay))	dias++;
		else							dias="";		
		document.all('d'+i).value=dias;
	}
}
function returnDate(d) {
	var campo=document.all('campo').value;
	month++;
	var m=month;
	if (month<10) m='0'+month;
	var dia=d.value;
	if (dia<10) dia='0'+dia;
    window.opener.document.all(campo).value = year + '-' + m + '-' + dia ;
    window.close();
}
</script>
<body onLoad="initCalendar(); javascript:resizeTo(360,360)" >

<input type="hidden" name="campo" value="<?=$_GET['campo']?>">
<table width="20%" height="20%" align="center">
<tr>
	<th colspan="7" bgcolor="#3333FF"><input style="font-size:18px; font-weight:bolder; color:#3333FF; font-stretch:expanded; text-align:center" name="año"></th>
</tr>
<tr>
	<th><button onClick="javascript: month--; initCalendar();">&laquo;</button>
	<th colspan="5"><input style="font-size:16px; font-weight:bolder; color:#FF0000; border: thick double #ffffff; text-align:center" name="nombre_mes"></th>
	<th><button onClick="javascript: month++; initCalendar();">&raquo;</button>
</tr>
<tr style="color:#FF0000;">
	<th>D</th><th>L</th><th>M</th><th>M</th><th>J</th><th>V</th><th>S</th>
<?
	for($x=0;$x<42;$x++)
	{
		$y=$x%7;
		if($y==0) echo"</tr><tr>";
		echo "<th><input size='1' readonly='1' name='d$x' style='text-align:center; color:#000000;  background-color:#ffffff;' onClick='javascript: returnDate(this);' onMouseOver='javascript: color(this,1);' onMouseOut='javascript: color(this,2);'></th>";
	}
?>
</tr>
</table>
</body>