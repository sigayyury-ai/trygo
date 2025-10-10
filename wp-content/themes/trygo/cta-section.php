<?php
/**
 * Original CTA Section Template
 * 
 * Usage: 
 * get_template_part('cta-section');
 */

// Default values for the original CTA block
$eyebrow = 'Experience TRYGO';
$title = 'Launch your GTM strategy in one click';
$description = 'Complete the onboarding flow and get campaigns, hypotheses, and activation channels generated for you in minutes.';
$button_text = 'Generate GTM';
$button_url = 'https://dashboard.trygo.io';
$image_url = 'https://urock.pro/trygo/wp-content/uploads/2025/09/glowing-spider-web-dark-abstract-backdrop-generated-by-ai.jpg';
$image_alt = 'Team collaborating on a go-to-market strategy';
$section_id = 'onboarding';
$title_id = 'product-cta-title';
?>

<section class="cta" id="<?php echo esc_attr($section_id); ?>" aria-labelledby="<?php echo esc_attr($title_id); ?>">
  <div class="cta-inner">
    <div class="cta-media">
      <img src="<?php echo esc_url($image_url); ?>" alt="<?php echo esc_attr($image_alt); ?>" loading="lazy" />
    </div>
    <div class="cta-content">
      <p class="eyebrow"><?php echo esc_html($eyebrow); ?></p>
      <h2 id="<?php echo esc_attr($title_id); ?>"><?php echo esc_html($title); ?></h2>
      <p><?php echo esc_html($description); ?></p>
      <a class="cta-button" href="<?php echo esc_url($button_url); ?>" target="_blank" rel="noopener noreferrer" role="button">
        <?php echo esc_html($button_text); ?>
      </a>
    </div>
  </div>
</section>
