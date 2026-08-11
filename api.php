<?php
// Atrapa cualquier error o warning antes de enviarlo
ob_start();
error_reporting(0);
ini_set('display_errors', '0');

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: Content-Type, X-Admin-User, X-Admin-Password');
header('Access-Control-Allow-Methods: GET, POST, DELETE, OPTIONS');
header('Content-Type: application/json; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    ob_end_clean();
    http_response_code(200);
    exit;
}

// Función auxiliar para responder en JSON garantizado
function responderJSON($data) {
    ob_clean(); // Borra cualquier warning o espacio en blanco previo
    echo json_encode($data, JSON_UNESCAPED_UNICODE);
    exit;
}

// Credenciales MySQL (Ajusta con tus datos)
$host = getenv('MYSQLHOST') ?: 'localhost';
$db   = getenv('MYSQLDATABASE') ?: 'tu_base_de_datos';
$user = getenv('MYSQLUSER') ?: 'root';
$pass = getenv('MYSQLPASSWORD') ?: '';
$port = getenv('MYSQLPORT') ?: '3306';

try {
    $pdo = new PDO("mysql:host=$host;port=$port;dbname=$db;charset=utf8mb4", $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]);
} catch (PDOException $e) {
    responderJSON([
        'success' => false,
        'message' => 'Fallo de conexión MySQL: ' . $e->getMessage()
    ]);
}

function esAdmin() {
    // 1. CORRECCIÓN: Leemos directamente desde $_SERVER (Universal para Nginx y Apache)
    $authUser = $_SERVER['HTTP_X_ADMIN_USER'] ?? '';
    $authPass = $_SERVER['HTTP_X_ADMIN_PASSWORD'] ?? '';
    
    // Fallback seguro por si se está usando un servidor Apache muy estricto
    if (empty($authUser) && empty($authPass) && function_exists('getallheaders')) {
        $headers = array_change_key_case(getallheaders(), CASE_LOWER);
        $authUser = $headers['x-admin-user'] ?? '';
        $authPass = $headers['x-admin-password'] ?? '';
    }

    return ($authUser === 'RTC' && $authPass === 'RTC1234');
}

$method = $_SERVER['REQUEST_METHOD'];

try {
    switch ($method) {
        case 'GET':
            $stmt = $pdo->query("SELECT * FROM productos ORDER BY id DESC");
            responderJSON([
                'success' => true,
                'data' => $stmt->fetchAll()
            ]);
            break;

        case 'POST':
            if (!esAdmin()) {
                responderJSON(['success' => false, 'message' => 'Acceso denegado: Credenciales de admin no válidas']);
            }

            $rawInput = file_get_contents('php://input');
            $input = json_decode($rawInput, true);

            if (!$input || empty($input['nombre']) || !isset($input['precio'])) {
                responderJSON(['success' => false, 'message' => 'Datos incompletos o JSON malformado']);
            }

            $stmt = $pdo->prepare("INSERT INTO productos (nombre, categoria, precio, descripcion, imagen_url) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([
                $input['nombre'],
                $input['categoria'] ?? 'General',
                $input['precio'],
                $input['descripcion'] ?? '',
                $input['imagen_url'] ?? ''
            ]);

            responderJSON(['success' => true, 'message' => 'Registro insertado con éxito']);
            break;

        case 'DELETE':
            if (!esAdmin()) {
                responderJSON(['success' => false, 'message' => 'Acceso denegado']);
            }

            $id = $_GET['id'] ?? null;
            if (!$id) {
                responderJSON(['success' => false, 'message' => 'ID no proporcionado']);
            }

            $stmt = $pdo->prepare("DELETE FROM productos WHERE id = ?");
            $stmt->execute([$id]);

            responderJSON(['success' => true, 'message' => 'Registro eliminado']);
            break;

        default:
            responderJSON(['success' => false, 'message' => 'Método no soportado']);
            break;
    }
// 2. CORRECCIÓN: Cambiado de Exception a Throwable para atrapar Errores Fatales
} catch (Throwable $e) {
    responderJSON([
        'success' => false,
        'message' => 'Error del servidor: ' . $e->getMessage()
    ]);
}
