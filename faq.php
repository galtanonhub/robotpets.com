<?php
require_once __DIR__ . '/includes/functions.php';
$title = 'FAQ';
$description = 'Answers to common questions about RobotPets — battery life, shipping, returns, warranties, allergies, and more.';
include __DIR__ . '/includes/header.php';
?>

<section class="page-hero">
  <div class="container">
    <h1>Frequently Asked Questions</h1>
    <p>Everything you want to know before you bring home your new companion.</p>
  </div>
</section>

<section class="section container faq-wrap">

  <div class="faq-group">
    <h2 class="faq-group-title">Shopping &amp; Orders</h2>

    <details class="faq-item">
      <summary>How do I place an order?</summary>
      <p>Browse the <a href="/shop.php">shop</a>, add any items you like to your cart, then head to checkout. We accept all major credit and debit cards. You'll receive a confirmation email once your order is placed.</p>
    </details>

    <details class="faq-item">
      <summary>Can I change or cancel my order after placing it?</summary>
      <p>Contact us at <a href="mailto:support@robotpets.com">support@robotpets.com</a> as soon as possible. We process orders quickly, but if your order hasn't shipped yet we can usually make changes or cancel it for a full refund.</p>
    </details>

    <details class="faq-item">
      <summary>Do you offer gift wrapping or gift messages?</summary>
      <p>Yes — add a note in the order comments at checkout and we'll include a handwritten gift message. Gift-ready packaging is available for an additional fee; just mention it in your note and we'll confirm the cost by email.</p>
    </details>

    <details class="faq-item">
      <summary>Are your prices in USD?</summary>
      <p>Yes, all prices are in US dollars. International customers will see the conversion handled by their card issuer at checkout.</p>
    </details>
  </div>

  <div class="faq-group">
    <h2 class="faq-group-title">Shipping &amp; Returns</h2>

    <details class="faq-item">
      <summary>Do you ship worldwide?</summary>
      <p>Yes — we ship to most countries. Free shipping is included on every order, no minimum required. Delivery times vary by destination: 3–5 business days domestically, 7–14 days internationally.</p>
    </details>

    <details class="faq-item">
      <summary>What is your return policy?</summary>
      <p>We offer a 30-day return window from the date of delivery. Items must be in original condition with all packaging. To start a return, email <a href="mailto:support@robotpets.com">support@robotpets.com</a> with your order number and we'll send you a prepaid label.</p>
    </details>

    <details class="faq-item">
      <summary>My order arrived damaged. What do I do?</summary>
      <p>We're sorry to hear that. Please take a photo of the damage and email it to <a href="mailto:support@robotpets.com">support@robotpets.com</a> within 7 days of delivery. We'll arrange a replacement or full refund immediately — no questions asked.</p>
    </details>
  </div>

  <div class="faq-group">
    <h2 class="faq-group-title">The Pets Themselves</h2>

    <details class="faq-item">
      <summary>Are robotic pets safe for children?</summary>
      <p>Most of our companions are designed for ages 3 and up. Each product page clearly states the recommended age range. Pets designed for kids have no small detachable parts and use child-safe materials. We also carry models specifically suited for very young children.</p>
    </details>

    <details class="faq-item">
      <summary>Are they truly hypoallergenic?</summary>
      <p>Yes. Robotic pets have no fur, no dander, no saliva, and no shedding — so they are completely safe for people with pet allergies. They are also non-toxic and safe for households with both people and real animals.</p>
    </details>

    <details class="faq-item">
      <summary>How long does the battery last?</summary>
      <p>Battery life varies by model but most companions run 2–6 hours on a full charge and recharge in 1–3 hours. Many models enter a low-power "sleep" mode when not interacted with to extend playtime. Battery specs are listed on each product page.</p>
    </details>

    <details class="faq-item">
      <summary>Do they need Wi-Fi or an app to work?</summary>
      <p>Entry-level models work completely standalone — no app, no Wi-Fi required. Advanced AI models may offer optional companion apps for added features (custom commands, firmware updates, personality tuning), but core functions always work out of the box.</p>
    </details>

    <details class="faq-item">
      <summary>Can robotic pets learn and remember things over time?</summary>
      <p>AI-enhanced models do learn. They can remember your name, recognize your voice, pick up on your daily routines, and develop a unique personality shaped by how you interact with them. Entry-level models have fixed behaviors but are still highly responsive to touch and sound.</p>
    </details>

    <details class="faq-item">
      <summary>Are they suitable for elderly people in care homes?</summary>
      <p>Absolutely — this is one of our most popular use cases. Our companions require no feeding, no cleaning, and no veterinary care. They respond to gentle touch and provide consistent, calm companionship. Many care facilities use robotic pets as part of therapeutic programs for residents with dementia or social isolation.</p>
    </details>
  </div>

  <div class="faq-group">
    <h2 class="faq-group-title">Warranty &amp; Support</h2>

    <details class="faq-item">
      <summary>What does the 1-year warranty cover?</summary>
      <p>All pets come with a 1-year limited warranty covering manufacturing defects, electronic failures, and motor issues under normal use. It does not cover physical damage from drops, water exposure, or unauthorized modifications. Extended warranty options are available at checkout.</p>
    </details>

    <details class="faq-item">
      <summary>My pet isn't behaving the way I expect. How do I get help?</summary>
      <p>Start with the troubleshooting guide included in the box. If that doesn't resolve it, reach out through our <a href="/contact.php">contact page</a> or email <a href="mailto:support@robotpets.com">support@robotpets.com</a>. Our team typically responds within 24 hours on business days.</p>
    </details>

    <details class="faq-item">
      <summary>Can I get replacement parts?</summary>
      <p>Yes, for most models. Common replacement parts (charging cables, covers, batteries) are listed in our <a href="/shop.php?category=accessories-parts">Accessories section</a>. For parts not listed, contact support and we'll source them for you.</p>
    </details>
  </div>

  <div class="faq-cta">
    <p>Still have a question?</p>
    <a href="/contact.php" class="btn btn-primary">Contact Us</a>
  </div>

</section>

<?php include __DIR__ . '/includes/footer.php'; ?>
