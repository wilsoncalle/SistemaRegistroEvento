<?php
// Configuración de la aplicación
session_start();

// Configuración de la base de datos
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'eventos_academicos');

// Configuración de la aplicación
define('APP_NAME', 'Sistema de Gestión de Eventos');
define('APP_URL', 'http://localhost/SistemaGestionEventos');

// Configuración de errores
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Configuración de zona horaria
date_default_timezone_set('America/Lima'); 