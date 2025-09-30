<?php
/**
 * Template for Tools Archive page
 * Template Name: Tools Archive
 */

get_header();
?>

<main id="tools-archive-root" aria-live="polite">
  <div class="tools-hero">
    <div class="container">
      <h1><?php esc_html_e( 'AI Tools & Resources', 'trygo' ); ?></h1>
      <p class="hero-subtitle"><?php esc_html_e( 'Powerful AI-driven tools to accelerate your business growth and marketing success.', 'trygo' ); ?></p>
    </div>
  </div>

  <div class="tools-content">
    <div class="container">
      <div class="tools-grid">
        
        <!-- Ads Creative Generator Tool -->
        <div class="tool-card">
          <div class="tool-icon">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <path d="M12 2L2 7l10 5 10-5-10-5z"/>
              <path d="M2 17l10 5 10-5"/>
              <path d="M2 12l10 5 10-5"/>
            </svg>
          </div>
          <div class="tool-content">
            <h3><?php esc_html_e( 'Ads Creative Generator', 'trygo' ); ?></h3>
            <p><?php esc_html_e( 'Analyze business websites and generate AI-powered ad creative ideas with compelling headlines, CTAs, and ad copy.', 'trygo' ); ?></p>
            <ul class="tool-features">
              <li><?php esc_html_e( 'Website Analysis', 'trygo' ); ?></li>
              <li><?php esc_html_e( 'AI-Powered Creatives', 'trygo' ); ?></li>
              <li><?php esc_html_e( 'Multiple Categories', 'trygo' ); ?></li>
              <li><?php esc_html_e( 'Copy to Clipboard', 'trygo' ); ?></li>
            </ul>
            <a href="<?php echo esc_url( home_url( '/ads-creative-generator/' ) ); ?>" class="tool-button">
              <?php esc_html_e( 'Try Now', 'trygo' ); ?>
            </a>
          </div>
        </div>

        <!-- Coming Soon Tools -->
        <div class="tool-card coming-soon">
          <div class="tool-icon">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <path d="M9 12l2 2 4-4"/>
              <path d="M21 12c-1 0-3-1-3-3s2-3 3-3 3 1 3 3-2 3-3 3"/>
              <path d="M3 12c1 0 3-1 3-3s-2-3-3-3-3 1-3 3 2 3 3 3"/>
              <path d="M12 3c0 1-1 3-3 3s-3-2-3-3 1-3 3-3 3 2 3 3"/>
              <path d="M12 21c0-1 1-3 3-3s3 2 3 3-1 3-3 3-3-2-3-3"/>
            </svg>
          </div>
          <div class="tool-content">
            <h3><?php esc_html_e( 'Business Plan Generator', 'trygo' ); ?></h3>
            <p><?php esc_html_e( 'Generate comprehensive business plans with market analysis, financial projections, and growth strategies.', 'trygo' ); ?></p>
            <div class="coming-soon-badge"><?php esc_html_e( 'Coming Soon', 'trygo' ); ?></div>
          </div>
        </div>

        <div class="tool-card coming-soon">
          <div class="tool-icon">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
              <polyline points="14,2 14,8 20,8"/>
              <line x1="16" y1="13" x2="8" y2="13"/>
              <line x1="16" y1="17" x2="8" y2="17"/>
              <polyline points="10,9 9,9 8,9"/>
            </svg>
          </div>
          <div class="tool-content">
            <h3><?php esc_html_e( 'Content Strategy Builder', 'trygo' ); ?></h3>
            <p><?php esc_html_e( 'Create data-driven content strategies with topic research, content calendars, and performance predictions.', 'trygo' ); ?></p>
            <div class="coming-soon-badge"><?php esc_html_e( 'Coming Soon', 'trygo' ); ?></div>
          </div>
        </div>

        <div class="tool-card coming-soon">
          <div class="tool-icon">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <path d="M3 3h18v18H3zM9 9h6v6H9z"/>
              <path d="M9 3v6"/>
              <path d="M15 3v6"/>
              <path d="M3 9h6"/>
              <path d="M15 9h6"/>
            </svg>
          </div>
          <div class="tool-content">
            <h3><?php esc_html_e( 'Social Media Scheduler', 'trygo' ); ?></h3>
            <p><?php esc_html_e( 'AI-powered social media scheduling with optimal timing, hashtag suggestions, and engagement predictions.', 'trygo' ); ?></p>
            <div class="coming-soon-badge"><?php esc_html_e( 'Coming Soon', 'trygo' ); ?></div>
          </div>
        </div>

        <div class="tool-card coming-soon">
          <div class="tool-icon">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <path d="M12 2v20M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/>
            </svg>
          </div>
          <div class="tool-content">
            <h3><?php esc_html_e( 'Pricing Optimizer', 'trygo' ); ?></h3>
            <p><?php esc_html_e( 'Optimize your pricing strategy with AI analysis of market conditions, competitor pricing, and customer behavior.', 'trygo' ); ?></p>
            <div class="coming-soon-badge"><?php esc_html_e( 'Coming Soon', 'trygo' ); ?></div>
          </div>
        </div>

        <div class="tool-card coming-soon">
          <div class="tool-icon">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"/>
              <rect x="8" y="2" width="8" height="4" rx="1" ry="1"/>
            </svg>
          </div>
          <div class="tool-content">
            <h3><?php esc_html_e( 'Email Campaign Builder', 'trygo' ); ?></h3>
            <p><?php esc_html_e( 'Create high-converting email campaigns with AI-generated subject lines, content, and send-time optimization.', 'trygo' ); ?></p>
            <div class="coming-soon-badge"><?php esc_html_e( 'Coming Soon', 'trygo' ); ?></div>
          </div>
        </div>

      </div>
    </div>
  </div>

  <div class="tools-cta">
    <div class="container">
      <div class="cta-content">
        <h2><?php esc_html_e( 'Need a Custom AI Tool?', 'trygo' ); ?></h2>
        <p><?php esc_html_e( 'We can build custom AI solutions tailored to your specific business needs and workflows.', 'trygo' ); ?></p>
        <a href="<?php echo esc_url( home_url( '/contact/' ) ); ?>" class="cta-button">
          <?php esc_html_e( 'Get Custom Solution', 'trygo' ); ?>
        </a>
      </div>
    </div>
  </div>
</main>

<style>
/* Tools Archive Styles */
.tools-hero {
  background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);
  color: white;
  padding: 4rem 0;
  text-align: center;
}

.tools-hero h1 {
  font-size: 3rem;
  font-weight: 700;
  margin: 0 0 1rem;
  line-height: 1.1;
}

.tools-hero .hero-subtitle {
  font-size: 1.25rem;
  opacity: 0.9;
  margin: 0;
  max-width: 600px;
  margin-left: auto;
  margin-right: auto;
}

.tools-content {
  padding: 4rem 0;
  background: #f8fafc;
}

.container {
  max-width: 1200px;
  margin: 0 auto;
  padding: 0 1rem;
}

.tools-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
  gap: 2rem;
}

.tool-card {
  background: white;
  border-radius: 20px;
  padding: 2rem;
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
  border: 1px solid #e2e8f0;
  transition: all 0.3s ease;
  position: relative;
  overflow: hidden;
}

.tool-card:hover {
  transform: translateY(-4px);
  box-shadow: 0 20px 25px rgba(0, 0, 0, 0.1);
}

.tool-card.coming-soon {
  opacity: 0.7;
  cursor: not-allowed;
}

.tool-card.coming-soon:hover {
  transform: none;
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
}

.tool-icon {
  width: 60px;
  height: 60px;
  background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);
  border-radius: 16px;
  display: flex;
  align-items: center;
  justify-content: center;
  margin-bottom: 1.5rem;
  color: white;
}

.tool-icon svg {
  width: 28px;
  height: 28px;
}

.tool-content h3 {
  font-size: 1.5rem;
  font-weight: 600;
  color: #1e293b;
  margin: 0 0 1rem;
  line-height: 1.3;
}

.tool-content p {
  color: #64748b;
  line-height: 1.6;
  margin: 0 0 1.5rem;
}

.tool-features {
  list-style: none;
  padding: 0;
  margin: 0 0 2rem;
}

.tool-features li {
  position: relative;
  padding: 0.25rem 0 0.25rem 1.5rem;
  color: #475569;
  font-size: 0.9rem;
}

.tool-features li:before {
  content: "✓";
  position: absolute;
  left: 0;
  top: 0.25rem;
  color: #10b981;
  font-weight: bold;
}

.tool-button {
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

.tool-button:hover {
  transform: translateY(-2px);
  box-shadow: 0 8px 16px rgba(99, 102, 241, 0.3);
  color: white;
  text-decoration: none;
}

.coming-soon-badge {
  position: absolute;
  top: 1rem;
  right: 1rem;
  background: #f59e0b;
  color: white;
  padding: 0.25rem 0.75rem;
  border-radius: 12px;
  font-size: 0.75rem;
  font-weight: 600;
  text-transform: uppercase;
  letter-spacing: 0.05em;
}

.tools-cta {
  background: #1e293b;
  color: white;
  padding: 4rem 0;
  text-align: center;
}

.cta-content h2 {
  font-size: 2.5rem;
  font-weight: 700;
  margin: 0 0 1rem;
  line-height: 1.2;
}

.cta-content p {
  font-size: 1.125rem;
  opacity: 0.9;
  margin: 0 0 2rem;
  max-width: 600px;
  margin-left: auto;
  margin-right: auto;
}

.cta-button {
  display: inline-block;
  background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);
  color: white;
  text-decoration: none;
  padding: 1rem 2rem;
  border-radius: 16px;
  font-weight: 600;
  font-size: 1.125rem;
  transition: all 0.2s ease;
}

.cta-button:hover {
  transform: translateY(-2px);
  box-shadow: 0 12px 24px rgba(99, 102, 241, 0.4);
  color: white;
  text-decoration: none;
}

/* Responsive Design */
@media (max-width: 768px) {
  .tools-hero h1 {
    font-size: 2rem;
  }
  
  .tools-hero .hero-subtitle {
    font-size: 1rem;
  }
  
  .tools-grid {
    grid-template-columns: 1fr;
    gap: 1.5rem;
  }
  
  .tool-card {
    padding: 1.5rem;
  }
  
  .cta-content h2 {
    font-size: 2rem;
  }
  
  .cta-content p {
    font-size: 1rem;
  }
}
</style>

<?php
get_footer();
