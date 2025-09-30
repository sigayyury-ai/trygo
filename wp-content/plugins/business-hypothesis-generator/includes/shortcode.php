<?php
/**
 * Шорткод для TRYGO BusinessHypothesis Generator
 */

// Предотвращение прямого доступа
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Обработчик шорткода [business_hypothesis_generator]
 */
function business_hypothesis_shortcode($atts) {
    $atts = shortcode_atts(array(
        'title' => __('Генератор бизнес-гипотез', 'business-hypothesis-generator')
    ), $atts);
    
    ob_start();
    ?>
    <div class="business-hypothesis-generator">
        <!-- Экран ввода URL -->
        <div class="bhg-input-screen">
            <input 
                type="url" 
                id="websiteUrl" 
                class="bhg-url-input" 
                placeholder="https://example.com"
                value="https://trygo.com"
            >
            
            <button class="bhg-analyze-btn">
                Анализировать сайт
            </button>
        </div>
        
        <!-- Экран результатов -->
        <div class="bhg-results-screen">
            <div class="bhg-results-header">
                <h3><?php echo esc_html($atts['title']); ?></h3>
                <div class="bhg-analyzed-url">Анализ сайта: <span id="analyzedUrl">https://trygo.com</span></div>
            </div>
            
            <div class="bhg-hypotheses-list">
                <!-- Демо гипотезы будут загружены через JavaScript -->
            </div>
            
            <div class="bhg-action-buttons">
                <button class="bhg-new-analysis-btn">
                    Начать новый анализ
                </button>
            </div>
        </div>
    </div>
    <?php
    return ob_get_clean();
}
