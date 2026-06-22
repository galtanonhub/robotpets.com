<?php
// One-time setup: creates the `subscribers` table. Run once, then delete this file.
require_once __DIR__ . '/includes/functions.php';

$sql = "CREATE TABLE IF NOT EXISTS subscribers (
  id INT AUTO_INCREMENT PRIMARY KEY,
  email VARCHAR(255) NOT NULL UNIQUE,
  source VARCHAR(64) DEFAULT NULL,
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";

header('Content-Type: text/plain; charset=utf-8');
try {
    db()->exec($sql);
    echo "✓ subscribers table created (or already existed). You can now delete this file.";
} catch (Throwable $e) {
    echo "Error: " . $e->getMessage();
}
