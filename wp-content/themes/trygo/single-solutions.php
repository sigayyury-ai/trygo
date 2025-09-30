<?php
/**
 * Single solutions template.
 */

global $post;

get_header();

$posts_page_id        = (int) get_option( 'page_for_posts' );
$feature_archive_link = get_post_type_archive_link( 'solutions' );
$features_page        = get_page_by_path( 'solutions' );
$features_page_link   = $features_page ? get_permalink( $features_page ) : '';

$back_link = $feature_archive_link ?: ( $features_page_link ?: ( $posts_page_id ? get_permalink( $posts_page_id ) : home_url( '/' ) ) );
?>

<?php if ( have_posts() ) : ?>
  <?php while ( have_posts() ) : the_post(); ?>
    <main aria-live="polite">
      <a class="back-link back-link--compact" href="<?php echo esc_url( $back_link ); ?>">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" aria-hidden="true">
          <path d="M20 12H4" stroke-linecap="round" stroke-linejoin="round" />
          <path d="M11 5l-7 7 7 7" stroke-linecap="round" stroke-linejoin="round" />
        </svg>
        <?php esc_html_e( 'Back to all solutions', 'trygo' ); ?>
      </a>

      <section class="feature-hero">
        <div class="feature-hero__headline">
          <p class="eyebrow"><?php esc_html_e( 'TRYGO Solutions', 'trygo' ); ?></p>
          <h1><?php the_title(); ?></h1>
          <?php if ( has_excerpt() ) : ?>
            <p class="feature-hero__tagline"><?php echo esc_html( get_the_excerpt() ); ?></p>
          <?php endif; ?>
          <a class="feature-hero__cta" href="<?php echo esc_url( home_url( '/#pricing' ) ); ?>" data-modal-trigger="signup">
            <?php esc_html_e( 'Try this solutions', 'trygo' ); ?>
          </a>
          <?php
            $terms = get_the_terms( get_the_ID(), 'feature_type' );
            if ( $terms && ! is_wp_error( $terms ) ) :
          ?>
            <div class="tags" aria-label="<?php esc_attr_e( 'Feature categories', 'trygo' ); ?>">
              <?php foreach ( $terms as $term ) : ?>
                <span class="tag"><?php echo esc_html( $term->name ); ?></span>
              <?php endforeach; ?>
            </div>
          <?php endif; ?>
        </div>
        <div class="feature-hero__media">
          <?php if ( has_post_thumbnail() ) : ?>
            <?php the_post_thumbnail( 'large', [ 'loading' => 'lazy', 'alt' => esc_attr( get_the_title() ) ] ); ?>
          <?php else : ?>
            <div class="feature-hero__media--fallback" aria-hidden="true">
              <span><?php esc_html_e( 'TRYGO solutions', 'trygo' ); ?></span>
            </div>
          <?php endif; ?>
        </div>
      </section>

      <?php
        $short_description  = function_exists( 'get_field' ) ? get_field( 'short_description', get_the_ID() ) : '';
        if ( ! $short_description ) {
            $short_description = get_post_meta( get_the_ID(), 'short_description', true );
        }

        $problem = function_exists( 'get_field' ) ? get_field( 'problem', get_the_ID() ) : '';
        if ( ! $problem_raw ) {
            $problem_raw = get_post_meta( get_the_ID(), 'problem', true );
        }

        if ( is_array( $problem_raw ) ) {
            $problem = array_filter( array_map( 'trim', $problem ) );
        } else {
            $problem = array_filter( array_map( 'trim', preg_split( '/\r?\n/', (string) $problem_raw ) ) );
        }

        
      ?>


      <?php if ( $short_description || $problem ) : ?>
        <section class="feature-summary">
          <?php if ( $short_description ) : ?>
            <div class="feature-summary__about">
              <h2><?php esc_html_e( 'Short description', 'trygo' ); ?></h2>
              <p><?php echo esc_html( wp_strip_all_tags( $short_description ) ); ?></p>
            </div>
          <?php endif; ?>

          <?php if ( $problem ) : ?>
            <div class="feature-summary__benefits">
              <h3><?php esc_html_e( 'Problem', 'trygo' ); ?></h3>
              <div class="problem">
                <?php foreach ( $problem as $item ) : ?>
                  <?php echo esc_html( wp_strip_all_tags( $item ) ); ?>
                <?php endforeach; ?>
              </div>
            </div>
          <?php endif; ?>
        </section>
      <?php endif; ?>


      <?php
// Блок RELATED FEATURES для single-solution.php
$features = get_field('related_features'); // ACF Relationship

if ($features && is_array($features)) :

  // Нормализуем к ID и убираем дубли
  $feature_ids = array_values(array_unique(array_map(function($item){
    return is_object($item) ? (int)$item->ID : (int)$item;
  }, $features)));

  if (!empty($feature_ids)) : ?>
    <section class="feature-grid" id="unique-features">
      <div class="section-header">
        <h2>Related features</h2>
      </div>

      <div class="cards grid">
        <?php foreach ($feature_ids as $fid) :
          $title     = get_the_title($fid);
          $permalink = get_permalink($fid);

          // Картинка (thumbnail)
          $thumb_url = get_the_post_thumbnail_url($fid, 'large');
          $thumb_alt = esc_attr($title);

          // Описание: ACF short_summary → excerpt → trimmed content
          $desc = get_field('short_summary', $fid);
          if (!$desc) {
            if (has_excerpt($fid)) {
              $desc = get_the_excerpt($fid);
            } else {
              $post_obj = get_post($fid);
              $desc = wp_trim_words(
                wp_strip_all_tags($post_obj ? $post_obj->post_content : ''),
                28,
                '…'
              );
            }
          }
          ?>
          <article class="feature-item">
            <a href="<?php echo esc_url($permalink); ?>">
              <?php if ($thumb_url): ?>
                <img src="<?php echo esc_url($thumb_url); ?>" alt="<?php echo $thumb_alt; ?>" loading="lazy">
              <?php endif; ?>
              <h3><?php echo esc_html($title); ?></h3>
              <?php if (!empty($desc)): ?>
                <p><?php echo esc_html($desc); ?></p>
              <?php endif; ?>
            </a>
          </article>
        <?php endforeach; ?>
      </div>
    </section>
  <?php endif; // !empty
endif; // $features
?>


      <section class="feature-content">
        <div class="rich-content">
          <?php the_content(); ?>
        </div>
      </section>

      <section class="cta" aria-labelledby="features-cta-title">
        <div class="cta-inner">
          <div class="cta-media">
            <img
              src="<?php echo home_url(); ?>/wp-content/uploads/2025/09/glowing-spider-web-dark-abstract-backdrop-generated-by-ai.jpg"
              alt="Team collaborating on launch collateral"
              loading="lazy"
            />
          </div>
          <div class="cta-content">
            <p class="eyebrow">Ready to launch</p>
            <h2 id="features-cta-title">Launch your GTM strategy in one click</h2>
            <p>Join TRYGO to capture customer insight, validate hypotheses, and package your product with AI co-pilot support.</p>
            <a class="cta-button" href="./product.html#pricing" role="button" data-modal-trigger="signup">Start with Freemium</a>
          </div>
        </div>
      </section>

      
    </main>
  <?php endwhile; ?>
<?php endif; ?>

<?php
get_footer();
