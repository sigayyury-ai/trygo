<?php
/**
 * Single feature template.
 */

global $post;

get_header();

$posts_page_id        = (int) get_option( 'page_for_posts' );
$feature_archive_link = get_post_type_archive_link( 'features' );
$features_page        = get_page_by_path( 'features' );
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
        <?php esc_html_e( 'Back to all features', 'trygo' ); ?>
      </a>

      <section class="feature-hero">
        <div class="feature-hero__headline">
          <p class="eyebrow"><?php esc_html_e( 'TRYGO Feature', 'trygo' ); ?></p>
          <h1><?php the_title(); ?></h1>
          <?php if ( has_excerpt() ) : ?>
            <p class="feature-hero__tagline"><?php echo esc_html( get_the_excerpt() ); ?></p>
          <?php endif; ?>
          <a class="feature-hero__cta" href="https://dashboard.trygo.io/dashboard" target="_blank" rel="noopener noreferrer">
            <?php esc_html_e( 'Try this feature', 'trygo' ); ?>
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
              <span><?php esc_html_e( 'TRYGO Feature', 'trygo' ); ?></span>
            </div>
          <?php endif; ?>
        </div>
      </section>

      <?php
        $what_it_does  = function_exists( 'get_field' ) ? get_field( 'what_it_does', get_the_ID() ) : '';
        if ( ! $what_it_does ) {
            $what_it_does = get_post_meta( get_the_ID(), 'what_it_does', true );
        }

        $what_you_get_raw = function_exists( 'get_field' ) ? get_field( 'what_you_get', get_the_ID() ) : '';
        if ( ! $what_you_get_raw ) {
            $what_you_get_raw = get_post_meta( get_the_ID(), 'what_you_get', true );
        }

        if ( is_array( $what_you_get_raw ) ) {
            $what_you_get = array_filter( array_map( 'trim', $what_you_get_raw ) );
        } else {
            $what_you_get = array_filter( array_map( 'trim', preg_split( '/\r?\n/', (string) $what_you_get_raw ) ) );
        }

        $feature_video = function_exists( 'get_field' ) ? get_field( 'feature_video', get_the_ID() ) : '';
        if ( ! $feature_video ) {
            $feature_video = get_post_meta( get_the_ID(), 'feature_video', true );
        }

        $feature_video = trim( (string) $feature_video );

        $feature_video_embed = '';
        if ( $feature_video ) {
            $video_url = $feature_video;

            if ( strpos( $video_url, 'youtube.com/watch' ) !== false ) {
                $video_url = preg_replace( '/watch\?v=([^&]+)/', 'embed/$1', $video_url );
            } elseif ( strpos( $video_url, 'youtu.be/' ) !== false ) {
                $video_url = preg_replace( '#https?://youtu\.be/([^?]+).*#', 'https://www.youtube.com/embed/$1', $video_url );
            }

            $feature_video_embed = esc_url( $video_url );
        }
      ?>

      <?php if ( $feature_video_embed ) : ?>
        <section class="feature-video" aria-labelledby="feature-video-title">
          <div class="feature-video__inner">
            <h2 id="feature-video-title"><?php esc_html_e( 'See how it works', 'trygo' ); ?></h2>
            <div class="feature-video__frame">
              <iframe src="<?php echo esc_url( $feature_video_embed ); ?>" title="<?php echo esc_attr( get_the_title() ); ?>" allowfullscreen loading="lazy"></iframe>
            </div>
          </div>
        </section>
      <?php endif; ?>

      <?php if ( $what_it_does || $what_you_get ) : ?>
        <section class="feature-summary">
          <?php if ( $what_it_does ) : ?>
            <div class="feature-summary__about">
              <h2><?php esc_html_e( 'What it does', 'trygo' ); ?></h2>
              <p><?php echo esc_html( wp_strip_all_tags( $what_it_does ) ); ?></p>
            </div>
          <?php endif; ?>

          <?php if ( $what_you_get ) : ?>
            <div class="feature-summary__benefits">
              <h3><?php esc_html_e( 'What you get', 'trygo' ); ?></h3>
              <ul>
                <?php foreach ( $what_you_get as $item ) : ?>
                  <li><?php echo esc_html( wp_strip_all_tags( $item ) ); ?></li>
                <?php endforeach; ?>
              </ul>
            </div>
          <?php endif; ?>
        </section>
      <?php endif; ?>

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
            <a class="cta-button" href="https://dashboard.trygo.io/dashboard" target="_blank" rel="noopener noreferrer" role="button">Start with Freemium</a>
          </div>
        </div>
      </section>

      
    </main>
  <?php endwhile; ?>
<?php endif; ?>

<?php
get_footer();
