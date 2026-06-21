<?php
require_once __DIR__ . '/../includes/functions.php';
require_admin();

$pdo = db();
try {
    $pdo->exec("ALTER TABLE products ADD COLUMN is_hero TINYINT(1) NOT NULL DEFAULT 0");
    echo '<p style="font-family:sans-serif;color:green">✓ is_hero column added. You can now delete this file.</p>';
} catch (PDOException $e) {
    if (strpos($e->getMessage(), 'Duplicate column') !== false) {
        echo '<p style="font-family:sans-serif;color:#ca8a04">Column already exists — nothing to do. You can delete this file.</p>';
    } else {
        echo '<p style="font-family:sans-serif;color:red">Error: ' . htmlspecialchars($e->getMessage()) . '</p>';
    }
}
