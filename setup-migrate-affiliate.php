<?php
require_once __DIR__ . '/includes/functions.php';
require_admin();

$rows = db()->exec(
    "UPDATE products SET affiliate_url = supplier_url WHERE supplier_url IS NOT NULL AND supplier_url != '' AND (affiliate_url IS NULL OR affiliate_url = '')"
);
echo '<p style="font-family:sans-serif;padding:2rem;color:green;">✓ ' . $rows . ' product(s) updated — supplier_url copied to affiliate_url. You can now delete this file.</p>';
