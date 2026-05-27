# Sistema de Gestión de Usuarios y Clima

Esto es una aplicación web desarrollada en PHP puro utilizando arquitectura MVC básica, MySQL, jQuery AJAX y Bootstrap 5.

# Se compone de:

- Login de usuarios
- Gestión de usuarios (CRUD)
- Roles de administrador y usuario
- Activación / desactivación de usuarios
- Cambio de contraseña seguro
- Consumo API OpenWeatherMap
- Consulta de clima en tiempo real
- AJAX con jQuery
- Sesiones seguras
- Prepared Statements
- Variables de entorno con Dotenv


# Tecnologías utilizadas:

- PHP 8
- MySQL
- jQuery
- Bootstrap 5
- Composer
- Dotenv


# Instalación:

# 1. Instalar dependencias:

Abrimos la consola cmd o bash dentro del proyecto y descargamos las dependencias
con el siguiente comando:

composer install

# 2. Crear base de datos:

Copiar el contenido del archivo esquema.sql para pegarlo en MySQL.

# 3. Configurar variables entorno

Copiar el contenido de .env.example para pegarlo en .env
y configurar
.env
DB_HOST=localhost
DB_NAME=pruebas_php
DB_USER=root
DB_PASS=
WEATHER_API_KEY=TU_API_KEY

# Ejecutar proyecto

Mover proyecto a:
C:\xampp\htdocs\

Iniciar Apache y MySQL

Abrir http://localhost/prueba_php/app/views/login.php

# Usuarios de prueba: Administrador

Email: carlos311salinas@gmail.com
Password: Manizales26*

# Seguridad implementada

- password_hash()
- password_verify()
- Prepared Statements
- Validación de sesiones
- Control de roles
- Protección básica contra SQL Injection
