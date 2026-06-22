<?php
require_once __DIR__ . '/includes/functions.php';

// Single source of truth: renders both the HTML and the FAQPage schema.
// Answers may contain HTML (links); schema uses a stripped-tags version.
$faqGroups = [
    'How RobotPets Works' => [
        ['Do you sell these products directly?',
         'No — RobotPets is a curated guide and review site. When you click "Check Price" on any product, you\'ll be taken directly to the retailer\'s website to complete your purchase. This means you\'re buying from the actual seller, with their pricing, shipping, and return policies.'],
        ['Who are the vendors you link to?',
         'We link to established retailers and brand websites that sell robotic companions. We vet each vendor before featuring their products. The vendor is clearly identified on the retailer\'s product page once you click through.'],
        ['How do I place an order?',
         'Click the "Check Price →" button on any product page. You\'ll be taken to the seller\'s website where you can add the item to their cart and check out directly with them.'],
        ['What are the shipping and return policies?',
         'Policies vary by vendor. Check the retailer\'s website for their current shipping times, costs, and return window before purchasing. If you have questions about a specific order, contact the retailer directly.'],
        ['Are your prices accurate?',
         'We do our best to keep prices up to date, but vendors may change their pricing at any time. The price shown on the retailer\'s checkout is always the authoritative one.'],
    ],
    'The Pets Themselves' => [
        ['Are robotic pets safe for children?',
         'Most companions are designed for ages 3 and up. Check the product page for the recommended age range. Models made for kids use child-safe materials and have no small detachable parts.'],
        ['Are they truly hypoallergenic?',
         'Yes. Robotic pets have no fur, no dander, no saliva, and no shedding — completely safe for people with pet allergies. They are also non-toxic and safe for households with real animals.'],
        ['How long does the battery last?',
         'Battery life varies by model but most companions run 2–6 hours on a full charge and recharge in 1–4 hours via USB-C. Many models enter a low-power sleep mode when not interacted with to extend playtime. Specs are listed on each product page.'],
        ['Do they need Wi-Fi or an app to work?',
         'Most models work completely standalone — no app or Wi-Fi required. Some advanced models offer optional companion apps for extra features, but core functions always work out of the box.'],
        ['Can robotic pets learn and remember things over time?',
         'Some models can. Voice-interactive companions like the Percy series can learn your name and respond to commands. Entry-level models have fixed behaviors but are still highly responsive to touch and sound.'],
        ['Are they suitable for elderly people in care homes?',
         'Absolutely — this is one of the most popular use cases. Robotic companions require no feeding, cleaning, or veterinary care. They respond to gentle touch and provide consistent, calm companionship. Many care facilities use them in therapeutic programs for residents with dementia or social isolation.'],
    ],
    'Warranty &amp; Support' => [
        ['What warranty comes with these products?',
         'Warranty terms vary by brand and model — check the product listing on the retailer\'s site for specifics. Many Chongker products, for example, include a 1-year manufacturer warranty covering defects and electronic failures.'],
        ['My pet isn\'t working as expected. Who do I contact?',
         'For product issues, contact the retailer or manufacturer you purchased from directly — they handle warranty claims and technical support. If you need help finding the right contact, reach out to us via the <a href="/contact.php">contact page</a> and we\'ll point you in the right direction.'],
        ['Can I get replacement parts?',
         'Many brands offer replacement accessories like charging cables. Check our <a href="/shop.php?category=accessories-parts">Accessories section</a> or the brand\'s own website for available parts.'],
    ],
];

// Build FAQPage structured data from the same array
$faqEntities = [];
foreach ($faqGroups as $items) {
    foreach ($items as [$q, $a]) {
        $faqEntities[] = [
            '@type' => 'Question',
            'name'  => html_entity_decode(strip_tags($q), ENT_QUOTES, 'UTF-8'),
            'acceptedAnswer' => [
                '@type' => 'Answer',
                'text'  => html_entity_decode(strip_tags($a), ENT_QUOTES, 'UTF-8'),
            ],
        ];
    }
}
$json_ld = json_encode([
    '@context'   => 'https://schema.org',
    '@type'      => 'FAQPage',
    'mainEntity' => $faqEntities,
], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);

$title = 'FAQ';
$description = 'Answers to common questions about robotic pets — battery life, how they work, who they\'re for, and how to buy.';
include __DIR__ . '/includes/header.php';
?>

<section class="page-hero">
  <div class="container">
    <h1>Frequently Asked Questions</h1>
    <p>Everything you want to know before you bring home your new companion.</p>
  </div>
</section>

<section class="section container faq-wrap">

  <?php foreach ($faqGroups as $groupTitle => $items): ?>
    <div class="faq-group">
      <h2 class="faq-group-title"><?= $groupTitle ?></h2>
      <?php foreach ($items as [$q, $a]): ?>
        <details class="faq-item">
          <summary><?= $q ?></summary>
          <p><?= $a ?></p>
        </details>
      <?php endforeach; ?>
    </div>
  <?php endforeach; ?>

  <div class="faq-cta">
    <p>Still have a question?</p>
    <a href="/contact.php" class="btn btn-primary">Contact Us</a>
  </div>

</section>

<?php include __DIR__ . '/includes/footer.php'; ?>
