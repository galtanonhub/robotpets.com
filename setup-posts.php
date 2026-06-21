<?php
// One-time DB migration to create the posts table.
// Visit this page once while logged in as admin, then you can delete it.
require_once __DIR__ . '/includes/functions.php';
require_admin();

$sql = "CREATE TABLE IF NOT EXISTS posts (
  id           INT AUTO_INCREMENT PRIMARY KEY,
  type         ENUM('post','guide') NOT NULL DEFAULT 'post',
  title        VARCHAR(255) NOT NULL,
  slug         VARCHAR(255) NOT NULL UNIQUE,
  excerpt      TEXT,
  body         LONGTEXT,
  image        VARCHAR(255),
  published_at DATETIME,
  active       TINYINT(1) NOT NULL DEFAULT 1,
  created_at   DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";

try {
    db()->exec($sql);
    echo '<p style="font-family:sans-serif;padding:2rem;color:green;">✓ posts table created (or already existed). You can now delete this file.</p>';
} catch (Exception $e) {
    echo '<p style="font-family:sans-serif;padding:2rem;color:red;">Error: ' . htmlspecialchars($e->getMessage()) . '</p>';
}
