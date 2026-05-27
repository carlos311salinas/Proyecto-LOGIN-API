$(document).ready(function () {

    // CARGAR USUARIOS AL INICIAR

    cargarUsuarios();


    // CREAR USUARIO

    $('#crearUsuarioForm').submit(function (e) {

        e.preventDefault();

        $.ajax({

            url: 'api/usuarios.php',

            method: 'POST',

            data: $(this).serialize(),

            dataType: 'json',

            success: function (response) {

                if (response.success) {

                    // CERRAR MODAL
                    $('#crearUsuarioModal').modal('hide');

                    // LIMPIAR FORMULARIO
                    $('#crearUsuarioForm')[0].reset();

                    // RECARGAR TABLA
                    cargarUsuarios();

                    alert('Usuario creado correctamente');

                } else {

                    alert(response.message);

                }

            },

            error: function () {

                alert('Error al crear usuario');

            }

        });

    });


    // ABRIR MODAL EDITAR

    $(document).on('click', '.editarBtn', function () {

        $('#edit_id').val($(this).data('id'));

        $('#edit_nombre').val($(this).data('nombre'));

        $('#edit_email').val($(this).data('email'));

        $('#edit_telefono').val($(this).data('telefono'));

        $('#edit_rol').val($(this).data('rol'));

        $('#editarUsuarioModal').modal('show');

    });


    // EDITAR USUARIO

    $('#editarUsuarioForm').submit(function (e) {

        e.preventDefault();

        $.ajax({

            url: 'api/usuarios.php',

            method: 'PUT',

            data: $(this).serialize(),

            dataType: 'json',

            success: function (response) {

                if (response.success) {

                    // CERRAR MODAL
                    $('#editarUsuarioModal').modal('hide');

                    // RECARGAR TABLA
                    cargarUsuarios();

                    alert('Usuario actualizado correctamente');

                } else {

                    alert(response.message);

                }

            },

            error: function () {

                alert('Error al actualizar usuario');

            }

        });

    });


    // ABRIR MODAL PASSWORD

    $(document).on('click', '.cambiarPasswordBtn', function () {

        const id = $(this).data('id');

        $('#password_user_id').val(id);

        $('#passwordModal').modal('show');

    });

    // CAMBIAR PASSWORD

    $('#passwordForm').submit(function (e) {

        e.preventDefault();

        const password = $('#new_password').val();

        // VALIDACIÓN COMPLEJIDAD

        const regex =
            /^(?=.*[A-Z])(?=.*[\W_]).{8,}$/;

        if (!regex.test(password)) {

            alert(
                'La contraseña debe tener mínimo 8 caracteres, una mayúscula y un carácter especial'
            );

            return;

        }

        $.ajax({

            url: 'api/usuarios.php',

            method: 'PATCH',

            data: $(this).serialize(),

            dataType: 'json',

            success: function (response) {

                if (response.success) {

                    $('#passwordModal').modal('hide');

                    $('#passwordForm')[0].reset();

                    alert('Contraseña actualizada');

                } else {

                    alert(response.message);

                }

            },

            error: function () {

                alert('Error al cambiar contraseña');

            }

        });

    });

    // DESACTIVAR USUARIO

    $(document).on('click', '.desactivarBtn', function () {

        if (!confirm('¿Desea desactivar este usuario?')) {

            return;

        }

        const id = $(this).data('id');

        $.ajax({

            url: 'api/usuarios.php',

            method: 'DELETE',

            data: {
                id: id
            },

            dataType: 'json',

            success: function (response) {

                if (response.success) {

                    cargarUsuarios();

                    alert('Usuario desactivado');

                } else {

                    alert(response.message);

                }

            },

            error: function () {

                alert('Error al desactivar usuario');

            }

        });

    });

});

// ACTIVAR USUARIO

$(document).on('click', '.activarBtn', function () {

    if (!confirm('¿Desea activar este usuario?')) {

        return;

    }

    const id = $(this).data('id');

    $.ajax({

        url: 'api/usuarios.php',

        method: 'PATCH',

        data: {
            action: 'activar',
            id: id
        },

        dataType: 'json',

        success: function (response) {

            if (response.success) {

                cargarUsuarios();

                alert('Usuario activado');

            } else {

                alert(response.message);

            }

        },

        error: function () {

            alert('Error al activar usuario');

        }

    });

});


// FUNCIÓN CARGAR USUARIOS

function cargarUsuarios() {

    $.ajax({

        url: 'api/usuarios.php',

        method: 'GET',

        dataType: 'json',

        success: function (response) {

            let html = '';

            response.usuarios.forEach(usuario => {

                html += `
                
                    <tr>

                        <td>${usuario.id}</td>

                        <td>${usuario.nombre}</td>

                        <td>${usuario.email}</td>

                        <td>${usuario.telefono}</td>

                        <td>${usuario.rol}</td>

                        <td>${usuario.estado}</td>

                        <td>

                            ${usuarioRol === 'admin' ? `

                                <button 
                                    class="btn btn-warning btn-sm editarBtn"

                                    data-id="${usuario.id}"
                                    data-nombre="${usuario.nombre}"
                                    data-email="${usuario.email}"
                                    data-telefono="${usuario.telefono}"
                                    data-rol="${usuario.rol}">
                                    Editar
                                </button>

                                <button 
                                    class="btn btn-info btn-sm cambiarPasswordBtn"
                                    data-id="${usuario.id}">
                                    Contraseña
                                </button>

                                ${usuario.estado === 'activo' ? `

                                    <button 
                                        class="btn btn-danger btn-sm desactivarBtn"
                                        data-id="${usuario.id}">
                                        Desactivar
                                    </button>`: `
                                
                                    <button 
                                        class="btn btn-success btn-sm activarBtn"
                                        data-id="${usuario.id}">
                                        Activar
                                    </button>`
                        }
                                `: `<span class="text-muted">
                                    Sin permisos
                                    </span>`
                    }

</td>
                    </tr>
                `;
            });
            $('#tablaUsuarios').html(html);
        },

        error: function () {

            alert('Error al cargar usuarios');

        }

    });

}


// CONSULTAR CLIMA

$('#climaForm').submit(function (e) {

    e.preventDefault();

    const ciudad = $('#ciudad').val();


    $.ajax({

        url: 'api/clima.php',

        method: 'POST',

        data: {
            ciudad: ciudad
        },

        dataType: 'json',

        success: function (response) {

            if (response.success) {

                $('#resultadoClima').html(`

                    <div class="card">

                        <div class="card-body">

                            <h3>
                                ${response.ciudad}
                            </h3>
                                ${response.cache
                        ? '<span class="badge bg-warning">CACHE</span>'
                        : ''
                    }

                            <hr>

                            <p>
                                Temperatura:
                                <strong>
                                    ${response.temperatura} °C
                                </strong>
                            </p>

                            <p>
                                Descripción:
                                <strong>
                                    ${response.descripcion}
                                </strong>
                            </p>

                            <p>
                                Humedad:
                                <strong>
                                    ${response.humedad}%
                                </strong>
                            </p>

                            <p>
                                Viento:
                                <strong>
                                    ${response.viento} km/h
                                </strong>
                            </p>
                        </div>
                    </div>
                `);

            } else {
                $('#resultadoClima').html(`

                        <div class="alert alert-danger">

                            ${response.message}

                        </div>

                    `);
            }

        },

        error: function (xhr) {

            console.log(xhr.responseText);

            alert('Error al consultar clima');

        }

    });

});