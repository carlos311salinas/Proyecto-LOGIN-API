<?php

session_start();

$timeout = 1800; // 30 minutos


// VERIFICAR LOGIN

if (!isset($_SESSION['user'])) {

    header('Location: /prueba_php/app/views/login.php');
    exit;
}


// VERIFICAR EXPIRACIÓN

if (isset($_SESSION['last_activity'])) {

    if (
        time() - $_SESSION['last_activity']
        > $timeout
    ) {

        session_unset();

        session_destroy();

        header('Location: /prueba_php/app/views/login.php');

        exit;
    }
}


// ACTUALIZAR ÚLTIMA ACTIVIDAD

$_SESSION['last_activity'] = time();