<? session_start();
include('../connection.php');
include('../config.php');
/////Fecha de última modificación 11-03-2016

$array = explode(",", $_GET['combopago']);
$_SESSION['combopago']=$array;
$link = mysql_connect($server, $userName, $password);
mySql_select_db($DB)or die("No se pudo seleccionar DB");
if (!$link) { die('No se pudo conectar: ' . mysql_error()); }
$pago_e=mysql_query("select * from parametros",$link)or die(mysql_error());
$datos_pago=mysql_fetch_array($pago_e);




$orderid=0;
if(mysql_num_rows(mysql_query("select max(orderidbanco) from pago_electronico"))>0)
	$orderid=mysql_result(mysql_query("select max(orderidbanco) from pago_electronico"),0);
	$caracteres = "abcdefghjkmnpqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ123456789";
	$Aleatorio = str_shuffle($caracteres);
	$orderid_ = substr($Aleatorio,0,15);

$familia=mysql_result(mysql_query("select nombre_familia as c_nombre from familias where familia = $_SESSION[clave]"),0);
$combopago=$_GET['combopago'];

$rst_ = mysql_query ("select * from familias where familia = $_SESSION[clave] ",$link) or die ("select * from familias where familia = $_SESSION[clave]".mysql_error());
	while($rs_=mysql_fetch_array($rst_))
	  {
	  	$fecha_modificacion = $rs_['fecha_modificacion'];
		$facturar_a = $rs_['facturar_a'];
		$direccion_facturar_a = $rs_['direccion_facturar_a'];
		$colonia_facturar_a = $rs_['colonia_facturar_a'];
		$cp_facturar_a = $rs_['cp_facturar_a'];
		$ciudad_facturar_a = $rs_['ciudad_facturar_a'];
		$rfc_facturar_a = $rs_['rfc_facturar_a'];
	  }
	  
$rst_2 = mysql_query ("select * from payworks_parametros",$link) or die ("select * from payworks_parametros".mysql_error());
	  while($rs_2=mysql_fetch_array($rst_2))
	  {
	  	$MerchantId = $rs_2['MerchantId'];
	  	$MerchantName = $rs_2['MerchantName'];
	  	$MerchantCity = $rs_2['MerchantCity'];
	  	$User_Payworks = $rs_2['User'];
	  	$Password = $rs_2['Password'];
	  	$Afiliation = $rs_2['Afiliation'];
	  	$TerminalId = $rs_2['TerminalId'];
	  	$URL_3DSecure = $rs_2['url_3DSecure'];
	  	
	  }
	  
 ?>

<fieldset>
 <table >
	<tr>
		<td rowspan="6">
			<b>Datos Factura:</b>
			<br />	
			<em style="font-size: 12px;" >Nombre</em>
			<br />		
			<em style="font-size: 12px;" >Direcci&oacute;n</em>
			<br />
			<em style="font-size: 12px;" >Colonia</em>
			<br />
			<em style="font-size: 12px;" >C.P.</em>
			<br />
			<em style="font-size: 12px;" >Ciudad</em>
			<br />
			<em style="font-size: 12px;" >R.F.C.</em> 
		</td>
		<td rowspan="6">
			<br />
			<br />
			<input type='text' name='facturar_a' id='facturar_a' value='<?=$facturar_a;?>' size='40' maxlength=40  onBlur="this.value=this.value.toUpperCase();" readonly disabled />	
			<br />
			<input type='text' name='direccion_facturar_a' id='direccion_facturar_a' value='<?=$direccion_facturar_a;?>' size='40' maxlength=40 onBlur="this.value=this.value.toUpperCase();" readonly disabled />	
			<br />
			<input type='text' name='colonia_facturar_a' id='colonia_facturar_a' value='<?=$colonia_facturar_a;?>' size='40' maxlength=40  onBlur="this.value=this.value.toUpperCase();" readonly disabled />	
			<br />
			<input type='text' name='cp_facturar_a' id='cp_facturar_a' value='<?=$cp_facturar_a;?>' size='40' maxlength=40  onBlur="this.value=this.value.toUpperCase();" readonly disabled />	
			<br />
			<input type='text' name='ciudad_facturar_a' id='ciudad_facturar_a' value='<?=$ciudad_facturar_a;?>' size='40' maxlength=40  onBlur="this.value=this.value.toUpperCase();" readonly disabled />	
			<br />
			<input type='text' name='rfc_facturar_a' id='rfc_facturar_a' value='<?=$rfc_facturar_a;?>' size='40' maxlength=40  onBlur="this.value=this.value.toUpperCase();" readonly disabled />		
		</td>
		<td rowspan="6">
			&nbsp;&nbsp;&nbsp;
		</td>
		<td>
			<em style="font-size: 12px;" >Titular de la tarjeta de cr&eacute;dito</em>
		</td>
		<td>
			<input id="titular" name="titular" size=40 maxlength=40  type="text" required/>
		</td>
	</tr>
	<tr>
		<td>
			<em style="font-size: 12px;" >Ingrese los d&iacute;gitos de su tarjeta de cr&eacute;dito / d&eacute;bito</em>
		</td>
		<td>
			<input id="tarjeta" name="tarjeta"  size=20 maxlength=16 type="text" class="solo-numero" required/>
		</td>
	</tr>
	<tr>
		<td>
			<em style="font-size: 12px;" >Tipo de tarjeta</em>
		</td>
		<td>
			<select type='text' name='TipoTarjeta'n id='TipoTarjeta'>
				<option value=''>&nbsp;</option>
  				<option value='MC'>MasterCard</option>
  				<option value='VISA'>Visa</option>
  			</select>
		</td>
	</tr>
	<tr>
		<td>
			<em style="font-size: 12px;" >Fecha de vencimiento</em>
		</td>
		<td>
			<em style="font-size: 11px;" >Mes</em>&nbsp;<select type='text' name='Fec_Mes' id='Fec_Mes'>
  				<option value='01'>01</option>
  				<option value='02'>02</option>
  				<option value='03'>03</option>
  				<option value='04'>04</option>
  				<option value='05'>05</option>
  				<option value='06'>06</option>
  				<option value='07'>07</option>
  				<option value='08'>08</option>
  				<option value='09'>09</option>
  				<option value='10'>10</option>
  				<option value='11'>11</option>
  				<option value='12'>12</option>
  			</select>
  			&nbsp;<em style="font-size: 11px;" >A&ntilde;o</em>&nbsp;<select type='text' name='Fec_Axo' id='Fec_Axo'>
  <?
  		$_actual_y = date("y");
  		$d_final = $_actual_y + 10;
  			for($i = $_actual_y; $i <= $d_final; $i++ ){
  				echo "<option value='$i'>$i</option>";
  			}
  ?>
  </select>
		</td>
	</tr>
	<tr>
		<td>
			<em style="font-size: 12px;" >C&oacute;digo de validaci&oacute;n de la tarjeta de cr&eacute;dito</em>
		</td>
		<td>
			<input name="CodAut" id="CodAut" type="text" size=4 maxlength=4 class="solo-numero" required />
				<a href="tc.jpg" onclick="window.open('tc.jpg','','width=250,height=50,scrollbars=1');return false">
					<em style="font-size: 11px;">&nbsp;&iquest;Qu&eacute; es?</em>
				</a>
		</td>
	</tr>
	<tr>
		<td>
			<em style="font-size: 12px;" >Importe a pagar</em>
		</td>
		<td>
			<p>$&nbsp;<input name="Total_imp" id="Total_imp" value="<?=intval(($_GET['importe']*100))/100; ?>" size=10 maxlength=10 disabled="disabled"/>
		</td>
	</tr>
</table>	
  </p> 	
<font size="1" style="font-style:italic; color:FF0000;">
Verifique que sus datos de facturaci&oacute;n sean correctos, de lo contrario puede capturarlos en la secci&oacute;n "Actualizar Datos Padres"
</font><br>

<font size="3">
  <!-- Nuevos campos Payworks 2.0 -->
  <input name="Total" 			id="Total" 			type="hidden" value="<?=intval(($_GET['importe']*100))/100;?>"> 
  <input name="Card" 			id="Card" 			type="hidden" value=""> 
  <input name="Expires" 		id="Expires" 		type="hidden" value="01/16"> 
  <input name="CardType" 		id="CardType" 		type="hidden" value=""> 
  <input name="Cert3D" 			id="Cert3D" 		type="hidden" value="03"> 
  <input name="Reference3D" 	id="Reference3D" 	type="hidden" value="<?=$orderid_ ;?>"> 
  <input name="MerchantId" 		id="MerchantId" 	type="hidden" value="<?=MerchantId ;?>"> 
  <input name="MerchantName" 	id="MerchantName" 	type="hidden" value="<?=MerchantName ;?>"> 
  <input name="MerchantCity" 	id="MerchantCity" 	type="hidden" value="<?=MerchantCity ;?>"> 
  <input name="ForwardPath" 	id="ForwardPath" 	type="hidden" value="<?=$URL_3DSecure;?>"> 
 <center>
    <table>
    	<tr>
        	<td><input type="button" value="&nbsp;&nbsp;Salir&nbsp;&nbsp;" onclick="javascript:cerrarVentana();" /></td>
  			<td><input type="button" value="Realizar Pago" onclick="javascript:validarTarjeta();" id="pagar" name="pagar"/></td>
			<td><div id="loading" name="loading" class="loading"></div></td>
        </tr>
     </table>       
		<div id="result" name="result" class="result"></div>
		<div id="respuesta_proceso" name="respuesta_proceso" class="respuesta_proceso"></div>
		<div name="respuesta_pago_banorte" id="respuesta_pago_banorte" class="respuesta_pago_banorte"  ></div>
 </center>

</fieldset>
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
<script language="javascript"[B] type="text/javascript"[/B]>
function enableSubmit(value){
		var patt=/[0-9]*/g;
		var result=patt.exec(value);
		document.form1.E1.value=result;
		if(!value.match('[0-9]{4}'))
			document.form1.enviar.disabled=true;
		else
			document.form1.enviar.disabled=false;
	}

//// Fecha de Expiración ///// 
$("#Fec_Mes").on("change", concatVigencia); 
$("#Fec_Axo").on("change", concatVigencia);


//// Tipo de Tarjeta ////
$("#TipoTarjeta").on("change", TipoTar); 

//// Numero de Tarjeta ////
$("#tarjeta").on("change", numTarj); 

function concatVigencia(){
		$fec_mes = $("#Fec_Mes").val();
		$fec_axo = $("#Fec_Axo").val();
			$expira = $("#Fec_Mes").val()+'/'+$("#Fec_Axo").val();
			document.getElementById("Expires").value = $expira;
	}

function TipoTar(){
	if($("#TipoTarjeta").val()!= ""){
	
	$tipo_tarjeta_ = $("#TipoTarjeta").val();
	document.getElementById("CardType").value = $tipo_tarjeta_;
	}else{
		alert("Seleccione su tipo de tarjeta.");
	}
}

function numTarj(){
	$num_tar = $("#tarjeta").val();
	document.getElementById("Card").value = $num_tar;
}


///////////////////////////////////////////////////////////////////////////// 
$(document).ready(function (){ 

          $('.solo-numero').keyup(function (){
            this.value = (this.value + '').replace(/[^0-9]/g, '');
          });
			var max_chars = 16;
    	$('#max').html(max_chars);
    	$('.solo-numero').keyup(function() {
        	var chars = $(this).val().length;
        	var diff = max_chars - chars;
        	$('.contador').html(diff);   
    	});
});

	

function validarTarjeta(){
	

	if($("#titular").val().length < 1) {  
		alert("El titular de la tarjeta no puede quedar vacio"); 
			$("#titular").focus(); 
			return false;  
		}  else{
			
			if($("#tarjeta").val().length < 16) {  
		        alert("La tarjeta debe tener como minimo 16 numeros");  
		        $("#tarjeta").focus();
		        return false;  
		    }  else{	

		    	if($("#CodAut").val().length < 3) {  
			        alert("El codigo de autorizacion debe tener al menos 3 numeros");
			        $("#CodAut").focus();  
			        return false;  
			    }  else{
			    	if($("#TipoTarjeta").val()!= ""){
		    
	$("#pagar").hide();
	
	var loading = '<center><br /><img src="loading.gif" class="loadingimg"  name="loadingimg" id="loadingimg" ></img></center>'
   		$("#loading").html(loading);
   		var proceso_tarjeta = '<font size="1"> Verificando datos de tarjeta. </font>'
   		$("#respuesta_proceso").html(proceso_tarjeta);
		$total = $("#Total").val();///
		$Card = $("#tarjeta").val(); ////
		$Expires = $("#Expires").val(); //
		$CardType = $("#CardType").val(); //
		$Cert3D = $("#Cert3D").val(); //
		$Reference3D = $("#Reference3D").val(); //
		$MerchantId = $("#MerchantId").val(); //
		$MerchantName = $("#MerchantName").val(); //
		$MerchantCity = $("#MerchantCity").val();  //
		$ForwardPath = $("#ForwardPath").val(); //
		$Status = $("#status").val(); //
		$Codigo_Seguridad = $("#CodAut").val(); // 
		$Titular_Tarjeta = $("#titular").val(); //
		$familia_c = <?=$_SESSION['clave'];?>;

	$("#titular").attr('disabled', 'disabled');
	$("#tarjeta").attr('disabled', 'disabled');
	$("#TipoTarjeta").attr('disabled', 'disabled');
	$("#Fec_Mes").attr('disabled', 'disabled');
	$("#Fec_Axo").attr('disabled', 'disabled');
	$("#CodAut").attr('disabled', 'disabled');
	$("#pagar").attr('disabled', 'disabled'); 

	$("#titular").prop('disabled', true);
	$("#tarjeta").prop('disabled', true);
	$("#TipoTarjeta").prop('disabled', true);
	$("#Fec_Mes").prop('disabled', true);
	$("#Fec_Axo").prop('disabled', true);
	$("#CodAut").prop('disabled', true);
	$("#pagar").prop('disabled', true); 
	
	var id = $("#result").attr('href');
    var src = 'valida_send.php?Card='+$Card+				
    						  	'&Expires='+$Expires+		
    							'&CardType='+$CardType+
    							'&Cert3D='+$Cert3D+
    							'&Reference3D='+$Reference3D+
    							'&MerchantId='+$MerchantId+
    							'&MerchantName='+$MerchantName+
    							'&MerchantCity='+$MerchantCity+
    							'&ForwardPath='+$ForwardPath+
    							'&Total='+$total+
    							'&Codigo_Seguridad='+$Codigo_Seguridad+
    							'&Titular_Tarjeta='+$Titular_Tarjeta+
    							'&combopago=<?=$combopago;?>'+
    							'&familia='+$familia_c;
   			
	window.open('valida_send.php?Card='+$Card+'&Expires='+$Expires+'&CardType='+$CardType+'&Cert3D='+$Cert3D+'&Reference3D='+$Reference3D+'&MerchantId='+$MerchantId+'&MerchantName='+$MerchantName+'&MerchantCity='+$MerchantCity+'&ForwardPath='+$ForwardPath+'&Total='+$total+'&Codigo_Seguridad='+$Codigo_Seguridad+'&Titular_Tarjeta='+$Titular_Tarjeta+'&combopago=<?=$combopago;?>','','width=850,height=600,scrollbars=1');
			    	}else{
							alert("Seleccione su tipo de tarjeta.");
							 $("#TipoTarjeta").focus();
						}
			    }
		    }
    }
	    
}	


function cerrarVentana(){
	$("#loading").html("&nbsp;")
	setTimeout(testResponse, 1);
	setTimeout(close_, 3000);
}	

function close_(){
	window.close()
}

function testResponse (){

	$Reference3D = $("#Reference3D").val();
	$.ajax({
					dataType: "json",
					data: {Reference3D:$Reference3D
					},
					url:   'cierra_pago.php',
					type:  'post',
					beforeSend: function(){
						//Lo que se hace antes de enviar el formulario
					},
						success: function(respuesta){
						//lo que se si el destino devuelve algo
						$("#respuesta_proceso").html(respuesta.html);
						//setTimeout(function() {$('.ok,.ko').fadeOut('fast');}, 5000);
				  },
					error:	function(xhr,err){ 
								$("#respuesta_proceso").html(xhr.responseText);
			}
		});		
}

</script>