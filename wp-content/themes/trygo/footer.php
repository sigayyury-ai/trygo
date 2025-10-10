    <footer class="site-footer" aria-label="Site footer">
      <div class="footer-top">
        <div class="footer-brand">
          <div class="eyebrow">TRYGO — Marketing co-pilot</div>
          <?php if ( has_nav_menu( 'footer_main' ) ) : ?>
            <nav class="footer-main-nav" aria-label="Footer primary navigation">
            <h3>Solutions</h3>
              <?php
                wp_nav_menu([
                  'theme_location' => 'footer_main',
                  'menu_class'     => 'footer-list',
                  'menu_id'        => false,
                  'container'      => false,
                  'fallback_cb'    => '__return_false',
                  'depth'          => 1,
                ]);
              ?>
            </nav>
          <?php endif; ?>
          <div class="footer-social" aria-label="Social media">
            <a href="#" aria-label="TRYGO on YouTube">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round">
                <path d="M22.54 6.42a2.78 2.78 0 0 0-1.94-1.94C18.88 4 12 4 12 4s-6.88 0-8.6.48a2.78 2.78 0 0 0-1.94 1.94A29 29 0 0 0 1 12a29 29 0 0 0 .46 5.58 2.78 2.78 0 0 0 1.94 1.94C5.12 20 12 20 12 20s6.88 0 8.6-.48a2.78 2.78 0 0 0 1.94-1.94A29 29 0 0 0 23 12a29 29 0 0 0-.46-5.58Z" />
                <path d="m10 15 5-3-5-3v6Z" />
              </svg>
            </a>
            <a href="#" aria-label="TRYGO on X (Twitter)">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round">
                <path d="M4 4l16 16" />
                <path d="M20 4 4 20" />
              </svg>
            </a>
            <a href="https://www.linkedin.com/company/trygoai/" aria-label="TRYGO on LinkedIn">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round">
                <rect x="3" y="3" width="18" height="18" rx="2" />
                <path d="M8 11v5" />
                <path d="M8 8h.01" />
                <path d="M12 16v-3a2 2 0 1 1 4 0v3" />
              </svg>
            </a>
            <a href="#" aria-label="TRYGO on Instagram">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round">
                <rect x="3" y="3" width="18" height="18" rx="5" />
                <path d="M16 8h.01" />
                <circle cx="12" cy="12" r="3.5" />
              </svg>
            </a>
          </div>
        </div>

        <div class="footer-columns">
          <section>
            <h3>TOOLS</h3>
            <?php if ( has_nav_menu( 'footer_tools' ) ) : ?>
              <?php
                wp_nav_menu([
                  'theme_location' => 'footer_tools',
                  'menu_class'     => 'footer-list',
                  'menu_id'        => false,
                  'container'      => false,
                  'fallback_cb'    => '__return_false',
                  'depth'          => 1,
                ]);
              ?>
            <?php endif; ?>
          </section>
          <section>
            <h3>Features</h3>
            <?php if ( has_nav_menu( 'footer_features' ) ) : ?>
              <?php
                wp_nav_menu([
                  'theme_location' => 'footer_features',
                  'menu_class'     => 'footer-list',
                  'menu_id'        => false,
                  'container'      => false,
                  'fallback_cb'    => '__return_false',
                  'depth'          => 1,
                ]);
              ?>
            <?php endif; ?>
          </section>
          <section>
            <h3>For who</h3>
            <?php if ( has_nav_menu( 'footer_who' ) ) : ?>
              <?php
                wp_nav_menu([
                  'theme_location' => 'footer_who',
                  'menu_class'     => 'footer-list',
                  'menu_id'        => false,
                  'container'      => false,
                  'fallback_cb'    => '__return_false',
                  'depth'          => 1,
                ]);
              ?>
            <?php endif; ?>
          </section>
        </div>
      </div>
      <div class="footer-bottom">
        <?php if ( has_nav_menu( 'footer_legal' ) ) : ?>
          <nav class="footer-legal-nav" aria-label="Legal information">
            <?php
              wp_nav_menu([
                'theme_location' => 'footer_legal',
                'menu_class'     => 'footer-legal-list',
                'menu_id'        => false,
                'container'      => false,
                'fallback_cb'    => '__return_false',
                'depth'          => 1,
              ]);
            ?>
          </nav>
        <?php endif; ?>
        <p>© 2025 TRYGO. All rights reserved.</p>
      </div>
    </footer>

    <?php wp_footer(); ?>
  </body>
</html>
