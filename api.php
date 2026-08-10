<?php
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, X-Admin-User, X-Admin-Password');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit(0);
}

// Configuración de la base de datos para Railway
$host = 'sakura.proxy.rlwy.net'; // Ej: autorack.proxy.rlwy.net
$port = '26568'; // Ej: 26568
$db   = 'railway'; 
$user = 'root';
$pass = 'pbZkTIRmLqGhjIoiYlZHlcVDscaljUoW';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;port=$port;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Error de conexión: ' . $e->getMessage()]);
    exit;
}

// Credenciales del Administrador
$admin_user = 'RTC';
$admin_pass = 'RTC1234';

function verificarAuth() {
    global $admin_user, $admin_pass;
    $headers = [];
    if (function_exists('getallheaders')) {
        $headers = getallheaders();
    } else {
        foreach ($_SERVER as $name => $value) {
            if (substr($name, 0, 5) == 'HTTP_') {
                $headers[str_replace(' ', '-', ucwords(strtolower(str_replace('_', ' ', substr($name, 5)))))] = $value;
            }
        }
    }
    
    $u = isset($headers['X-Admin-User']) ? $headers['X-Admin-User'] : '';
    $p = isset($headers['X-Admin-Password']) ? $headers['X-Admin-Password'] : '';
    
    return ($u === $admin_user && $p === $admin_pass);
}

$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'GET':
        try {
            $stmt = $pdo->query("SELECT * FROM productos ORDER BY id DESC");
            $productos = $stmt->fetchAll();
            echo json_encode(['success' => true, 'data' => $productos]);
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
        break;

    case 'POST':
        if (!verificarAuth()) {
            echo json_encode(['success' => false, 'message' => 'No autorizado. Credenciales incorrectas.']);
            exit;
        }
        
        $input = json_decode(file_get_contents('php://input'), true);
        if (!$input) {
            echo json_encode(['success' => false, 'message' => 'Datos inválidos']);
            exit;
        }

        try {
            $stmt = $pdo->prepare("INSERT INTO productos (nombre, categoria, precio, descripcion, imagen_url) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([
                $input['nombre'] ?? '',
                $input['categoria'] ?? '',
                $input['precio'] ?? 0,
                $input['descripcion'] ?? '',
                $input['imagen_url'] ?? ''
            ]);
            echo json_encode(['success' => true, 'message' => 'Producto agregado con éxito']);
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
        break;

    case 'DELETE':
        if (!verificarAuth()) {
            echo json_encode(['success' => false, 'message' => 'No autorizado. Credenciales incorrectas.']);
            exit;
        }

        $id = isset($_GET['id']) ? $_GET['id'] : null;
        if (!$id) {
            echo json,encode(['success' => false, 'message' => 'ID de producto no proporcionado']);
            exit;
        }

        try {
            $stmt = $pdo->prepare("DELETE FROM productos WHERE id = ?");
            $stmt->execute([$id]);
            echo json_encode(['success' => true, 'message' => 'Producto eliminado con éxito']);
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
        break;

    default:
        echo json_encode(['success' => false, 'message' => 'Método no soportado']);
        break;
}
?>
