<!DOCTYPE html>
<html <?php language_attributes(); ?>>
  <head>
    <meta charset="<?php bloginfo('charset'); ?>" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
      href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap"
      rel="stylesheet"
    />
    <script type="application/ld+json" id="product-structured-data"></script>
    <?php wp_head(); ?>
  </head>
  <?php
    $trygo_body_classes = [ 'trygo-site' ];

    if ( is_front_page() ) {
      $trygo_body_classes[] = 'product-page';
    }

    if ( is_home() || is_archive() ) {
      $trygo_body_classes[] = 'blog-page';
    }

    if ( is_single() ) {
      $trygo_body_classes[] = 'detail-page';
    }

    if ( is_singular( 'features' ) ) {
      $trygo_body_classes[] = 'feature-detail';
    }

    if ( is_post_type_archive( 'features' ) || is_tax( 'feature_type' ) ) {
      $trygo_body_classes[] = 'features-page';
    }

    if ( is_page_template( 'page-features.php' ) ) {
      $trygo_body_classes[] = 'features-page';
    }
  ?>
  <body <?php body_class( $trygo_body_classes ); ?>>
    <?php wp_body_open(); ?>
    <header class="top-nav" data-nav>
      <div class="top-nav__inner">
        <a class="brand" href="<?php echo esc_url( home_url('/') ); ?>" aria-label="TRYGO home">
          <span class="brand-logo" aria-hidden="true">
            <?php 
            $logo_path = get_template_directory() . '/assets/images/trygo-logo.png';
            $logo_url = get_template_directory_uri() . '/assets/images/trygo-logo.png';
            if ( file_exists( $logo_path ) ) : ?>
              <img src="<?php echo esc_url( $logo_url ); ?>" alt="TRYGO Logo" width="38" height="38" />
            <?php else : ?>
              <svg width="38" height="38" viewBox="0 0 38 38" fill="none" xmlns="http://www.w3.org/2000/svg">
                <rect width="38" height="38" rx="12" fill="url(#gradient)"/>
                <text x="19" y="26" font-family="Inter, sans-serif" font-size="16" font-weight="700" text-anchor="middle" fill="white" letter-spacing="0.08em">T</text>
                <defs>
                  <linearGradient id="gradient" x1="0%" y1="0%" x2="100%" y2="100%">
                    <stop offset="0%" style="stop-color:#2563eb;stop-opacity:1" />
                    <stop offset="100%" style="stop-color:#1d4ed8;stop-opacity:1" />
                  </linearGradient>
                </defs>
              </svg>
            <?php endif; ?>
          </span>
          <span class="brand-name">TRYGO</span>
          <span class="brand-badge">Beta</span>
        </a>
        <button class="nav-toggle" type="button" aria-expanded="false" aria-label="Toggle navigation" data-nav-toggle>
          <span></span>
          <span></span>
          <span></span>
        </button>
        <div class="nav-group" data-nav-menu>
          <?php if ( has_nav_menu( 'primary' ) ) : ?>
            <nav class="nav-links" aria-label="Primary">
              <?php
                wp_nav_menu([
                  'theme_location' => 'primary',
                  'menu_class'     => 'nav-links__list',
                  'container'      => false,
                  'fallback_cb'    => '__return_false',
                  'depth'          => 1,
                ]);
              ?>
            </nav>
          <?php endif; ?>
          <a class="nav-cta" href="https://dashboard.trygo.io" target="_blank" rel="noopener noreferrer">
            <span>Get Started</span>
            <span class="cta-arrow">→</span>
          </a>
        </div>
      </div>
    </header>
