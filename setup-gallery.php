<?php
require_once __DIR__ . '/includes/functions.php';
require_admin();

$sql = "CREATE TABLE IF NOT EXISTS gallery (
  id         INT AUTO_INCREMENT PRIMARY KEY,
  owner_name VARCHAR(100) NOT NULL,
  pet_name   VARCHAR(100) NOT NULL,
  story      TEXT,
  image      VARCHAR(255) NOT NULL,
  approved   TINYINT(1) NOT NULL DEFAULT 0,
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  INDEX (approved)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";

try {
    db()->exec($sql);
    echo '<p style="font-family:sans-serif;padding:2rem;color:green;">✓ gallery table created (or already existed). You can now delete this file.</p>';
} catch (Exception $e) {
    echo '<p style="font-family:sans-serif;padding:2rem;color:red;">Error: ' . htmlspecialchars($e->getMessage()) . '</p>';
}
