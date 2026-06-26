<?php
/**
 * One-off Amazon affiliate import.
 * Reads admin/_import-data.json (produced from the Amazon Links spreadsheet),
 * inserts each product HIDDEN (active=0) for review, mirrors images locally,
 * auto-assigns a category by keyword, and is idempotent (skips ASINs already imported).
 *
 * DELETE this file and _import-data.json after a successful run.
 */
require_once __DIR__ . '/../includes/functions.php';
require_admin();

$pdo  = db();
$data = json_decode(file_get_contents(__DIR__ . '/_import-data.json'), true);
if (!is_array($data)) {
    die('Could not read _import-data.json');
}

// Map category id by lowercase keyword in the product name. First match wins.
$catMap = [
    1 => ['dog', 'puppy', 'dachshund', 'husky', 'collie', 'labradoodle'], // Robot Dogs
    2 => ['cat', 'kitten', 'kitty', 'tabby', 'maltese', 'ragdoll'],       // Robot Cats
    4 => ['raspberry', 'sunfounder', 'pidog', ' kit', 'stem kit'],        // Accessories & Parts
    3 => ['bird', 'fish', 'monkey', 'hedgehog', 'unicorn', 'dinosaur',    // Robot Birds & Exotics
          'locust', 'pig', 'piggly', 'dino'],
];
function pick_category(string $name, array $catMap): ?int {
    $n = strtolower($name);
    foreach ([4, 1, 2, 3] as $cid) {           // parts first so "robot dog KIT" -> parts
        foreach ($catMap[$cid] as $kw) {
            if (strpos($n, $kw) !== false) return $cid;
        }
    }
    return null;
}

// Which ASINs are already in the DB? (stored inside affiliate_url)
$existing = [];
foreach ($pdo->query('SELECT affiliate_url FROM products')->fetchAll() as $r) {
    if (preg_match('#/dp/([A-Z0-9]{10})#', (string)$r['affiliate_url'], $m)) {
        $existing[$m[1]] = true;
    }
}

$report = [];
foreach ($data as $row) {
    $asin = $row['asin'];
    if (isset($existing[$asin])) { $report[] = ['skip (already imported)', $asin, $row['name']]; continue; }

    $image = '';
    if (!empty($row['image'])) {
        $image = mirror_image_url($row['image']);
        if (strpos($image, (string)UPLOAD_URL) !== 0) $image = ''; // mirroring failed -> leave blank
    }

    $slug = slugify($row['name']);
    $stmt = $pdo->prepare('SELECT COUNT(*) FROM products WHERE slug LIKE ?');
    $stmt->execute(["$slug%"]);
    $dupes = (int)$stmt->fetchColumn();
    if ($dupes > 0) $slug .= '-' . ($dupes + 1);

    $fields = [
        'name'             => $row['name'],
        'slug'             => $slug,
        'description'      => $row['description'] ?: '',
        'price'            => $row['price'] !== '' ? (float)$row['price'] : 0,
        'compare_at_price' => $row['compare_at_price'] !== '' ? (float)$row['compare_at_price'] : null,
        'affiliate_url'    => $row['affiliate_url'],
        'image'            => $image,
        'category_id'      => pick_category($row['name'], $catMap),
        'stock'            => 0,
        'featured'         => 0,
        'is_hero'          => 0,
        'active'           => 0, // hidden for review
    ];
    $cols  = implode(', ', array_keys($fields));
    $marks = implode(', ', array_fill(0, count($fields), '?'));
    $pdo->prepare("INSERT INTO products ($cols) VALUES ($marks)")
        ->execute(array_values($fields));
    $existing[$asin] = true;

    $note = [];
    if (!empty($row['unavailable'])) $note[] = 'UNAVAILABLE on Amazon';
    if ($fields['price'] == 0) $note[] = 'NO PRICE';
    if ($image === '')         $note[] = 'NO IMAGE';
    if ($fields['category_id'] === null) $note[] = 'no category';
    $report[] = ['imported', $asin, $row['name'], implode(', ', $note)];
}

header('Content-Type: text/html; charset=utf-8');
echo '<h1>Amazon import</h1><table border=1 cellpadding=6 style="border-collapse:collapse;font:13px sans-serif">';
echo '<tr><th>status</th><th>asin</th><th>name</th><th>flags</th></tr>';
foreach ($report as $r) {
    printf('<tr><td>%s</td><td>%s</td><td>%s</td><td style="color:#b00">%s</td></tr>',
        htmlspecialchars($r[0]), htmlspecialchars($r[1]),
        htmlspecialchars(mb_strimwidth($r[2], 0, 70, '…')), htmlspecialchars($r[3] ?? ''));
}
echo '</table>';
$imp = count(array_filter($report, fn($r) => $r[0] === 'imported'));
echo "<p><b>$imp imported</b> (hidden). Review in <a href='/admin/products.php'>Products</a>, then DELETE import-amazon.php + _import-data.json.</p>";
