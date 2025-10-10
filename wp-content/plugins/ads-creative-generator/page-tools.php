<?php
/**
 * Template for Tools pages (e.g., Ads Creative Generator)
 * Template Name: Tools Page
 */

global $post;

get_header();

$posts_page_id = (int) get_option( 'page_for_posts' );
$back_link     = $posts_page_id ? get_permalink( $posts_page_id ) : home_url( '/' );
?>

<?php if ( have_posts() ) : ?>
  <?php while ( have_posts() ) : the_post(); ?>
    <main id="tools-root" aria-live="polite">
      <a class="back-link" href="<?php echo home_url(); ?>/">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" aria-hidden="true">
          <path d="M20 12H4" stroke-linecap="round" stroke-linejoin="round" />
          <path d="M11 5l-7 7 7 7" stroke-linecap="round" stroke-linejoin="round" />
        </svg>
        <?php esc_html_e( 'Back to Home', 'trygo' ); ?>
      </a>

      <div class="article-layout">
        <article class="post-detail">
          <div class="body">
            <h1><?php the_title(); ?></h1>
            
            <?php 
            // Get custom fields for tool information
            $tool_description = get_post_meta( get_the_ID(), 'tool_description', true );
            $tool_features = get_post_meta( get_the_ID(), 'tool_features', true );
            $tool_shortcode = get_post_meta( get_the_ID(), 'tool_shortcode', true );
            ?>
            
            <?php if ( $tool_description ) : ?>
              <div class="tool-description">
                <p class="lead"><?php echo esc_html( $tool_description ); ?></p>
              </div>
            <?php endif; ?>
            
            <?php if ( $tool_features ) : ?>
              <div class="tool-features">
                <h3><?php esc_html_e( 'Key Features', 'trygo' ); ?></h3>
                <ul class="features-list">
                  <?php 
                  $features = explode( "\n", $tool_features );
                  foreach ( $features as $feature ) :
                    $feature = trim( $feature );
                    if ( ! empty( $feature ) ) :
                  ?>
                    <li><?php echo esc_html( $feature ); ?></li>
                  <?php 
                    endif;
                  endforeach; 
                  ?>
                </ul>
              </div>
            <?php endif; ?>
            
            <div class="rich-content">
              <?php the_content(); ?>
            </div>
            
            <?php if ( $tool_shortcode ) : ?>
              <div class="tool-interface">
                <h3><?php esc_html_e( 'Try the Tool', 'trygo' ); ?></h3>
                <div class="tool-container">
                  <?php echo do_shortcode( $tool_shortcode ); ?>
                </div>
              </div>
            <?php endif; ?>
          </div>
        </article>

        <aside class="article-sidebar">
          <div class="sidebar-card">
            <p class="eyebrow"><?php esc_html_e( 'More AI Tools', 'trygo' ); ?></p>
            <h2><?php esc_html_e( 'Explore Our AI Toolkit', 'trygo' ); ?></h2>
            <p><?php esc_html_e( 'Discover more powerful AI tools to accelerate your business growth and marketing efforts.', 'trygo' ); ?></p>
            <ul class="sidebar-list">
              <li><?php esc_html_e( 'Business Analysis Tools', 'trygo' ); ?></li>
              <li><?php esc_html_e( 'Content Generation', 'trygo' ); ?></li>
              <li><?php esc_html_e( 'Marketing Automation', 'trygo' ); ?></li>
            </ul>
            <a class="sidebar-button" href="<?php echo esc_url( home_url( '/tools/' ) ); ?>" role="button">
              <?php esc_html_e( 'View All Tools', 'trygo' ); ?>
            </a>
            <p class="sidebar-footnote"><?php esc_html_e( 'Free to use · No registration required', 'trygo' ); ?></p>
          </div>
          
          <div class="sidebar-card">
            <p class="eyebrow"><?php esc_html_e( 'Need Help?', 'trygo' ); ?></p>
            <h2><?php esc_html_e( 'Get Support', 'trygo' ); ?></h2>
            <p><?php esc_html_e( 'Having trouble with this tool? Our support team is here to help you get the most out of our AI tools.', 'trygo' ); ?></p>
            <a class="sidebar-button" href="<?php echo esc_url( home_url( '/contact/' ) ); ?>" role="button">
              <?php esc_html_e( 'Contact Support', 'trygo' ); ?>
            </a>
            <p class="sidebar-footnote"><?php esc_html_e( 'Response within 24 hours', 'trygo' ); ?></p>
          </div>
        </aside>
      </div>
    </main>
  <?php endwhile; ?>
<?php endif; ?>

<style>
/* Additional styles for tools page */

.tool-description {
  margin: 1.5rem 0;
  padding: 1.5rem;
  background: linear-gradient(135deg, rgba(99, 102, 241, 0.05) 0%, rgba(139, 92, 246, 0.05) 100%);
  border-radius: 16px;
  border-left: 4px solid #6366f1;
}

.tool-description .lead {
  font-size: 1.125rem;
  line-height: 1.6;
  color: #374151;
  margin: 0;
  font-weight: 500;
}

.tool-features {
  margin: 2rem 0;
  padding: 1.5rem;
  background: #f8fafc;
  border-radius: 16px;
  border: 1px solid #e2e8f0;
}

.tool-features h3 {
  margin: 0 0 1rem;
  color: #1e293b;
  font-size: 1.25rem;
  font-weight: 600;
}

.features-list {
  list-style: none;
  padding: 0;
  margin: 0;
}

.features-list li {
  position: relative;
  padding: 0.5rem 0 0.5rem 2rem;
  color: #475569;
  line-height: 1.5;
}

.features-list li:before {
  content: "✓";
  position: absolute;
  left: 0;
  top: 0.5rem;
  color: #10b981;
  font-weight: bold;
  font-size: 1rem;
}

.tool-interface {
  margin: 2rem 0;
  padding: 2rem;
  background: #ffffff;
  border-radius: 20px;
  box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
  border: 1px solid #e2e8f0;
}

.tool-interface h3 {
  margin: 0 0 1.5rem;
  color: #1e293b;
  font-size: 1.5rem;
  font-weight: 600;
  text-align: center;
}

.tool-container {
  position: relative;
  min-height: 400px;
}

/* Responsive design */
@media (max-width: 768px) {
  .tool-description,
  .tool-features,
  .tool-interface {
    margin: 1rem 0;
    padding: 1rem;
  }
  
  .tool-interface h3 {
    font-size: 1.25rem;
  }
  
  .tool-container {
    min-height: 300px;
  }
}

/* Integration with existing theme styles */
.article-layout {
  display: grid;
  grid-template-columns: 1fr 300px;
  gap: 3rem;
  max-width: 1200px;
  margin: 0 auto;
  padding: 0 1rem;
}

@media (max-width: 1024px) {
  .article-layout {
    grid-template-columns: 1fr;
    gap: 2rem;
  }
}

.sidebar-card {
  background: #ffffff;
  padding: 2rem;
  border-radius: 16px;
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
  border: 1px solid #e2e8f0;
  margin-bottom: 1.5rem;
}

.sidebar-card .eyebrow {
  color: #6366f1;
  font-size: 0.875rem;
  font-weight: 600;
  text-transform: uppercase;
  letter-spacing: 0.05em;
  margin: 0 0 0.5rem;
}

.sidebar-card h2 {
  color: #1e293b;
  font-size: 1.25rem;
  font-weight: 600;
  margin: 0 0 1rem;
  line-height: 1.3;
}

.sidebar-card p {
  color: #64748b;
  line-height: 1.6;
  margin: 0 0 1.5rem;
}

.sidebar-list {
  list-style: none;
  padding: 0;
  margin: 0 0 1.5rem;
}

.sidebar-list li {
  position: relative;
  padding: 0.5rem 0 0.5rem 1.5rem;
  color: #475569;
  line-height: 1.5;
}

.sidebar-list li:before {
  content: "•";
  position: absolute;
  left: 0;
  top: 0.5rem;
  color: #6366f1;
  font-weight: bold;
}

.sidebar-button {
  display: inline-block;
  background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);
  color: white;
  text-decoration: none;
  padding: 0.75rem 1.5rem;
  border-radius: 12px;
  font-weight: 600;
  transition: all 0.2s ease;
  text-align: center;
  width: 100%;
  box-sizing: border-box;
}

.sidebar-button:hover {
  transform: translateY(-2px);
  box-shadow: 0 8px 16px rgba(99, 102, 241, 0.3);
  color: white;
  text-decoration: none;
}

.sidebar-footnote {
  font-size: 0.875rem;
  color: #94a3b8;
  margin: 1rem 0 0;
  text-align: center;
}
</style>

<?php
get_footer();
