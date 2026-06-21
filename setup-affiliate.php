<?php
require_once __DIR__ . '/includes/functions.php';
require_admin();

try {
    db()->exec("ALTER TABLE products ADD COLUMN affiliate_url VARCHAR(500) NULL AFTER supplier_url");
    echo '<p style="font-family:sans-serif;padding:2rem;color:green;">✓ affiliate_url column added. You can now delete this file.</p>';
} catch (PDOException $e) {
    if (str_contains($e->getMessage(), 'Duplicate column')) {
        echo '<p style="font-family:sans-serif;padding:2rem;color:green;">✓ Column already exists. You can delete this file.</p>';
    } else {
        echo '<p style="font-family:sans-serif;padding:2rem;color:red;">Error: ' . htmlspecialchars($e->getMessage()) . '</p>';
    }
}
