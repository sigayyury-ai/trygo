<?php
/**
 * Universal CTA Section Template
 * 
 * Usage: 
 * get_template_part('cta-section', null, [
 *   'eyebrow' => 'Experience TRYGO',
 *   'title' => 'Launch your GTM strategy in one click',
 *   'description' => 'Complete the onboarding flow and get campaigns, hypotheses, and activation channels generated for you in minutes.',
 *   'button_text' => 'Generate GTM',
 *   'button_url' => 'https://dashboard.trygo.io/dashboard',
 *   'image_url' => 'https://urock.pro/trygo/wp-content/uploads/2025/09/glowing-spider-web-dark-abstract-backdrop-generated-by-ai.jpg',
 *   'image_alt' => 'Team collaborating on a go-to-market strategy'
 * ]);
 */

// Default values
$args = wp_parse_args($args ?? [], [
    'eyebrow' => 'Experience TRYGO',
    'title' => 'Launch your GTM strategy in one click',
    'description' => 'Complete the onboarding flow and get campaigns, hypotheses, and activation channels generated for you in minutes.',
    'button_text' => 'Generate GTM',
    'button_url' => 'https://dashboard.trygo.io/dashboard',
    'image_url' => 'https://urock.pro/trygo/wp-content/uploads/2025/09/glowing-spider-web-dark-abstract-backdrop-generated-by-ai.jpg',
    'image_alt' => 'Team collaborating on a go-to-market strategy',
    'section_id' => 'onboarding',
    'title_id' => 'product-cta-title'
]);

// Extract variables
$eyebrow = esc_html($args['eyebrow']);
$title = esc_html($args['title']);
$description = esc_html($args['description']);
$button_text = esc_html($args['button_text']);
$button_url = esc_url($args['button_url']);
$image_url = esc_url($args['image_url']);
$image_alt = esc_attr($args['image_alt']);
$section_id = esc_attr($args['section_id']);
$title_id = esc_attr($args['title_id']);
?>

<section class="cta" id="<?php echo $section_id; ?>" aria-labelledby="<?php echo $title_id; ?>">
  <div class="cta-inner">
    <div class="cta-media">
      <img src="<?php echo $image_url; ?>" alt="<?php echo $image_alt; ?>" loading="lazy" />
    </div>
    <div class="cta-content">
      <?php if ($eyebrow) : ?>
        <p class="eyebrow"><?php echo $eyebrow; ?></p>
      <?php endif; ?>
      
      <?php if ($title) : ?>
        <h2 id="<?php echo $title_id; ?>"><?php echo $title; ?></h2>
      <?php endif; ?>
      
      <?php if ($description) : ?>
        <p><?php echo $description; ?></p>
      <?php endif; ?>
      
      <?php if ($button_text && $button_url) : ?>
        <a class="cta-button" href="<?php echo $button_url; ?>" target="_blank" rel="noopener noreferrer" role="button">
          <?php echo $button_text; ?>
        </a>
      <?php endif; ?>
    </div>
  </div>
</section>
