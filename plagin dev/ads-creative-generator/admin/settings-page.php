<?php
// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

$api_key = get_option('ads_creative_generator_openai_api_key', '');
$daily_limit = get_option('ads_creative_generator_daily_limit', 3);
?>

<div class="wrap">
    <h1>Ads Creative Generator - Settings</h1>
    
    <form method="post" action="">
        <?php wp_nonce_field('ads_creative_generator_settings', 'ads_creative_generator_nonce'); ?>
        
        <table class="form-table">
            <tr>
                <th scope="row">
                    <label for="openai_api_key">OpenAI API Key</label>
                </th>
                <td>
                    <input type="password" 
                           id="openai_api_key" 
                           name="openai_api_key" 
                           value="<?php echo esc_attr($api_key); ?>" 
                           class="regular-text" 
                           placeholder="sk-proj-..." />
                    <p class="description">
                        Enter your OpenAI API key. You can get it from <a href="https://platform.openai.com/api-keys" target="_blank">OpenAI Platform</a>.
                    </p>
                </td>
            </tr>
            
            <tr>
                <th scope="row">
                    <label>Usage Tracking</label>
                </th>
                <td>
                    <p style="color: #666;">Usage tracking has been disabled to improve performance.</p>
                </td>
            </tr>
        </table>
        
        <?php submit_button('Save Settings'); ?>
    </form>
    
    <hr>
    
    <h2>How to Use the Plugin</h2>
    
    <div class="card">
        <h3>Display the Plugin on Your Website</h3>
        <p>You can display the Ads Creative Generator on any page or post using the following methods:</p>
        
        <h4>Method 1: Shortcode</h4>
        <p>Add this shortcode to any page or post:</p>
        <code>[ads_creative_generator]</code>
        
        <h4>Method 2: PHP Code</h4>
        <p>Add this code to your theme files (like page.php, single.php, etc.):</p>
        <pre><code>&lt;?php echo do_shortcode('[ads_creative_generator]'); ?&gt;</code></pre>
        
        <h4>Method 3: Widget (if supported by your theme)</h4>
        <p>Look for "Ads Creative Generator" in your widgets area and drag it to your desired location.</p>
        
        <h4>Method 4: Gutenberg Block</h4>
        <p>In the WordPress editor, search for "Ads Creative Generator" block and add it to your content.</p>
    </div>
    
    <div class="card">
        <h3>Plugin Features</h3>
        <ul>
            <li><strong>Website Analysis:</strong> Analyzes business websites to extract key insights</li>
            <li><strong>AI-Powered:</strong> Uses OpenAI to generate professional ad creatives</li>
            <li><strong>Multiple Categories:</strong> Creates creatives based on UVP, ICP, Pain Points, Triggers, Goals, and Benefits</li>
            <li><strong>Copy to Clipboard:</strong> Easy copying of generated creatives</li>
            <li><strong>Daily Limits:</strong> Configurable usage limits per user</li>
            <li><strong>Responsive Design:</strong> Works on all devices</li>
        </ul>
    </div>
    
    <div class="card">
        <h3>Analysis Results Include</h3>
        <ul>
            <li><strong>UVP (Unique Value Proposition):</strong> Main advantage that distinguishes from competitors</li>
            <li><strong>ICP (Ideal Customer Profile):</strong> Portrait of ideal buyer</li>
            <li><strong>Pain Points:</strong> Customer problems and frustrations</li>
            <li><strong>Triggers:</strong> Situations that prompt action</li>
            <li><strong>Goals:</strong> What customers want to achieve</li>
            <li><strong>Benefits:</strong> Value customers get from the product</li>
        </ul>
    </div>
    
    <div class="card">
        <h3>Generated Creatives Include</h3>
        <ul>
            <li><strong>Image Ideas:</strong> Visual concepts for ad images</li>
            <li><strong>CTA for Image:</strong> Call-to-action text for images</li>
            <li><strong>Headlines:</strong> Short, compelling headlines</li>
            <li><strong>Ad Text:</strong> 2-3 sentence ad copy with problem-solution-CTA structure</li>
        </ul>
    </div>
    
    <div class="card">
        <h3>Usage Statistics</h3>
        <p>Usage statistics have been disabled to improve performance.</p>
    </div>
</div>

<style>
.card {
    background: #fff;
    border: 1px solid #ccd0d4;
    border-radius: 4px;
    padding: 20px;
    margin: 20px 0;
    box-shadow: 0 1px 1px rgba(0,0,0,.04);
}

.card h3 {
    margin-top: 0;
    color: #23282d;
}

.card h4 {
    color: #0073aa;
    margin-bottom: 10px;
}

.card code {
    background: #f1f1f1;
    padding: 2px 6px;
    border-radius: 3px;
    font-family: Consolas, Monaco, monospace;
}

.card pre {
    background: #f1f1f1;
    padding: 15px;
    border-radius: 3px;
    overflow-x: auto;
    border-left: 4px solid #0073aa;
}

.card ul {
    margin-left: 20px;
}

.card li {
    margin-bottom: 8px;
}

.widefat {
    width: 100%;
    border-collapse: collapse;
}

.widefat td {
    padding: 10px;
    border-bottom: 1px solid #eee;
}

.widefat tr:last-child td {
    border-bottom: none;
}
</style>
