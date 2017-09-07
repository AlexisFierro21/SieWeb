<?php
include('index.html');
?>
<div class="recuperar">
	<p><span>¡Recuperar mi contraseña!</span></p>
  <span>Módulo Web del Sistema de Información Escolar</span>
    <form id="login">
      <div class="form-group">
        <input type="text" class="form-control" id="email" placeholder="Ejm. P5****" required="true" readonly="true">
      </div>
      <div class="separador"></div>
      <div class="form-group">
      <span>Por favor, escribe el correo electronico que tienes registrado en la cuenta</span>
        <input type="text" class="form-control" id="pass" placeholder="Ejm@ejm.com" required="true">
      </div>
      <div class="form-group abajo">
      <label class="radio">
      <input type="radio" name="options" id="option2" autocomplete="off">
      <a href="">terminos y condiciones</a>          
        </label>
        <button type="submit" class="btn btn-primary">Recuperar</button>
        
      </div>	
    </form>
</div>
<style>
	.container{
		display: none;
	}
</style>
