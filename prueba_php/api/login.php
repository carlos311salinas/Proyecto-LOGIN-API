<?php

session_start();

header('Content-Type: application/json');

require_once '../app/config/database.php';

$email = $_POST['email'] ?? '';
$password = $_POST['password'] ?? '';

try {

    $stmt = $pdo->prepare("
    SELECT * FROM usuarios 
    WHERE email = ?
    LIMIT 1
    ");

    $stmt->execute([$email]);

    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {

        // VALIDAR PASSWORD

        if (
            !password_verify(
                $password,
                $user['password_hash']
            )
        ) {

            echo json_encode([
                'success' => false,
                'message' => 'Credenciales incorrectas'
            ]);

            exit;
        }


        // VALIDAR ESTADO

        if ($user['estado'] !== 'activo') {

            echo json_encode([
                'success' => false,
                'message' => 'Usuario inactivo. Contacte al administrador.'
            ]);

            exit;
        }


        // CREAR SESIÓN

        $_SESSION['user'] = [
            'id' => $user['id'],
            'nombre' => $user['nombre'],
            'rol' => $user['rol']
        ];


        echo json_encode([
            'success' => true
        ]);

    } else {

        echo json_encode([
            'success' => false,
            'message' => 'Credenciales incorrectas'
        ]);

    }

} catch (PDOException $e) {

    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);

}