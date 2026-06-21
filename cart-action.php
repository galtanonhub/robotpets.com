<?php
// Cart endpoint: add (JSON or form), update, remove.
require_once __DIR__ . '/includes/functions.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: /cart.php');
    exit;
}

// Accept JSON bodies from fetch() as well as regular form posts
$input = $_POST;
$isJson = str_contains($_SERVER['CONTENT_TYPE'] ?? '', 'application/json');
if ($isJson) {
    $input = json_decode(file_get_contents('php://input'), true) ?? [];
}

$action = $input['action'] ?? 'add';
$productId = (int)($input['productId'] ?? 0);
$qty = (int)($input['qty'] ?? 1);

switch ($action) {
    case 'add':
        $stmt = db()->prepare('SELECT id FROM products WHERE id = ? AND active = 1');
        $stmt->execute([$productId]);
        if ($stmt->fetch()) {
            cart_add($productId, max(1, $qty));
        } elseif ($isJson) {
            http_response_code(404);
            echo json_encode(['error' => 'Product not found']);
            exit;
        }
        break;
    case 'update':
        cart_set($productId, $qty);
        break;
    case 'remove':
        cart_set($productId, 0);
        break;
}

if ($isJson) {
    header('Content-Type: application/json');
    echo json_encode(['count' => cart_count()]);
} else {
    header('Location: /cart.php');
}
