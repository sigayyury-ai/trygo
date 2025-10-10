<?php
/**
 * Single post template.
 */

global $post;

get_header();

$posts_page_id = (int) get_option( 'page_for_posts' );
$back_link     = $posts_page_id ? get_permalink( $posts_page_id ) : home_url( '/' );
?>

<?php if ( have_posts() ) : ?>
  <?php while ( have_posts() ) : the_post(); ?>
    <main id="article-root" aria-live="polite">
      <a class="back-link" href="<?php echo home_url(); ?>/blog/">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" aria-hidden="true">
          <path d="M20 12H4" stroke-linecap="round" stroke-linejoin="round" />
          <path d="M11 5l-7 7 7 7" stroke-linecap="round" stroke-linejoin="round" />
        </svg>
        <?php esc_html_e( 'Back to all articles', 'trygo' ); ?>
      </a>

      <div class="article-layout">
        <article class="post-detail">
          <?php if ( has_post_thumbnail() ) : ?>
            <?php the_post_thumbnail( 'large', [ 'class' => 'hero', 'loading' => 'lazy', 'alt' => esc_attr( get_the_title() ) ] ); ?>
          <?php else : ?>
            <img class="hero" src="https://images.unsplash.com/photo-1545239351-1141bd82e8a6?auto=format&fit=crop&w=1200&q=80" alt="<?php echo esc_attr( get_the_title() ); ?>" loading="lazy" />
          <?php endif; ?>
          <div class="body">
            <div class="meta">
              <span><?php echo esc_html( get_the_date( 'F j, Y' ) ); ?></span>
              <span><?php echo esc_html( trygo_estimated_reading_time( get_the_ID() ) ); ?></span>
            </div>
            <h1><?php the_title(); ?></h1>
            <?php $post_tags = get_the_terms( get_the_ID(), 'post_tag' ); ?>
            <?php if ( $post_tags && ! is_wp_error( $post_tags ) ) : ?>
              <div class="tags" aria-label="<?php esc_attr_e( 'Tags', 'trygo' ); ?>">
                <?php foreach ( $post_tags as $tag ) : ?>
                  <span class="tag"><?php echo esc_html( $tag->name ); ?></span>
                <?php endforeach; ?>
              </div>
            <?php endif; ?>
            <div class="rich-content">
              <?php the_content(); ?>
            </div>
          </div>
        </article>

        <aside class="article-sidebar">
          <div class="sidebar-card">
            <p class="eyebrow"><?php esc_html_e( 'Launch faster', 'trygo' ); ?></p>
            <h2><?php esc_html_e( 'Make your GTM strategy in one click', 'trygo' ); ?></h2>
            <p><?php esc_html_e( 'TRYGO assembles your go-to-market roadmap with campaigns, hypotheses, and messaging crafted by AI.', 'trygo' ); ?></p>
            <ul class="sidebar-list">
              <li><?php esc_html_e( 'AI-built Lean Canvas & ICPs', 'trygo' ); ?></li>
              <li><?php esc_html_e( 'Actionable GTM roadmap', 'trygo' ); ?></li>
              <li><?php esc_html_e( 'Launch content and analytics', 'trygo' ); ?></li>
            </ul>
            <a class="sidebar-button" href="<?php echo esc_url( home_url( '/#pricing' ) ); ?>" role="button" data-modal-trigger="signup">
              <?php esc_html_e( 'Start with Freemium', 'trygo' ); ?>
            </a>
            <p class="sidebar-footnote"><?php esc_html_e( '7-day trial · No credit card required', 'trygo' ); ?></p>
          </div>
        </aside>
      </div>
    </main>
  <?php endwhile; ?>
<?php endif; ?>

<?php
get_footer();
