<?php
// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// Register shortcode
add_shortcode('ads_creative_generator', 'ads_creative_generator_shortcode');

function ads_creative_generator_shortcode($atts) {
    // Parse shortcode attributes
    $atts = shortcode_atts(array(
        'title' => 'Business analysis and ad creative ideas generation',
        'button_text' => 'Analyze Website',
        'placeholder' => 'example.com'
    ), $atts);
    
    // Allow all users to use the tool (no login required)
    
    // No usage tracking - no limit checks
    
    ob_start();
    ?>
    <div class="ads-creative-generator">
        <div class="container">
            <!-- SCREEN 1: Website URL input -->
            <div class="screen" id="screen1">
                
                <div class="form-group">
                    <label for="website-url">Website URL</label>
                    <input type="url" id="website-url" placeholder="<?php echo esc_attr($atts['placeholder']); ?>" required>
                </div>
                
                <button class="btn" id="analyze-btn">
                    <?php echo esc_html($atts['button_text']); ?>
                </button>
                
                <div class="loading" id="loading1">
                    <div class="spinner"></div>
                    <p>Analyzing your website with TRYGO...</p>
                </div>
            </div>
            
            <!-- SCREEN 2: Analysis results -->
            <div class="screen" id="screen2" style="display: none;">
                <button class="btn back-btn">Back to Analysis</button>
                
                <div class="analysis-result" id="analysis-result">
                    <div class="analysis-section">
                        <h3>UVP (Unique Value Proposition)</h3>
                        <p style="font-size: 0.9rem; color: #5f6c86; margin-bottom: 0.75rem; font-style: italic;">Main advantage of your product that distinguishes it from competitors</p>
                        <p id="uvp-text">Loading...</p>
                        
                        <!-- UVP Creatives -->
                        <div class="section-creatives" id="uvp-creatives" style="display: none;">
                            <h4 style="margin: 1.5rem 0 1rem; font-size: 1.1rem; color: #132039;">Creatives by UVP</h4>
                            <div class="mini-creatives">
                                <div class="mini-creative">
                                    <button class="mini-copy-btn" data-section="uvp" data-creative-number="1">Copy</button>
                                    <div class="mini-creative-content">
                                        <strong>Image:</strong> <span id="uvp-image-1">Loading...</span><br>
                                        <strong>CTA:</strong> <span id="uvp-cta-1">Loading...</span><br>
                                        <strong>Headline:</strong> <span id="uvp-headline-1">Loading...</span><br>
                                        <strong>Text:</strong> <span id="uvp-text-1">Loading...</span>
                                    </div>
                                </div>
                                <div class="mini-creative">
                                    <button class="mini-copy-btn" data-section="uvp" data-creative-number="2">Copy</button>
                                    <div class="mini-creative-content">
                                        <strong>Image:</strong> <span id="uvp-image-2">Loading...</span><br>
                                        <strong>CTA:</strong> <span id="uvp-cta-2">Loading...</span><br>
                                        <strong>Headline:</strong> <span id="uvp-headline-2">Loading...</span><br>
                                        <strong>Text:</strong> <span id="uvp-text-2">Loading...</span>
                                    </div>
                                </div>
                                <div class="mini-creative">
                                    <button class="mini-copy-btn" data-section="uvp" data-creative-number="3">Copy</button>
                                    <div class="mini-creative-content">
                                        <strong>Image:</strong> <span id="uvp-image-3">Loading...</span><br>
                                        <strong>CTA:</strong> <span id="uvp-cta-3">Loading...</span><br>
                                        <strong>Headline:</strong> <span id="uvp-headline-3">Loading...</span><br>
                                        <strong>Text:</strong> <span id="uvp-text-3">Loading...</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="analysis-section">
                        <h3>ICP (Ideal Customer Profile)</h3>
                        <p style="font-size: 0.9rem; color: #5f6c86; margin-bottom: 0.75rem; font-style: italic;">Portrait of your ideal buyer: age, profession, interests, needs</p>
                        <p id="icp-text">Loading...</p>
                        
                        <!-- ICP Creatives -->
                        <div class="section-creatives" id="icp-creatives" style="display: none;">
                            <h4 style="margin: 1.5rem 0 1rem; font-size: 1.1rem; color: #132039;">Creatives by ICP</h4>
                            <div class="mini-creatives">
                                <div class="mini-creative">
                                    <button class="mini-copy-btn" data-section="icp" data-creative-number="1">Copy</button>
                                    <div class="mini-creative-content">
                                        <strong>Image:</strong> <span id="icp-image-1">Loading...</span><br>
                                        <strong>CTA:</strong> <span id="icp-cta-1">Loading...</span><br>
                                        <strong>Headline:</strong> <span id="icp-headline-1">Loading...</span><br>
                                        <strong>Text:</strong> <span id="icp-text-1">Loading...</span>
                                    </div>
                                </div>
                                <div class="mini-creative">
                                    <button class="mini-copy-btn" data-section="icp" data-creative-number="2">Copy</button>
                                    <div class="mini-creative-content">
                                        <strong>Image:</strong> <span id="icp-image-2">Loading...</span><br>
                                        <strong>CTA:</strong> <span id="icp-cta-2">Loading...</span><br>
                                        <strong>Headline:</strong> <span id="icp-headline-2">Loading...</span><br>
                                        <strong>Text:</strong> <span id="icp-text-2">Loading...</span>
                                    </div>
                                </div>
                                <div class="mini-creative">
                                    <button class="mini-copy-btn" data-section="icp" data-creative-number="3">Copy</button>
                                    <div class="mini-creative-content">
                                        <strong>Image:</strong> <span id="icp-image-3">Loading...</span><br>
                                        <strong>CTA:</strong> <span id="icp-cta-3">Loading...</span><br>
                                        <strong>Headline:</strong> <span id="icp-headline-3">Loading...</span><br>
                                        <strong>Text:</strong> <span id="icp-text-3">Loading...</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="analysis-section">
                        <h3>Pain Points</h3>
                        <p style="font-size: 0.9rem; color: #5f6c86; margin-bottom: 0.75rem; font-style: italic;">Problems and frustrations that your customers face</p>
                        <p id="pain-text">Loading...</p>
                        
                        <!-- Pain Points Creatives -->
                        <div class="section-creatives" id="pain-creatives" style="display: none;">
                            <h4 style="margin: 1.5rem 0 1rem; font-size: 1.1rem; color: #132039;">Creatives by Pain Points</h4>
                            <div class="mini-creatives">
                                <div class="mini-creative">
                                    <button class="mini-copy-btn" data-section="pain" data-creative-number="1">Copy</button>
                                    <div class="mini-creative-content">
                                        <strong>Image:</strong> <span id="pain-image-1">Loading...</span><br>
                                        <strong>CTA:</strong> <span id="pain-cta-1">Loading...</span><br>
                                        <strong>Headline:</strong> <span id="pain-headline-1">Loading...</span><br>
                                        <strong>Text:</strong> <span id="pain-text-1">Loading...</span>
                                    </div>
                                </div>
                                <div class="mini-creative">
                                    <button class="mini-copy-btn" data-section="pain" data-creative-number="2">Copy</button>
                                    <div class="mini-creative-content">
                                        <strong>Image:</strong> <span id="pain-image-2">Loading...</span><br>
                                        <strong>CTA:</strong> <span id="pain-cta-2">Loading...</span><br>
                                        <strong>Headline:</strong> <span id="pain-headline-2">Loading...</span><br>
                                        <strong>Text:</strong> <span id="pain-text-2">Loading...</span>
                                    </div>
                                </div>
                                <div class="mini-creative">
                                    <button class="mini-copy-btn" data-section="pain" data-creative-number="3">Copy</button>
                                    <div class="mini-creative-content">
                                        <strong>Image:</strong> <span id="pain-image-3">Loading...</span><br>
                                        <strong>CTA:</strong> <span id="pain-cta-3">Loading...</span><br>
                                        <strong>Headline:</strong> <span id="pain-headline-3">Loading...</span><br>
                                        <strong>Text:</strong> <span id="pain-text-3">Loading...</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="analysis-section">
                        <h3>Triggers</h3>
                        <p style="font-size: 0.9rem; color: #5f6c86; margin-bottom: 0.75rem; font-style: italic;">Situations and events that prompt customers to take action</p>
                        <p id="triggers-text">Loading...</p>
                        
                        <!-- Triggers Creatives -->
                        <div class="section-creatives" id="triggers-creatives" style="display: none;">
                            <h4 style="margin: 1.5rem 0 1rem; font-size: 1.1rem; color: #132039;">Creatives by Triggers</h4>
                            <div class="mini-creatives">
                                <div class="mini-creative">
                                    <button class="mini-copy-btn" data-section="triggers" data-creative-number="1">Copy</button>
                                    <div class="mini-creative-content">
                                        <strong>Image:</strong> <span id="triggers-image-1">Loading...</span><br>
                                        <strong>CTA:</strong> <span id="triggers-cta-1">Loading...</span><br>
                                        <strong>Headline:</strong> <span id="triggers-headline-1">Loading...</span><br>
                                        <strong>Text:</strong> <span id="triggers-text-1">Loading...</span>
                                    </div>
                                </div>
                                <div class="mini-creative">
                                    <button class="mini-copy-btn" data-section="triggers" data-creative-number="2">Copy</button>
                                    <div class="mini-creative-content">
                                        <strong>Image:</strong> <span id="triggers-image-2">Loading...</span><br>
                                        <strong>CTA:</strong> <span id="triggers-cta-2">Loading...</span><br>
                                        <strong>Headline:</strong> <span id="triggers-headline-2">Loading...</span><br>
                                        <strong>Text:</strong> <span id="triggers-text-2">Loading...</span>
                                    </div>
                                </div>
                                <div class="mini-creative">
                                    <button class="mini-copy-btn" data-section="triggers" data-creative-number="3">Copy</button>
                                    <div class="mini-creative-content">
                                        <strong>Image:</strong> <span id="triggers-image-3">Loading...</span><br>
                                        <strong>CTA:</strong> <span id="triggers-cta-3">Loading...</span><br>
                                        <strong>Headline:</strong> <span id="triggers-headline-3">Loading...</span><br>
                                        <strong>Text:</strong> <span id="triggers-text-3">Loading...</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="analysis-section">
                        <h3>Goals</h3>
                        <p style="font-size: 0.9rem; color: #5f6c86; margin-bottom: 0.75rem; font-style: italic;">What customers want to achieve and their desired outcomes</p>
                        <p id="goals-text">Loading...</p>
                        
                        <!-- Goals Creatives -->
                        <div class="section-creatives" id="goals-creatives" style="display: none;">
                            <h4 style="margin: 1.5rem 0 1rem; font-size: 1.1rem; color: #132039;">Creatives by Goals</h4>
                            <div class="mini-creatives">
                                <div class="mini-creative">
                                    <button class="mini-copy-btn" data-section="goals" data-creative-number="1">Copy</button>
                                    <div class="mini-creative-content">
                                        <strong>Image:</strong> <span id="goals-image-1">Loading...</span><br>
                                        <strong>CTA:</strong> <span id="goals-cta-1">Loading...</span><br>
                                        <strong>Headline:</strong> <span id="goals-headline-1">Loading...</span><br>
                                        <strong>Text:</strong> <span id="goals-text-1">Loading...</span>
                                    </div>
                                </div>
                                <div class="mini-creative">
                                    <button class="mini-copy-btn" data-section="goals" data-creative-number="2">Copy</button>
                                    <div class="mini-creative-content">
                                        <strong>Image:</strong> <span id="goals-image-2">Loading...</span><br>
                                        <strong>CTA:</strong> <span id="goals-cta-2">Loading...</span><br>
                                        <strong>Headline:</strong> <span id="goals-headline-2">Loading...</span><br>
                                        <strong>Text:</strong> <span id="goals-text-2">Loading...</span>
                                    </div>
                                </div>
                                <div class="mini-creative">
                                    <button class="mini-copy-btn" data-section="goals" data-creative-number="3">Copy</button>
                                    <div class="mini-creative-content">
                                        <strong>Image:</strong> <span id="goals-image-3">Loading...</span><br>
                                        <strong>CTA:</strong> <span id="goals-cta-3">Loading...</span><br>
                                        <strong>Headline:</strong> <span id="goals-headline-3">Loading...</span><br>
                                        <strong>Text:</strong> <span id="goals-text-3">Loading...</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="analysis-section">
                        <h3>Benefits</h3>
                        <p style="font-size: 0.9rem; color: #5f6c86; margin-bottom: 0.75rem; font-style: italic;">Value and advantages customers get from using your product</p>
                        <p id="benefits-text">Loading...</p>
                        
                        <!-- Benefits Creatives -->
                        <div class="section-creatives" id="benefits-creatives" style="display: none;">
                            <h4 style="margin: 1.5rem 0 1rem; font-size: 1.1rem; color: #132039;">Creatives by Benefits</h4>
                            <div class="mini-creatives">
                                <div class="mini-creative">
                                    <button class="mini-copy-btn" data-section="benefits" data-creative-number="1">Copy</button>
                                    <div class="mini-creative-content">
                                        <strong>Image:</strong> <span id="benefits-image-1">Loading...</span><br>
                                        <strong>CTA:</strong> <span id="benefits-cta-1">Loading...</span><br>
                                        <strong>Headline:</strong> <span id="benefits-headline-1">Loading...</span><br>
                                        <strong>Text:</strong> <span id="benefits-text-1">Loading...</span>
                                    </div>
                                </div>
                                <div class="mini-creative">
                                    <button class="mini-copy-btn" data-section="benefits" data-creative-number="2">Copy</button>
                                    <div class="mini-creative-content">
                                        <strong>Image:</strong> <span id="benefits-image-2">Loading...</span><br>
                                        <strong>CTA:</strong> <span id="benefits-cta-2">Loading...</span><br>
                                        <strong>Headline:</strong> <span id="benefits-headline-2">Loading...</span><br>
                                        <strong>Text:</strong> <span id="benefits-text-2">Loading...</span>
                                    </div>
                                </div>
                                <div class="mini-creative">
                                    <button class="mini-copy-btn" data-section="benefits" data-creative-number="3">Copy</button>
                                    <div class="mini-creative-content">
                                        <strong>Image:</strong> <span id="benefits-image-3">Loading...</span><br>
                                        <strong>CTA:</strong> <span id="benefits-cta-3">Loading...</span><br>
                                        <strong>Headline:</strong> <span id="benefits-headline-3">Loading...</span><br>
                                        <strong>Text:</strong> <span id="benefits-text-3">Loading...</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="loading" id="loading2">
                    <div class="spinner"></div>
                    <p>Generating analysis with TRYGO...</p>
                </div>
            </div>
        </div>
    </div>
    <?php
    return ob_get_clean();
}

// Helper function to check daily limit - DISABLED to avoid database errors
function ads_creative_generator_check_daily_limit() {
    // Function disabled to avoid database errors
    return true; // Always allow analysis
}
