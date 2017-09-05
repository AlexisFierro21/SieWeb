<?php
include('../config.php');
mysql_query("SET NAMES 'utf8'");
$result_formato=mysql_query("select * from estado_cuenta_formatos_cartas",$link)or die(mysql_error());

$dobles = '"';

echo "
<link rel='stylesheet' type='text/css' href='style.css' media='screen' />
<style>
*{ font-family: sans-serif; margin: 0;}
		dl{ margin: 0px auto; width: 900px; }
		dt, dd{ padding: 3px; }
		dt{ background: #333333; color: white; border-bottom: 1px solid #141414; border-top: 1px solid #4E4E4E; cursor: pointer; }
		dd{  display: none; line-height: 1.6em; }
		dt.activo, dt:hover{ background: #424242; }

		dt:before{ content: '▸'; margin-right: 5px; }
		dt.activo:before{ content: '▾'; }
		
textarea {
    resize: none;
}
table{
	border:1px;
}
</style>
<dl>
<dt>Agregar Nuevo Formato</dt>
	  <dd>
	  	<table>
	  		<tbody>
	  			<tr>
	  				<td>
	  					<input type='text' id='titulo_nuevo' name='titulo_nuevo' />
	  					<br />
	  					<label style='color: black; font-size: 12px;'>Titulo del Formato</label>
	  					<br />
	  					<br />
	  				</td>
	  				<td>
	  					&nbsp;
	  				</td>
	  			</tr>
	  			<tr>
	  				<td>
	  					<textarea type='text' id='mensaje_entrada_nuevo' name='mensaje_entrada_nuevo' />
	  					<br />
	  					<label style='color: black; font-size: 12px;'>Mensaje de Entrada</label>
	  					<br />
	  					<br />
	  				</td>
	  				<td>
	  					&nbsp;
	  				</td>
	  			</tr>
	  			<tr>
	  				<td>
	  					<textarea type='text' id='mensaje_salida_nuevo' name='mensaje_salida_nuevo' />
	  					<br />
	  					<label style='color: black; font-size: 12px;'>Mensaje de Salida</label>
	  					<br />
	  					<br />
					</td>
					<td>
						&nbsp;
					</td>
				</tr>
				<tr>
					<td>
	  					<textarea type='text' id='remitente_nuevo' name='remitente_nuevo' />
	  					<br />
	  					<label style='color: black; font-size: 12px;'>Remitente</label>
	  					<br />
	  					<br />
	  				</td>
	  				<td>
	  					&nbsp;
	  				</td>
	  			</tr>
	  			<tr>
					<td colspan='2'>
	  					<button onclick='agregarFormato();' > Guardar </button>
	  				</td>
	  			</tr>
	  			<tr>
					<td colspan='2'>
	  					<div id='MensajeGuardado' name='MensajeGuardado' class='MensajeGuardado'></div>
	  				</td>
	  			</tr>
	  		</tbody>	
	  	</table>
	   </dd>		
";

echo "<dt>Editar Formato</dt>
	  <dd>";
	  
	  $formatos = "";
echo "	  Formato a Editar:&nbsp;&nbsp;<select name='seleccionaEditar' id='seleccionaEditar' class='seleccionaEditar' onchange='seleccionaEditar();'>";
						echo '<option value=""></option>';
							
								while($row_familias_edit = mysql_fetch_array($result_formato))
									{
	 									echo "<option value='".$row_familias_edit["id"]."'>".$row_familias_edit["nombre"]."</option>";
	 											
	 									$formatos.= "<option value='".$row_familias_edit["id"]."'>".$row_familias_edit["nombre"]."</option>";
									}
							
						echo '</select>
						<br>
						
						<div id="formato_editando" name="formato_editando" ><p style="font-size: 13px;" >Seleccione un formato a editar</p></div>
			</dd>		';

 echo "	<dd>
 			<dt>Seleccionar Formato</dt>
 				<dd>	
 					<p>Seleccione el tipo de formato que desea utilizar</p>
 						<select name='formato' id='formato' class='formato' onchange='preview();'>";
						echo '<option value=""></option>';
							
								echo $formatos;
								
						echo '</select>
									<div id="Preview" name="Preview" class="Preview"></div>
									
			</dd>';


echo "</dl>
<script>
$('dl dd').hide();
       $('dl dt').click(function(){
          if ($(this).hasClass('activo')) {
               $(this).removeClass('activo');
               $(this).next().slideUp();
          } else {
               $('dl dt').removeClass('activo');
               $(this).addClass('activo');
               $('dl dd').slideUp();
               $(this).next().slideDown();
          }
       });

</script>
";

	
?>