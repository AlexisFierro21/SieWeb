var posicionCampo = 1;

function agregarMedicamento() {

    nuevaFila = document.getElementById("tablaMedicamentoAlumnos").insertRow(-1);
    nuevaFila.id = posicionCampo;

    nuevaCelda = nuevaFila.insertCell(-1);
    nuevaCelda.innerHTML = "<tr><td><input type='text' size='15' name='medicamento[" + posicionCampo + "]' id='medicamento[" + posicionCampo + "]' ></td>";

    nuevaCelda = nuevaFila.insertCell(-1);
    nuevaCelda.innerHTML = "<td><input type='text' size='10' name='cantidad[" + posicionCampo + "]' id='cantidad[" + posicionCampo + "]' ></td>";

    nuevaCelda = nuevaFila.insertCell(-1);
    nuevaCelda.innerHTML = "<td><input type='text' size='10' name='dosis[" + posicionCampo + "]' id='dosis[" + posicionCampo + "]' ></td>";
	
	nuevaCelda = nuevaFila.insertCell(-1);
    nuevaCelda.innerHTML = "<td><input type='text' size='15' name='via_de_administracion[" + posicionCampo + "]' id='via_de_administracion[" + posicionCampo + "]' ></td>";
	
	nuevaCelda = nuevaFila.insertCell(-1);
	nuevaCelda.innerHTML = "<td><input type='text' size='10' name='frecuencia["+posicionCampo +"]' id='frecuencia["+posicionCampo +"]' ></td>";

    nuevaCelda = nuevaFila.insertCell(-1);
    nuevaCelda.innerHTML = "<td><input type='button' value='-' onclick='eliminarMedicamento(this)'></td></tr>";

		
	var varjs="'.$collection.'";
    posicionCampo++;
	$(function() {
    var availableTags = [
	varjs
    ];
	console.log(availableTags);
    $( "[id*=medicamento]" ).autocomplete({
      source: availableTags
    });
  });

}


function eliminarMedicamento(obj) {

    var oTr = obj;

    while(oTr.nodeName.toLowerCase() != 'tr') {

        oTr=oTr.parentNode;

    }

    var root = oTr.parentNode;

    root.removeChild(oTr);

}
