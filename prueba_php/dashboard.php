<?php

require_once 'vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);

$dotenv->load();

require_once 'app/config/session.php';

?>

<!DOCTYPE html>
<html lang="es">

<head>

    <meta charset="UTF-8">

    <title>Dashboard</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

</head>

<body class="bg-light">

    <div class="container mt-5">
        <div class="d-flex justify-content-between align-items-center mb-4">

            <div>

                <h2>
                    Gestión de Usuarios
                </h2>

                <p>
                    Bienvenido
                    <strong>
                        <?= htmlspecialchars($_SESSION['user']['nombre']) ?>
                    </strong>
                </p>

            </div>

            <div>

                <a href="logout.php" class="btn btn-danger me-2">
                    Logout
                </a>

                <?php if ($_SESSION['user']['rol'] === 'admin'): ?>
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#crearUsuarioModal">
                        Crear Usuario
                    </button>
                <?php endif; ?>
            </div>

        </div>
        <div class="card shadow">

            <div class="card-body">

                <table class="table table-bordered">

                    <thead>

                        <tr>

                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Email</th>
                            <th>Teléfono</th>
                            <th>Rol</th>
                            <th>Estado</th>
                            <th>Acciones</th>

                        </tr>

                    </thead>

                    <tbody id="tablaUsuarios">

                    </tbody>

                </table>

            </div>

        </div>

    </div>

    <!-- CREAR USUARIO -->

    <div class="modal fade" id="crearUsuarioModal">

        <div class="modal-dialog">

            <div class="modal-content">

                <div class="modal-header">

                    <h5 class="modal-title">
                        Crear Usuario
                    </h5>

                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>

                </div>

                <div class="modal-body">

                    <form id="crearUsuarioForm">

                        <div class="mb-3">

                            <label>Nombre</label>

                            <input type="text" name="nombre" class="form-control" required>

                        </div>

                        <div class="mb-3">

                            <label>Email</label>

                            <input type="email" name="email" class="form-control" required>

                        </div>

                        <div class="mb-3">

                            <label>Teléfono</label>

                            <input type="text" name="telefono" class="form-control">

                        </div>

                        <div class="mb-3">

                            <label>Contraseña</label>

                            <input type="password" name="password" class="form-control" required>

                        </div>

                        <div class="mb-3">

                            <label>Rol</label>

                            <select name="rol" class="form-select">

                                <option value="usuario">
                                    Usuario
                                </option>

                                <option value="admin">
                                    Admin
                                </option>

                            </select>

                        </div>

                        <button class="btn btn-success w-100">
                            Guardar
                        </button>

                    </form>

                </div>

            </div>

        </div>

    </div>

    <!-- EDITAR -->

    <div class="modal fade" id="editarUsuarioModal">

        <div class="modal-dialog">

            <div class="modal-content">

                <div class="modal-header">

                    <h5 class="modal-title">
                        Editar Usuario
                    </h5>

                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>

                </div>

                <div class="modal-body">

                    <form id="editarUsuarioForm">

                        <input type="hidden" name="id" id="edit_id">

                        <div class="mb-3">

                            <label>Nombre</label>

                            <input type="text" name="nombre" id="edit_nombre" class="form-control" required>

                        </div>

                        <div class="mb-3">

                            <label>Email</label>

                            <input type="email" name="email" id="edit_email" class="form-control" required>

                        </div>

                        <div class="mb-3">

                            <label>Teléfono</label>

                            <input type="text" name="telefono" id="edit_telefono" class="form-control">

                        </div>

                        <div class="mb-3">

                            <label>Rol</label>

                            <select name="rol" id="edit_rol" class="form-select">

                                <option value="usuario">
                                    Usuario
                                </option>

                                <option value="admin">
                                    Admin
                                </option>

                            </select>

                        </div>

                        <button class="btn btn-primary w-100">
                            Actualizar
                        </button>

                    </form>

                </div>

            </div>

        </div>

    </div>

    <!-- CAMBIAR PASSWORD -->

    <div class="modal fade" id="passwordModal">

        <div class="modal-dialog">

            <div class="modal-content">

                <div class="modal-header">

                    <h5 class="modal-title">
                        Cambiar Contraseña
                    </h5>

                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>

                </div>

                <div class="modal-body">

                    <form id="passwordForm">

                        <input type="hidden" id="password_user_id" name="id">

                        <div class="mb-3">

                            <label>Nueva Contraseña</label>

                            <input type="password" name="password" id="new_password" class="form-control" required>

                            <small class="text-muted">
                                Mínimo 8 caracteres, mayúscula y carácter especial
                            </small>

                        </div>

                        <button class="btn btn-primary w-100">
                            Cambiar Contraseña
                        </button>

                    </form>

                </div>

            </div>

        </div>

    </div>


    <!-- CONSULTAR CLIMA -->
    <div class="card mt-5">

        <div class="card-header">

            <h4>
                Consultar Clima
            </h4>

        </div>

        <div class="card-body">

            <form id="climaForm">

                <div class="row">

                    <div class="col-md-10">

                        <input type="text" id="ciudad" name="ciudad" class="form-control" placeholder="Ejemplo: Bogotá"
                            required>

                    </div>

                    <div class="col-md-2">

                        <button class="btn btn-primary w-100">

                            Consultar

                        </button>

                    </div>

                </div>

            </form>

            <!-- RESULTADO CLIMA -->

            <div id="resultadoClima" class="mt-4"></div>

        </div>

    </div>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script>

        const usuarioRol = "<?= $_SESSION['user']['rol'] ?>";

    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script src="assets/js/dashboard.js"></script>

</body>

</html>