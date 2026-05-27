<?php

require_once '../app/config/session.php';

header('Content-Type: application/json');

require_once '../app/config/database.php';



// VALIDAR SESIÓN

if (!isset($_SESSION['user'])) {

    echo json_encode([
        'success' => false,
        'message' => 'No autorizado'
    ]);

    exit;
}


// OBTENER MÉTODO HTTP

$method = $_SERVER['REQUEST_METHOD'];

// VALIDAR ROL ADMIN
if ($_SESSION['user']['rol'] !== 'admin') {

    // PERMITIR SOLO GET

    if ($method !== 'GET') {

        echo json_encode([
            'success' => false,
            'message' => 'Acceso denegado'
        ]);

        exit;
    }
}


try {

    // LISTAR USUARIOS

    if ($method === 'GET') {

        $stmt = $pdo->prepare("
            SELECT 
                id,
                nombre,
                email,
                telefono,
                rol,
                estado
            FROM usuarios
        ");

        $stmt->execute();

        $usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);

        echo json_encode([
            'success' => true,
            'usuarios' => $usuarios
        ]);

        exit;
    }


    // CREAR USUARIO

    if ($method === 'POST') {

        $nombre = trim($_POST['nombre']);
        $email = trim($_POST['email']);
        $telefono = trim($_POST['telefono']);
        $password = $_POST['password'];
        $rol = $_POST['rol'];


        // VALIDAR EMAIL EXISTENTE

        $stmt = $pdo->prepare("
            SELECT id 
            FROM usuarios 
            WHERE email = ?
        ");

        $stmt->execute([$email]);

        if ($stmt->fetch()) {

            echo json_encode([
                'success' => false,
                'message' => 'El email ya existe'
            ]);

            exit;
        }


        // HASH PASSWORD

        $hash = password_hash($password, PASSWORD_DEFAULT);


        // INSERTAR USUARIO

        $stmt = $pdo->prepare("
            INSERT INTO usuarios (
                nombre,
                email,
                telefono,
                password_hash,
                rol
            )
            VALUES (?, ?, ?, ?, ?)
        ");

        $stmt->execute([
            $nombre,
            $email,
            $telefono,
            $hash,
            $rol
        ]);


        echo json_encode([
            'success' => true,
            'message' => 'Usuario creado correctamente'
        ]);

        exit;
    }


    // EDITAR USUARIO

    if ($method === 'PUT') {

        parse_str(file_get_contents("php://input"), $_PUT);

        $id = $_PUT['id'];
        $nombre = trim($_PUT['nombre']);
        $email = trim($_PUT['email']);
        $telefono = trim($_PUT['telefono']);
        $rol = $_PUT['rol'];


        // VALIDAR EMAIL DUPLICADO

        $stmt = $pdo->prepare("
            SELECT id 
            FROM usuarios 
            WHERE email = ?
            AND id != ?
        ");

        $stmt->execute([$email, $id]);

        if ($stmt->fetch()) {

            echo json_encode([
                'success' => false,
                'message' => 'El email ya pertenece a otro usuario'
            ]);

            exit;
        }


        // ACTUALIZAR USUARIO

        $stmt = $pdo->prepare("
            UPDATE usuarios
            SET
                nombre = ?,
                email = ?,
                telefono = ?,
                rol = ?
            WHERE id = ?
        ");

        $stmt->execute([
            $nombre,
            $email,
            $telefono,
            $rol,
            $id
        ]);


        echo json_encode([
            'success' => true,
            'message' => 'Usuario actualizado correctamente'
        ]);

        exit;
    }


    // DESACTIVAR USUARIO

    if ($method === 'DELETE') {

        parse_str(file_get_contents("php://input"), $_DELETE);

        $id = $_DELETE['id'];


        $stmt = $pdo->prepare("
            UPDATE usuarios
            SET estado = 'inactivo'
            WHERE id = ?
        ");

        $stmt->execute([$id]);


        echo json_encode([
            'success' => true,
            'message' => 'Usuario desactivado'
        ]);

        exit;
    }


    // PATCH CAMBIAR PASSWORD Y ACTIVAR USUARIO

    if ($method === 'PATCH') {

        parse_str(file_get_contents("php://input"), $_PATCH);


        // ACTIVAR USUARIO

        if (
            isset($_PATCH['action'])
            &&
            $_PATCH['action'] === 'activar'
        ) {

            $id = $_PATCH['id'];

            $stmt = $pdo->prepare("
            UPDATE usuarios
            SET estado = 'activo'
            WHERE id = ?
        ");

            $stmt->execute([$id]);

            echo json_encode([
                'success' => true,
                'message' => 'Usuario activado'
            ]);

            exit;
        }


        // CAMBIAR PASSWORD

        $id = $_PATCH['id'];

        $password = $_PATCH['password'];


        // HASH PASSWORD

        $hash = password_hash(
            $password,
            PASSWORD_DEFAULT
        );


        // ACTUALIZAR PASSWORD

        $stmt = $pdo->prepare("
        UPDATE usuarios
        SET password_hash = ?
        WHERE id = ?
    ");

        $stmt->execute([
            $hash,
            $id
        ]);


        echo json_encode([
            'success' => true,
            'message' => 'Contraseña actualizada'
        ]);

        exit;
    }


    // MÉTODO NO PERMITIDO

    echo json_encode([
        'success' => false,
        'message' => 'Método no permitido'
    ]);

} catch (PDOException $e) {

    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);

}