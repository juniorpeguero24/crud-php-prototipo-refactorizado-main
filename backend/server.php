<?php
/**
 * DEBUG MODE
 */
ini_set('display_errors', 1); // Habilita la visualización de errores en pantalla
error_reporting(E_ALL); // Muestra todos los tipos de errores

header("Access-Control-Allow-Origin: *"); // Permite peticiones desde cualquier origen (CORS)
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS"); // Métodos HTTP permitidos
header("Access-Control-Allow-Headers: Content-Type"); // Permite el encabezado Content-Type

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') { // Si la petición es de tipo OPTIONS (preflight CORS)
    http_response_code(200); // Responde con 200 OK
    exit(); // Finaliza la ejecución
}

// Analizar la URL para determinar el módulo
$requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH); // Obtiene la ruta de la URL solicitada
$scriptName = dirname($_SERVER['SCRIPT_NAME']); // Obtiene el directorio del script actual
$basePath = rtrim($scriptName, '/'); // Elimina la barra final del path base
$relativeUri = '/' . ltrim(substr($requestUri, strlen($basePath)), '/'); // Obtiene la ruta relativa al script

// Obtener el primer segmento de la ruta (módulo)
$segments = explode('/', trim($relativeUri, '/')); // Divide la ruta en segmentos por "/"
$module = isset($segments[0]) && $segments[0] !== '' ? $segments[0] : 'students'; // Usa el primer segmento o 'students' por defecto

// Construir el nombre del archivo de rutas
$routesFile = __DIR__ . "/routes/{$module}Routes.php"; // Construye la ruta al archivo de rutas correspondiente

if (file_exists($routesFile)) { // Si el archivo de rutas existe
    require_once($routesFile); // Lo incluye para manejar la petición
} else {
    // Manejador 404
    http_response_code(404); // Devuelve código 404
    header('Content-Type: application/json'); // Especifica que la respuesta es JSON
    echo json_encode([
        'error' => 'Ruta no encontrada' // Mensaje de error
    ]);
    exit(); // Finaliza la ejecución
}
?>