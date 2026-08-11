<?php
// 1. Ocultar warnings/errores de PHP en el buffer para evitar corrupto en el JSON
error_reporting(0);
ini_set('display_errors', '0');

// 2. Encabezados HTTP estrictos
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: Content-Type, X-Admin-User, X-Admin-Password');
header('Access-Control-Allow-Methods: GET, POST, DELETE, OPTIONS');
header('Content-Type: application/json; charset=utf-8');

// Responder inmediatamente si es una petición PREFLIGHT (CORS)
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

// 3. Configuración de Base de Datos (Ajusta tus datos aquí si no usas variables de entorno)
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
    echo json_encode([
        'success' => false,
        'message' => 'Error de conexión MySQL: ' . $e->getMessage()
    ]);
    exit;
}

// 4. Verificación de credenciales de Admin
function esAdmin() {
    $headers = array_change_key_case(getallheaders(), CASE_LOWER);
    $authUser = $headers['x-admin-user'] ?? $_SERVER['HTTP_X_ADMIN_USER'] ?? '';
    $authPass = $headers['x-admin-password'] ?? $_SERVER['HTTP_X_ADMIN_PASSWORD'] ?? '';
    return ($authUser === 'RTC' && $authPass === 'RTC1234');
}

$method = $_SERVER['REQUEST_METHOD'];

try {
    switch ($method) {
        case 'GET':
            $stmt = $pdo->query("SELECT * FROM productos ORDER BY id DESC");
            $productos = $stmt->fetchAll();
            echo json_encode([
                'success' => true,
                'data' => $productos
            ]);
            break;

        case 'POST':
            if (!esAdmin()) {
                echo json_encode(['success' => false, 'message' => 'No autorizado: Credenciales incorrectas']);
                exit;
            }

            $input = json_decode(file_get_contents('php://input'), true);

            if (empty($input['nombre']) || !isset($input['precio'])) {
                echo json_encode(['success' => false, 'message' => 'Faltan datos obligatorios (Nombre o Precio)']);
                exit;
            }

            $stmt = $pdo->prepare("INSERT INTO productos (nombre, categoria, precio, descripcion, imagen_url) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([
                $input['nombre'],
                $input['categoria'] ?? 'General',
                $input['precio'],
                $input['descripcion'] ?? '',
                $input['imagen_url'] ?? ''
            ]);

            echo json_encode(['success' => true, 'message' => 'Producto guardado en el nodo']);
            break;

        case 'DELETE':
            if (!esAdmin()) {
                echo json_encode(['success' => false, 'message' => 'No autorizado: Credenciales incorrectas']);
                exit;
            }

            $id = $_GET['id'] ?? null;
            if (!$id) {
                echo json_encode(['success' => false, 'message' => 'ID no proporcionado']);
                exit;
            }

            $stmt = $pdo->prepare("DELETE FROM productos WHERE id = ?");
            $stmt->execute([$id]);

            echo json_encode(['success' => true, 'message' => 'Registro eliminado']);
            break;

        default:
            echo json_encode(['success' => false, 'message' => 'Método HTTP no permitido']);
            break;
    }
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Error de consulta SQL: ' . $e->getMessage()
    ]);
}
