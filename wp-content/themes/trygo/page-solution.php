<?php
/**
 * Template Name: Solutions page
 */

get_header();
?>
<main>
<section class="solutions-hero" id="hero">
  <div class="features-hero__content">
  <p class="eyebrow">TRYGO solutions</p>
  <h1>Four steps from idea to first users</h1>
  <p class="lead">
    Research, Validation, Strategy, and User Acquisition — a complete path for solo founders.
    Each solution combines AI-driven insights, structured workflows, and ready-to-use deliverables 
    so you can move from assumptions to traction with clarity and speed.
  </p>
</div>
</section>

<section class="solutions">
  <div class="category-grid">
     <?php
            $features_query = new WP_Query(
              [
                'post_type'      => 'solutions',
                'posts_per_page' => -1,
                'post_status'    => 'publish',
                'orderby'        => 'menu_order',
                'order'          => 'ASC',
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

<?php if ( have_posts() ) : ?>
  <?php while ( have_posts() ) : the_post(); ?>
    <?php if ( get_the_content() ) : ?>
      <section class="page-content">
        <div class="container">
          <div class="content-wrapper">
            <?php the_content(); ?>
          </div>
        </div>
      </section>
    <?php endif; ?>
  <?php endwhile; ?>
<?php endif; ?>
<?php get_template_part('cta-section'); ?>
</main>
<?php get_footer();
