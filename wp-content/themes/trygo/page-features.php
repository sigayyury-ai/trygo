<?php
/**
 * Template Name: Features Landing
 */

get_header();
?>

    <main>
      <section class="features-hero" id="hero">
        <div class="features-hero__content">
          <p class="eyebrow">TRYGO feature stack</p>
          <h1>All-in-one GTM workspace powered by AI</h1>
          <p class="lead">
            From onboarding to launch, TRYGO combines guided workflows, research automation, and packaging tools to help product teams ship faster.
          </p>
        </div>
      </section>

      <section class="feature-category" id="onboarding">
        <div class="section-header">
          <h2>Onboarding</h2>
          <p>Kick-start every project with structured guidance and personalised flows.</p>
        </div>
        <div class="category-grid">
          <?php
            $features_query = new WP_Query(
              [
                'post_type'      => 'features',
                'posts_per_page' => -1,
                'post_status'    => 'publish',
                'orderby'        => 'menu_order',
                'order'          => 'ASC',
                'tax_query'      => [
                  [
                    'taxonomy' => 'category',
                    'field'    => 'slug',
                    'terms'    => 'onboarding',
                  ],
                ],
              ]
            );
          ?>

          <?php if ( $features_query->have_posts() ) : ?>
            <?php while ( $features_query->have_posts() ) : $features_query->the_post(); ?>
              <?php
                $image_id  = get_post_thumbnail_id();
                $image_src = $image_id ? wp_get_attachment_image_url( $image_id, 'medium_large' ) : 'https://images.unsplash.com/photo-1545239351-1141bd82e8a6?auto=format&fit=crop&w=900&q=80';
                $image_alt = $image_id ? get_post_meta( $image_id, '_wp_attachment_image_alt', true ) : '';

                if ( ! $image_alt ) {
                    $image_alt = get_the_title();
                }
              ?>
              <?php
                // short_description может быть добавлено через ACF, поэтому пробуем сначала get_field().
                $short_description = function_exists( 'get_field' ) ? get_field( 'short_description', get_the_ID() ) : '';

                if ( ! $short_description ) {
                    $short_description = get_post_meta( get_the_ID(), 'short_description', true );
                }

                $card_text = $short_description ? wp_strip_all_tags( $short_description ) : wp_trim_words( get_the_excerpt(), 26 );
              ?>
              <a class="category-card" href="<?php the_permalink(); ?>">
                <img src="<?php echo esc_url( $image_src ); ?>" alt="<?php echo esc_attr( $image_alt ); ?>" loading="lazy" />
                <div class="category-card__body">
                  <h3><?php the_title(); ?></h3>
                  <p><?php echo esc_html( $card_text ); ?></p>
                </div>
              </a>
            <?php endwhile; ?>
            <?php wp_reset_postdata(); ?>
          <?php else : ?>
            <div class="category-card empty-card">
              <div class="category-card__body">
                <h3><?php esc_html_e( 'Content coming soon', 'trygo' ); ?></h3>
                <p><?php esc_html_e( 'Add onboarding features in the WordPress admin to populate this section.', 'trygo' ); ?></p>
              </div>
            </div>
          <?php endif; ?>
        </div>
      </section>

      <section class="feature-category" id="research">
        <div class="section-header">
          <h2>Market research</h2>
          <p>Discover audiences, competitors, and whitespace without leaving your workspace.</p>
        </div>
        <div class="category-grid">
          <?php
            $market_query = new WP_Query(
              [
                'post_type'      => 'features',
                'posts_per_page' => -1,
                'post_status'    => 'publish',
                'orderby'        => 'menu_order',
                'order'          => 'ASC',
                'tax_query'      => [
                  [
                    'taxonomy' => 'category',
                    'field'    => 'slug',
                    'terms'    => 'market-research',
                  ],
                ],
              ]
            );
          ?>

          <?php if ( $market_query->have_posts() ) : ?>
            <?php while ( $market_query->have_posts() ) : $market_query->the_post(); ?>
              <?php
                $image_id  = get_post_thumbnail_id();
                $image_src = $image_id ? wp_get_attachment_image_url( $image_id, 'medium_large' ) : 'https://images.unsplash.com/photo-1545239351-1141bd82e8a6?auto=format&fit=crop&w=900&q=80';
                $image_alt = $image_id ? get_post_meta( $image_id, '_wp_attachment_image_alt', true ) : '';

                if ( ! $image_alt ) {
                    $image_alt = get_the_title();
                }

                $short_description = function_exists( 'get_field' ) ? get_field( 'short_description', get_the_ID() ) : '';
                if ( ! $short_description ) {
                    $short_description = get_post_meta( get_the_ID(), 'short_description', true );
                }
                $card_text = $short_description ? wp_strip_all_tags( $short_description ) : wp_trim_words( get_the_excerpt(), 26 );
              ?>
              <a class="category-card" href="<?php the_permalink(); ?>">
                <img src="<?php echo esc_url( $image_src ); ?>" alt="<?php echo esc_attr( $image_alt ); ?>" loading="lazy" />
                <div class="category-card__body">
                  <h3><?php the_title(); ?></h3>
                  <p><?php echo esc_html( $card_text ); ?></p>
                </div>
              </a>
            <?php endwhile; ?>
            <?php wp_reset_postdata(); ?>
          <?php else : ?>
            <div class="category-card empty-card">
              <div class="category-card__body">
                <h3><?php esc_html_e( 'Content coming soon', 'trygo' ); ?></h3>
                <p><?php esc_html_e( 'Add market research features in the WordPress admin to populate this section.', 'trygo' ); ?></p>
              </div>
            </div>
          <?php endif; ?>
        </div>
      </section>

      <section class="feature-category" id="validation">
        <div class="section-header">
          <h2>Validation</h2>
          <p>Stress-test ideas with structured hypotheses, fast experiments, and customer feedback.</p>
        </div>
        <div class="category-grid">
          <?php
            $validation_query = new WP_Query(
              [
                'post_type'      => 'features',
                'posts_per_page' => -1,
                'post_status'    => 'publish',
                'orderby'        => 'menu_order',
                'order'          => 'ASC',
                'tax_query'      => [
                  [
                    'taxonomy' => 'category',
                    'field'    => 'slug',
                    'terms'    => 'validation',
                  ],
                ],
              ]
            );
          ?>

          <?php if ( $validation_query->have_posts() ) : ?>
            <?php while ( $validation_query->have_posts() ) : $validation_query->the_post(); ?>
              <?php
                $image_id  = get_post_thumbnail_id();
                $image_src = $image_id ? wp_get_attachment_image_url( $image_id, 'medium_large' ) : 'https://images.unsplash.com/photo-1545239351-1141bd82e8a6?auto=format&fit=crop&w=900&q=80';
                $image_alt = $image_id ? get_post_meta( $image_id, '_wp_attachment_image_alt', true ) : '';
                if ( ! $image_alt ) {
                    $image_alt = get_the_title();
                }

                $short_description = function_exists( 'get_field' ) ? get_field( 'short_description', get_the_ID() ) : '';
                if ( ! $short_description ) {
                    $short_description = get_post_meta( get_the_ID(), 'short_description', true );
                }
                $card_text = $short_description ? wp_strip_all_tags( $short_description ) : wp_trim_words( get_the_excerpt(), 26 );
              ?>
              <a class="category-card" href="<?php the_permalink(); ?>">
                <img src="<?php echo esc_url( $image_src ); ?>" alt="<?php echo esc_attr( $image_alt ); ?>" loading="lazy" />
                <div class="category-card__body">
                  <h3><?php the_title(); ?></h3>
                  <p><?php echo esc_html( $card_text ); ?></p>
                </div>
              </a>
            <?php endwhile; ?>
            <?php wp_reset_postdata(); ?>
          <?php else : ?>
            <div class="category-card empty-card">
              <div class="category-card__body">
                <h3><?php esc_html_e( 'Content coming soon', 'trygo' ); ?></h3>
                <p><?php esc_html_e( 'Add validation features in the WordPress admin to populate this section.', 'trygo' ); ?></p>
              </div>
            </div>
          <?php endif; ?>
        </div>
      </section>

      <section class="feature-category" id="gtm">
        <div class="section-header">
          <h2>Go-to-market</h2>
          <p>Automate strategy, channel planning, and campaign execution across the launch funnel.</p>
        </div>
        <div class="category-grid">
          <?php
            $gtm_query = new WP_Query(
              [
                'post_type'      => 'features',
                'posts_per_page' => -1,
                'post_status'    => 'publish',
                'orderby'        => 'menu_order',
                'order'          => 'ASC',
                'tax_query'      => [
                  [
                    'taxonomy' => 'category',
                    'field'    => 'slug',
                    'terms'    => 'go-to-market',
                  ],
                ],
              ]
            );
          ?>

          <?php if ( $gtm_query->have_posts() ) : ?>
            <?php while ( $gtm_query->have_posts() ) : $gtm_query->the_post(); ?>
              <?php
                $image_id  = get_post_thumbnail_id();
                $image_src = $image_id ? wp_get_attachment_image_url( $image_id, 'medium_large' ) : 'https://images.unsplash.com/photo-1545239351-1141bd82e8a6?auto=format&fit=crop&w=900&q=80';
                $image_alt = $image_id ? get_post_meta( $image_id, '_wp_attachment_image_alt', true ) : '';
                if ( ! $image_alt ) {
                    $image_alt = get_the_title();
                }

                $short_description = function_exists( 'get_field' ) ? get_field( 'short_description', get_the_ID() ) : '';
                if ( ! $short_description ) {
                    $short_description = get_post_meta( get_the_ID(), 'short_description', true );
                }
                $card_text = $short_description ? wp_strip_all_tags( $short_description ) : wp_trim_words( get_the_excerpt(), 26 );
              ?>
              <a class="category-card" href="<?php the_permalink(); ?>">
                <img src="<?php echo esc_url( $image_src ); ?>" alt="<?php echo esc_attr( $image_alt ); ?>" loading="lazy" />
                <div class="category-card__body">
                  <h3><?php the_title(); ?></h3>
                  <p><?php echo esc_html( $card_text ); ?></p>
                </div>
              </a>
            <?php endwhile; ?>
            <?php wp_reset_postdata(); ?>
          <?php else : ?>
            <div class="category-card empty-card">
              <div class="category-card__body">
                <h3><?php esc_html_e( 'Content coming soon', 'trygo' ); ?></h3>
                <p><?php esc_html_e( 'Add go-to-market features in the WordPress admin to populate this section.', 'trygo' ); ?></p>
              </div>
            </div>
          <?php endif; ?>
        </div>
      </section>

      <section class="feature-category" id="packing">
        <div class="section-header">
          <h2>Packing</h2>
          <p>Package your product story with ready-to-use collateral and conversion materials.</p>
        </div>
        <div class="category-grid">
          <?php
            $packing_query = new WP_Query(
              [
                'post_type'      => 'features',
                'posts_per_page' => -1,
                'post_status'    => 'publish',
                'orderby'        => 'menu_order',
                'order'          => 'ASC',
                'tax_query'      => [
                  [
                    'taxonomy' => 'category',
                    'field'    => 'slug',
                    'terms'    => 'packing',
                  ],
                ],
              ]
            );
          ?>

          <?php if ( $packing_query->have_posts() ) : ?>
            <?php while ( $packing_query->have_posts() ) : $packing_query->the_post(); ?>
              <?php
                $image_id  = get_post_thumbnail_id();
                $image_src = $image_id ? wp_get_attachment_image_url( $image_id, 'medium_large' ) : 'https://images.unsplash.com/photo-1545239351-1141bd82e8a6?auto=format&fit=crop&w=900&q=80';
                $image_alt = $image_id ? get_post_meta( $image_id, '_wp_attachment_image_alt', true ) : '';
                if ( ! $image_alt ) {
                    $image_alt = get_the_title();
                }

                $short_description = function_exists( 'get_field' ) ? get_field( 'short_description', get_the_ID() ) : '';
                if ( ! $short_description ) {
                    $short_description = get_post_meta( get_the_ID(), 'short_description', true );
                }
                $card_text = $short_description ? wp_strip_all_tags( $short_description ) : wp_trim_words( get_the_excerpt(), 26 );
              ?>
              <a class="category-card" href="<?php the_permalink(); ?>">
                <img src="<?php echo esc_url( $image_src ); ?>" alt="<?php echo esc_attr( $image_alt ); ?>" loading="lazy" />
                <div class="category-card__body">
                  <h3><?php the_title(); ?></h3>
                  <p><?php echo esc_html( $card_text ); ?></p>
                </div>
              </a>
            <?php endwhile; ?>
            <?php wp_reset_postdata(); ?>
          <?php else : ?>
            <div class="category-card empty-card">
              <div class="category-card__body">
                <h3><?php esc_html_e( 'Content coming soon', 'trygo' ); ?></h3>
                <p><?php esc_html_e( 'Add packing features in the WordPress admin to populate this section.', 'trygo' ); ?></p>
              </div>
            </div>
          <?php endif; ?>
        </div>
      </section>

      <?php get_template_part('cta-section'); ?>
    </main>

<?php
get_footer();
