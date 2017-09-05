$(document).ready(function() {
    //A cada select lo identificamos con una variable
    $seccion = $('#seccion');
    $grado = $('#grado');
    $grupo = $('#grupo');
	$alumno = $('#alumno');

    //Cargamos secciones,equipos y jugadores cuando se carga la pagina
    cargarSeccion();
    cargarGrado('');
    cargarGrupo('');

    //Cuando en el select de secciones ocurre un cambio actualizamos el select de equipos
    $seccion.change(function(event) {
        cargarGrado($seccion.val());
        setTimeout(function(){cargarGrupo($equipo.val())},500);
    });

    //Cuando en el select de equipos ocurre un cambio actualizamos el select de jugadores
    $equipo.change(function(event) {
        cargarGrupo($equipo.val());
    });

});


//Funcion que carga los secciones en el SELECT con id seccion
function cargarsecciones(){
    //Enviamos una peticion con AJAX
    $.get('servidor/servidor.php',{getSeccion:true}, function(data) {
        //Fomamos un objeto JSON para manejarlo mas facil con MUSTACHEJS
        datos = '{"seccion":'+data+"}";
        //Creamos un template con el JSON
        var template = '{{#seccion}}<option>{{seccion}}</option>{{/seccion}}';
        var html = Mustache.to_html(template, $.parseJSON(datos));
        //Le asignamos el template al SELECT seccion
        $seccion.html(html);
    });
}

//Funcion que carga los equipos...recibe el nombre del seccion que se quiere
function cargarEquipos(seccion){
    $.get('servidor/servidor.php',{getGrado:true,Seccion:seccion}, function(data) {
        datos = '{"grado":'+data+"}";
        var template = '{{#grado}}<option value="{{grado}}">{{nombre}}</option>{{/grado}}';
        var html = Mustache.to_html(template, $.parseJSON(datos));
        $equipo.html(html);
    });
}

//Funcion que carga los jugadores...recibe de parametro el id del equipo
function cargarGrupo(grado){
    $.get('servidor/servidor.php',{getGrupo:true,Grado:grado}, function(data) {
        datos = '{"Grupo":'+data+"}";
        var template = '{{#grupo}}<option>{{grupo}}</option>{{/jugadores}}';
        var html = Mustache.to_html(template, $.parseJSON(datos));
        $grupo.html(html);
    });
}

function cargarAlumno(grupo){
    $.get('servidor/servidor.php',{getAlumno:true,Grupo:grupo}, function(data) {
        datos = '{"alumno":'+data+"}";
        var template = '{{#alumno}}<option>{{nombre}}</option>{{/alumno}}';
        var html = Mustache.to_html(template, $.parseJSON(datos));
        $alumno.html(html);
    });
}