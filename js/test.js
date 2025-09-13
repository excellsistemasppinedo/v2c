$(function(){

    listado_datos();
    actualizarBotones();

    $("#btnNuevo").off().on("click", function(){
        $("#id").val(0);
        limpieza_campos();
        $("#modalVehiculo").modal("show");
    })

    $("#btnFiltrar").off().on("click", function(){
        listado_datos();
        actualizarBotones();
    });

    $('#btnGuardar').click(function(){
        guardar_datos();
    });

    $("#selectRegistros").off().on("change", function () {
        listado_datos(1);
        actualizarBotones();
    });
});

function guardar_datos(){

    let validar = validar_campos();
    let swError = validar.error;
    let msjError = validar.mensajeerror;

    if(swError == 1){
        Swal.fire({
            icon: 'error',
            html: msjError,
            title: 'Problemas',
            showConfirmButton: false,
            timer: 1500
        });
        return false;
    }

    let id = $('#id').val();

    let placa = $('#placa').val();
    let marca = $('#marca').val();
    let modelo = $('#modelo').val();
    let anio = $('#anio').val();

    let nombre = $('#nombre').val();
    let apellido = $('#apellido').val();
    let documento = $('#documento').val();
    let correo = $('#correo').val();
    let telefono = $('#telefono').val();

    let accion = 'guardar_datos';
    let parametros = {
        'accion': accion,
        'id': id,
        'placa': placa,
        'marca': marca,
        'modelo': modelo,
        'anio': anio,
        'nombre': nombre,
        'apellido': apellido,
        'documento': documento,
        'correo': correo,
        'telefono': telefono
    };

    $.ajax({
        url: 'pages/controlador.php',
        type: 'POST',
        data: parametros,
        success: function(data) {
            let resultado = JSON.parse(data);
            if(resultado.resultado == 100){
                Swal.fire({
                    icon: 'success',
                    text: 'Datos Guardados correctamente',
                    title: 'Excelente',
                    showConfirmButton: false,
                    timer: 1500
                })

                $('#modalVehiculo').modal('hide');
                listado_datos();
            }
        },   
        error: function(error) {
                Swal.fire({
                    icon: 'error',
                    text: 'Comuniquese con TI',
                    title: 'Problemas',
                    showConfirmButton: false,
                    timer: 1500
                });
        }
    })

}

function listado_datos(paginaActual = 1){
    let filtroPlaca = $('#filtroPlaca').val();
    let filtroApellidos = $('#filtroApellidos').val();

    let filtrolimit = $('#selectRegistros').val();
    let offset = (paginaActual - 1) * filtrolimit;

    let accion = 'listado_datos';
    let parametros = {
        'accion': accion,
        'filtroPlaca': filtroPlaca,
        'filtroApellidos': filtroApellidos,
        'filtrolimit': filtrolimit,
        'offset': offset,
        'paginaActual': paginaActual
    };

    $.ajax({
        url: 'pages/controlador.php',
        type: 'POST',
        data: parametros,
        success: function(data) {
            let resultado = JSON.parse(data);
            $('#tabla_principal').html(resultado);
            seccion_datos();
        },   
        error: function(error) {
                Swal.fire({
                    icon: 'error',
                    text: 'Comuniquese con TI',
                    title: 'Problemas',
                    showConfirmButton: false,
                    timer: 1500
                });
        }
    });

}

function seccion_datos(){
    let botones = document.querySelectorAll('table tr td button');
    for (let i = 0; i < botones.length; i++) {
        if(botones[i].id == 'btnEditar'){
            botones[i].addEventListener('click', modificar_registro, false);
        }else if(botones[i].id == 'btnEliminar'){
            botones[i].addEventListener('click', eliminar_registro, false);
        }
    }

}

function modificar_registro(e){
    let id = $(e.currentTarget).closest('tr').data('id');

    let accion = 'modificar_registro';
    let parametros = {
        'accion': accion,
        'id': id
    };

    $.ajax({
        url: 'pages/controlador.php',
        type: 'POST',
        data: parametros,
        success: function(data) {
            let resultado = JSON.parse(data);
            $("#modalCuerpo").html(resultado);
            $("#modalVehiculo").modal("show");
        },   
        error: function(error) {
                Swal.fire({
                    icon: 'error',
                    text: 'Comuniquese con TI',
                    title: 'Problemas',
                    showConfirmButton: false,
                    timer: 1500
                });
        }
        
    })
}

function eliminar_registro(e){
    let id = $(e.currentTarget).closest('tr').data('id');    
    swal.fire({
        title: 'Estas seguro?',
        text: "No podras revertir esta accion!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Si, Eliminar!',
        cancelButtonText: 'No, cancelar!',
        reverseButtons: true
    }).then((result) => {
        let accion = 'eliminar_registro';
        let parametros = {
            'accion': accion,
            'id': id
        };

        $.ajax({
            url: 'pages/controlador.php',
            type: 'POST',
            data: parametros,
            success: function(data) {
                let resultado = JSON.parse(data);
                if(resultado.resultado == 100){
                    listado_datos();
                }},   
            error: function(error) {
                    Swal.fire({
                        icon: 'error',
                        text: 'Comuniquese con TI',
                        title: 'Problemas',
                        showConfirmButton: false,
                        timer: 1500
                    });
            }
        })        
    })









    
}

function validar_campos(){

    let placa = $('#placa').val();
    let apellidos = $('#apellidos').val();
    let nombres = $('#nombres').val();
    let telefono = $('#telefono').val();
    let modelo = $('#modelo').val();    
    let correo = $('#correo').val();        
    let error = 0;
    let mensajeerror = '';

    if(placa == '' || apellidos == '' || nombres == '' || telefono == '' || modelo == ''){
        error = 1;
        mensajeerror = 'Todos los campos son obligatorios <br>';
    }

    const regex_email = /^[^\s@]+@[^\s@]+\.[^\s@]+$/; //Correo
    const regex_phone = /^[0-9]{7,15}$/;  // Telefono
    const regex_placa = /^[A-Z0-9]{1,3}-[0-9]{3,4}$/; //Placa

    if(regex_email.test(correo) == false){
        error = 1;
        mensajeerror += 'El correo no es valido <br>';
    }

    if(regex_phone.test(telefono) == false){
        error = 1;
        mensajeerror += 'El telefono no es valido <br>';
    }

    if(regex_placa.test(placa) == false){
        error = 1;
        mensajeerror += 'La placa no es valida <br>';
    }

    return {'error': error, 'mensajeerror': mensajeerror};

}

function limpieza_campos(){

    $('#placa').val('');
    $('#marca').val(0);
    $('#modelo').val('');        
    $('#anio').val(0);

    $('#apellido').val('');
    $('#nombre').val('');

    $('#telefono').val('');
    $('#documento').val('');
    $('#correo').val('');
}

function paginacion(){
    $(document).on("click", ".page-link[data-page]", function (e) {
        e.preventDefault();
        let page = $(this).data("page");
        // let limit = $("#selectRegistros").val();
        // cargarTabla(page, limit);
        listado_datos(page);
    });

    // Cambio en el selector de registros
    $(document).on("change", "#selectRegistros", function () {
        // let limit = $(this).val();
        // cargarTabla(1, limit); // siempre regresa a página 1
        listado_datos(1);
    });    
}

function cargarTabla(pagina, limite) {
    $.ajax({
        url: "controlador.php",
        type: "POST",
        data: { page: pagina, limit: limite },
        success: function (respuesta) {
            $("#tabla_principal").html(respuesta);
        },
        error: function () {
            alert("❌ Error al cargar los datos");
        }
    });
}

function actualizarBotones(totalRegistros, paginaActual, filtrolimit) {
    let totalPaginas = Math.ceil(totalRegistros / filtrolimit);

    if (paginaActual <= 1) {
        $('#btnAnterior').prop('disabled', true);
    } else {
        $('#btnAnterior').prop('disabled', false);
    }

    if (paginaActual >= totalPaginas) {
        $('#btnSiguiente').prop('disabled', true);
    } else {
        $('#btnSiguiente').prop('disabled', false);
    }
}
