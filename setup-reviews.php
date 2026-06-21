<?php
require_once __DIR__ . '/includes/functions.php';
require_admin();

$sql = "CREATE TABLE IF NOT EXISTS reviews (
  id         INT AUTO_INCREMENT PRIMARY KEY,
  product_id INT NOT NULL,
  name       VARCHAR(100) NOT NULL,
  email      VARCHAR(255) NOT NULL,
  rating     TINYINT NOT NULL DEFAULT 5,
  body       TEXT,
  approved   TINYINT(1) NOT NULL DEFAULT 0,
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  INDEX (product_id),
  INDEX (approved)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";

try {
    db()->exec($sql);
    echo '<p style="font-family:sans-serif;padding:2rem;color:green;">✓ reviews table created (or already existed). You can now delete this file.</p>';
} catch (Exception $e) {
    echo '<p style="font-family:sans-serif;padding:2rem;color:red;">Error: ' . htmlspecialchars($e->getMessage()) . '</p>';
}
