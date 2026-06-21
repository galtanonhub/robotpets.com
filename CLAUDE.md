# RobotPets.com — Claude Context

## What this site is
Affiliate e-commerce site for robotic/AI pets (primary brand: Chongker). Visitors browse products and click out to retailers via `/go/<slug>`. No checkout, no cart in use — pure affiliate model. PHP/MySQL, no framework, no build step.

## Hosting & deploy
- **Host:** cPanel shared hosting
- **Deploy:** `git push origin master` → user manually pulls on cPanel git panel
- **DB:** MySQL 5.7 (no `ADD COLUMN IF NOT EXISTS` — use try/catch)
- **Config:** `includes/config.php` — excluded from git (has real credentials), never commit

## Key conventions
- CSS cache bust: `?v=N` on stylesheet link in `includes/header.php` — increment N on any CSS change
- `UPLOAD_DIR` = server path to `/uploads/`, `UPLOAD_URL` = `/uploads`
- Images are mirrored locally via `mirror_image_url()` in `includes/functions.php` — never hotlink from Chongker
- Affiliate links: stored as `affiliate_url` in DB, exposed publicly as `/go/<slug>` via `go.php`
- `compare_at_price` = crossed-out original price; `price` = current sale price
- `is_hero` tinyint — one product pinned to homepage hero (exclusive: set all to 0 before setting 1)
- `featured` tinyint — Featured Companions grid, max 8 slots
- Side tiles on hero: 2 newest non-accessory products excluding hero, ordered by `created_at DESC`

## Page structure
Every page: `require_once includes/functions.php` → set `$title`, `$description`, etc. → `include includes/header.php` → content → `include includes/footer.php`

## Admin
`/admin/` — password-protected. Key pages:
- `product-form.php` — add/edit products, image upload or URL (auto-mirrors), is_hero + featured checkboxes
- `product-audit.php` — shows missing affiliate URLs, images, categories
- `posts.php` / `post-form.php` — blog and guides (same `posts` table, `type` column: `post` vs `guide`)
- `reviews.php` — moderate reviews (public submission disabled; re-enable when site has real traffic)

## DB tables (key ones)
- `products` — id, name, slug, description, price, compare_at_price, affiliate_url, image, category_id, featured, is_hero, active
- `categories` — id, name, slug
- `posts` — id, title, slug, body, excerpt, image, type (post|guide), published_at, active
- `reviews` — id, product_id, name, email, rating, body, approved, created_at

## What's intentionally disabled / dormant
- **Cart/checkout** — `cart.php`, `cart-action.php`, `checkout.php` exist but no public links; session cart functions in `functions.php` kept for future merch
- **Review submission form** — removed from `product.php`; reviews section only renders when `$reviewCount > 0`

## Mobile / CSS notes
- `overflow-x: hidden` on both `html` and `body`
- Mobile drawer uses `transform: translateX(100%)` not `right: -300px` (iOS Safari fix)
- Tab switcher: pill buttons desktop, native `<select>` dropdown on mobile
- Audience strip: `<section class="section container audience-strip">` — both classes on same element (no double padding)

## Git
- Repo: `https://github.com/galtanonhub/robotpets.com.git` (private)
- Branch: `master`
- Stage specific files by name — never `git add -A`
- Delete merged branches locally + remote after merge
- Never commit: `config.php`, `.env`, credentials of any kind
