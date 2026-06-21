<?php
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/db.php';

define('SITE_URL', 'https://robotpets.com');

function h(?string $s): string
{
    return htmlspecialchars($s ?? '', ENT_QUOTES, 'UTF-8');
}

function money($n): string
{
    return '$' . number_format((float)$n, 2);
}

function slugify(string $name): string
{
    $slug = strtolower(trim($name));
    $slug = preg_replace('/[^a-z0-9]+/', '-', $slug);
    return trim($slug, '-');
}

// ---------- Image mirroring ----------
function mirror_image_url(string $url): string
{
    if (!filter_var($url, FILTER_VALIDATE_URL)) return $url;

    // Strip query params to get clean extension
    $path = parse_url($url, PHP_URL_PATH);
    $ext  = strtolower(pathinfo($path, PATHINFO_EXTENSION));
    if (!in_array($ext, ['jpg','jpeg','png','gif','webp'], true)) $ext = 'jpg';

    $filename = time() . '-' . bin2hex(random_bytes(6)) . '.' . $ext;
    $dest     = UPLOAD_DIR . '/' . $filename;

    if (!is_dir(UPLOAD_DIR)) mkdir(UPLOAD_DIR, 0755, true);

    $ctx  = stream_context_create(['http' => ['timeout' => 15, 'user_agent' => 'Mozilla/5.0']]);
    $data = @file_get_contents($url, false, $ctx);
    if ($data === false || strlen($data) < 100) return $url; // fallback to original on failure

    file_put_contents($dest, $data);
    return UPLOAD_URL . '/' . $filename;
}

// ---------- Cart (session-based: [productId => qty]) ----------
function cart(): array
{
    return $_SESSION['cart'] ?? [];
}

function cart_count(): int
{
    return array_sum(cart());
}

function cart_set(int $productId, int $qty): void
{
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }
    if ($qty <= 0) {
        unset($_SESSION['cart'][$productId]);
    } else {
        $_SESSION['cart'][$productId] = $qty;
    }
}

function cart_add(int $productId, int $qty): void
{
    cart_set($productId, (cart()[$productId] ?? 0) + $qty);
}

// Returns [['product' => row, 'qty' => n, 'line_total' => x], ...] and total
function cart_items(): array
{
    $items = [];
    $total = 0.0;
    $cart = cart();
    if ($cart) {
        $ids = array_keys($cart);
        $placeholders = implode(',', array_fill(0, count($ids), '?'));
        $stmt = db()->prepare("SELECT * FROM products WHERE id IN ($placeholders)");
        $stmt->execute($ids);
        foreach ($stmt->fetchAll() as $p) {
            $qty = $cart[$p['id']];
            $line = (float)$p['price'] * $qty;
            $items[] = ['product' => $p, 'qty' => $qty, 'line_total' => $line];
            $total += $line;
        }
    }
    return [$items, $total];
}

// ---------- Admin auth ----------
function is_admin(): bool
{
    return !empty($_SESSION['admin_id']);
}

function require_admin(): void
{
    if (!is_admin()) {
        header('Location: /admin/login.php');
        exit;
    }
}
