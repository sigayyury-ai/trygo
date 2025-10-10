<?php
/**
 * Front page template.
 */

get_header();
?>

    <section class="hero" id="hero">
      <div class="hero-content">
        <h1>AI marketing co-pilot for solo founders and solopreneurs</h1>
        <p class="hero-lead">
          No marketing expertise or time? Use our AI co-pilot to create complete go-to-market strategies in minutes.
        </p>
        <ul class="hero-benefits">
          <li>Shortens the path from idea to first qualified leads</li>
          <li>Delivers strategy, plan, and marketing assets straight out of the box</li>
          <li>Helps launch digital products without hiring a marketing team</li>
        </ul>
        <div class="hero-actions">
          <a class="primary-btn" href="#pricing">Make your GTM in one click</a>
          <a class="secondary-btn" href="#unique-features">See all features</a>
        </div>
      </div>
    </section>

    <main>
      <section class="feature-grid" id="unique-features">
        <div class="section-header">
          <h2>Unique features</h2>
          <p>Everything you need to go from idea to launch — orchestrated by AI.</p>
        </div>
        <div class="cards">
          <?php
            $unique_features = new WP_Query(
              [
                'post_type'      => 'features',
                'posts_per_page' => 8,
                'post_status'    => 'publish',
                'orderby'        => 'menu_order',
                'order'          => 'ASC',
                'meta_query'     => [
                  'relation' => 'OR',
                  [
                    'key'     => 'add_to_front_page',
                    'value'   => '1',
                    'compare' => '=',
                  ],
                  [
                    'key'     => 'add_to_front_page',
                    'value'   => 'Yes',
                    'compare' => '=',
                  ],
                  [
                    'key'     => 'add_to_front_page',
                    'value'   => 'Yes',
                    'compare' => 'LIKE',
                  ],
                ],
              ]
            );
          ?>

          <?php if ( $unique_features->have_posts() ) : ?>
            <?php while ( $unique_features->have_posts() ) : $unique_features->the_post(); ?>
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
                $card_text = $short_description ? wp_strip_all_tags( $short_description ) : wp_trim_words( get_the_excerpt(), 24 );
              ?>
              <article class="feature-item">
                <a href="<?php the_permalink(); ?>">
                  <img src="<?php echo esc_url( $image_src ); ?>" alt="<?php echo esc_attr( $image_alt ); ?>" loading="lazy" />
                  <h3><?php the_title(); ?></h3>
                  <p><?php echo esc_html( $card_text ); ?></p>
                </a>
              </article>
            <?php endwhile; ?>
            <?php wp_reset_postdata(); ?>
          <?php else : ?>
            <article class="feature-item">
              <h3><?php esc_html_e( 'Highlight your best features', 'trygo' ); ?></h3>
              <p><?php esc_html_e( 'Mark features with “Add to front page” in the WordPress admin to showcase them here.', 'trygo' ); ?></p>
            </article>
          <?php endif; ?>
        </div>
      </section>

      <section class="inside" id="inside">
        <div class="section-header">
          <h2>What's inside</h2>
          <p>The AI co-pilot guides you through every step — from hypotheses to finished collateral.</p>
        </div>
        <div class="inside-shell">
          <figure class="inside-visual" aria-hidden="true">
            <img
              src="https://urock.pro/trygo/wp-content/uploads/2025/09/black-white-portrait-digital-nomads.jpg"
              alt="Workflow preview"
              loading="lazy"
              width="100%"
            />
          </figure>
          <div class="inside-copy">
            <h3>Your personal launch assistant</h3>
            <p>
              AI runs your project like an experienced product manager or marketer: shapes hypotheses, drafts content,
              analyses competitors, and assembles the GTM plan.
            </p>
          </div>
        </div>
      </section>

      <section class="how-it-works" id="how-it-works">
        <div class="section-header">
          <h2>How it works</h2>
          <p>Visualise the journey from idea to launch — all in one flow.</p>
        </div>
        <ol class="steps">
          <li>
            <h3>Define your idea</h3>
            <p>Describe your product, target market, and the problem you want to solve.</p>
          </li>
          <li>
            <h3>Build Lean Canvas</h3>
            <p>Structure the business model with Lean Canvas and AI tips on success metrics.</p>
          </li>
          <li>
            <h3>Define your ICP</h3>
            <p>Create detailed customer profiles and uncover pains, goals, and triggers.</p>
          </li>
          <li>
            <h3>Launch & iterate</h3>
            <p>Package your MVP, assemble the GTM strategy, and launch campaigns. Iterate using real feedback.</p>
          </li>
        </ol>
      </section>

      <section class="pricing" id="pricing">
        <div class="section-header">
          <h2>Pricing plans</h2>
          <p>Start for free and scale as your product grows.</p>
        </div>
        <div class="pricing-grid">
          <article class="pricing-card">
            <header>
              <h3>Freemium</h3>
              <p class="price">Free · 1 week trial</p>
            </header>
            <p class="plan-text">Extend to two weeks by sharing feedback.</p>
            <ul class="plan-points">
              <li>One active project and three hypotheses</li>
              <li>10 AI co-pilot requests</li>
            </ul>
            <div class="plan-tools" aria-label="Included tools">
              <span class="plan-tools__label">Tools:</span>
              Lean Canvas, ICP Manager, GTM Roadmap
            </div>
            <a class="primary-btn" href="https://dashboard.trygo.io/dashboard" target="_blank" rel="noopener noreferrer">Explore free tools</a>
          </article>
          <article class="pricing-card">
            <header>
              <h3>Starter</h3>
              <p class="price">$10 / month</p>
            </header>
            <p class="plan-text">Perfect for first products and first customers.</p>
            <ul class="plan-points">
              <li>One active project and five hypotheses</li>
              <li>50 AI co-pilot requests</li>
            </ul>
            <div class="plan-tools" aria-label="Included tools">
              <span class="plan-tools__label">Tools:</span>
              Lean Canvas, ICP Manager, Action Plan, Packaging Suite
            </div>
            <a class="primary-btn" href="https://dashboard.trygo.io/dashboard" target="_blank" rel="noopener noreferrer">Make your first hypothesis</a>
          </article>
          <article class="pricing-card accent">
            <header>
              <h3>Pro</h3>
              <p class="price">$20 / month</p>
            </header>
            <p class="plan-text">Built for serial entrepreneurs who launch, test, and scale multiple ideas in parallel.</p>
            <ul class="plan-points">
              <li>Unlimited projects and hypotheses</li>
              <li>Unlimited AI co-pilot requests</li>
            </ul>
            <div class="plan-tools" aria-label="Included tools">
              <span class="plan-tools__label">Tools:</span>
              Lean Canvas, ICP Manager, Research, Validation, Packaging, GTM Module, Action Plan
            </div>
            <a class="primary-btn" href="https://dashboard.trygo.io/dashboard" target="_blank" rel="noopener noreferrer">Unlock full platform</a>
          </article>
        </div>
      </section>

      <div id="case-studies" class="anchor-target" aria-hidden="true"></div>

      <section class="audience" id="who">
        <div class="section-header">
          <h2>Who it's for</h2>
          <p>The platform for everyone launching digital products — solo or with a team.</p>
        </div>
        <div class="audience-grid">
          <?php
            $audience_query = new WP_Query(
              [
                'post_type'      => 'job-titles',
                'posts_per_page' => -1,
                'post_status'    => 'publish',
                'orderby'        => 'menu_order',
                'order'          => 'ASC',
              ]
            );
          ?>

          <?php if ( $audience_query->have_posts() ) : ?>
            <?php while ( $audience_query->have_posts() ) : $audience_query->the_post(); ?>
              <?php
                $image_id  = get_post_thumbnail_id();
                $image_src = $image_id ? wp_get_attachment_image_url( $image_id, 'large' ) : 'https://images.unsplash.com/photo-1522202176988-66273c2fd55f?auto=format&fit=crop&w=1200&q=80';
                $image_alt = $image_id ? get_post_meta( $image_id, '_wp_attachment_image_alt', true ) : '';
                if ( ! $image_alt ) {
                    $image_alt = get_the_title();
                }

                $custom_link = function_exists( 'get_field' ) ? get_field( 'detail_link', get_the_ID() ) : '';
                $card_link   = $custom_link ? $custom_link : get_permalink();

                $short_description = function_exists( 'get_field' ) ? get_field( 'short_description', get_the_ID() ) : '';
                if ( ! $short_description ) {
                    $short_description = get_post_meta( get_the_ID(), 'short_description', true );
                }
                $card_text = $short_description ? wp_strip_all_tags( $short_description ) : wp_trim_words( get_the_excerpt(), 22 );
              ?>
              <a class="audience-card" href="<?php echo esc_url( $card_link ); ?>">
                <img src="<?php echo esc_url( $image_src ); ?>" alt="<?php echo esc_attr( $image_alt ); ?>" loading="lazy" />
                <h3><?php the_title(); ?></h3>
                <p><?php echo esc_html( $card_text ); ?></p>
              </a>
            <?php endwhile; ?>
            <?php wp_reset_postdata(); ?>
          <?php else : ?>
            <div class="audience-card audience-card--empty">
              <h3><?php esc_html_e( 'Add job titles', 'trygo' ); ?></h3>
              <p><?php esc_html_e( 'Create job title entries in WordPress to showcase your key audiences here.', 'trygo' ); ?></p>
            </div>
          <?php endif; ?>
        </div>
      </section>

      <section class="faq" id="faq">
        <div class="section-header">
          <h2>FAQ</h2>
          <p>Answers to the most common questions about TRYGO.</p>
        </div>
        <?php
          $faq_query = new WP_Query(
            [
              'post_type'      => 'faq',
              'posts_per_page' => -1,
              'post_status'    => 'publish',
              'orderby'        => 'menu_order',
              'order'          => 'ASC',
              'tax_query'      => [
                [
                  'taxonomy' => 'category',
                  'field'    => 'slug',
                  'terms'    => 'faq-general',
                ],
              ],
            ]
          );
        ?>

        <div class="faq-accordion" data-accordion>
          <?php if ( $faq_query->have_posts() ) : ?>
            <?php $faq_index = 0; ?>
            <?php while ( $faq_query->have_posts() ) : $faq_query->the_post(); ?>
              <?php
                $is_open = 0 === $faq_index ? ' is-open' : '';
                $answer  = apply_filters( 'the_content', get_the_content() );
              ?>
              <div class="faq-item<?php echo esc_attr( $is_open ); ?>" data-accordion-item>
                <button class="faq-trigger" type="button" data-accordion-trigger>
                  <span><?php the_title(); ?></span>
                </button>
                <div class="faq-content" data-accordion-content>
                  <?php echo $answer; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                </div>
              </div>
              <?php $faq_index++; ?>
            <?php endwhile; ?>
            <?php wp_reset_postdata(); ?>
          <?php else : ?>
            <div class="faq-item is-open" data-accordion-item>
              <div class="faq-content" data-accordion-content>
                <p><?php esc_html_e( 'Add FAQ entries in the WordPress admin to populate this section.', 'trygo' ); ?></p>
              </div>
            </div>
          <?php endif; ?>
        </div>
      </section>

      <?php get_template_part('cta-section'); ?>
    </main>

<?php get_footer(); ?>
