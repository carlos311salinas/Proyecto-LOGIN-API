<!DOCTYPE html>
<html lang="es">

<head>

    <meta charset="UTF-8">

    <title>Login</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

</head>

<body class="bg-light">

    <div class="container">

        <div class="row justify-content-center mt-5">

            <div class="col-md-4">

                <div class="card shadow">

                    <div class="card-body">

                        <h3 class="text-center mb-4">
                            Iniciar Sesión
                        </h3>

                        <form id="loginForm">

                            <div class="mb-3">

                                <label>Email</label>

                                <input type="email" name="email" class="form-control" required>

                            </div>

                            <div class="mb-3">

                                <label>Contraseña</label>

                                <input type="password" name="password" class="form-control" required>

                            </div>

                            <button class="btn btn-primary w-100">
                                Ingresar
                            </button>

                        </form>

                        <div id="mensaje" class="mt-3"></div>

                    </div>

                </div>

            </div>

        </div>

    </div>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

    <script src="../../assets/js/login.js"></script>

</body>

</html>