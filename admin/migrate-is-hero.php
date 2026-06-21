<?php
require_once __DIR__ . '/../includes/functions.php';
require_admin();

$pdo = db();
$pdo->exec("ALTER TABLE products ADD COLUMN IF NOT EXISTS is_hero TINYINT(1) NOT NULL DEFAULT 0");
echo '<p style="font-family:sans-serif;color:green">✓ is_hero column added. You can now delete this file.</p>';
