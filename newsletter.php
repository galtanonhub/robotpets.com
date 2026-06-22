<?php
require_once __DIR__ . '/includes/functions.php';
header('Content-Type: application/json; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['ok' => false, 'error' => 'Method not allowed']);
    exit;
}

// Honeypot — bots fill this hidden field; silently accept without storing.
if (!empty($_POST['website'])) {
    echo json_encode(['ok' => true, 'message' => 'Thanks for subscribing!']);
    exit;
}

$email = mb_strtolower(trim($_POST['email'] ?? ''));
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(['ok' => false, 'error' => 'Please enter a valid email address.']);
    exit;
}
$source = substr(trim($_POST['source'] ?? 'footer'), 0, 64);

try {
    $stmt = db()->prepare('INSERT INTO subscribers (email, source) VALUES (?, ?)');
    $stmt->execute([$email, $source]);
    echo json_encode(['ok' => true, 'message' => 'Thanks for subscribing!']);
} catch (PDOException $e) {
    if ($e->getCode() === '23000') {
        echo json_encode(['ok' => true, 'message' => "You're already on the list!"]);
    } else {
        http_response_code(500);
        echo json_encode(['ok' => false, 'error' => 'Something went wrong. Please try again.']);
    }
}
